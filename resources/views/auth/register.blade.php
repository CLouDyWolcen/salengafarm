<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="{{ asset('js/loading.js') }}"></script>
    <div class="min-h-screen">
        <div class="login-container position-relative">
            <div class="max-w-md bg-white rounded-3xl shadow-2xl p-2">
                <div class="text-center mb-1">
                    <h2>Create Account</h2>
                    <p class="text-gray-600 text-sm">Join us by creating your account</p>
                </div>

                @if ($errors->any())
                    <div class="error-message" id="errorMessage">
                        @if($errors->has('password_confirmation'))
                            The confirm password field confirmation does not match.
                        @else
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <form class="space-y-2" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name</label>
                                <div class="clean-input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" 
                                           id="first_name"
                                           name="first_name" 
                                           value="{{ old('first_name') }}" 
                                           required 
                                           placeholder="First name"
                                           autocomplete="given-name"
                                           class="clean-input">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name</label>
                                <div class="clean-input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" 
                                           id="last_name"
                                           name="last_name" 
                                           value="{{ old('last_name') }}" 
                                           required 
                                           placeholder="Last name"
                                           autocomplete="family-name"
                                           class="clean-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="clean-input-group">
                            <span class="input-icon">@</span>
                            <input type="email" 
                                   id="email"
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="Enter your email"
                                   autocomplete="email"
                                   class="clean-input">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group password-field-wrapper">
                                <!-- Password requirements speech bubble (fixed position above password field) -->
                                <div id="password-requirements" class="password-tooltip">
                                    <h4 class="font-medium mb-1 text-xs">Password Requirements:</h4>
                                    <ul>
                                        <li id="req-length"><i class="fas fa-check-circle"></i> 8+ characters</li>
                                        <li id="req-uppercase"><i class="fas fa-check-circle"></i> At least one uppercase letter</li>
                                        <li id="req-lowercase"><i class="fas fa-check-circle"></i> At least one lowercase letter</li>
                                        <li id="req-number"><i class="fas fa-check-circle"></i> At least one number</li>
                                        <li id="req-special"><i class="fas fa-check-circle"></i> At least one special character (@$!%*#?&)</li>
                                        <li id="req-sequential"><i class="fas fa-check-circle"></i> No sequential characters (e.g., Amzel1234567)</li>
                                        <li id="req-dictionary"><i class="fas fa-check-circle"></i> No dictionary words (e.g., Love109274)</li>
                                    </ul>
                                </div>
                                <label for="password" class="form-label">Password</label>
                                <div class="clean-input-group">        
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" 
                                           id="password"
                                           name="password" 
                                           required 
                                           placeholder="Create a password"
                                           autocomplete="new-password"
                                           onfocus="showPasswordRequirements()"
                                           onblur="hidePasswordRequirements()"
                                           oninput="validatePassword()"
                                           class="clean-input password-field">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                        <i class="fas fa-eye" id="toggleIcon1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="clean-input-group">        
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" 
                                           id="password_confirmation"
                                           name="password_confirmation" 
                                           required 
                                           placeholder="Confirm your password"
                                           autocomplete="new-password"
                                           class="clean-input password-field">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                        <i class="fas fa-eye" id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="register-button" class="sign-in-btn">
                        <span id="button-text">Create Account</span>
                        <span id="button-loader" class="spinner" style="display: none;"></span>
                    </button>

                    <div class="mt-4 flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-primary hover:text-primary-dark">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Overall form styling */
        body, .min-h-screen, .bg-gray-100 {
            background: transparent !important;
        }
        
        /* Loading indicator styles */
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            margin-left: 8px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
        }
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-align: center;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .login-container {
            position: relative;
            width: fit-content;
            margin: 0 auto;
            padding-top: 40px;
            background: transparent;
        }
        
        .max-w-md {
            width: 100%;
            max-width: 36rem;
            margin: 0 auto;
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 1rem 1.5rem !important;
        }
        
        .min-h-screen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 38rem;
            margin: 0 auto;
            padding: 1rem 0;
        }
        
        /* Password field wrapper to handle the tooltip positioning */
        .password-field-wrapper {
            position: relative;
        }
        
        /* Password requirements tooltip */
        .password-tooltip {
            display: none;
            position: absolute;
            top: -160px;
            left: 0;
            width: 260px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 10px 12px;
            z-index: 100;
        }
        
        .password-tooltip:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 20px;
            border-width: 10px 10px 0;
            border-style: solid;
            border-color: #f8f9fa transparent;
        }
        
        .password-tooltip h5 {
            margin: 0 0 8px 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
        }
        
        .password-tooltip ul {
            list-style: none;
            padding-left: 5px;
            margin: 0;
        }
        
        .password-tooltip li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            color: #495057;
            font-size: 0.8rem;
        }
        
        .password-tooltip i {
            margin-right: 8px;
            color: #22c55e;
            font-size: 14px;
        }
        
        /* Form controls */
        .form-label {
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            color: #4B5563;
        }
        
        .form-input {
            font-size: 0.875rem;
            border-radius: 0.5rem;
            width: 100%;
            border: none;
            outline: none;
        }
        
        .form-input::placeholder {
            font-size: 0.75rem;
            color: #9CA3AF;
        }
        
        .input-group {
            display: flex;
            align-items: center;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            background-color: #F9FAFB;
        }
        
        .input-icon {
            margin-right: 0.5rem;
            color: #9CA3AF;
            font-size: 0.8rem;
            width: 1rem;
            text-align: center;
        }
        
        .password-toggle {
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            padding: 0;
        }
        
        /* Row and column layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }
        
        .col-md-6 {
            width: 50%;
            padding: 0 0.5rem;
        }
        
        .form-group {
            margin-bottom: 0.75rem;
        }
        
        /* Submit button */
        .sign-in-btn {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #111827;
            color: white;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .sign-in-btn:hover {
            background-color: #1F2937;
        }
        
        /* Error message styling */
        .error-message {
            background-color: #FEE2E2;
            color: #B91C1C;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        
        /* Link styling */
        .text-primary {
            color: #3B82F6;
            text-decoration: none;
        }
        
        .text-primary:hover {
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .min-h-screen {
                width: 90%;
            }
            
            .col-md-6 {
                width: 100%;
            }
            
            .password-tooltip {
                width: 250px;
            }
        }
    </style>

    <script>
        // Form submission with loading screen
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const buttonText = document.getElementById('button-text');
            const buttonLoader = document.getElementById('button-loader');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Basic client-side validation first
                    const password = document.getElementById('password').value;
                    const passwordConfirm = document.getElementById('password_confirmation').value;
                    
                    if (password !== passwordConfirm) {
                        return; // Let the form validation handle this error
                    }
                    
                    // Show button spinner
                    buttonText.textContent = 'Processing...';
                    buttonLoader.style.display = 'inline-block';
                    document.getElementById('register-button').disabled = true;
                    
                    // Show the standardized loading overlay
                    setTimeout(function() {
                        LoadingManager.show('Creating Your Account...', 'Please wait while we set up your profile');
                    }, 300);
                });
            }
        });
        
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function showPasswordRequirements() {
            const requirements = document.getElementById('password-requirements');
            requirements.style.display = 'block';
            // Start validation on first focus
            validatePassword();
        }
        
        function hidePasswordRequirements() {
            // Only hide if not hovering
            if (!isHoveringOnRequirements) {
                const requirements = document.getElementById('password-requirements');
                requirements.style.display = 'none';
            }
        }
        
        // Track mouse hover on requirements
        let isHoveringOnRequirements = false;
        
        function validatePassword() {
            const password = document.getElementById('password').value;
            
            // Check requirements
            updateRequirement('req-length', password.length >= 8);
            updateRequirement('req-uppercase', /[A-Z]/.test(password));
            updateRequirement('req-lowercase', /[a-z]/.test(password));
            updateRequirement('req-number', /[0-9]/.test(password));
            updateRequirement('req-special', /[@$!%*#?&]/.test(password));
            
            // Check for sequential characters (e.g., 1234, abcd)
            updateRequirement('req-sequential', !hasSequentialChars(password));
            
            // Check for common dictionary words
            updateRequirement('req-dictionary', !hasDictionaryWord(password));
        }
        
        function hasSequentialChars(password) {
            // Check for numeric sequences (e.g., 1234, 4321)
            for (let i = 0; i < password.length - 3; i++) {
                // Check ascending sequence (e.g., 1234)
                if (
                    password.charCodeAt(i) + 1 === password.charCodeAt(i + 1) &&
                    password.charCodeAt(i + 1) + 1 === password.charCodeAt(i + 2) &&
                    password.charCodeAt(i + 2) + 1 === password.charCodeAt(i + 3)
                ) {
                    return true;
                }
                
                // Check descending sequence (e.g., 4321)
                if (
                    password.charCodeAt(i) - 1 === password.charCodeAt(i + 1) &&
                    password.charCodeAt(i + 1) - 1 === password.charCodeAt(i + 2) &&
                    password.charCodeAt(i + 2) - 1 === password.charCodeAt(i + 3)
                ) {
                    return true;
                }
            }
            return false;
        }
        
        function hasDictionaryWord(password) {
            // List of common words to reject
            const commonWords = ['password', 'love', 'admin', 'welcome', 'qwerty', 'abc', 'secret', 'letmein'];
            
            // Convert to lowercase for checking
            const lowerPassword = password.toLowerCase();
            
            // Check if password contains any common word
            for (const word of commonWords) {
                if (lowerPassword.includes(word)) {
                    return true;
                }
            }
            
            return false;
        }
        
        function updateRequirement(reqId, isValid) {
            const requirement = document.getElementById(reqId);
            const icon = requirement.querySelector('i');
            
            if (isValid) {
                icon.style.color = '#22c55e'; // Green
            } else {
                icon.style.color = '#ef4444'; // Red
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide error messages after 3 seconds
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.transition = 'opacity 0.5s ease';
                    errorMessage.style.opacity = '0';
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 500);
                }, 3000);
            }
            
            // Setup password validation events
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                passwordInput.addEventListener('input', validatePassword);
            }
            
            // Setup password requirements hover behavior
            const requirements = document.getElementById('password-requirements');
            if (requirements) {
                requirements.addEventListener('mouseenter', function() {
                    isHoveringOnRequirements = true;
                });
                
                requirements.addEventListener('mouseleave', function() {
                    isHoveringOnRequirements = false;
                    if (document.getElementById('password') !== document.activeElement) {
                        hidePasswordRequirements();
                    }
                });
            }
        });
    </script>
</x-guest-layout>
