<!-- resources/views/auth/passwords/reset.blade.php -->

<x-guest-layout>
    <!-- Main Container with Modern Design -->
    <div class="min-vh-100 d-flex align-items-center justify-content-center position-relative">
        <!-- Background Gradient Layer -->
        <div class="position-absolute top-0 start-0 w-100 h-100" 
             style="background: linear-gradient(135deg, #0a2e63 0%, #1a56bb 100%);"></div>
        
        <!-- Animated Background Pattern -->
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" id="particles-js"></div>
        
        <!-- Background Image -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-cover bg-center" 
             style="background-image: url('{{ asset('images/CHED.jpg') }}'); background-repeat: no-repeat; background-size: cover; opacity: 0.1;"></div>

        <!-- Spinner (Hidden by Default) -->
        <div id="loading-spinner" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(255, 255, 255, 0.8); z-index: 9999;">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>

        <!-- Authentication Card -->
        <div class="card shadow-lg border-0 rounded-lg p-4 position-relative z-2 auth-card" style="max-width: 450px; width: 95%;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/Logo.png') }}" alt="Authentication Card Logo" class="img-fluid mb-3 logo-animation" style="max-height: 90px;">
                    <h1 class="h3 fw-bold text-primary mb-2">Reset Your Password</h1>
                    <p class="text-muted">Create a new strong password for your account</p>
                </div>

                <!-- Validation Errors (Hidden by default, shown via JS) -->
                <div id="validation-errors" class="alert alert-danger mt-3 d-none">
                    <ul class="mb-0" id="error-list"></ul>
                </div>

                <!-- Reset Password Form -->
                <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <!-- Hidden Token Field -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Display -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-dark">Your Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope text-primary"></i></span>
                            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" 
                                class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Password Field with Show/Hide Toggle -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-dark">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-lock text-primary"></i></span>
                            <input id="password" type="password" name="password" required 
                                class="form-control bg-light" autocomplete="new-password">
                            <button class="btn btn-light border password-toggle" type="button" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Meter -->
                        <div class="password-strength mt-2">
                            <div class="progress" style="height: 5px;">
                                <div id="password-strength-meter" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <small id="password-strength-text" class="form-text mt-1">Password strength: Too weak</small>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="password-requirements mt-3">
                            <p class="text-muted mb-2" style="font-size: 0.85rem;">Password must contain:</p>
                            <div class="d-flex flex-wrap">
                                <div class="requirement" id="req-length"><i class="fas fa-times-circle text-danger me-1"></i> At least 8 characters</div>
                                <div class="requirement" id="req-uppercase"><i class="fas fa-times-circle text-danger me-1"></i> Uppercase letter</div>
                                <div class="requirement" id="req-lowercase"><i class="fas fa-times-circle text-danger me-1"></i> Lowercase letter</div>
                                <div class="requirement" id="req-number"><i class="fas fa-times-circle text-danger me-1"></i> Number</div>
                                <div class="requirement" id="req-special"><i class="fas fa-times-circle text-danger me-1"></i> Special character</div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Field with Show/Hide Toggle -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-lock text-primary"></i></span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required 
                                class="form-control bg-light" autocomplete="new-password">
                            <button class="btn btn-light border password-toggle" type="button" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwords-match" class="form-text d-none text-success mt-1">
                            <i class="fas fa-check-circle"></i> Passwords match
                        </div>
                        <div id="passwords-not-match" class="form-text d-none text-danger mt-1">
                            <i class="fas fa-times-circle"></i> Passwords don't match
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" id="reset-btn" class="btn btn-primary p-3 fw-bold" disabled>
                            <i class="fas fa-key me-2"></i> Reset Password
                        </button>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Particles.js for background animation -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        
        .auth-card {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            transition: all 0.3s ease;
            animation: card-appear 0.5s ease-out;
        }
        
        @keyframes card-appear {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        .logo-animation {
            animation: logo-float 4s ease-in-out infinite;
        }
        
        @keyframes logo-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .form-control, .input-group-text {
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.25rem rgba(74, 108, 247, 0.25);
        }
        
        .btn-primary {
            background-color: #0a58ca;
            border-color: #0a58ca;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: #0a4bb3;
            border-color: #0a4bb3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(10, 88, 202, 0.2);
        }
        
        .btn-primary:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            opacity: 0.5;
        }
        
        .password-toggle {
            cursor: pointer;
        }
        
        /* Password strength meter */
        .password-strength-meter {
            transition: width 0.3s ease;
        }
        
        /* Password requirements */
        .password-requirements {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.5rem;
        }
        
        .requirement {
            font-size: 0.75rem;
            color: #6c757d;
            padding: 0.25rem 0;
        }
        
        .requirement.met i {
            color: #10b981 !important;
        }
        
        /* Custom spinner */
        .spinner {
            margin: 100px auto 0;
            width: 70px;
            text-align: center;
        }

        .spinner > div {
            width: 18px;
            height: 18px;
            background-color: #0a58ca;
            border-radius: 100%;
            display: inline-block;
            animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        }

        .spinner .bounce1 {
            animation-delay: -0.32s;
        }

        .spinner .bounce2 {
            animation-delay: -0.16s;
        }

        @keyframes sk-bouncedelay {
            0%, 80%, 100% { 
                transform: scale(0);
            } 40% { 
                transform: scale(1.0);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .auth-card {
                width: 90%;
                padding: 1.5rem;
            }
            
            .password-requirements {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Particles.js
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                },
                "retina_detect": true
            });
            
            // Initialize Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "showDuration": "300",
                "hideDuration": "1000",
                "extendedTimeOut": "1000",
            };

            // Display session status as Toastr notification
            @if (session('status'))
                toastr.success("{{ session('status') }}");
            @endif

            // Display validation errors via SweetAlert
            @if ($errors->any())
                Swal.fire({
                    title: 'Oops!',
                    html: '<ul class="list-unstyled text-start">@foreach ($errors->all() as $error)<li><i class="fas fa-exclamation-circle text-danger me-2"></i> {{ $error }}</li>@endforeach</ul>',
                    icon: 'error',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#0a58ca'
                });
            @endif

            // Handle password visibility toggle
            const toggleButtons = document.querySelectorAll('.password-toggle');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
            
            // Password strength checker
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const strengthMeter = document.getElementById('password-strength-meter');
            const strengthText = document.getElementById('password-strength-text');
            const resetButton = document.getElementById('reset-btn');
            
            // Password requirement elements
            const reqLength = document.getElementById('req-length');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');
            
            // Password match indicators
            const passwordsMatch = document.getElementById('passwords-match');
            const passwordsNotMatch = document.getElementById('passwords-not-match');
            
            // Check password strength on input
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let strengthText = '';
                let strengthClass = '';
                
                // Update requirements
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                
                // Update requirement indicators
                updateRequirement(reqLength, hasLength);
                updateRequirement(reqUppercase, hasUppercase);
                updateRequirement(reqLowercase, hasLowercase);
                updateRequirement(reqNumber, hasNumber);
                updateRequirement(reqSpecial, hasSpecial);
                
                // Calculate strength
                if (hasLength) strength += 20;
                if (hasUppercase) strength += 20;
                if (hasLowercase) strength += 20;
                if (hasNumber) strength += 20;
                if (hasSpecial) strength += 20;
                
                // Update strength meter
                strengthMeter.style.width = strength + '%';
                
                // Set strength text and class
                if (strength <= 20) {
                    strengthText = 'Too weak';
                    strengthClass = 'bg-danger';
                } else if (strength <= 40) {
                    strengthText = 'Weak';
                    strengthClass = 'bg-warning';
                } else if (strength <= 60) {
                    strengthText = 'Fair';
                    strengthClass = 'bg-info';
                } else if (strength <= 80) {
                    strengthText = 'Good';
                    strengthClass = 'bg-primary';
                } else {
                    strengthText = 'Strong';
                    strengthClass = 'bg-success';
                }
                
                // Apply changes
                strengthMeter.className = 'progress-bar ' + strengthClass;
                document.getElementById('password-strength-text').textContent = 'Password strength: ' + strengthText;
                
                // Check if passwords match
                checkPasswordsMatch();
                
                // Enable submit button if password is strong enough and passwords match
                validateForm();
            });
            
            // Check if passwords match on confirmation input
            confirmPasswordInput.addEventListener('input', function() {
                checkPasswordsMatch();
                validateForm();
            });
            
            // Function to update requirement status
            function updateRequirement(element, isValid) {
                const icon = element.querySelector('i');
                
                if (isValid) {
                    element.classList.add('met');
                    icon.classList.remove('fa-times-circle', 'text-danger');
                    icon.classList.add('fa-check-circle', 'text-success');
                } else {
                    element.classList.remove('met');
                    icon.classList.remove('fa-check-circle', 'text-success');
                    icon.classList.add('fa-times-circle', 'text-danger');
                }
            }
            
            // Function to check if passwords match
            function checkPasswordsMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword === '') {
                    passwordsMatch.classList.add('d-none');
                    passwordsNotMatch.classList.add('d-none');
                } else if (password === confirmPassword) {
                    passwordsMatch.classList.remove('d-none');
                    passwordsNotMatch.classList.add('d-none');
                } else {
                    passwordsMatch.classList.add('d-none');
                    passwordsNotMatch.classList.remove('d-none');
                }
            }
            
            // Function to validate the entire form
            function validateForm() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const strength = parseInt(strengthMeter.style.width);
                
                // Requirements for enabling the submit button
                const passwordsMatchCheck = password === confirmPassword && confirmPassword !== '';
                const isStrongEnough = strength >= 60; // At least Fair strength
                
                resetButton.disabled = !(passwordsMatchCheck && isStrongEnough);
            }

            // Show spinner on form submit
            document.getElementById('reset-password-form').addEventListener('submit', function(e) {
                // Additional client-side validation
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const strength = parseInt(strengthMeter.style.width);
                
                if (strength < 60) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Weak Password',
                        text: 'Please create a stronger password for better security.',
                        icon: 'warning',
                        confirmButtonColor: '#0a58ca'
                    });
                    return;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Passwords Do Not Match',
                        text: 'Please make sure both passwords match.',
                        icon: 'error',
                        confirmButtonColor: '#0a58ca'
                    });
                    return;
                }
                
                // If validation passes, show spinner and submit
                document.getElementById('loading-spinner').classList.remove('d-none');
                
                // Animate button to show progress
                resetButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Resetting...';
                resetButton.disabled = true;
            });
        });
    </script>
</x-guest-layout>