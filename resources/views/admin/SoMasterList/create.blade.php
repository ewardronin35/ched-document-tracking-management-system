
<!-- resources/views/admin/so_master_lists/create.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Add New Student</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.so_master_lists.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="hei_name" class="form-label">HEI Name</label>
            <input type="text" name="hei_name" class="form-control" id="hei_name" value="{{ old('hei_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="hei_uii" class="form-label">HEI UII</label>
            <input type="text" name="hei_uii" class="form-control" id="hei_uii" value="{{ old('hei_uii') }}" required>
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">Student First Name</label>
            <input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Student Last Name</label>
            <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="program_id" class="form-label">Program</label>
            <select name="program_id" id="program_id" class="form-select" required>
                <option value="">Select Program</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                        {{ $program->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="major_id" class="form-label">Major</label>
            <select name="major_id" id="major_id" class="form-select" required>
                <option value="">Select Major</option>
                @foreach($majors as $major)
                    <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                        {{ $major->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Create Student</button>
        <a href="{{ route('admin.so_master_lists.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
