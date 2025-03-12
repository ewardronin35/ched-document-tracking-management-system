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
  href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" 
/>

<style>
  :root {
    /* Original Color Scheme - Maintained */
    --primary-color: #133A86;      /* Blue */
    --secondary-color: #DA042A;    /* Red */
    --accent-color:   #FEE71B;     /* Yellow */
    --white-color:    #ffffff;
    --black-color:    #000000;
    
    /* Additional variables for improved design */
    --primary-dark: #0F2D6B;
    --sidebar-width: 260px;
    --font-family: 'Poppins', sans-serif;
    --transition-speed: 0.3s;
    --border-radius: 0.25rem;
  }

  body {
    font-family: var(--font-family);
  }

  /* The main sidebar container - keep width consistent with original */
  .sidebar {
    background-color: var(--primary-color);
    width: var(--sidebar-width);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform var(--transition-speed) ease-in-out;
    transform: translateX(0);
    box-shadow: 2px 0 10px rgba(0,0,0,0.2);
    z-index: 1000;
    display: flex;
    flex-direction: column;
  }

  /* On screens under 992px, hide the sidebar off-canvas by default */
  @media (max-width: 991.98px) {
    .sidebar {
      transform: translateX(-260px);
    }
    .sidebar.open {
      transform: translateX(0);
    }
  }

  /* Sidebar Header with improved alignment */
  .sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: rgba(0,0,0,0.1);
  }
  
  .sidebar-header img {
    width: 45px;
    height: 45px;
    object-fit: contain;
    border-radius: 4px;
  }
  
  .sidebar-title {
    color: var(--white-color);
    font-weight: 600;
    font-size: 1.2rem;
    margin: 0 0.75rem;
  }

  /* Improved navigation area */
  .sidebar-nav {
    flex-grow: 1;
    overflow-y: auto;
    padding: 1rem 0.5rem;
  }
  
  .sidebar-nav::-webkit-scrollbar {
    width: 5px;
  }
  
  .sidebar-nav::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
  }
  
  .sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.15);
    border-radius: 5px;
  }

  /* Navigation Section Labels */
  .nav-section {
    margin-bottom: 1rem;
    padding: 0 0.5rem;
  }
  
  .nav-section-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: rgba(255,255,255,0.5);
    margin-bottom: 0.5rem;
    padding-left: 0.5rem;
    letter-spacing: 0.5px;
    font-weight: 500;
  }

  /* Improved Navigation Links */
  .nav-link {
    color: var(--white-color);
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    transition: all 0.2s ease;
    margin-bottom: 0.25rem;
    text-decoration: none;
  }
  
  .nav-link i {
    font-size: 1.1rem;
    min-width: 24px;
    margin-right: 0.75rem;
    text-align: center;
    transition: transform 0.2s ease;
  }
  
  .nav-link-text {
    font-size: 0.95rem;
  }

  /* Navigation Hover & Active States */
  .nav-link:hover {
    background-color: var(--primary-dark);
    color: var(--white-color);
  }
  
  .nav-link:hover i {
    transform: translateY(-2px);
  }
  
  .nav-link.active {
    background-color: var(--secondary-color);
    color: var(--white-color);
    font-weight: 500;
    position: relative;
  }
  
  .nav-link.active i {
    color: var(--white-color);
  }

  /* Badge styling for notifications */
  .nav-badge {
    background-color: var(--accent-color);
    color: var(--black-color);
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.1rem 0.5rem;
    border-radius: 10px;
    margin-left: auto;
  }

  /* User profile section */
  .user-profile {
    padding: 1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    background-color: rgba(0,0,0,0.15);
    border-radius: var(--border-radius);
  }
  
  .user-avatar {
    width: 38px;
    height: 38px;
    background-color: var(--secondary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white-color);
    font-weight: 600;
    margin-right: 0.75rem;
  }
  
  .user-info {
    overflow: hidden;
  }
  
  .user-name {
    color: var(--white-color);
    margin: 0;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .user-role {
    color: rgba(255,255,255,0.7);
    margin: 0;
    font-size: 0.8rem;
  }

  /* Improved Logout Section */
  .sidebar-footer {
    padding: 1rem;
    border-top: 1px solid rgba(255,255,255,0.1);
  }
  
  .sidebar-logout-btn {
    width: 100%;
    background-color: var(--secondary-color);
    color: var(--white-color);
    border: none;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  
  .sidebar-logout-btn:hover {
    background-color: #c50325;
    transform: translateY(-2px);
  }
  
  .sidebar-logout-btn i {
    margin-right: 0.5rem;
  }

  /* Mobile menu toggle button */
  .mobile-toggle {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 1001;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--primary-color);
    border: none;
    color: var(--white-color);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    cursor: pointer;
  }
  
  @media (max-width: 991.98px) {
    .mobile-toggle {
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }

  /* Overlay for mobile */
  #sidebarOverlay {
    display: none;
    position: fixed;
    inset: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 999;
    transition: opacity 0.3s ease;
  }
  
  #sidebarOverlay.active {
    display: block;
  }


  @media (max-width: 991.98px) {
    .content-wrapper {
      margin-left: 0;
    }
  }
