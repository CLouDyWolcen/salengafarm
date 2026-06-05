<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'email' => $this->email,
            'password' => $this->password
        ];

        // Check for password requirements before attempting authentication
        $password = $this->password;
        $passwordErrors = [];
        
        // Only check password format if the user exists
        $user = User::where('email', $this->email)->first();
        
        if ($user) {
            // Only validate password length for login
            if (strlen($password) < 8) {
                $passwordErrors[] = 'Password must be at least 8 characters long';
            }
            
            if (strlen($password) > 64) {
                $passwordErrors[] = 'Password cannot exceed 64 characters';
            }
        }
        
        // If we have specific password errors, show them
        if (!empty($passwordErrors)) {
            RateLimiter::hit($this->throttleKey(), 60);
            throw ValidationException::withMessages([
                'password' => $passwordErrors,
            ]);
        }
        
        // Otherwise, attempt normal authentication
        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), 60); // Add explicit 60 second decay time

            // Log failed login attempt
            AuditService::logFailedLogin($this->email);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Set redirect based on role
        if (Auth::user()->hasAdminAccess()) {
            session(['redirect_to' => route('dashboard')]);
        } else {
            session(['redirect_to' => route('home')]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
