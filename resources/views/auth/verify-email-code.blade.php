<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Your Email - Salenga Farm</title>
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
            max-width: 420px;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 1rem 1rem 0 0;
            text-align: center;
        }
        
        .card-header-custom h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        
        .card-header-custom p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 13px;
        }
        
        .card-body-custom {
            padding: 30px 25px;
        }
        
        .icon-email {
            font-size: 36px;
            color: white;
            margin-bottom: 10px;
        }
        
        /* Compact info box */
        .info-box {
            background: #d1f4e0;
            border-left: 3px solid #198754;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #0f5132;
            line-height: 1.5;
        }
        
        /* Separate code boxes styling - more compact */
        .code-boxes-container {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 20px 0;
        }
        
        .code-box {
            width: 50px;
            height: 56px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background-color: #f9fafb;
            color: #1f2937;
            font-family: 'Courier New', monospace;
            outline: none;
            transition: all 0.2s ease;
        }
        
        .code-box:focus {
            border-color: #198754;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
        }
        
        .code-box:disabled {
            background-color: #e5e7eb;
            opacity: 0.6;
        }
        
        /* Verify button - green modern */
        .verify-btn-new {
            width: 100%;
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 600;
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            border: none;
            border-radius: 8px;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(25, 135, 84, 0.2);
        }
        
        .verify-btn-new:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
        }
        
        .verify-btn-new:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .resend-link {
            color: #198754;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
        }
        
        .resend-link:hover {
            color: #157347;
            text-decoration: underline;
        }
        
        /* Match login page styling */
        .text-gray-600 {
            color: #4b5563;
        }
        .text-muted {
            color: #6b7280;
        }
        
        /* Compact spacing */
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        
        /* Success/Error alerts - more compact */
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }
        
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        
        .alert i {
            font-size: 14px;
        }
        
        /* Footer text */
        .footer-text {
            text-align: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text small {
            font-size: 12px;
            color: #9ca3af;
        }
        
        /* Custom checkbox styling - green */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-left: 0;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #4b5563;
            border-radius: 4px;
            cursor: pointer;
            margin: 0;
            flex-shrink: 0;
            background-color: white;
        }
        
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        }
        
        .form-check-input:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
            outline: none;
        }
        
        .form-check-label {
            cursor: pointer;
            user-select: none;
            margin: 0;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }
        
        /* Mobile responsive */
        @media (max-width: 640px) {
            .verify-card {
                max-width: 95%;
                width: 95%;
            }
            .card-body-custom {
                padding: 25px 20px;
            }
            .card-header-custom {
                padding: 20px 25px;
            }
            .code-boxes-container {
                gap: 6px;
            }
            .code-box {
                width: 44px;
                height: 52px;
                font-size: 24px;
                border-radius: 8px;
            }
            /* Adjust checkbox padding for mobile */
            .form-check-mobile {
                padding-left: 20px !important;
            }
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="card-header-custom">
            <i class="fas fa-envelope-open icon-email"></i>
            <h4>Verify Your Email</h4>
            <p>Complete your registration</p>
        </div>
        
        <div class="card-body-custom">
            <div class="info-box">
                <p>
                    <i class="fas fa-paper-plane" style="margin-right: 6px;"></i>
                    We've sent a 6-digit code to your email. Check your inbox and enter it below.
                </p>
            </div>
            
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
            
            <form method="POST" action="{{ route('verification.code.verify') }}" id="verifyForm">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label text-center d-block fw-bold mb-3" style="font-size: 14px;">
                        Enter Verification Code
                    </label>
                    
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
                    
                    <small class="text-muted d-block text-center mt-2" style="font-size: 12px;">
                        <i class="fas fa-clock me-1"></i>Code expires in 10 minutes
                    </small>
                </div>
                
                <!-- Remember device checkbox -->
                <div class="form-check form-check-mobile mb-3" style="padding-left: 25px;">
                    <input type="checkbox" class="form-check-input" id="remember_device" name="remember_device" value="1">
                    <label class="form-check-label" for="remember_device">
                        Trust this device for 30 days
                    </label>
                </div>
                
                <button type="submit" class="btn verify-btn-new">
                    <i class="fas fa-check-circle me-2"></i>Verify & Continue
                </button>
            </form>
            
            <div class="text-center mt-3">
                <p class="text-muted mb-0" style="font-size: 13px;">
                    Didn't receive the code? 
                    <form method="POST" action="{{ route('verification.code.resend') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link resend-link p-0">
                            Click to resend
                        </button>
                    </form>
                </p>
            </div>
            
            <div class="footer-text">
                <small>
                    <i class="fas fa-leaf" style="color: #198754; margin-right: 4px;"></i>
                    Salenga Farm - Secure Registration
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
