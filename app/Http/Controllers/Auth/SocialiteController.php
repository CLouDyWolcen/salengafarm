<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to provider for authentication
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Get user data from social provider (this is the network-intensive part)
            $socialUser = Socialite::driver($provider)->user();
            $email = $socialUser->getEmail();
            $socialName = $socialUser->getName();
            
            // Reduce logging to only essential information
            Log::info('Social login attempt', ['provider' => $provider, 'email' => $email]);
            
            // Find existing user with a single efficient query
            $user = User::where('email', $email)->first();
            
            // If user doesn't exist, create new one efficiently
            if (!$user) {
                // Split the name into first and last name (do this only once)
                $nameParts = explode(' ', $socialName, 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                
                // Prepare user data
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'contact_number' => '', 
                    'company_name' => '',   
                    'email' => $email,
                    // Generate password without excessive entropy
                    'password' => Hash::make(Str::random(12)),
                    'role' => 'client',  // Changed from 'user' to 'client'
                    'is_client' => true,
                    // Set default page access for client users
                    'page_access' => json_encode(['dashboard', 'plant_guide'])
                ];
                
                // Avoid redundant Schema check if possible by using static property or cache
                static $hasNameColumn = null;
                if ($hasNameColumn === null) {
                    $hasNameColumn = Schema::hasColumn('users', 'name');
                }
                
                if ($hasNameColumn) {
                    $userData['name'] = $socialName;
                }
                
                // Create user with a single database operation
                $user = User::create($userData);
            }
            
            // Login the user (this creates the session)
            Auth::login($user);
            
            // Optimize redirection by avoiding additional queries when possible
            $redirectRoute = $user->hasAdminAccess() ? 'dashboard' : 'home';
            return redirect()->route($redirectRoute);
            
        } catch (Exception $e) {
            // Only log essential error information
            Log::error('Social login failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Social login failed: ' . $e->getMessage());
        }
    }
}
