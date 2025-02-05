<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags and CSRF Token -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CHED-eTrack') }}</title>
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">

    <!-- Preloaded Fonts -->
    <link rel="preload"
          href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    </noscript>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Blade Stack for Additional Styles -->
    @stack('styles')

    <!-- Tailwind CSS and Custom CSS (if any) -->
    @vite(['resources/css/app.css', 'resources/css/sidebar.css', 'resources/js/app.js'])

    @livewireStyles

    <!-- Custom Styles for the Loading Overlay -->
    <style>
        /* 
         * Loading Overlay (Image-Based)
         * Displayed by default, then hidden after a short delay or on AJAX completion.
        */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Semi-transparent white background */
            background-color: rgba(255, 255, 255, 0.8);

            /* Center content (the loading image) both vertically and horizontally */
            display: flex;
            align-items: center;
            justify-content: center;

            /* Ensure it's above everything else */
            z-index: 9999;

            /* 
             * For smooth fading transitions:
             * 1. Start fully opaque
             * 2. We'll toggle opacity to 0 (hidden) or 1 (visible)
             */
            opacity: 1;
            pointer-events: auto;
            transition: opacity 0.6s ease-in-out;
        }

        /* Hidden state for fluid fade-out */
        .spinner-overlay.hidden {
            opacity: 0;               /* Gradually fade out via transition */
            pointer-events: none;     /* Prevent clicks or interactions when hidden */
        }

        /* Style the loading image if you'd like a fixed size */
        .loading-image {
            width: 100px;
            height: auto; /* Keep aspect ratio, or set a fixed height if needed */
        }

      
        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-image {
            animation: spin 3s linear infinite; 
        }
        */
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Loading Overlay with an Image -->
    <div id="loading-overlay" class="spinner-overlay">
        <img
            src="{{ asset('images/logo.png') }}"
            alt="Loading..."
            class="loading-image"
            aria-label="Loading Overlay"
            aria-busy="true"
        />
       
    </div>

    <x-banner />

    <!-- Alpine.js Shared State Wrapper -->
    <div x-data="{ isSidebarOpen: false, isResponsiveMenuOpen: false }" class="d-flex">
        <!-- 1) The Overlay (mobile only) -->
        <div 
            x-show="isSidebarOpen" 
            @click="isSidebarOpen = false" 
            class="fixed inset-0 bg-black opacity-50 z-40" 
            x-transition
        ></div>

        <!-- Sidebar -->
        @auth
            @include('layouts.sidebar')
        @endauth

        <!-- Main Content -->
        <div class="flex-grow-1 main-content">
            <!-- Navigation Menu -->
            @livewire('navigation-menu')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')

    <!-- jQuery (Required for Bootstrap JS and DataTables) -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <!-- Bootstrap JS Bundle (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Blade Stack for Additional Scripts -->
    @stack('scripts')

    @livewireScripts

    <!-- Initialize Bootstrap Tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
        });
    </script>

    <!-- Universal Loading Overlay Control with Fade and Delay -->
    <script>
        /**
         * We keep the overlay visible by default.
         * 1. Hide overlay ~1 second after window finishes loading (images, scripts, etc.).
         * 2. For jQuery AJAX or Livewire events, show and then fade out again.
         */

        window.addEventListener('load', function() {
            // Use a small delay (e.g., 1000 ms) to show the logo briefly, then fade out.
            setTimeout(function() {
                const overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    overlay.classList.add('hidden'); // triggers CSS transition
                    // After the transition finishes (~0.6s), we can fully hide or remove the overlay if we want
                    setTimeout(() => overlay.style.display = 'none', 500);
                }
            }, 500);
        });

        // jQuery-based: Show overlay on AJAX start, hide on AJAX stop
        $(document).ajaxStart(function() {
            // Reinstate overlay if previously hidden
            $('#loading-overlay').removeClass('hidden').show();
        }).ajaxStop(function() {
            // Fade out again
            $('#loading-overlay').addClass('hidden');
            setTimeout(function() {
                $('#loading-overlay').hide();
            }, 500);
        });

        // Livewire-based: Show overlay on loading start, hide on loading stop
        document.addEventListener('livewire:loading-start', () => {
            // Reinstate overlay if previously hidden
            let overlay = document.getElementById('loading-overlay');
            overlay.classList.remove('hidden');
            overlay.style.display = 'flex';
        });

        document.addEventListener('livewire:loading-stop', () => {
            let overlay = document.getElementById('loading-overlay');
            overlay.classList.add('hidden');
            setTimeout(function() {
                overlay.style.display = 'none';
            }, 500);
        });
    </script>

    <!-- Include Toastr Partial (if applicable) -->
    @include('layouts.partials.toastr')
</body>
</html>