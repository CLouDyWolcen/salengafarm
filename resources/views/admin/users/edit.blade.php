<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Salenga Farm - Edit User</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v=4" rel="stylesheet">
    <link href="{{ asset('css/inventory.css') }}?v=2" rel="stylesheet">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Edit User</h2>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Edit User Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">User Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.update', $user) }}" method="POST" id="editUserForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Hidden input to ensure page_access is always sent even when all checkboxes are unchecked -->
                                <input type="hidden" name="page_access_submitted" value="1">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name *</label>
                                            <input type="text" 
                                                   class="form-control @error('first_name') is-invalid @enderror" 
                                                   id="first_name" 
                                                   name="first_name" 
                                                   value="{{ old('first_name', $user->first_name) }}" 
                                                   required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name *</label>
                                            <input type="text" 
                                                   class="form-control @error('last_name') is-invalid @enderror" 
                                                   id="last_name" 
                                                   name="last_name" 
                                                   value="{{ old('last_name', $user->last_name) }}" 
                                                   required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $user->email) }}" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_number" class="form-label">Contact Number</label>
                                            <input type="text" 
                                                   class="form-control @error('contact_number') is-invalid @enderror" 
                                                   id="contact_number" 
                                                   name="contact_number" 
                                                   value="{{ old('contact_number', $user->contact_number) }}">
                                            @error('contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role *</label>
                                            <select class="form-select @error('role') is-invalid @enderror" 
                                                    id="role" 
                                                    name="role" 
                                                    required
                                                    onchange="togglePageAccessOptions()">
                                                <option value="">Select Role</option>
                                                <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Client</option>
                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Page Access Section -->
                                <div id="pageAccessSection">
                                    <hr class="my-4">
                                    <h6 class="mb-3">Page Access Permissions</h6>
                                    <p class="text-muted small mb-3">Select which pages this user can access</p>
                                    
                                    @php
                                        // Decode the saved page access permissions
                                        $savedAccess = [];
                                        if ($user->page_access) {
                                            $decoded = json_decode($user->page_access, true);
                                            $savedAccess = is_array($decoded) ? $decoded : [];
                                        }
                                    @endphp
                                    
                                    <!-- Client Pages -->
                                    <div id="clientPages" style="display: {{ old('role', $user->role) == 'client' ? 'block' : 'none' }};">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check mb-2">
                                                    <!-- Hidden input to ensure 'home' is always included -->
                                                    <input type="hidden" name="page_access[]" value="home">
                                                    <input class="form-check-input" type="checkbox" id="access_home" value="home" checked disabled>
                                                    <label class="form-check-label" for="access_home">
                                                        <i class="fas fa-home me-1"></i> Home
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="access_dashboard" name="page_access[]" value="dashboard" {{ in_array('dashboard', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="access_dashboard">
                                                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="access_plant_guide" name="page_access[]" value="plant_guide" {{ in_array('plant_guide', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="access_plant_guide">
                                                        <i class="fas fa-book me-1"></i> Plant Guide
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="access_site_data" name="page_access[]" value="site_data" {{ in_array('site_data', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="access_site_data">
                                                        <i class="fas fa-map-marked-alt me-1"></i> Site Data
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Home page is always accessible</small>
                                    </div>

                                    <!-- Admin Pages -->
                                    <div id="adminPages" style="display: {{ old('role', $user->role) == 'admin' ? 'block' : 'none' }};">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <!-- Hidden input to ensure 'home' is always included -->
                                                    <input type="hidden" name="page_access[]" value="home">
                                                    <input class="form-check-input" type="checkbox" id="admin_access_home" value="home" checked disabled>
                                                    <label class="form-check-label" for="admin_access_home">
                                                        <i class="fas fa-home me-1"></i> Home
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="admin_access_dashboard" name="page_access[]" value="dashboard" {{ in_array('dashboard', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_access_dashboard">
                                                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="admin_access_inventory" name="page_access[]" value="inventory" {{ in_array('inventory', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_access_inventory">
                                                        <i class="fas fa-boxes me-1"></i> Inventory
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="admin_access_pos" name="page_access[]" value="point_of_sale" {{ in_array('point_of_sale', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_access_pos">
                                                        <i class="fas fa-cash-register me-1"></i> Point of Sale
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="admin_access_requests" name="page_access[]" value="requests" {{ in_array('requests', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_access_requests">
                                                        <i class="fas fa-clipboard-list me-1"></i> Requests
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="admin_access_site_visits" name="page_access[]" value="site_visits" {{ in_array('site_visits', $savedAccess) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="admin_access_site_visits">
                                                        <i class="fas fa-map-marker-alt me-1"></i> Site Visits
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Home page is always accessible</small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/dashboard.js') }}?v=3"></script>
    <script>
        function togglePageAccessOptions() {
            const role = document.getElementById('role').value;
            const clientPages = document.getElementById('clientPages');
            const adminPages = document.getElementById('adminPages');
            
            if (role === 'client') {
                clientPages.style.display = 'block';
                adminPages.style.display = 'none';
            } else if (role === 'admin') {
                clientPages.style.display = 'none';
                adminPages.style.display = 'block';
            } else {
                clientPages.style.display = 'none';
                adminPages.style.display = 'none';
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePageAccessOptions();
        });
    </script>
</body>
</html>
