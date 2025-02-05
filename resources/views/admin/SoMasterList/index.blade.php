@extends('layouts.app')

@section('content')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- If you want the Responsive extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin - SO Master List</h1>
        <div>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importCsvModal">Import CSV</button>
            <button class="btn btn-secondary me-2" onclick="window.location.href='{{ route('admin.programs.index') }}'">View Programs</button>
            <button class="btn btn-secondary me-2" onclick="window.location.href='{{ route('admin.majors.index') }}'">View Majors</button>
            <button class="btn btn-success ms-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add New Student</button>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- DataTable -->
    <table id="soMasterListTable" class="table table-bordered table-striped">
    <thead>
  <tr>
    <th>No.</th>
    <th>HEI Name</th>
    <th>HEI UII</th>
    <th>Last Name</th>
    <th>First Name</th>
    <th>Middle Name</th>
    <th>Extension Name</th>
    <th>Sex</th>
    <th>Program</th>
    <th>Major</th>
    <th>Started</th>
    <th>Ended</th>
    <th>Academic Year</th>
    <th>Date of Application</th>
    <th>Date of Issuance</th>
    <th>Registrar</th>
    <th>Govt Permit Reco</th>
    <th>Total</th>
    <th>Semester</th>
    <th>Date of Graduation</th>
    <th>Actions</th>
  </tr>
