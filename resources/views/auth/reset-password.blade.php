<!-- resources/views/auth/passwords/reset.blade.php -->

<x-guest-layout>
    <!-- Main Container with Background Image -->
    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/CHED.jpg') }}');">
        
        <!-- Overlay to Darken Background for Readability -->
        <div class="absolute inset-0 bg-black opacity-60"></div>

        <!-- Spinner (Hidden by Default) -->
        <div id="loading-spinner" class="hidden fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
        </div>

        <!-- Authentication Card -->
        <div class="relative z-10 w-full max-w-md p-8 bg-white rounded-xl shadow-lg border border-gray-300">
            <div class="flex flex-col items-center">
                <img src="{{ asset('images/Logo.png') }}" alt="Authentication Card Logo" class="h-20 w-auto">
                <h1 class="text-center text-3xl font-bold text-gray-800 mt-4">Reset Your Password</h1>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4 text-red-600" />

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Reset Password Form -->
            <form id="reset-password-form" method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <!-- Hidden Token Field -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Hidden Email Field -->
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Email Field (Read-Only) -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-800" />
                    <x-input id="email" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email" :value="old('email', $email)" required readonly />
                </div>

                <!-- Password Field -->
                <div>
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-800" />
                    <x-input id="password" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-800" />
                    <x-input id="password_confirmation" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end">
                    <x-button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Reset Password') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery (Required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                // Add other options as needed
            };

            // Display session status as Toastr notification
            @if (session('status'))
                toastr.success("{{ session('status') }}");
            @endif

            // Handle form submission to show spinner
            $('#reset-password-form').on('submit', function() {
                $('#loading-spinner').removeClass('hidden');
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
