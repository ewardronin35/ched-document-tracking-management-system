<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags and CSRF Token -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CHED-eTrack') }}</title>
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">

    <!-- Responsive Fonts -->
    <link rel="preload"
          href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    </noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Bootstrap CSS with Responsive Utilities -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Blade Stack for Additional Styles -->
    @stack('styles')

    <!-- Tailwind CSS and Custom CSS (if any) -->
    @vite(['resources/css/app.css', 'resources/css/sidebar.css', 'resources/js/app.js'])

    @livewireStyles

    <!-- Custom Responsive Styles -->
    <style>
        /* Responsive Loading Overlay */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            pointer-events: auto;
            transition: opacity 0.6s ease-in-out;
        }

        .spinner-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-image {
            width: 100px;
            max-width: 50vw; /* Responsive sizing */
            height: auto;
            animation: spin 3s linear infinite;
        }

        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Sidebar and Main Content */
        @media (max-width: 992px) {
            .main-content {
                width: 100%;
                margin-left: 0;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -250px; /* Hidden by default */
                width: 250px;
                height: 100%;
                transition: left 0.3s ease;
                z-index: 1050;
            }

            .sidebar.show {
                left: 0;
            }

            /* Overlay for mobile sidebar */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Ensure full responsiveness for tables and content */
        .table-responsive {
            overflow-x: auto;
        }

        /* Responsive typography */
        body {
            font-size: 16px;
        }

        @media (max-width: 576px) {
            body {
                font-size: 14px;
            }

            .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    @include('layouts.partials.toastr')

    <!-- Loading Overlay with Responsive Image -->
    <div id="loading-overlay" class="spinner-overlay">
        <img
            src="{{ asset('images/logo.png') }}"
            alt="Loading..."
            class="loading-image"
            aria-label="Loading Overlay"
            aria-busy="true"
        />
    </div>

    <!-- Responsive Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Alpine.js Responsive State Wrapper -->
    <div 
        x-data="{ 
            isSidebarOpen: false, 
            isResponsiveMenuOpen: false,
            toggleSidebar() {
                this.isSidebarOpen = !this.isSidebarOpen;
                document.getElementById('sidebar-overlay').classList.toggle('show', this.isSidebarOpen);
            }
        }" 
        class="d-flex"
    >
        <!-- Sidebar Toggle for Mobile -->
        <button 
            @click="toggleSidebar" 
            class="d-lg-none position-fixed top-0 start-0 m-3 btn btn-outline-primary z-1050"
            aria-label="Toggle Sidebar"
        >
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        @auth
            @include('layouts.sidebar')
        @endauth

        <!-- Main Content Area -->
        <div class="flex-grow-1 main-content">
            <!-- Navigation Menu -->
            @livewire('navigation-menu')

            @if (isset($header))
                <header class="bg-white shadow-sm">
                    <div class="container-fluid py-3 px-md-4">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="p-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')

    <!-- jQuery (Required for Bootstrap JS and DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <!-- User ID Script -->
    <script>
        const userId = {{ auth()->user()->id ?? 'null' }};
        console.log("Listening for notifications for user: " + userId);
    </script>

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

    <!-- Universal Loading Overlay Control with Improved Responsiveness -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                const overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                    setTimeout(() => overlay.style.display = 'none', 500);
                }
            }, 500);
        });

        // jQuery-based AJAX handling
        $(document).ajaxStart(function() {
            $('#loading-overlay').removeClass('hidden').show();
        }).ajaxStop(function() {
            $('#loading-overlay').addClass('hidden');
            setTimeout(function() {
                $('#loading-overlay').hide();
            }, 500);
        });

        // Livewire loading states
        document.addEventListener('livewire:loading-start', () => {
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

    <!-- Notification Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @auth
                let userId = {{ auth()->user()->id }};
                console.log('Listening for notifications for user:', userId);

                if (window.Echo) {
                    window.Echo.private('App.Models.User.1')
                        .listen('.document.status.updated', function (notification) {
                            console.log('Notification received:', notification);
                            updateNotificationUI(notification);
                        });

                    window.Echo.connector.pusher.bind_global(function(eventName, data) {
                        console.log("Received event:", eventName, data);
                    });
                } else {
                    console.error('Echo is not defined. Check your app.js.');
                }

                function updateNotificationUI(notification) {
                    console.log('Displaying Toastr notification:', notification);

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "5000"
                    };
                    toastr.success(notification.message, 'New Notification');
                }
            @endauth
        });
    </script>

    @include('layouts.partials.toastr')
</body>
</html>