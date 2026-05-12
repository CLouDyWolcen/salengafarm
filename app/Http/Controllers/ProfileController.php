<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Base fields for all users
        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'account_type' => $request->account_type ?? $user->account_type,
        ];

        // Account type specific fields
        if ($request->account_type === 'company') {
            // Handle business_type "other" option
            $businessType = $request->business_type;
            if ($businessType === 'other' && $request->business_type_other) {
                $businessType = $request->business_type_other;
            }
            
            $updateData = array_merge($updateData, [
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'company_city' => $request->company_city,
                'company_province' => $request->company_province,
                'company_zip_code' => $request->company_zip_code,
                'company_contact_person' => $request->company_contact_person,
                'position' => $request->position,
                'company_phone_number' => $request->company_phone_number,
                'business_type' => $businessType,
                'tin' => $request->tin,
                'website_socials' => $request->website_socials,
            ]);
        } else {
            // Individual account
            // Handle property_type "other" option
            $propertyType = $request->property_type;
            if ($propertyType === 'other' && $request->property_type_other) {
                $propertyType = $request->property_type_other;
            }
            
            $updateData = array_merge($updateData, [
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
                'property_type' => $propertyType,
                'gender' => $request->gender,
            ]);
        }

        $user->fill($updateData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Check if profile is now complete and update flag
        $user->profile_completed = $user->isProfileComplete();

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateAvatar(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:1024'],
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');
        
        // Delete old avatar if exists
        if ($request->user()->avatar) {
            Storage::disk('public')->delete($request->user()->avatar);
        }

        $request->user()->update([
            'avatar' => $path
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'avatar_url' => asset('storage/' . $path)
            ]);
        }

        return redirect()->route('profile.edit')->with('status', 'avatar-updated');
    }
}
