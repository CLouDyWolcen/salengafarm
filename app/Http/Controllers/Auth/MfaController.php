<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MfaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MfaController extends Controller
{
    protected $mfaService;

    public function __construct(MfaService $mfaService)
    {
        $this->mfaService = $mfaService;
    }

    /**
     * Show MFA verification form
     */
    public function showVerify()
    {
        $user = Auth::user();
        
        if (!$user || !$user->mfa_enabled) {
            // Redirect to appropriate dashboard based on role
            $redirectRoute = $user && $user->hasAdminAccess() ? route('dashboard') : route('dashboard.user');
            return redirect($redirectRoute);
        }
        
        // Check if locked out
        if ($this->mfaService->isLockedOut($user)) {
            $minutes = $this->mfaService->getLockoutMinutes($user);
            return view('auth.mfa.verify', [
                'lockedOut' => true,
                'lockoutMinutes' => $minutes
            ]);
        }
        
        return view('auth.mfa.verify', ['lockedOut' => false]);
    }

    /**
     * Verify MFA code
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
        
        $user = Auth::user();
        
        if (!$user || !$user->mfa_enabled) {
            return redirect()->route('dashboard');
        }
        
        // Check lockout
        if ($this->mfaService->isLockedOut($user)) {
            $minutes = $this->mfaService->getLockoutMinutes($user);
            return back()->withErrors([
                'code' => "Too many failed attempts. Please try again in {$minutes} minutes."
            ])->withInput();
        }
        
        // Verify code with remember device option
        $rememberDevice = $request->boolean('remember_device');
        $valid = $this->mfaService->verifyCode($user, $request->code, $rememberDevice);
        
        if ($valid) {
            // Determine redirect based on user role
            $redirectRoute = $user->hasAdminAccess() ? route('dashboard') : route('dashboard.user');
            
            $message = 'Verification successful! Welcome back.';
            if ($rememberDevice) {
                $message = 'Verification successful! This device will be trusted for 30 days.';
            }
            
            return redirect()->intended($redirectRoute)
                ->with('success', $message);
        }
        
        return back()->withErrors([
            'code' => 'Invalid verification code. Please try again.'
        ])->withInput();
    }

    /**
     * Resend MFA code
     */
    public function resend()
    {
        $user = Auth::user();
        
        if (!$user || !$user->mfa_enabled) {
            // Redirect to appropriate dashboard based on role
            $redirectRoute = $user && $user->hasAdminAccess() ? route('dashboard') : route('dashboard.user');
            return redirect($redirectRoute);
        }
        
        // Check rate limiting
        $canResend = $this->mfaService->canResendCode($user);
        
        if (!$canResend['can_resend']) {
            return back()->withErrors([
                'code' => $canResend['message']
            ]);
        }
        
        // Record resend
        $this->mfaService->recordResend($user);
        
        // Send new code
        $sent = $this->mfaService->sendCode($user);
        
        if ($sent) {
            return back()->with('success', 'A new verification code has been sent to your email.');
        }
        
        return back()->withErrors([
            'code' => 'Failed to send verification code. Please try again.'
        ]);
    }

    /**
     * Show enable MFA form (in profile settings)
     */
    public function showEnable()
    {
        return view('auth.mfa.enable');
    }

    /**
     * Enable MFA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);
        
        $user = Auth::user();
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.'
            ])->withInput();
        }
        
        $this->mfaService->enableMfa($user);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Two-factor authentication has been enabled successfully.');
    }

    /**
     * Disable MFA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);
        
        $user = Auth::user();
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.'
            ])->withInput();
        }
        
        $this->mfaService->disableMfa($user);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Two-factor authentication has been disabled.');
    }
}
