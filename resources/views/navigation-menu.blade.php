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
            <i class="fa fa-bell bell-animated"></i>
            <!-- Optional Notification Counter -->
            <span id="notificationCount" class="badge bg-danger ms-1">
    {{ auth()->user()->unreadNotifications()->count() }}
</span>
        </a>
        <!-- Animated dropdown: include your notifications partial -->
        @include('layouts.partials.notifications')
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
              <!-- User photo -->
              <img 
                class="rounded-circle me-2" 
                src="{{ Auth::user()->profile_photo_url }}" 
                alt="{{ Auth::user()->name }}" 
                width="32" 
                height="32"
              >
            @else
              <!-- Fallback icon -->
              <i class="fa fa-user me-2"></i>
            @endif
            <span>{{ Auth::user()->name }}</span>
            
            @if($roles->isNotEmpty())
              <small style="color: #fff;">({{ $roles->first() }})</small>
            @endif
          </a>
          <!-- Animated dropdown -->
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
    const overlay = document.getElementById('sidebarOverlay'); // if you want to show/hide the overlay
    toggleBtn.addEventListener('click', () => {
      // 1) Toggle the .open class
      sidebar.classList.toggle('open');

      // 2) Show the overlay if the sidebar is open
      if (sidebar.classList.contains('open')) {
        overlay.style.display = 'block';
      } else {
        overlay.style.display = 'none';
      }
    });

    // Hide the sidebar and overlay if the user clicks on the overlay
    overlay.addEventListener('click', () => {
      sidebar.classList.remove('open');
      overlay.style.display = 'none';
    });

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

      // Smooth dropdown
    
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
  </style>
</div>
