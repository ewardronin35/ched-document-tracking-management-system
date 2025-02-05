@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New HEI</h1>
    <form action="{{ route('admin.heis.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Region" class="form-label">Region</label>
            <input type="text" class="form-control" id="Region" name="Region" value="{{ old('Region') }}" required>
        </div>
        <div class="mb-3">
            <label for="HEIs" class="form-label">HEIs</label>
            <input type="text" class="form-control" id="HEIs" name="HEIs" value="{{ old('HEIs') }}" required>
        </div>
        <div class="mb-3">
            <label for="UII" class="form-label">UII</label>
            <input type="text" class="form-control" id="UII" name="UII" value="{{ old('UII') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.heis.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
