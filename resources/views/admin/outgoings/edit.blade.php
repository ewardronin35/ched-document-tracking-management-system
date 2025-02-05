@extends('layouts.app') <!-- Ensure you have an admin layout -->

@section('content')
    <div class="container">
        <h1>Edit Outgoing</h1>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.outgoings.update', $outgoing->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="control_no" class="form-label">Control No.</label>
                <input type="text" name="control_no" class="form-control" id="control_no" value="{{ old('control_no', $outgoing->control_no) }}" required>
            </div>

            <div class="mb-3">
                <label for="date_released" class="form-label">Date Released</label>
                <input type="date" name="date_released" class="form-control" id="date_released" value="{{ old('date_released', $outgoing->date_released->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" class="form-control" id="category" value="{{ old('category', $outgoing->category) }}" required>
            </div>

            <div class="mb-3">
                <label for="addressed_to" class="form-label">Addressed To</label>
                <input type="text" name="addressed_to" class="form-control" id="addressed_to" value="{{ old('addressed_to', $outgoing->addressed_to) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $outgoing->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="subject_of_letter" class="form-label">Subject of Letter</label>
                <input type="text" name="subject_of_letter" class="form-control" id="subject_of_letter" value="{{ old('subject_of_letter', $outgoing->subject_of_letter) }}" required>
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks (Who Initiates the Process)</label>
                <textarea name="remarks" class="form-control" id="remarks">{{ old('remarks', $outgoing->remarks) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="libcap_no" class="form-label">LIBCAP #</label>
                <input type="text" name="libcap_no" class="form-control" id="libcap_no" value="{{ old('libcap_no', $outgoing->libcap_no) }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" id="status" required>
                    <option value="">Select Status</option>
                    <option value="Pending" {{ old('status', $outgoing->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ old('status', $outgoing->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status', $outgoing->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Rejected" {{ old('status', $outgoing->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Outgoing</button>
            <a href="{{ route('admin.outgoings.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
