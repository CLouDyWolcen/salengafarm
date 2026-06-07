<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v=4" rel="stylesheet">
    <link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/responsive-fixes.css') }}?v={{ time() }}" rel="stylesheet">
    <style>
        /* ========================================
           CUSTOM CHECKBOX STYLING (Bootstrap-independent)
           ======================================== */
        
        /* Base custom checkbox - Perfect square on all devices */
        input[type="checkbox"].custom-checkbox {
            /* Remove all browser defaults */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            
            /* Perfect square dimensions */
            width: 20px;
            height: 20px;
            min-width: 20px;
            min-height: 20px;
            max-width: 20px;
            max-height: 20px;
            
            /* Force square aspect ratio */
            aspect-ratio: 1 / 1;
            flex-shrink: 0;
            
            /* Visual styling */
            border: 2px solid #6c757d;
            border-radius: 4px;
            background-color: #ffffff;
            cursor: pointer;
            
            /* Positioning */
            position: relative;
            display: inline-block;
            vertical-align: middle;
            margin: 0;
            padding: 0;
            
            /* Smooth transition */
            transition: all 0.2s ease;
        }
        
        /* Hover state */
        input[type="checkbox"].custom-checkbox:hover {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
        
        /* Focus state for accessibility */
        input[type="checkbox"].custom-checkbox:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* Checked state */
        input[type="checkbox"].custom-checkbox:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        /* Checkmark using ::after pseudo-element */
        input[type="checkbox"].custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        /* Indeterminate state (for select-all) */
        input[type="checkbox"].custom-checkbox:indeterminate {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        input[type="checkbox"].custom-checkbox:indeterminate::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 8px;
            width: 12px;
            height: 2px;
            background-color: white;
            transform: none;
            border: none;
        }
        
        /* Disabled state */
        input[type="checkbox"].custom-checkbox:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Mobile-specific adjustments */
        @media (max-width: 576px) {
            input[type="checkbox"].custom-checkbox {
                /* Slightly larger for better touch targets on mobile */
                width: 22px;
                height: 22px;
                min-width: 22px;
                min-height: 22px;
                max-width: 22px;
                max-height: 22px;
            }
            
            input[type="checkbox"].custom-checkbox:checked::after {
                left: 7px;
                top: 3px;
                width: 5px;
                height: 10px;
            }
            
            input[type="checkbox"].custom-checkbox:indeterminate::after {
                left: 4px;
                top: 9px;
                width: 12px;
            }
        }
        
        /* Button Sizing Fixes */
        /* Make Export and Add New User buttons same size */
        .btn.btn-success {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Mobile: Smaller buttons */
        @media (max-width: 767px) {
            .btn.btn-success {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
                height: 32px;
            }
            
            .btn.btn-success i {
                font-size: 0.85rem;
            }
            
            #bulk-edit-btn, #bulk-delete-btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
                height: 32px;
            }
        }
        
        /* Bulk action buttons same size */
        #bulk-edit-btn, #bulk-delete-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Table Action Buttons - Fix alignment */
        .btn-group .btn-link {
            padding: 0 !important;
            margin: 0 !important;
            line-height: 1 !important;
            height: 24px !important;
            width: 24px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
        }
        
        .btn-group .btn-link i {
            font-size: 1rem !important;
            line-height: 1 !important;
            margin: 0 !important;
        }
        
        .btn-group {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
        }
        
        /* Nested dropdown structure */
        .dropdown-submenu {
            position: relative;
        }

        /* Make submenus expand inline (below parent) for both mobile and desktop */
        .dropdown-submenu .submenu {
            position: static !important;
            transform: none !important;
            margin-left: 1rem;
            margin-top: 0.5rem;
            box-shadow: none;
            border-left: 3px solid #198754;
            background-color: #f8f9fa;
            display: none;
        }

        .dropdown-submenu .submenu.show {
            display: block;
        }

        /* Add arrow indicator using CSS - positioned inline with text */
        .dropdown-submenu > a[data-submenu-toggle] {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-submenu > a[data-submenu-toggle]::after {
            content: "\f078"; /* FontAwesome chevron-down */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.75rem;
            opacity: 0.6;
            margin-left: auto;
            padding-left: 1rem;
            transition: transform 0.2s ease;
        }

        .dropdown-submenu > a[data-submenu-toggle]:hover::after {
            opacity: 1;
        }

        /* Rotate arrow when submenu is open */
        .dropdown-submenu .submenu.show + a[data-submenu-toggle]::after,
        .dropdown-submenu:has(.submenu.show) > a[data-submenu-toggle]::after {
            transform: rotate(180deg);
        }

        /* Ensure proper z-index */
        .dropdown-menu {
            z-index: 1050;
        }

        .dropdown-submenu .submenu {
            z-index: 1051;
        }
        
        /* Skeleton loading animation */
        .skeleton-loader {
            animation: skeleton-loading 1s linear infinite alternate;
        }
        
        @keyframes skeleton-loading {
            0% {
                background-color: hsl(200, 20%, 80%);
            }
            100% {
                background-color: hsl(200, 20%, 95%);
            }
        }
        
        .skeleton-row {
            height: 60px;
        }
        
        .skeleton-cell {
            padding: 12px;
        }
        
        .skeleton-box {
            height: 16px;
            border-radius: 4px;
            background-color: hsl(200, 20%, 80%);
        }
        
        .skeleton-badge {
            height: 24px;
            width: 60px;
            border-radius: 12px;
            background-color: hsl(200, 20%, 80%);
        }
        
        .skeleton-icon {
            height: 20px;
            width: 20px;
            border-radius: 4px;
            background-color: hsl(200, 20%, 80%);
            display: inline-block;
        }
        
        /* Checkbox and row selection styling */
        .user-checkbox {
            cursor: pointer;
        }
        
        tr.selected-row {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }
        
        tr.selected-row:hover {
            background-color: rgba(13, 110, 253, 0.15) !important;
        }
        
        /* Action buttons styling */
        .btn-group .btn-link {
            transition: transform 0.2s ease;
        }
        
        .btn-group .btn-link:hover {
            transform: scale(1.1);
        }
        
        .view-user-btn i {
            transition: transform 0.3s ease;
        }
        
        /* User details modal styling */
        #userDetailsModal .modal-body h6 {
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        #userDetailsModal .modal-body strong {
            display: inline-block;
            min-width: 150px;
            color: #495057;
        }
        
        #userDetailsModal .badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
        
        /* Bulk action buttons styling */
        #bulk-edit-btn, #bulk-delete-btn {
            white-space: nowrap;
            transition: all 0.3s ease;
        }
        
        /* Bulk action buttons responsive */
        @media (min-width: 992px) {
            /* Desktop: All buttons in one row */
            #bulk-edit-btn, #bulk-delete-btn {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }
        
        @media (min-width: 768px) and (max-width: 991px) {
            /* Tablet: Slightly smaller buttons */
            #bulk-edit-btn, #bulk-delete-btn {
                font-size: 0.85rem;
                padding: 0.45rem 0.85rem;
            }
        }
        
        @media (max-width: 767px) {
            /* Mobile: Stack buttons vertically */
            .d-flex.gap-2.flex-wrap {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            
            #bulk-edit-btn, #bulk-delete-btn {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
                width: 100%;
                justify-content: center;
            }
            
            .btn-success, .dropdown {
                width: 100%;
            }
            
            .dropdown-toggle {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            /* Small mobile: Even more compact */
            #bulk-edit-btn, #bulk-delete-btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.6rem;
            }
            
            /* Make table horizontally scrollable - OVERRIDE responsive-fixes.css with higher specificity */
            .user-table-card .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            /* Table should have minimum width - MUST override global CSS */
            .user-table-card .table {
                min-width: 850px !important;
                white-space: nowrap !important;
            }
            
            /* Ensure checkbox column is properly sized */
            .user-table-card .table th:first-child,
            .user-table-card .table td:first-child {
                width: 50px !important;
                min-width: 50px !important;
                max-width: 50px !important;
                text-align: center !important;
                padding: 0.5rem 0.3rem !important;
            }
            
            /* Compact table cells on mobile */
            .user-table-card .table th,
            .user-table-card .table td {
                padding: 0.5rem 0.4rem !important;
                font-size: 0.85rem !important;
                white-space: nowrap !important;
            }
            
            /* Make action buttons more compact */
            .btn-group .btn-link {
                padding: 0 0.25rem !important;
            }
            
            .btn-group .btn-link i {
                font-size: 0.9rem !important;
            }
            
            /* Adjust title size and spacing */
            h2 {
                font-size: 1.15rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Reduce button text size */
            .btn.btn-success {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }
            
            /* Stack search and filter vertically with minimal spacing */
            .d-flex.gap-2.mb-3 {
                flex-direction: column !important;
                gap: 0.4rem !important;
                margin-bottom: 0.35rem !important;
            }
            
            #searchInput, #roleFilter {
                max-width: 100% !important;
                width: 100% !important;
                height: 38px !important;
                font-size: 0.85rem !important;
            }
            
            /* Statistics cards - 2 columns on mobile with compact sizing */
            .user-stats-row .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
                padding: 0.2rem !important;
                margin-bottom: 0 !important;
            }
            
            .user-stats-row .card {
                margin-bottom: 0 !important;
            }
            
            /* Force smaller padding on mobile by overriding Bootstrap p-2 class */
            .user-stats-row .card-body {
                padding: 0.35rem !important;
            }
            
            /* Compact text in stat cards */
            .user-stats-row h4 {
                font-size: 0.95rem !important;
                margin-bottom: 0 !important;
            }
            
            .user-stats-row p.small,
            .user-stats-row .text-muted {
                font-size: 0.6rem !important;
                margin-bottom: 0 !important;
            }
            
            /* Compact icon containers */
            .user-stats-row .rounded {
                padding: 0.3rem !important;
                width: 30px !important;
                height: 30px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            /* Compact icon sizing */
            .user-stats-row i.fa-lg {
                font-size: 1rem !important;
            }
            
            /* Remove bottom spacing from stat columns - THIS IS KEY */
            .user-stats-row .user-stat-col {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            
            /* Remove ANY bottom spacing from the last row of cards */
            .user-stats-row .col-md-3:nth-child(3),
            .user-stats-row .col-md-3:nth-child(4) {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
        }
        
        /* Modal responsive adjustments */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-footer {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .modal-footer .btn {
                width: 100%;
            }
        }
        
        /* Custom User Management Spacing - Isolated to this page only */
        /* Statistics row - small bottom margin */
        .user-stats-row {
            margin-bottom: 0.75rem !important;
        }
        
        /* Scrollable Users Table Container - Fixed Headers like Inventory */
        .users-table-container {
            min-height: calc(100vh - 230px) !important;
            max-height: calc(100vh - 230px) !important;
            overflow-y: auto !important;
            overflow-x: auto !important;
            position: relative;
        }
        
        /* Sticky table headers for users table */
        .users-table-container thead {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }
        
        .users-table-container thead th {
            background-color: #ffffff !important;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive height adjustments for users table */
        @media (max-width: 1199px) {
            .users-table-container {
                min-height: calc(100vh - 260px) !important;
                max-height: calc(100vh - 260px) !important;
            }
        }
        
        @media (max-width: 991px) {
            .users-table-container {
                min-height: calc(100vh - 300px) !important;
                max-height: calc(100vh - 300px) !important;
            }
        }
        
        @media (max-width: 767px) {
            .users-table-container {
                min-height: calc(100vh - 370px) !important;
                max-height: calc(100vh - 370px) !important;
            }
        }
        
        @media (max-width: 576px) {
            .users-table-container {
                min-height: calc(100vh - 420px) !important;
                max-height: calc(100vh - 420px) !important;
            }
        }
        
        /* Remove bottom margin from stat columns */
        .user-stat-col {
            margin-bottom: 0 !important;
        }
        
        /* Remove margin from stat cards themselves */
        .user-stat-col .card {
            margin-bottom: 0 !important;
        }
        
        /* Remove spacing from notification container */
        .notification-container {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            height: 0 !important;
            overflow: visible !important;
        }
        
        /* Table card with minimal top margin */
        .user-table-card {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Override any global card spacing for user management page */
        .user-table-card.card {
            margin-bottom: 0 !important;
        }
        
        /* Mobile-specific: Remove ALL gaps between stats and table */
        @media (max-width: 767px) {
            .notification-container {
                display: none !important;
            }
            
            .user-stats-row {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            
            .user-table-card {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
            
            /* Remove card top margin on mobile */
            .user-table-card.card {
                margin-top: 0 !important;
            }
        }
        
        /* Mobile-specific improvements */
        @media (max-width: 767px) {
            /* Add padding to main content on mobile */
            .main-content {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            
            /* Title and button row with reduced spacing */
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem;
                margin-top: 10px !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Keep Export and Add User buttons side by side with proper sizing */
            .d-flex.gap-2.flex-wrap {
                width: 100%;
            }
            
            /* Export and Add User button container - side by side */
            .d-flex.gap-2.flex-wrap > .d-flex.gap-2 {
                display: flex !important;
                flex-direction: row !important;
                gap: 0.5rem !important;
                width: 100%;
            }
            
            /* Each button takes equal space */
            .d-flex.gap-2.flex-wrap > .d-flex.gap-2 > .dropdown,
            .d-flex.gap-2.flex-wrap > .d-flex.gap-2 > a {
                flex: 1;
            }
            
            /* Button sizing */
            .d-flex.gap-2.flex-wrap > .d-flex.gap-2 .btn {
                width: 100%;
                font-size: 0.85rem;
                padding: 0.5rem 0.75rem;
                white-space: nowrap;
            }
            
            /* Bulk action buttons full width when visible */
            #bulk-edit-btn, #bulk-delete-btn {
                width: 100%;
            }
            
            /* Search and filter on mobile */
            .d-flex.gap-2.mb-3 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            
            #searchInput, #roleFilter, #clearFilters {
                max-width: 100% !important;
                width: 100% !important;
            }
        }
        
        /* Responsive adjustments for user management */
        @media (min-width: 992px) {
            /* Desktop: tight spacing */
            .user-stats-row {
                margin-bottom: 0.75rem !important;
            }
            
            .user-stat-col {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            
            .user-table-card {
                margin-top: 0 !important;
            }
        }
        
        @media (min-width: 768px) and (max-width: 991px) {
            /* Tablet: moderate spacing */
            .user-stats-row {
                margin-bottom: 1rem !important;
            }
            
            .user-stat-col {
                margin-bottom: 0.5rem !important;
            }
            
            .user-table-card {
                margin-top: 0.25rem !important;
            }
            
            /* Ensure table scrolls on tablet if needed - OVERRIDE responsive-fixes.css */
            .user-table-card .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .user-table-card .table {
                min-width: 850px !important;
                white-space: nowrap !important;
            }
            
            .user-table-card .table th,
            .user-table-card .table td {
                white-space: nowrap !important;
            }
        }
        
        @media (max-width: 767px) {
            /* Mobile: Small gap between statistics and table */
            .user-stats-row {
                margin-bottom: 0.5rem !important;
                padding-bottom: 0 !important;
            }
            
            .user-stat-col {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }
            
            /* Remove Bootstrap's row bottom margin */
            .user-stats-row.row {
                margin-bottom: 0.5rem !important;
                --bs-gutter-y: 0 !important;
            }
            
            .user-table-card {
                margin-top: 0 !important;
            }
            
            /* Ensure table scrolls horizontally on mobile - OVERRIDE responsive-fixes.css */
            .user-table-card .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .user-table-card .table {
                min-width: 850px !important;
                white-space: nowrap !important;
            }
            
            .user-table-card .table th,
            .user-table-card .table td {
                padding: 0.5rem 0.4rem !important;
                font-size: 0.85rem !important;
                white-space: nowrap !important;
            }
        }
        
        @media (max-width: 576px) {
            /* Small mobile: more spacing for readability */
            .user-stats-row {
                margin-bottom: 1.25rem !important;
            }
            
            .user-stat-col {
                margin-bottom: 1rem !important;
            }
            
            .user-table-card {
                margin-top: 0.75rem !important;
            }
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
            <div style="padding-top: 0; padding-bottom: 0;">
                <div class="p-0" style="margin-bottom: 0;">
                    <!-- Title -->
                    <div class="d-flex justify-content-between align-items-center" style="margin-top: 15px; margin-bottom: 15px;">
                        <h2 class="mb-0">User Management</h2>
                        
                        <div class="d-flex gap-2 flex-wrap justify-content-end">
                            <!-- Bulk Action Buttons (Hidden by default) -->
                            <button type="button" id="bulk-edit-btn" class="btn btn-primary" style="display: none;">
                                <i class="fas fa-edit me-1"></i>Bulk Edit (<span id="selected-count">0</span>)
                            </button>
                            <button type="button" id="bulk-delete-btn" class="btn btn-danger" style="display: none;">
                                <i class="fas fa-trash me-1"></i>Bulk Delete (<span id="selected-count-delete">0</span>)
                            </button>
                            
                            <!-- Export and Add User buttons side by side -->
                            <div class="d-flex gap-2">
                                <!-- Export Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdownUsers" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="fas fa-file-export me-1"></i>Export
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdownUsers">
                                        <!-- All Users -->
                                        <li class="dropdown-submenu">
                                            <a class="dropdown-item" href="#" data-submenu-toggle>
                                                <i class="fas fa-users me-2 text-primary"></i>All Users
                                            </a>
                                            <ul class="dropdown-menu submenu">
                                                <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'xlsx', 'role' => 'all']) }}">
                                                    <i class="fas fa-file-excel me-2 text-success"></i>Excel (.xlsx)
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'csv', 'role' => 'all']) }}">
                                                    <i class="fas fa-file-csv me-2 text-info"></i>CSV (.csv)
                                                </a></li>
                                            </ul>
                                        </li>
                                        
                                        <!-- Clients Only -->
                                        <li class="dropdown-submenu">
                                            <a class="dropdown-item" href="#" data-submenu-toggle>
                                                <i class="fas fa-user-tie me-2 text-success"></i>Clients Only
                                            </a>
                                            <ul class="dropdown-menu submenu">
                                                <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'xlsx', 'role' => 'client']) }}">
                                                    <i class="fas fa-file-excel me-2 text-success"></i>Excel (.xlsx)
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'csv', 'role' => 'client']) }}">
                                                    <i class="fas fa-file-csv me-2 text-info"></i>CSV (.csv)
                                                </a></li>
                                            </ul>
                                        </li>
                                        
                                        <!-- Admins Only -->
                                        <li class="dropdown-submenu">
                                            <a class="dropdown-item" href="#" data-submenu-toggle>
                                                <i class="fas fa-user-shield me-2 text-danger"></i>Admins Only
                                            </a>
                                            <ul class="dropdown-menu submenu">
                                                <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'xlsx', 'role' => 'admin']) }}">
                                                    <i class="fas fa-file-excel me-2 text-success"></i>Excel (.xlsx)
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'csv', 'role' => 'admin']) }}">
                                                    <i class="fas fa-file-csv me-2 text-info"></i>CSV (.csv)
                                                </a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                
                                <a href="{{ route('users.create') }}" class="btn btn-success">
                                    <i class="fas fa-user-plus me-1"></i> Add New User
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filter Bar -->
                    <div class="d-flex gap-2 mb-3">
                        <input type="text" 
                               id="searchInput"
                               class="form-control" 
                               placeholder="Search users..." 
                               value="{{ request('search') }}"
                               style="max-width: 300px;">
                        
                        <select id="roleFilter" class="form-select" style="max-width: 200px;">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Clients</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                        </select>
                        
                        <button type="button" id="clearFilters" class="btn btn-secondary" style="display: none;">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row user-stats-row">
                        <div class="col-md-3 col-sm-6 user-stat-col">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-0 small">Total Users</p>
                                            <h4 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h4>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-users fa-lg text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 user-stat-col">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-0 small">Total Clients</p>
                                            <h4 class="mb-0 fw-bold text-success">{{ $stats['total_clients'] }}</h4>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-user-tie fa-lg text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 user-stat-col">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-0 small">Total Admins</p>
                                            <h4 class="mb-0 fw-bold text-danger">{{ $stats['total_admins'] }}</h4>
                                        </div>
                                        <div class="bg-danger bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-user-shield fa-lg text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 user-stat-col">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-0 small">New This Month</p>
                                            <h4 class="mb-0 fw-bold text-info">{{ $stats['new_this_month'] }}</h4>
                                        </div>
                                        <div class="bg-info bg-opacity-10 p-2 rounded">
                                            <i class="fas fa-user-plus fa-lg text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification Container -->
                    <div class="notification-container">
                        @if(session('success') || session('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @if(session('success'))
                                    if (window.PushNotifications) {
                                        window.PushNotifications.show('success', '{{ session('success') }}', true);
                                    }
                                @endif
                                
                                @if(session('error'))
                                    if (window.PushNotifications) {
                                        window.PushNotifications.show('danger', '{{ session('error') }}', false);
                                    }
                                @endif
                            });
                        </script>
                        @endif
                    </div>
                    
                    <!-- User Accounts Section -->
                    <div class="card user-table-card">
                        <div class="card-body">
                            <div class="table-responsive users-table-container">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px; text-align: center;">
                                                <input type="checkbox" id="select-all-users" class="custom-checkbox">
                                            </th>
                                            <th style="width: 60px;">No.</th>
                                            <th style="width: 150px;">First Name</th>
                                            <th style="width: 150px;">Last Name</th>
                                            <th style="width: 220px;">Email</th>
                                            <th style="width: 150px;">Contact Number</th>
                                            <th style="width: 100px;">Role</th>
                                            <th class="text-end" style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $index => $user)
                                        <tr data-user-id="{{ $user->id }}">
                                            <td style="text-align: center;">
                                                <input type="checkbox" class="custom-checkbox user-checkbox" value="{{ $user->id }}">
                                            </td>
                                            <td style="text-align: left;">{{ $index + 1 }}</td>
                                            <td style="text-align: left;">{{ $user->first_name }}</td>
                                            <td style="text-align: left;">{{ $user->last_name }}</td>
                                            <td style="text-align: left;">{{ $user->email }}</td>
                                            <td style="text-align: left;">{{ $user->contact_number ?? 'N/A' }}</td>
                                            <td style="text-align: left;">
                                                @php
                                                    $badgeColor = match($user->role) {
                                                        'super_admin' => 'bg-warning text-dark',
                                                        'admin' => 'bg-danger',
                                                        'client' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeColor }}">
                                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-link text-info p-0 view-user-btn" 
                                                            data-user-id="{{ $user->id }}"
                                                            data-user-data="{{ json_encode($user) }}"
                                                            title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-link text-success p-0" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-link text-danger p-0 delete-user-btn" 
                                                            data-user-id="{{ $user->id }}" 
                                                            data-user-name="{{ $user->first_name }} {{ $user->last_name }}"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <form id="delete-user-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No users found</p>
                                                @if(request('search') || request('role') != 'all')
                                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-times me-1"></i> Clear Filters
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Mobile Scroll Indicator -->
                            <div class="d-md-none text-center text-muted py-2" style="font-size: 0.75rem; background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                                <i class="fas fa-arrows-alt-h me-1"></i> Swipe left/right to see all columns
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete User Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Are you sure you want to delete this user?</h5>
                    <p class="text-muted mb-2"><strong id="deleteUserName"></strong></p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUser">
                        <i class="fas fa-trash me-1"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Role Request Confirmation Modal -->
    <div class="modal fade" id="approveRoleRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Client Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #198754;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Approve this client request?</h5>
                    <p class="text-muted mb-2"><strong id="approveRequestName"></strong></p>
                    <p class="text-success"><small>This user will be granted client access and receive a notification.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form id="approveRoleRequestForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Yes, Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Role Request Confirmation Modal -->
    <div class="modal fade" id="rejectRoleRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Client Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #dc3545;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Reject this client request?</h5>
                    <p class="text-muted mb-2"><strong id="rejectRequestName"></strong></p>
                    <p class="text-danger"><small>The user will be notified that their request was reviewed.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form id="rejectRoleRequestForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i> Yes, Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Done Role Request Confirmation Modal -->
    <div class="modal fade" id="doneRoleRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Remove Role Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #0d6efd;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Remove this role request from the list?</h5>
                    <p class="text-muted mb-2"><strong id="doneRequestName"></strong></p>
                    <p class="text-muted"><small>This request has been processed and will be removed from the list.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form id="doneRoleRequestForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i> Yes, Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Bulk Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Are you sure you want to delete <span id="bulk-delete-count">0</span> user(s)?</h5>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDelete">
                        <i class="fas fa-trash me-1"></i> Yes, Delete All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Edit Modal -->
    <div class="modal fade" id="bulkEditModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Bulk Edit Users</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Editing <span id="bulk-edit-count">0</span> user(s)</p>
                    <form id="bulkEditForm">
                        <div class="mb-3">
                            <label for="bulk-role" class="form-label">Change Role</label>
                            <select class="form-select" id="bulk-role" name="role">
                                <option value="">-- Keep Current --</option>
                                <option value="client">Client</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Only selected fields will be updated. Leave fields unchanged to keep current values.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmBulkEdit">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-circle me-2"></i>User Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-id-card me-2"></i>Personal Information
                            </h6>
                            <div class="mb-2">
                                <strong>User ID:</strong>
                                <span id="detail-user-id" class="text-muted"></span>
                            </div>
                            <div class="mb-2">
                                <strong>First Name:</strong>
                                <span id="detail-first-name" class="text-muted"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Last Name:</strong>
                                <span id="detail-last-name" class="text-muted"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong>
                                <span id="detail-email" class="text-muted"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Contact Number:</strong>
                                <span id="detail-contact" class="text-muted"></span>
                            </div>
                            <div class="mb-2" id="detail-gender-row">
                                <strong>Gender:</strong>
                                <span id="detail-gender" class="text-muted"></span>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-success border-bottom pb-2 mb-3">
                                <i class="fas fa-user-shield me-2"></i>Account Information
                            </h6>
                            <div class="mb-2">
                                <strong>Role:</strong>
                                <span id="detail-role" class="badge"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Account Type:</strong>
                                <span id="detail-account-type" class="text-muted"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Is Client:</strong>
                                <span id="detail-is-client" class="badge"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Email Verified:</strong>
                                <span id="detail-email-verified" class="badge"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Registration Date:</strong>
                                <span id="detail-created-at" class="text-muted"></span>
                            </div>
                        </div>

                        <!-- Company/Address Information -->
                        <div class="col-12 mb-3">
                            <h6 class="text-warning border-bottom pb-2 mb-3" id="detail-section-header">
                                <i class="fas fa-building me-2"></i>Company & Address
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-2" id="detail-company-address-row">
                                    <strong>Company Address:</strong>
                                    <span id="detail-company-address" class="text-muted"></span>
                                </div>
                                <div class="col-md-6 mb-2" id="detail-address-row">
                                    <strong>Personal Address:</strong>
                                    <span id="detail-address" class="text-muted"></span>
                                </div>
                                <div class="col-md-6 mb-2" id="detail-property-type-row">
                                    <strong>Property Type:</strong>
                                    <span id="detail-property-type" class="text-muted"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Page Access -->
                        <div class="col-12">
                            <h6 class="text-danger border-bottom pb-2 mb-3">
                                <i class="fas fa-key me-2"></i>Page Access Permissions
                            </h6>
                            <div id="detail-page-access" class="d-flex flex-wrap gap-2">
                                <!-- Page access badges will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <a href="#" id="detail-edit-link" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/push-notifications-global.js') }}?v=fadefix{{ time() }}"></script>
    <script src="{{ asset('js/alerts.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/push-notifications.js') }}?v={{ time() }}"></script>
    
    <script>
        $(document).ready(function() {
            let searchTimeout;
            
            // ===== AJAX SEARCH AND FILTER =====
            function filterUsers() {
                const search = $('#searchInput').val();
                const role = $('#roleFilter').val();
                
                // Show/hide clear button
                if (search || role !== 'all') {
                    $('#clearFilters').show();
                } else {
                    $('#clearFilters').hide();
                }
                
                // Show skeleton loading (same number as current rows)
                showSkeletonLoading();
                
                // Make AJAX request
                $.ajax({
                    url: '{{ route("users.index") }}',
                    type: 'GET',
                    data: {
                        search: search,
                        role: role,
                        ajax: 1
                    },
                    success: function(response) {
                        // Update table body
                        $('tbody').html(response);
                        
                        // Reinitialize delete buttons
                        initializeDeleteButtons();
                    },
                    error: function() {
                        console.error('Failed to filter users');
                        $('tbody').html('<tr><td colspan="8" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error loading users</td></tr>');
                    }
                });
            }
            
            // Show skeleton loading rows (matches current row count)
            function showSkeletonLoading() {
                // Count current rows (excluding empty state)
                const currentRowCount = $('tbody tr').not(':has(td[colspan])').length;
                
                // Use current count, or default to 5 if no rows
                const rowCount = currentRowCount > 0 ? currentRowCount : 5;
                
                // Generate skeleton rows
                let skeletonRows = '';
                const widthVariations = [
                    [30, 80, 80, 90, 70],
                    [30, 75, 85, 95, 65],
                    [30, 70, 80, 85, 75],
                    [30, 85, 75, 90, 80],
                    [30, 80, 70, 88, 72],
                    [30, 78, 82, 92, 68],
                    [30, 82, 78, 87, 73],
                    [30, 76, 84, 91, 77],
                    [30, 83, 76, 89, 71],
                    [30, 79, 81, 93, 74]
                ];
                
                for (let i = 0; i < rowCount; i++) {
                    const widths = widthVariations[i % widthVariations.length];
                    skeletonRows += `
                        <tr class="skeleton-row">
                            <td class="skeleton-cell text-center"><div class="skeleton-box skeleton-loader" style="width: 18px; height: 18px; margin: 0 auto;"></div></td>
                            <td class="skeleton-cell"><div class="skeleton-box skeleton-loader" style="width: ${widths[0]}px;"></div></td>
                            <td class="skeleton-cell"><div class="skeleton-box skeleton-loader" style="width: ${widths[1]}%;"></div></td>
                            <td class="skeleton-cell"><div class="skeleton-box skeleton-loader" style="width: ${widths[2]}%;"></div></td>
                            <td class="skeleton-cell"><div class="skeleton-box skeleton-loader" style="width: ${widths[3]}%;"></div></td>
                            <td class="skeleton-cell"><div class="skeleton-box skeleton-loader" style="width: ${widths[4]}%;"></div></td>
                            <td class="skeleton-cell"><div class="skeleton-badge skeleton-loader"></div></td>
                            <td class="skeleton-cell text-end">
                                <span class="skeleton-icon skeleton-loader me-2"></span>
                                <span class="skeleton-icon skeleton-loader"></span>
                            </td>
                        </tr>
                    `;
                }
                
                $('tbody').html(skeletonRows);
            }
            
            // Search input with debounce
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterUsers, 500);
            });
            
            // Role filter change
            $('#roleFilter').on('change', filterUsers);
            
            // Clear filters
            $('#clearFilters').on('click', function() {
                $('#searchInput').val('');
                $('#roleFilter').val('all');
                filterUsers();
            });
            
            // Initialize clear button visibility
            if ($('#searchInput').val() || $('#roleFilter').val() !== 'all') {
                $('#clearFilters').show();
            }
            
            // ===== DELETE USER FUNCTIONALITY =====
            function initializeDeleteButtons() {
                $('.delete-user-btn').off('click').on('click', function() {
                    const userIdToDelete = $(this).data('user-id');
                    const userName = $(this).data('user-name');
                    $('#deleteUserName').text(userName);
                    
                    $('#confirmDeleteUser').off('click').on('click', function() {
                        $('#delete-user-form-' + userIdToDelete).submit();
                    });
                    
                    $('#deleteUserModal').modal('show');
                });
                
                // Reinitialize checkbox handlers after AJAX update
                attachCheckboxHandlers();
                
                // Reinitialize view user buttons after AJAX update
                initializeViewUserButtons();
            }
            
            initializeDeleteButtons();
            
            // ===== VIEW USER DETAILS FUNCTIONALITY =====
            function initializeViewUserButtons() {
                $('.view-user-btn').off('click').on('click', function() {
                    const userData = $(this).data('user-data');
                    showUserDetails(userData);
                });
            }
            
            initializeViewUserButtons();
            
            function showUserDetails(user) {
                // Personal Information
                $('#detail-user-id').text('U-' + String(user.id).padStart(5, '0'));
                $('#detail-first-name').text(user.first_name || 'N/A');
                $('#detail-last-name').text(user.last_name || 'N/A');
                $('#detail-email').text(user.email || 'N/A');
                $('#detail-contact').text(user.contact_number || 'N/A');
                
                // Account Information
                const roleBadgeColor = {
                    'super_admin': 'bg-warning text-dark',
                    'admin': 'bg-danger',
                    'client': 'bg-success'
                };
                const roleText = user.role ? user.role.replace('_', ' ').split(' ').map(word => 
                    word.charAt(0).toUpperCase() + word.slice(1)
                ).join(' ') : 'N/A';
                $('#detail-role').attr('class', 'badge ' + (roleBadgeColor[user.role] || 'bg-secondary')).text(roleText);
                
                $('#detail-account-type').text(user.account_type ? user.account_type.charAt(0).toUpperCase() + user.account_type.slice(1) : 'N/A');
                
                $('#detail-is-client').attr('class', 'badge ' + (user.is_client ? 'bg-success' : 'bg-secondary'))
                    .text(user.is_client ? 'Yes' : 'No');
                
                $('#detail-email-verified').attr('class', 'badge ' + (user.email_verified_at ? 'bg-success' : 'bg-warning'))
                    .text(user.email_verified_at ? 'Verified' : 'Not Verified');
                
                const createdDate = user.created_at ? new Date(user.created_at).toLocaleString() : 'N/A';
                $('#detail-created-at').text(createdDate);
                
                // Conditional display based on account type
                const accountType = user.account_type || 'individual';
                
                if (accountType === 'individual') {
                    // Show individual fields
                    $('#detail-gender-row').show();
                    $('#detail-address-row').show();
                    $('#detail-property-type-row').show();
                    
                    // Hide company fields
                    $('#detail-company-name-row').hide();
                    $('#detail-company-address-row').hide();
                    
                    // Update section header
                    $('#detail-section-header').html('<i class="fas fa-home me-2"></i>Personal Information');
                    
                    // Populate individual fields
                    $('#detail-gender').text(user.gender ? user.gender.charAt(0).toUpperCase() + user.gender.slice(1) : 'N/A');
                    $('#detail-address').text(user.address || 'N/A');
                    $('#detail-property-type').text(user.property_type || 'N/A');
                } else {
                    // Show company fields
                    $('#detail-company-name-row').show();
                    $('#detail-company-address-row').show();
                    
                    // Hide individual fields
                    $('#detail-gender-row').hide();
                    $('#detail-address-row').hide();
                    $('#detail-property-type-row').hide();
                    
                    // Update section header
                    $('#detail-section-header').html('<i class="fas fa-building me-2"></i>Company Information');
                    
                    // Populate company fields
                    $('#detail-company-address').text(user.company_address || 'N/A');
                }
                
                // Page Access
                let pageAccess = [];
                try {
                    pageAccess = user.page_access ? JSON.parse(user.page_access) : [];
                } catch (e) {
                    pageAccess = [];
                }
                
                const pageAccessContainer = $('#detail-page-access');
                pageAccessContainer.empty();
                
                if (pageAccess.length > 0) {
                    pageAccess.forEach(page => {
                        const pageName = page.replace('_', ' ').split(' ').map(word => 
                            word.charAt(0).toUpperCase() + word.slice(1)
                        ).join(' ');
                        pageAccessContainer.append(`<span class="badge bg-info">${pageName}</span>`);
                    });
                } else {
                    pageAccessContainer.append('<span class="text-muted">No page access configured</span>');
                }
                
                // Set edit link
                $('#detail-edit-link').attr('href', '/users/' + user.id + '/edit');
                
                // Show modal
                $('#userDetailsModal').modal('show');
            }
            
            // ===== CHECKBOX AND BULK ACTIONS FUNCTIONALITY =====
            function attachCheckboxHandlers() {
                // Handle select all checkbox
                $('#select-all-users').off('change').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $('.user-checkbox').prop('checked', isChecked);
                    
                    // Update row highlighting
                    if (isChecked) {
                        $('tbody tr').addClass('selected-row');
                    } else {
                        $('tbody tr').removeClass('selected-row');
                    }
                    
                    toggleBulkActionButtons();
                });
                
                // Handle individual user checkboxes
                $('.user-checkbox').off('change').on('change', function() {
                    const row = $(this).closest('tr');
                    
                    // Toggle row highlighting
                    if ($(this).is(':checked')) {
                        row.addClass('selected-row');
                    } else {
                        row.removeClass('selected-row');
                    }
                    
                    // Update select all checkbox state
                    const totalCheckboxes = $('.user-checkbox').length;
                    const checkedCheckboxes = $('.user-checkbox:checked').length;
                    
                    $('#select-all-users').prop('checked', totalCheckboxes === checkedCheckboxes);
                    $('#select-all-users').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
                    
                    toggleBulkActionButtons();
                });
            }
            
            // Initialize checkbox handlers on page load
            attachCheckboxHandlers();
            
            // Function to show/hide bulk action buttons
            function toggleBulkActionButtons() {
                const checkedCount = $('.user-checkbox:checked').length;
                
                if (checkedCount > 0) {
                    $('#bulk-edit-btn, #bulk-delete-btn').show();
                    $('#selected-count, #selected-count-delete').text(checkedCount);
                } else {
                    $('#bulk-edit-btn, #bulk-delete-btn').hide();
                }
            }
            
            // Bulk Edit Button Click
            $('#bulk-edit-btn').on('click', function() {
                const selectedCount = $('.user-checkbox:checked').length;
                $('#bulk-edit-count').text(selectedCount);
                $('#bulkEditModal').modal('show');
            });
            
            // Bulk Delete Button Click
            $('#bulk-delete-btn').on('click', function() {
                const selectedCount = $('.user-checkbox:checked').length;
                $('#bulk-delete-count').text(selectedCount);
                $('#bulkDeleteModal').modal('show');
            });
            
            // Confirm Bulk Edit
            $('#confirmBulkEdit').on('click', function() {
                const selectedIds = [];
                $('.user-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                
                const role = $('#bulk-role').val();
                
                if (selectedIds.length === 0) {
                    alert('No users selected');
                    return;
                }
                
                // Show loading state
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
                
                // Make AJAX request to bulk update
                $.ajax({
                    url: '{{ route("users.bulk-update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_ids: selectedIds,
                        role: role
                    },
                    success: function(response) {
                        $('#bulkEditModal').modal('hide');
                        
                        // Show success message
                        if (window.PushNotifications) {
                            window.PushNotifications.show('success', response.message || 'Users updated successfully', true);
                        }
                        
                        // Reload the page to show updated data
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        alert('Error updating users: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        $('#confirmBulkEdit').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
                    }
                });
            });
            
            // Confirm Bulk Delete
            $('#confirmBulkDelete').on('click', function() {
                const selectedIds = [];
                $('.user-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                
                if (selectedIds.length === 0) {
                    alert('No users selected');
                    return;
                }
                
                // Show loading state
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Deleting...');
                
                // Make AJAX request to bulk delete
                $.ajax({
                    url: '{{ route("users.bulk-delete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_ids: selectedIds
                    },
                    success: function(response) {
                        $('#bulkDeleteModal').modal('hide');
                        
                        // Show success message
                        if (window.PushNotifications) {
                            window.PushNotifications.show('success', response.message || 'Users deleted successfully', true);
                        }
                        
                        // Reload the page to show updated data
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        alert('Error deleting users: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        $('#confirmBulkDelete').prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Yes, Delete All');
                    }
                });
            });
            
            // Reset bulk edit form when modal closes
            $('#bulkEditModal').on('hidden.bs.modal', function() {
                $('#bulkEditForm')[0].reset();
                $('#confirmBulkEdit').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Changes');
            });
            
            // Reset bulk delete button when modal closes
            $('#bulkDeleteModal').on('hidden.bs.modal', function() {
                $('#confirmBulkDelete').prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Yes, Delete All');
            });
            
            // ===== CUSTOM NESTED DROPDOWN FUNCTIONALITY =====
            const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
            
            dropdownSubmenus.forEach(function(submenu) {
                const toggle = submenu.querySelector('[data-submenu-toggle]');
                const submenuDropdown = submenu.querySelector('.submenu');
                
                if (!toggle || !submenuDropdown) return;
                
                // Click handler for both mobile and desktop
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // Close other submenus
                    document.querySelectorAll('.dropdown-submenu .submenu.show').forEach(function(otherSubmenu) {
                        if (otherSubmenu !== submenuDropdown) {
                            otherSubmenu.classList.remove('show');
                        }
                    });
                    
                    // Toggle this submenu
                    submenuDropdown.classList.toggle('show');
                    
                    return false;
                }, true);
            });
            
            // Prevent clicks inside submenus from closing the main dropdown
            document.querySelectorAll('.dropdown-submenu .submenu').forEach(function(submenu) {
                submenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
            
            // Close all submenus when main dropdown closes
            const mainDropdown = document.getElementById('exportDropdownUsers');
            if (mainDropdown) {
                mainDropdown.addEventListener('hidden.bs.dropdown', function() {
                    document.querySelectorAll('.dropdown-submenu .submenu.show').forEach(function(submenu) {
                        submenu.classList.remove('show');
                    });
                });
            }
            
            // ===== EXPORT LOADING INDICATOR =====
            $('.dropdown-submenu .submenu a').on('click', function(e) {
                const link = $(this);
                const icon = link.find('i');
                
                if (icon.length) {
                    const originalClasses = icon.attr('class');
                    icon.attr('class', 'fas fa-spinner fa-spin me-2');
                    
                    setTimeout(function() {
                        icon.attr('class', originalClasses);
                    }, 3000);
                }
            });
        });
        
        // Check if we should activate the role requests tab
        @if(session('activeTab') === 'role-requests')
        document.addEventListener('DOMContentLoaded', function() {
            const roleRequestsTab = document.getElementById('role-requests-tab');
            const roleRequestsPane = document.getElementById('role-requests');
            const accountsTab = document.getElementById('accounts-tab');
            const accountsPane = document.getElementById('accounts');
            
            // Deactivate accounts tab
            accountsTab.classList.remove('active');
            accountsPane.classList.remove('show', 'active');
            
            // Activate role requests tab
            roleRequestsTab.classList.add('active');
            roleRequestsPane.classList.add('show', 'active');
        });
        @endif
        
        // Approve Role Request Modal
        $('.approve-role-request-btn').on('click', function() {
            const requestId = $(this).data('request-id');
            const requestName = $(this).data('request-name');
            $('#approveRequestName').text(requestName);
            $('#approveRoleRequestForm').attr('action', '/users/role-requests/' + requestId + '/approve');
            $('#approveRoleRequestModal').modal('show');
        });
        
        // Reject Role Request Modal
        $('.reject-role-request-btn').on('click', function() {
            const requestId = $(this).data('request-id');
            const requestName = $(this).data('request-name');
            $('#rejectRequestName').text(requestName);
            $('#rejectRoleRequestForm').attr('action', '/users/role-requests/' + requestId + '/reject');
            $('#rejectRoleRequestModal').modal('show');
        });
        
        // Done Role Request Modal
        $('.done-role-request-btn').on('click', function() {
            const requestId = $(this).data('request-id');
            const requestName = $(this).data('request-name');
            $('#doneRequestName').text(requestName);
            $('#doneRoleRequestForm').attr('action', '/users/role-requests/' + requestId);
            $('#doneRoleRequestModal').modal('show');
        });
        
        // ===== SIDEBAR TOGGLE FOR MOBILE =====
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebarToggle && sidebar && overlay) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            
            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
            
            // Close sidebar when clicking a link (optional, for better UX)
            const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            });
        }
    </script>
</body>
</html>
