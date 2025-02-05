@extends('layouts.app')

@section('title', 'CDTMS Admin Dashboard')

@push('styles')
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          crossorigin="anonymous" 
          referrerpolicy="no-referrer" />
          
    <!-- Custom Styles -->
    <style>
        /* =========================================
           General Styles
        ========================================= */
        body {
            background-color: #ffffff; /* Clean white background */
            color: #343a40;
            font-family: 'Montserrat', sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
        img {
    transition: transform 0.3s ease;
}

img:hover {
    transform: scale(1.1);
}
        /* =========================================
           Welcome Section
        ========================================= */
        .welcome-section {
            background-color: #f8f9fa; /* Light gray for contrast */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 40px;
            transition: transform 0.3s, box-shadow 0.3s;
            transition: transform 0.3s, box-shadow 0.3s;

        }

        .welcome-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            color: #343a40;
        }

        .welcome-section p {
            color: #6c757d;
            margin-top: 15px;
            font-size: 1rem;
        }

        .logo-img {
            height: 40px; /* Reduced size */
            width: auto;
        }

        /* =========================================
           Stats Cards
        ========================================= */
        .stats-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            color: #343a40;
            border: 1px solid #e0e0e0;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .stats-card h2 {
            font-size: 1.2rem;
            color: #343a40;
            
            margin-bottom: 20px;
        }

        .stats-card .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: #00d084;
        }

        .stats-card .stats-description {
            font-size: 0.9rem;
            color: #343a40;
            margin-top: 10px;
        }

        /* =========================================
           Chart Section
        ========================================= */
        .chart-section {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
            color: #343a40;
            border: 1px solid #e0e0e0;
        }

        .chart-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .chart-section h2 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 15px;
        }

        .chart-container {
            position: relative;
            height: 300px; /* Fixed height to prevent endless expansion */
            width: 100%;
            overflow: hidden; /* Prevents canvas overflow if it resizes oddly */
        }

        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        /* =========================================
           Upcoming Deadlines
        ========================================= */
        .deadlines-section {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
            color: #343a40;
            border: 1px solid #e0e0e0;
        }

        .deadlines-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .deadlines-section h2 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 20px;
        }

        .deadlines-section ul {
            list-style: none;
            padding: 0;
        }

        .deadlines-section li {
            background: #f1f3f5; /* Light background for items */
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: background 0.3s;
            color: #343a40;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .deadlines-section li:hover {
            background: #e2e6ea;
        }

        .deadline-date {
            font-weight: 600;
            color: #00d084;
        }

        /* =========================================
           User Management Section
        ========================================= */
        .user-management {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 30px;
            margin-top: 40px;
            transition: transform 0.3s, box-shadow 0.3s;
            color: #343a40;
            border: 1px solid #e0e0e0;
        }

        .user-management:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .user-management h2 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 25px;
        }

        .user-management table thead {
            background: #00d084;
            color: #ffffff;
        }

        .user-management table thead th {
            font-weight: 600;
        }

        .user-management table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .user-management .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 8px;
        }

        /* =========================================
           Recent Documents Table
        ========================================= */
        .recent-documents {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 30px;
            margin-top: 40px;
            transition: transform 0.3s, box-shadow 0.3s;
            color: #343a40;
            border: 1px solid #e0e0e0;
        }

        .recent-documents:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .recent-documents h2 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 25px;
        }

        .recent-documents table thead {
            background: #00d084;
            color: #ffffff;
        }

        .recent-documents table thead th {
            font-weight: 600;
        }

        .recent-documents table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .recent-documents .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 8px;
        }

        /* =========================================
           Modal Styling
        ========================================= */
        .modal-content {
            background: #ffffff;
            color: #343a40;
            border: none;
            border-radius: 16px;
        }

        .modal-header {
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-title {
            color: #343a40;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            border-top: 1px solid #e0e0e0;
        }

        /* =========================================
           Accessibility Enhancements
        ========================================= */
        .btn:focus, .btn-primary:focus, .btn-secondary:focus {
            box-shadow: 0 0 0 3px rgba(0, 211, 132, 0.5);
        }

        /* =========================================
           Responsive Adjustments
        ========================================= */
        @media (max-width: 768px) {
            .welcome-section,
            .chart-section,
            .deadlines-section,
            .recent-documents,
            .user-management {
                padding: 20px;
            }

            .stats-card .stats-number {
                font-size: 2.2rem;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }

            .logo-img {
                height: 35px; /* Further reduced size for smaller screens */
            }

            .chart-container {
                height: 250px; /* Adjust chart height for smaller screens */
            }

            .deadlines-section li {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <!-- Welcome Section -->
        <section class="welcome-section mb-4">
    <div class="card shadow-lg border-0 rounded-3 bg-white text-dark">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <!-- Welcome Text -->
                <div class="col-lg-8">
                    <h1 id="welcome-heading" class="fw-bold text-dark">
                        Welcome to CDTMS Admin Dashboard!
                    </h1>
                    <p class="mt-3 text-dark">
                        Manage and monitor all aspects of your document tracking system. Stay on top of 
                        submissions, approvals, and user activities seamlessly with ease and efficiency.
                    </p>
                
                </div>

                <!-- Logo -->
                <div class="col-lg-4 text-center">
                    <div class="p-3 bg-dark rounded-circle shadow" style="width: 150px; height: 150px; margin: auto;">
                        <img 
                            src="{{ asset('images/logo.png') }}" 
                            alt="CDTMS Logo" 
                            class="img-fluid rounded-circle"
                            style="width: 100%; height: 100%; object-fit: cover;"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        <!-- Stats Section -->
        <section class="row g-4 mb-4" aria-labelledby="stats-heading">
            <div class="col-md-4">
                <div class="stats-card" role="region" aria-labelledby="total-documents">
                    <h2 id="total-documents">Total Documents</h2>
                    <p class="stats-number" aria-label="Total Documents">{{ number_format($totalDocuments) }}</p>
                    <p class="stats-description">All documents tracked in the system</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" role="region" aria-labelledby="pending-approvals">
                    <h2 id="pending-approvals">Pending Approvals</h2>
                    <p class="stats-number" aria-label="Pending Approvals">{{ number_format($pendingApprovals) }}</p>
                    <p class="stats-description">Documents awaiting action</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" role="region" aria-labelledby="approved-this-month">
                    <h2 id="approved-this-month">Approved This Month</h2>
                    <p class="stats-number" aria-label="Approved This Month">{{ number_format($approvedThisMonth) }}</p>
                    <p class="stats-description">Recently approved documents</p>
                </div>
            </div>
        </section>

        <!-- Chart and Upcoming Deadlines Section -->
        <section class="row g-4" aria-labelledby="chart-deadlines-heading">
            <!-- Chart Section -->
            <div class="col-lg-8">
                <div class="chart-section" role="region" aria-labelledby="monthly-submissions">
                    <h2 id="monthly-submissions">Monthly Submissions</h2>
                    <div class="chart-container">
                        <canvas 
                            id="documentChart" 
                            aria-label="Line chart showing monthly document submissions" 
                            role="img">
                        </canvas>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="col-lg-4">
                <div class="deadlines-section" role="region" aria-labelledby="upcoming-deadlines">
                    <h2 id="upcoming-deadlines">Upcoming Deadlines</h2>
                    <ul>
                        @forelse($upcomingDeadlines as $deadline)
                            <li>
                                <span class="deadline-date">
                                    {{ \Carbon\Carbon::parse($deadline->deadline_date)->format('F d, Y') }}
                                </span> 
                                <span>{{ $deadline->description ?? 'No Description Provided' }}</span>
                            </li>
                        @empty
                            <li>No upcoming deadlines.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </section>

        <!-- User Management Section -->
        <section class="user-management" aria-labelledby="user-management-heading">
            <h2 id="user-management-heading">User Management</h2>
            <div class="table-responsive">
                <table id="userManagementTable" 
                       class="table table-hover" 
                       aria-describedby="user-management-description">
                    <thead>
                        <tr>
                            <th scope="col">User Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Registered On</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleNames = $user->getRoleNames();
                                        $roleNameDisplay = $roleNames->isNotEmpty() 
                                            ? $roleNames->implode(', ') 
                                            : 'N/A';
                                    @endphp
                                    <span class="badge bg-primary">{{ $roleNameDisplay }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.manage.users.edit', $user->id) }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> 
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.manage.users.destroy', $user->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure?');">
                                            <i class="fas fa-trash-alt"></i> 
                                            Delete
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
        </section>

        <!-- Recent Documents Table -->
        <section class="recent-documents" aria-labelledby="recent-documents-heading">
            <h2 id="recent-documents-heading">Recently Updated Documents</h2>
            <div class="table-responsive">
                <table id="recentDocumentsTable" 
                       class="table table-hover" 
                       aria-describedby="recent-documents-description">
                    <thead>
                        <tr>
                            <th scope="col">Document Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Last Updated</th>
                            <th scope="col" class="text-end">Action</th>
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
                                        @default
                                            <span class="badge bg-secondary">Unknown</span>
                                    @endswitch
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($document->updated_at)->format('M d, Y') }}
                                </td>
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
        </section>
    </div>

    <!-- Document Details Modal -->
    <div class="modal fade" 
         id="documentDetailsModal" 
         tabindex="-1" 
         aria-labelledby="documentDetailsModalLabel" 
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Details</h5>
                    <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="modal" 
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div id="document-details-content" class="text-center">
                        <div class="spinner-border text-primary" 
                             role="status" 
                             aria-hidden="true">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading document details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-bs-dismiss="modal">
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
    <!-- Bootstrap JS (required for modals) -->
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome JS -->
    <script 
      src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" 
      crossorigin="anonymous" 
      referrerpolicy="no-referrer">
    </script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the Monthly Submissions Chart using Chart.js
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
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#00d084',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Important for fixed chart height
                    plugins: {
                        legend: {
                            labels: {
                                color: '#343a40',
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#343a40',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
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
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            },
                            grid: {
                                color: '#dee2e6'
                            },
                            ticks: {
                                color: '#343a40'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month',
                                color: '#343a40',
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            },
                            grid: {
                                color: '#dee2e6'
                            },
                            ticks: {
                                color: '#343a40'
                            }
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
                order: [[2, 'desc']], // sort by 'Last Updated' descending
                language: {
                    search: "Search:",
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
                    { orderable: false, targets: [3] } // Action column not sortable
                ]
            });

            // Initialize DataTable for User Management
            $('#userManagementTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                order: [[3, 'desc']], // sort by 'Registered On' descending
                language: {
                    search: "Search Users:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    infoEmpty: "No users available",
                    infoFiltered: "(filtered from _MAX_ total users)",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [4] } // Actions column not sortable
                ]
            });

            // Handle "View" Document button (AJAX-based modal)
            $('.recent-documents').on('click', '.btn-primary', function(e) {
                e.preventDefault();
                const documentId = $(this).attr('href').split('/').pop();
                const modal = new bootstrap.Modal(document.getElementById('documentDetailsModal'), {
                    keyboard: false
                });
                modal.show();

                // Reset the modal content to a loading spinner
                $('#document-details-content').html(`
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" aria-hidden="true">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading document details...</p>
                    </div>
                `);

                // Fetch details asynchronously
                $.ajax({
                    url: '{{ route("admin.documents.details", ":id") }}'.replace(':id', documentId),
                    type: 'GET',
                    success: function(data) {
                        $('#document-details-content').html(data);
                    },
                    error: function() {
                        $('#document-details-content').html(
                            '<p class="text-danger">Failed to load document details.</p>'
                        );
                    }
                });
            });

            // Handle Edit and Delete user actions
            $('.user-management').on('click', '.btn-secondary, .btn-danger', function(e) {
                e.preventDefault();
                const action = $(this).hasClass('btn-secondary') ? 'edit' : 'delete';
                const userId = $(this).attr('href').split('/').pop();
                
                if (action === 'edit') {
                    window.location.href = '{{ route("admin.manage.users.edit", ":id") }}'
                        .replace(':id', userId);
                } else if (action === 'delete') {
                    if(confirm('Are you sure you want to delete this user?')) {
                        window.location.href = '{{ route("admin.manage.users.destroy", ":id") }}'
                            .replace(':id', userId);
                    }
                }
            });
        });
    </script>
@endpush
