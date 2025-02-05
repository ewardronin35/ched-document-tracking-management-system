@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Import HEIs</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.heis.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Upload Excel or CSV File</label>
            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .csv" required>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-file-import"></i> Import
        </button>
        <a href="{{ route('admin.heis.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
