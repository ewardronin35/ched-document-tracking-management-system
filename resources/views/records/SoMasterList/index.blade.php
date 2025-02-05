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
    <h1>Records - SO Master List</h1>
    <a href="{{ route('records.so_master_lists.create') }}" class="btn btn-primary mb-3">Add New Student</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($soMasterLists->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>HEI Name</th>
                    <th>HEI UII</th>
                    <th>Student Name</th>
                    <th>Program</th>
                    <th>Major</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($soMasterLists as $index => $student)
                    <tr>
                        <td>{{ $soMasterLists->firstItem() + $index }}</td>
                        <td>{{ $student->hei_name }}</td>
                        <td>{{ $student->hei_uii }}</td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->program->name }}</td>
                        <td>{{ $student->major->name }}</td>
                        <td>
                            <a href="{{ route('records.so_master_lists.show', $student) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('records.so_master_lists.edit', $student) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('records.so_master_lists.destroy', $student) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $soMasterLists->links() }}
    @else
        <p>No students found.</p>
    @endif
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
