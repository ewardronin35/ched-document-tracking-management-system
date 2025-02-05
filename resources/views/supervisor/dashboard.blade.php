@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">

        <!-- Heading and Welcome Section -->
        <div class="bg-white shadow-sm rounded p-4 mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-0">
                        Welcome to CDTMS Dashboard!
                    </h1>
                    <p class="mt-2 text-muted">
                        A quick overview of the document tracking system. Monitor your document submissions,
                        check pending approvals, and stay updated with recent activities.
                    </p>
                </div>
                <img 
                    src="{{ asset('images/Logo.png') }}" 
                    alt="Application Logo" 
                    class="img-fluid mt-3 mt-lg-0" 
                    style="height: 80px;"
                >
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row g-3 mb-4">
            <!-- Total Documents -->
            <div class="col-md-4">
                <div class="bg-white shadow-sm rounded p-4 h-100">
                    <h2 class="h5 text-secondary">Total Documents</h2>
                    <p class="display-4 text-primary mb-0">1,234</p>
                    <p class="text-muted small">All documents tracked in the system</p>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="col-md-4">
                <div class="bg-white shadow-sm rounded p-4 h-100">
                    <h2 class="h5 text-secondary">Pending Approvals</h2>
                    <p class="display-4 text-warning mb-0">56</p>
                    <p class="text-muted small">Documents awaiting action</p>
                </div>
            </div>

            <!-- Documents Approved This Month -->
            <div class="col-md-4">
                <div class="bg-white shadow-sm rounded p-4 h-100">
                    <h2 class="h5 text-secondary">Approved This Month</h2>
                    <p class="display-4 text-success mb-0">312</p>
                    <p class="text-muted small">Recently approved documents</p>
                </div>
            </div>
        </div>

        <!-- Chart + Recent Updates -->
        <div class="row g-3">
            <!-- Chart Section -->
            <div class="col-lg-8">
                <div class="bg-white shadow-sm rounded p-4 h-100">
                    <h2 class="h5 text-secondary">Documents Submitted (Monthly)</h2>
                    <p class="text-muted small mb-3">Overview of how many documents were submitted per month</p>

                    <!-- Responsive chart container -->
                    <div style="min-height: 300px;">
                        <canvas id="documentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="col-lg-4">
                <div class="bg-white shadow-sm rounded p-4 h-100">
                    <h2 class="h5 text-secondary">Upcoming Deadlines</h2>
                    <ul class="list-unstyled mt-3 mb-0">
                        <li>
                            <span class="fw-bold text-primary">May 15:</span> 
                            Scholarship Application Documents due.
                        </li>
                        <li>
                            <span class="fw-bold text-primary">May 20:</span> 
                            Accreditation Files Review.
                        </li>
                        <li>
                            <span class="fw-bold text-primary">June 1:</span> 
                            Annual Reports Submission.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Documents Table -->
        <div class="bg-white shadow-sm rounded p-4 mt-4">
            <h2 class="h5 text-secondary">Recently Updated Documents</h2>
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Enrollment Guidelines 2023</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>May 10, 2023</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-link">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Faculty Credentials Update</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>May 9, 2023</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-link">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Scholarship Grants List</td>
                            <td><span class="badge bg-danger">Rejected</span></td>
                            <td>May 8, 2023</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-link">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('documentChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'Documents Submitted',
                    data: [100, 200, 150, 300, 250],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderWidth: 2,
                    pointStyle: 'circle',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Documents'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 13
                            }
                        }
                    },
                }
            }
        });
    });
</script>
@endsection
