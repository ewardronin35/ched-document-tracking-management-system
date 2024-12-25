{{-- resources/views/manage-users/import.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Import Users</h1>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Import Form --}}
    <form action="{{ route('manage.users.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="file" class="form-label">CSV File</label>
            <input type="file" name="file" class="form-control" id="file" accept=".csv" required>
            <div class="form-text">Ensure the CSV has headers: <strong>name, email, role</strong>.</div>
        </div>

        <button type="submit" class="btn btn-success">Import Users</button>
        <a href="{{ route('manage.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
