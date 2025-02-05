@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin - Majors</h1>
        <div>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importMajorsModal">Import CSV</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMajorModal">Add New Major</button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- DataTable -->
    <table id="majorsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No.</th>
                <th>Major Name</th>
                <th>Program</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($majors as $index => $major)
                <tr data-id="{{ $major->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td contenteditable="true" data-column="name">{{ $major->name }}</td>
                    <td>
                        <select class="form-select form-select-sm program-select" data-column="program_id">
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ $major->program_id == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $major->id }}">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Import Majors Modal -->
    <div class="modal fade" id="importMajorsModal" tabindex="-1" aria-labelledby="importMajorsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.majors.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importMajorsModalLabel">Import Majors CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="majorCsvFile" class="form-label">Upload CSV File</label>
                            <input type="file" name="csv_file" id="majorCsvFile" class="form-control" accept=".csv" required>
                            <small class="form-text text-muted">Ensure the CSV has headers: "name", "program_id".</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import Majors</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Major Modal -->
    <div class="modal fade" id="addMajorModal" tabindex="-1" aria-labelledby="addMajorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.majors.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMajorModalLabel">Add New Major</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="majorName" class="form-label">Major Name *</label>
                            <input type="text" name="name" id="majorName" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="majorProgram" class="form-label">Program *</label>
                            <select name="program_id" id="majorProgram" class="form-select" required>
                                <option value="">-- Select Program --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Major</button>
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
            $('#majorsTable').DataTable({
                "scrollX": true
            });

            // CSRF Token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Inline Editing for Major Name
            $('#majorsTable').on('blur', '[contenteditable="true"]', function () {
                let cell = $(this);
                let newValue = cell.text().trim();
                let column = cell.data('column');
                let row = cell.closest('tr');
                let id = row.data('id');

                if (newValue === '') {
                    alert('Major name cannot be empty.');
                    // Optionally, revert to previous value or focus back
                    location.reload();
                    return;
                }

                let data = {};
                data[column] = newValue;

                $.ajax({
                    url: `/admin/majors/${id}/inline-update`,
                    method: 'PUT',
                    data: data,
                    success: function (response) {
                        console.log(response.success);
                    },
                    error: function (xhr) {
                        alert('Error updating major: ' + xhr.responseJSON.error[column][0]);
                        // Optionally, revert the text to previous value
                        location.reload();
                    }
                });
            });

            // Inline Editing for Program Selection
            $('#majorsTable').on('change', '.program-select', function () {
                let select = $(this);
                let newValue = select.val();
                let column = select.data('column');
                let row = select.closest('tr');
                let id = row.data('id');

                if (newValue === '') {
                    alert('Program must be selected.');
                    return;
                }

                let data = {};
                data[column] = newValue;

                $.ajax({
                    url: `/admin/majors/${id}/inline-update`,
                    method: 'PUT',
                    data: data,
                    success: function (response) {
                        console.log(response.success);
                    },
                    error: function (xhr) {
                        alert('Error updating major: ' + xhr.responseJSON.error[column][0]);
                        // Optionally, revert the select to previous value
                        location.reload();
                    }
                });
            });

            // Delete Button
            $('#majorsTable').on('click', '.delete-btn', function () {
                if (confirm('Are you sure you want to delete this major?')) {
                    let button = $(this);
                    let id = button.data('id');

                    $.ajax({
                        url: `/admin/majors/${id}`,
                        method: 'DELETE',
                        success: function () {
                            alert('Major deleted successfully!');
                            // Remove the row from DataTable
                            $('#majorsTable').DataTable().row(button.parents('tr')).remove().draw();
                        },
                        error: function () {
                            alert('Error deleting major.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
