{{-- resources/views/manage-users/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit User: {{ $user->name }}</h1>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- User Edit Form --}}
    <form action="{{ route('manage.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" class="form-select" id="role" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password <small class="text-muted">(Leave blank to keep current password)</small></label>
            <input type="password" name="password" class="form-control" id="password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('manage.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
