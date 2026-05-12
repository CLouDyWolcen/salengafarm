@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    $user = Auth::user();
    $isAdmin = $user && $user->hasAdminAccess();
@endphp

@if($isAdmin)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Edit Profile - Plant Inventory</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inventory.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}?v=1" rel="stylesheet">
    <link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
    <style>
        /* Admin profile page proper spacing */
        .main-content {
            padding: 2rem !important;
            background-color: #f5f8f7 !important;
        }
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        /* Ensure profile cards have proper styling for admin */
        .profile-card {
            background-color: white !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05) !important;
            border: none !important;
            overflow: hidden !important;
            margin-bottom: 1.5rem !important;
            position: relative;
            z-index: 1;
        }
        
        .profile-card .card-header {
            background: linear-gradient(145deg, #2a9d4e, #6cbf84) !important;
            color: white !important;
            border-bottom: none !important;
            padding: 0.5rem 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
        }
        
        .profile-card .card-body {
            padding: 0.75rem !important;
        }
        
        /* Override any sidebar/inventory styles */
        .dashboard-page .profile-card {
            background: white !important;
        }
        
        /* Reduce form group spacing */
        .form-group {
            margin-bottom: 0.5rem !important;
        }
        
        /* Ensure navbar dropdown is above profile content */
        .navbar {
            position: relative;
            z-index: 1030 !important;
        }
        
        .dropdown-menu {
            z-index: 1031 !important;
        }
    </style>
</head>
<body class="bg-light dashboard-page">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        @include('layouts.sidebar')
        <!-- Sidebar Toggle Button for Mobile -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div class="container-fluid px-4 py-4" style="max-width: 1400px; margin: 0 auto; margin-top: 1rem;">
@else
@extends('layouts.public')

@push('styles')
<link href="{{ asset('css/profile.css') }}?v=1" rel="stylesheet">
<link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
<style>
/* Hide sidebar for non-admin users */
body.with-sidebar {
    display: block !important;
}
body.with-sidebar .dashboard-flex {
    display: block !important;
}
body.with-sidebar .dashboard-flex .sidebar {
    display: none !important;
}
body.with-sidebar .dashboard-flex .main-content {
    margin-left: 0 !important;
    width: 100% !important;
    padding-left: 0 !important;
}

/* User profile page proper spacing */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}

/* Ensure profile cards have proper styling */
.profile-card {
    background-color: white !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05) !important;
    border: none !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
    position: relative;
    z-index: 1;
}

.profile-card .card-header {
    background: linear-gradient(145deg, #2a9d4e, #6cbf84) !important;
    color: white !important;
    border-bottom: none !important;
    padding: 0.5rem 0.75rem !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
}

.profile-card .card-body {
    padding: 0.75rem !important;
}

/* Reduce form group spacing */
.form-group {
    margin-bottom: 0.5rem !important;
}

/* Ensure navbar dropdown is above profile content */
.main-nav {
    position: relative !important;
    z-index: 1030 !important;
}

.navbar {
    position: relative;
    z-index: 1030 !important;
}

.dropdown-menu {
    z-index: 10000 !important;
    position: absolute !important;
}

.user-section .dropdown {
    position: relative !important;
}

.user-section .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    display: none !important;
}

.user-section .dropdown-menu.show {
    display: block !important;
}

