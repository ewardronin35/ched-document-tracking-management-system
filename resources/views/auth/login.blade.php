<x-guest-layout>
    <!-- Internal Styles for Login Page -->
    <style>
        /* Variables for consistent theming */
        :root {
            --primary-color: #133A86;
            --secondary-color: #1CE5FF;
            --accent-color: #F0F4FF;
            --text-color: #2D3748;
            --error-color: #e53e3e;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition-speed: 0.3s;
        }

        /* Body Background with Gradient Overlay */
        body {
            background: url('{{ asset('images/CHED.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            height: 100vh;
            animation: fadeIn 0.8s ease-out;
            overflow-x: hidden;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Modern Overlay with Gradient */
        .overlay {
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%; 
            height: 100vh;
            background: linear-gradient(135deg, rgba(19, 58, 134, 0.8), rgba(0, 0, 0, 0.7));
            z-index: 1;
        }

        /* Main Container */
        .login-container {
            z-index: 2; 
            position: relative;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Left Side Content */
        .left-content {
            display: none; /* Hidden on mobile */
            color: white;
            padding: 2rem;
        }

        /* Login Card */
        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            transition: all var(--transition-speed);
            position: relative;
            backdrop-filter: blur(10px);
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        /* Branding Section */
        .brand-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .logo-text {
            color: var(--primary-color);
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            opacity: 0.7;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #fff;
            transition: all 0.2s;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(28, 229, 255, 0.25);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        /* Error Messages */
        .error-message {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary-color), #2563EB);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #1a4aad, #3b82f6);
            transform: translateY(-2px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Forgot Password Link */
        .forgot-password {
            color: var(--primary-color);
            text-align: center;
            display: block;
            margin: 1.5rem 0;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        /* Loading Overlay */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .spinner-container {
            text-align: center;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid var(--secondary-color);
            width: 40px;
            height: 40px;
            margin: 0 auto 1rem;
            animation: spin 1s linear infinite;
        }

        .spinner-text {
            color: var(--primary-color);
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .login-container {
                justify-content: flex-end;
                padding-right: 5rem;
            }

            .left-content {
                display: block;
                position: absolute;
                left: 0;
                max-width: 500px;
            }
            
            .left-content h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            
            .left-content p {
                font-size: 1.1rem;
                opacity: 0.9;
                line-height: 1.6;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .logo-text {
                font-size: 22px;
            }
        }
    </style>

    <!-- Dark Overlay with Gradient -->
    <div class="overlay"></div>

    <!-- Loading Spinner Overlay -->
    <div id="loading-overlay">
        <div class="spinner-container">
            <div class="spinner"></div>
            <p class="spinner-text">Signing in...</p>
        </div>
    </div>

    <!-- Main Login Container -->
    <div class="login-container">
        <!-- Left Content - Visible on Desktop -->
        <div class="left-content">
            <h1>Welcome to CHED-eTrack</h1>
            <p>The Commission on Higher Education's online tracking and management system. Sign in to access your account.</p>
        </div>
        
        <!-- Login Card -->
        <div class="login-card">
            <!-- Branding Section -->
            <div class="brand-section">
                <img src="{{ asset('images/Logo.png') }}" alt="CHED Logo" style="height: 55px;" class="me-3">
                <div class="logo-text">CHED-eTrack</div>
                <img src="{{ asset('images/logo2.png') }}" alt="eTrack Logo" style="height: 55px;" class="ms-3">
            </div>
            
            <!-- Login Form -->
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input id="email" class="form-control"
                               type="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               placeholder="name@example.com" />
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" class="form-control"
                               type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="Enter your password" />
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Sign In Button -->
                <button type="submit" class="submit-btn">
                    Sign In
                </button>
                
                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Script for Form Submission and Loading Overlay -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault(); // Prevent default form submission
              
                // Show loading overlay
                const loadingOverlay = document.getElementById('loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                }
              
                // Prepare form data for AJAX submission
                const formData = new FormData(loginForm);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
              
                try {
                    // Send AJAX request
                    const response = await fetch(loginForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData,
                    });
                  
                    const status = response.status;
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        data = {};
                    }
                  
                    // Hide loading overlay
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }
                  
                    if (response.ok && data.success) {
                        // Show success Toastr notification
                        toastr.success('Login successful!', 'Welcome', {
                            onHidden: function() {
                                window.location.href = data.redirect; // Redirect to dashboard or role-based route
                            }
                        });
                    } else if(status === 422) {
                        // Validation errors from server
                        const errors = data.errors ? data.errors.join('<br>') : 'Validation error.';
                        toastr.error(errors, 'Error');
                    } else {
                        const message = data.message || 'Invalid credentials. Please try again.';
                        toastr.error(message, 'Error');
                    }
                } catch (error) {
                    console.error('AJAX Error:', error);
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }
                    toastr.error('Connection error. Please check your internet connection and try again.', 'Error');
                }
            });
        }
    });
    </script>
    @include('layouts.partials.toastr')
</x-guest-layout>