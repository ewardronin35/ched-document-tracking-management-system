@extends('layouts.app') <!-- Ensure you have an admin layout -->

@section('content')
    <div class="container">
        <h1>Outgoing Documents</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Import CSV Form -->
        <form action="{{ route('admin.outgoings.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                <button class="btn btn-primary" type="submit">Import CSV</button>
            </div>
            @error('csv_file')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </form>

        <!-- Create New Outgoing Button -->
        <a href="{{ route('admin.outgoings.create') }}" class="btn btn-success mb-3">Add New Outgoing</a>

        <!-- Outgoings Table -->
        <table id="outgoings-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Control No.</th>
                    <th>Date Released</th>
                    <th>Category</th>
                    <th>Addressed To</th>
                    <th>Email</th>
                    <th>Subject of Letter</th>
                    <th>Remarks</th>
                    <th>LIBCAP #</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($outgoings as $outgoing)
                    <tr>
                        <td>{{ $outgoing->control_no }}</td>
                        <td>{{ $outgoing->date_released->format('Y-m-d') }}</td>
                        <td>{{ $outgoing->category }}</td>
                        <td>{{ $outgoing->addressed_to }}</td>
                        <td>{{ $outgoing->email }}</td>
                        <td>{{ $outgoing->subject_of_letter }}</td>
                        <td>{{ $outgoing->remarks }}</td>
                        <td>{{ $outgoing->libcap_no }}</td>
                        <td>{{ $outgoing->status }}</td>
                        <td>
                            <a href="{{ route('admin.outgoings.show', $outgoing->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('admin.outgoings.edit', $outgoing->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.outgoings.destroy', $outgoing->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this outgoing?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No outgoing records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#outgoings-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                // Optional: Customize language, length menu, etc.
            });
        });
    </script>
@endpush
