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
                <h1 class="text-center text-3xl font-bold text-gray-800 mt-4">Welcome to CDTMS</h1>
            </div>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4 text-red-600" />

            <!-- Register Form -->
            <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name Input -->
                <div>
                    <x-label for="name" value="{{ __('Name') }}" class="text-gray-800" />
                    <x-input id="name" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <!-- Email Input -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-800" />
                    <x-input id="email" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <!-- Password Input -->
                <div>
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-800" />
                    <x-input id="password" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-800" />
                    <x-input id="password_confirmation" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <!-- Terms and Privacy Policy -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div>
                        <x-label for="terms" class="text-gray-800">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required class="text-blue-600 focus:ring-blue-500 border-gray-300" />
                                <span class="ml-2 text-sm text-gray-800">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-blue-600 hover:text-blue-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-blue-600 hover:text-blue-800">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </div>
                        </x-label>
                    </div>
                @endif

                <!-- Submit Button and Login Redirect -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                        {{ __('Already registered?') }}
                    </a>

                    <x-button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Register') }}
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
        document.getElementById('register-form').addEventListener('submit', function () {
            document.getElementById('loading-spinner').classList.remove('hidden');
        });
    </script>
</x-guest-layout>
