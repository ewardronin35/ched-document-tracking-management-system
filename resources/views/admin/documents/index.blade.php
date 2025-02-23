@extends('layouts.app')

@section('title', 'Manage Documents')

@push('styles')
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #ffffff;
            color: #343a40;
            font-family: 'Montserrat', sans-serif;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #ffffff;
            padding: 30px;
            margin-bottom: 40px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        /* DataTables Styling */
        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom: none;
        }
        table.dataTable.no-footer {
            border-bottom: none;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem;
            margin-left: 0.25rem;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #343a40 !important;
            cursor: pointer;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #00d084;
            color: #ffffff !important;
            border-color: #00d084;
        }
        /* Redesigned Status Tabs with Icons */
        .nav-tabs#status-tabs {
            border-bottom: none;
        }
        .nav-tabs#status-tabs .nav-link {
            border: none;
            border-radius: 50px;
            margin-right: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            background-color: #f0f0f0;
            color: #343a40;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-tabs#status-tabs .nav-link.active,
        .nav-tabs#status-tabs .nav-link:hover {
            background-color: #00d084;
            color: #fff;
        }
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
        <h2 class="mb-4">Manage Documents</h2>

        <!-- Filters and Bulk Actions -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <!-- Status Filter Tabs -->
            <div class="flex-grow-1">
                <ul class="nav nav-tabs" id="status-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-status="today">
                            <i class="fa fa-calendar-day me-1"></i> Today
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="Pending">
                            <i class="fa fa-hourglass-start me-1"></i> Pending
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="Approved">
                            <i class="fa fa-check me-1"></i> Approved
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="Rejected">
                            <i class="fa fa-times me-1"></i> Rejected
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="">
                            <i class="fa fa-list me-1"></i> All
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="Archived">
                            <i class="fa fa-archive me-1"></i> Archive
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Role Filter and Bulk Actions -->
            <div class="d-flex align-items-center">
                <select id="role-filter" class="form-select form-select-sm me-2">
                    <option value="">All Roles</option>
                    <option value="Supervisor">Supervisor</option>
                    <!-- Add more roles as needed -->
                </select>
                <form id="bulk-action-form" action="{{ route('admin.documents.bulkApproval') }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    <select name="approval_status" class="form-select form-select-sm me-2">
                        <option value="Approved">Approve Selected</option>
                        <option value="Rejected">Reject Selected</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-warning">Apply</button>
                </form>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="card table-container">
            <div class="card-body">
                <table class="table table-striped table-bordered w-100" id="documents-table">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all" /></th>
                            <th>Email</th>
                            <th>Tracking Number</th>
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Status Details</th>
                            <th>Remarks</th>
                            <th>Approval Status</th>
                            <th>Route To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate data via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Document Details Modal -->
    <div class="modal fade" id="documentDetailsModal" tabindex="-1" aria-labelledby="documentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="document-details-content" class="text-center">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading document details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Document Modal -->
    <div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDocumentModalLabel">Edit Document Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="edit-document-modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                        <p class="mt-3">Loading form...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle (includes Popper) -->
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" 
            integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" 
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            var table = $('#documents-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.documents.getDocuments") }}',
                    data: function (d) {
                        var statusFilter = $('#status-tabs button.active').attr('data-status') || '';
                        console.log("Status Filter: " + statusFilter);
                        d.status = statusFilter;
                        d.role = $('#role-filter').val();
                        d.date = (statusFilter === 'today') ? 'today' : '';
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'email', name: 'email' },
                    { data: 'tracking_number', name: 'tracking_number' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'document_type', name: 'document_type' },
                    { data: 'status', name: 'status' },
                    { data: 'status_details', name: 'status_details' },
                    { data: 'remarks', name: 'remarks' },
                    { data: 'approval_status', name: 'approval_status' },
                    { data: 'routed_to', name: 'routed_to' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[1, 'desc']],
                responsive: true,
                language: {
                    search: "Search Documents:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ documents",
                    infoEmpty: "No documents available",
                    infoFiltered: "(filtered from _MAX_ total documents)",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                columnDefs: [
                    { className: "dt-center", targets: "_all" },
                    {
                        targets: 9,
                        render: function(data, type, row) {
                            return data ? data : "Not Assigned";
                        }
                    }
                ],
                drawCallback: function(settings) {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                }
            });

            // Status filter button click event
            $('#status-tabs button').on('click', function() {
                $('#status-tabs button').removeClass('active');
                $(this).addClass('active');
                table.ajax.reload();
            });

            // Role filter change event
            $('#role-filter').on('change', function() {
                table.ajax.reload();
            });

            // Select All Checkbox functionality
            $('#select-all').on('click', function(){
                var isChecked = $(this).is(':checked');
                $('input[name="doc_ids[]"]').prop('checked', isChecked);
            });
            $('#documents-table tbody').on('change', 'input[name="doc_ids[]"]', function(){
                if(!$(this).is(':checked')){
                    $('#select-all').prop('checked', false);
                } else {
                    if ($('input[name="doc_ids[]"]').length === $('input[name="doc_ids[]"]:checked').length){
                        $('#select-all').prop('checked', true);
                    }
                }
            });

            // Handle Bulk Action Form Submission with SweetAlert2
            $('#bulk-action-form').on('submit', function(e){
                e.preventDefault();
                var form = $(this);
                var approvalStatus = form.find('select[name="approval_status"]').val();
                var selectedIds = $('input[name="doc_ids[]"]:checked').map(function(){
                    return $(this).val();
                }).get();

                if(selectedIds.length === 0){
                    Swal.fire({
                        title: 'No Documents Selected',
                        text: 'Please select at least one document.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to " + (approvalStatus === 'Approved' ? 'approve' : 'reject') + " the selected documents.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: {
                                approval_status: approvalStatus,
                                doc_ids: selectedIds,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response){
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    table.ajax.reload();
                                    $('#select-all').prop('checked', false);
                                });
                            },
                            error: function(xhr){
                                var errorMsg = (xhr.responseJSON && xhr.responseJSON.message)
                                    ? xhr.responseJSON.message
                                    : 'An error occurred while processing your request.';
                                Swal.fire({
                                    title: 'Error',
                                    text: errorMsg,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Edit Document Modal Handler
            $(document).on('click', '.edit-document', function(e) {
                e.preventDefault();
                var documentId = $(this).data('id');
                var modal = new bootstrap.Modal(document.getElementById('editDocumentModal'));
                modal.show();
                $.ajax({
                    url: '{{ url("admin/documents/edit-modal") }}/' + documentId,
                    type: 'GET',
                    success: function(response) {
                        $('#edit-document-modal-body').html(response);
                    },
                    error: function() {
                        $('#edit-document-modal-body').html('<p class="text-danger">Failed to load the edit form.</p>');
                    }
                });
            });

            // Optional: Handle AJAX form submission for editing document status
            $(document).on('submit', '#edit-document-form', function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            table.ajax.reload();
                            var modalEl = document.getElementById('editDocumentModal');
                            var modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred while saving changes.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // View Document Modal Handler
            $('#documents-table').on('click', '.view-document', function(e) {
                e.preventDefault();
                var documentId = $(this).data('id');
                var modal = new bootstrap.Modal(document.getElementById('documentDetailsModal'), { keyboard: false });
                modal.show();
                $('#document-details-content').html(`
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading document details...</p>
                    </div>
                `);
                $.ajax({
                    url: '{{ route("admin.documents.details", ":id") }}'.replace(':id', documentId),
                    type: 'GET',
                    success: function(data) {
                        $('#document-details-content').html(data);
                    },
                    error: function() {
                        $('#document-details-content').html('<p class="text-danger">Failed to load document details.</p>');
                    }
                });
            });
        });
    </script>
@endpush
