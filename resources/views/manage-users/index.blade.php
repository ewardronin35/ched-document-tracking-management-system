{{-- resources/views/manage-users/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Users</h1>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Actions --}}
    <div class="mb-3">
        <a href="{{ route('manage.users.create') }}" class="btn btn-primary">Add New User</a>
        <a href="{{ route('manage.users.import.form') }}" class="btn btn-secondary">Import Users</a>
    </div>

    {{-- Users Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-info text-dark">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('manage.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        {{-- Generate Password Button --}}
                        <form action="{{ route('manage.users.generatePassword', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to generate a new password for this user?')">Generate Password</button>
                        </form>

                        {{-- Delete Button --}}
                        <form action="{{ route('manage.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination Links --}}
    {{ $users->links() }}
</div>
@endsection
