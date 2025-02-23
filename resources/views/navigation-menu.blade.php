@php
    // Count unread notifications for dynamic tooltip and display
    $unreadCount = auth()->user()->unreadNotifications()->count();

    // Show recent notifications (e.g., limit to 5). Adjust query as needed.
    $recentNotifications = auth()->user()->notifications()
        ->latest()
        ->take(5)
        ->get();
@endphp

<div>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #133A86; border-bottom: 1px solid #ccc;">
    <div class="container-fluid">
      <!-- Hamburger Button (small screens) -->
      <button 
        class="btn btn-outline-light d-lg-none me-2" 
        type="button" 
        id="sidebarToggle" 
        aria-label="Toggle Sidebar"
      >
        <i class="fa fa-bars"></i>
      </button>

      <!-- Dynamic Page Title -->
      <span class="navbar-text text-white fs-5 me-auto">
        @if (request()->routeIs('dashboard'))
          Dashboard
        @elseif (request()->routeIs('profile.show'))
          Profile
        @elseif (request()->routeIs('admin.cav.index'))
          CAV Documents
        @elseif (request()->routeIs('admin.so_master_lists.index'))
          S.O MasterList
        @elseif (request()->routeIs('admin.outgoings.index'))
          Outgoing
        @elseif (request()->routeIs('admin.manage.users.index'))
          Manage Users
        @elseif (request()->routeIs('admin.gmail.emails'))
          Email History
        @else
          Home
        @endif
      </span>

      <!-- Right Section: Notification & Profile Icons -->
      <ul class="navbar-nav align-items-center">
        <!-- Notification Icon (bell) -->
        <li class="nav-item dropdown me-3 d-flex align-items-center">
          <a 
            class="nav-link dropdown-toggle d-flex align-items-center"
            href="#" 
            id="notificationDropdown" 
            role="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
          >
            <!-- Bell icon with ring animation and tooltip -->
            <i 
              class="fa fa-bell bell-animated"
              data-bs-toggle="tooltip"
              data-bs-placement="bottom"
              {{-- We'll set the dynamic title in a script below --}}
            ></i>

            <!-- Notification Counter (only show if we have unread notifications) -->
            @if($unreadCount > 0)
              <span id="notificationCount" class="badge bg-danger ms-1">
                {{ $unreadCount }}
              </span>
            @endif
          </a>

          <!-- Dropdown notifications -->
          <div class="dropdown-menu custom-dropdown" aria-labelledby="notificationDropdown" style="min-width: 300px;">
            <!-- Mark All as Read (only if there are unread notifications) -->
            @if($unreadCount > 0)
              <form action="#" method="POST" class="p-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger">
                  Mark All as Read
                </button>
              </form>
              <hr class="my-0">
            @endif

            @if($recentNotifications->count())
              <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                @foreach($recentNotifications as $notification)
                  <a 
                    href="{{ route('notifications.show', $notification->id) }}" 
                    class="list-group-item list-group-item-action d-flex align-items-center"
                  >
                    <!-- Icon based on the notification status -->
                    @if(isset($notification->data['status']) && $notification->data['status'] === 'Rejected')
                      <i class="fa fa-times text-danger me-2"></i>
                    @else
                      <i class="fa fa-check text-success me-2"></i>
                    @endif
                    <div>
                      <strong>{{ $notification->data['tracking_number'] ?? 'No Tracking' }}</strong>
                      <div>{{ $notification->data['message'] ?? '' }}</div>
                      <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                  </a>
                @endforeach
              </div>

              <!-- Optional link to a "View All Notifications" page -->
              <div class="text-center p-2">
                <a href="#" class="btn btn-sm btn-light">
                  View All Notifications
                </a>
              </div>
            @else
              <div class="p-2 text-center">No notifications</div>
            @endif
          </div>
        </li>

        <!-- Profile Icon/Photo -->
        <li class="nav-item dropdown">
          <a 
            class="nav-link dropdown-toggle d-flex align-items-center" 
            href="#" 
            id="profileDropdown" 
            role="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
          >
            @php 
              $roles = Auth::user()->getRoleNames(); 
            @endphp

            @if(Auth::user()->profile_photo_url)
              <img 
                class="rounded-circle me-2"
                src="{{ Auth::user()->profile_photo_url }}"
                alt="{{ Auth::user()->name }}"
                width="32"
                height="32"
              >
            @else
              <i class="fa fa-user me-2"></i>
            @endif
            <span>{{ Auth::user()->name }}</span>

            @if($roles->isNotEmpty())
              <small style="color: #fff;">({{ $roles->first() }})</small>
            @endif
          </a>
          <ul class="dropdown-menu dropdown-menu-end fade" aria-labelledby="profileDropdown">
            <li>
              <a class="dropdown-item" href="{{ route('profile.show') }}">
                Profile
              </a>
            </li>
            @if(Laravel\Jetstream\Jetstream::hasApiFeatures())
              <li>
                <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
                  API Tokens
                </a>
              </li>
            @endif
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button class="dropdown-item" type="submit">Log Out</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  
  <div id="sidebarOverlay" class="overlay" style="display: none;"></div>

  <!-- Sidebar Toggle & Overlay Script -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const toggleBtn = document.getElementById('sidebarToggle');
      const logoutForm = document.getElementById('logoutForm');
      const sidebar = document.querySelector('.sidebar');
      const overlay = document.getElementById('sidebarOverlay');

      // Toggle sidebar (for smaller screens)
      if(toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('open');
          overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
        });
      }

      // Hide sidebar if overlay clicked
      if(overlay) {
        overlay.addEventListener('click', () => {
          sidebar.classList.remove('open');
          overlay.style.display = 'none';
        });
      }

      // Logout confirmation
      if (logoutForm) {
        logoutForm.addEventListener('submit', function (e) {
          e.preventDefault(); // Prevent immediate form submission
          Swal.fire({
            title: 'Confirm Logout',
            text: 'Are you sure you want to log out?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, log me out',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: 'Successfully Logged Out',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
              });
              setTimeout(() => {
                logoutForm.submit(); // Proceed with logout after delay
              }, 1500);
            }
          });
        });
      }

      // Initialize tooltip for bell icon
      const bellIcon = document.querySelector('.fa-bell.bell-animated');
      if (bellIcon) {
        let unreadCount = parseInt('{{ $unreadCount }}', 10);
        let tooltipText = 'No new notifications';
        if (unreadCount === 1) {
          tooltipText = '1 new notification';
        } else if (unreadCount > 1) {
          tooltipText = unreadCount + ' new notifications';
        }
        bellIcon.setAttribute('title', tooltipText);

        // Use Bootstrap’s tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }
    });
  </script>

  <style>
    /* Ring animation for the bell icon */
    @keyframes ring {
      0%   { transform: rotate(0); }
      10%  { transform: rotate(15deg); }
      20%  { transform: rotate(-15deg); }
      30%  { transform: rotate(10deg); }
      40%  { transform: rotate(-10deg); }
      50%  { transform: rotate(5deg); }
      60%  { transform: rotate(-5deg); }
      70%  { transform: rotate(3deg); }
      80%  { transform: rotate(-3deg); }
      90%  { transform: rotate(2deg); }
      100% { transform: rotate(0); }
    }

    .bell-animated {
      display: inline-block;
      animation: ring 3s 0.5s ease-in-out infinite;
      transform-origin: top center;
    }

    /* Smooth dropdown fade effect */
    .custom-dropdown {
      opacity: 0;
      transform: translateY(-10px);
      transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .custom-dropdown.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Overlay for sidebar on small screens */
   
  </style>
</div>
