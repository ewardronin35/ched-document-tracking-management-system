@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-color: #4361ee;
        --primary-hover: #3a56d4;
        --danger-color: #ef476f;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --light-bg: #f8f9fa;
        --border-radius: 0.5rem;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        --transition: all 0.3s ease;
    }

    /* Profile container */
    .profile-container {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    /* Profile header */
    .profile-header {
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 1.5rem;
        text-align: center;
    }

    .profile-avatar {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .profile-avatar img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.2);
    }

    .avatar-upload {
        position: absolute;
        right: 0;
        bottom: 0;
        background-color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        color: #666;
        transition: var(--transition);
    }

    .avatar-upload:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .profile-title {
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .profile-subtitle {
        opacity: 0.8;
        font-size: 0.875rem;
    }

    /* Profile navigation */
    .profile-nav {
        display: flex;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        overflow-x: auto;
        scrollbar-width: none;
    }

    .profile-nav::-webkit-scrollbar {
        display: none;
    }

    .profile-nav-item {
        padding: 1rem 1.25rem;
        font-weight: 500;
        color: #6c757d;
        white-space: nowrap;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: var(--transition);
    }

    .profile-nav-item:hover {
        color: var(--primary-color);
    }

    .profile-nav-item.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }

    .profile-nav-item i {
        margin-right: 0.5rem;
    }

    /* Profile content */
    .profile-content {
        padding: 2rem;
    }

    .profile-section {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .profile-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card styling */
    .profile-card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .profile-card-header {
        padding: 1.25rem 1.5rem;
        background-color: var(--light-bg);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
    }

    .card-icon.danger {
        background-color: rgba(239, 71, 111, 0.1);
        color: var(--danger-color);
    }

    .card-icon.success {
        background-color: rgba(6, 214, 160, 0.1);
        color: var(--success-color);
    }

    .card-icon.warning {
        background-color: rgba(255, 209, 102, 0.1);
        color: var(--warning-color);
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    /* Buttons */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-content {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="profile-container">
        <!-- Profile Header with Avatar -->
        <div class="profile-header">
            <div class="profile-avatar">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                    <div class="avatar-upload" title="Update profile photo">
                        <i class="fas fa-camera"></i>
                    </div>
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ Auth::user()->name }}">
                @endif
            </div>
            <h2 class="profile-title">{{ Auth::user()->name }}</h2>
            <p class="profile-subtitle">{{ Auth::user()->email }}</p>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <div class="profile-nav-item active" data-target="personal-info">
                <i class="fas fa-user"></i> Personal Information
            </div>
            <div class="profile-nav-item" data-target="security">
                <i class="fas fa-lock"></i> Security
            </div>
            <div class="profile-nav-item" data-target="sessions">
                <i class="fas fa-desktop"></i> Sessions
            </div>
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <div class="profile-nav-item" data-target="danger-zone">
                <i class="fas fa-exclamation-triangle"></i> Danger Zone
            </div>
            @endif
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
            <!-- Personal Information Section -->
            <div class="profile-section active" id="personal-info">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        Update Profile Information
                    </div>
                    <div class="profile-card-body">
                        @livewire('profile.update-profile-information-form')
                    </div>
                </div>
                @endif
            </div>

            <!-- Security Section -->
            <div class="profile-section" id="security">
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        Update Password
                    </div>
                    <div class="profile-card-body">
                        @livewire('profile.update-password-form')
                    </div>
                </div>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon success">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        Two-Factor Authentication
                    </div>
                    <div class="profile-card-body">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                </div>
                @endif
            </div>

            <!-- Sessions Section -->
            <div class="profile-section" id="sessions">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon warning">
                            <i class="fas fa-desktop"></i>
                        </div>
                        Browser Sessions
                    </div>
                    <div class="profile-card-body">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                </div>
            </div>

            <!-- Danger Zone Section -->
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <div class="profile-section" id="danger-zone">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="card-icon danger">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <span class="text-danger">Delete Account</span>
                    </div>
                    <div class="profile-card-body">
                        @livewire('profile.delete-user-form')
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navigation tabs functionality
        const navItems = document.querySelectorAll('.profile-nav-item');
        const sections = document.querySelectorAll('.profile-section');

        navItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                navItems.forEach(nav => nav.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
                
                // Hide all sections
                sections.forEach(section => section.classList.remove('active'));
                // Show the target section
                const targetId = this.getAttribute('data-target');
                document.getElementById(targetId).classList.add('active');
            });
        });

        // Avatar upload button (if using Jetstream profile photos)
        const avatarUpload = document.querySelector('.avatar-upload');
        if (avatarUpload) {
            avatarUpload.addEventListener('click', function() {
                // Trigger the hidden file input in the Livewire component
                const fileInput = document.querySelector('input[type="file"][id^="photo"]');
                if (fileInput) {
                    fileInput.click();
                }
            });
        }
    });
</script>
@endpush