<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Your Identity - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        /* Blurred background - same as login page */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background-image: url('../images/salengap.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            filter: blur(8px) brightness(0.7);
            -webkit-filter: blur(8px) brightness(0.7);
            transform: scale(1.1);
            z-index: -1;
            pointer-events: none;
        }
        
        .verify-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            padding: 30px;
            border-radius: 1rem 1rem 0 0;
            text-align: center;
        }
        .card-header-custom h4 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .card-header-custom p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .card-body-custom {
            padding: 40px 30px;
        }
        
        /* New separate code boxes styling */
        .code-boxes-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 20px 0;
        }
        
        .code-box {
            width: 60px;
            height: 70px;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background-color: #2d2d2d;
            color: white;
            font-family: 'Courier New', monospace;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .code-box:focus {
            border-color: #198754;
            background-color: #3d3d3d;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
        }
        
        .code-box:disabled {
            background-color: #1d1d1d;
            opacity: 0.5;
        }
        
        /* Button group styling */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .cancel-btn {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            background: #e8e8e8;
            color: #666;
            border: none;
            border-radius: 8px;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }
        
        .cancel-btn:hover {
            background: #d8d8d8;
            color: #333;
        }
        
        .verify-btn-new {
            flex: 1;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            background: #5b5fff;
            color: white;
            border: none;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .verify-btn-new:hover {
            background: #4a4edb;
        }
        
        .verify-btn-new:disabled {
            background: #9999ff;
            cursor: not-allowed;
        }
        
        /* Old single input styling (keep for backwards compatibility) */
        .code-input {
            font-size: 32px;
            letter-spacing: 15px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            padding: 20px;
            border: 3px solid #198754;
            border-radius: 10px;
            background-color: white;
        }
        
        .code-input:focus {
            border-color: #157347;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
            outline: none;
        }
        
        .verify-btn {
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            background: #000;
            color: white;
            border: none;
            border-radius: 0.375rem;
            transition: all 0.2s;
            width: 100%;
        }
        
        .verify-btn:hover {
            background: #1a1a1a;
        }
        .resend-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        .resend-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
        .icon-shield {
            font-size: 50px;
            color: white;
            margin-bottom: 15px;
        }
        .lockout-message {
            background: #f8d7da;
            border: 2px solid #f5c2c7;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        .lockout-icon {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 15px;
        }
        
        /* Match login page styling */
        .text-gray-600 {
            color: #4b5563;
        }
        .text-muted {
            color: #6b7280;
        }
        
        /* Mobile responsive */
        @media (max-width: 640px) {
            .verify-card {
                max-width: 90%;
                width: 90%;
            }
            .card-body-custom {
                padding: 30px 20px;
            }
            .code-boxes-container {
                gap: 8px;
            }
            .code-box {
                width: 45px;
                height: 60px;
                font-size: 24px;
                border-radius: 8px;
            }
            .button-group {
                flex-direction: column;
            }
            .cancel-btn, .verify-btn-new {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="card-header-custom">
            <i class="fas fa-shield-alt icon-shield"></i>
            <h4>Security Verification</h4>
            <p>Protect your account with two-factor authentication</p>
        </div>
        
        <div class="card-body-custom">
            @if($lockedOut)
                <div class="lockout-message">
                    <i class="fas fa-lock lockout-icon"></i>
                    <h5 class="text-danger">Account Temporarily Locked</h5>
                    <p class="mb-0">
                        Too many failed attempts. Please try again in <strong>{{ $lockoutMinutes }} minutes</strong>.
                    </p>
                </div>
            @else
                <p class="text-center text-muted mb-4">
                    <i class="fas fa-envelope me-2"></i>
                    We've sent a 6-digit verification code to your email address.
                    <br><small>Check your inbox and enter the code below.</small>
                </p>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('mfa.verify.post') }}" id="verifyForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label text-center d-block fw-bold mb-3">
                            Please check your email
                        </label>
                        <p class="text-center text-muted mb-4" style="font-size: 14px;">
                            @php
                                $user = auth()->user();
                                $email = $user->email;
                                $atPos = strpos($email, '@');
                                $masked = substr($email, 0, 3) . '***' . substr($email, $atPos);
                            @endphp
                            We've sent a code to <strong>{{ $masked }}</strong>
                        </p>
                        
                        <!-- Hidden input to store the complete code -->
                        <input type="hidden" id="code" name="code" required>
                        
                        <!-- 6 separate input boxes -->
                        <div class="code-boxes-container">
                            <input type="text" class="code-box" maxlength="1" pattern="[0-9]" autocomplete="off" data-index="0">
                            <input type="text" class="code-box" maxlength="1" pattern="[0-9]" autocomplete="off" data-index="1">
                            <input type="text" class="code-box" maxlength="1" pattern="[0-9]" autocomplete="off" data-index="2">
                            <input type="text" class="code-box" maxlength="1" pattern="[0-9]" autocomplete="off" data-index="3">
                            <input type="text" class="code-box" maxlength="1" pattern="[0-9]" autocomplete="off" data-index="4">
                            <input type="text" class="code-box" maxlength="1" pattern="[0-9]" autocomplete="off" data-index="5">
                        </div>
                        
                        <small class="text-muted d-block text-center mt-3">
                            <i class="fas fa-clock me-1"></i>Code expires in 5 minutes
                        </small>
                    </div>
                    
                    <!-- Remember device checkbox -->
                    <div class="form-check mb-4 text-center">
                        <input type="checkbox" class="form-check-input" id="remember_device" name="remember_device" value="1">
                        <label class="form-check-label" for="remember_device" style="font-size: 14px;">
                            Trust this device for 30 days
                        </label>
                    </div>
                    
                    <div class="button-group">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form-cancel').submit();" 
                           class="btn cancel-btn">
                            Cancel
                        </a>
                        <button type="submit" class="btn verify-btn-new">
                            Verify
                        </button>
                    </div>
                </form>
                
                <form id="logout-form-cancel" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted mb-2" style="font-size: 14px;">Didn't get the code? 
                        <form method="POST" action="{{ route('mfa.resend') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link resend-link p-0" style="font-size: 14px;">
                                Click to resend
                            </button>
                        </form>
                    </p>
                </div>
            @endif
            
            <hr class="my-4">
            
            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Your security is our priority
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle separate code boxes
        const codeBoxes = document.querySelectorAll('.code-box');
        const hiddenCodeInput = document.getElementById('code');
        const form = document.getElementById('verifyForm');
        
        // Focus first box on load
        if (codeBoxes.length > 0) {
            codeBoxes[0].focus();
        }
        
        // Handle input in code boxes
        codeBoxes.forEach((box, index) => {
            // Handle typing
            box.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Move to next box if digit entered
                if (this.value.length === 1 && index < codeBoxes.length - 1) {
                    codeBoxes[index + 1].focus();
                }
                
                // Update hidden input with complete code
                updateCompleteCode();
            });
            
            // Handle backspace
            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    codeBoxes[index - 1].focus();
                }
                
                // Handle paste
                if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                }
            });
            
            // Handle paste event
            box.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                
                // Fill boxes with pasted data
                for (let i = 0; i < Math.min(pastedData.length, codeBoxes.length); i++) {
                    codeBoxes[i].value = pastedData[i];
                }
                
                // Focus last filled box or last box
                const lastFilledIndex = Math.min(pastedData.length, codeBoxes.length) - 1;
                codeBoxes[lastFilledIndex].focus();
                
                updateCompleteCode();
            });
        });
        
        // Update hidden input with complete code
        function updateCompleteCode() {
            let completeCode = '';
            codeBoxes.forEach(box => {
                completeCode += box.value;
            });
            hiddenCodeInput.value = completeCode;
            
            // Auto-submit when all 6 digits are entered
            if (completeCode.length === 6) {
                // Disable inputs to prevent changes during submission
                codeBoxes.forEach(box => box.disabled = true);
                
                // Submit form after short delay for visual feedback
                setTimeout(() => {
                    form.submit();
                }, 300);
            }
        }
    </script>
</body>
</html>
