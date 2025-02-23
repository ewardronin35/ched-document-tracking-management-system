{{-- resources/views/admin/manage-users/index.blade.php --}}

@extends('layouts.app')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #133A86; /* Blue */
            --secondary-color: #DA042A; /* Red */
            --accent-color: #FEE71B; /* Yellow */
            --white-color: #ffffff;
            --black-color: #000000;
        }

        .alert-info strong {
            color: var(--black-color);
        }

        /* Tab Nav spacing */
        .nav-tabs .nav-link {
            margin-right: 5px;
        }

        /* DataTables row highlight on hover */
        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Toggle Switch (for can_login) */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }
        .switch input {
            opacity: 0; width: 0; height: 0;
        }
        .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 22px;
        }
        .slider:before {
            position: absolute; content: "";
            height: 16px; width: 16px;
            left: 3px; bottom: 3px;
            background-color: #fff;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #28a745; /* Green */
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #28a745;
        }
        input:checked + .slider:before {
            transform: translateX(18px);
        }

        /* Role Badges */
        .badge-role-admin {
            background-color: var(--secondary-color);
            color: var(--white-color);
        }
        .badge-role-user {
            background-color: var(--primary-color);
            color: var(--white-color);
        }
        .badge-role-manager {
            background-color: var(--accent-color);
            color: var(--black-color);
        }
        .badge-accent {
            background-color: var(--accent-color);
            color: var(--black-color);
        }

        /* Modal Overrides */
        .modal-header {
            background-color: var(--primary-color);
            color: var(--white-color);
        }

        /* Action Buttons */
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: var(--black-color);
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: var(--white-color);
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .btn-danger {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: var(--white-color);
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
@endpush

@section('content')
  

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs mb-4" id="manageUsersTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button 
          class="nav-link active d-flex align-items-center gap-2" 
          id="users-tab" 
          data-bs-toggle="tab" 
          data-bs-target="#users-tab-pane"
          type="button" 
          role="tab" 
          aria-controls="users-tab-pane" 
          aria-selected="true"
        >
            <i class="fas fa-users"></i> Manage Users
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button 
          class="nav-link d-flex align-items-center gap-2" 
          id="gmail-tab" 
          data-bs-toggle="tab" 
          data-bs-target="#gmail-tab-pane" 
          type="button" 
          role="tab" 
          aria-controls="gmail-tab-pane" 
          aria-selected="false"
        >
            <i class="fas fa-envelope"></i> Gmail Logins
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button 
          class="nav-link d-flex align-items-center gap-2" 
          id="audit-tab" 
          data-bs-toggle="tab" 
          data-bs-target="#audit-tab-pane" 
          type="button" 
          role="tab" 
          aria-controls="audit-tab-pane" 
          aria-selected="false"
        >
            <i class="fas fa-clipboard-list"></i> Audit Logs
        </button>
    </li>
</ul>


    <div class="tab-content" id="manageUsersTabsContent">
        <!-- 1) Manage Users Tab -->
        <div 
          class="tab-pane fade show active" 
          id="users-tab-pane" 
          role="tabpanel" 
          aria-labelledby="users-tab"
        >
            <div class="d-flex justify-content-between mb-3">
                <h4>Manage Users</h4>
                <div>
                    <a href="{{ route('admin.manage.users.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-user-plus"></i> Add New User
                    </a>
                    <a href="{{ route('admin.manage.users.import.form') }}" class="btn btn-secondary">
                        <i class="fas fa-file-import"></i> Import Users
                    </a>
                </div>
            </div>

            <div class="card table-container">
                <div class="card-body">
                    <table class="table table-bordered table-striped w-100" id="usersTable">
                        <thead class="table-primary">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Permissions</th>
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
                                            $badgeClass = match(strtolower($role->name)) {
                                                'admin' => 'badge-role-admin',
                                                'user' => 'badge-role-user',
                                                'manager' => 'badge-role-manager',
                                                default => 'badge bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($user->permissions as $permission)
                                        <span class="badge badge-accent">{{ $permission->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <!-- Toggle Switch for can_login -->
                                    <label class="switch">
                                        <input 
                                          type="checkbox" 
                                          class="toggle-can-login" 
                                          data-user-id="{{ $user->id }}"
                                          {{ $user->can_login ? 'checked' : '' }}
                                        >
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('admin.manage.users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button 
                                        class="btn btn-sm btn-info me-1 edit-permissions-btn" 
                                        data-user-id="{{ $user->id }}"
                                    >
                                        <i class="fas fa-lock"></i> Permissions
                                    </button>
                                    <form 
                                        action="{{ route('admin.manage.users.destroy', $user->id) }}" 
                                        method="POST" 
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                          type="submit" 
                                          class="btn btn-sm btn-danger" 
                                          onclick="return confirm('Are you sure?');"
                                        >
                                            <i class="fas fa-trash-alt"></i> 
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination for Manage Users -->
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        </div>

        <!-- 2) Gmail Logins Tab -->
        <div 
          class="tab-pane fade" 
          id="gmail-tab-pane" 
          role="tabpanel" 
          aria-labelledby="gmail-tab"
        >
            <h4>Gmail Logins</h4>
            <p class="text-muted">Below is a list of users who have a valid Gmail token.</p>
            @if($gmailTokens->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="gmailLoginsTable">
                        <thead class="table-primary">
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
                                    <td>{{ $token->user?->name }}</td>
                                    <td>{{ $token->user?->email }}</td>
                                    <td>{{ $token->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $token->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">No Gmail tokens found.</div>
            @endif
        </div>

        <!-- 3) Audit Logs Tab -->
        <div 
          class="tab-pane fade" 
          id="audit-tab-pane" 
          role="tabpanel" 
          aria-labelledby="audit-tab"
        >
            <h4>Audit Logs</h4>
            <p class="text-muted">
                Below is an example list of user login events (or any custom audit logs).
            </p>
            @if($auditLogs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="auditLogsTable">
                        <thead class="table-primary">
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Event</th>
                                <th>Logged At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->user?->name ?? 'N/A' }}</td>
                                    <td>{{ $log->user?->email ?? 'N/A' }}</td>
                                    <td>{{ $log->event_type ?? 'Login' }}</td>
                                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $auditLogs->links() }}
                </div>
            @else
                <div class="alert alert-warning">No audit logs found.</div>
            @endif
        </div>
    </div> <!-- /.tab-content -->

    <!-- Edit Permissions Modal -->
    <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editPermissionsForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPermissionsModalLabel">Edit Permissions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modalUserId" name="user_id">
                        <div class="mb-3">
                            <label for="permissions" class="form-label">Permissions</label>
                            <div id="permissionsContainer">
                                <!-- Checkboxes inserted via JS -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button 
                          type="button" 
                          class="btn btn-secondary" 
                          data-bs-dismiss="modal"
                        >Cancel</button>
                        <button 
                          type="submit" 
                          class="btn btn-primary"
                        >Save Permissions</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /.container-fluid -->
@endsection

@push('scripts')
    <!-- jQuery (if not loaded globally) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS (if not already included) -->

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Axios for AJAX requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toastr config
            toastr.options = {
                "closeButton": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "3000",
            };

            // Initialize DataTables for each table
            $('#usersTable').DataTable({
                paging: false,
                info: false,
                searching: true,
                ordering: true,
                order: [[0, "asc"]], // Sort by Name
                columnDefs: [
                    { orderable: false, targets: [2,3,4,5] }
                ]
            });
            $('#gmailLoginsTable').DataTable({
                paging: false,
                info: false,
                searching: true,
                ordering: true,
                order: [[0, "asc"]]
            });
            $('#auditLogsTable').DataTable({
                paging: false,
                info: false,
                searching: true,
                ordering: true,
                order: [[3, "desc"]] // Sort by date descending
            });

            // Toggle can_login
            $('.toggle-can-login').on('change', function() {
                const userId = $(this).data('user-id');
                const checked = $(this).is(':checked');
                axios.patch('/admin/manage/users/toggle-login/' + userId)
                    .then(function(response) {
                        if (response.data && response.data.successMessage) {
                            toastr.success(response.data.successMessage);
                        } else {
                            toastr.success('Login eligibility updated.');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('Failed to update login eligibility.');
                        // Revert checkbox if error
                        $(this).prop('checked', !checked);
                    }.bind(this));
            });

            // Edit Permissions
            $('.edit-permissions-btn').on('click', function() {
                const userId = $(this).data('user-id');
                $('#modalUserId').val(userId);

                axios.get(`/admin/manage-users/${userId}/permissions`)
                    .then(function(response) {
                        const permissions = response.data.permissions || [];
                        const userPermissions = response.data.user_permissions || [];
                        let html = '';
                        permissions.forEach(function(permission) {
                            const isChecked = userPermissions.includes(permission.name) ? 'checked' : '';
                            html += `
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        value="${permission.id}"
                                        id="perm-${permission.id}"
                                        name="permissions[]"
                                        ${isChecked}
                                    >
                                    <label class="form-check-label" for="perm-${permission.id}">
                                        ${permission.name}
                                    </label>
                                </div>
                            `;
                        });
                        document.getElementById('permissionsContainer').innerHTML = html;
                        const modal = new bootstrap.Modal(document.getElementById('editPermissionsModal'));
                        modal.show();
                    })
                    .catch(function(error) {
                        console.error(error);
                        toastr.error('Unable to fetch permissions.');
                    });
            });

            // Save Permissions
            document.getElementById('editPermissionsForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const userId = document.getElementById('modalUserId').value;
                // Collect checked permissions
                const checkedBoxes = document.querySelectorAll('input[name="permissions[]"]:checked');
                const permissions = [];
                checkedBoxes.forEach(cb => permissions.push(parseInt(cb.value, 10)));

                axios.post(`/admin/manage-users/${userId}/permissions`, {
                    permissions: permissions
                })
                .then(function(response) {
                    if (response.data.success) {
                        toastr.success('Permissions updated successfully.');
                        location.reload();
                    } else {
                        toastr.error(response.data.message || 'Failed to update permissions.');
                    }
                })
                .catch(function(error) {
                    toastr.error('An error occurred while updating permissions.');
                    console.error(error);
                });
            });
        });
    </script>

    {{-- Optionally show session messages via Toastr if desired --}}
    @if(session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif
    @if(session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
    @endif
@endpush
