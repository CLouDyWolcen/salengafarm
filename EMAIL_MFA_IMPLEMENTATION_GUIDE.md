# Email-Based MFA Implementation Guide

## Overview
Simple Multi-Factor Authentication using email verification codes sent via Brevo. Perfect for clients who just want to buy plants without downloading apps!

## How It Works

### User Experience:

**1. Login:**
- User enters email + password
- System sends 6-digit code to their email
- User checks email and enters code
- Access granted!

**2. Setup (Optional - Admin/Client can enable):**
- Go to Profile → Security Settings
- Toggle "Enable Two-Factor Authentication"
- Done! Next login will require email code.

**3. Disable:**
- Go to Profile → Security Settings
- Toggle off "Enable Two-Factor Authentication"
- Confirm password
- Done!

## Installation Steps

### 1. Run Database Migrations
```bash
php artisan migrate
```

This creates:
- `mfa_enabled` column in users table
- `mfa_enabled_at` timestamp in users table  
- `mfa_attempts` table for security tracking

### 2. No Additional Packages Needed!
- Uses existing Brevo integration ✅
- Uses Laravel Cache for temporary code storage ✅
- Uses existing BrevoEmailService ✅

## Database Schema

### Users Table (New Columns)
- `mfa_enabled` (boolean): Whether MFA is enabled
- `mfa_enabled_at` (timestamp): When MFA was enabled

### MFA Attempts Table
- Tracks all login attempts
- Records success/failure
- Stores IP address and user agent
- Used for security monitoring and rate limiting

## Components to Implement

### 1. Update BrevoEmailService

Add method to send MFA codes:

```php
// In app/Services/BrevoEmailService.php

public function sendMfaCode(string $email, string $code, string $userName = 'User')
{
    try {
        $sendSmtpEmail = new SendSmtpEmail([
            'to' => [['email' => $email, 'name' => $userName]],
            'sender' => [
                'email' => config('mail.from.address'),
                'name' => config('mail.from.name')
            ],
            'subject' => 'Your Verification Code - Salenga Farm',
            'htmlContent' => view('emails.mfa-code', [
                'code' => $code,
                'userName' => $userName,
                'expiresIn' => 5 // minutes
            ])->render()
        ]);

        $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
        
        Log::info('MFA code sent successfully', [
            'email' => $email,
            'message_id' => $result->getMessageId()
        ]);

        return true;
    } catch (Exception $e) {
        Log::error('Failed to send MFA code', [
            'email' => $email,
            'error' => $e->getMessage()
        ]);
        return false;
    }
}
```

### 2. Create MFA Service

Create `app/Services/MfaService.php`:

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\MfaAttempt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
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
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store in cache for 5 minutes
        $cacheKey = "mfa_code_{$user->id}";
        Cache::put($cacheKey, $code, now()->addMinutes(5));
        
        // Store attempt count
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
            // Log the send (not the code!)
            activity()
                ->causedBy($user)
                ->log('MFA code sent to email');
        }
        
        return $sent;
    }

    /**
     * Verify MFA code
     */
    public function verifyCode(User $user, string $code): bool
    {
        $cacheKey = "mfa_code_{$user->id}";
        $storedCode = Cache::get($cacheKey);
        
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
        
        activity()
            ->causedBy($user)
            ->log('MFA enabled');
        
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
        
        activity()
            ->causedBy($user)
            ->log('MFA disabled');
        
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
        
        return max(0, now()->diffInMinutes($expiresAt, false));
    }

    /**
     * Check if user has too many failed attempts
     */
    protected function checkAndLockout(User $user): void
    {
        $recentFailures = MfaAttempt::where('user_id', $user->id)
            ->where('success', false)
            ->where('attempted_at', '>=', now()->subMinutes(15))
            ->count();
        
        if ($recentFailures >= 5) {
            $lockoutKey = "mfa_lockout_{$user->id}";
            $expiresAt = now()->addMinutes(15);
            Cache::put($lockoutKey, $expiresAt, $expiresAt);
            
            activity()
                ->causedBy($user)
                ->log('MFA account locked due to failed attempts');
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
        
        // Session valid for 2 hours
        $timeout = config('mfa.session_timeout', 120);
        return now()->diffInMinutes($verifiedAt) < $timeout;
    }
}
```

### 3. Create MFA Controller

Create `app/Http/Controllers/Auth/MfaController.php`:

```php
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
            return redirect()->route('dashboard');
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
            'code' => 'required|string|size:6'
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
            ]);
        }
        
        // Verify code
        $valid = $this->mfaService->verifyCode($user, $request->code);
        
        if ($valid) {
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Verification successful! Welcome back.');
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
            return redirect()->route('dashboard');
        }
        
        // Rate limit resends (max 3 per 15 minutes)
        $key = "mfa_resend_{$user->id}";
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 3) {
            return back()->withErrors([
                'code' => 'Too many code requests. Please wait 15 minutes.'
            ]);
        }
        
        cache()->put($key, $attempts + 1, now()->addMinutes(15));
        
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
     * Show enable MFA form
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
            ]);
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
            ]);
        }
        
        $this->mfaService->disableMfa($user);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Two-factor authentication has been disabled.');
    }
}
```

### 4. Create MFA Middleware

Create `app/Http/Middleware/RequireMfaVerification.php`:

```php
<?php

namespace App\Http\Middleware;

