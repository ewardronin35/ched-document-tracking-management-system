@extends('layouts.app')

@section('title', 'CDTMS Admin Dashboard')

@push('styles')

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          crossorigin="anonymous" 
          referrerpolicy="no-referrer" />
          <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
    <style>
     /* Global Styles */
html, body {
    margin: 0;
    padding: 0;
    /* Removed forced 100% height to allow the content to flow naturally */
    font-family: 'Montserrat', sans-serif;
    background-color: #f0f2f5;
    color: #343a40;
}

a {
    text-decoration: none;
    color: inherit;
}

/* Dashboard Layout */
.dashboard {
    display: flex;
    flex-direction: column;
    /* Removed fixed height to improve responsiveness */
    min-height: 100vh; /* Use min-height if you want a full-viewport feel */
}

.dashboard-header {
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    padding: 0 20px;
    /* Let the header height adapt to content or use a standard Bootstrap class (e.g. py-2) */
    height: 60px;
}

.dashboard-header h1 {
    font-size: 1.5rem;
    margin: 0;
    color: #343a40;
}

/* Main Content */
.dashboard-main {
    /* Removed overflow: hidden and flex: 1 so content can expand vertically */
    padding: 20px;
}

/* Wrap main content to allow internal scrolling if needed */
.content-wrapper {
    /* Removed fixed height, let it grow naturally */
    width: 100%;
}

/* Card Base Style */
.card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 20px;
    margin-bottom: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.card h2 {
    font-size: 1.2rem;
    margin-bottom: 15px;
    color: #343a40;
}

/* Welcome Section */
.welcome-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.welcome-flex > div:first-child {
    flex: 1;
    padding-right: 20px;
}

.welcome-flex img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
}

/* Stats Cards Row */
.cards-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    /* Allow wrapping on smaller screens */
    flex-wrap: wrap;
}

.cards-row .card {
    flex: 1;
    /* Optional: set a min-width so cards don't shrink too small on mobile 
       e.g., min-width: 250px; */
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #00d084;
    margin: 0;
}

.stats-description {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

/* Chart & Deadlines Section */
.overview-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap; /* Ensure it wraps on smaller screens */
}

.overview-section .card {
    flex: 1;
    /* Optional min-width if desired */
}

