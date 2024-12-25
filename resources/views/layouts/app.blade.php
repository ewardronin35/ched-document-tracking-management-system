<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags and CSRF Token -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CDTMS') }}</title>
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS and Custom CSS -->
    @vite(['resources/css/app.css', 'resources/css/sidebar.css', 'resources/js/app.js'])

    @livewireStyles

    <!-- Alpine.js (Ensure it's included only once via CDN) -->

</head>

<body class="font-sans antialiased">
    <x-banner />

    <!-- Alpine.js Shared State Wrapper -->
    <div x-data="{ isSidebarOpen: false, isResponsiveMenuOpen: false }" class="d-flex">
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts
    <script>window.Livewire = { skipAlpine: true };</script>
    <!-- Initialize Bootstrap Tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            isOpen: false,
        });
    });
</script>

    <!-- Include Toastr Partial -->
    @include('layouts.partials.toastr')
</body>
</html>
