{{-- resources/views/manage-users/index.blade.php --}}

@extends('layouts.app')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CSS (Ensure this is included if not already) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Optional: Custom CSS for Hover Effects and Role Badges -->
    <style>
        /* Custom Colors */
        :root {
            --primary-color: #133A86; /* Blue */
            --secondary-color: #DA042A; /* Red */
            --accent-color: #FEE71B; /* Yellow */
            --white-color: #ffffff;
            --black-color: #000000;
        }

        /* Highlight table rows on hover */
        #usersTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Custom role badge colors */
        .badge-role-admin {
            background-color: var(--secondary-color); /* Red */
            color: var(--white-color);
        }

        .badge-role-user {
            background-color: var(--primary-color); /* Blue */
            color: var(--white-color);
        }

        .badge-role-manager {
            background-color: var(--accent-color); /* Yellow */
            color: var(--black-color);
        }

        /* Add more role-specific badge classes as needed */

        /* Permissions badge */
        .badge-accent {
            background-color: var(--accent-color); /* Yellow */
            color: var(--black-color);
        }

        /* Modal Styling Override */
        .modal-header {
            background-color: var(--primary-color);
            color: var(--white-color);
        }

        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .modal-footer .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* DataTables Button Styling */
        .dt-buttons .btn {
            background-color: var(--primary-color);
            color: var(--white-color);
            border: none;
        }

        .dt-buttons .btn:hover {
            background-color: #0d47a1; /* Darker Blue */
            color: var(--white-color);
        }

        /* Custom Styling for Action Buttons */
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: var(--black-color);
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: var(--black-color);
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: var(--white-color);
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: var(--white-color);
        }

        .btn-danger {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: var(--white-color);
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: var(--white-color);
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-3">Manage Users</h2>

    <!-- Success & Error Messages -->
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

    <!-- Actions -->
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="{{ route('admin.manage.users.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
            <a href="{{ route('admin.manage.users.import.form') }}" class="btn btn-secondary">
                <i class="fas fa-file-import"></i> Import Users
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card table-container">
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="usersTable">
                <thead class="table-primary">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Permissions</th>
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
                            <a href="{{ route('admin.manage.users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-sm btn-info me-1 edit-permissions-btn" data-user-id="{{ $user->id }}">
                                <i class="fas fa-lock"></i> Permissions
                            </button>
                            <form action="{{ route('admin.manage.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                    <i class="fas fa-trash-alt"></i> Delete
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
    </div>
</div>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
    {{-- View Permissions Modal (Optional) --}}
    <div class="modal fade" id="viewPermissionsModal" tabindex="-1" aria-labelledby="viewPermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPermissionsModalLabel">User Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="permissionsList">
                        {{-- Permissions will be loaded via JavaScript --}}
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
    {{-- Edit Permissions Modal --}}
    <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel"  tabindex="-1" inert>
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
                                {{-- Permissions checkboxes will be injected here via JavaScript --}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Axios for AJAX requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                "paging": true, // Enable DataTables paging
                "info": true,   // Enable the info text
                "searching": true, // Enable searching
                "ordering": true,  // Enable column ordering
                "order": [[0, "asc"]], // Initial sort by the first column (Name) ascending
                "columnDefs": [
                    { "orderable": false, "targets": [3,4] } // Disable ordering on the Permissions and Actions columns
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
            document.querySelector('#editPermissionsModal').removeAttribute('inert');

            // Edit Permissions Button Click Handler
            $('.edit-permissions-btn').on('click', function() {
                var userId = $(this).data('user-id');
                $('#modalUserId').val(userId);

                // Fetch current permissions for the user
                axios.get('/admin/manage-users/' + userId + '/permissions')
                .then(function(response) {
                        var permissions = response.data.permissions;
                        var userPermissions = response.data.user_permissions;

                        var permissionsHtml = '';
                        permissions.forEach(function(permission) {
                            var isChecked = userPermissions.includes(permission.name) ? 'checked' : '';
                            permissionsHtml += `
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="${permission.id}" id="perm-${permission.id}" name="permissions[]" ${isChecked}>
                                    <label class="form-check-label" for="perm-${permission.id}">
                                        ${permission.name}
                                    </label>
                                </div>
                            `;
                        });

                        $('#permissionsContainer').html(permissionsHtml);
                        var editPermissionsModal = new bootstrap.Modal(document.getElementById('editPermissionsModal'));
                        editPermissionsModal.show();
                    })
                    .catch(function(error) {
                        console.error('Error fetching permissions:', error);
                        Swal.fire('Error!', 'Unable to fetch permissions.', 'error');
                    });
            });

            // Handle Permissions Form Submission
            $('#editPermissionsForm').on('submit', function(e) {
                e.preventDefault();
                var userId = $('#modalUserId').val();
                var permissions = $('input[name="permissions[]"]:checked').map(function(){
                    return parseInt($(this).val(), 10);
                }).get();

                axios.post('/admin/manage-users/' + userId + '/permissions', {
                    permissions: permissions
                })
                .then(function(response) {
                    if (response.data.success) {
                        Swal.fire('Success!', 'Permissions updated successfully.', 'success');
                        // Optionally, update the Permissions column in the table without reloading
                        location.reload();
                    } else {
                        Swal.fire('Error!', response.data.message || 'Failed to update permissions.', 'error');
                    }
                })
                .catch(function(error) {
                    console.error('Error updating permissions:', error);
                    Swal.fire('Error!', 'An error occurred while updating permissions.', 'error');
                });
            });
        });
    </script>
@endpush
