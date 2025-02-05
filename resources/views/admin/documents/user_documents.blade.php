@extends('layouts.app')

@section('title', 'Manage Documents for ' . $userName)

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Documents for {{ $userName }} ({{ $userEmail }})</h2>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Back to All Documents -->
    <div class="mb-3">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
            Back to All Documents
        </a>
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
                                <td>{{ $document->document_type }}</td>
                                <td>{{ $document->status }}</td>
                                <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.documents.view', ['document' => $document->id]) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-info me-1" 
                                       title="View Document">
                                        <!-- SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                             class="bi bi-eye" viewBox="0 0 16 16" aria-label="View Document Icon">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                                            <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                        </svg>
                                    </a>
                                    <!-- Track Document Button -->
                                    <button type="button" 
                                            class="btn btn-sm btn-warning me-1 track-document-btn" 
                                            data-tracking-number="{{ $document->tracking_number }}" 
                                            title="Track Document">
                                        <!-- SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                             class="bi bi-graph-up-arrow" viewBox="0 0 16 16" aria-label="Track Document Icon">
                                            <path fill-rule="evenodd" 
                                                  d="M0 0h1v15h15v1H0V0zm10 4a.5.5 0 0 1 .5.5v4.793l2.146-2.147a.5.5 
                                                  0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-2-2a.5.5 
                                                  0 1 1 .708-.708L10.5 9.293V4.5A.5.5 
                                                  0 0 1 10 4z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $documents->links() }}
                </div>
            @else
                <p class="text-center">No documents found for this user.</p>
            @endif
        </div>
    </div>
</div>

<!-- Track Document Modal -->
<div class="modal fade" id="trackDocumentModal" tabindex="-1" aria-labelledby="trackDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="trackDocumentForm" method="POST" action="{{ route('admin.documents.track.assign') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="trackDocumentModalLabel">Assign Tracking Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tracking_number" id="modalTrackingNumber" value="">
                    <div class="mb-3">
                        <label for="assignUser" class="form-label">Assign to User</label>
                        <select class="form-select" id="assignUser" name="user_id" required>
                            <option value="" selected disabled>Select a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a user to assign the tracking number.
                        </div>
                    </div>
                    <!-- Additional fields can be added here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Tracking</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Bootstrap Modal
        var trackModal = new bootstrap.Modal(document.getElementById('trackDocumentModal'));

        // Event Listener for Track Document Buttons
        var trackButtons = document.querySelectorAll('.track-document-btn');
        trackButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var trackingNumber = this.getAttribute('data-tracking-number');
                document.getElementById('modalTrackingNumber').value = trackingNumber;
                trackModal.show();
            });
        });

        // Form Validation
        var trackForm = document.getElementById('trackDocumentForm');
        trackForm.addEventListener('submit', function(event) {
            if (!trackForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                trackForm.classList.add('was-validated');
            }
        });
    });
</script>
@endpush
