<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add New Client - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v=4" rel="stylesheet">
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .page-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .page-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }
        .page-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .btn-success {
            background: #28a745;
            border: none;
            padding: 10px 24px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        .btn-secondary {
            padding: 10px 24px;
            font-weight: 500;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-light dashboard-page">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        @include('layouts.sidebar')
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div style="padding-top: 0;">
                <div class="p-0">
                    <div class="page-header">
                        <h2><i class="fas fa-user-plus me-2"></i>Add New Client</h2>
                        <p>Create a new client account with access to the system</p>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf
                                
                                <!-- Hidden fields to automatically set as client -->
                                <input type="hidden" name="role" value="client">
                                <input type="hidden" name="is_client" value="1">
                                
                                <!-- Personal Information Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-user"></i>
                                        Personal Information
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">
                                                <i class="fas fa-user me-1"></i>First Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                                   id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                                   placeholder="Enter first name" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">
                                                <i class="fas fa-user me-1"></i>Last Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                                   id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                                   placeholder="Enter last name" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-address-book"></i>
                                        Contact Information
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" 
                                                   placeholder="client@example.com" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="contact_number" class="form-label">
                                                <i class="fas fa-phone me-1"></i>Contact Number <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                                   id="contact_number" name="contact_number" value="{{ old('contact_number') }}" 
                                                   placeholder="+63 912 345 6789" required>
                                            @error('contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-lock"></i>
                                        Account Security
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">
                                                <i class="fas fa-key me-1"></i>Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" 
                                                   placeholder="Minimum 8 characters" required>
                                            <small class="text-muted">Must be at least 8 characters long</small>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label">
                                                <i class="fas fa-key me-1"></i>Confirm Password <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Re-enter password" required>
                                            <small class="text-muted">Must match the password above</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-user-plus me-1"></i> Create Client Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
