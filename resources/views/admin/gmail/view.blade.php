@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="mb-4">
    <h2>{{ $emailData['subject'] }}</h2>
    <p><strong>From:</strong> {{ $emailData['from'] }}</p>
    <p><strong>Date:</strong> {{ $emailData['date'] }}</p>
  </div>
  <div class="card">
    <div class="card-body">
      <p>{{ $emailData['body'] }}</p>
      <!-- Display attachments here if parsed -->
    </div>
  </div>
  <a href="{{ route('admin.gmail.emails') }}" class="btn btn-secondary mt-3">Back to Inbox</a>
</div>
@endsection
