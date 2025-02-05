@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">HEIs List</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.heis.create') }}" class="btn btn-primary mb-3">Add New HEI</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Region</th>
                <th>HEIs</th>
                <th>UII</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($heis as $hei)
                <tr>
                    <td>{{ $hei->id }}</td>
                    <td>{{ $hei->Region }}</td>
                    <td>{{ $hei->HEIs }}</td>
                    <td>{{ $hei->UII }}</td>
                    <td>
                        <a href="{{ route('admin.heis.edit', $hei) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.heis.destroy', $hei) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No HEIs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