</style>


<!-- Overlay for mobile -->
<div id="sidebarOverlay"></div>

<!-- Main Sidebar -->
<div class="sidebar" id="sidebar">
  <!-- Header -->
  <div class="sidebar-header">
    <div class="d-flex align-items-center">
      <img 
        src="{{ asset('images/logo.png') }}" 
        alt="CHED Logo" 
        class="me-2"
      >
      <span class="sidebar-title">CHED - eTrack</span>
    </div>
    <img 
      src="{{ asset('images/logo2.png') }}" 
      alt="Secondary Logo" 
    >
  </div>
  
  <!-- User Profile Section -->
  <div class="user-profile">
    <div class="user-avatar">
      {{ substr(auth()->user()->name, 0, 1) }}
    </div>
    <div class="user-info">
      <p class="user-name">{{ auth()->user()->name }}</p>
      <p class="user-role">{{ ucfirst($role ?? 'User') }}</p>
    </div>
  </div>
  
  <!-- Navigation -->
  <nav class="sidebar-nav">
    <!-- Main Navigation -->
    <div class="nav-section">
      <div class="nav-section-title">Main Navigation</div>
      
      <!-- Home -->
      <a 
        href="{{ route($role.'.dashboard') }}" 
        class="nav-link {{ request()->routeIs($role.'.dashboard') ? 'active' : '' }}"
      >
        <i class="fa fa-home"></i>
        <span class="nav-link-text">Home</span>
      </a>
      
      <!-- Manage Documents -->
      <a 
        href="{{ route($role.'.documents.index') }}" 
        class="nav-link {{ request()->routeIs($role.'.documents.*') ? 'active' : '' }}"
      >
        <i class="fa fa-folder-open"></i>
        <span class="nav-link-text">Manage Documents</span>
      </a>
    </div>
    
    <!-- Document Management -->
    @if(in_array($role, ['admin', 'regionaldirector', 'records']))
    <div class="nav-section">
      <div class="nav-section-title">Document Management</div>
      
      <!-- Outgoing & Incoming -->
      <a 
        href="{{ route($role.'.outgoings.index') }}"
        class="nav-link {{ request()->routeIs($role.'.outgoings.*') ? 'active' : '' }}"
      >
        <i class="fa fa-arrow-up-right-from-square"></i>
        <span class="nav-link-text">Outgoing & Incoming</span>
      </a>
      
      <!-- CAV Documents -->
      <a 
        href="{{ route($role.'.cav.index') }}" 
        class="nav-link {{ request()->routeIs($role.'.cav.index') ? 'active' : '' }}"
      >
        <i class="fa fa-file-alt"></i>
        <span class="nav-link-text">CAV Documents</span>
      </a>
      
      <!-- S.O MasterList -->
      <a 
        href="{{ route($role.'.so_master_lists.index') }}" 
        class="nav-link {{ request()->routeIs($role.'.so_master_lists.*') ? 'active' : '' }}"
      >
        <i class="fa fa-list"></i>
        <span class="nav-link-text">S.O MasterList</span>
      </a>
    </div>
    @endif
    
    <!-- Admin Section -->
    @role('admin')
    <div class="nav-section">
      <div class="nav-section-title">Administration</div>
      
      <!-- Manage Users -->
      <a 
        href="{{ route('admin.manage.users.index') }}" 
        class="nav-link {{ request()->routeIs('admin.manage.users.*') ? 'active' : '' }}"
      >
        <i class="fa fa-users"></i>
        <span class="nav-link-text">Manage Users</span>
      </a>
    </div>
    @endrole
    
    <!-- Communication -->
    <div class="nav-section">
      <div class="nav-section-title">Communication</div>
      
      <!-- Email -->
      <a 
        href="{{ route('admin.gmail.emails') }}" 
        class="nav-link {{ request()->routeIs('admin.gmail.*') ? 'active' : '' }}"
      >
        <i class="fa fa-envelope"></i>
        <span class="nav-link-text">CHED-eMail</span>
      </a>
    </div>
    
    <!-- User Account -->
    <div class="nav-section">
      <div class="nav-section-title">Account</div>
      
      <!-- Profile -->
      <a 
        href="{{ route('profile.show') }}" 
        class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
      >
        <i class="fa fa-user"></i>
        <span class="nav-link-text">Profile</span>
      </a>
    </div>
  </nav>
  
  <!-- Logout Section -->
  <div class="sidebar-footer">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="sidebar-logout-btn">
        <i class="fa fa-sign-out-alt"></i>
        Sign Out
      </button>
    </form>
  </div>
</div>

<!-- Content Wrapper -->
<div class="content-wrapper" id="contentWrapper">
  <!-- Your page content goes here -->
</div>

<!-- JavaScript for Functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Mobile toggle functionality
 
    // Close sidebar when clicking overlay
    sidebarOverlay.addEventListener('click', function() {
      sidebar.classList.remove('open');
      sidebarOverlay.classList.remove('active');
    });
    
    // Initialize tooltips if they exist
    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  });
</script>