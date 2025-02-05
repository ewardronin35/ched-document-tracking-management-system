@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Incoming Document Details</h1>

    <div class="card mb-4">
        <div class="card-header">
            Reference Number: {{ $incoming->reference_number }}
        </div>
        <div class="card-body">
            <p><strong>Date Received:</strong> {{ $incoming->date_received ? $incoming->date_received->format('Y-m-d') : 'N/A' }}</p>
            <p><strong>Time Emailed:</strong> {{ $incoming->time_emailed }}</p>
            <p><strong>Sender Name:</strong> {{ $incoming->sender_name }}</p>
            <p><strong>Sender Email:</strong> {{ $incoming->sender_email }}</p>
            <p><strong>Subject:</strong> {{ $incoming->subject }}</p>
            <p><strong>Remarks:</strong> {{ $incoming->remarks }}</p>
            <p><strong>Date Time Routed:</strong> {{ $incoming->date_time_routed }}</p>
            <p><strong>Routed To:</strong> {{ $incoming->routed_to }}</p>
            <p><strong>Date Acted By ES:</strong> {{ $incoming->date_acted_by_es }}</p>
            <p><strong>Outgoing Details:</strong> {{ $incoming->outgoing_details }}</p>
            <p><strong>Quarter 1:</strong> {{ $incoming->q1 ? 'Yes' : 'No' }}</p>
            <p><strong>Quarter 2:</strong> {{ $incoming->q2 ? 'Yes' : 'No' }}</p>
            <p><strong>Quarter 3:</strong> {{ $incoming->q3 ? 'Yes' : 'No' }}</p>
            <p><strong>Quarter 4:</strong> {{ $incoming->q4 ? 'Yes' : 'No' }}</p>
            <p><strong>Year:</strong> {{ $incoming->year }}</p>
        </div>
    </div>

    <a href="{{ route('admin.incomings.index') }}" class="btn btn-secondary">Back to Incoming List</a>
</div>
@endsection
