<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EmailVerificationCodeController extends Controller
{
    /**
     * Show email verification form
     */
    public function show()
    {
        $email = session('email_to_verify');
        
        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Verification session expired. Please register again.');
        }
        
        return view('auth.verify-email-code');
    }

    /**
     * Verify email with code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6|regex:/^[0-9]{6}$/',
            'remember_device' => 'nullable|boolean'
        ], [
            'code.required' => 'Please enter the verification code.',
            'code.size' => 'The code must be exactly 6 digits.',
            'code.regex' => 'The code must contain only numbers.'
        ]);
        
        $email = session('email_to_verify');
        
        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Verification session expired. Please register again.');
        }
        
        // Get code from cache
        $cacheKey = "email_verification_{$email}";
        $storedCode = Cache::get($cacheKey);
        
        if (!$storedCode) {
            return back()->withErrors([
                'code' => 'Verification code has expired. Please request a new code.'
            ]);
        }
        
        // Verify code
        if ($storedCode !== $request->code) {
            Log::warning('Email verification failed - invalid code', [
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            return back()->withErrors([
                'code' => 'Invalid verification code. Please try again.'
            ]);
        }
        
        // Code is valid - verify user's email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'User not found. Please register again.');
        }
        
        // Mark email as verified
        $user->update([
            'email_verified_at' => now()
        ]);
        
        // Clear the code
        Cache::forget($cacheKey);
        
        // Clear session
        session()->forget('email_to_verify');
        
        // Log in the user
        Auth::login($user);
        
        // Handle "remember device" option
        $rememberDevice = $request->boolean('remember_device');
        if ($rememberDevice) {
            $deviceToken = bin2hex(random_bytes(32));
            $expiresAt = now()->addDays(30);
            
            // Store device token in cache (30 days)
            Cache::put(
                "registration_device_{$user->id}_{$deviceToken}",
                [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now()
                ],
                $expiresAt
            );
            
            // Set cookie for 30 days
            cookie()->queue(
                'registration_device_token',
                $deviceToken,
                60 * 24 * 30, // 30 days in minutes
                '/',
                null,
                true, // secure
                true, // httpOnly
                false,
                'Lax'
            );
            
            Log::info('Registration device remembered', [
                'user_id' => $user->id,
                'expires_at' => $expiresAt->toDateTimeString()
            ]);
        }
        
        Log::info('Email verification successful', [
            'user_id' => $user->id,
            'email' => $email,
            'ip' => $request->ip(),
            'device_remembered' => $rememberDevice
        ]);
        
        // Redirect based on role
        $redirectRoute = $user->hasAdminAccess() ? route('dashboard') : route('dashboard.user');
        
        $message = $rememberDevice 
            ? 'Email verified successfully! This device will be trusted for 30 days. Welcome to Salenga Farm!'
            : 'Email verified successfully! Welcome to Salenga Farm.';
        
        return redirect($redirectRoute)->with('success', $message);
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request)
    {
        $email = session('email_to_verify');
        
        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Verification session expired. Please register again.');
        }
        
        // Generate new code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store in cache for 10 minutes
        $cacheKey = "email_verification_{$email}";
        Cache::put($cacheKey, $code, now()->addMinutes(10));
        
        // Send email
        $user = User::where('email', $email)->first();
        $brevoService = new \App\Services\BrevoEmailService();
        $sent = $brevoService->sendRegistrationCode(
            $email,
            $code,
            $user->first_name ?? 'User'
        );
        
        if ($sent) {
            Log::info('Verification code resent', [
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            return back()->with('success', 'A new verification code has been sent to your email.');
        }
        
        return back()->withErrors([
            'code' => 'Failed to send verification code. Please try again.'
        ]);
    }
}
