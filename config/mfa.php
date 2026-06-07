<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multi-Factor Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the MFA system.
    |
    */

    'enabled' => env('MFA_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | MFA Session Timeout
    |--------------------------------------------------------------------------
    |
    | How long (in minutes) the MFA verification session should last.
    | After this time, the user will need to verify again.
    |
    */

    'session_timeout' => env('MFA_SESSION_TIMEOUT', 120), // 2 hours

    /*
    |--------------------------------------------------------------------------
    | Maximum Verification Attempts
    |--------------------------------------------------------------------------
    |
    | Maximum number of failed MFA verification attempts before lockout.
    |
    */

    'max_attempts' => env('MFA_MAX_ATTEMPTS', 5),

    /*
    |--------------------------------------------------------------------------
    | Lockout Duration
    |--------------------------------------------------------------------------
    |
    | How long (in minutes) a user should be locked out after exceeding
    | the maximum number of failed attempts.
    |
    */

    'lockout_duration' => env('MFA_LOCKOUT_DURATION', 15), // 15 minutes

    /*
    |--------------------------------------------------------------------------
    | Code Expiry
    |--------------------------------------------------------------------------
    |
    | How long (in minutes) the MFA code should remain valid.
    |
    */

    'code_expiry' => env('MFA_CODE_EXPIRY', 5), // 5 minutes

    /*
    |--------------------------------------------------------------------------
    | Maximum Resend Attempts
    |--------------------------------------------------------------------------
    |
    | Maximum number of times a user can request a new code within the
    | resend window.
    |
    */

    'max_resends' => env('MFA_MAX_RESENDS', 3),

    /*
    |--------------------------------------------------------------------------
    | Resend Window
    |--------------------------------------------------------------------------
    |
    | Time window (in minutes) for counting resend attempts.
    |
    */

    'resend_window' => env('MFA_RESEND_WINDOW', 15), // 15 minutes

];
