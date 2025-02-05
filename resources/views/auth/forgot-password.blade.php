<x-guest-layout>
    <!-- Main Container with Background Image -->
    <div class="min-vh-100 d-flex align-items-center justify-content-center position-relative bg-dark">
        <!-- Background Image -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-cover bg-center" 
     style="background-image: url('{{ asset('images/CHED.jpg') }}'); background-repeat: no-repeat; background-size: cover; opacity: 0.6;"></div>

        <!-- Spinner (Hidden by Default) -->
        <div id="loading-spinner" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-75">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status"></div>
        </div>

        <!-- Authentication Card -->
        <div class="card shadow-lg border-0 rounded-lg p-4 bg-light position-relative z-2" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/Logo.png') }}" alt="Authentication Card Logo" class="img-fluid mb-3" style="max-height: 80px;">
                    <h1 class="h4 fw-bold text-dark">Forgot Password</h1>
                </div>

                <p class="text-muted small">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>

                <!-- Status Message -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Validation Errors -->
                <x-validation-errors class="alert alert-danger mt-3" />

                <!-- Forgot Password Form -->
                <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                               class="form-control" placeholder="Enter your email">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr CSS and JS (For Notifications) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toastr Options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showDuration": "300",
                "hideDuration": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Display Toastr Notifications
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if(session('status'))
                toastr.info("{{ session('status') }}");
            @endif

            // Validation Errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });

        // Show Spinner on Form Submission
        document.getElementById('forgot-password-form').addEventListener('submit', function () {
            document.getElementById('loading-spinner').classList.remove('d-none');
        });
    </script>
</x-guest-layout>
