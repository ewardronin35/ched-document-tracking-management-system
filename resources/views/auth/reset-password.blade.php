<!-- resources/views/auth/passwords/reset.blade.php -->

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
                    <h1 class="h4 fw-bold text-dark">Reset Your Password</h1>
                </div>

                <!-- Validation Errors -->
                <x-validation-errors class="alert alert-danger mt-3" />

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Reset Password Form -->
                <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <!-- Hidden Token Field -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Hidden Email Field -->
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Email Field (Read-Only) -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $email) }}" 
                               class="form-control" readonly>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" required 
                               class="form-control" autocomplete="new-password">
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required 
                               class="form-control" autocomplete="new-password">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };

            // Display session status as Toastr notification
            @if (session('status'))
                toastr.success("{{ session('status') }}");
            @endif

            // Handle form submission to show spinner
            document.getElementById('reset-password-form').addEventListener('submit', function () {
                document.getElementById('loading-spinner').classList.remove('d-none');
            });

            // Optionally, handle validation errors with Toastr
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
</x-guest-layout>
