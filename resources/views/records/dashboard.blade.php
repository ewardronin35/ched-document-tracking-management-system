<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- Heading and Welcome Section -->
        <div class="bg-white shadow-xl sm:rounded-lg p-6 lg:p-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Welcome to CDTMS Dashboard!
                    </h1>
                    <p class="mt-2 text-gray-500 leading-relaxed">
                        A quick overview of the document tracking system. Monitor your document submissions, check pending approvals, and stay updated with recent activities.
                    </p>
                </div>
                <img src="{{ asset('images/Logo.png') }}" alt="Application Logo" class="h-20 w-auto mt-6 lg:mt-0">
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Documents -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-800">Total Documents</h2>
                <p class="mt-2 text-3xl font-bold text-blue-600">1,234</p>
                <p class="mt-auto text-sm text-gray-500">All documents tracked in the system</p>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-800">Pending Approvals</h2>
                <p class="mt-2 text-3xl font-bold text-yellow-500">56</p>
                <p class="mt-auto text-sm text-gray-500">Documents awaiting action</p>
            </div>

            <!-- Documents Approved This Month -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-800">Approved This Month</h2>
                <p class="mt-2 text-3xl font-bold text-green-500">312</p>
                <p class="mt-auto text-sm text-gray-500">Recently approved documents</p>
            </div>
        </div>

        <!-- Main Content Area: Chart + Recent Updates + Deadlines -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Section (occupies two columns on large screens) -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 lg:col-span-2">
                <h2 class="text-xl font-semibold text-gray-800">Monthly Document Submissions</h2>
                <p class="text-sm text-gray-500">Overview of how many documents were submitted per month</p>
                <div class="mt-4">
                    <!-- Chart Canvas -->
                    <canvas id="documentChart" height="100"></canvas>
                </div>
            </div>

            <!-- Upcoming Deadlines or Announcements -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800">Upcoming Deadlines</h2>
                <ul class="mt-4 space-y-2 text-gray-700 text-sm">
                    <li>
                        <span class="font-medium text-blue-600">May 15:</span> Scholarship Application Documents due.
                    </li>
                    <li>
                        <span class="font-medium text-blue-600">May 20:</span> Accreditation Files Review.
                    </li>
                    <li>
                        <span class="font-medium text-blue-600">June 1:</span> Annual Reports Submission.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Recent Documents Table -->
        <div class="bg-white shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800">Recently Updated Documents</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Document Name</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr>
                            <td class="px-6 py-4">Enrollment Guidelines 2023</td>
                            <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Approved</span></td>
                            <td class="px-6 py-4">May 10, 2023</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Faculty Credentials Update</td>
                            <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span></td>
                            <td class="px-6 py-4">May 9, 2023</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Scholarship Grants List</td>
                            <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">Rejected</span></td>
                            <td class="px-6 py-4">May 8, 2023</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js Script (Add to your layout or here if you prefer) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('documentChart').getContext('2d');
        var documentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Documents Submitted',
                    data: [100, 200, 150, 300, 250], // Placeholder data
                    borderColor: 'rgba(59, 130, 246, 1)', // Tailwind's blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endsection
