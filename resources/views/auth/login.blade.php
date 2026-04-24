<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="{{ asset('js/loading.js') }}"></script>
    
    <style>
        /* Mobile-specific overrides with highest specificity */
        @media (max-width: 640px) {
            .login-container .max-w-md {
                max-width: 95vw !important;
                width: 95vw !important;
            }
            
            .login-container .max-w-md.bg-white {
                padding: 2rem !important;
            }
            
            .login-container h2 {
                font-size: 1.75rem !important;
            }
            
            .login-container .text-sm {
                font-size: 1.1rem !important;
            }
            
            .login-container .form-label {
                font-size: 1rem !important;
            }
            
            .login-container input {
                font-size: 1.1rem !important;
                height: 55px !important;
                padding: 14px 14px 14px 50px !important;
            }
            
            .login-container .sign-in-btn,
            .login-container .social-btn {
                font-size: 1.1rem !important;
                height: 55px !important;
                padding: 1rem !important;
            }
        }
    </style>
    
    <div class="min-h-screen">
        <div class="login-container position-relative">
            <div class="max-w-md bg-white rounded-3xl shadow-2xl p-10">
                <div class="text-center mb-6">
                    <h2>Login</h2>
                    <p class="text-gray-600 text-sm">Enter your credentials to access the system</p>
            </div>

                @if ($errors->any())
                    <div class="error-message" id="errorMessage">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="clean-input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" 
                                id="email"
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                placeholder="Enter your email"
                                autocomplete="email"
                                class="clean-input">
                    </div>
                </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="clean-input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" 
                                id="password"
                                name="password" 
                                required 
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                class="clean-input">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon')">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                    </div>
                </div>

                    <div class="text-sm mb-2">
                        <a href="{{ route('password.request') }}" class="text-primary hover:text-primary-dark">
                            Forgot your password?
                        </a>
                </div>

                <div>
                        <button type="submit" class="sign-in-btn">
                        Login
                    </button>
                </div>

                    <!-- OR Divider -->
                    <div class="divider-container">
                        <div class="divider-line"></div>
                        <div class="divider-text">- OR -</div>
                        <div class="divider-line"></div>
                    </div>
                    
                    <!-- Social Login Buttons -->
                    <div class="social-login-container">
                        <a href="{{ route('socialite.redirect', ['provider' => 'google']) }}" class="social-btn google-btn">
                            <i class="fab fa-google"></i> Sign in using Google
                        </a>
                    </div>

                    <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                            <a href="{{ route('register') }}" class="text-primary hover:text-primary-dark">Create one</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        // Form submission with loading screen
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Show the standardized loading overlay
                    setTimeout(function() {
                        LoadingManager.show('Logging In...', 'Please wait while we verify your credentials');
                    }, 300);
                });
            }
        });
    </script>
</x-guest-layout>