use App\Services\MfaService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireMfaVerification
{
    protected $mfaService;

    public function __construct(MfaService $mfaService)
    {
        $this->mfaService = $mfaService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Skip if not authenticated
        if (!Auth::check()) {
            return $next($request);
        }
        
        $user = Auth::user();
        
        // Skip if MFA not enabled
        if (!$user->mfa_enabled) {
            return $next($request);
        }
        
        // Skip MFA routes themselves
        if ($request->routeIs('mfa.*')) {
            return $next($request);
        }
        
        // Skip logout route
        if ($request->routeIs('logout')) {
            return $next($request);
        }
        
        // Check if already verified
        if ($this->mfaService->isSessionValid()) {
            return $next($request);
        }
        
        // Redirect to MFA verification
        return redirect()->route('mfa.verify');
    }
}
```

### 5. Add Routes

In `routes/web.php`:

```php
// MFA Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/mfa/verify', [MfaController::class, 'showVerify'])->name('mfa.verify');
    Route::post('/mfa/verify', [MfaController::class, 'verify'])->name('mfa.verify.post');
    Route::post('/mfa/resend', [MfaController::class, 'resend'])->name('mfa.resend');
    
    Route::get('/mfa/enable', [MfaController::class, 'showEnable'])->name('mfa.enable');
    Route::post('/mfa/enable', [MfaController::class, 'enable'])->name('mfa.enable.post');
    Route::post('/mfa/disable', [MfaController::class, 'disable'])->name('mfa.disable');
});
```

### 6. Update Login Controller

In `app/Http/Controllers/Auth/AuthenticatedSessionController.php`, modify the `store` method:

```php
public function store(LoginRequest $request)
{
    $request->authenticate();
    $request->session()->regenerate();
    
    $user = Auth::user();
    
    // If MFA is enabled, send code and redirect to verification
    if ($user->mfa_enabled) {
        $mfaService = app(MfaService::class);
        $mfaService->sendCode($user);
        
        return redirect()->route('mfa.verify')
            ->with('success', 'A verification code has been sent to your email.');
    }
    
    // Normal login flow
    return redirect()->intended(route('dashboard', absolute: false));
}
```

### 7. Register Middleware

In `bootstrap/app.php` or `app/Http/Kernel.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(RequireMfaVerification::class);
})
```

### 8. Create Email Template

Create `resources/views/emails/mfa-code.blade.php`:

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #198754;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .code-box {
            background-color: white;
            border: 2px dashed #198754;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Salenga Farm</h1>
            <p>Verification Code</p>
        </div>
        <div class="content">
            <p>Hello {{ $userName }},</p>
            <p>Your verification code is:</p>
            
            <div class="code-box">
                {{ $code }}
            </div>
            
            <p><strong>This code will expire in {{ $expiresIn }} minutes.</strong></p>
            <p>If you didn't request this code, please ignore this email or contact support if you have concerns.</p>
            
            <p>Best regards,<br>Salenga Farm Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
```

### 9. Create Verification View

Create `resources/views/auth/mfa/verify.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Identity - Salenga Farm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">Verify Your Identity</h4>
                    </div>
                    <div class="card-body">
                        @if($lockedOut)
                            <div class="alert alert-danger">
                                <i class="fas fa-lock"></i>
                                Too many failed attempts. Please try again in {{ $lockoutMinutes }} minutes.
                            </div>
                        @else
                            <p class="text-center mb-4">
                                We've sent a 6-digit verification code to your email address.
                            </p>
                            
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('mfa.verify.post') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="code" class="form-label">Verification Code</label>
                                    <input type="text" 
                                           class="form-control form-control-lg text-center" 
                                           id="code" 
                                           name="code" 
                                           maxlength="6" 
                                           pattern="[0-9]{6}"
                                           placeholder="000000"
                                           autofocus 
                                           required>
                                    <small class="text-muted">Enter the 6-digit code from your email</small>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100">
                                    Verify
                                </button>
                            </form>
                            
                            <div class="text-center mt-3">
                                <form method="POST" action="{{ route('mfa.resend') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link">
                                        Didn't receive the code? Resend
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        <div class="text-center mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    Cancel & Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

## Configuration

Add to `config/mfa.php`:

```php
<?php

return [
    'enabled' => env('MFA_ENABLED', true),
    'session_timeout' => env('MFA_SESSION_TIMEOUT', 120), // minutes
    'max_attempts' => 5,
    'lockout_duration' => 15, // minutes
    'code_expiry' => 5, // minutes
    'max_resends' => 3, // per 15 minutes
];
```

## Testing Checklist

- [ ] User can enable MFA in profile
- [ ] Login sends email with 6-digit code
- [ ] Valid code grants access
- [ ] Invalid code shows error
- [ ] Code expires after 5 minutes
- [ ] Resend generates new code
- [ ] 5 failed attempts triggers lockout
- [ ] Lockout expires after 15 minutes
- [ ] User can disable MFA
- [ ] Session timeout re-requires verification

## User Guide

**For Users:**

1. **Enable MFA:**
   - Go to Profile → Security
   - Click "Enable Two-Factor Authentication"
   - Enter your password
   - Done!

2. **Login with MFA:**
   - Enter email and password
   - Check your email for 6-digit code
   - Enter code on verification page
   - Access granted!

3. **Disable MFA:**
   - Go to Profile → Security
   - Click "Disable Two-Factor Authentication"
   - Enter your password
   - Confirm

**Advantages:**
- No app to download
- Simple and familiar
- Just check your email
- Works on any device

This is much more user-friendly for plant buyers! 🌱
