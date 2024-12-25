<!-- resources/views/layouts/sidebar.blade.php -->

<!-- Sidebar Overlay for Mobile Devices -->
<div 
    x-show="isSidebarOpen" 
    @click="isSidebarOpen = false" 
    class="fixed inset-0 bg-black opacity-50 z-40 d-md-none" 
    x-transition
    aria-hidden="true">
</div>

<div 
    id="sidebar" 
    class="sidebar text-white d-flex flex-column bg-[#133A86] fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto 
           d-none d-md-flex transition-transform duration-300 ease-in-out"
    :class="{'show': isSidebarOpen}"
    @click.away="isSidebarOpen = false" >

    <!-- Logo and Title -->
    <div class="p-4 d-flex align-items-center">
        <!-- Main Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2" width="40" height="40" loading="lazy">

        <!-- Sidebar Title -->
        <span class="fs-5 fw-semibold me-2">CDTMS</span>

        <!-- Secondary Logo -->
        <img src="{{ asset('images/logo2.png') }}" alt="Logo2" class="ms-2" width="50" height="50" loading="lazy">
    </div>

    <!-- Navigation Links -->
    <ul class="list-unstyled px-3 flex-grow-1">
        <!-- Home Link -->
        <li class="mb-2">
            <a href="{{ route('dashboard') }}" 
               class="d-flex align-items-center text-white text-decoration-none" 
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Home"
               @click="isSidebarOpen = false">
                <!-- Home SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" 
                     class="bi bi-house-fill me-2" viewBox="0 0 16 16" aria-label="Home Icon">
                    <path fill-rule="evenodd" d="M8 3.293l6 6V13.5a.5.5 0 0 1-.5.5h-4v-4H6v4H2.5a.5.5
                    0 0 1-.5-.5V9.293l6-6zm5 6.707V13h-2v-3.293l-4 4V13h-2v-3.293l-4-4V13H1v-3.293l7-7 
                    7 7z"/>
                </svg>
                Home
            </a>
        </li>

        <!-- Dashboard Link with Submenu -->
        <li class="mb-2">
            <!-- Local Alpine.js State for Submenu -->
            <div x-data="{ openSubmenu: false }">
                <a href="javascript:void(0)" 
                   @click="openSubmenu = !openSubmenu" 
                   class="d-flex align-items-center text-white text-decoration-none">
                    <!-- Dashboard SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" 
                         class="bi bi-speedometer2 me-2" viewBox="0 0 16 16" aria-label="Dashboard Icon">
                        <path d="M8 4a.5.5 0 0 1 .5.5V8a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4z"/>
                        <path fill-rule="evenodd"
                              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-6.916 6H1a.5.5
                              0 0 1 0 1h.084A6.978 6.978 
                              0 0 1 8 1a6.978 6.978 
                              0 0 1 6.916 5H15a.5.5 
                              0 0 1 0 1h-.084A7 7 
                              0 0 0 8 1z"/>
                    </svg>
                    Dashboard
                    <!-- Submenu Indicator Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                         class="bi bi-chevron-down ms-auto submenu-indicator transition-transform duration-300" 
                         viewBox="0 0 16 16" 
                         :class="{'rotate-90': openSubmenu }">
                        <path fill-rule="evenodd" 
                              d="M1.646 4.646a.5.5 
                              0 0 1 .708 0L8 
                              10.293l5.646-5.647a.5.5 
                              0 0 1 .708.708l-6 6a.5.5 
                              0 0 1-.708 0l-6-6a.5.5 
                              0 0 1 0-.708z"/>
                    </svg>
                </a>
                <ul class="list-unstyled ps-4" x-show="openSubmenu" x-transition>
                    <li class="mb-1">
                        <a href="#" 
                           class="d-flex align-items-center text-white text-decoration-none" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="right" 
                           title="Overview"
                           @click="isSidebarOpen = false">
                            <!-- Overview SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                 class="bi bi-circle-fill me-2" viewBox="0 0 16 16" aria-label="Overview Icon">
                                <circle cx="8" cy="8" r="8"/>
                            </svg>
                            Overview
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" 
                           class="d-flex align-items-center text-white text-decoration-none" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="right" 
                           title="Analytics"
                           @click="isSidebarOpen = false">
                            <!-- Analytics SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                 class="bi bi-graph-up-arrow me-2" viewBox="0 0 16 16" aria-label="Analytics Icon">
                                <path fill-rule="evenodd"
                                      d="M10.854 7.146a.5.5 
                                      0 1 1 .708.708l-3 3a.5.5 
                                      0 0 1-.708-.708l1.5-1.5-2.646-2.647a.5.5 
                                      0 1 1 .708-.708l3 3z"/>
                            </svg>
                            Analytics
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Manage Users Link (Visible Only to Admins) -->
        @role('admin')
        <li class="mb-2">
            <a href="{{ route('manage.users.index') }}" 
               class="d-flex align-items-center text-white text-decoration-none" 
               data-bs-toggle="tooltip" 
               data-bs-placement="right" 
               title="Manage Users"
               @click="isSidebarOpen = false">
                <!-- Manage Users SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                     class="bi bi-people-fill me-2" viewBox="0 0 16 16" aria-label="Manage Users Icon">
                    <path d="M5.216 14A2.238 2.238 
                    0 0 1 3 12.28c0-.75.333-1.47.919-1.936C4.446 
                    9.073 5.111 8 7 8s2.554 1.073 3.081 
                    2.344A2.238 2.238 0 0 1 13 12.28a2.238 
                    2.238 0 0 1-2.216 1z"/>
                    <path fill-rule="evenodd" 
                          d="M10.5 6a2 2 0 
                          1 1-4 0 2 2 0 
                          0 1 4 0z"/>
                </svg>
                Manage Users
            </a>
        </li>
        @endrole

        <!-- Settings Link with Submenu -->
        <li class="mb-2">
            <!-- Local Alpine.js State for Submenu -->
            <div x-data="{ openSubmenu: false }">
                <a href="javascript:void(0)" 
                   @click="openSubmenu = !openSubmenu" 
                   class="d-flex align-items-center text-white text-decoration-none">
                    <!-- Settings SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" 
                         class="bi bi-gear-fill me-2" viewBox="0 0 16 16" aria-label="Settings Icon">
                        <path d="M9.405 1.05c-.413-.1-.85.163-1 
                        .575l-.538 1.69c-.05.157-.176.273-.34.31l-1.8.317c-.286.05-.4.386-.204.597l1.277 
                        1.277c.175.175.275.414.275.667v1.8c0 
                        .286.2.536.482.607l1.69.538c.413.15.675.588.575 
                        1l-.85 3.18c-.1.413-.588.675-1 
                        .575l-1.69-.538a1.753 1.753 
                        0 0 1-1.057.614l-.317 1.8c-.05.286.212.5.482.5h1.8c.286 
                        0 .536-.2.607-.482l.538-1.69c.15-.413.588-.675 
                        1-.575l3.18.85c.413.1.675-.588.575-1l-.85-3.18a1.753 
                        1.753 0 0 1 .614-1.057l1.8-.317c.286-.05.5-.212.5-.482v-1.8c0-.286-.2-.536-.482-.607l-1.69-.538a1.753 
                        1.753 0 0 1-.614-1.057l.317-1.8zM8 
                        10.5a2.5 2.5 
                        0 1 0 0-5 2.5 2.5 
                        0 0 0 0 5z"/>
                    </svg>
                    Settings
                    <!-- Submenu Indicator Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                         class="bi bi-chevron-down ms-auto submenu-indicator transition-transform duration-300" 
                         viewBox="0 0 16 16" 
                         :class="{'rotate-90': openSubmenu }">
                        <path fill-rule="evenodd" 
                              d="M1.646 4.646a.5.5 
                              0 0 1 .708 0L8 
                              10.293l5.646-5.647a.5.5 
                              0 0 1 .708.708l-6 6a.5.5 
                              0 0 1-.708 0l-6-6a.5.5 
                              0 0 1 0-.708z"/>
                    </svg>
                </a>
                <ul class="list-unstyled ps-4" x-show="openSubmenu" x-transition>
                    <li class="mb-1">
                        <a href="#" 
                           class="d-flex align-items-center text-white text-decoration-none" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="right" 
                           title="Profile"
                           @click="isSidebarOpen = false">
                            <!-- Profile SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                 class="bi bi-person-fill me-2" viewBox="0 0 16 16" aria-label="Profile Icon">
                                <path d="M3 14s-1 0-1-1 1-4 
                                7-4 7 3 7 4-1 1-1 1H3zm5-6a3 3 
                                0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>
                            Profile
                        </a>
                    </li>
                    <!-- Add more submenu items as needed -->
                </ul>
            </div>
        </li>
    </ul>

    <!-- Logout Button -->
    <div class="mt-auto p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger w-100 d-flex align-items-center">
                <!-- Logout Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                     class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16" aria-label="Logout Icon">
                    <path fill-rule="evenodd"
                          d="M6.646 3.646a.5.5 
                          0 0 1 0 .708L4.707 6h7.586a.5.5 
                          0 0 1 0 1H4.707l1.939 1.646a.5.5 
                          0 1 1-.708.708l-2.5-2.5a.499.499 
                          0 0 1 0-.707l2.5-2.5a.5.5 
                          0 0 1 .708 0z"/>
                    <path fill-rule="evenodd"
                          d="M13 8a5 5 
                          0 1 1-8-4.546V2.5a.5.5 
                          0 0 1 1 0v.954A4 4 0 1 0 
                          4 8a5 5 0 0 1 9 0z"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
