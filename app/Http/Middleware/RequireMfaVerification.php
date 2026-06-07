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
        
        // Skip if MFA not enabled for this user
        if (!$user->mfa_enabled) {
            return $next($request);
        }
        
        // Skip ALL MFA routes (GET and POST) to avoid blocking verification
        if ($request->routeIs('mfa.*')) {
            return $next($request);
        }
        
        // Skip logout route
        if ($request->routeIs('logout')) {
            return $next($request);
        }
        
        // Check if device is remembered (30-day trust)
        if ($this->mfaService->isDeviceRemembered($user)) {
            return $next($request);
        }
        
        // Check if already verified in this session
        if ($this->mfaService->isSessionValid()) {
            return $next($request);
        }
        
        // Redirect to MFA verification (only for non-MFA routes)
        return redirect()->route('mfa.verify')
            ->with('info', 'Please verify your identity to continue.');
    }
}
