<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Log successful login
        AuditService::logLogin($request->email);

        // Check if MFA is enabled for this user
        if ($user->mfa_enabled) {
            $mfaService = app(\App\Services\MfaService::class);
            $sent = $mfaService->sendCode($user);
            
            if ($sent) {
                return redirect()->route('mfa.verify')
                    ->with('success', 'A verification code has been sent to your email.');
            } else {
                // If email fails, log them in anyway but show warning
                return redirect()->intended(session('redirect_to', route('dashboard')))
                    ->with('warning', 'Unable to send verification code. Please check your email settings.');
            }
        }

        // Normal login flow (MFA not enabled)
        return redirect()->intended(session('redirect_to', route('dashboard')));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log logout before destroying session
        AuditService::logLogout();

        // Clear MFA session if exists
        $mfaService = app(\App\Services\MfaService::class);
        $mfaService->clearSession();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
