<div class="row">
    <div class="col-md-6">
        <h5>Document Information</h5>
        <table class="table table-bordered">
            <tr>
                <th>Tracking Number</th>
                <td>{{ $document->tracking_number }}</td>
            </tr>
            <tr>
                <th>Document Type</th>
                <td>{{ $document->document_type }}</td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td>{{ $document->full_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $document->email }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $document->phone_number }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h5>Status Information</h5>
        <table class="table table-bordered">
            <tr>
                <th>Current Status</th>
                <td>
                    @php
                        $statusClass = match(strtolower($document->status)) {
                            'approved' => 'badge-approved',
                            'pending' => 'badge-pending',
                            'rejected' => 'badge-rejected',
                            'released' => 'badge-released',
                            'archived' => 'badge-archived',
                            'redirected' => 'badge-info',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($document->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Approval Status</th>
                <td>
                    @php
                        $approvalClass = match(strtolower($document->approval_status)) {
                            'accepted' => 'badge-approved',
                            'pending' => 'badge-pending',
                            'rejected' => 'badge-rejected',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <span class="badge {{ $approvalClass }}">
                        {{ ucfirst($document->approval_status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ $document->created_at->format('M d, Y H:i A') }}</td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td>{{ $document->updated_at->format('M d, Y H:i A') }}</td>
            </tr>
        </table>
    </div>
</div>