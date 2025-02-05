@extends('layouts.app') <!-- Ensure you have an admin layout -->

@section('content')
    <div class="container">
        <h1>Outgoing Details</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-header">
                Control No.: {{ $outgoing->control_no }}
            </div>
            <div class="card-body">
                <p><strong>Date Released:</strong> {{ $outgoing->date_released->format('Y-m-d') }}</p>
                <p><strong>Category:</strong> {{ $outgoing->category }}</p>
                <p><strong>Addressed To:</strong> {{ $outgoing->addressed_to }}</p>
                <p><strong>Email:</strong> {{ $outgoing->email }}</p>
                <p><strong>Subject of Letter:</strong> {{ $outgoing->subject_of_letter }}</p>
                <p><strong>Remarks:</strong> {{ $outgoing->remarks }}</p>
                <p><strong>LIBCAP #:</strong> {{ $outgoing->libcap_no }}</p>
                <p><strong>Status:</strong> {{ $outgoing->status }}</p>
            </div>
        </div>

        <a href="{{ route('admin.outgoings.edit', $outgoing->id) }}" class="btn btn-warning">Edit Outgoing</a>
        <a href="{{ route('admin.outgoings.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
