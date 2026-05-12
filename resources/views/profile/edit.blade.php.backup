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
        }
        
        /* Ensure profile cards have proper styling for admin */
        .profile-card {
            background-color: white !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05) !important;
            border: none !important;
            overflow: hidden !important;
            margin-bottom: 1.5rem !important;
        }
        
        .profile-card .card-header {
            background: linear-gradient(145deg, #2a9d4e, #6cbf84) !important;
            color: white !important;
            border-bottom: none !important;
            padding: 1rem 1.25rem !important;
            font-weight: 600 !important;
            font-size: 1.1rem !important;
        }
        
        .profile-card .card-body {
            padding: 1.5rem !important;
        }
        
        /* Override any sidebar/inventory styles */
        .dashboard-page .profile-card {
            background: white !important;
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
}

/* Ensure profile cards have proper styling */
.profile-card {
    background-color: white !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05) !important;
    border: none !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
}

.profile-card .card-header {
    background: linear-gradient(145deg, #2a9d4e, #6cbf84) !important;
    color: white !important;
    border-bottom: none !important;
    padding: 1rem 1.25rem !important;
    font-weight: 600 !important;
    font-size: 1.1rem !important;
}

.profile-card .card-body {
    padding: 1.5rem !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4" style="max-width: 1400px; margin: 0 auto;">
@endif

                    <h2 class="mb-4">Profile Settings</h2>
                    
                    <!-- Notification Container with Push Animation -->
                    <div class="notification-container">
                        @if(session('status'))
                        <div class="alert alert-success alert-dismissible push-notification" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close notification-close" aria-label="Close"></button>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible push-notification" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close notification-close" aria-label="Close"></button>
                        </div>
                        @endif
                    </div>
                    
                    <div class="profile-container">

        <div class="row">
            <!-- Profile Picture Section -->
            <div class="col-md-4 mb-4">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-camera me-2"></i>Profile Picture
                    </div>
                    <div class="card-body text-center">
                        <div class="profile-pic-container" id="profilePicContainer">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile Picture" class="profile-pic" id="previewImage">
                            @else
                                <div class="profile-pic-placeholder" id="previewPlaceholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        
                        <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            
                            <div class="file-upload-container">
                                <div class="upload-btn-wrapper">
                                    <input type="file" class="form-control" id="avatarInput" name="avatar" accept="image/*">
                                    <label for="avatarInput" class="btn-upload">
                                        <i class="fas fa-upload me-2"></i>Choose Profile Picture
                                    </label>
                                    <span id="file-chosen" class="text-muted">No file selected</span>
                                </div>
                                <button type="submit" class="btn btn-update-picture">
                                    <i class="fas fa-save me-2"></i>Update Picture
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="col-md-8 mb-4">
                <div class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-user me-2"></i>Profile Information
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <!-- Role Display -->
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $roleDisplay = match(auth()->user()->role) {
                                            'super_admin' => 'Super Admin',
                                            'admin' => 'Admin',
                                            'user' => 'User',
                                            default => ucfirst(auth()->user()->role)
                                        };
                                        $badgeColor = match(auth()->user()->role) {
                                            'super_admin' => 'bg-warning text-dark',
                                            'admin' => 'bg-danger',
                                            'user' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} fs-6">{{ $roleDisplay }}</span>
                                    @if(auth()->user()->is_client)
                                        <span class="badge bg-info fs-6">Client</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="{{ old('first_name', auth()->user()->first_name) }}" required 
                                               placeholder="Your first name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name', auth()->user()->last_name) }}" required 
                                               placeholder="Your last name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                                       value="{{ old('contact_number', auth()->user()->contact_number) }}" required 
                                       placeholder="Your contact number">
                            </div>

                            @if(auth()->user()->is_client)
                            <div class="form-group">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       value="{{ old('company_name', auth()->user()->company_name) }}" 
                                       placeholder="Your company name">
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', auth()->user()->email) }}" required readonly 
                                       placeholder="Your email address">
                                <small class="text-muted">Email cannot be changed.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
            if (file) {
                // Update filename display
                fileChosen.textContent = file.name;
                
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
            }
        });

        // Add file size validation (max 5MB)
        avatarInput.addEventListener('change', function() {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const file = this.files[0];
            
            if (file && file.size > maxSize) {
                AlertSystem.alert({
                    title: 'File Too Large',
                    message: 'File size exceeds 5MB. Please choose a smaller image.',
                    type: 'warning'
                });
                this.value = '';
                fileChosen.textContent = 'No file selected';
            }
        });

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
</body>
</html>
@else
</div>
@endsection
@endif
