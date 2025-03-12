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
  <!-- Modern Navigation Bar -->
  <nav class="navbar navbar-expand-lg" style="background-color: #133A86; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
    <div class="container-fluid">
      <!-- Hamburger Button with improved styling -->
      <button 
        class="btn btn-link text-white d-lg-none me-3" 
        type="button" 
        id="sidebarToggle" 
        aria-label="Toggle Sidebar"
      >
        <i class="fa fa-bars fa-lg"></i>
      </button>

      <!-- Logo (optional) - add your logo here -->
      <a class="navbar-brand d-none d-lg-block me-4" href="{{ route('dashboard') }}">
        <!-- Replace with your actual logo or text -->
        <span class="text-white fw-bold">CHEDRO-9</span>
      </a>

      <!-- Dynamic Page Title with better typography -->
      <h5 class="mb-0 me-auto text-white fw-light">
        <i class="fa 
          @if (request()->routeIs('dashboard'))
            fa-tachometer-alt me-2
          @elseif (request()->routeIs('profile.show'))
            fa-user-circle me-2
          @elseif (request()->routeIs('admin.cav.index'))
            fa-file-alt me-2
          @elseif (request()->routeIs('admin.so_master_lists.index'))
            fa-list me-2
          @elseif (request()->routeIs('admin.outgoings.index'))
            fa-paper-plane me-2
          @elseif (request()->routeIs('admin.manage.users.index'))
            fa-users-cog me-2
          @elseif (request()->routeIs('admin.gmail.emails'))
            fa-envelope me-2
          @else
            fa-home me-2
          @endif
        "></i>
        @if (request()->routeIs('dashboard'))
          Dashboard
        @elseif (request()->routeIs('profile.show'))
          Profile
        @elseif (request()->routeIs('admin.cav.index'))
          CAV Documents
        @elseif (request()->routeIs('admin.so_master_lists.index'))
          S.O MasterList
        @elseif (request()->routeIs('admin.outgoings.index'))
          Outgoing / Incoming
        @elseif (request()->routeIs('admin.manage.users.index'))
          Manage Users
        @elseif (request()->routeIs('admin.gmail.emails'))
          Email History
        @else
          Home
        @endif
      </h5>

      <!-- Search Box (new addition) -->
      <form class="d-none d-md-flex mx-4" style="width: 30%;">
        <div class="input-group">
          <input type="search" class="form-control form-control-sm bg-light border-0" 
                placeholder="Search..." aria-label="Search">
          <button class="btn btn-sm btn-light" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </form>

      <!-- Right Section: Notification & Profile -->
      <ul class="navbar-nav align-items-center ms-auto">
        <!-- Notification Icon with enhanced styling -->
        <li class="nav-item dropdown me-3">
          <a 
            class="nav-link position-relative p-2 rounded-circle bg-white bg-opacity-10 text-white d-flex align-items-center justify-content-center"
            href="#" 
            id="notificationDropdown" 
            role="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            style="width: 36px; height: 36px;"
          >
            <i class="fa fa-bell"></i>
            
            @if($unreadCount > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                <span class="visually-hidden">unread notifications</span>
              </span>
            @endif
          </a>

          <!-- Enhanced Dropdown for notifications -->
          <div class="dropdown-menu dropdown-menu-end py-0 overflow-hidden shadow border-0" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px;">
            <!-- Header with mark all read button -->
            <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light">
              <h6 class="mb-0 fw-bold">Notifications</h6>
              @if($unreadCount > 0)
                <form action="#" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm text-primary p-0 border-0">
                    <small>Mark all as read</small>
                  </button>
                </form>
              @endif
            </div>
            
            <!-- Notification list -->
            <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
              @if($recentNotifications->count())
                @foreach($recentNotifications as $notification)
                  <a 
                    href="{{ route('notifications.show', $notification->id) }}" 
                    class="list-group-item list-group-item-action p-3 border-start-0 border-end-0 {{ $notification->read_at ? '' : 'bg-light' }}"
                  >
                    <div class="d-flex">
                      <!-- Status icon -->
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar {{ $notification->read_at ? 'bg-light' : 'bg-primary bg-opacity-10' }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                          @if(isset($notification->data['status']) && $notification->data['status'] === 'Rejected')
                            <i class="fa fa-times text-danger"></i>
                          @else
                            <i class="fa fa-check text-success"></i>
                          @endif
                        </div>
                      </div>
                      
                      <!-- Content -->
                      <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-1 text-truncate fw-bold">{{ $notification->data['tracking_number'] ?? 'No Tracking' }}</p>
                        <p class="mb-1 small text-secondary">{{ $notification->data['message'] ?? '' }}</p>
                        <p class="mb-0 text-muted" style="font-size: 0.7rem;">
                          <i class="fa fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </p>
                      </div>
                    </div>
                  </a>
                @endforeach
              @else
                <div class="p-4 text-center text-muted">
                  <i class="fa fa-bell-slash mb-3" style="font-size: 2rem;"></i>
                  <p>No notifications yet</p>
                </div>
              @endif
            </div>
            
            <!-- Footer -->
            @if($recentNotifications->count())
              <div class="text-center p-2 border-top">
                <a href="#" class="btn btn-sm btn-link text-decoration-none">
                  View all notifications
                </a>
              </div>
            @endif
          </div>
        </li>

        <!-- Divider -->
        <li class="nav-item border-start border-white border-opacity-25 h-50 mx-2"></li>

        <!-- User Profile with enhanced styling -->
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
            
            <div class="d-flex align-items-center">
              @if(Auth::user()->profile_photo_url)
                <img 
                  class="rounded-circle border border-2 border-white"
                  src="{{ Auth::user()->profile_photo_url }}"
                  alt="{{ Auth::user()->name }}"
                  width="36"
                  height="36"
                >
              @else
                <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px;">
                  <i class="fa fa-user"></i>
                </div>
              @endif
              
              <div class="ms-2 d-none d-sm-block">
                <span class="d-block text-white" style="line-height: 1.2;">{{ Auth::user()->name }}</span>
                @if($roles->isNotEmpty())
                  <small class="text-white text-opacity-75">{{ $roles->first() }}</small>
                @endif
              </div>
            </div>
          </a>
          
          <!-- Enhanced profile dropdown -->
          <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="profileDropdown" style="min-width: 200px;">
            <li class="px-3 py-2 mb-1">
              <div class="d-flex align-items-center">
                @if(Auth::user()->profile_photo_url)
                  <img 
                    class="rounded-circle"
                    src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}"
                    width="45"
                    height="45"
                  >
                @else
                  <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fa fa-user text-primary"></i>
                  </div>
                @endif
                <div class="ms-3">
                  <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                  <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
              </div>
            </li>
            <li><hr class="dropdown-divider my-1"></li>
            
            <li>
              <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                <i class="fa fa-user-circle me-2 text-secondary"></i> My Profile
              </a>
            </li>
            
            <li>
              <a class="dropdown-item py-2" href="#">
                <i class="fa fa-cog me-2 text-secondary"></i> Settings
              </a>
            </li>
            
            @if(Laravel\Jetstream\Jetstream::hasApiFeatures())
              <li>
                <a class="dropdown-item py-2" href="{{ route('api-tokens.index') }}">
                  <i class="fa fa-key me-2 text-secondary"></i> API Tokens
                </a>
              </li>
            @endif
            
            <li><hr class="dropdown-divider my-1"></li>
            
            <li>
              <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button class="dropdown-item text-danger py-2" type="submit">
                  <i class="fa fa-sign-out-alt me-2"></i> Log Out
                </button>
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
      
      // Initialize all dropdowns with animation
      const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
      const dropdownList = [...dropdownElementList].map(dropdownToggleEl => {
        const dropdown = new bootstrap.Dropdown(dropdownToggleEl);
        
        // Add animation on show
        dropdownToggleEl.addEventListener('show.bs.dropdown', function () {
          const dropdownMenu = this.nextElementSibling;
          dropdownMenu.classList.add('animate__animated', 'animate__fadeIn', 'animate__faster');
        });
        
        return dropdown;
      });

      // Toggle sidebar (for smaller screens)
      if(toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
          sidebar.classList.toggle('open');
          overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
          document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        });
      }

      // Hide sidebar if overlay clicked
      if(overlay) {
        overlay.addEventListener('click', () => {
          sidebar.classList.remove('open');
          overlay.style.display = 'none';
          document.body.style.overflow = '';
        });
      }

      // Improved logout confirmation
      if (logoutForm) {
        logoutForm.addEventListener('submit', function (e) {
          e.preventDefault(); // Prevent immediate form submission
          Swal.fire({
            title: 'Logging Out?',
            text: 'You will be signed out of your account',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out',
            cancelButtonText: 'Cancel',
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: 'Successfully Logged Out',
                text: 'Redirecting you to login page...',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                customClass: {
                  popup: 'animate__animated animate__fadeOutUp'
                }
              });
              setTimeout(() => {
                logoutForm.submit(); // Proceed with logout after delay
              }, 1500);
            }
          });
        });
      }
    });
  </script>

  <style>
    /* Modern styling for navigation */  
    .navbar {
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
    }
    
    .navbar-brand {
      font-size: 1.25rem;
    }
    
    /* Custom styling for dropdown menus */
    .dropdown-menu {
      border-radius: 0.5rem;
      animation-duration: 0.3s;
    }
    
    .dropdown-item {
      border-radius: 0.25rem;
      margin: 0 0.5rem;
      width: auto;
      transition: all 0.2s;
    }
    
    .dropdown-item:hover {
      background-color: rgba(13, 110, 253, 0.1);
    }
    
    /* Notification styles */
    .notification-indicator {
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(25%, -25%);
    }
    
    /* Bell animation (more subtle) */
    @keyframes gentleRing {
      0%   { transform: rotate(0); }
      10%  { transform: rotate(10deg); }
      20%  { transform: rotate(-10deg); }
      30%  { transform: rotate(6deg); }
      40%  { transform: rotate(-6deg); }
      50%  { transform: rotate(3deg); }
      60%  { transform: rotate(-3deg); }
      70%  { transform: rotate(1deg); }
      80%  { transform: rotate(-1deg); }
      100% { transform: rotate(0); }
    }

    .fa-bell {
      display: inline-block;
      transform-origin: top center;
    }
    
    /* Only animate when there are unread notifications */
    @if($unreadCount > 0)
      .fa-bell {
        animation: gentleRing 3s 1s ease-in-out infinite;
      }
    @endif
    
    /* Improved overlay for sidebar */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      transition: all 0.3s;
    }
    
    /* Sidebar transition */
    .sidebar {
      transition: transform 0.3s ease;
    }
    
    /* Avatar placeholder */
    .avatar {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Profile photo hover effect */
    .dropdown-toggle img {
      transition: transform 0.3s ease;
    }
    
    .dropdown-toggle:hover img {
      transform: scale(1.05);
    }
    
    /* Optional: Add a subtle animation to notification badge */
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    
    .badge {
      animation: pulse 2s infinite;
    }
  </style>
</div>