</thead>

        <tbody>
          
        </tbody>
    </table>

    <!-- Upload Govt Permit Reco Modal -->
    <div class="modal fade" id="uploadGovtPermitModal" tabindex="-1" aria-labelledby="uploadGovtPermitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="uploadGovtPermitForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadGovtPermitModalLabel">Upload/Change Government Permit Recommendation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="student_id" id="uploadStudentId">
                        <div class="mb-3">
                            <label for="govt_permit_file" class="form-label">Government Permit Recommendation (PDF, DOCX)</label>
                            <input type="file" name="govt_permit_file" id="govt_permit_file" class="form-control" accept=".pdf,.docx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import CSV Modal -->
    <div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.so_master_lists.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importCsvModalLabel">Import SO Master List CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="soCsvFile" class="form-label">Upload CSV File</label>
                            <input type="file" name="csv_file" id="soCsvFile" class="form-control" accept=".csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="addStudentForm" action="{{ route('admin.so_master_lists.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- HEI Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="hei_name" class="form-label">HEI Name</label>
                                        <input type="text" name="hei_name" id="hei_name" class="form-control" value="{{ old('hei_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="hei_uii" class="form-label">HEI UII</label>
                                        <input type="text" name="hei_uii" id="hei_uii" class="form-control" value="{{ old('hei_uii') }}">
                                    </div>
                                </div>
                            </div>
                            <!-- Student Name Details -->
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" required value="{{ old('last_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" required value="{{ old('first_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name" class="form-control" value="{{ old('middle_name') }}">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- Sex, Program, Major -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="sex" class="form-label">Sex *</label>
                                        <select name="sex" id="sex" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="program_id" class="form-label">Program *</label>
                                        <select name="program_id" id="program_id" class="form-select" required>
                                            <option value="">-- Select Program --</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                                    {{ $program->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="major_id" class="form-label">Major *</label>
                                        <select name="major_id" id="major_id" class="form-select" required>
                                            <option value="">-- Select Major --</option>
                                            @foreach($majors as $major)
                                                <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                                    {{ $major->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Academic and Application Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="academic_year" class="form-label">Academic Year</label>
                                        <input type="text" name="academic_year" id="academic_year" class="form-control" value="{{ old('academic_year') }}" placeholder="e.g., 2023-2024">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="semester" class="form-label">Current Semester *</label>
                                        <select name="semester" id="semester" class="form-select" required>
                                            <option value="">-- Select Semester --</option>
                                            <option value="1" {{ old('semester') == 1 ? 'selected' : '' }}>First</option>
                                            <option value="2" {{ old('semester') == 2 ? 'selected' : '' }}>Second</option>
                                            <option value="3" {{ old('semester') == 3 ? 'selected' : '' }}>Summer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="started" class="form-label">Started</label>
                                        <input type="date" name="started" id="started" class="form-control" value="{{ old('started') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ended" class="form-label">Ended</label>
                                        <input type="date" name="ended" id="ended" class="form-control" value="{{ old('ended') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_of_application" class="form-label">Date of Application</label>
                                        <input type="date" name="date_of_application" id="date_of_application" class="form-control" value="{{ old('date_of_application') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_of_issuance" class="form-label">Date of Issuance</label>
                                        <input type="date" name="date_of_issuance" id="date_of_issuance" class="form-control" value="{{ old('date_of_issuance') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_of_graduation" class="form-label">Date of Graduation</label>
                                        <input type="date" name="date_of_graduation" id="date_of_graduation" class="form-control" value="{{ old('date_of_graduation') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="semester1_start" class="form-label">Semester 1 Start</label>
                                        <input type="date" name="semester1_start" id="semester1_start" class="form-control" value="{{ old('semester1_start') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="semester1_end" class="form-label">Semester 1 End</label>
                                        <input type="date" name="semester1_end" id="semester1_end" class="form-control" value="{{ old('semester1_end') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="semester2_start" class="form-label">Semester 2 Start</label>
                                        <input type="date" name="semester2_start" id="semester2_start" class="form-control" value="{{ old('semester2_start') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="semester2_end" class="form-label">Semester 2 End</label>
                                        <input type="date" name="semester2_end" id="semester2_end" class="form-control" value="{{ old('semester2_end') }}">
                                    </div>
                                </div>
                            </div>
                            <!-- Registrar and Permit Details -->
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="registrar" class="form-label">Registrar</label>
                                        <input type="text" name="registrar" id="registrar" class="form-control" value="{{ old('registrar') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="govt_permit_reco" class="form-label">Government Permit Recommendation (PDF, DOCX)</label>
                                        <input type="file" name="govt_permit_reco" id="govt_permit_reco" class="form-control" accept=".pdf,.docx">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="total" class="form-label">Total</label>
                                        <input type="number" name="total" id="total" class="form-control" value="{{ old('total') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Notes or Additional Information (Optional) -->
                        <!-- You can add more fields here if needed -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Student</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

@endsection

@section('scripts')
    <!-- DataTables CSS -->
    @push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- If you want the Responsive extension -->
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#soMasterListTable').DataTable({
                processing: true,
      serverSide: true,
      ajax: "{{ route('admin.so_master_lists.data') }}",
      columns: [
        { data: 'id', name: 'id' },
        { data: 'hei_name', name: 'hei_name' },
        { data: 'hei_uii', name: 'hei_uii' },
        { data: 'last_name', name: 'last_name' },
        { data: 'first_name', name: 'first_name' },
        { data: 'middle_name', name: 'middle_name' },
        { data: 'extension_name', name: 'extension_name' },
        { data: 'sex', name: 'sex' },
        { data: 'program_id', name: 'program_id' },
        { data: 'major_id', name: 'major_id' },
        { data: 'started', name: 'started' },
        { data: 'ended', name: 'ended' },
        { data: 'academic_year', name: 'academic_year' },
        { data: 'date_of_application', name: 'date_of_application' },
        { data: 'date_of_issuance', name: 'date_of_issuance' },
        { data: 'registrar', name: 'registrar' },
        { data: 'govt_permit_reco', name: 'govt_permit_reco' },
        { data: 'total', name: 'total' },
        { data: 'semester', name: 'semester' },
        { data: 'date_of_graduation', name: 'date_of_graduation' },
     
        // ...other columns...
        { data: 'actions', name: 'actions', orderable: false, searchable: false }
      ],
            });

            // Log DataTable initialization
            console.log('DataTable initialized.');

            // Toastr Options
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // CSRF Token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Inline Editing for text inputs
            $('#soMasterListTable').on('blur', '[contenteditable="true"]', function () {
                let cell = $(this);
                let newValue = cell.text().trim();
                let column = cell.data('column');
                let row = cell.closest('tr');
                let id = row.data('id');

                updateField(id, column, newValue);
            });

            // Inline Editing for select inputs (sex, program, major, semester)
            $('#soMasterListTable').on('change', '.sex-select, .program-select, .major-select, .semester-select', function () {
                let select = $(this);
                let newValue = select.val();
                let column = select.data('column');
                let row = select.closest('tr');
                let id = row.data('id');

                updateField(id, column, newValue);
            });

            // Function to update a field via AJAX
            function updateField(id, column, value) {
                $.ajax({
                    url: `/admin/so_master_lists/${id}`,
                    method: 'PUT',
                    data: {
                        [column]: value
                    },
                    success: function (response) {
                        console.log(`Updated ${column} for ID ${id}`);
                        toastr.success(`Successfully updated ${column}.`);
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        if (errors) {
                            $.each(errors, function (key, messages) {
                                $.each(messages, function (index, message) {
                                    errorMessages += message + '\n';
                                });
                            });
                            toastr.error('Error updating data:\n' + errorMessages);
                        } else {
                            toastr.error('Error updating data.');
                        }
                    }
                });
            }

            // Delete Button
            $('#soMasterListTable').on('click', '.delete-btn', function () {
                if (confirm('Are you sure you want to delete this student?')) {
                    let button = $(this);
                    let id = button.data('id');

                    $.ajax({
                        url: `/admin/so_master_lists/${id}`,
                        method: 'DELETE',
                        success: function () {
                            toastr.success('Student deleted successfully!');
                            // Remove the row from DataTable
                            $('#soMasterListTable').DataTable().row(button.parents('tr')).remove().draw();
                        },
                        error: function () {
                            toastr.error('Error deleting student.');
                        }
                    });
                }
            });

            // Handle Add Student Form Submission via AJAX
            $('#addStudentForm').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                let formData = new FormData(form[0]); // To handle file uploads

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false, // Important for file uploads
                    contentType: false, // Important for file uploads
                    success: function (response) {
                        toastr.success('Student added successfully!');
                        location.reload(); // Reload the page to show the new student
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        if (errors) {
                            $.each(errors, function (key, messages) {
                                $.each(messages, function (index, message) {
                                    errorMessages += message + '\n';
                                });
                            });
                            toastr.error('Error adding student:\n' + errorMessages);
                        } else {
                            toastr.error('Error adding student.');
                        }
                    }
                });
            });

            // Handle opening the Upload Govt Permit Modal
            $('#soMasterListTable').on('click', '.upload-govt-permit-btn', function () {
                let studentId = $(this).data('id');
                $('#uploadStudentId').val(studentId);
                $('#govt_permit_file').val(''); // Reset the file input
            });

            // Handle Upload Govt Permit Form Submission via AJAX
            $('#uploadGovtPermitForm').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                let studentId = $('#uploadStudentId').val();
                let formData = new FormData(form[0]);

                $.ajax({
                    url: `/admin/so_master_lists/${studentId}/upload_govt_permit`,
                    method: 'POST',
                    data: formData,
                    processData: false, // Important for file uploads
                    contentType: false, // Important for file uploads
                    success: function (response) {
                        toastr.success('Government Permit Recommendation uploaded successfully!');
                        $('#uploadGovtPermitModal').modal('hide');
                        location.reload(); // Reload to update the table
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        if (errors) {
                            $.each(errors, function (key, messages) {
                                $.each(messages, function (index, message) {
                                    errorMessages += message + '\n';
                                });
                            });
                            toastr.error('Error uploading attachment:\n' + errorMessages);
                        } else {
                            toastr.error('Error uploading attachment.');
                        }
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
