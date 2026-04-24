<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'Salenga Farm')); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>?v=<?php echo e(time()); ?>">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/loading.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/push-notifications.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>?v=4" rel="stylesheet">
    <link href="<?php echo e(asset('css/inventory.css')); ?>?v=2" rel="stylesheet">
    <!-- Shared public styles (navbar/buttons) to match Home page -->
    <link href="<?php echo e(asset('css/public.css')); ?>?v=3" rel="stylesheet">
    <!-- <link href="<?php echo e(asset('css/plant-details.css')); ?>?v=<?php echo e(rand(1000,9999) . time()); ?>" rel="stylesheet"> -->
    <!-- <link href="<?php echo e(asset('css/plant-details-fix.css')); ?>?v=<?php echo e(rand(1000,9999) . time()); ?>" rel="stylesheet"> -->
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    .dashboard-flex {
        display: flex !important;
        flex-direction: row !important;
        min-height: 100vh;
        width: 100%;
    }
    .main-content {
        flex: 1 1 0%;
        min-width: 0;
        height: auto;
        min-height: 100vh;
        overflow-y: visible;
        display: block;
    }
    
    /* Force FontAwesome spin animation to work properly with high specificity */
    .fa-spin,
    .fas.fa-spin,
    .far.fa-spin,
    .fab.fa-spin,
    .fal.fa-spin,
    i.fa-spin,
    i.fas.fa-spin,
    .fa-spinner.fa-spin {
        -webkit-animation: custom-fa-spin 1s infinite linear !important;
        animation: custom-fa-spin 1s infinite linear !important;
        -webkit-transform-origin: center !important;
        transform-origin: center !important;
    }
    
    @-webkit-keyframes custom-fa-spin {
        0% { 
            -webkit-transform: rotate(0deg); 
            transform: rotate(0deg); 
        }
        100% { 
            -webkit-transform: rotate(360deg); 
            transform: rotate(360deg); 
        }
    }
    
    @keyframes custom-fa-spin {
        0% { 
            -webkit-transform: rotate(0deg); 
            transform: rotate(0deg); 
        }
        100% { 
            -webkit-transform: rotate(360deg); 
            transform: rotate(360deg); 
        }
    }
    /* Override sidebar spacing when sidebar is hidden */
    body.no-sidebar .dashboard-flex .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
    body.no-sidebar .dashboard-flex {
        width: 100% !important;
    }
    /* Ensure cards auto-size on simplified user/client dashboard */
    body.no-sidebar .main-content .card { height: auto !important; }
    body.no-sidebar .main-content .card .card-body { height: auto !important; }
    
    /* Force navbar links to be consistent size */
    .navbar-nav .nav-link {
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        padding: 0.5rem 1rem !important;
    }
    
    /* Force navbar height to match home page */
    .main-nav {
        height: 60px !important;
        min-height: 60px !important;
        max-height: 60px !important;
    }
    
    /* Fix layout for non-admin users - no sidebar, no flex */
    body.no-sidebar {
        display: block !important;
    }
    body.no-sidebar .dashboard-flex {
        display: block !important;
        flex-direction: unset !important;
        min-height: unset !important;
    }
    body.no-sidebar .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding-left: 0 !important;
        flex: unset !important;
        min-width: unset !important;
    }
    
    /* Menu button animation styles */
    .menu-btn {
        font-size: 0.98rem;
        font-weight: 500;
        color: #fff !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        transition: all 0.3s ease;
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        text-decoration: none !important;
    }
    
    .menu-btn:focus, .menu-btn:hover, .menu-btn:active {
        color: #e0e0e0 !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    /* Remove Bootstrap's btn-link default styles */
    .menu-btn.btn-link {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .menu-btn.btn-link:hover,
    .menu-btn.btn-link:focus,
    .menu-btn.btn-link:active {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }
    
    /* Remove Bootstrap dropdown toggle arrow */
    .menu-btn.dropdown-toggle::after {
        display: none !important;
    }
    
    /* Icon and text container - positioned absolutely for smooth morphing */
    .menu-btn .menu-icon,
    .menu-btn .menu-text {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        transition: opacity 0.3s ease, transform 0.3s ease;
        pointer-events: none;
    }
    
    /* Show icon by default */
    .menu-btn .menu-icon {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    
    /* Hide text by default - use visibility to prevent white dot */
    .menu-btn .menu-text {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.5);
        white-space: nowrap;
        visibility: hidden;
    }
    
    /* When menu is open: hide icon, show text with morph effect */
    .menu-btn[aria-expanded="true"] .menu-icon {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.5);
        visibility: hidden;
    }
    
    .menu-btn[aria-expanded="true"] .menu-text {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
        visibility: visible;
    }
    
    /* Adjust button width when expanded to fit text */
    .menu-btn[aria-expanded="true"] {
        min-width: 70px;
    }
    </style>
</head>
<body class="bg-light <?php echo e((auth()->check() && auth()->user()->hasAdminAccess()) ? 'with-sidebar' : 'no-sidebar'); ?>">
    <?php
        $noNavbarPatterns = ['/walk-in', '/requests', 'admin/requests'];
        $currentPath = request()->path();
        $hideNavbar = false;
        foreach ($noNavbarPatterns as $pattern) {
            if ($currentPath === ltrim($pattern, '/')) {
                $hideNavbar = true;
                break;
            }
            // Also hide for any subroutes (e.g., admin/requests/*)
            if (str_starts_with($currentPath, ltrim($pattern, '/').'/')) {
                $hideNavbar = true;
                break;
            }
        }
    ?>
    <?php if(!$hideNavbar): ?>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg main-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('public.plants')); ?>">
                <img src="<?php echo e(asset('images/salengap-modified.png')); ?>" alt="Salenga Logo" class="nav-logo">
                <span class="brand-text">Salenga Farm</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <?php if(auth()->check() && !auth()->user()->hasAdminAccess()): ?>
                <!-- Authenticated non-admin users: show centered nav links -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo e(request()->routeIs('public.plants') ? 'active' : ''); ?>" href="<?php echo e(route('public.plants')); ?>">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo e(request()->routeIs('dashboard.user') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.user')); ?>">
                            <i class="fas fa-gauge me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo e(request()->routeIs('plant-care.*') ? 'active' : ''); ?>" href="<?php echo e(route('plant-care.index')); ?>">
                            <i class="fas fa-leaf me-1"></i> Plant Guide
                        </a>
                    </li>
                    <?php if(auth()->user()->isClient()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo e(request()->routeIs('client-data.*') ? 'active' : ''); ?>" href="<?php echo e(route('client-data.index')); ?>">
                            <i class="fas fa-folder-open me-1"></i> Client Data
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php elseif(auth()->check() && auth()->user()->hasAdminAccess()): ?>
                <!-- Admin users: show Home and Plant Care nav links -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo e(request()->routeIs('public.plants') ? 'active' : ''); ?>" href="<?php echo e(route('public.plants')); ?>">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <?php if(auth()->user()->role === 'super_admin'): ?>
                            <a class="nav-link text-white <?php echo e(request()->routeIs('plant-care.index') || request()->routeIs('plant-care.show') ? 'active' : ''); ?>" href="<?php echo e(route('plant-care.index')); ?>">
                                <i class="fas fa-leaf me-1"></i> Plant Guide
                            </a>
                        <?php else: ?>
                            <a class="nav-link text-white <?php echo e(request()->routeIs('plant-care.admin') || request()->routeIs('plant-care.edit') || request()->routeIs('plant-care.show') ? 'active' : ''); ?>" href="<?php echo e(route('plant-care.admin')); ?>">
                                <i class="fas fa-leaf me-1"></i> Plant Guide
                            </a>
                        <?php endif; ?>
                    </li>
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo e(request()->routeIs('plants.index') ? 'active' : ''); ?>" href="<?php echo e(route('plants.index')); ?>">
                            <i class="fas fa-seedling me-1"></i> Plant Details
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php else: ?>
                <!-- Guests: no centered nav, just spacer -->
                <div class="flex-grow-1"></div>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                <div class="user-section d-flex align-items-center gap-2">
                    <!-- Notification Bell -->
                    <div class="position-relative">
                        <div class="notification-bell notification-bell-trigger" id="navbarNotificationBell" title="Notifications">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger notification-badge" style="display: none;">0</span>
                        </div>
                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown" id="navbarNotificationDropdown" style="z-index: 99999;">
                            <div class="notification-header">
                                <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
                                <div class="d-flex gap-2">
                                    <a href="#" class="mark-all-read" title="Mark all as read">
                                        <i class="fas fa-check-double"></i>
                                    </a>
                                    <a href="#" class="delete-all-notifications" title="Delete all">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="notification-list">
                                <div class="no-notifications">
                                    <i class="fas fa-seedling"></i>
                                    <p>Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(auth()->user()->hasAdminAccess()): ?>
                    <!-- Menu Dropdown for Admins -->
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle menu-btn px-3 py-1" type="button" id="menuDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.98rem; font-weight: 500;">
                            <i class="fas fa-bars menu-icon"></i>
                            <span class="menu-text">Menu</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                            <!-- Mobile-only nav links (Home, Plant Guide) -->
                            <li class="d-md-none"><a class="dropdown-item" href="<?php echo e(route('public.plants')); ?>"><i class="fas fa-home me-2"></i>Home</a></li>
                            <?php if(auth()->user()->role === 'super_admin'): ?>
                                <li class="d-md-none"><a class="dropdown-item" href="<?php echo e(route('plant-care.index')); ?>"><i class="fas fa-leaf me-2"></i>Plant Guide</a></li>
                            <?php else: ?>
                                <li class="d-md-none"><a class="dropdown-item" href="<?php echo e(route('plant-care.admin')); ?>"><i class="fas fa-leaf me-2"></i>Plant Guide</a></li>
                            <?php endif; ?>
                            <li class="d-md-none"><hr class="dropdown-divider"></li>
                            
                            <!-- Desktop nav links (always visible) -->
                            <li><a class="dropdown-item" href="<?php echo e(route('dashboard')); ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('plants.index')); ?>"><i class="fas fa-seedling me-2"></i>Inventory</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('requests.index')); ?>"><i class="fas fa-envelope-open-text me-2"></i>Request</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('walk-in.index')); ?>"><i class="fas fa-cash-register me-2"></i>Point-of-Sale</a></li>
                            <li><a class="dropdown-item" href="/site-visits"><i class="fas fa-map-marked-alt me-2"></i>Site Visits</a></li>
                            <?php if(auth()->user()->isSuperAdmin()): ?>
                            <li><a class="dropdown-item" href="<?php echo e(route('users.index')); ?>"><i class="fas fa-users-cog me-2"></i>Users</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php else: ?>
                    <!-- Regular users: Show Menu on mobile only with their nav links -->
                    <div class="dropdown d-md-none">
                        <button class="btn btn-link dropdown-toggle menu-btn px-3 py-1" type="button" id="menuDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.98rem; font-weight: 500;">
                            <i class="fas fa-bars menu-icon"></i>
                            <span class="menu-text">Menu</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdownMobile">
                            <li><a class="dropdown-item" href="<?php echo e(route('public.plants')); ?>"><i class="fas fa-home me-2"></i>Home</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('dashboard.user')); ?>"><i class="fas fa-gauge me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('plant-care.index')); ?>"><i class="fas fa-leaf me-2"></i>Plant Guide</a></li>
                            <?php if(auth()->user()->isClient()): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('client-data.index')); ?>"><i class="fas fa-folder-open me-2"></i>Client Data</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Profile Dropdown -->
                    <div class="dropdown ms-2">
                        <button class="btn btn-link dropdown-toggle profile-btn" type="button" id="profileDropdown" data-bs-toggle="dropdown">
                            <?php if(auth()->user()->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>" alt="Profile" class="profile-pic">
                            <?php else: ?>
                                <div class="profile-pic-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                                <span><?php echo e(auth()->user()->name); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php else: ?>
                <div class="user-section">
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light me-2">
                        <i class="fas fa-user me-1"></i>Login
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-light">
                        <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <?php if(auth()->check() && auth()->user()->hasAdminAccess()): ?>
    <!-- Admin layout with sidebar -->
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    <?php else: ?>
    <!-- Regular user/guest layout without sidebar -->
    <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?>

    <!-- Toast Container for Notifications -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('js/alerts.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php if(auth()->guard()->check()): ?>
    <script src="<?php echo e(asset('js/push-notifications.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php endif; ?>
    <?php if(request()->routeIs('public.plants') || request()->routeIs('home')): ?>
    <script src="<?php echo e(asset('js/home.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php endif; ?>
    <?php if(request()->routeIs('requests.*') || request()->is('*/plants')): ?>
    <script src="<?php echo e(asset('js/rfq.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php endif; ?>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\CODING\my_Inventory\resources\views/layouts/public.blade.php ENDPATH**/ ?>