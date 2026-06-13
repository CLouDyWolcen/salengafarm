<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\MfaController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    // Rate limit: 5 registrations per minute per IP to prevent mass account creation
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:5,1');
    
    // Email verification routes (after registration)
    Route::get('verify-email-code', [\App\Http\Controllers\Auth\EmailVerificationCodeController::class, 'show'])
        ->name('verification.code.show');
    
    // Rate limit: 10 attempts per minute to prevent brute force of 6-digit codes
    Route::post('verify-email-code', [\App\Http\Controllers\Auth\EmailVerificationCodeController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('verification.code.verify');
    
    // Rate limit: 3 resends per 15 minutes to prevent email spam
    Route::post('verify-email-code/resend', [\App\Http\Controllers\Auth\EmailVerificationCodeController::class, 'resend'])
        ->middleware('throttle:3,15')
        ->name('verification.code.resend');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Rate limit: 3 password reset requests per hour per IP
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:3,60')
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // Rate limit: 5 password reset submissions per hour (with token)
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->middleware('throttle:5,60')
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // MFA Routes - explicitly bypass MFA verification middleware to prevent loops
    Route::prefix('mfa')->name('mfa.')->group(function () {
        // Verification routes (needed during MFA challenge)
        Route::get('/verify', [MfaController::class, 'showVerify'])->name('verify');
        Route::post('/verify', [MfaController::class, 'verify'])->name('verify.post');
        Route::post('/resend', [MfaController::class, 'resend'])->name('resend');
        
        // Management routes (for profile settings - require MFA verification)
        Route::get('/enable', [MfaController::class, 'showEnable'])->name('enable');
        Route::post('/enable', [MfaController::class, 'enable'])->name('enable.post');
        Route::post('/disable', [MfaController::class, 'disable'])->name('disable');
    });
});
