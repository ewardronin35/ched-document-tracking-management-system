{{-- resources/views/manage-users/index.blade.php --}}

@extends('layouts.app')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        /* Highlight table rows on hover */
        #usersTable tbody tr:hover {
            background-color: #f1f1f1;
        }
        /* Custom role badge colors */
        .badge-role-admin {
            background-color: #dc3545;
            color: white;
        }
        .badge-role-user {
            background-color: #0d6efd;
            color: white;
        }
        /* Tab nav spacing */
        .nav-tabs .nav-link {
            margin-right: 5px;
        }
    </style>
@endpush

@section('content')
<div class="container">

    {{-- Show who is currently logged in --}}
    <div class="alert alert-info">
        You are logged in as: 
        <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
    </div>

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

    {{-- Bootstrap 5 Nav Tabs --}}
    <ul class="nav nav-tabs mb-4" id="manageUsersTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button 
              class="nav-link active" 
              id="users-tab" 
              data-bs-toggle="tab" 
              data-bs-target="#usersTabContent" 
              type="button" 
              role="tab" 
              aria-controls="usersTabContent" 
              aria-selected="true">
                Manage Users
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button 
              class="nav-link" 
              id="gmail-tab" 
              data-bs-toggle="tab" 
              data-bs-target="#gmailTabContent" 
              type="button" 
              role="tab" 
              aria-controls="gmailTabContent" 
              aria-selected="false">
                Gmail Logins
            </button>
        </li>
    </ul>

    <div class="tab-content" id="manageUsersTabsContent">

        {{-- 1) Manage Users Tab --}}
        <div 
          class="tab-pane fade show active" 
          id="usersTabContent" 
          role="tabpanel" 
          aria-labelledby="users-tab"
        >
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Manage Users</h2>
                <div>
                    <a href="{{ route('admin.manage.users.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-user-plus"></i> Add New User
                    </a>
                    <a href="{{ route('admin.manage.users.import.form') }}" class="btn btn-secondary">
                        <i class="fas fa-file-import"></i> Import Users
                    </a>
                </div>
            </div>

            {{-- Users Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="usersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Eligible?</th>
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
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            {{-- can_login toggle --}}
                            <td>
                                <form 
                                  action="{{ route('admin.manage.users.toggle-login', $user->id) }}" 
                                  method="POST"
                                  style="display:inline-block;"
                                >
                                    @csrf
                                    @method('PATCH')
                                    @if($user->can_login)
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Enabled
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            Disabled
                                        </button>
                                    @endif
                                </form>
                            </td>
                            {{-- Actions --}}
                            <td>
                                <a 
                                  href="{{ route('admin.manage.users.edit', $user->id) }}"
                                  class="btn btn-sm btn-warning me-1"
                                  title="Edit User" 
                                  data-bs-toggle="tooltip" 
                                  data-bs-placement="top"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form 
                                  action="{{ route('admin.manage.users.destroy', $user->id) }}" 
                                  method="POST" 
                                  style="display:inline-block;"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                      type="submit" 
                                      class="btn btn-sm btn-danger"
                                      title="Delete User" 
                                      data-bs-toggle="tooltip" 
                                      data-bs-placement="top"
                                      onclick="return confirm('Are you sure you want to delete this user?');"
                                    >
                                        <i class="fas fa-trash-alt"></i> 
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No users found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>

        {{-- 2) Gmail Logins Tab --}}
        <div 
          class="tab-pane fade" 
          id="gmailTabContent" 
          role="tabpanel" 
          aria-labelledby="gmail-tab"
        >
            <h2 class="mb-3">Gmail Logins</h2>
            <p class="text-muted">
                Below is a list of users who have an active Gmail Token in the system.
            </p>

            @if($gmailTokens->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="gmailLoginsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Token Created</th>
                                <th>Token Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gmailTokens as $token)
                                <tr>
                                    <td>{{ $token->user->name }}</td>
                                    <td>{{ $token->user->email }}</td>
                                    <td>{{ $token->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $token->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    No users are currently connected to Gmail.
                </div>
            @endif
        </div>
    </div> <!-- /.tab-content -->
</div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables for the "Manage Users" table
            if (typeof $.fn.DataTable === 'function') {
                $('#usersTable').DataTable({
                    paging: false, // Because Laravel does the paging
                    info: false,
                    searching: true,
                    ordering: true,
                    order: [[0, "asc"]], // Sort by first column (Name)
                    columnDefs: [
                        { orderable: false, targets: 3 }, // "Eligible?"
                        { orderable: false, targets: 4 }  // "Actions"
                    ],
                    language: {
                        search: "Filter records:",
                        emptyTable: "No users available",
                    }
                });
            }

            // Initialize DataTables for the "Gmail Logins" table (optional)
            if (typeof $.fn.DataTable === 'function') {
                $('#gmailLoginsTable').DataTable({
                    paging: false,
                    info: false,
                    searching: true,
                    ordering: true,
                    order: [[0, "asc"]],
                    language: {
                        search: "Filter records:",
                        emptyTable: "No Gmail logins available",
                    }
                });
            }

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
