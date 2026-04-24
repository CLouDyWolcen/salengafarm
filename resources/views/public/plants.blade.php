@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="{{ asset('css/public.css') }}?v=3" rel="stylesheet">
    <link href="{{ asset('css/plant-selection.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">
    <link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Preloader styles - inline to prevent FOUC -->
    <style>
        /* Ensure notification dropdown is above all buttons including RFQ */
        #requestPlantsBtn,
        .search-controls-container,
        .search-controls-container * {
            z-index: 100 !important;
            position: relative;
        }
        
        .notification-dropdown {
            z-index: 99999 !important;
        }
        
        /* View Inquiry button color states */
        #viewRequestBtn {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        #viewRequestBtn:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        
        #viewRequestBtn.has-plants {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        #viewRequestBtn.has-plants:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        #page-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        #page-preloader .preloader-logo {
            width: 150px;
            height: 150px;
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .page-content {
            opacity: 0;
            transition: opacity 0.3s ease-in;
        }
        .page-content.loaded {
            opacity: 1;
        }
        
        /* Force navbar links to be consistent size */
        .navbar-nav .nav-link {
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            padding: 0.5rem 1rem !important;
        }
        
        /* Force navbar height to be consistent */
        .main-nav {
            height: 60px !important;
            min-height: 60px !important;
            max-height: 60px !important;
        }
    </style>

    <!-- Early script to ensure clean state -->
    <script  >
        // Run this immediately to clean up any previous selection state
        (function() {
            // Remove selection mode class
            if (document.body) {
                document.body.classList.remove('plant-selection-mode');
            }

            // Make sure CSS is fresh by adding a random query param to the URL
            document.querySelectorAll('link[href*="public.css"]').forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('v', Date.now());
                link.href = url.toString();
            });

            console.log('Early cleanup script executed');
        })();

        // Add scrollToContent function
        function scrollToContent() {
            // First, add the hidden class to the splash page to trigger its animation
            const splashPage = document.getElementById('splashPage');
            if (splashPage) {
                splashPage.classList.add('hidden');
            }
            
            // Scroll to the top of the page
            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 300); // Small delay to let the animation start
        }
    </script>
    <style >
        /* Custom RFQ Modal Styles - Optimized for Screen Space */
        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            overflow-y: auto;
            padding: 10px;
            box-sizing: border-box;
        }

        .custom-modal-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            min-height: calc(100vh - 20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
        }

        .custom-modal-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-height: 95vh;
            display: flex;
            flex-direction: column;
        }

        .custom-modal-header {
            background-color: #198754;
            color: white;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .custom-modal-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .custom-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .custom-close-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .custom-modal-body {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
        }

        .custom-modal-footer {
            padding: 10px 15px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        /* RFQ Form Specific Styles - More Compact Layout */
        .rfq-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .rfq-header h2 {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0 0 3px 0;
            color: #333;
        }

        .rfq-header h3 {
            font-size: 1rem;
            font-weight: normal;
            margin: 0;
            color: #666;
        }

        .rfq-info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .rfq-card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .rfq-card-header {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 6px 6px 0 0;
        }

        .rfq-card-header h5 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
        }

        .rfq-card-body {
            padding: 12px;
        }

        .rfq-info-table {
            width: 100%;
        }

        .rfq-info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .rfq-info-row:last-child {
            margin-bottom: 0;
        }

        .rfq-info-label {
            font-weight: bold;
            min-width: 65px;
            margin-right: 12px;
            color: #333;
            font-size: 0.85rem;
        }

        .rfq-info-value {
            flex: 1;
            color: #555;
            line-height: 1.3;
            font-size: 0.85rem;
        }

        .rfq-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .rfq-detail-item {
            margin-bottom: 10px;
        }

        .rfq-detail-label {
            font-weight: bold;
            margin-bottom: 4px;
            color: #333;
            font-size: 0.85rem;
        }

        .rfq-detail-value {
            color: #555;
            line-height: 1.3;
            font-size: 0.85rem;
        }

        .rfq-table-container {
            padding: 0 !important;
        }

        .rfq-table-wrapper {
            overflow-x: auto;
            border: 1px solid #dee2e6;
            max-height: 250px;
            overflow-y: auto;
        }

        .rfq-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 0.8rem;
        }

        .rfq-table th {
            background-color: #f8f9fa;
            padding: 8px 6px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #dee2e6;
            color: #333;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .rfq-table td {
            padding: 8px 6px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
            text-align: center;
        }

        .rfq-table input,
        .rfq-table textarea {
            width: 100%;
            padding: 4px 6px;
            border: 1px solid #ced4da;
            border-radius: 3px;
            font-size: 0.8rem;
            box-sizing: border-box;
        }

        .rfq-table textarea {
            min-height: 40px;
            resize: vertical;
        }

        .rfq-table input:focus,
        .rfq-table textarea:focus {
            outline: none;
            border-color: #198754;
            box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.25);
        }

        .rfq-terms-list {
            padding-left: 18px;
            margin: 0;
        }

        .rfq-terms-list li {
            margin-bottom: 8px;
            line-height: 1.4;
            color: #555;
            font-size: 0.85rem;
        }

        .rfq-terms-list li:last-child {
            margin-bottom: 0;
        }

        .rfq-btn {
            padding: 6px 14px;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .rfq-btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .rfq-btn-secondary:hover {
            background-color: #5a6268;
        }

        .rfq-btn-primary {
            background-color: #198754;
            color: white;
        }

        .rfq-btn-primary:hover {
            background-color: #157347;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .custom-modal-container {
                padding: 5px 0;
            }

            .custom-modal-header,
            .custom-modal-body,
            .custom-modal-footer {
                padding-left: 12px;
                padding-right: 12px;
            }

            .rfq-info-section {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .rfq-details-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .rfq-table th,
            .rfq-table td {
                padding: 6px 4px;
                font-size: 0.75rem;
            }

            .rfq-table-wrapper {
                max-height: 200px;
            }
        }
            font-size: 0.85rem;
        }

        #rfqFormModal .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #rfqFormModal .form-control-sm {
            height: calc(1.8em + 0.75rem + 2px);
            padding: 0.375rem 0.5rem;
            font-size: 0.9rem;
            line-height: 1.4;
            border-radius: 0.25rem;
            width: 100%;
        }

        #rfqFormModal .table-no-border td {
            border: none;
            padding: 0.5rem 0.75rem;
        }

        #rfqFormModal .row.mb-4 {
            margin-bottom: 1rem !important;
        }

        #rfqFormModal .row.mb-3 {
            margin-bottom: 0.75rem !important;
        }

        /* Extra styles for the modal form inputs */
        #modalRequestForm input[type="text"],
        #modalRequestForm input[type="email"],
        #modalRequestForm input[type="number"] {
            padding: 10px;
            font-size: 16px;
            height: auto;
        }

        #modalSelectedPlantsTable th,
        #modalSelectedPlantsTable td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        #modalSelectedPlantsTable input {
            padding: 8px;
            width: 100%;
        }

        /* Make quantity input wider */
        #modalSelectedPlantsTable input[name*="quantity"] {
            min-width: 80px;
        }

        /* Make measurement inputs wider */
        #modalSelectedPlantsTable input[name*="height"],
        #modalSelectedPlantsTable input[name*="spread"],
        #modalSelectedPlantsTable input[name*="spacing"] {
            min-width: 100px;
        }
        
        /* Success Modal Animated Checkmark */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        
        .success-checkmark .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;
        }
        
        .success-checkmark .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }
        
        .success-checkmark .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }
        
        .success-checkmark .check-icon::before,
        .success-checkmark .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: #FFFFFF;
            transform: rotate(-45deg);
        }
        
        .success-checkmark .check-icon .icon-line {
            height: 5px;
            background-color: #4CAF50;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }
        
        .success-checkmark .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }
        
        .success-checkmark .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }
        
        .success-checkmark .check-icon .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 175, 80, .5);
        }
        
        .success-checkmark .check-icon .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: #FFFFFF;
        }
        
        @keyframes rotate-circle {
            0% {
                transform: rotate(-45deg);
            }
            5% {
                transform: rotate(-45deg);
            }
            12% {
                transform: rotate(-405deg);
            }
            100% {
                transform: rotate(-405deg);
            }
        }
        
        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 1px;
                top: 19px;
            }
            54% {
                width: 0;
                left: 1px;
                top: 19px;
            }
            70% {
                width: 50px;
                left: -8px;
                top: 37px;
            }
            84% {
                width: 17px;
                left: 21px;
                top: 48px;
            }
            100% {
                width: 25px;
                left: 14px;
                top: 45px;
            }
        }
        
        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 46px;
                top: 54px;
            }
            65% {
                width: 0;
                right: 46px;
                top: 54px;
            }
            84% {
                width: 55px;
                right: 0px;
                top: 35px;
            }
            100% {
                width: 47px;
                right: 8px;
                top: 38px;
            }
        }
    </style>
    <style>
        /* Add Plant Modal Spacing Fix */
        #addPlantModal .modal-body {
            padding-bottom: 0.5rem !important;
        }
        #addPlantModal .card:last-child {
            margin-bottom: 0 !important;
        }
        #addPlantModal .card-body .row:last-child {
            margin-bottom: 0 !important;
        }
        
        /* Fix for search results dropdown z-index */
        #addPlantModal .search-container {
            position: relative;
            z-index: 1050;
            margin-bottom: 1rem;
        }
        
        #addPlantModal #searchResults {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1060;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 2px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        #addPlantModal #searchResults .list-group-item {
            cursor: pointer;
            transition: background-color 0.2s;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        #addPlantModal #searchResults .list-group-item:hover {
            background-color: #f8f9fa;
        }
        
        #addPlantModal #searchResults .list-group-item:last-child {
            border-bottom: none;
        }
        
        /* Ensure the select plant section doesn't clip the dropdown */
        #addPlantModal .mb-3:has(.search-container) {
            overflow: visible !important;
            margin-bottom: 2rem !important;
        }
    </style>
    <style>
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
        
        /* Smooth dropdown animation */
        .dropdown-menu {
            animation: slideDown 0.3s ease;
            transform-origin: top;
        }
        
        @keyframes slideDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .profile-btn {
            font-size: 0.98rem;
            min-width: 0;
            padding: 2px 8px;
            transition: all 0.3s ease;
        }
        
        .profile-btn:hover {
            background: #259d4e22;
        }
        
        .profile-pic, .profile-pic-placeholder {
            width: 28px !important;
            height: 28px !important;
            border-radius: 50%;
            object-fit: cover;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }
        
        .profile-btn:hover .profile-pic,
        .profile-btn:hover .profile-pic-placeholder {
            transform: scale(1.1);
        }
    </style>
    <style>
        /* Delete button icon styling - remove all backgrounds */
        .delete-plant-btn {
            transition: all 0.2s ease;
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        
        .delete-plant-btn:hover,
        .delete-plant-btn:focus,
        .delete-plant-btn:active {
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            transform: scale(1.15);
        }
        
        .delete-plant-btn:active {
            transform: scale(0.95);
        }
        
        .delete-plant-btn i {
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.3));
        }
    </style>
    <style>
        .compact-dropdown .dropdown-item {
            font-size: 0.95rem !important;
            padding: 6px 14px !important;
        }
        .compact-dropdown .dropdown-divider {
            margin: 4px 0 !important;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Preloader -->
    <div id="page-preloader">
        <img src="{{ asset('images/salengap-modified.png') }}" alt="Loading..." class="preloader-logo">
    </div>

    <!-- Page Content -->
    <div class="page-content">
    <nav class="navbar navbar-expand-lg main-nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('public.plants') }}">
                <img src="{{ asset('images/salengap-modified.png') }}" alt="Salenga Logo" class="nav-logo">
                <span class="brand-text">Salenga Farm</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                @if(Auth::check() && !Auth::user()->hasAdminAccess())
                <!-- Authenticated non-admin users: show centered nav links -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('public.plants') ? 'active' : '' }}" href="{{ route('public.plants') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('dashboard.user') ? 'active' : '' }}" href="{{ route('dashboard.user') }}">
                            <i class="fas fa-gauge me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('plant-care.*') ? 'active' : '' }}" href="{{ route('plant-care.index') }}">
                            <i class="fas fa-leaf me-1"></i> Plant Guide
                        </a>
                    </li>
                    @if(Auth::user()->isClient())
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('client-data.*') ? 'active' : '' }}" href="{{ route('client-data.index') }}">
                            <i class="fas fa-folder-open me-1"></i> Client Data
                        </a>
                    </li>
                    @endif
                </ul>
                @elseif(Auth::check() && Auth::user()->hasAdminAccess())
                <!-- Admin users: show Home and Plant Care nav links -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('public.plants') ? 'active' : '' }}" href="{{ route('public.plants') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        @if(Auth::user()->role === 'super_admin')
                            <a class="nav-link text-white {{ request()->routeIs('plant-care.index') || request()->routeIs('plant-care.show') ? 'active' : '' }}" href="{{ route('plant-care.index') }}">
                                <i class="fas fa-leaf me-1"></i> Plant Guide
                            </a>
                        @else
                            <a class="nav-link text-white {{ request()->routeIs('plant-care.admin') || request()->routeIs('plant-care.edit') || request()->routeIs('plant-care.show') ? 'active' : '' }}" href="{{ route('plant-care.admin') }}">
                                <i class="fas fa-leaf me-1"></i> Plant Guide
                            </a>
                        @endif
                    </li>
                </ul>
                @else
                <!-- Guests: no centered nav, just spacer -->
                <div class="flex-grow-1"></div>
                @endif
                <div class="user-section d-flex align-items-center">
                    @auth
                        <!-- Notification Bell -->
                        <div class="position-relative me-3">
                            <div class="notification-bell notification-bell-trigger" id="homeNotificationBell" title="Notifications">
                                <i class="fas fa-bell"></i>
                                <span class="badge bg-danger notification-badge" style="display: none;">0</span>
                            </div>
                            <!-- Notification Dropdown -->
                            <div class="notification-dropdown" id="homeNotificationDropdown">
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
                        
                        @if(auth()->user()->hasAdminAccess())
                            <div class="dropdown me-2">
                                <button class="btn btn-link dropdown-toggle menu-btn px-3 py-1" type="button" id="menuDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.98rem; font-weight: 500;">
                                    <i class="fas fa-bars menu-icon"></i>
                                    <span class="menu-text">Menu</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                                    <!-- Mobile-only nav links (Home, Plant Guide) -->
                                    <li class="d-md-none"><a class="dropdown-item" href="{{ route('public.plants') }}"><i class="fas fa-home me-2"></i>Home</a></li>
                                    @if(auth()->user()->role === 'super_admin')
                                        <li class="d-md-none"><a class="dropdown-item" href="{{ route('plant-care.index') }}"><i class="fas fa-leaf me-2"></i>Plant Guide</a></li>
                                    @else
                                        <li class="d-md-none"><a class="dropdown-item" href="{{ route('plant-care.admin') }}"><i class="fas fa-leaf me-2"></i>Plant Guide</a></li>
                                    @endif
                                    <li class="d-md-none"><hr class="dropdown-divider"></li>
                                    
                                    <!-- Desktop nav links (always visible) -->
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('plants.index') }}"><i class="fas fa-seedling me-2"></i>Inventory</a></li>
                                    <li><a class="dropdown-item" href="{{ route('requests.index') }}"><i class="fas fa-envelope-open-text me-2"></i>Request</a></li>
                                    <li><a class="dropdown-item" href="{{ route('walk-in.index') }}"><i class="fas fa-cash-register me-2"></i>Point-of-Sale</a></li>
                                    <li><a class="dropdown-item" href="/site-visits"><i class="fas fa-map-marked-alt me-2"></i>Site Visits</a></li>
                        @if(auth()->user()->isSuperAdmin())
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users-cog me-2"></i>Users</a></li>
                        @endif
                </ul>
                            </div>
                        @else
                            <!-- Regular users: Show Menu on mobile only with their nav links -->
                            <div class="dropdown me-2 d-md-none">
                                <button class="btn btn-link dropdown-toggle menu-btn px-3 py-1" type="button" id="menuDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.98rem; font-weight: 500;">
                                    <i class="fas fa-bars menu-icon"></i>
                                    <span class="menu-text">Menu</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdownMobile">
                                    <li><a class="dropdown-item" href="{{ route('public.plants') }}"><i class="fas fa-home me-2"></i>Home</a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.user') }}"><i class="fas fa-gauge me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('plant-care.index') }}"><i class="fas fa-leaf me-2"></i>Plant Guide</a></li>
                                    @if(Auth::user()->isClient())
                                        <li><a class="dropdown-item" href="{{ route('client-data.index') }}"><i class="fas fa-folder-open me-2"></i>Client Data</a></li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle profile-btn px-2 py-1" type="button" id="profileDropdown" data-bs-toggle="dropdown" style="font-size: 0.98rem; min-width: 0;">
                                @if(auth()->user() && auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile" class="profile-pic" style="width: 28px; height: 28px;">
                            @else
                                    <div class="profile-pic-placeholder" style="width: 28px; height: 28px; font-size: 1.2rem;">
                                        <i class="fas fa-user"></i>
                                </div>
                            @endif
                                <span style="font-size: 0.98rem;">{{ auth()->user() ? auth()->user()->name : 'Profile' }}</span>
                        </button>
                            <ul class="dropdown-menu dropdown-menu-end compact-dropdown" style="min-width: 150px;">
                            <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}" style="font-size: 0.95rem; padding: 6px 14px;">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                                <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                            <li>
                                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                        <button type="submit" class="dropdown-item" style="font-size: 0.95rem; padding: 6px 14px;">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                            <i class="fas fa-user me-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-light">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content structure -->
    <div class="main-content">
        <!-- Splash Page Content -->
    <div class="splash-page" id="splashPage">
                <div class="splash-content">
            <h1 class="display-4">Welcome to Salenga Farm</h1>
            <p class="lead">Discover our wide range of available plants</p>
            <button class="scroll-down-btn" onclick="scrollToContent()">
                <i class="fas fa-chevron-down"></i>
                Explore Plants
            </button>
        </div>
    </div>

        <!-- Marquee Container without any margin -->
        <div class="marquee-container">
        <div class="marquee">
            <div class="marquee-content">
                <img src="{{ asset('images/plantpic/bamboo-p.jpg') }}" alt="Bamboo" class="marquee-img">
                <img src="{{ asset('images/plantpic/fertilizer-p.jpg') }}" alt="Fertilizer" class="marquee-img">
                <img src="{{ asset('images/plantpic/grass-p.jpg') }}" alt="Grass" class="marquee-img">
                <img src="{{ asset('images/plantpic/herbs-pp.jpg') }}" alt="Herbs" class="marquee-img">
                <img src="{{ asset('images/plantpic/palm-pp.jpg') }}" alt="Palm" class="marquee-img">
                <img src="{{ asset('images/plantpic/shrubs-p.jpg') }}" alt="Shrubs" class="marquee-img">
                <img src="{{ asset('images/plantpic/tree-p.jpg') }}" alt="Tree" class="marquee-img">
            </div>
            <!-- Duplicate content for seamless scrolling -->
            <div class="marquee-content">
                <img src="{{ asset('images/plantpic/bamboo-p.jpg') }}" alt="Bamboo" class="marquee-img">
                <img src="{{ asset('images/plantpic/fertilizer-p.jpg') }}" alt="Fertilizer" class="marquee-img">
                <img src="{{ asset('images/plantpic/grass-p.jpg') }}" alt="Grass" class="marquee-img">
                <img src="{{ asset('images/plantpic/herbs-pp.jpg') }}" alt="Herbs" class="marquee-img">
                <img src="{{ asset('images/plantpic/palm-pp.jpg') }}" alt="Palm" class="marquee-img">
                <img src="{{ asset('images/plantpic/shrubs-p.jpg') }}" alt="Shrubs" class="marquee-img">
                <img src="{{ asset('images/plantpic/tree-p.jpg') }}" alt="Tree" class="marquee-img">
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid py-5">
        <div class="row">
            <!-- Left sidebar with category filter (desktop only) -->
            <div class="col-md-3 d-none d-md-block">
                <div class="category-filter-box">
                    <div class="filter-title d-flex align-items-center justify-content-between mb-3">
                        <span>Category Filter</span>
                        @if(Auth::check() && Auth::user()->hasAdminAccess() && !Auth::user()->isSuperAdmin())
                        <div class="d-flex align-items-center" style="gap: .5rem;">
                            <button type="button" id="deleteCategoryBtn" class="btn btn-outline-danger icon-square-btn" title="Delete Category" aria-label="Delete Category">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="btn btn-success icon-square-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Add Category" aria-label="Add Category">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    <div class="category-grid">
                        @php
                            $isAdmin = Auth::check() && Auth::user()->hasAdminAccess();
                            $categoryCounts = [
                                'shrub' => $plants->where('category', 'shrub')->count(),
                                'herbs' => $plants->where('category', 'herbs')->count(),
                                'palm' => $plants->where('category', 'palm')->count(),
                                'tree' => $plants->where('category', 'tree')->count(),
                                'grass' => $plants->where('category', 'grass')->count(),
                                'bamboo' => $plants->where('category', 'bamboo')->count(),
                                'fertilizer' => $plants->where('category', 'fertilizer')->count(),
                            ];
                        @endphp
                        <div class="category-icon-item active" data-category="all">
                            <div class="icon-circle active">
                                <i class="fas fa-border-all"></i>
                            </div>
                            <span>All</span>
                        </div>
                        @if($isAdmin || $categoryCounts['shrub'] > 0)
                        <div class="category-icon-item" data-category="shrub">
                            <img src="{{ asset('images/categories/shrub-g.png') }}" alt="Shrub" class="category-img">
                            <span>Shrub</span>
                        </div>
                        @endif
                        @if($isAdmin || $categoryCounts['herbs'] > 0)
                        <div class="category-icon-item" data-category="herbs">
                            <img src="{{ asset('images/categories/herbs-g.png') }}" alt="Herbs" class="category-img">
                            <span>Herbs</span>
                        </div>
                        @endif
                        @if($isAdmin || $categoryCounts['palm'] > 0)
                        <div class="category-icon-item" data-category="palm">
                            <img src="{{ asset('images/categories/palm-g.png') }}" alt="Palm" class="category-img">
                            <span>Palm</span>
                        </div>
                        @endif
                        @if($isAdmin || $categoryCounts['tree'] > 0)
                        <div class="category-icon-item" data-category="tree">
                            <img src="{{ asset('images/categories/tree-g.png') }}" alt="Tree" class="category-img">
                            <span>Tree</span>
                        </div>
                        @endif
                        @if($isAdmin || $categoryCounts['grass'] > 0)
                        <div class="category-icon-item" data-category="grass">
                            <img src="{{ asset('images/categories/grass-g.png') }}" alt="Grass" class="category-img">
                            <span>Grass</span>
                        </div>
                        @endif
                        @if($isAdmin || $categoryCounts['bamboo'] > 0)
                        <div class="category-icon-item" data-category="bamboo">
                            <img src="{{ asset('images/categories/bamboo-g.png') }}" alt="Bamboo" class="category-img">
                            <span>Bamboo</span>
                        </div>
                        @endif
                        @if($isAdmin || $categoryCounts['fertilizer'] > 0)
                        <div class="category-icon-item" data-category="fertilizer">
                            <img src="{{ asset('images/categories/fertilizer-g.png') }}" alt="Fertilizer" class="category-img">
                            <span>Fertilizer</span>
                        </div>
                        @endif
                        
                        @if(isset($additionalCategories) && $additionalCategories->count() > 0)
                            @foreach($additionalCategories as $category)
                                <div class="category-icon-item" data-category="{{ $category->slug }}">
                                    @if($category->icon_path)
                                        <img src="{{ asset('storage/' . $category->icon_path) }}" alt="{{ $category->name }}" class="category-img">
                                    @else
                                        <div class="icon-circle">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                    @endif
                                    <span>{{ $category->name }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main content area with search and plants grid -->
            <div class="col-md-9">
                <!-- Search bar -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search plants..." autocomplete="off">
                </div>
            </div>
            <div class="col-md-6 text-end search-controls-container">
                    @if(Auth::check() && Auth::user()->hasAdminAccess() && !Auth::user()->isSuperAdmin())
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addPlantModal">
                    <i class="fas fa-plus me-1"></i>Add New Plant
                </button>
                    @endif

                    @if(Auth::check() && Auth::user()->isClient())
                        <!-- Client RFQ button -->
                        <button class="btn btn-success" id="requestPlantsBtn">
                            <i class="fas fa-file-invoice me-1"></i>Request for Quotation (RFQ)
                        </button>
                    @elseif(Auth::check() && Auth::user()->role === 'user' && !Auth::user()->is_client)
                        <!-- Regular User ONLY - View Inquiry button -->
                        <button class="btn" id="viewRequestBtn" onclick="viewSelectedPlants()">
                            <i class="fas fa-clipboard-list me-1"></i>View Inquiry (<span id="requestCount">0</span>)
                        </button>
                    @endif
            </div>
        </div>

        <!-- Mobile Category Filter (shows only on mobile) -->
        <div class="category-filter-box d-md-none mb-3">
            <div class="filter-title d-flex align-items-center justify-content-between mb-3">
                <span>Category Filter</span>
                @if(Auth::check() && Auth::user()->hasAdminAccess() && !Auth::user()->isSuperAdmin())
                <div class="d-flex align-items-center" style="gap: .5rem;">
                    <button type="button" class="btn btn-outline-danger icon-square-btn delete-category-btn" title="Delete Category" aria-label="Delete Category">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button" class="btn btn-success icon-square-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Add Category" aria-label="Add Category">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                @endif
            </div>
            <div class="category-grid">
                <div class="category-icon-item active" data-category="all">
                    <div class="icon-circle active">
                        <i class="fas fa-border-all"></i>
                    </div>
                    <span>All</span>
                </div>
                @if($isAdmin || $categoryCounts['shrub'] > 0)
                <div class="category-icon-item" data-category="shrub">
                    <img src="{{ asset('images/categories/shrub-g.png') }}" alt="Shrub" class="category-img">
                    <span>Shrub</span>
                </div>
                @endif
                @if($isAdmin || $categoryCounts['herbs'] > 0)
                <div class="category-icon-item" data-category="herbs">
                    <img src="{{ asset('images/categories/herbs-g.png') }}" alt="Herbs" class="category-img">
                    <span>Herbs</span>
                </div>
                @endif
                @if($isAdmin || $categoryCounts['palm'] > 0)
                <div class="category-icon-item" data-category="palm">
                    <img src="{{ asset('images/categories/palm-g.png') }}" alt="Palm" class="category-img">
                    <span>Palm</span>
                </div>
                @endif
                @if($isAdmin || $categoryCounts['tree'] > 0)
                <div class="category-icon-item" data-category="tree">
                    <img src="{{ asset('images/categories/tree-g.png') }}" alt="Tree" class="category-img">
                    <span>Tree</span>
                </div>
                @endif
                @if($isAdmin || $categoryCounts['grass'] > 0)
                <div class="category-icon-item" data-category="grass">
                    <img src="{{ asset('images/categories/grass-g.png') }}" alt="Grass" class="category-img">
                    <span>Grass</span>
                </div>
                @endif
                @if($isAdmin || $categoryCounts['bamboo'] > 0)
                <div class="category-icon-item" data-category="bamboo">
                    <img src="{{ asset('images/categories/bamboo-g.png') }}" alt="Bamboo" class="category-img">
                    <span>Bamboo</span>
                </div>
                @endif
                @if($isAdmin || $categoryCounts['fertilizer'] > 0)
                <div class="category-icon-item" data-category="fertilizer">
                    <img src="{{ asset('images/categories/fertilizer-g.png') }}" alt="Fertilizer" class="category-img">
                    <span>Fertilizer</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Plants Grid -->
        <div class="row row-cols-1 row-cols-md-4 g-4" id="plantsGrid">
            @foreach($plants as $plant)
            <div class="col plant-item" data-category="{{ $plant->category }}" data-name="{{ $plant->name }}">
                        @if(Auth::check() && Auth::user()->hasAdminAccess())
                            <!-- Admin/Super Admin View -->
                            <div class="card admin-plant-card">
                                <div class="card-body p-0">
                                    <!-- Main View (Always Visible) -->
                                    <div class="plant-main-view">
                                        <div class="plant-header d-flex justify-content-between align-items-center p-3">
                                            <h5 class="card-title mb-0">{{ $plant->name }}</h5>
                                            <div class="info-icon">
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </div>

                                        <!-- Photo Display -->
                                        <div class="plant-image-container">
                                            @if($plant->photo_path)
                                                <img src="{{ asset('storage/' . $plant->photo_path) }}" alt="{{ $plant->name }}" class="plant-main-photo">
                                            @else
                                                <div class="no-photo-placeholder">
                                                    <i class="fas fa-image"></i>
                                                    <p class="small">No Photo Available</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Sliding Details Panel -->
                                    <div class="plant-details-panel">
                                        <!-- Header: Back (left), Icons (center), Title (right) -->
                                        <div class="details-header d-flex align-items-center p-3 border-bottom text-white">
                                            <button class="btn btn-sm btn-link back-to-main" onclick="toggleAdminDetails(this)">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            @if(!Auth::user()->isSuperAdmin())
                                            <div class="d-flex align-items-center gap-2">
                                                <button class="btn btn-link p-0 edit-plant-btn"
                                                        title="Edit"
                                                        data-plant-id="{{ $plant->id }}"
                                                        data-name="{{ $plant->name }}"
                                                        data-code="{{ $plant->code }}"
                                                        data-scientific-name="{{ $plant->scientific_name }}"
                                                        data-category="{{ $plant->category }}"
                                                        data-description="{{ $plant->description }}"
                                                        data-height-mm="{{ $plant->height_mm }}"
                                                        data-spread-mm="{{ $plant->spread_mm }}"
                                                        data-spacing-mm="{{ $plant->spacing_mm }}"
                                                        data-oc="{{ $plant->oc }}"
                                                        data-price="{{ $plant->price }}"
                                                        data-cost-per-sqm="{{ $plant->cost_per_sqm }}"
                                                        data-pieces-per-sqm="{{ $plant->pieces_per_sqm }}"
                                                        data-cost-per-mm="{{ $plant->cost_per_mm }}"
                                                        data-quantity="{{ $plant->quantity }}"
                                                        data-photo-path="{{ $plant->photo_path }}"
                                                        type="button">
                                                    <i class="fas fa-edit fa-lg text-white"></i>
                                                </button>
                                                <button class="btn btn-link p-0 delete-plant-btn"
                                                        style="background: transparent !important; border: none !important; box-shadow: none !important; color: #dc3545 !important;"
                                                        title="Delete"
                                                        data-plant-id="{{ $plant->id }}"
                                                        data-plant-name="{{ $plant->name }}">
                                                    <i class="fas fa-trash-can fa-lg"></i>
                                                </button>
                                            </div>
                                            @endif
                                            <h6 class="mb-0">Plant Details</h6>
                                        </div>

                                        <!-- Plant Information -->
                                        <div class="p-3">
                                            <div class="info-section text-white">
                                                <div class="section-content">
                                                    <p><small class="text-muted">Category:</small> <span class="value-text">{{ ucfirst($plant->category) }}</span></p>
                                                    <p><small class="text-muted">Code:</small> <span class="value-text">{{ $plant->code ?? 'N/A' }}</span></p>
                                                    @if($plant->scientific_name)
                                                        <p><small class="text-muted">Scientific Name:</small> <em class="value-text">{{ $plant->scientific_name }}</em></p>
                                                    @endif
                                                    @if($plant->height_mm || $plant->spread_mm || $plant->spacing_mm)
                                                        <div class="measurements mt-2">
                                                            <ul class="list-unstyled mb-0">
                            @if($plant->height_mm)
                                                                    <li><small class="text-muted">Height:</small> <span class="value-text">{{ $plant->height_mm }} mm</span></li>
                            @endif
                            @if($plant->spread_mm)
                                                                    <li><small class="text-muted">Spread:</small> <span class="value-text">{{ $plant->spread_mm }} mm</span></li>
                            @endif
                            @if($plant->spacing_mm)
                                                                    <li><small class="text-muted">Spacing:</small> <span class="value-text">{{ $plant->spacing_mm }} mm</span></li>
                            @endif
                        </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- User View -->
                            <div class="card user-plant-card">
                                <div class="card-body p-0">
                                    <!-- Main View (Always Visible) -->
                                    <div class="plant-main-view">
                                        <div class="plant-header d-flex justify-content-between align-items-center p-3">
                                            <h5 class="card-title mb-0">{{ $plant->name }}</h5>
                                            <div class="info-icon">
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </div>

                                        <!-- Photo Display -->
                                        <div class="plant-image-container">
                                            @if($plant->photo_path)
                                                <img src="{{ asset('storage/' . $plant->photo_path) }}" alt="{{ $plant->name }}" class="plant-main-photo">
                                            @else
                                                <div class="no-photo-placeholder">
                                                    <i class="fas fa-image"></i>
                                                    <p class="small">No Photo Available</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Sliding Details Panel -->
                                    <div class="plant-details-panel">
                                        <!-- Header with Add to Inquiry Button -->
                                        <div class="details-header d-flex justify-content-between align-items-center p-2 text-white">
                                            <button type="button" class="btn btn-sm btn-link back-to-main text-white p-1" onclick="toggleUserDetails(this)">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            @if(Auth::check() && Auth::user()->role === 'user' && !Auth::user()->is_client)
                                                <div class="d-flex gap-2 align-items-center">
                                                    <button type="button" class="btn btn-sm btn-success plant-action-btn"
                                                            data-plant-id="{{ $plant->id }}"
                                                            data-plant-name="{{ $plant->name }}"
                                                            data-plant-code="{{ $plant->code }}"
                                                            data-height="{{ $plant->height_mm }}"
                                                            data-spread="{{ $plant->spread_mm }}"
                                                            data-spacing="{{ $plant->spacing_mm }}"
                                                            data-action="add"
                                                            style="font-size: 0.7rem; padding: 0.2rem 0.35rem; white-space: nowrap;">
                                                        <i class="fas fa-plus"></i> Add to Inquiry
                                                    </button>
                                                    <span class="text-white" style="font-size: 0.85rem; white-space: nowrap; cursor: default;">
                                                        Plant Details
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Plant Information -->
                                        <div class="p-2">
                                            <div class="info-section text-white">
                                                <div class="section-content">
                                                    <p class="mb-1" style="font-size: 0.9rem;"><small class="text-muted">Category:</small> <span class="value-text">{{ ucfirst($plant->category) }}</span></p>
                                                    <p class="mb-1" style="font-size: 0.9rem;"><small class="text-muted">Code:</small> <span class="value-text">{{ $plant->code ?? 'N/A' }}</span></p>
                                                    @if($plant->scientific_name)
                                                        <p class="mb-2" style="font-size: 0.9rem;"><small class="text-muted">Scientific Name:</small> <em class="value-text">{{ $plant->scientific_name }}</em></p>
                                                    @endif

                                                    <div class="measurements mt-2">
                                                        <ul class="list-unstyled mb-0">
                                                            @if($plant->height_mm)
                                                                <li class="mb-1" style="font-size: 0.9rem;">
                                                                    <small class="text-muted">Height:</small>
                                                                    <span class="value-text">{{ $plant->height_mm }} mm</span>
                                                                </li>
                                                            @endif
                                                            @if($plant->spread_mm)
                                                                <li class="mb-1" style="font-size: 0.9rem;">
                                                                    <small class="text-muted">Spread:</small>
                                                                    <span class="value-text">{{ $plant->spread_mm }} mm</span>
                                                                </li>
                                                            @endif
                                                            @if($plant->spacing_mm)
                                                                <li class="mb-1" style="font-size: 0.9rem;">
                                                                    <small class="text-muted">Spacing:</small>
                                                                    <span class="value-text">{{ $plant->spacing_mm }} mm</span>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>

                                                    @guest
                                                        <!-- For guests - Login prompt -->
                                                        <div class="login-prompt mt-2">
                                                            <a href="{{ route('login') }}" class="text-white" style="font-size: 0.85rem;">
                                                                <i class="fas fa-sign-in-alt"></i> Want to request? Let's log you in first.
                                                            </a>
                                                        </div>
                                                    @endguest
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
            </div>
            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this modal for Admin/Super Admin -->
    <div class="modal fade" id="addPlantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Add Plant to Display</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto; padding: 1rem 1rem 0.5rem 1rem !important;">
                    <form id="addPlantForm">
                        <!-- Select Plant Section -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Plant from Inventory</label>
                            <div class="search-container position-relative">
                                <input type="text" class="form-control" id="plantSearchInput" placeholder="Search plants..." autocomplete="off">
                                <div id="searchResults" class="search-results d-none position-absolute w-100 bg-white border rounded shadow">
                                    <!-- Results will be populated here -->
                                </div>
                            </div>
                        </div>

                        <!-- Photo Section -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Photo</label>
                            <input type="file" class="form-control" id="photo">
                        </div>

                        <!-- Basic Information Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body" style="padding: 1.5rem !important;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Plant Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="plantName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Code</label>
                                        <input type="text" class="form-control" id="plantCode">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Scientific Name</label>
                                        <input type="text" class="form-control" id="scientificName">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-control" id="category" required>
                                            <option value="">— Select Category —</option>
                                            <option value="shrub">Shrub</option>
                                            <option value="herbs">Herbs</option>
                                            <option value="palm">Palm</option>
                                            <option value="tree">Tree</option>
                                            <option value="grass">Grass</option>
                                            <option value="bamboo">Bamboo</option>
                                            <option value="fertilizer">Fertilizer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Measurements Section -->
                        <div class="card mb-0">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-ruler me-2"></i>Measurements</h6>
                            </div>
                            <div class="card-body" style="padding: 1.5rem 1.5rem 1rem 1.5rem !important;">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Height (mm)</label>
                                        <input type="number" class="form-control" id="heightMm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Spread (mm)</label>
                                        <input type="number" class="form-control" id="spreadMm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Spacing (mm)</label>
                                        <input type="number" class="form-control" id="spacingMm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveNewPlant">Add Plant</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Plant Modal (Admin/Super Admin) -->
    <div class="modal fade" id="editPlantModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Plant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPlantForm">
                        @csrf
                        <input type="hidden" id="edit_plant_id">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Current Photo</label>
                                <div class="d-flex align-items-start gap-3">
                                    <img id="edit_current_photo" src="" alt="Current Photo" class="img-thumbnail d-none" style="width: 160px; height: 160px; object-fit: cover;">
                                    <div id="edit_no_photo" class="no-photo-placeholder d-flex align-items-center justify-content-center border bg-light" style="width: 160px; height: 160px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <input type="file" class="form-control mb-2" id="edit_photo_file" accept="image/*">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="editUploadPhoto">
                                            <i class="fas fa-upload me-1"></i>Upload
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" id="editRemovePhoto">
                                            <i class="fas fa-trash me-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" id="edit_name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Code</label>
                                        <input type="text" class="form-control" id="edit_code">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Scientific Name</label>
                                        <input type="text" class="form-control" id="edit_scientific_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" id="edit_category">
                                            <option value="shrub">Shrub</option>
                                            <option value="herbs">Herbs</option>
                                            <option value="palm">Palm</option>
                                            <option value="tree">Tree</option>
                                            <option value="grass">Grass</option>
                                            <option value="bamboo">Bamboo</option>
                                            <option value="fertilizer">Fertilizer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">O.C.</label>
                                        <input type="text" class="form-control" id="edit_oc">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" id="edit_description" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Height (mm)</label>
                                        <input type="number" class="form-control" id="edit_height_mm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Spread (mm)</label>
                                        <input type="number" class="form-control" id="edit_spread_mm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Spacing (mm)</label>
                                        <input type="number" class="form-control" id="edit_spacing_mm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_price">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Cost / sqm</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_cost_per_sqm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pieces / sqm</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_pieces_per_sqm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Cost / mm</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_cost_per_mm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="edit_quantity">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEditPlant">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Management Modal -->
    <div class="modal fade" id="photoManageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Plant Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="photoUploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="photoPlantId" name="plant_id">
                        <div class="current-photo mb-3 text-center">
                            <img id="currentPhoto" src="" alt="" class="img-fluid mb-2 d-none">
                            <div id="noPhotoPlaceholder" class="no-photo-placeholder mb-2">
                                <i class="fas fa-image"></i>
                                <p class="small">No photo available</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload New Photo</label>
                            <input type="file" class="form-control" id="plantPhoto" name="photo" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="removePhoto">Remove Photo</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="savePhoto">Upload Photo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Plants Modal -->
    <div class="modal fade" id="requestPlantsModal" tabindex="-1" aria-labelledby="requestPlantsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="requestPlantsModalLabel">Request for Quotation (RFQ)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
                    <p>Please provide your email address to continue with your plant quotation request.</p>
                    <div class="mb-3">
                        <label for="requestEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="requestEmail" placeholder="your@email.com"
                            @auth value="{{ auth()->user()->email }}" @endauth>
                        <div class="form-text">We'll send you updates about your quotation request to this email.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="selectPlantsBtn">Select Plants</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom RFQ Form Modal -->
    <div class="custom-modal-overlay" id="rfqFormModal" style="display: none;">
        <div class="custom-modal-container">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h5 class="custom-modal-title">
                        <i class="fas fa-file-invoice me-2"></i>Request for Quotation (RFQ)
                    </h5>
                    <button type="button" class="custom-close-btn" onclick="closeRfqModal()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="custom-modal-body">
                    <div id="rfqFormContainer" class="rfq-form-container">
                        <!-- RFQ Form Content -->
                        <div class="rfq-header">
                            <h2>SALENGA FARM</h2>
                            <h3>REQUEST FOR QUOTATION (RFQ)</h3>
                        </div>

                        <div class="rfq-info-section">
                        <!-- Vendor Information Section -->
                            <div class="rfq-info-left">
                                <div class="rfq-card">
                                    <div class="rfq-card-header">
                                        <h5>VENDOR INFORMATION</h5>
                                    </div>
                                    <div class="rfq-card-body">
                            <div class="rfq-info-table">
                                <div class="rfq-info-row">
                                    <div class="rfq-info-label">Company:</div>
                                    <div class="rfq-info-value">
                                        ESTHER LIBRES SALENGA ESTHER'S<br>
                                        FLOWER GARDEN AND LANDSCAPING
                                    </div>
                                </div>
                                <div class="rfq-info-row">
                                    <div class="rfq-info-label">Address:</div>
                                    <div class="rfq-info-value">
                                        INFRONT OF FATIMA VILLAGE SITIO<br>
                                        MCL.DAVAO CITY.PH
                                    </div>
                                </div>
                                <div class="rfq-info-row">
                                    <div class="rfq-info-label">TIN:</div>
                                    <div class="rfq-info-value">47496058600000</div>
                                </div>
                                <div class="rfq-info-row">
                                    <div class="rfq-info-label">E-mail:</div>
                                    <div class="rfq-info-value">salengafarm@example.com</div>
                                </div>
                            </div>
                                    </div>
                                </div>
                        </div>

                        <!-- RFQ Details Section -->
                                <div class="rfq-info-right">
                                <div class="rfq-card">
                                    <div class="rfq-card-header">
                                        <h5>REQUEST DETAILS</h5>
                                </div>
                                    <div class="rfq-card-body">
                                        <div class="rfq-details-grid">
                                <div class="rfq-detail-item">
                                                <div class="rfq-detail-label">RFQ Date:</div>
                                                <div id="rfqDate" class="rfq-detail-value"></div>
                                </div>
                                            <div class="rfq-detail-item">
                                                <div class="rfq-detail-label">RFQ Due Date:</div>
                                                <div id="rfqDueDate" class="rfq-detail-value"></div>
                            </div>
                                <div class="rfq-detail-item">
                                                <div class="rfq-detail-label">Buyer Name:</div>
                                                <div id="buyerName" class="rfq-detail-value"></div>
                                </div>
                                <div class="rfq-detail-item">
                                                <div class="rfq-detail-label">Buyer Email:</div>
                                                <div id="buyerEmail" class="rfq-detail-value"></div>
                                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="rfq-card">
                            <div class="rfq-card-header">
                                <h5>REQUESTED PLANTS</h5>
                            </div>
                            <div class="rfq-card-body rfq-table-container">
                                <div class="rfq-table-wrapper">
                                    <table class="rfq-table">
                                        <thead>
                                    <tr>
                                                <th style="width: 40px;">#</th>
                                                <th style="width: 60px;">Qty</th>
                                                <th style="min-width: 150px;">Plant Name</th>
                                                <th style="width: 80px;">Code</th>
                                                <th style="width: 70px;">H(mm)</th>
                                                <th style="width: 70px;">S(mm)</th>
                                                <th style="width: 70px;">Sp(mm)</th>
                                                <th style="width: 120px;">Remarks</th>
                                                <th style="width: 90px;">Unit ₱</th>
                                                <th style="width: 90px;">Total ₱</th>
                                                <th style="width: 80px;">Avail.</th>
                                    </tr>
                                </thead>
                                <tbody id="rfqItemsTable">
                                    <!-- Items will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                            </div>
                        </div>

                        <!-- Vendor Instructions -->
                        <div class="rfq-card">
                            <div class="rfq-card-header">
                                <h5>VENDOR INSTRUCTIONS</h5>
                            </div>
                            <div class="rfq-card-body">
                                <p>Specify brand/made and availability or quotation will not be honored. Vendor's proposal in response to this RFQ do not need to submit such documentation as part of this RFQ.</p>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="rfq-card">
                            <div class="rfq-card-header">
                                <h5>TERMS AND CONDITIONS</h5>
                            </div>
                            <div class="rfq-card-body">
                                <ol class="rfq-terms-list">
                                <li>Please provide your best quotation for the items listed above.</li>
                                <li>Quotation should include pricing, availability, and delivery timeline.</li>
                                <li>All prices should be valid for at least 30 days from the date of quotation.</li>
                                <li>Please respond to this RFQ by the due date indicated.</li>
                            </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="rfq-btn rfq-btn-secondary" onclick="closeRfqModal()">Close</button>
                    <button type="button" class="rfq-btn rfq-btn-primary" id="submitRequest">
                        <i class="fas fa-paper-plane me-1"></i>Send Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay - Now handled by LoadingManager (domino loader) -->

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Request Submitted Successfully!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="success-checkmark mb-4">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="checkmark-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                    <h4 class="text-success mb-3">Thank You!</h4>
                    <p class="text-muted mb-0">Your request has been submitted successfully!</p>
                    <p class="text-muted">We'll process your request shortly and send a response to your email address.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
                        <i class="fas fa-home me-2"></i>Continue Browsing
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiry Form Modal -->
    <div class="modal fade" id="requestFormModal" tabindex="-1" aria-labelledby="requestFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl extra-wide-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestFormModalLabel">Plant Inquiry Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You can request up to 20 plants. Adjust quantities and measurements as needed.
                    </div>

                    <form id="modalRequestForm">
                        @csrf

                        <!-- User Info Section -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="modal_name">Your Name</label>
                                    <input type="text" class="form-control" id="modal_name" name="name"
                                        value="{{ auth()->check() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="modal_email">Email</label>
                                    <input type="email" class="form-control" id="modal_email" name="email"
                                        value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="modal_contact_number">Contact Number</label>
                                    <input type="text" class="form-control" id="modal_contact_number" name="contact_number"
                                        value="{{ auth()->check() ? auth()->user()->contact_number : '' }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Plants Table -->
                        <h5 class="mb-3">Selected Plants for Inquiry</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="modalSelectedPlantsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Plant Name</th>
                                        <th>Code</th>
                                        <th style="width: 80px;">Qty</th>
                                        <th>Height (mm)</th>
                                        <th>Spread (mm)</th>
                                        <th>Spacing (mm)</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="modalPlantsTableBody">
                                    <!-- Plants will be loaded here via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <div id="modalEmptySelection" class="text-center py-4 d-none">
                            <i class="fas fa-leaf fa-3x text-muted mb-3"></i>
                            <p class="mb-0">No plants selected yet</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">
                                <i class="fas fa-search"></i> Browse Plants
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="modalSubmitButton">
                        <i class="fas fa-paper-plane"></i> Submit Inquiry
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">© 2024 All rights reserved.</span>
        </div>
    </footer>
    @if(Auth::check() && Auth::user()->hasAdminAccess())
    <!-- Add Category Modal (moved outside navbar; inputs enabled) -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" id="newCategoryName" class="form-control" placeholder="e.g., Succulent">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (optional)</label>
                        <input type="file" id="newCategoryIcon" class="form-control" accept="image/*">
                    </div>
                    <div class="alert alert-info mb-0">
                        This is a placeholder UI. Saving will be wired when we add the Category backend.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveNewCategory" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Generic Confirm/Info Modal for Home page actions -->
    <div class="modal fade" id="homeConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="homeConfirmTitle">Confirm</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="warn-icon mb-2"><i class="fas fa-triangle-exclamation"></i></div>
                    <p id="homeConfirmBody" class="mb-0">Are you sure?</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-cancel" id="homeConfirmCancelBtn" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="homeConfirmYesBtn">Yes</button>
                    <button type="button" class="btn btn-success d-none" id="homeConfirmOkBtn" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/push-notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/rfq.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
<script>
// UX improvement for modalRequestForm submission
console.log('Loading modal form submission handler');


    <!-- Remove any existing selection-related inline styles -->
    <style>
        .plant-selection-mode .admin-plant-card .selection-overlay,
        .plant-selection-mode .user-plant-card .selection-overlay {
            display: block !important;
            z-index: 999 !important;
        }

        .plant-selection-mode .selection-checkbox {
            z-index: 1000 !important;
            pointer-events: auto !important;
        }

        .plant-selection-mode .admin-plant-card.selected .selection-checkbox,
        .plant-selection-mode .user-plant-card.selected .selection-checkbox {
            background-color: #198754 !important;
        }

        .plant-selection-mode .admin-plant-card.selected .selection-checkbox i,
        .plant-selection-mode .user-plant-card.selected .selection-checkbox i {
            color: white !important;
        }

        /* Add styles to prevent text selection in search results */
        .search-result-item {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            cursor: pointer;
        }

        /* Add styles for card selection */
        .plant-selection-mode .admin-plant-card,
        .plant-selection-mode .user-plant-card {
            cursor: pointer;
            position: relative;
        }

        .plant-selection-mode .admin-plant-card.selected,
        .plant-selection-mode .user-plant-card.selected {
            border: 2px solid #198754;
        }

        .plant-selection-mode .admin-plant-card:hover,
        .plant-selection-mode .user-plant-card:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
    </style>

    <style>
    /* Animated Success Checkmark */
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }

    .check-icon {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4caf50;
    }

    .check-icon::before {
        top: 3px;
        left: -2px;
        width: 30px;
        transform-origin: 100% 50%;
        border-radius: 100px 0 0 100px;
    }

    .check-icon::after {
        top: 0;
        left: 30px;
        width: 60px;
        transform-origin: 0 50%;
        border-radius: 0 100px 100px 0;
        animation: rotate-circle 4.25s ease-in;
    }

    .icon-line {
        height: 5px;
        background-color: #4caf50;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }

    .icon-line.line-tip {
        top: 46px;
        left: 14px;
        width: 25px;
        transform: rotate(45deg);
        animation: icon-line-tip 0.75s;
    }

    .icon-line.line-long {
        top: 38px;
        right: 8px;
        width: 47px;
        transform: rotate(-45deg);
        animation: icon-line-long 0.75s;
    }

    .checkmark-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(76, 175, 80, 0.5);
    }

    .icon-fix {
        top: 8px;
        width: 5px;
        left: 26px;
        z-index: 1;
        height: 85px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #fff;
    }

    @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }
        5% {
            transform: rotate(-45deg);
        }
        12% {
            transform: rotate(-405deg);
        }
        100% {
            transform: rotate(-405deg);
        }
    }

    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }
        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }
        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }
        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
    }

    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }
        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }
        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
    }
    </style>

    <!-- Direct script to fix selection counter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Direct selection counter script loaded');

            // Function to directly handle selection toggle
            function directToggleSelection(card, checkboxClicked = false) {
                // If not checkbox clicked, don't toggle selection (preserve details mode)
                if (!checkboxClicked) return;

                // Toggle the selected class
                card.classList.toggle('selected');

                // Find the existing checkbox (should already exist from setup)
                let checkbox = card.querySelector('.selection-checkbox');
                
                // If checkbox doesn't exist for some reason, create it
                if (!checkbox) {
                    console.warn('Checkbox not found, creating new one');
                    checkbox = document.createElement('div');
                    checkbox.classList.add('selection-checkbox');
                    checkbox.style.position = 'absolute';
                    checkbox.style.top = '10px';
                    checkbox.style.right = '10px';
                    checkbox.style.width = '34px';
                    checkbox.style.height = '34px';
                    checkbox.style.borderRadius = '50%';
                    checkbox.style.background = 'white';
                    checkbox.style.border = '2px solid #ddd';
                    checkbox.style.display = 'flex';
                    checkbox.style.alignItems = 'center';
                    checkbox.style.justifyContent = 'center';
                    checkbox.style.zIndex = '1000';
                    checkbox.style.pointerEvents = 'auto';
                    checkbox.style.cursor = 'pointer';
                    checkbox.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
                    checkbox.innerHTML = '<i class="fas fa-check" style="color: transparent; font-size: 18px;"></i>';
                    checkbox.style.display = document.body.classList.contains('plant-selection-mode') ? 'flex' : 'none';
                    card.appendChild(checkbox);
                }

                // Update checkbox visual state
                const checkIcon = checkbox.querySelector('i');
                if (card.classList.contains('selected')) {
                    checkbox.style.backgroundColor = '#198754';
                    checkbox.style.border = '2px solid #198754';
                    if (checkIcon) checkIcon.style.color = 'white';

                    // Add border highlight - applied with !important
                    card.style.setProperty('border', '2px solid #198754', 'important');
                    card.style.setProperty('transform', 'scale(1.02)', 'important');
                } else {
                    checkbox.style.backgroundColor = 'white';
                    checkbox.style.border = '2px solid #ddd';
                    if (checkIcon) checkIcon.style.color = 'transparent';

                    // Remove border highlight - reset to default
                    card.style.removeProperty('border');
                    card.style.removeProperty('transform');
                }

                // Update counter immediately
                updateSelectionCounter();
            }

            // Function to update selection counter
            function updateSelectionCounter() {
                // Count selected plants
                const selectedCards = document.querySelectorAll('.admin-plant-card.selected, .user-plant-card.selected');
                const count = selectedCards.length;

                console.log('Selection counter update - Found ' + count + ' selected plants');

                // Find send plants button
                const sendBtn = document.getElementById('sendPlantsBtn');
                if (sendBtn) {
                    // Update button text and style
                    sendBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Plants (' + count + ')';

                    if (count > 0) {
                        sendBtn.disabled = false;
                        sendBtn.classList.remove('btn-secondary');
                        sendBtn.classList.add('btn-success');
                    } else {
                        sendBtn.disabled = true;
                        sendBtn.classList.remove('btn-success');
                        sendBtn.classList.add('btn-secondary');
                    }

                    console.log('Updated button:', sendBtn.innerHTML);
                }
            }

            // Override click handling for all cards when in selection mode
            function setupDirectSelectionHandling() {
                // Check every second if we need to add direct handlers
                setInterval(function() {
                    // Only if in selection mode and handlers not added yet
                    if (document.body.classList.contains('plant-selection-mode') &&
                        !document.body.hasAttribute('direct-handlers-added')) {

                        console.log('Adding direct selection handlers');
                        document.body.setAttribute('direct-handlers-added', 'true');

                        // Add styles to ensure consistency
                        const styleEl = document.createElement('style');
                        styleEl.textContent = `
                            .admin-plant-card, .user-plant-card {
                                position: relative !important;
                                border: 1px solid #dee2e6 !important;
                                border-radius: 0.375rem !important;
                                overflow: hidden !important;
                                transition: all 0.2s ease-in-out !important;
                            }
                            .admin-plant-card.featured, .user-plant-card.featured {
                                border-color: #28a745 !important;
                            }
                            .plant-selection-mode .selection-checkbox {
                                display: flex !important;
                                pointer-events: auto !important;
                                cursor: pointer !important;
                            }
                        `;
                        document.head.appendChild(styleEl);

                        // First, ensure all cards have proper styles and selectors
                        document.querySelectorAll('.admin-plant-card, .user-plant-card').forEach(function(card) {
                            // Ensure consistent border on all cards
                            card.style.setProperty('border', '1px solid #dee2e6', 'important');

                            // Check if checkbox already exists to avoid duplicates
                            let checkbox = card.querySelector('.selection-checkbox');
                            if (!checkbox) {
                                // Create checkboxes on all cards for selection mode
                                checkbox = document.createElement('div');
                                checkbox.classList.add('selection-checkbox');
                                checkbox.style.position = 'absolute';
                                checkbox.style.top = '10px';
                                checkbox.style.right = '10px';
                                checkbox.style.width = '34px';
                                checkbox.style.height = '34px';
                                checkbox.style.borderRadius = '50%';
                                checkbox.style.background = 'white';
                                checkbox.style.border = '2px solid #ddd';
                                checkbox.style.display = 'flex';
                                checkbox.style.alignItems = 'center';
                                checkbox.style.justifyContent = 'center';
                                checkbox.style.zIndex = '1000';
                                checkbox.style.pointerEvents = 'auto';
                                checkbox.style.cursor = 'pointer';
                                checkbox.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
                                checkbox.innerHTML = '<i class="fas fa-check" style="color: transparent; font-size: 18px;"></i>';
                                checkbox.style.display = document.body.classList.contains('plant-selection-mode') ? 'flex' : 'none';
                                card.appendChild(checkbox);

                                // Only checkbox should toggle selection, not card
                                checkbox.addEventListener('click', function(e) {
                                    if (!document.body.classList.contains('plant-selection-mode')) return;

                                    // Toggle selection of parent card
                                    directToggleSelection(card, true);
                                    e.stopPropagation();

                                    console.log('Checkbox clicked, selection toggled');
                                });
                            }
                        });

                        // Initial counter update
                        updateSelectionCounter();
                    }

                    // If not in selection mode, remove the flag so handlers get added next time
                    if (!document.body.classList.contains('plant-selection-mode') &&
                        document.body.hasAttribute('direct-handlers-added')) {
                        document.body.removeAttribute('direct-handlers-added');

                        // Hide all checkboxes when not in selection mode
                        document.querySelectorAll('.selection-checkbox').forEach(function(checkbox) {
                            checkbox.style.display = 'none';
                        });
                    }
                }, 1000);
            }

            // Start the monitoring process
            setupDirectSelectionHandling();

            // Patch the Send Plants button to bypass the original handler
            setInterval(function() {
                const sendPlantsBtn = document.getElementById('sendPlantsBtn');
                if (sendPlantsBtn && !sendPlantsBtn.hasAttribute('direct-handler-added')) {
                    // Remove all existing event listeners by cloning
                    const newBtn = sendPlantsBtn.cloneNode(true);
                    sendPlantsBtn.parentNode.replaceChild(newBtn, sendPlantsBtn);

                    // Add our direct handler
                    newBtn.setAttribute('direct-handler-added', 'true');
                    newBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Create a global array of selected plants
                        window.selectedPlants = [];

                        // Get all selected plants
                        const selectedCards = document.querySelectorAll('.admin-plant-card.selected, .user-plant-card.selected');
                        console.log('Direct Send Plants button has', selectedCards.length, 'selected plants');

                        if (selectedCards.length === 0) {
                            AlertSystem.alert({
                                title: 'No Plants Selected',
                                message: 'Please select at least one plant to continue.',
                                type: 'warning'
                            });
                            return;
                        }

                        // Build the plants array
                        selectedCards.forEach(function(card) {
                            // Basic plant info
                            const plantName = card.querySelector('.card-title')?.textContent?.trim() || 'Unknown Plant';
                            const plantId = card.getAttribute('data-id') || 'plant_' + Math.random().toString(36).substr(2, 9);

                            // Default values
                            let plantData = {
                                id: plantId,
                                name: plantName,
                                quantity: 1,
                                code: '',
                                height: '',
                                spread: '',
                                spacing: ''
                            };

                            // Try to extract details from the details panel
                            const detailsPanel = card.querySelector('.plant-details-panel');
                            if (detailsPanel) {
                                console.log('Found details panel for', plantName);

                                // Look for code
                                const codeElement = detailsPanel.querySelector('[data-field="code"]');
                                if (codeElement) {
                                    const text = codeElement.textContent.trim();
                                    const match = text.match(/Code:\s*(.*)/);
                                    if (match && match[1]) {
                                        plantData.code = match[1].trim();
                                    }
                                }

                                // Look for height
                                const heightElement = detailsPanel.querySelector('[data-field="height_mm"]');
                                if (heightElement) {
                                    const text = heightElement.textContent.trim();
                                    const match = text.match(/Height:\s*([0-9]+)/);
                                    if (match && match[1]) {
                                        plantData.height = match[1].trim();
                                    }
                                }

                                // Look for spread
                                const spreadElement = detailsPanel.querySelector('[data-field="spread_mm"]');
                                if (spreadElement) {
                                    const text = spreadElement.textContent.trim();
                                    const match = text.match(/Spread:\s*([0-9]+)/);
                                    if (match && match[1]) {
                                        plantData.spread = match[1].trim();
                                    }
                                }

                                // Look for spacing
                                const spacingElement = detailsPanel.querySelector('[data-field="spacing_mm"]');
                                if (spacingElement) {
                                    const text = spacingElement.textContent.trim();
                                    const match = text.match(/Spacing:\s*([0-9]+)/);
                                    if (match && match[1]) {
                                        plantData.spacing = match[1].trim();
                                    }
                                }

                                // Try alternative approach
                                const measurementItems = detailsPanel.querySelectorAll('.measurements li');
                                measurementItems.forEach(item => {
                                    const text = item.textContent.trim();

                                    if (text.includes('Height:') && !plantData.height) {
                                        const match = text.match(/Height:\s*([0-9]+)/);
                                        if (match && match[1]) {
                                            plantData.height = match[1].trim();
                                        }
                                    }

                                    if (text.includes('Spread:') && !plantData.spread) {
                                        const match = text.match(/Spread:\s*([0-9]+)/);
                                        if (match && match[1]) {
                                            plantData.spread = match[1].trim();
                                        }
                                    }

                                    if (text.includes('Spacing:') && !plantData.spacing) {
                                        const match = text.match(/Spacing:\s*([0-9]+)/);
                                        if (match && match[1]) {
                                            plantData.spacing = match[1].trim();
                                        }
                                    }
                                });

                                // Try super alternative approach for code
                                if (!plantData.code) {
                                    const allElements = detailsPanel.querySelectorAll('p, span, div');
                                    for (const element of allElements) {
                                        const text = element.textContent.trim();
                                        if (text.includes('Code:')) {
                                            const match = text.match(/Code:\s*(.*)/);
                                            if (match && match[1]) {
                                                plantData.code = match[1].trim();
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            window.selectedPlants.push(plantData);
                        });

                        console.log('Selected plants for RFQ:', window.selectedPlants.length);

                        // Show loading with domino loader
                        LoadingManager.show('Preparing RFQ Form...', 'Please wait');

                        // Get user info
                        const userEmail = document.getElementById('requestEmail')?.value || 'guest@example.com';
                        const userName = document.querySelector('.profile-btn span')?.textContent.trim() || 'Guest User';

                        // Process the request
                        setTimeout(function() {
                            // Hide loading
                            LoadingManager.hide();

                            // Show RFQ form modal
                            showRfqModal();

                            // Get today's date and date 2 weeks from now
                            const today = new Date();
                            const twoWeeksLater = new Date(today.getTime() + 14 * 24 * 60 * 60 * 1000);

                            // Format dates
                            const formatDate = function(date) {
                                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                                return date.toLocaleDateString('en-US', options);
                            };

                            // Populate the form
                            document.getElementById('rfqDate').textContent = formatDate(today);
                            document.getElementById('rfqDueDate').textContent = formatDate(twoWeeksLater);
                            document.getElementById('buyerName').textContent = userName;
                            document.getElementById('buyerEmail').textContent = userEmail;

                            // Fill the items table
                            const itemsTable = document.getElementById('rfqItemsTable');
                            itemsTable.innerHTML = '';

                            console.log(`Populating table with ${window.selectedPlants.length} plants`);

                            window.selectedPlants.forEach(function(plant, index) {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="text-center align-middle">${index + 1}</td>
                                    <td class="text-center">
                                        <input type="number" min="1" value="1" class="form-control form-control-sm"
                                            onchange="updatePlantTotalPrice(this)">
                                    </td>
                                    <td class="align-middle">${plant.name}</td>
                                    <td class="align-middle">${plant.code || ''}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm height-field">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm spread-field">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm spacing-field">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm remarks-field" placeholder="Add remarks">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm unit-price" value="" min="0" step="0.01"
                                            onchange="updatePlantTotalPrice(this)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm total-price-input" value="" min="0" step="0.01">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm">
                                    </td>
                                `;
                                itemsTable.appendChild(row);
                                console.log(`Added row ${index + 1} for ${plant.name}`);
                            });

                            // Make sure the table section scrolls to top
                            const tableSection = document.querySelector('#rfqFormModal .section:has(.table-bordered)');
                            if (tableSection) {
                                setTimeout(() => {
                                    tableSection.scrollTop = 0;
                                    console.log('Reset table scroll position');
                                }, 100);
                            }

                            // Add function to calculate total price
                            window.updatePlantTotalPrice = function(input) {
                                const row = input.closest('tr');
                                const quantity = parseFloat(row.querySelector('td:nth-child(2) input').value) || 0;
                                const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                                const totalPrice = (quantity * unitPrice).toFixed(2);

                                // Update the total price input field if not manually edited
                                const totalPriceInput = row.querySelector('.total-price-input');
                                if (totalPriceInput && !totalPriceInput.hasAttribute('user-edited')) {
                                    totalPriceInput.value = totalPrice;
                                }
                            };

                            // Add event listener to flag when user manually edits total price
                            document.querySelectorAll('#rfqItemsTable .total-price-input').forEach((input) => {
                                input.addEventListener('input', function() {
                                    this.setAttribute('user-edited', 'true');
                                });
                            });

                            // Reset selection mode
                            document.body.classList.remove('plant-selection-mode');

                            // Remove selection bar
                            const selectionBar = document.getElementById('selectionBar');
                            if (selectionBar) {
                                selectionBar.remove();
                            }

                            // Restore original search controls
                            const searchControlsContainer = document.querySelector('.search-controls-container');
                            if (searchControlsContainer) {
                                const originalContent = searchControlsContainer.getAttribute('data-original-content');
                                if (originalContent) {
                                    searchControlsContainer.innerHTML = originalContent;

                                    // Re-attach event listener to the Request Plants button
                                    const requestPlantsBtn = document.getElementById('requestPlantsBtn');
                                    if (requestPlantsBtn) {
                                        requestPlantsBtn.addEventListener('click', function() {
                                            const modal = document.getElementById('requestPlantsModal');
                                            if (modal) {
                                                const modalInstance = new bootstrap.Modal(modal);
                                                modalInstance.show();
                                            }
                                        });
                                    }
                                }
                            }

                            // Remove selected class from all cards
                            document.querySelectorAll('.admin-plant-card.selected, .user-plant-card.selected').forEach(card => {
                                card.classList.remove('selected');
                            });

                            // Remove all selection overlays
                            document.querySelectorAll('.selection-overlay').forEach(overlay => {
                                overlay.remove();
                            });

                            // Add event listener for submit button
                            document.getElementById('submitRequest').addEventListener('click', function() {
                                // Show loading state
                                const submitBtn = this;
                                const originalText = submitBtn.innerHTML;
                                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                                submitBtn.disabled = true;

                                // Prepare request data
                        const requestData = {
                            email: userEmail,
                            name: userName,
                                    items_json: JSON.stringify(window.selectedPlants.map(plant => ({
                                        name: plant.name,
                                        quantity: plant.quantity || '',
                                        unit_price: plant.unit_price || '',
                                        total_price: plant.total_price || '',
                                        remarks: plant.remarks || ''
                                    })))
                        };

                                // Send request
                        fetch('/client-request', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(requestData)
                        })
                        .then(response => response.json())
                        .then(data => {
                                    if (data.message) {
                                        // Close the RFQ modal
                            closeRfqModal();

                                        // Show success message popup
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Request Submitted!',
                                            html: `
                                                <div class="text-center mb-3">
                                                    <p>Your request has been submitted successfully!</p>
                                                    <p>Request ID: <strong>${data.request_id}</strong></p>
                                                    <p>We'll process your request shortly and send a response to your email address.</p>
                                                </div>
                                            `,
                                            confirmButtonText: 'Continue Browsing',
                                            confirmButtonColor: '#198754'
                                        });
                                    }
                        })
                        .catch(error => {
                                    console.error('Error:', error);
                                    // Show error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'An error occurred while submitting your request. Please try again.'
                                    });
                                })
                                .finally(() => {
                                    // Reset button state
                                    submitBtn.innerHTML = originalText;
                                    submitBtn.disabled = false;
                                });
                        });
                        }, 1000);
                    });

                    console.log('Added direct handler to Send Plants button');
                }
            }, 1000);
        });
    </script>

    <!-- Hidden auth check element for JavaScript -->
    @auth
        <div data-auth-check="true" style="display: none;"></div>
        @if(Auth::user()->hasClientAccess())
            <div data-user-role="client" style="display: none;"></div>
        @endif
    @endauth
    </div><!-- End page-content -->

    <!-- Preloader script -->
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('page-preloader');
            const content = document.querySelector('.page-content');
            
            // Add a small delay to ensure everything is rendered
            setTimeout(function() {
                content.classList.add('loaded');
                preloader.style.opacity = '0';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 300);
            }, 100);
        });
    </script>
</body>
</html>