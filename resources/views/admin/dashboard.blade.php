@extends('layouts.app')

@section('title', 'CDTMS Admin Dashboard')

@push('styles')
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          crossorigin="anonymous" 
          referrerpolicy="no-referrer" />
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eaefff;
            --secondary: #3f37c9;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
            --info: #3498db;
            --dark: #1e293b;
            --gray: #94a3b8;
            --light: #f8fafc;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            line-height: 1.6;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-card .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: white;
            font-size: 1.2rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0.5rem 0;
            color: var(--dark);
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            color: var(--gray);
            font-weight: 500;
        }

        .accent-primary { background-color: var(--primary); }
        .accent-success { background-color: var(--success); }
        .accent-warning { background-color: var(--warning); }
        .accent-info { background-color: var(--info); }

        /* Main Content Cards */
        .content-card {
            border-radius: 10px;
            box-shadow: var(--shadow);
            background: white;
            border: none;
            margin-bottom: 1.5rem;
        }

        .content-card .card-header {
            background: white;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark);
        }

        .content-card .card-body {
            padding: 1.5rem;
        }

        /* Badge styling */
        .badge {
            padding: 0.35em 0.65em;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-pending {
            background-color: #fff4de;
            color: #ff9f43;
        }

        .badge-approved {
            background-color: #e6f7ee;
            color: #28c76f;
        }

        .badge-rejected {
            background-color: #fee8e6;
            color: #ea5455;
        }

        .badge-released {
            background-color: #d8f5ff;
            color: #00cfe8;
        }

        .badge-archived {
            background-color: #eeeef4;
            color: #8a8aaa;
        }

        /* Table styling */
        .table > :not(caption) > * > * {
            padding: 0.75rem 1.25rem;
            vertical-align: middle;
        }

        .table thead th {
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .deadline-date {
            font-weight: 600;
            color: var(--info);
        }

        /* Dark mode toggle */
        .dark-mode-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .dark-mode-toggle:hover {
            background-color: var(--light);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .stat-card .stat-value {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="d-flex align-items-center">
            <span class="me-3">{{ now()->format('F d, Y') }}</span>
            <div class="dark-mode-toggle">
                <i class="fas fa-moon"></i>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Pending Approvals Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Pending Approvals</p>
                            <h2 class="stat-value">{{ number_format($pendingApprovals) }}</h2>
                            <p class="mb-0 text-muted small">Documents awaiting action</p>
                        </div>
                        <div class="icon-box accent-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Deadlines Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">Deadlines</p>
                            <h2 class="stat-value">{{ $upcomingDeadlines->count() }}</h2>
                            @if($upcomingDeadlines->count() > 0)
                                <p class="mb-0 text-muted small">Next: 
                                    <span class="deadline-date">
                                        {{ \Carbon\Carbon::parse($upcomingDeadlines->first()->deadline_date)->format('M d') }}
                                    </span>
                                </p>
                            @else
                                <p class="mb-0 text-muted small">No upcoming deadlines</p>
                            @endif
                        </div>
                        <div class="icon-box accent-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SO MasterList Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">SO MasterList</p>
                            <h2 class="stat-value">{{ number_format($soMasterListCount) }}</h2>
                            <p class="mb-0 text-muted small">Total documents</p>
                        </div>
                        <div class="icon-box accent-info">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CAV Card -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label">CAV</p>
                            <h2 class="stat-value">{{ number_format($cavCount) }}</h2>
                            <p class="mb-0 text-muted small">Total certificates</p>
                        </div>
                        <div class="icon-box accent-success">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Main Column -->
        <div class="col-xl-8">
            <!-- Recent Documents Table -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Recent Documents</h5>
                    <div>
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Add New
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="recentDocumentsTable" class="table align-middle" aria-describedby="recent-documents-description">
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-file-pdf text-danger fa-lg"></i>
                                                </div>
                                                <div>
                                                    {{ basename($document->file_path) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
    @switch(strtolower($document->status))
        @case('approved')
            <span class="badge badge-approved">Approved</span>
            @break
        @case('pending')
            <span class="badge badge-pending">Pending</span>
            @break
        @case('rejected')
            <span class="badge badge-rejected">Rejected</span>
            @break
        @case('released')
            <span class="badge badge-released">Released</span>
            @break
        @case('archived')   
            <span class="badge badge-archived">Archived</span>
            @break
        @default
            <span class="badge bg-secondary">{{ $document->status }}</span>
    @endswitch
</td>
                                        <td>{{ \Carbon\Carbon::parse($document->updated_at)->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.documents.details', $document->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               aria-label="View details of {{ basename($document->file_path) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                                <p>No documents found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Document Activity Chart -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Document Activity Trend</h5>
                    <div>
                        <span class="badge bg-success me-2">
                            <i class="fas fa-check me-1"></i> {{ number_format($approvedThisMonth) }} Approved This Month
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="documentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Column -->
        <div class="col-xl-4">
            <!-- Document Types Chart -->
            <div class="content-card mb-4">
                <div class="card-header">
                    <h5>Document Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="documentTypesChart"></canvas>
                    </div>
                    <div class="text-center text-muted small mt-3">
                        Total Documents: {{ number_format($totalDocuments) }}
                    </div>
                </div>
            </div>

            <!-- Users & Roles -->
            <div class="content-card">
                <div class="card-header">
                    <h5>Users & Roles</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($users as $user)
                            <li class="list-group-item px-3 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                                alt="{{ $user->name }}" 
                                                class="rounded-circle" 
                                                width="36" height="36">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                style="width: 36px; height: 36px; font-size: 14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <p class="text-muted mb-0 small">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-secondary">No Role</span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-3 py-4 text-center">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <p>No users found</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
               
            </div>
        </div>
    </div>
</div>

<!-- Document Details Modal -->
<div class="modal fade" id="documentDetailsModal" tabindex="-1" aria-labelledby="documentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
            // Sort months chronologically
            const monthOrder = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            
            // Process monthly document data for the chart
            const monthlyData = {!! json_encode($monthlyDocuments) !!};
            const sortedMonthlyData = [...monthlyData].sort((a, b) => {
                return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
            });
            
            const monthLabels = sortedMonthlyData.map(item => item.month);
            const documentCounts = sortedMonthlyData.map(item => item.count);
            
            // Get current year for chart title
            const currentYear = new Date().getFullYear();
            
            // Initialize Monthly Document Activity Chart
            const ctx = document.getElementById('documentChart').getContext('2d');
            const documentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Documents Submitted',
                        data: documentCounts,
                        backgroundColor: 'rgba(67, 97, 238, 0.2)',
                        borderColor: '#4361ee',
                        borderWidth: 2,
                        pointBackgroundColor: '#4361ee',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4361ee',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#4361ee',
                            borderWidth: 1,
                            cornerRadius: 6,
                            padding: 10,
                            callbacks: {
                                title: function(tooltipItems) {
                                    return tooltipItems[0].label + ' ' + currentYear;
                                }
                            }
                        },
                        title: {
                            display: false,
                            text: 'Document Submissions ' + currentYear,
                            font: {
                                size: 16
                            },
                            padding: {
                                top: 10,
                                bottom: 10
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { 
                                color: '#e2e8f0',
                                drawBorder: false
                            },
                            ticks: { 
                                color: '#94a3b8',
                                padding: 10,
                                precision: 0,
                                callback: function(value) {
                                    if (value % 1 === 0) {
                                        return value;
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Number of Documents',
                                color: '#94a3b8',
                                font: {
                                    size: 12,
                                    weight: 'normal'
                                },
                                padding: {
                                    bottom: 10
                                }
                            }
                        },
                        x: {
                            grid: { 
                                display: false,
                                drawBorder: false
                            },
                            ticks: { 
                                color: '#94a3b8',
                                padding: 10
                            },
                            title: {
                                display: true,
                                text: 'Month',
                                color: '#94a3b8',
                                font: {
                                    size: 12,
                                    weight: 'normal'
                                },
                                padding: {
                                    top: 10
                                }
                            }
                        }
                    }
                }
            });

            // Document Types Pie Chart - Calculate percentages from real data
            const totalDocuments = {{ $totalDocuments ?? 0 }};
            const pendingCount = {{ $pendingApprovals ?? 0 }};
            const approvedCount = {{ $approvedThisMonth ?? 0 }};
            const soCount = {{ $soMasterListCount ?? 0 }};
            const cavCount = {{ $cavCount ?? 0 }};
            
            // Determine "Others" count to make up the difference
            const trackedCount = pendingCount + approvedCount + soCount + cavCount;
            const othersCount = totalDocuments > trackedCount ? totalDocuments - trackedCount : 0;
            
            // Convert to percentages if totalDocuments is not zero
            let pendingPercentage, approvedPercentage, soPercentage, cavPercentage, othersPercentage;
            
            if (totalDocuments > 0) {
                pendingPercentage = Math.round((pendingCount / totalDocuments) * 100);
                approvedPercentage = Math.round((approvedCount / totalDocuments) * 100);
                soPercentage = Math.round((soCount / totalDocuments) * 100);
                cavPercentage = Math.round((cavCount / totalDocuments) * 100);
                othersPercentage = 100 - (pendingPercentage + approvedPercentage + soPercentage + cavPercentage);
                
                // Ensure othersPercentage is not negative due to rounding
                if (othersPercentage < 0) othersPercentage = 0;
            } else {
                // Default values if no documents
                pendingPercentage = 0;
                approvedPercentage = 0;
                soPercentage = 0;
                cavPercentage = 0;
                othersPercentage = 0;
            }
            
            const pieCtx = document.getElementById('documentTypesChart').getContext('2d');
            const documentTypesChart = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved (This Month)', 'SO Documents', 'CAV Documents', 'Others'],
                    datasets: [{
                        data: [pendingPercentage, approvedPercentage, soPercentage, cavPercentage, othersPercentage],
                        backgroundColor: [
                            '#f39c12', // warning - pending
                            '#2ecc71', // success - approved
                            '#4361ee', // primary - SO
                            '#3498db', // info - CAV
                            '#94a3b8'  // gray - others
                        ],
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6,
                                padding: 20,
                                color: '#94a3b8'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            bodyColor: '#fff',
                            cornerRadius: 6,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const label = context.label;
                                    const count = [pendingCount, approvedCount, soCount, cavCount, othersCount][context.dataIndex];
                                    return `${label}: ${value}% (${count} docs)`;
                                }
                            }
                        }
                    }
                }
            });

            // Initialize DataTable for Recent Documents
            $('#recentDocumentsTable').DataTable({
                responsive: true,
                paging: true,
                lengthChange: false,
                searching: false,
                info: false,
                pageLength: 5,
                language: {
                    paginate: { previous: '<i class="fas fa-chevron-left"></i>', next: '<i class="fas fa-chevron-right"></i>' }
                },
                columnDefs: [{ orderable: false, targets: [3] }]
            });

            // Handle "View" Document button (AJAX Modal)
            $('.content-card').on('click', '.btn-primary', function(e) {
                if ($(this).attr('href')) {
                    e.preventDefault();
                    const documentId = $(this).attr('href').split('/').pop();
                    const modal = new bootstrap.Modal(document.getElementById('documentDetailsModal'));
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
    error: function(xhr) {
        console.error('AJAX Error:', xhr);
        $('#document-details-content').html(`
            <div class="alert alert-danger">
                Failed to load document details. 
                Status: ${xhr.status}
                Response: ${xhr.responseText}
            </div>
        `);
    }
    });
}
});
            // Dark mode toggle (placeholder functionality)
            $('.dark-mode-toggle').on('click', function() {
                $(this).find('i').toggleClass('fa-moon fa-sun');
                // Actual dark mode implementation would go here
            });
        });
    </script>
@endpush