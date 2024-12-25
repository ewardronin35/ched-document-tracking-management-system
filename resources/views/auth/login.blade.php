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

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-800" />
                    <x-input id="email" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <!-- Password Input -->
                <div>
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-800" />
                    <x-input id="password" class="block mt-1 w-full px-3 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="text-blue-600 focus:ring-blue-500 border-gray-300" />
                    <span class="ml-2 text-sm text-gray-800">{{ __('Remember me') }}</span>
                </div>

                <!-- Submit Button and Forgot Password -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:text-blue-800 underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>

            <!-- Register Redirect -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-800">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                        Register
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery (Required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
</x-guest-layout>
