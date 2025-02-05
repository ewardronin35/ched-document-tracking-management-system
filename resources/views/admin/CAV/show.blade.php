{{-- resources/views/admin/cav/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">CAV Details</h1>

    <div class="card">
        <div class="card-header">
            CAV No: {{ $cav->cav_no }}
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>CAV No</th>
                        <td>{{ $cav->cav_no }}</td>
                    </tr>
                    <tr>
                        <th>Region</th>
                        <td>{{ $cav->region }}</td>
                    </tr>
                    <tr>
                        <th>Surname</th>
                        <td>{{ $cav->surname }}</td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td>{{ $cav->first_name }}</td>
                    </tr>
                    <tr>
                        <th>Extension Name</th>
                        <td>{{ $cav->extension_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Middle Name</th>
                        <td>{{ $cav->middle_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Sex</th>
                        <td>{{ ucfirst($cav->sex) }}</td>
                    </tr>
                    <tr>
                        <th>Institution Code</th>
                        <td>{{ $cav->institution_code }}</td>
                    </tr>
                    <tr>
                        <th>Full Name of HEI</th>
                        <td>{{ $cav->full_name_of_hei }}</td>
                    </tr>
                    <tr>
                        <th>Address of HEI</th>
                        <td>{{ $cav->address_of_hei ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Official Receipt Number</th>
                        <td>{{ $cav->official_receipt_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Type of HEIs</th>
                        <td>{{ $cav->type_of_heis ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Discipline Code</th>
                        <td>{{ $cav->discipline_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Program Name</th>
                        <td>{{ $cav->program_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Major</th>
                        <td>{{ $cav->major ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Program Level</th>
                        <td>{{ $cav->program_level ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status of the Program</th>
                        <td>{{ ucfirst($cav->status_of_the_program) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date Started</th>
                        <td>{{ $cav->date_started ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date Ended</th>
                        <td>{{ $cav->date_ended ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Graduation Date</th>
                        <td>{{ $cav->graduation_date ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Units Earned</th>
                        <td>{{ $cav->units_earned ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Special Order No</th>
                        <td>{{ $cav->special_order_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Series</th>
                        <td>{{ $cav->series ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date Applied</th>
                        <td>{{ $cav->date_applied ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date Released</th>
                        <td>{{ $cav->date_released ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Airway Bill No</th>
                        <td>{{ $cav->airway_bill_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Serial Number of Security Paper</th>
                        <td>{{ $cav->serial_number_of_security_paper ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Purpose of CAV</th>
                        <td>{{ $cav->purpose_of_cav ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Target Country</th>
                        <td>{{ $cav->target_country ?? 'N/A' }}</td>
                    </tr>
                    {{-- Add more fields as necessary --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-3">
        @can('cav.edit')
            <a href="{{ route('admin.cav.edit', $cav->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Edit CAV
            </a>
        @endcan

        @can('cav.delete')
            <form action="{{ route('admin.cav.destroy', $cav->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this CAV record?');">
                    <i class="fas fa-trash-alt"></i> Delete CAV
                </button>
            </form>
        @endcan

        <a href="{{ route('admin.cav.index') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left"></i> Back to CAV List
        </a>
    </div>
</div>
@endsection
