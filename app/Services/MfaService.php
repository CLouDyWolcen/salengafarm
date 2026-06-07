<?php

namespace App\Services;

use App\Models\User;
use App\Models\MfaAttempt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MfaService
{
    protected $brevoService;

    public function __construct(BrevoEmailService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    /**
     * Generate and send MFA code via email
     */
    public function sendCode(User $user): bool
    {
        // Generate 6-digit code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store in cache for 5 minutes
        $cacheKey = "mfa_code_{$user->id}";
        Cache::put($cacheKey, $code, now()->addMinutes(5));
        
        // Store send attempt count (for rate limiting resends)
        $attemptKey = "mfa_send_attempts_{$user->id}";
        $attempts = Cache::get($attemptKey, 0) + 1;
        Cache::put($attemptKey, $attempts, now()->addMinutes(15));
        
        // Send via Brevo
        $sent = $this->brevoService->sendMfaCode(
            $user->email,
            $code,
            $user->first_name ?? $user->email
        );
        
        if ($sent) {
            Log::info('MFA code generated and sent', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);
        }
        
        return $sent;
    }

    /**
     * Verify MFA code
     */
    public function verifyCode(User $user, string $code, bool $rememberDevice = false): bool
    {
        $cacheKey = "mfa_code_{$user->id}";
        $storedCode = Cache::get($cacheKey);
        
        // Check if code matches
        $success = $storedCode && $storedCode === $code;
        
        // Log attempt
        $this->logAttempt($user, $success);
        
        if ($success) {
            // Clear the code (single use)
            Cache::forget($cacheKey);
            
            // Set session as verified
            session([
                'mfa_verified' => true,
                'mfa_verified_at' => now(),
                'mfa_verified_for_user' => $user->id
            ]);
            
            // If remember device is enabled, set long-term cookie
            if ($rememberDevice) {
                $deviceToken = bin2hex(random_bytes(32));
                $expiresAt = now()->addDays(30);
                
                // Store device token in cache (30 days)
                Cache::put(
                    "mfa_device_{$user->id}_{$deviceToken}",
                    [
                        'user_id' => $user->id,
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'created_at' => now()
                    ],
                    $expiresAt
                );
                
                // Set cookie for 30 days
                cookie()->queue(
                    'mfa_device_token',
                    $deviceToken,
                    60 * 24 * 30, // 30 days in minutes
                    '/',
                    null,
                    true, // secure
                    true, // httpOnly
                    false,
                    'Lax'
                );
                
                Log::info('MFA device remembered', [
                    'user_id' => $user->id,
                    'expires_at' => $expiresAt->toDateTimeString()
                ]);
            }
            
            Log::info('MFA verification successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
                'device_remembered' => $rememberDevice
            ]);
        } else {
            Log::warning('MFA verification failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
                'provided_code' => $code,
                'code_exists' => !empty($storedCode)
            ]);
        }
        
        return $success;
    }

    /**
     * Enable MFA for user
     */
    public function enableMfa(User $user): bool
    {
        $user->update([
            'mfa_enabled' => true,
            'mfa_enabled_at' => now()
        ]);
        
        Log::info('MFA enabled for user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);
        
        return true;
    }

    /**
     * Disable MFA for user
     */
    public function disableMfa(User $user): bool
    {
        $user->update([
            'mfa_enabled' => false,
            'mfa_enabled_at' => null
        ]);
        
        // Clear any existing codes
        $cacheKey = "mfa_code_{$user->id}";
        Cache::forget($cacheKey);
        
        Log::info('MFA disabled for user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);
        
        return true;
    }

    /**
     * Check if user is locked out due to failed attempts
     */
    public function isLockedOut(User $user): bool
    {
        $key = "mfa_lockout_{$user->id}";
        return Cache::has($key);
    }

    /**
     * Get remaining lockout time in minutes
     */
    public function getLockoutMinutes(User $user): int
    {
        $key = "mfa_lockout_{$user->id}";
        $expiresAt = Cache::get($key);
        
        if (!$expiresAt) {
            return 0;
        }
        
        $minutes = now()->diffInMinutes($expiresAt, false);
        return max(0, (int) ceil($minutes));
    }

    /**
     * Check if user has too many failed attempts and lockout if needed
     */
    protected function checkAndLockout(User $user): void
    {
        // Count recent failures (last 15 minutes)
        $recentFailures = MfaAttempt::where('user_id', $user->id)
            ->where('success', false)
            ->where('attempted_at', '>=', now()->subMinutes(15))
            ->count();
        
        // Lock out after 5 failed attempts
        if ($recentFailures >= 5) {
            $lockoutKey = "mfa_lockout_{$user->id}";
            $expiresAt = now()->addMinutes(15);
            Cache::put($lockoutKey, $expiresAt, $expiresAt);
            
            Log::warning('User locked out due to failed MFA attempts', [
                'user_id' => $user->id,
                'email' => $user->email,
                'failed_attempts' => $recentFailures,
                'lockout_until' => $expiresAt->toDateTimeString(),
                'ip' => request()->ip()
            ]);
        }
    }

    /**
     * Log MFA attempt
     */
    protected function logAttempt(User $user, bool $success): void
    {
        MfaAttempt::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'success' => $success,
            'user_agent' => request()->userAgent(),
            'attempted_at' => now()
        ]);
        
        if (!$success) {
            $this->checkAndLockout($user);
        }
    }

    /**
     * Check if MFA session is still valid
     */
    public function isSessionValid(): bool
    {
        if (!session('mfa_verified')) {
            return false;
        }
        
        $verifiedAt = session('mfa_verified_at');
        if (!$verifiedAt) {
            return false;
        }
        
        // Session valid for 2 hours (120 minutes)
        $timeout = config('mfa.session_timeout', 120);
        $minutesSinceVerification = now()->diffInMinutes($verifiedAt);
        
        return $minutesSinceVerification < $timeout;
    }

    /**
     * Check if device is remembered (30-day trust)
     */
    public function isDeviceRemembered(User $user): bool
    {
        $deviceToken = request()->cookie('mfa_device_token');
        
        if (!$deviceToken) {
            return false;
        }
        
        $cacheKey = "mfa_device_{$user->id}_{$deviceToken}";
        $deviceData = Cache::get($cacheKey);
        
        if (!$deviceData) {
            return false;
        }
        
        // Verify device data matches current request
        if ($deviceData['user_id'] !== $user->id) {
            return false;
        }
        
        Log::info('MFA device recognized', [
            'user_id' => $user->id,
            'device_created' => $deviceData['created_at']
        ]);
        
        return true;
    }

    /**
     * Forget remembered device
     */
    public function forgetDevice(User $user): void
    {
        $deviceToken = request()->cookie('mfa_device_token');
        
        if ($deviceToken) {
            $cacheKey = "mfa_device_{$user->id}_{$deviceToken}";
            Cache::forget($cacheKey);
            
            // Clear cookie
            cookie()->queue(cookie()->forget('mfa_device_token'));
            
            Log::info('MFA device forgotten', [
                'user_id' => $user->id
            ]);
        }
    }

    /**
     * Clear MFA session
     */
    public function clearSession(): void
    {
        session()->forget(['mfa_verified', 'mfa_verified_at', 'mfa_verified_for_user']);
    }

    /**
     * Check if user can resend code (rate limiting)
     */
    public function canResendCode(User $user): array
    {
        $key = "mfa_resend_{$user->id}";
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= 3) {
            return [
                'can_resend' => false,
                'message' => 'Too many code requests. Please wait 15 minutes.'
            ];
        }
        
        return [
            'can_resend' => true,
            'attempts_remaining' => 3 - $attempts
        ];
    }

    /**
     * Record resend attempt
     */
    public function recordResend(User $user): void
    {
        $key = "mfa_resend_{$user->id}";
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes(15));
    }
}
