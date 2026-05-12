<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Add custom validation rules for sequential characters and dictionary words
        \Illuminate\Support\Facades\Validator::extend('no_sequential', function ($attribute, $value, $parameters, $validator) {
            // Check for sequences of 4 or more characters (ascending or descending)
            for ($i = 0; $i < strlen($value) - 3; $i++) {
                // Check ascending sequence
                if (
                    ord($value[$i]) + 1 === ord($value[$i+1]) &&
                    ord($value[$i+1]) + 1 === ord($value[$i+2]) &&
                    ord($value[$i+2]) + 1 === ord($value[$i+3])
                ) {
                    return false;
                }
                
                // Check descending sequence
                if (
                    ord($value[$i]) - 1 === ord($value[$i+1]) &&
                    ord($value[$i+1]) - 1 === ord($value[$i+2]) &&
                    ord($value[$i+2]) - 1 === ord($value[$i+3])
                ) {
                    return false;
                }
            }
            return true;
        }, 'The :attribute contains sequential characters (e.g., 1234, abcd).');
        
        \Illuminate\Support\Facades\Validator::extend('no_dictionary_words', function ($attribute, $value, $parameters, $validator) {
            $commonWords = ['password', 'love', 'admin', 'welcome', 'qwerty', 'abc', 'secret', 'letmein'];
            $lowercase = strtolower($value);
            
            foreach ($commonWords as $word) {
                if (strpos($lowercase, $word) !== false) {
                    return false;
                }
            }
            return true;
        }, 'The :attribute contains common dictionary words.');
        
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 
                'string',
                'min:8',                  // Minimum 8 characters
                'max:64',                 // Maximum 64 characters
                'confirmed',              // Must match password_confirmation field
                'regex:/[a-z]/',         // At least one lowercase letter
                'regex:/[A-Z]/',         // At least one uppercase letter
                'regex:/[0-9]/',         // At least one number
                'regex:/[@$!%*#?&]/',    // At least one special character
                'no_sequential',          // No sequential characters (e.g., 1234, abcd)
                'no_dictionary_words',    // No common dictionary words
            ],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // Set default role to client (green badge)
            'account_type' => 'individual', // Default to individual
            'profile_completed' => false, // Profile not complete yet
            'page_access' => json_encode([
                'dashboard',
                'my_requests',
                'plant_guide',
                'site_data'
            ]), // Give full page access to all client pages (as array, not object)
        ]);

        Auth::login($user);

        // Skip email verification event to avoid SMTP timeout
        // event(new Registered($user));

        return redirect('/');
    }
}
