<!-- resources/views/documents/index.blade.php -->

@extends('layouts.app') <!-- Ensure this extends your main layout -->

@section('title', 'Manage Documents')

@section('content')
<div class="container py-4" x-data="manageDocuments()">
    <h2 class="mb-4">Manage Documents</h2>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Filters</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.documents.index') }}">
                <div class="row">
                    <!-- User Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="user_id" class="form-label">Filter by Assigned Person</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Document Type Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select name="document_type" id="document_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="CAV" {{ request('document_type') == 'CAV' ? 'selected' : '' }}>CAV</option>
                            <option value="SO" {{ request('document_type') == 'SO' ? 'selected' : '' }}>SO</option>
                            <option value="IP" {{ request('document_type') == 'IP' ? 'selected' : '' }}>IP</option>
                            <option value="GR" {{ request('document_type') == 'GR' ? 'selected' : '' }}>GR</option>
                            <option value="COPC" {{ request('document_type') == 'COPC' ? 'selected' : '' }}>COPC</option>
                            <!-- Add more types as needed -->
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="In Review" {{ request('status') == 'In Review' ? 'selected' : '' }}>In Review</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <!-- Add more statuses as needed -->
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card">
        <div class="card-header">
            <strong>Documents</strong>
        </div>
        <div class="card-body table-responsive">
            @if($documents->count())
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Document ID</th>
                            <th>Tracking Number</th>
                            <th>User Full Name</th>
                            <th>User Email</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Uploaded At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->id }}</td>
                                <td>{{ $document->document_id }}</td>
                                <td>{{ $document->tracking_number }}</td>
                                <td>{{ $document->full_name }}</td>
                                <td>{{ $document->email }}</td>
                                <td>{{ $document->document_type }}</td>
                                <td>{{ $document->status }}</td>
                                <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                <td>
    @if($document->email)
        <a href="{{ route('admin.documents.userDocuments', $document->email) }}" 
           class="btn btn-sm btn-primary" 
           title="View User Documents">
            View User Documents
        </a>
    @else
        <span class="text-muted">N/A</span>
    @endif
</td>
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $documents->withQueryString()->links() }}
                </div>
            @else
                <p class="text-center">No documents found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