/* Chart Container */
.chart-container {
    position: relative;
    /* Give a flexible height that looks good on various screens */
    height: 300px;
    width: 100%;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Deadlines Card */
.deadlines-card ul {
    list-style: none;
    padding: 0;
    max-height: 300px;
    overflow-y: auto;
    margin: 0; /* reset default list margins */
}

.deadlines-card li {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s;
}

.deadlines-card li:hover {
    background: #e2e6ea;
}

.deadline-date {
    font-weight: 600;
    color: #00d084;
}

/* Tables Section */
.tables-section {
    margin-bottom: 20px;
}

/* Table Card */
.table-card {
    overflow: hidden; /* You can remove this if you want horizontal scroll on smaller screens */
}

.table-card .table-responsive {
    /* Manage height with auto or remove max-height to let it expand fully */
    max-height: 300px;
    overflow-y: auto;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
}

table thead {
    background: #00d084;
    color: #fff;
}

table thead th {
    padding: 10px;
    text-align: left;
    font-weight: 600;
}

table tbody td {
    padding: 10px;
    border-bottom: 1px solid #f1f3f5;
}

table tbody tr:nth-child(even) {
    background: #f8f9fa;
}

/* Badges */
.badge {
    font-size: 0.85rem;
    padding: 4px 8px;
    border-radius: 8px;
}

/* Text end alignment */
.text-end {
    text-align: right;
}

/* Modal Styling */
.modal-content {
    background: #fff;
    color: #343a40;
    border: none;
    border-radius: 16px;
}

.modal-header {
    border-bottom: 1px solid #e0e0e0;
}

.modal-footer {
    border-top: 1px solid #e0e0e0;
}

/* Responsive Adjustments */
/* Stacking columns/cards at smaller breakpoints */
@media (max-width: 992px) {
    .welcome-flex {
        flex-direction: column;
        text-align: center;
    }

    .welcome-flex > div:first-child {
        padding-right: 0;
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    /* Additional stacking if needed */
    .cards-row,
    .overview-section {
        flex-direction: column;
    }
    
    /* Adjust image size if desired */
    .welcome-flex img {
        width: 100px;
        height: 100px;
    }
}
    </style>
@endpush

@section('content')

    <!-- Header -->

    <!-- Main Content -->
   

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">CDTMS AdminDashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Pending Approvals</div>
                    <p class="stats-number" aria-label="Pending Approvals">{{ number_format($pendingApprovals) }}</p>
                    <p class="stats-description">Documents awaiting action</p>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Upcoming Deadlines</div>
                            
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @forelse($upcomingDeadlines as $deadline)
                                <span class="deadline-date">
                                    {{ \Carbon\Carbon::parse($deadline->deadline_date)->format('F d, Y') }}
                                </span>
                        @empty
                            <p>No upcoming deadlines.</p>
                        @endforelse
                    </div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <table id="recentDocumentsTable" class="table table-hover" aria-describedby="recent-documents-description">
                            <thead>
                                <tr>
                                    <th>Document Name</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDocuments as $document)
                                    <tr>
                                        <td>{{ basename($document->file_path) }}</td>
                                        <td>
                                            @switch($document->status)
                                                @case('Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                    @break
                                                @case('Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                    @break
                                                @case('Rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                    @break
                                                @case('Released')
                                                    <span class="badge bg-success">Released</span>
                                                    @break
                                                @case('Archived')   
                                                    <span class="badge bg-secondary">Archived</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($document->updated_at)->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.documents.details', $document->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               aria-label="View details of {{ basename($document->file_path) }}">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No documents found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Referral
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Server Migration <span
                        class="float-right">20%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Sales Tracking <span
                        class="float-right">40%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Customer Database <span
                        class="float-right">60%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 60%"
                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Payout Details <span
                        class="float-right">80%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Account Setup <span
                        class="float-right">Complete!</span></h4>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

     

    </div>

    <div class="col-lg-6 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                        src="img/undraw_posting_photo.svg" alt="...">
                </div>
                <p>Add some quality, svg illustrations to your project courtesy of <a
                        target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                    constantly updated collection of beautiful svg images that you can use
                    completely free and without attribution!</p>
                <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                    unDraw &rarr;</a>
            </div>
        </div>

        <!-- Approach -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
            </div>
            <div class="card-body">
                <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                    CSS bloat and poor page performance. Custom CSS classes are used to create
                    custom components and custom utility classes.</p>
                <p class="mb-0">Before working with this theme, you should become familiar with the
                    Bootstrap framework, especially the utility classes.</p>
            </div>
        </div>

    </div>
</div>

</div>
<!-- /.container-fluid -->

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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" 
            crossorigin="anonymous" 
            referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Monthly Submissions Chart
            const ctx = document.getElementById('documentChart').getContext('2d');
            const documentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyDocuments->pluck('month')) !!},
                    datasets: [{
                        label: 'Documents Submitted',
                        data: {!! json_encode($monthlyDocuments->pluck('count')) !!},
                        backgroundColor: 'rgba(0, 211, 132, 0.2)',
                        borderColor: '#00d084',
                        borderWidth: 2,
                        pointBackgroundColor: '#00d084',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#00d084',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#343a40',
                                font: { size: 14, weight: '600' }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#343a40',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#00d084',
                            borderWidth: 1,
                            cornerRadius: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Documents',
                                color: '#343a40',
                                font: { size: 14, weight: '600' }
                            },
                            grid: { color: '#dee2e6' },
                            ticks: { color: '#343a40' }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month',
                                color: '#343a40',
                                font: { size: 14, weight: '600' }
                            },
                            grid: { color: '#dee2e6' },
                            ticks: { color: '#343a40' }
                        }
                    }
                }
            });

            // Initialize DataTable for Recent Documents
            $('#recentDocumentsTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                order: [[2, 'desc']],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ documents",
                    infoEmpty: "No documents available",
                    infoFiltered: "(filtered from _MAX_ total documents)",
                    paginate: { previous: "Previous", next: "Next" }
                },
                columnDefs: [{ orderable: false, targets: [3] }]
            });

            // Initialize DataTable for User Management
            $('#userManagementTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                order: [[3, 'desc']],
                language: {
                    search: "Search Users:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    infoEmpty: "No users available",
                    infoFiltered: "(filtered from _MAX_ total users)",
                    paginate: { previous: "Previous", next: "Next" }
                },
                columnDefs: [{ orderable: false, targets: [4] }]
            });

            // Handle "View" Document button (AJAX Modal)
            $('.table-card').on('click', '.btn-primary', function(e) {
                e.preventDefault();
                const documentId = $(this).attr('href').split('/').pop();
                const modal = new bootstrap.Modal(document.getElementById('documentDetailsModal'), { keyboard: false });
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

            // Handle Edit and Delete User actions
            $('.table-card').on('click', '.btn-secondary, .btn-danger', function(e) {
                e.preventDefault();
                const action = $(this).hasClass('btn-secondary') ? 'edit' : 'delete';
                const userId = $(this).attr('href').split('/').pop();
                if (action === 'edit') {
                    window.location.href = '{{ route("admin.manage.users.edit", ":id") }}'.replace(':id', userId);
                } else if (action === 'delete') {
                    if (confirm('Are you sure you want to delete this user?')) {
                        window.location.href = '{{ route("admin.manage.users.destroy", ":id") }}'.replace(':id', userId);
                    }
                }
            });
        });
    </script>
@endpush
