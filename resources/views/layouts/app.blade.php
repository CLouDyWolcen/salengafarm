<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Salenga Farm') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap & Font Awesome -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
        <link href="{{ asset('css/loading.css') }}?v={{ time() }}" rel="stylesheet">
        <link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="{{ asset('csss/public.css') }}" rel="stylesheet">
        
        <!-- FontAwesome spin fix -->
        <style>
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
        </style>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.sidebar')
            <div class="container-fluid" style="margin-left: 220px; padding-top: 0.5rem;">
            <!-- Page Heading -->
            @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow" style="padding: 0.5rem 1rem;">
                        <div class="max-w-7xl mx-auto py-2 px-2 sm:px-4 lg:px-6" style="font-size: 1.1rem;">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
                <main style="font-size: 0.97rem;">
                @yield('content')
            </main>
            </div>
        </div>
        
        <!-- Base Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/loading.js') }}"></script>
        @auth
        <script src="{{ asset('js/push-notifications.js') }}?v={{ time() }}"></script>
        @endauth
        
        <!-- Scripts Section -->
        @yield('scripts')
        
        <!-- Toast Container for Notifications -->
        <div id="toastContainer" class="toast-container"></div>
    </body>
</html>
