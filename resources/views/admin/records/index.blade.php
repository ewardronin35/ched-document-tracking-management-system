@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Highlight table rows on hover */
    #recordTable tbody tr:hover {
        background-color: #f1f1f1;
    }
    /* Inline editable cell style */
    .editable {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h1>Manage Records</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs mb-3" id="recordTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage" type="button" role="tab" aria-controls="manage" aria-selected="true">
          Manage Records
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab" aria-controls="create" aria-selected="false">
          Create Record
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab" aria-controls="create" aria-selected="false">
          Create Reports
        </button>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="recordTabContent">
        <!-- Manage Records Tab Pane -->
        <div class="tab-pane fade show active" id="manage" role="tabpanel" aria-labelledby="manage-tab">
            <table class="table table-bordered" id="recordTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Relevant HEI</th>
                        <th>Document Type</th>
                        <th>Name of Document</th>
                        <th>Status</th>
                        <th>Transaction Type</th>
                        <th>Assigned Staff</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>

      <!-- Create Record Tab Pane -->
<div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab">
    <form action="{{ route('admin.record.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="project" class="form-label">Project</label>
                <input type="text" name="project" id="project" class="form-control" required>
                <div class="invalid-feedback">Please enter a project name.</div>
            </div>
            <div class="col-md-6">
                <label for="relevant_hei" class="form-label">Relevant HEI</label>
                <input type="text" name="relevant_hei" id="relevant_hei" class="form-control" required>
                <div class="invalid-feedback">Please enter a relevant HEI.</div>
            </div>
            <div class="col-md-6">
                <label for="document_type" class="form-label">Document Type</label>
                <input type="text" name="document_type" id="document_type" class="form-control" required>
                <div class="invalid-feedback">Please enter a document type.</div>
            </div>
            <div class="col-md-6">
                <label for="name_of_document" class="form-label">Name of Document</label>
                <input type="text" name="name_of_document" id="name_of_document" class="form-control" required>
                <div class="invalid-feedback">Please enter the name of the document.</div>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <input type="text" name="status" id="status" class="form-control" required>
                <div class="invalid-feedback">Please enter a status.</div>
            </div>
            <div class="col-md-6">
                <label for="transaction_type" class="form-label">Transaction Type</label>
                <input type="text" name="transaction_type" id="transaction_type" class="form-control" required>
                <div class="invalid-feedback">Please enter a transaction type.</div>
            </div>
            <div class="col-md-6">
                <label for="assigned_staff" class="form-label">Assigned Staff</label>
                <input type="text" name="assigned_staff" id="assigned_staff" class="form-control" required>
                <div class="invalid-feedback">Please enter the assigned staff name.</div>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Create Record</button>
        </div>
    </form>
</div>


    <!-- Modal for inline editing feedback (optional) -->
    <div id="feedback" style="display: none;"></div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#recordTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.record.data') }}", // Define a route for fetching record data
        columns: [
            { data: 'id', name: 'id' },
            { data: 'project', name: 'project', className: 'editable' },
            { data: 'relevant_hei', name: 'relevant_hei', className: 'editable' },
            { data: 'document_type', name: 'document_type', className: 'editable' },
            { data: 'name_of_document', name: 'name_of_document', className: 'editable' },
            { data: 'status', name: 'status', className: 'editable' },
            { data: 'transaction_type', name: 'transaction_type', className: 'editable' },
            { data: 'assigned_staff', name: 'assigned_staff', className: 'editable' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        createdRow: function(row, data, dataIndex) {
            // Mark editable cells
            $('td.editable', row).attr('contenteditable', 'true');
        }
    });

    // Handle inline editing
    $('#recordTable').on('blur', 'td.editable', function() {
        var cell = table.cell(this);
        var newValue = $(this).text();
        var rowData = table.row($(this).closest('tr')).data();
        var columnName = table.column(this).dataSrc();

        // Prepare data for update
        var updateData = {};
        updateData[columnName] = newValue;

        // Send AJAX request to update the record
        axios.put('/admin/record/' + rowData.id, updateData)
            .then(function(response) {
                // Optionally display feedback
                $('#feedback').text('Record updated successfully').fadeIn().delay(1000).fadeOut();
            })
            .catch(function(error) {
                console.error('Error updating record:', error);
                $('#feedback').text('Error updating record').fadeIn().delay(1000).fadeOut();
            });
    });
});
</script>
@endpush
