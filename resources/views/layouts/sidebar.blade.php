@php
  $roleCollection = auth()->user()->getRoleNames();
  $role = $roleCollection->isNotEmpty() ? strtolower($roleCollection->first()) : null;
@endphp

<!-- Include Bootstrap & Font Awesome & Google Fonts -->
<link 
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
  rel="stylesheet"
/>
<link 
  rel="stylesheet" 
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
  crossorigin="anonymous" 
  referrerpolicy="no-referrer"
/>
<!-- Google Font: Poppins -->
<link 
  rel="stylesheet" 
  href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" 
/>

<style>
  :root {
  --primary-color: #133A86;      /* Blue */
  --secondary-color: #DA042A;    /* Red */
  --accent-color:   #FEE71B;     /* Yellow */
  --white-color:    #ffffff;
  --black-color:    #000000;

  /* Additional custom values */
  --font-family: 'Poppins', sans-serif;  
  --sidebar-hover-bg: #0F2D6B;  /* Slightly darker than primary */
  --sidebar-active-bg: #DA042A; /* Red highlight for active link */
  --sidebar-border: #0B2A5D;    /* Subtle border color for separation */
  }

  body, .sidebar, .nav-link, button, .dropdown-item {
  font-family: var(--font-family);
  }

  /* The main sidebar container */
  .sidebar {
    background-color: var(--primary-color);
    width: 260px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease-in-out; /* Slide animation */
    transform: translateX(0);
    box-shadow: 2px 0 6px rgba(0,0,0,0.15);
  }

  /* On screens under 992px (Bootstrap "lg"), hide the sidebar off-canvas by default */
  @media (max-width: 991.98px) {
    .sidebar {
      transform: translateX(-260px);
    }
    .sidebar.open {
      transform: translateX(0);
    }
  }

  /* Overlay for mobile when sidebar is open */
  #sidebarOverlay {
    display: none;
    position: fixed;
    inset: 0;
    background-color: rgba(0,0,0,0.5);
  }

  /* Sidebar Header */
  .sidebar-header {
    border-bottom: 1px solid var(--sidebar-border);
  }
  .sidebar-header img {
    border-radius: 4px;
  }

  /* Navigation Links */
  .sidebar .nav-link {
    color: var(--white-color);
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    transition: background-color 0.2s ease, color 0.2s ease;
    border-radius: 0;
  }
  .sidebar .nav-link i {
    font-size: 1.1rem;
    margin-right: 0.5rem;
    /* Initial transform for subtle icon scale or rotation if needed */
    transition: transform 0.2s ease;
  }

  /* Hover/Focus states */
  .sidebar .nav-link:hover,
  .sidebar .nav-link:focus {
    background-color: var(--sidebar-hover-bg);
    color: var(--white-color);
    text-decoration: none;
  }

  /* Animated icon on hover or click - scale or rotate */
  .sidebar .nav-link:active i,
  .sidebar .nav-link:focus i {
    transform: scale(1.2) rotate(15deg);
  }

  /* Active link highlight */
  .sidebar .nav-link.active {
    background-color: var(--sidebar-active-bg) !important;
    border-radius: 0.25rem;
  }

  /* Logout button styling */
  .sidebar-logout-btn {
    background-color: var(--secondary-color);
    border: none;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  .sidebar-logout-btn:hover {
    background-color: #C30E23; /* Slightly darker red on hover */
    transform: scale(1.02);    /* Slight scale-up for feedback */
  }
</style>

<div class="sidebar d-flex flex-column text-white">
  <!-- Header -->
  <div class="p-3 d-flex align-items-center justify-content-between sidebar-header">
    <img 
      src="{{ asset('images/logo.png') }}" 
      alt="CHED Logo" 
      width="50" 
      height="50" 
      class="me-2"
    >
    <span class="fs-5 fw-bold text-center mx-2">CHED - eTrack</span>
    <img 
      src="{{ asset('images/logo2.png') }}" 
      alt="Secondary Logo" 
      width="50" 
      height="50" 
      class="ms-2"
    >
  </div>

  <!-- Navigation Links -->
  <nav class="flex-grow-1 overflow-auto">
    <ul class="nav flex-column py-3">
      
      <!-- Home -->
      <li class="nav-item mb-1">
        <a 
          href="{{ route($role.'.dashboard') }}" 
          class="nav-link {{ request()->routeIs($role.'.dashboard') ? 'active' : '' }}"
          data-bs-toggle="tooltip" 
          data-bs-placement="right" 
          title="Home"
        >
          <i class="fa fa-home"></i> Home
        </a>
      </li>
      <li class="nav-item mb-1">
        <a 
          href="{{ route($role.'.documents.index') }}" 
          class="nav-link {{ request()->routeIs($role.'.documents.*') ? 'active' : '' }}"
          data-bs-toggle="tooltip" 
          data-bs-placement="right" 
          title="Manage Documents"
        >
          <i class="fa fa-folder-open"></i> Manage Documents
        </a>
      </li>
      @if(in_array($role, ['admin', 'regionaldirector', 'records']))

      <li class="nav-item mb-1">
        <a 
          href="{{ route($role.'.outgoings.index') }}"
          class="nav-link {{ request()->routeIs($role.'.outgoings.*') ? 'active' : '' }}"
          data-bs-toggle="tooltip" 
          data-bs-placement="right" 
          title="Outgoing & Incoming"
        >
          <i class="fa fa-arrow-up-right-from-square"></i> Outgoing & Incoming 
        </a>
      </li>
     @endif
      <!-- CAV Documents -->
      @if(in_array($role, ['admin', 'regionaldirector', 'records']))

      <li class="nav-item mb-1">
        <a 
          href="{{ route($role.'.cav.index') }}" 
          class="nav-link {{ request()->routeIs($role.'.cav.index') ? 'active' : '' }}"
          data-bs-toggle="tooltip" 
          data-bs-placement="right" 
          title="CAV Documents"
        >
          <i class="fa fa-file-alt"></i> CAV Documents
        </a>
      </li>
@endif
      <!-- S.O MasterList -->
       @if(in_array($role, ['admin', 'regionaldirector', 'records']))
      <li class="nav-item mb-1">
        <a 
          href="{{ route($role.'.so_master_lists.index') }}" 
          class="nav-link {{ request()->routeIs($role.'.so_master_lists.*') ? 'active' : '' }}"
          data-bs-toggle="tooltip" 
          data-bs-placement="right" 
          title="S.O MasterList"
        >
          <i class="fa fa-list"></i> S.O MasterList
        </a>
      </li>
@endif
  <!-- Outgoing -->
     
  <!-- Admin-Only Links -->
  @role('admin')
  
  <li class="nav-item mb-1">
    <a 
      href="{{ route('admin.manage.users.index') }}" 
      class="nav-link {{ request()->routeIs('admin.manage.users.*') ? 'active' : '' }}"
      data-bs-toggle="tooltip" 
      data-bs-placement="right" 
      title="Manage Users"
    >
      <i class="fa fa-users"></i> Manage Users
    </a>
  </li>
  @endrole

  <!-- Communication Tracking -->
     
  <!-- Email History -->
  <li class="nav-item mb-1">
    <a 
      href="{{ route('admin.gmail.emails') }}" 
      class="nav-link {{ request()->routeIs('admin.gmail.*') ? 'active' : '' }}"
      data-bs-toggle="tooltip" 
      data-bs-placement="right" 
      title="CHED-eMail"
    >
      <i class="fa fa-envelope"></i> CHED-eMail
    </a>
  </li>

  <!-- Profile -->
  <li class="nav-item mb-1">
    <a 
      href="{{ route('profile.show') }}" 
      class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
      data-bs-toggle="tooltip" 
      data-bs-placement="right" 
      title="Profile"
    >
      <i class="fa fa-user"></i> Profile
    </a>
  </li>
    </ul>
  </nav>

  <!-- Redesigned Logout Section -->
  <div class="p-3 border-top">
    <form method="POST" action="{{ route('logout') }}">
  @csrf
  <button 
    type="submit" 
    class="btn w-100 d-flex align-items-center justify-content-center"
    style="background: linear-gradient(45deg, #DA042A, #C30E23); color: #fff; border: none; transition: transform 0.2s ease;"
    onmouseover="this.style.transform='scale(1.05)'"
    onmouseout="this.style.transform='scale(1)'"
  >
    <i class="fa fa-sign-out-alt me-2"></i> Sign Out
  </button>
    </form>
  </div>
</div>

<!-- Overlay for mobile -->
<div id="sidebarOverlay"></div>

<!-- Initialize Bootstrap Tooltips & Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize Tooltips
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>
