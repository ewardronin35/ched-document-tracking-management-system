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
                <h1 class="text-center text-3xl font-bold text-gray-800 mt-4">Forgot Password</h1>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="mt-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            <x-validation-errors class="mt-4 text-red-600" />

            <!-- Forgot Password Form -->
            <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" class="space-y-4 mt-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-800" />
                    <x-input id="email" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end">
                    <x-button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Email Password Reset Link') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>

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
            document.getElementById('loading-spinner').classList.remove('hidden');
        });
    </script>
</x-guest-layout>