/* Ensure content doesn't overlap navbar */
.container-fluid {
    position: relative;
    z-index: 1;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4" style="max-width: 1400px; margin: 0 auto; padding-top: 1.5rem; padding-bottom: 2rem;">
@endif

                    <h2 class="mb-3">Profile Settings</h2>
                    
                    <!-- Profile Completion Banner -->
                    @if(!auth()->user()->hasAdminAccess() && !auth()->user()->isProfileComplete())
                    <div class="alert alert-warning d-flex align-items-center mb-4" style="border-left: 4px solid #ffc107; padding: 1rem 1.25rem; border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                        <div class="flex-grow-1">
                            <strong style="font-size: 1rem;">Complete Your Profile</strong>
                            <p class="mb-0" style="font-size: 0.9rem; margin-top: 0.25rem;">Your profile is {{ auth()->user()->getProfileCompletionPercentage() }}% complete. Complete it to unlock all features.</p>
                        </div>
                        <div class="ms-3">
                            <div class="progress" style="width: 200px; height: 24px; border-radius: 12px;">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ auth()->user()->getProfileCompletionPercentage() }}%; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">
                                    {{ auth()->user()->getProfileCompletionPercentage() }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="profile-container">

        <!-- START: Unified Form Wrapper for All Profile Sections -->
        <form method="post" action="{{ route('profile.update') }}" id="unifiedProfileForm">
            @csrf
            @method('put')

        <div class="row">
            <!-- Profile Picture Section -->
            <div class="col-md-4 mb-4">
                <div class="profile-card h-100">
                    <div class="card-header">
                        <i class="fas fa-camera me-2"></i>Profile Picture
                    </div>
                    <div class="card-body text-center">
                        <div class="profile-pic-container" id="profilePicContainer" style="margin-bottom: 0.75rem;">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Picture" class="profile-pic" id="previewImage">
                            @else
                                <div class="profile-pic-placeholder" id="previewPlaceholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="file-upload-container" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                            <div class="upload-btn-wrapper">
                                <input type="file" class="form-control" id="avatarInput" name="avatar_file" accept="image/*">
                                <label for="avatarInput" class="btn-upload">
                                    <i class="fas fa-upload me-2"></i>Choose Profile Picture
                                </label>
                                <span id="file-chosen" class="text-muted">No file selected</span>
                            </div>
                            <button type="button" class="btn btn-update-picture" id="updateAvatarBtn" disabled>
                                <i class="fas fa-save me-2"></i>Update Picture
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="col-md-8 mb-4">
                <div class="profile-card h-100">
                    <div class="card-header">
                        <i class="fas fa-user me-2"></i>Profile Information
                    </div>
                    <div class="card-body">
                            <!-- Role Display -->
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $roleDisplay = match(auth()->user()->role) {
                                            'super_admin' => 'Super Admin',
                                            'admin' => 'Admin',
                                            'client' => 'Client',
                                            default => ucfirst(auth()->user()->role)
                                        };
                                        $badgeColor = match(auth()->user()->role) {
                                            'super_admin' => 'bg-warning text-dark',
                                            'admin' => 'bg-danger',
                                            'client' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} fs-6">{{ $roleDisplay }}</span>
                                </div>
                            </div>

                            <!-- Account Type Selection -->
                            @if(!auth()->user()->hasAdminAccess())
                            <div class="form-group">
                                <label class="form-label">Account Type <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="account_type" id="accountIndividual" 
                                               value="individual" {{ old('account_type', auth()->user()->account_type) === 'individual' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="accountIndividual">
                                            <i class="fas fa-user me-1"></i> Individual
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="account_type" id="accountCompany" 
                                               value="company" {{ old('account_type', auth()->user()->account_type) === 'company' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="accountCompany">
                                            <i class="fas fa-building me-1"></i> Company
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="account_type" value="{{ auth()->user()->account_type ?? 'individual' }}">
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="{{ old('first_name', auth()->user()->first_name) }}" required 
                                               placeholder="Your first name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name', auth()->user()->last_name) }}" required 
                                               placeholder="Your last name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                                       value="{{ old('contact_number', auth()->user()->contact_number) }}" required 
                                       placeholder="Your contact number">
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', auth()->user()->email) }}" required readonly 
                                       placeholder="Your email address">
                                <small class="text-muted">Email cannot be changed.</small>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Individual Address Information Section -->
        <div class="row individual-fields" style="display: none;">
            <div class="col-12 mb-4">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-map-marker-alt me-2"></i>Address Information
                    </div>
                    <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="{{ old('address', auth()->user()->address) }}" 
                                       placeholder="Street, Barangay">
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           value="{{ old('city', auth()->user()->city) }}" 
                                           placeholder="City">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="province" name="province" 
                                           value="{{ old('province', auth()->user()->province) }}" 
                                           placeholder="Province">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="zip_code" class="form-label">Zip Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                           value="{{ old('zip_code', auth()->user()->zip_code) }}" 
                                           placeholder="Zip Code">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="property_type" class="form-label">Property Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="property_type" name="property_type">
                                        <option value="">Select Property Type</option>
                                        <option value="residential" {{ old('property_type', auth()->user()->property_type) === 'residential' ? 'selected' : '' }}>Residential</option>
                                        <option value="commercial" {{ old('property_type', auth()->user()->property_type) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="industrial" {{ old('property_type', auth()->user()->property_type) === 'industrial' ? 'selected' : '' }}>Industrial</option>
                                        <option value="agricultural" {{ old('property_type', auth()->user()->property_type) === 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                        <option value="mixed_use" {{ old('property_type', auth()->user()->property_type) === 'mixed_use' ? 'selected' : '' }}>Mixed Use</option>
                                        <option value="other" {{ old('property_type', auth()->user()->property_type) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" id="property_type_other" name="property_type_other" 
                                           value="{{ old('property_type_other', auth()->user()->property_type_other) }}"
                                           placeholder="Please specify property type" style="display: none;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender <small class="text-muted">(Optional)</small></label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">Prefer not to say</option>
                                        <option value="male" {{ old('gender', auth()->user()->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', auth()->user()->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', auth()->user()->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Information Section -->
        <div class="company-fields" style="display: none;">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="profile-card">
                        <div class="card-header">
                            <i class="fas fa-building me-2"></i>Company Information
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" 
                                               value="{{ old('company_name', auth()->user()->company_name) }}" 
                                               placeholder="Company Name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="business_type" class="form-label">Business Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="business_type" name="business_type">
                                            <option value="">Select Business Type</option>
                                            <option value="landscaping" {{ old('business_type', auth()->user()->business_type) === 'landscaping' ? 'selected' : '' }}>Landscaping</option>
                                            <option value="real_estate" {{ old('business_type', auth()->user()->business_type) === 'real_estate' ? 'selected' : '' }}>Real Estate</option>
                                            <option value="construction" {{ old('business_type', auth()->user()->business_type) === 'construction' ? 'selected' : '' }}>Construction</option>
                                            <option value="hospitality" {{ old('business_type', auth()->user()->business_type) === 'hospitality' ? 'selected' : '' }}>Hospitality</option>
                                            <option value="retail" {{ old('business_type', auth()->user()->business_type) === 'retail' ? 'selected' : '' }}>Retail</option>
                                            <option value="agriculture" {{ old('business_type', auth()->user()->business_type) === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                            <option value="other" {{ old('business_type', auth()->user()->business_type) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        <input type="text" class="form-control mt-2" id="business_type_other" name="business_type_other" 
                                               value="{{ old('business_type_other', auth()->user()->business_type_other) }}"
                                               placeholder="Please specify business type" style="display: none;">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tin" class="form-label">TIN <small class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control" id="tin" name="tin" 
                                               value="{{ old('tin', auth()->user()->tin) }}" 
                                               placeholder="Tax Identification Number">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="website_socials" class="form-label">Website/Socials <small class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control" id="website_socials" name="website_socials" 
                                               value="{{ old('website_socials', auth()->user()->website_socials) }}" 
                                               placeholder="Website or Social Media URL">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="company_address" class="form-label">Company Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_address" name="company_address" 
                                           value="{{ old('company_address', auth()->user()->company_address) }}" 
                                           placeholder="Street, Barangay">
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="company_city" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="company_city" name="company_city" 
                                               value="{{ old('company_city', auth()->user()->company_city) }}" 
                                               placeholder="City">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="company_province" class="form-label">Province <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="company_province" name="company_province" 
                                               value="{{ old('company_province', auth()->user()->company_province) }}" 
                                               placeholder="Province">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="company_zip_code" class="form-label">Zip Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="company_zip_code" name="company_zip_code" 
                                               value="{{ old('company_zip_code', auth()->user()->company_zip_code) }}" 
                                               placeholder="Zip Code">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="company_contact_person" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="company_contact_person" name="company_contact_person" 
                                               value="{{ old('company_contact_person', auth()->user()->company_contact_person) }}" 
                                               placeholder="Full Name">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="position" name="position" 
                                               value="{{ old('position', auth()->user()->position) }}" 
                                               placeholder="Job Title">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="company_phone_number" class="form-label">Company Phone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="company_phone_number" name="company_phone_number" 
                                               value="{{ old('company_phone_number', auth()->user()->company_phone_number) }}" 
                                               placeholder="Company Phone Number">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Save Button for All Profile Sections -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Save All Changes
                    </button>
                </div>
            </div>
        </div>

        </form>
        <!-- END: Unified Form Wrapper -->

        <!-- Password Update Section -->
        <div class="row">
            <div class="col-12">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-lock me-2"></i>Update Password
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Ensure your account is using a long, random password to stay secure.
                        </p>

                        <form method="post" action="{{ route('password.update') }}" id="passwordForm">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="password-toggle-wrapper">
                                            <input type="password" class="form-control" id="current_password" name="current_password" 
                                                   autocomplete="current-password" required>
                                            <i class="fas fa-eye password-toggle-icon" data-target="current_password"></i>
                                        </div>
                                        @if($errors->updatePassword->get('current_password'))
                                            <div class="text-danger mt-1">
                                                {{ $errors->updatePassword->first('current_password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="password-toggle-wrapper">
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   autocomplete="new-password" required>
                                            <i class="fas fa-eye password-toggle-icon" data-target="password"></i>
                                        </div>
                                        @if($errors->updatePassword->get('password'))
                                            <div class="text-danger mt-1">
                                                {{ $errors->updatePassword->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="password-toggle-wrapper">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                                   autocomplete="new-password" required>
                                            <i class="fas fa-eye password-toggle-icon" data-target="password_confirmation"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="password-requirements mb-3">
                                <p class="mb-1">Password must:</p>
                                <ul>
                                    <li id="length-requirement">Be at least 8 characters long</li>
                                    <li id="uppercase-requirement">Contain at least one uppercase letter</li>
                                    <li id="lowercase-requirement">Contain at least one lowercase letter</li>
                                    <li id="number-requirement">Contain at least one number</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-primary" id="updatePasswordBtn" disabled>
                                <i class="fas fa-save me-2"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/push-notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/alerts.js') }}?v={{ time() }}"></script>
    <script>
        // Show success/error messages
        @if(session('status'))
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.style.minWidth = '300px';
            alert.style.maxWidth = '500px';
            alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            alert.style.opacity = '1';
            alert.style.backgroundColor = '#d1e7dd';
            alert.style.color = '#0f5132';
            alert.style.border = '1px solid #badbcc';
            alert.style.borderRadius = '8px';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        });
        @endif

        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.style.minWidth = '300px';
            alert.style.maxWidth = '500px';
            alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            alert.style.opacity = '1';
            alert.style.backgroundColor = '#f8d7da';
            alert.style.color = '#842029';
            alert.style.border = '1px solid #f5c2c7';
            alert.style.borderRadius = '8px';
            alert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        });
        @endif
        
        // Force Bootstrap dropdown to work on profile page
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for everything to load
            setTimeout(function() {
                const profileBtn = document.getElementById('profileDropdown');
                if (profileBtn) {
                    console.log('Profile button found:', profileBtn);
                    
                    // Remove any existing Bootstrap dropdown instance
                    const existingDropdown = bootstrap.Dropdown.getInstance(profileBtn);
                    if (existingDropdown) {
                        existingDropdown.dispose();
                    }
                    
                    // Create new dropdown instance
                    const dropdown = new bootstrap.Dropdown(profileBtn, {
                        boundary: 'viewport',
                        popperConfig: {
                            strategy: 'fixed'
                        }
                    });
                    
                    console.log('Dropdown initialized:', dropdown);
                    
                    // Manual click handler as backup
                    profileBtn.addEventListener('click', function(e) {
                        console.log('Profile button clicked!');
                        const menu = this.nextElementSibling;
                        if (menu && menu.classList.contains('dropdown-menu')) {
                            console.log('Toggling menu:', menu);
                            menu.classList.toggle('show');
                        }
                    });
                }
            }, 500);
        });
        
        // Image preview functionality with enhanced UX
        const avatarInput = document.getElementById('avatarInput');
        const fileChosen = document.getElementById('file-chosen');
        const profilePicContainer = document.getElementById('profilePicContainer');
        
        // Clicking on profile picture should trigger file input
        profilePicContainer.addEventListener('click', function() {
            avatarInput.click();
        });
        
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const updateBtn = document.getElementById('updateAvatarBtn');
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (file) {
                // Validate file size
                if (file.size > maxSize) {
                    // Show warning notification
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-warning alert-dismissible fade show position-fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.style.minWidth = '300px';
                    alert.style.maxWidth = '500px';
                    alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                    alert.style.opacity = '1';
                    alert.style.backgroundColor = '#fff3cd';
                    alert.style.color = '#664d03';
                    alert.style.border = '1px solid #ffecb5';
                    alert.style.borderRadius = '8px';
                    alert.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i>File size exceeds 5MB. Please choose a smaller image.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 5000);
                    
                    this.value = '';
                    fileChosen.textContent = 'No file selected';
                    updateBtn.disabled = true;
                    return;
                }
                
                // Update filename display
                fileChosen.textContent = file.name;
                updateBtn.disabled = false;
                
                // Preview image with animation
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    const placeholder = document.getElementById('previewPlaceholder');
                    
                    if (preview) {
                        // Add animation class, then update src
                        preview.classList.add('profile-pic-animate');
                        preview.src = e.target.result;
                    } else if (placeholder) {
                        const newPreview = document.createElement('img');
                        newPreview.src = e.target.result;
                        newPreview.id = 'previewImage';
                        newPreview.className = 'profile-pic profile-pic-animate';
                        placeholder.parentNode.replaceChild(newPreview, placeholder);
                    }
                    
                    // Show a visual indicator that the image was selected
                    const btn = document.querySelector('.btn-update-picture');
                    btn.classList.add('pulse-once');
                    setTimeout(() => {
                        btn.classList.remove('pulse-once');
                    }, 1000);
                }
                reader.readAsDataURL(file);
            } else {
                fileChosen.textContent = 'No file selected';
                updateBtn.disabled = true;
            }
        });

        // Handle avatar upload via AJAX
        document.getElementById('updateAvatarBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const file = avatarInput.files[0];
            if (!file) {
                // Show warning notification
                const alert = document.createElement('div');
                alert.className = 'alert alert-warning alert-dismissible fade show position-fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                alert.style.minWidth = '300px';
                alert.style.maxWidth = '500px';
                alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                alert.style.opacity = '1';
                alert.style.backgroundColor = '#fff3cd';
                alert.style.color = '#664d03';
                alert.style.border = '1px solid #ffecb5';
                alert.style.borderRadius = '8px';
                alert.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>Please select a profile picture first.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 5000);
                return;
            }
            
            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('_method', 'PATCH');
            
            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            
            fetch('{{ route("profile.avatar.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success notification
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.style.minWidth = '300px';
                    alert.style.maxWidth = '500px';
                    alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                    alert.style.opacity = '1';
                    alert.style.backgroundColor = '#d1e7dd';
                    alert.style.color = '#0f5132';
                    alert.style.border = '1px solid #badbcc';
                    alert.style.borderRadius = '8px';
                    alert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Profile picture updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 5000);
                    
                    // Reset button state
                    btn.innerHTML = originalText;
                    btn.disabled = true;
                    avatarInput.value = '';
                    fileChosen.textContent = 'No file selected';
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            })
            .catch(error => {
                // Show error notification
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                alert.style.minWidth = '300px';
                alert.style.maxWidth = '500px';
                alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                alert.style.opacity = '1';
                alert.style.backgroundColor = '#f8d7da';
                alert.style.color = '#842029';
                alert.style.border = '1px solid #f5c2c7';
                alert.style.borderRadius = '8px';
                alert.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>${error.message || 'Failed to upload profile picture. Please try again.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 5000);
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });

        // Account type toggle functionality
        const accountIndividual = document.getElementById('accountIndividual');
        const accountCompany = document.getElementById('accountCompany');
        const individualFields = document.querySelectorAll('.individual-fields');
        const companyFields = document.querySelectorAll('.company-fields');

        function toggleAccountFields() {
            const isIndividual = accountIndividual && accountIndividual.checked;
            
            individualFields.forEach(field => {
                field.style.display = isIndividual ? 'block' : 'none';
            });
            
            companyFields.forEach(field => {
                field.style.display = isIndividual ? 'none' : 'block';
            });
        }

        // Initialize on page load
        if (accountIndividual || accountCompany) {
            toggleAccountFields();
            
            if (accountIndividual) {
                accountIndividual.addEventListener('change', toggleAccountFields);
            }
            if (accountCompany) {
                accountCompany.addEventListener('change', toggleAccountFields);
            }
        }

        // Property Type "Other" field toggle
        const propertyTypeSelect = document.getElementById('property_type');
        const propertyTypeOther = document.getElementById('property_type_other');
        
        if (propertyTypeSelect && propertyTypeOther) {
            propertyTypeSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    propertyTypeOther.style.display = 'block';
                    propertyTypeOther.required = true;
                } else {
                    propertyTypeOther.style.display = 'none';
                    propertyTypeOther.required = false;
                    propertyTypeOther.value = '';
                }
            });
            
            // Initialize on page load
            if (propertyTypeSelect.value === 'other') {
                propertyTypeOther.style.display = 'block';
                propertyTypeOther.required = true;
            }
        }

        // Business Type "Other" field toggle
        const businessTypeSelect = document.getElementById('business_type');
        const businessTypeOther = document.getElementById('business_type_other');
        
        if (businessTypeSelect && businessTypeOther) {
            businessTypeSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    businessTypeOther.style.display = 'block';
                    businessTypeOther.required = true;
                } else {
                    businessTypeOther.style.display = 'none';
                    businessTypeOther.required = false;
                    businessTypeOther.value = '';
                }
            });
            
            // Initialize on page load
            if (businessTypeSelect.value === 'other') {
                businessTypeOther.style.display = 'block';
                businessTypeOther.required = true;
            }
        }

        // Password toggle functionality
        document.querySelectorAll('.password-toggle-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });

        // Password validation
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const currentInput = document.getElementById('current_password');
        const updateButton = document.getElementById('updatePasswordBtn');
        
        // Requirements elements
        const lengthReq = document.getElementById('length-requirement');
        const uppercaseReq = document.getElementById('uppercase-requirement');
        const lowercaseReq = document.getElementById('lowercase-requirement');
        const numberReq = document.getElementById('number-requirement');
        
        function validatePassword() {
            const password = passwordInput.value;
            let isValid = true;
            
            // Check length
            if (password.length >= 8) {
                lengthReq.classList.add('requirement-met');
            } else {
                lengthReq.classList.remove('requirement-met');
                isValid = false;
            }
            
            // Check uppercase
            if (/[A-Z]/.test(password)) {
                uppercaseReq.classList.add('requirement-met');
            } else {
                uppercaseReq.classList.remove('requirement-met');
                isValid = false;
            }
            
            // Check lowercase
            if (/[a-z]/.test(password)) {
                lowercaseReq.classList.add('requirement-met');
            } else {
                lowercaseReq.classList.remove('requirement-met');
                isValid = false;
            }
            
            // Check number
            if (/[0-9]/.test(password)) {
                numberReq.classList.add('requirement-met');
            } else {
                numberReq.classList.remove('requirement-met');
                isValid = false;
            }
            
            // Check if everything is filled and password matches confirmation
            if (isValid && 
                password === confirmInput.value && 
                password.length > 0 && 
                currentInput.value.length > 0) {
                updateButton.disabled = false;
            } else {
                updateButton.disabled = true;
            }
        }
        
        // Add listeners to all password fields
        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);
        currentInput.addEventListener('input', validatePassword);
    </script>

@if($isAdmin)
        </div><!-- Close container-fluid for admin -->
    </div><!-- Close main-content -->
</div><!-- Close dashboard-flex -->
</body>
</html>
@else
</div><!-- Close container-fluid for non-admin -->
@endsection
@endif
