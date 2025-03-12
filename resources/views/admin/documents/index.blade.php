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
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Custom Styles -->
    <style>
   /* Enhanced Status Filter Styles */
   .document-status-filters {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .status-filter-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border: 2px solid transparent;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
        gap: 0.5rem;
        cursor: pointer;
    }

    .status-filter-btn:hover {
        background-color: #e9ecef;
    }

    .status-filter-btn.active {
        background-color: #007bff;
        color: white;
        border-color: #0056b3;
    }

    .status-filter-btn i {
        margin-right: 0.25rem;
        opacity: 0.7;
    }

    .status-filter-btn.active i {
        opacity: 1;
    }

    /* Color variations for different statuses */
    .status-filter-btn[data-status="today"] {
        background-color: #e6f2ff;
        color: #0366d6;
    }

    .status-filter-btn[data-status="today"].active {
        background-color: #0366d6;
        color: white;
    }

    .status-filter-btn[data-status="Pending"] {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-filter-btn[data-status="Pending"].active {
        background-color: #ffc107;
        color: white;
    }

    .status-filter-btn[data-status="Approved"] {
        background-color: #d4edda;
        color: #155724;
    }

    .status-filter-btn[data-status="Approved"].active {
        background-color: #28a745;
        color: white;
    }

    .status-filter-btn[data-status="Rejected"] {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-filter-btn[data-status="Rejected"].active {
        background-color: #dc3545;
        color: white;
    }

    .status-filter-btn[data-status=""] {
        background-color: #e9ecef;
        color: #495057;
    }

    .status-filter-btn[data-status=""].active {
        background-color: #6c757d;
        color: white;
    }

    .status-filter-btn[data-status="Archived"] {
        background-color: #e2e6ea;
        color: #343a40;
    }

    .status-filter-btn[data-status="Archived"].active {
        background-color: #343a40;
        color: white;
    }

    @media (max-width: 768px) {
        .document-status-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .status-filter-btn {
            justify-content: center;
            width: 100%;
        }
    }
    .reports-section {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: none;
    }

    .report-card {
        background-color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .report-card-icon {
        font-size: 2.5rem;
        color: #007bff;
        margin-bottom: 15px;
    }

    .report-filters {
        background-color: #f1f3f5;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .report-export-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .report-export-btn:hover {
        transform: scale(1.05);
    }

    .chart-container {
        position: relative;
        height: 300px;
    }
    .status-filter-btn[data-status="Reports"].active + .reports-section {
        display: block;
    }
    
    /* FilePond custom styles */
    .filepond--root {
        margin-bottom: 0;
    }
    .filepond--panel-root {
        background-color: #f8f9fa;
        border: 1px dashed #ced4da;
    }
    .filepond--drop-label {
        color: #6c757d;
    }
    .filepond--label-action {
        text-decoration-color: #007bff;
    }
    
    /* Input validation styles */
    .is-valid {
        border-color: #28a745 !important;
        padding-right: calc(1.5em + 0.75rem) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: center right calc(0.375em + 0.1875rem);
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
        padding-right: calc(1.5em + 0.75rem) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E");
        background-repeat: no-repeat;
        background-position: center right calc(0.375em + 0.1875rem);
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }
    
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
</style>
@endpush

@section('content')

        <!-- Filters and Bulk Actions -->
        <div class="d-flex align-items-center justify-content-between mb-3">
    <div class="document-status-filters flex-grow-1">
        <button class="status-filter-btn active" data-status="today">
            <i class="fa fa-calendar-day"></i> Today
        </button>
        <button class="status-filter-btn" data-status="Pending">
            <i class="fa fa-hourglass-start"></i> Pending
        </button>
        <button class="status-filter-btn" data-status="Approved">
            <i class="fa fa-check"></i> Approved
        </button>
        <button class="status-filter-btn" data-status="Rejected">
            <i class="fa fa-times"></i> Rejected
        </button>
        <button class="status-filter-btn" data-status="">
            <i class="fa fa-list"></i> All
        </button>
        <button class="status-filter-btn" data-status="Archived">
            <i class="fa fa-archive"></i> Archive
        </button>
        <button class="status-filter-btn" data-status="Reports">
    <i class="fa fa-file-alt"></i> Reports
</button>
<button class="status-filter-btn" data-status="Released">
    <i class="fa fa-check-circle"></i> Released
</button>
<button class="status-filter-btn" data-status="Create">
    <i class="fa fa-upload"></i> Upload Document
</button>

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
                        <option value="Accepted">Approve Selected</option>
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
    <div class="reports-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Document Reports & Analytics
                    </h2>
                </div>
            </div>

            <!-- Reporting Filters -->
            <div class="report-filters">
                <div class="row">
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Quarter</label>
                        <select id="report-quarter" class="form-select">
                            <option value="">All Quarters</option>
                            <option value="1">Q1 (Jan-Mar)</option>
                            <option value="2">Q2 (Apr-Jun)</option>
                            <option value="3">Q3 (Jul-Sep)</option>
                            <option value="4">Q4 (Oct-Dec)</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Export Type</label>
                        <select id="export-type" class="form-select">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Document Status</label>
                        <select id="report-status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button id="generate-report" class="btn btn-primary w-100 report-export-btn">
                            <i class="fas fa-file-export"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Report Cards -->
            <div class="row">
                <!-- Quarterly Overview -->
                <div class="col-md-4 mb-4">
                    <div class="report-card">
                        <div class="text-center">
                            <i class="fas fa-chart-pie report-card-icon"></i>
                            <h5>Quarterly Overview</h5>
                        </div>
                        <div class="chart-container">
                            <canvas id="quarterlyDocumentsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Document Type Distribution -->
                <div class="col-md-4 mb-4">
                    <div class="report-card">
                        <div class="text-center">
                            <i class="fas fa-file-alt report-card-icon"></i>
                            <h5>Document Types</h5>
                        </div>
                        <div class="chart-container">
                            <canvas id="documentTypesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="col-md-4 mb-4">
                    <div class="report-card">
                        <div class="text-center">
                            <i class="fas fa-chart-line report-card-icon"></i>
                            <h5>Monthly Trend</h5>
                        </div>
                        <div class="chart-container">
                            <canvas id="monthlyDocumentsChart"></canvas>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="upload-document-section" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-file-upload me-2"></i> Upload New Document</h5>
            </div>
            <div class="card-body">
                <form id="upload-document-form" action="{{ route('admin.documents.direct-upload') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                            <div class="invalid-feedback">
                                Please enter the full name.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                placeholder="+63XXXXXXXXXX" required>
                            <div class="invalid-feedback">
                                Please enter a valid phone number (format: +63XXXXXXXXXX).
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="document_type" name="document_type" required>
                                <option value="">-- Select Document Type --</option>
                                <option value="Application Form">Application Form</option>
                                <option value="Transcript of Records">Transcript of Records</option>
                                <option value="Certificate">Certificate</option>
                                <option value="Letter">Letter</option>
                                <option value="Request Form">Request Form</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a document type.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Initial Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Under Review">Under Review</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Released">Released</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="approval_status" class="form-label">Approval Status</label>
                            <select class="form-select" id="approval_status" name="approval_status">
                                <option value="Pending">Pending</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="routed_to" class="form-label">Route To (Optional)</label>
                            <select class="form-select" id="routed_to" name="routed_to">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->getRoleNames()->first() }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="document" class="form-label">Document File <span class="text-danger">*</span></label>
                            <input type="file" class="filepond" id="document" name="document" required>
                            <div class="invalid-feedback">
                                Please select a document file.
                            </div>
                            <small class="text-muted">Max file size: 5MB. Allowed formats: PDF, DOC, DOCX, PNG, JPG</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                            placeholder="Enter any additional notes or remarks about this document"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" id="cancel-upload">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Document Details Modal -->
  
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" 
            integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" 
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
<!-- Custom Scripts -->

<script>

// Replace the existing JavaScript section in your page with this corrected version

$(document).ready(function() {
    // Initialize DataTable with server-side processing
    var table = $('#documents-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: document.location.origin + '/admin/documents/getDocuments',
            data: function (d) {
                var statusFilter = $('.status-filter-btn.active').attr('data-status') || '';
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

    // Function to toggle between reports section and table
    function toggleSections(activeTab) {
    if (activeTab === 'Reports') {
        $('.table-container').hide(); // Hide table
        $('.reports-section').show(); // Show reports section
    } else if (activeTab === 'Create') {
        $('.table-container').hide(); // Hide table
        $('.reports-section').hide(); // Hide reports section
        // Show upload form if you have one
        $('.upload-document-section').show(); 
    } else {
        $('.table-container').show(); // Show table
        $('.reports-section').hide(); // Hide reports section
        $('.upload-document-section').hide(); // Hide upload form
    }
}
// Initialize FilePond for document upload
FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );
        
        const pond = FilePond.create(document.querySelector('.filepond'), {
            acceptedFileTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/png', 'image/jpeg'],
            maxFileSize: '5MB',
            labelIdle: 'Drag & Drop your document or <span class="filepond--label-action">Browse</span>',
            labelFileTypeNotAllowed: 'File type not allowed. Allowed types: PDF, DOC, DOCX, PNG, JPG',
            labelMaxFileSizeExceeded: 'File is too large. Max size is 5MB',
            storeAsFile: true
        });

        // Form validation functions
        function validateEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        
        function validatePhoneNumber(phone) {
            const re = /^\+63\d{10}$/;
            return re.test(String(phone));
        }
        
        // Add field validation on input
        $('#email').on('input', function() {
            const email = $(this).val();
            if (validateEmail(email)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
        
        $('#phone_number').on('input', function() {
            const phone = $(this).val();
            if (validatePhoneNumber(phone)) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
        
        $('#full_name').on('input', function() {
            if ($(this).val().trim().length > 0) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
        
        $('#document_type').on('change', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
        
        // Form submission validation
  // Document upload form submission with SweetAlert
$('#upload-document-form').on('submit', function(e) {
    e.preventDefault();
    
    // Validate form
    let isValid = true;
    
    // Validate email
    if (!validateEmail($('#email').val())) {
        $('#email').removeClass('is-valid').addClass('is-invalid');
        isValid = false;
    }
    
    // Validate phone
    if (!validatePhoneNumber($('#phone_number').val())) {
        $('#phone_number').removeClass('is-valid').addClass('is-invalid');
        isValid = false;
    }
    
    // Validate name
    if ($('#full_name').val().trim().length === 0) {
        $('#full_name').removeClass('is-valid').addClass('is-invalid');
        isValid = false;
    }
    
    // Validate document type
    if (!$('#document_type').val()) {
        $('#document_type').removeClass('is-valid').addClass('is-invalid');
        isValid = false;
    }
    
    // Check if file is added
    if (!pond.getFile()) {
        isValid = false;
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please upload a document file.'
        });
        return;
    }
    
    if (!isValid) {
        // Scroll to the first invalid field
        $('html, body').animate({
            scrollTop: $('.is-invalid:first').offset().top - 100
        }, 200);
        return;
    }
    
    // Show SweetAlert loading state
    Swal.fire({
        title: 'Uploading Document',
        html: `
            <div class="text-center">
                <i class="fas fa-file-upload fa-3x mb-3 text-primary"></i>
                <div class="progress mb-3" style="height: 20px;">
                    <div id="upload-progress-bar" 
                         class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                         role="progressbar" style="width: 0%" 
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <p id="upload-status">Preparing to upload...</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
            
            // Simulate progress (in a real app, connect to actual upload progress)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 10;
                if (progress > 100) progress = 100;
                
                const progressBar = document.getElementById('upload-progress-bar');
                const statusText = document.getElementById('upload-status');
                
                if (progressBar) {
                    progressBar.style.width = progress + '%';
                    progressBar.setAttribute('aria-valuenow', progress);
                    progressBar.textContent = Math.round(progress) + '%';
                }
                
                if (statusText) {
                    if (progress < 30) {
                        statusText.textContent = 'Preparing document...';
                    } else if (progress < 60) {
                        statusText.textContent = 'Uploading to server...';
                    } else if (progress < 90) {
                        statusText.textContent = 'Processing document...';
                    } else {
                        statusText.textContent = 'Almost done...';
                    }
                }
                
                if (progress >= 100) {
                    clearInterval(progressInterval);
                }
            }, 300);
            
            // Submit form data via AJAX
            const formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    clearInterval(progressInterval);
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Document Uploaded',
                        text: 'Your document has been successfully uploaded.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reset form
                        $('#upload-document-form')[0].reset();
                        pond.removeFiles();
                        $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                        
                        // Switch back to Today tab
                        $('.status-filter-btn[data-status="today"]').trigger('click');
                    });
                },
                error: function(xhr) {
                    clearInterval(progressInterval);
                    
                    // Show error message
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) 
                        ? xhr.responseJSON.message 
                        : 'An error occurred while uploading the document.';
                        
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: errorMsg,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});
    // Prepare the report URL with proper error handling
    var reportUrl = document.location.origin + '/admin/documents/get-report-statistics';
    reportUrl += '?_=' + new Date().getTime(); // Add timestamp to prevent caching

    // Chart initialization function
    function initializeCharts(data) {
    // Safety check for data
    if (!data) {
        console.error('No data received for charts');
        Swal.fire({
            icon: 'error',
            title: 'Data Loading Error',
            text: 'Could not load report data. Please try again.'
        });
        return;
    }

    // More robust checks for data structure
    let quarterlyData = data.quarterlyDocuments || [];
    if (!Array.isArray(quarterlyData) || quarterlyData.length === 0) {
        quarterlyData = [
            {quarter: 1, total_documents: 0, accepted_documents: 0},
            {quarter: 2, total_documents: 0, accepted_documents: 0},
            {quarter: 3, total_documents: 0, accepted_documents: 0},
            {quarter: 4, total_documents: 0, accepted_documents: 0}
        ];
    }

    let documentTypeData = data.documentTypeDistribution || [];
    if (!Array.isArray(documentTypeData) || documentTypeData.length === 0) {
        documentTypeData = [{document_type: 'No Data', total_count: 0}];
    }

    // Initialize quarterly documents chart
    const quarterlyCtx = document.getElementById('quarterlyDocumentsChart');
    if (quarterlyCtx) {
        // Safely destroy existing chart if it exists
        if (window.quarterlyChart && typeof window.quarterlyChart.destroy === 'function') {
            window.quarterlyChart.destroy();
        }
        
        // Create new chart
        window.quarterlyChart = new Chart(quarterlyCtx, {
            type: 'bar',
            data: {
                labels: quarterlyData.map(q => `Q${q.quarter}`),
                datasets: [{
                    label: 'Total Documents',
                    data: quarterlyData.map(q => q.total_documents || 0),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Approved Documents',
                    data: quarterlyData.map(q => q.accepted_documents || 0),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Initialize document types chart
    const documentTypesCtx = document.getElementById('documentTypesChart');
    if (documentTypesCtx) {
        // Safely destroy existing chart if it exists
        if (window.documentTypesChart && typeof window.documentTypesChart.destroy === 'function') {
            window.documentTypesChart.destroy();
        }
        
        // Create new chart
        window.documentTypesChart = new Chart(documentTypesCtx, {
            type: 'pie',
            data: {
                labels: documentTypeData.map(d => d.document_type || 'Unknown'),
                datasets: [{
                    data: documentTypeData.map(d => d.total_count || 0),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Initialize monthly trend chart
    const monthlyDocumentsChart = document.getElementById('monthlyDocumentsChart');
    if (monthlyDocumentsChart) {
        // Safely destroy existing chart if it exists
        if (window.monthlyTrendChart && typeof window.monthlyTrendChart.destroy === 'function') {
            window.monthlyTrendChart.destroy();
        }
        
        // Create new chart
        window.monthlyTrendChart = new Chart(monthlyDocumentsChart, {
            type: 'line',
            data: {
                labels: quarterlyData.map(q => `Q${q.quarter}`),
                datasets: [{
                    label: 'Total Documents',
                    data: quarterlyData.map(q => q.total_documents || 0),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true
                }, {
                    label: 'Approved Documents',
                    data: quarterlyData.map(q => q.accepted_documents || 0),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}
$('#cancel-upload').on('click', function() {
        // Switch back to 'Today' tab
        $('.status-filter-btn[data-status="today"]').trigger('click');
    });
    // Status filter button click event - only one implementation
    $('.status-filter-btn').on('click', function() {
        $('.status-filter-btn').removeClass('active');
        $(this).addClass('active');
        var activeTab = $(this).attr('data-status');
        
        // Toggle sections based on active tab
        toggleSections(activeTab);
        
        // Only fetch report data when Reports tab is clicked
        if (activeTab === 'Reports') {
            // Show loading state
            Swal.fire({
                title: 'Loading Reports',
                text: 'Please wait while we generate the reports...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Fetch and initialize charts when Reports tab is clicked
            $.ajax({
                url: reportUrl,
                method: 'GET',
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    console.log('AJAX Success:', data);
                    Swal.close();
                    initializeCharts(data);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error Details:', {
                        status: xhr.status,
                        responseText: xhr.responseText.substring(0, 100),
                        statusText: xhr.statusText,
                        error: error
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Loading Reports',
                        text: 'There was a problem loading the report data. Please try again later.',
                        footer: 'If this problem persists, please contact IT support.'
                    });
                }
            });
        } else {
            // For non-report tabs, refresh the DataTable
            table.ajax.reload();
        }
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
    
    // Checkbox change event handler
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
            text: "You are about to " + (approvalStatus === 'Accepted' ? 'approve' : 'reject') + " the selected documents.",
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
                        _token: $('meta[name="csrf-token"]').attr('content')
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
        
        // Check if modal exists before trying to show it
        var modalEl = document.getElementById('editDocumentModal');
        if (!modalEl) {
            console.error('Edit document modal element not found');
            return;
        }
        
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
        
        $.ajax({
            url: document.location.origin + '/admin/documents/edit-modal/' + documentId,
            type: 'GET',
            success: function(response) {
                $('#edit-document-modal-body').html(response);
            },
            error: function() {
                $('#edit-document-modal-body').html('<p class="text-danger">Failed to load the edit form.</p>');
            }
        });
    });

    // Handle AJAX form submission for editing document status
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
                    if (modalEl) {
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
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
        var modalEl = document.getElementById('documentDetailsModal');
        
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl, { keyboard: false });
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
                url: document.location.origin + '/admin/documents/' + documentId + '/details',
                type: 'GET',
                success: function(data) {
                    $('#document-details-content').html(data);
                },
                error: function() {
                    $('#document-details-content').html('<p class="text-danger">Failed to load document details.</p>');
                }
            });
        } else {
            console.error('Modal element not found');
        }
    });

    // Generate Report functionality
    $('#generate-report').on('click', function() {
        const quarter = $('#report-quarter').val();
        const exportType = $('#export-type').val();
        const status = $('#report-status').val();

        // Redirect to export route with parameters
        window.location.href = document.location.origin + '/admin/documents/generate-report?quarter=' + quarter + '&export_type=' + exportType + '&status=' + status;
    });

    // Initially hide the reports section
    $('.reports-section').hide();
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
                        _token: $('meta[name="csrf-token"]').attr('content')
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
  
    // Handle AJAX form submission for editing document status
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
        var modalEl = document.getElementById('documentDetailsModal');
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl, { keyboard: false });
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
        } else {
            console.error('Modal element not found');
        }
    });

    // Generate Report functionality
    $('#generate-report').on('click', function() {
        const quarter = $('#report-quarter').val();
        const exportType = $('#export-type').val();
        const status = $('#report-status').val();

        // Redirect to export route with parameters
        window.location.href = '{{ route("admin.documents.generate-report") }}?quarter=' + quarter + '&export_type=' + exportType + '&status=' + status;
    });

    // Initially hide the reports section
    $('.reports-section').hide();



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

            // Generate Report functionality
            $('#generate-report').on('click', function() {
                const quarter = $('#report-quarter').val();
                const exportType = $('#export-type').val();
                const status = $('#report-status').val();

                // Redirect to export route with parameters
                window.location.href = `{{ route('admin.documents.generate-report') }}?quarter=${quarter}&export_type=${exportType}&status=${status}`;
            });
    
    </script>
@endpush