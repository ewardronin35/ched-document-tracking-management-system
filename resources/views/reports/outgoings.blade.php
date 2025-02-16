@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Outgoing Reports for {{ $documentType }}</h2>
    <a href="{{ route('outgoings.report', ['document_type' => $documentType, 'export_type' => 'excel']) }}" class="btn btn-success">Download Excel</a>
    <a href="{{ route('outgoings.report', ['document_type' => $documentType, 'export_type' => 'pdf']) }}" class="btn btn-danger">Download PDF</a>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No.</th>
                <th>Date Released</th>
                <th>Category</th>
                <th>Addressed To</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Remarks</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outgoings as $outgoing)
                <tr>
                    <td>{{ $outgoing->no }}</td>
                    <td>{{ $outgoing->date_released }}</td>
                    <td>{{ $outgoing->category }}</td>
                    <td>{{ $outgoing->addressed_to }}</td>
                    <td>{{ $outgoing->email }}</td>
                    <td>{{ $outgoing->subject_of_letter }}</td>
                    <td>{{ $outgoing->remarks }}</td>
                    <td>{{ $outgoing->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
