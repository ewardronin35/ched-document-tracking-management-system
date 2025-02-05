<x-guest-layout>
    <!-- Internal Styles for Login Page -->
     
    <style>
        /* Body Background & Overlay */
        body {
            background: url('{{ asset('images/CHED.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            animation: fadeInBackground 1s ease-in-out;
            overflow: hidden;
            position: relative;
        }
        /* Overlay: Full Viewport Coverage */
        .overlay {
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%; 
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        /* Container: Slightly Wider */
        .container {
            z-index: 2; 
            position: relative;
            max-width: 900px;
            margin: auto;
        }
        /* Card: Increase Width / Padding */
        .login-card {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
        }
        /* Input Wrapper Styling */
        .input-wrapper {
            position: relative;
            margin-bottom: 16px;
        }
        .input-wrapper i.fa-lock,
        .input-wrapper i.fa-envelope {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black;
        }
        /* Label Styling */
        .label {
            color: black;
            display: block;
            margin-bottom: 5px;
        }
        /* Animations */
        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        /* Loading Overlay & Spinner */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #1CE5FF;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Dark Overlay -->
    <div class="overlay"></div>

    <!-- Loading Spinner Overlay -->
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Main Container (wider) -->
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div>
            <!-- Login Card (wider & more padding) -->
            <div class="card shadow login-card">
                <!-- Logo and Branding -->
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <img src="{{ asset('images/Logo.png') }}" alt="Left Logo" style="max-width: 50px;" class="me-2">
                    <div class="logo-text mx-2" style="color: #133A86; font-size: 24px; font-weight:bold;">
                        CHED-eTrack
                    </div>
                    <img src="{{ asset('images/logo2.png') }}" alt="Right Logo" style="max-width: 50px;" class="ms-2">
                </div>

                <!-- Login Form -->
                <form id="login-form" method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf
        
                    <!-- Email Field -->
                    <div class="mb-3 position-relative">
                        <label for="email" class="label">Email</label>
                        <div class="input-wrapper">
                            <i class="fa-regular fa-envelope"></i>
                            <input id="email" class="form-control ps-5"
                                   type="email" name="email" value="{{ old('email') }}"
                                   required autofocus autocomplete="username"
                                   placeholder="Enter your email" />
                        </div>
                        @error('email')
                            <div class="mt-2 text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field (no toggle icon) -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input id="password" class="form-control ps-5"
                                   type="password" name="password"
                                   required autocomplete="current-password"
                                   placeholder="Enter your password" />
                        </div>
                        @error('password')
                            <div class="mt-2 text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-center mt-4">
                        @if (Route::has('password.request'))
                            <a class="forgot-password-link" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary log-in-button">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- Optional: Show Spinner on Form Submission (non-AJAX) -->
         
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
              
                // Validate form fields (if needed) before submission
                // For brevity, validation is skipped here; you can add client-side checks
                
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
                        toastr.success('Login successful.', 'Success', {
                            onHidden: function() {
                                window.location.href = data.redirect; // Redirect to dashboard or role-based route
                            }
                        });
                    } else if(status === 422) {
                        // Validation errors from server
                        const errors = data.errors ? data.errors.join('<br>') : 'Validation error.';
                        toastr.error(errors, 'Error');
                    } else {
                        const message = data.message || 'An unexpected error occurred.';
                        toastr.error(message, 'Error');
                    }
                } catch (error) {
                    console.error('AJAX Error:', error);
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'none';
                    }
                    toastr.error('An unexpected error occurred. Please try again later.', 'Error');
                }
            });
        }
    });
    </script>
    @include('layouts.partials.toastr')

</x-guest-layout>
