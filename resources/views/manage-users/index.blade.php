{{-- resources/views/manage-users/index.blade.php --}}

@extends('layouts.app')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Optional: Custom CSS for Hover Effects and Role Badges -->
    <style>
        /* Highlight table rows on hover */
        #usersTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Custom role badge colors */
        .badge-role-admin {
            background-color: #dc3545; /* Bootstrap Danger */
            color: white;
        }

        .badge-role-user {
            background-color: #0d6efd; /* Bootstrap Primary */
            color: white;
        }

        /* Add more role-specific badge classes as needed */
    </style>
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Users</h1>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Actions --}}
    <div class="mb-3 d-flex justify-content-between">
        <div>
            <a href="{{ route('manage.users.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
            <a href="{{ route('manage.users.import.form') }}" class="btn btn-secondary">
                <i class="fas fa-file-import"></i> Import Users
            </a>
        </div>
        {{-- Optional: Add Export Button --}}
        {{-- 
        <div>
            <a href="{{ route('manage.users.export') }}" class="btn btn-success me-2">
                <i class="fas fa-file-export"></i> Export Users
            </a>
        </div>
        --}}
    </div>

    {{-- Users Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="usersTable">
            <thead class="table-dark">
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
                                @php
                                    // Determine the badge class based on role name
                                    switch(strtolower($role->name)) {
                                        case 'admin':
                                            $badgeClass = 'badge-role-admin';
                                            break;
                                        case 'user':
                                            $badgeClass = 'badge-role-user';
                                            break;
                                        default:
                                            $badgeClass = 'bg-secondary';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('manage.users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1" title="Edit User" data-bs-toggle="tooltip" data-bs-placement="top">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- Password Generation Button (Optional) --}}
                            {{-- 
                            <form action="{{ route('manage.users.generatePassword', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary me-1" title="Generate Password" data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="fas fa-key"></i> Generate Password
                                </button>
                            </form>
                            --}}

                            {{-- Delete Button --}}
                            <form action="{{ route('manage.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete User" data-bs-toggle="tooltip" data-bs-placement="top" onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Initialize DataTables and Tooltips -->
    <script>
        $(document).ready(function() {
            console.log('Initializing DataTables');

            // Check if DataTable plugin is loaded
            if (typeof $.fn.DataTable !== 'function') {
                console.error('DataTables JS is not loaded correctly.');
                return;
            }

            // Initialize DataTables
            $('#usersTable').DataTable({
                "paging": false, // Disable DataTables paging since Laravel handles it
                "info": false,   // Disable the info text
                "searching": true, // Enable searching
                "ordering": true,  // Enable column ordering
                "order": [[0, "asc"]], // Initial sort by the first column (Name) ascending
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // Disable ordering on the Actions column
                ],
                "language": {
                    "search": "Filter records:",
                    "emptyTable": "No users available",
                }
            });

            console.log('DataTables initialized successfully.');

            // Initialize Bootstrap Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            console.log('Bootstrap Tooltips initialized.');
        });
    </script>
@endpush
