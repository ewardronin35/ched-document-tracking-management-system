@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin - Programs</h1>
        <div>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importProgramsModal">Import CSV</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProgramModal">Add New Program</button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- DataTable -->
    <table id="programsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Program Name</th>
                <th>PSCED Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programs as $index => $program)
                <tr data-id="{{ $program->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td contenteditable="true" data-column="name">{{ $program->name }}</td>
                    <td contenteditable="true" data-column="psced_code">{{ $program->psced_code }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $program->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Import Programs Modal -->
    <div class="modal fade" id="importProgramsModal" tabindex="-1" aria-labelledby="importProgramsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.programs.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importProgramsModalLabel">Import Programs CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="programCsvFile" class="form-label">Upload CSV File</label>
                            <input type="file" name="csv_file" id="programCsvFile" class="form-control" accept=".csv" required>
                            <small class="form-text text-muted">Ensure the CSV has headers: "name", "psced_code".</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import Programs</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Program Modal -->
    <div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.programs.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProgramModalLabel">Add New Program</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="programName" class="form-label">Program Name *</label>
                            <input type="text" name="name" id="programName" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="pscedCode" class="form-label">PSCED Code</label>
                            <input type="text" name="psced_code" id="pscedCode" class="form-control" value="{{ old('psced_code') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Program</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#programsTable').DataTable({
                "scrollX": true
            });

            // CSRF Token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Inline Editing
            $('#programsTable').on('blur', '[contenteditable="true"]', function () {
                let cell = $(this);
                let newValue = cell.text().trim();
                let column = cell.data('column');
                let row = cell.closest('tr');
                let id = row.data('id');

                if (newValue === '') {
                    alert('This field cannot be empty.');
                    // Optionally, revert to previous value or focus back
                    location.reload();
                    return;
                }

                let data = {};
                data[column] = newValue;

                $.ajax({
                    url: `/admin/programs/${id}/inline-update`,
                    method: 'PUT',
                    data: data,
                    success: function (response) {
                        console.log(response.success);
                    },
                    error: function (xhr) {
                        alert('Error updating program: ' + xhr.responseJSON.error[column][0]);
                        // Optionally, revert the text to previous value
                        location.reload();
                    }
                });
            });

            // Delete Button
            $('#programsTable').on('click', '.delete-btn', function () {
                if (confirm('Are you sure you want to delete this program?')) {
                    let button = $(this);
                    let id = button.data('id');

                    $.ajax({
                        url: `/admin/programs/${id}`,
                        method: 'DELETE',
                        success: function () {
                            alert('Program deleted successfully!');
                            // Remove the row from DataTable
                            $('#programsTable').DataTable().row(button.parents('tr')).remove().draw();
                        },
                        error: function () {
                            alert('Error deleting program.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
