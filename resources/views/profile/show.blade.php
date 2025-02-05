@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">Profile</h2>
        </div>
        <div class="col-12">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="card mb-4">
                    <div class="card-header fw-semibold">
                        Update Profile Information
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-profile-information-form')
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="card mb-4">
                    <div class="card-header fw-semibold">
                        Update Password
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-password-form')
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="card mb-4">
                    <div class="card-header fw-semibold">
                        Two-Factor Authentication
                    </div>
                    <div class="card-body">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header fw-semibold">
                    Logout Other Browser Sessions
                </div>
                <div class="card-body">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="card mb-4">
                    <div class="card-header fw-semibold text-danger">
                        Delete Account
                    </div>
                    <div class="card-body">
                        @livewire('profile.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
