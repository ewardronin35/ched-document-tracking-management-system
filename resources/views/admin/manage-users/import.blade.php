@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Import Users</h1>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h4 class="alert-heading">Import Failed!</h4>
            <p>There were some problems with your input:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Import Form --}}
    <form action="{{ route('admin.manage.users.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf

        <div class="mb-3">
            <label for="file" class="form-label">CSV File <span class="text-danger">*</span></label>
            <input type="file" name="file" class="form-control" id="file" accept=".csv" required>
            <div class="form-text">Download the <a href="{{ route('admin.manage.users.import.template') }}" class="link-primary">template CSV here</a>.</div>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-file-import"></i> Import Users
        </button>
        <a href="{{ route('admin.manage.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
    </form>

    {{-- Optional: Instructions or Tips --}}
    <div class="card">
        <div class="card-header">
            <strong>Import Instructions</strong>
        </div>
        <div class="card-body">
            <ul>
                <li>Ensure that the CSV file follows the template provided.</li>
                <li>Each row should contain the <strong>Name</strong>, <strong>Email</strong>, and <strong>Role</strong> of a user.</li>
                <li>The <strong>Role</strong> must exist in the system. Available roles: <strong>admin</strong>, <strong>user</strong>.</li>
                <li>Emails must be unique and in a valid format.</li>
                <li>Passwords are automatically generated, and users will receive an email to set their password.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
