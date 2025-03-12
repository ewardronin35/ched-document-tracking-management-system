{{-- resources/views/admin/manage-users/index.blade.php --}}

@extends('layouts.app')

@push('styles')
    <!-- Modern Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
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
            --primary-color: #133A86;
            --primary-light: rgba(19, 58, 134, 0.1);
            --primary-hover: #0e2d68;
            --secondary-color: #DA042A;
            --accent-color: #FEE71B;
            --success-color: #00c389;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #ff4f70;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 0.75rem;
            --border-radius-sm: 0.5rem;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f9fc;
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
        }

        /* Modern Card Styling */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
            background: white;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background-color: #fff;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }

        /* Improved Tabs */
        .nav-tabs {
            border: none;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 0.5rem;
        }

        .nav-tabs::-webkit-scrollbar {
            display: none;
        }

        .nav-tabs .nav-item {
            margin: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            background-color: white;
            color: var(--dark-color);
            font-weight: 500;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.25rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 140px;
            justify-content: center;
            white-space: nowrap;
        }

        .nav-tabs .nav-link:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 5px 15px rgba(19, 58, 134, 0.2);
        }

        /* Modern Badges */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.5em 0.75em;
            border-radius: 30px;
        }

        .badge-role-admin {
            background-color: var(--secondary-color);
            color: white;
        }

        .badge-role-user {
            background-color: var(--primary-color);
            color: white;
        }

        .badge-role-manager {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }

        .badge-accent {
            background-color: #e9ecef;
            color: #495057;
        }

        /* Buttons */
        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 58, 134, 0.2);
        }

        .btn-light {
            background-color: #fff;
            border-color: #e0e0e0;
        }

        .btn-light:hover {
            background-color: #f8f9fa;
            border-color: #d0d0d0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 58, 134, 0.2);
        }

        /* Action Buttons Group */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-buttons .btn {
            padding: 0.375rem;
            width: 2.25rem;
            height: 2.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Modern Switch Toggle */
        .form-switch {
            margin: 0;
            padding: 0;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-switch .form-check-input {
            width: 44px;
            height: 24px;
            margin: 0;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='white'/%3e%3c/svg%3e");
            background-color: #ced4da;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 195, 137, 0.25);
            border-color: var(--success-color);
        }

        /* Table Improvements */
        .table-container {
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .table-responsive {
            min-height: 300px; /* Prevent layout shift during loading */
        }

        .table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            font-weight: 600;
            color: #475569;
            border-top: none;
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            color: #334155;
            border-bottom: 1px solid #edf2f7;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: rgba(19, 58, 134, 0.02);
        }

        /* DataTables Modern Styling */
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter, 
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_processing, 
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 1rem;
            color: #64748b;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            background-position: right 0.5rem center;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            width: 300px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='M21 21l-4.3-4.3'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 10px center;
            padding-left: 2.5rem;
            margin-left: 0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.75rem;
            margin: 0 0.25rem;
            color: #475569 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease-in-out;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: var(--primary-color) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: transparent;
            color: #cbd5e0 !important;
            border-color: #e2e8f0;
        }

        /* Avatar styling */
        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        /* Stats cards */
        .stats-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            height: 100%;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .stats-card.primary {
            border-left-color: var(--primary-color);
        }

        .stats-card.success {
            border-left-color: var(--success-color);
        }

        .stats-card.warning {
            border-left-color: var(--warning-color);
        }

        .stats-card.danger {
            border-left-color: var(--danger-color);
        }

        .stats-card .stats-icon {
            width: 48px;
            height: 48px;
            background-color: var(--primary-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .stats-card .stats-icon.success {
            background-color: rgba(0, 195, 137, 0.1);
            color: var(--success-color);
        }

        .stats-card .stats-icon.warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .stats-card .stats-icon.danger {
            background-color: rgba(255, 79, 112, 0.1);
            color: var(--danger-color);
        }

        .stats-card .stats-title {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .stats-card .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0;
        }

        .stats-card .stats-change {
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        .stats-card .stats-change.positive {
            color: var(--success-color);
        }

        .stats-card .stats-change.negative {
            color: var(--danger-color);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            background-color: #fcfcfd;
            border-radius: var(--border-radius);
        }

        .empty-state-icon {
            font-size: 3.5rem;
            color: #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .empty-state .text-muted {
            margin-bottom: 1.5rem;
        }

        /* Modal Improvements */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #edf2f7;
            padding: 1.25rem 1.5rem;
        }

        /* Form Controls */
        .form-control, .form-select {
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            color: #475569;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(19, 58, 134, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        /* Permission checkboxes */
        .permissions-container {
            max-height: 300px;
            overflow-y: auto;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .permission-group {
            margin-bottom: 1.5rem;
        }

        .permission-group:last-child {
            margin-bottom: 0;
        }

        .permission-group-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .permission-group-header h6 {
            color: var(--primary-color);
            margin-bottom: 0;
            font-weight: 600;
        }

        .permission-group-header hr {
            margin: 0 0.75rem;
            flex-grow: 1;
            border-color: #e2e8f0;
        }

        .permission-group-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.5rem;
        }

        .permission-checkbox:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Loader */
        .loader-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Fade in animation for tables */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pagination */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--primary-color);
            padding: 0.375rem 0.75rem;
            border: 1px solid #e2e8f0;
            margin: 0 0.25rem;
            border-radius: 0.375rem !important;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .card-header {
                padding: 1.25rem;
            }
            
            .card-body {
                padding: 1.25rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
            }
            
            .action-buttons {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
            }
            
            .table th, .table td {
                padding: 0.75rem;
            }
        }

        @media (max-width: 768px) {
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 0.75rem;
            }
            
            .stats-row {
                margin-bottom: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .header-actions {
                margin-top: 1rem;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .header-actions .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')


    <!-- Stats Cards -->
    <div class="row stats-row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card primary">
                <div class="stats-icon">
                    <i class="fas fa-users fa-lg"></i>
                </div>
                <h6 class="stats-title">TOTAL USERS</h6>
                <h2 class="stats-value">{{ $users->count() }}</h2>
                <div class="stats-change positive">
                    <i class="fas fa-arrow-up me-1"></i> 4.8% from last month
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card success">
                <div class="stats-icon success">
                    <i class="fas fa-user-check fa-lg"></i>
                </div>
                <h6 class="stats-title">ACTIVE ACCOUNTS</h6>
                <h2 class="stats-value">{{ $users->where('can_login', true)->count() }}</h2>
                <div class="stats-change positive">
                    <i class="fas fa-arrow-up me-1"></i> 2.3% from last month
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card warning">
                <div class="stats-icon warning">
                    <i class="fas fa-user-shield fa-lg"></i>
                </div>
                <h6 class="stats-title">ADMINISTRATORS</h6>
                <h2 class="stats-value">{{ $users->filter(function($user) { return $user->roles->contains('name', 'admin'); })->count() }}</h2>
                <div class="stats-change">
                    <i class="fas fa-minus me-1"></i> No change
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card danger">
                <div class="stats-icon danger">
                    <i class="fas fa-user-clock fa-lg"></i>
                </div>
                <h6 class="stats-title">NEW REGISTRATIONS</h6>
                <h2 class="stats-value">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</h2>
                <div class="stats-change positive">
                    <i class="fas fa-arrow-up me-1"></i> 12.5% from last month
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="manageUsersTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center"
                id="users-tab"
                data-bs-toggle="tab"
                data-bs-target="#users-tab-pane"
                type="button"
                role="tab"
                aria-controls="users-tab-pane"
                aria-selected="true">
                <i class="fas fa-users"></i> Manage Users
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center"
                id="gmail-tab"
                data-bs-toggle="tab"
                data-bs-target="#gmail-tab-pane"
                type="button"
                role="tab"
                aria-controls="gmail-tab-pane"
                aria-selected="false">
                <i class="fas fa-envelope"></i> Gmail Logins
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center"
                id="audit-tab"
                data-bs-toggle="tab"
                data-bs-target="#audit-tab-pane"
                type="button"
                role="tab"
                aria-controls="audit-tab-pane"
                aria-selected="false">
                <i class="fas fa-clipboard-list"></i> Audit Logs
            </button>
        </li>
    </ul>

    <div class="tab-content" id="manageUsersTabsContent">
        <!-- 1) Manage Users Tab -->
        <div class="tab-pane fade show active" id="users-tab-pane" role="tabpanel" aria-labelledby="users-tab">
            <div class="card mb-4 fade-in">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0">User Accounts</h5>
                    <div class="d-flex gap-2 mt-2 mt-md-0 header-actions">
                        <a href="{{ route('admin.manage.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Add User
                        </a>
                        <a href="{{ route('admin.manage.users.import.form') }}" class="btn btn-light">
                            <i class="fas fa-file-import"></i> Import Users
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>ROLES</th>
                                    <th>PERMISSIONS</th>
                                    <th class="text-center">STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php 
                                                $colors = ['#4F46E5', '#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
                                                $colorIndex = crc32(substr($user->name, 0, 1)) % count($colors);
                                                $bgColor = $colors[$colorIndex];
                                            @endphp
                                            <div class="avatar-circle" style="background-color: {{ $bgColor }}">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <div class="small text-muted">Member since {{ $user->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            @php
                                                $badgeClass = match(strtolower($role->name)) {
                                                    'admin' => 'badge-role-admin',
                                                    'user' => 'badge-role-user',
                                                    'manager' => 'badge-role-manager',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} me-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if(count($user->permissions) > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($user->permissions->take(2) as $permission)
                                                    <span class="badge badge-accent">{{ $permission->name }}</span>
                                                @endforeach
                                                
                                                @if(count($user->permissions) > 2)
                                                    <span class="badge bg-light text-dark">+{{ count($user->permissions) - 2 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">No permissions</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input 
                                                type="checkbox"
                                                class="form-check-input toggle-can-login"
                                                data-user-id="{{ $user->id }}"
                                                {{ $user->can_login ? 'checked' : '' }}
                                                role="switch"
                                                data-bs-toggle="tooltip"
                                                title="{{ $user->can_login ? 'Deactivate user' : 'Activate user' }}"
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.manage.users.edit', $user->id) }}" class="btn btn-outline-primary btn-icon" data-bs-toggle="tooltip" title="Edit user">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button
                                                class="btn btn-outline-info btn-icon edit-permissions-btn"
                                                data-user-id="{{ $user->id }}"
                                                data-bs-toggle="tooltip"
                                                title="Edit permissions"
                                            >
                                                <i class="fas fa-lock"></i>
                                            </button>
                                            <form
                                                action="{{ route('admin.manage.users.destroy', $user->id) }}"
                                                method="POST"
                                                class="d-inline"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger btn-icon delete-user-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete user"
                                                >
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-users-slash"></i>
                                            </div>
                                            <h4>No Users Found</h4>
                                            <p class="text-muted">Get started by adding a new user account</p>
                                            <a href="{{ route('admin.manage.users.create') }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-2"></i> Add User
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- 2) Gmail Logins Tab -->
        <div class="tab-pane fade" id="gmail-tab-pane" role="tabpanel" aria-labelledby="gmail-tab">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Gmail Integrations</h5>
                        <p class="text-muted mb-0">Users with active Gmail tokens</p>
                    </div>
                    <div>
                        <button class="btn btn-light">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($gmailTokens->count() > 0)
                        <div class="table-responsive">
                            <table class="table" id="gmailLoginsTable">
                                <thead>
                                    <tr>
                                        <th>USER</th>
                                        <th>EMAIL</th>
                                        <th>TOKEN CREATED</th>
                                        <th>LAST UPDATED</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gmailTokens as $token)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php 
                                                        $userName = $token->user?->name ?? 'Unknown';
                                                        $colors = ['#4F46E5', '#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
                                                        $colorIndex = crc32(substr($userName, 0, 1)) % count($colors);
                                                        $bgColor = $colors[$colorIndex];
                                                    @endphp
                                                    <div class="avatar-circle" style="background-color: {{ $bgColor }}">
                                                        {{ substr($userName, 0, 1) }}
                                                    </div>
                                                    <div>{{ $userName }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fab fa-google text-danger me-2"></i>
                                                    {{ $token->user?->email ?? 'No email' }}
                                                </div>
                                            </td>
                                            <td>{{ $token->created_at->format('M d, Y h:i A') }}</td>
                                            <td>{{ $token->updated_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-outline-danger btn-icon" data-bs-toggle="tooltip" title="Revoke token">
                                                        <i class="fas fa-unlink"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h4>No Gmail Tokens Found</h4>
                            <p class="text-muted">Users have not connected their Gmail accounts yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3) Audit Logs Tab -->
        <div class="tab-pane fade" id="audit-tab-pane" role="tabpanel" aria-labelledby="audit-tab">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-0">System Activity</h5>
                        <p class="text-muted mb-0">Recent user activity logs</p>
                    </div>
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <div class="btn-group">
                            <button class="btn btn-light" id="auditFilterBtn">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn btn-light">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($auditLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table" id="auditLogsTable">
                                <thead>
                                    <tr>
                                        <th>USER</th>
                                        <th>EVENT</th>
                                        <th>IP ADDRESS</th>
                                        <th>TIMESTAMP</th>
                                        <th>DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($auditLogs as $log)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php 
                                                        $userName = $log->user?->name ?? 'Unknown';
                                                        $colors = ['#4F46E5', '#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
                                                        $colorIndex = crc32(substr($userName, 0, 1)) % count($colors);
                                                        $bgColor = $colors[$colorIndex];
                                                    @endphp
                                                    <div class="avatar-circle" style="background-color: {{ $bgColor }}">
                                                        {{ substr($userName, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $userName }}</div>
                                                        <div class="small text-muted">{{ $log->user?->email ?? 'No email' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $eventClass = match($log->event_type ?? 'Login') {
                                                        'Login' => 'bg-success',
                                                        'Logout' => 'bg-secondary',
                                                        'Failed Login' => 'bg-danger',
                                                        'Password Reset' => 'bg-warning',
                                                        'Profile Update' => 'bg-info',
                                                        default => 'bg-primary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $eventClass }}">{{ $log->event_type ?? 'Login' }}</span>
                                            </td>
                                            <td>{{ $log->ip_address ?? '127.0.0.1' }}</td>
                                            <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-light view-log-details" data-log-id="{{ $log->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4>No Audit Logs Found</h4>
                            <p class="text-muted">System activity logs will appear here</p>
                        </div>
                    @endif
                </div>
                @if($auditLogs->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $auditLogs->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Permissions Modal -->
    <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editPermissionsForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPermissionsModalLabel">
                            <i class="fas fa-lock me-2"></i> User Permissions
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modalUserId" name="user_id">
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">Available Permissions</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="toggleAllPermissions">
                                    Select All
                                </button>
                            </div>
                            <div class="permissions-container p-3">
                                <div id="permissionsContainer" class="d-flex flex-column gap-2">
                                    <!-- Checkboxes inserted via JS -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading permissions...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteUserModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            
            // Toastr configuration
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": 3000,
                "extendedTimeOut": 1000,
                "preventDuplicates": true,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            
            // Initialize DataTables with optimized settings
            const usersTable = $('#usersTable').DataTable({
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive"t><"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: "",
                    searchPlaceholder: "Search users...",
                    lengthMenu: "_MENU_ users per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ users"
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ordering: true,
                order: [[0, "asc"]],
                columnDefs: [
                    { orderable: false, targets: [4, 5] }
                ],
                stateSave: true,
                responsive: true,
                serverSide: false,
                processing: true,
                drawCallback: function() {
                    const tooltipTriggers = document.querySelectorAll('#usersTable [data-bs-toggle="tooltip"]');
                    tooltipTriggers.forEach(el => {
                        new bootstrap.Tooltip(el);
                    });
                }
            });
            
            const gmailTable = $('#gmailLoginsTable').DataTable({
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive"t><"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: "",
                    searchPlaceholder: "Search gmail connections..."
                },
                pageLength: 10,
                responsive: true,
                order: [[0, "asc"]]
            });
            
            const auditTable = $('#auditLogsTable').DataTable({
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive"t><"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: "",
                    searchPlaceholder: "Search logs..."
                },
                pageLength: 10,
                responsive: true,
                order: [[3, "desc"]]
            });
            
            // Toggle login eligibility with optimized handler
            let toggleInProgress = false;
            
            $(document).on('change', '.toggle-can-login', function() {
                if (toggleInProgress) return;
                
                const checkbox = $(this);
                const userId = checkbox.data('user-id');
                const originalState = checkbox.prop('checked');
                
                toggleInProgress = true;
                
                // Show loading indicator
                const loadingToast = toastr.info('Updating user status...', null, {timeOut: 0});
                
                // Use POST method with _method field for method spoofing (instead of PATCH)
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PATCH');
                
                fetch(`/admin/manage/users/toggle-login/${userId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    toggleInProgress = false;
                    toastr.clear(loadingToast);
                    
                    const statusText = originalState ? 'deactivated' : 'activated';
                    toastr.success(`User ${statusText} successfully`);
                    
                    // Update tooltip text
                    const tooltip = bootstrap.Tooltip.getInstance(checkbox[0]);
                    if (tooltip) {
                        tooltip.dispose();
                    }
                    
                    checkbox.attr('title', originalState ? 'Activate user' : 'Deactivate user');
                    new bootstrap.Tooltip(checkbox[0]);
                })
                .catch(error => {
                    toggleInProgress = false;
                    toastr.clear(loadingToast);
                    checkbox.prop('checked', !originalState);
                    
                    toastr.error('Failed to update user status. Please try again.');
                    console.error('Toggle error:', error);
                });
            });
            
            // Edit Permissions with improved UX
            let permissionsCache = {};
            let allPermissionsSelected = false;
            
            $('.edit-permissions-btn').on('click', function() {
                const userId = $(this).data('user-id');
                $('#modalUserId').val(userId);
                
                // Show loading state
                $('#permissionsContainer').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading permissions...</p>
                    </div>
                `);
                
                // Show the modal immediately to improve perceived performance
                const modal = new bootstrap.Modal(document.getElementById('editPermissionsModal'));
                modal.show();
                
                // Check if we have cached permissions for this user
                if (permissionsCache[userId]) {
                    renderPermissions(permissionsCache[userId]);
                    return;
                }
                
                // Fetch permissions with caching
                fetch(`/admin/manage-users/${userId}/permissions`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        permissionsCache[userId] = data;
                        renderPermissions(data);
                    })
                    .catch(error => {
                        console.error('Error fetching permissions:', error);
                        $('#permissionsContainer').html(`
                            <div class="alert alert-danger">
                                <p><i class="fas fa-exclamation-circle me-2"></i> Failed to load permissions.</p>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="$('.edit-permissions-btn[data-user-id="${userId}"]').click()">
                                    <i class="fas fa-sync me-1"></i> Retry
                                </button>
                            </div>
                        `);
                    });
            });
            
            // Render permissions with improved UI and grouping
            function renderPermissions(data) {
                const permissions = data.permissions || [];
                const userPermissions = data.user_permissions || [];
                
                if (permissions.length === 0) {
                    $('#permissionsContainer').html(`
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> No permissions are available in the system.
                        </div>
                    `);
                    return;
                }
                
                // Group permissions by category if available
                const groupedPermissions = {};
                
                permissions.forEach(function(permission) {
                    // Extract category from permission name (e.g., "users.create" -> "users")
                    const category = permission.name.includes('.') ?
                        permission.name.split('.')[0].toUpperCase() :
                        'OTHER';
                    
                    if (!groupedPermissions[category]) {
                        groupedPermissions[category] = [];
                    }
                    
                    groupedPermissions[category].push(permission);
                });
                
                let html = '';
                
                // If we have groups, render grouped permissions
                if (Object.keys(groupedPermissions).length > 1) {
                    Object.keys(groupedPermissions).sort().forEach(function(category) {
                        html += `
                            <div class="permission-group mb-3">
                                <div class="permission-group-header d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-primary">${category}</h6>
                                    <hr class="flex-grow-1 mx-2">
                                </div>
                                <div class="permission-group-items">
                        `;
                        
                        groupedPermissions[category].forEach(function(permission) {
                            const isChecked = userPermissions.includes(permission.name) ? 'checked' : '';
                            html += `
                                <div class="form-check">
                                    <input
                                        class="form-check-input permission-checkbox"
                                        type="checkbox"
                                        value="${permission.id}"
                                        id="perm-${permission.id}"
                                        name="permissions[]"
                                        ${isChecked}
                                    >
                                    <label class="form-check-label" for="perm-${permission.id}">
                                        ${permission.name.includes('.') ? permission.name.split('.')[1] : permission.name}
                                    </label>
                                </div>
                            `;
                        });
                        
                        html += `
                                </div>
                            </div>
                        `;
                    });
                } else {
                    // Simple list for ungrouped permissions
                    permissions.forEach(function(permission) {
                        const isChecked = userPermissions.includes(permission.name) ? 'checked' : '';
                        html += `
                            <div class="form-check">
                                <input
                                    class="form-check-input permission-checkbox"
                                    type="checkbox"
                                    value="${permission.id}"
                                    id="perm-${permission.id}"
                                    name="permissions[]"
                                    ${isChecked}
                                >
                                <label class="form-check-label" for="perm-${permission.id}">
                                    ${permission.name}
                                </label>
                            </div>
                        `;
                    });
                }
                
                $('#permissionsContainer').html(html);
                
                // Update Select All button state
                updateSelectAllButtonState();
            }
            
            // Toggle all permissions
            $('#toggleAllPermissions').on('click', function() {
                allPermissionsSelected = !allPermissionsSelected;
                $('.permission-checkbox').prop('checked', allPermissionsSelected);
                $(this).text(allPermissionsSelected ? 'Deselect All' : 'Select All');
            });
            
            // Update Select All button state based on checkboxes
            function updateSelectAllButtonState() {
                const totalCheckboxes = $('.permission-checkbox').length;
                const checkedCheckboxes = $('.permission-checkbox:checked').length;
                
                allPermissionsSelected = (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
                $('#toggleAllPermissions').text(allPermissionsSelected ? 'Deselect All' : 'Select All');
            }
            
            // Listen for checkbox changes to update button state
            $(document).on('change', '.permission-checkbox', function() {
                updateSelectAllButtonState();
            });
            
            // Handle Save Permissions with improved UX and error handling
            $('#editPermissionsForm').on('submit', function(e) {
                e.preventDefault();
                
                const userId = $('#modalUserId').val();
                const submitButton = $(this).find('button[type="submit"]');
                const originalButtonText = submitButton.html();
                
                // Show loading state
                submitButton.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
                submitButton.prop('disabled', true);
                
                // Collect checked permissions
                const checkedBoxes = document.querySelectorAll('input[name="permissions[]"]:checked');
                const permissions = Array.from(checkedBoxes).map(cb => parseInt(cb.value, 10));
                
                // Create form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                permissions.forEach(permId => {
                    formData.append('permissions[]', permId);
                });
                
                // Use fetch instead of axios
                fetch(`/admin/manage-users/${userId}/permissions`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Hide modal
                        bootstrap.Modal.getInstance(document.getElementById('editPermissionsModal')).hide();
                        
                        // Show success message
                        toastr.success('User permissions updated successfully');
                        
                        // Refresh the page to show updated permissions
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        submitButton.html(originalButtonText);
                        submitButton.prop('disabled', false);
                        toastr.error(data.message || 'Failed to update permissions');
                    }
                })
                .catch(error => {
                    submitButton.html(originalButtonText);
                    submitButton.prop('disabled', false);
                    toastr.error('An error occurred while updating permissions');
                    console.error('Permission update error:', error);
                });
            });
            
            // Delete user confirmation
            $('.delete-user-btn').on('click', function() {
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');
                
                $('#deleteUserName').text(userName);
                $('#deleteUserForm').attr('action', `/admin/manage/users/${userId}`);
                
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                deleteModal.show();
            });
            
            // Tab persistence using URL hash
            // Keep the selected tab when page is refreshed
            const triggerTabList = document.querySelectorAll('#manageUsersTabs button');
            triggerTabList.forEach(triggerEl => {
                const tabTrigger = new bootstrap.Tab(triggerEl);
                
                triggerEl.addEventListener('click', event => {
                    event.preventDefault();
                    const tabId = triggerEl.id;
                    window.location.hash = tabId;
                    tabTrigger.show();
                });
            });
            
            // Show active tab based on URL hash or default to users tab
            const activeTabId = window.location.hash.substring(1) || 'users-tab';
            const activeTab = document.getElementById(activeTabId);
            if (activeTab) {
                const tab = new bootstrap.Tab(activeTab);
                tab.show();
            }
            
            // Optimize datatables for performance
            $.fn.dataTable.ext.errMode = 'throw'; // Prevents alert dialogs on errors
            
            // Add performance optimizations for large tables
            const loadTablesLazily = () => {
                // Only initialize tables when their tab becomes visible
                $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    const targetId = e.target.getAttribute('data-bs-target');
                    
                    if (targetId === '#gmail-tab-pane') {
                        gmailTable.columns.adjust().responsive.recalc();
                    } else if (targetId === '#audit-tab-pane') {
                        auditTable.columns.adjust().responsive.recalc();
                    } else {
                        usersTable.columns.adjust().responsive.recalc();
                    }
                });
            };
            
            loadTablesLazily();
            
            // Handle responsive buttons
            function handleResponsiveButtons() {
                if (window.innerWidth < 768) {
                    $('.action-buttons .btn').addClass('btn-sm');
                } else {
                    $('.action-buttons .btn').removeClass('btn-sm');
                }
            }
            
            // Call once on load and then on resize
            handleResponsiveButtons();
            $(window).on('resize', debounce(handleResponsiveButtons, 250));
            
            // Simple debounce function
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
        });
        
        // Show session messages via Toastr
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif
    </script>
@endpush