@extends('layouts.app')

@section('content')
@push('styles')
    <!-- Google Fonts for Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Handsontable CSS v8.4.0 including Horizon Light theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.css" />
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Container Styling */
        #programsContainer {
            border: 2px solid #003366;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
            height: 500px;
        }
        #programsContainer:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        /* Horizon Light Theme Styles */
        .htHorizonLight { /* ... preserve your theme styles ... */ }
    </style>
@endpush

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Admin - Programs</h1>
    <div>
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importProgramsModal">Import CSV</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProgramModal">Add New Program</button>
        <a href="{{ route('admin.so_master_lists.index') }}" class="btn btn-secondary ms-2">Back to SO Master Lists</a>
    </div>
</div>

<!-- Handsontable Container for Programs -->
<div id="programsContainer" class="htHorizonLight"></div>

<!-- Import Programs Modal -->
<div class="modal fade" id="importProgramsModal" tabindex="-1" aria-labelledby="importProgramsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.programs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importProgramsModalLabel">Import Programs CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="programCsvFile" class="form-label">Upload CSV File</label>
                        <input type="file" name="csv_file" id="programCsvFile" class="form-control" accept=".csv" required>
                        <small class="form-text text-muted">Ensure the CSV has headers: "name", "psced_code".</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import Programs</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.programs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addProgramModalLabel">Add New Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="programName" class="form-label">Program Name *</label>
                        <input type="text" name="name" id="programName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="pscedCode" class="form-label">PSCED Code</label>
                        <input type="text" name="psced_code" id="pscedCode" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Program</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@push('scripts')
    <!-- Load HyperFormula before Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/hyperformula@1.2.0/dist/hyperformula.full.min.js"></script>
    <!-- Handsontable JS v8.4.0, Moment.js, and Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

    <script>
    $(document).ready(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000"
        };

        function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
            if (value) {
                let date = moment(value);
                td.innerText = date.isValid() ? date.format('YYYY-MM-DD') : value;
            }
            Handsontable.renderers.TextRenderer.apply(this, arguments);
        }

        var programOptions = {!! json_encode($programOptions) !!};
        var programNameToId = {};
        for (let id in programOptions) {
            programNameToId[programOptions[id].name] = id;
        }
        var programNames = Object.values(programOptions).map(obj => obj.name);

        var hfInstance = HyperFormula.buildEmpty({ licenseKey: 'non-commercial-and-evaluation' });

        var programsContainer = document.getElementById('programsContainer');
        var programsHot = new Handsontable(programsContainer, {
            data: [],
            colHeaders: ['ID', 'Program Name', 'PSCED Code'],
            columns: [
                {data: 'id', readOnly: true, title: 'ID'},
                {data: 'name', title: 'Program Name'},
                {data: 'psced_code', title: 'PSCED Code'}
            ],
            rowHeaders: true,
            stretchH: 'all',
            minSpareRows: 0,
            licenseKey: 'non-commercial-and-evaluation',
            contextMenu: {
                items: {
                    'remove_row': {
                        name: 'Delete Program',
                        callback: function(key, selection) {
                            var row = selection.start.row;
                            var rowData = programsHot.getSourceDataAtRow(row);
                            var id = rowData.id;
                            if(id) {
                                if(confirm('Are you sure you want to delete this program? This will affect related majors and students.')) {
                                    $.ajax({
                                        url: '/admin/programs/' + id,
                                        method: 'DELETE',
                                        success: function() {
                                            toastr.success('Program deleted successfully!');
                                            programsHot.alter('remove_row', row);
                                        },
                                        error: function() {
                                            toastr.error('Error deleting program.');
                                        }
                                    });
                                }
                            } else {
                                toastr.warning('Cannot delete an empty row.');
                            }
                        }
                    },
                    'undo': {}, 'redo': {}, 'copy': {}, 'cut': {}, 'paste': {},
                    'separator': Handsontable.plugins.ContextMenu.SEPARATOR,
                    'row_above': {}, 'row_below': {}, 'col_left': {}, 'col_right': {}
                }
            },
            afterChange: function(changes, source) {
                if(source === 'loadData' || !changes) return;
                let rowsToUpdate = [];
                let newEntries = [];
                changes.forEach(function(change) {
                    var row = change[0], prop = change[1], oldVal = change[2], newVal = change[3];
                    if(oldVal === newVal) return;
                    var rowData = programsHot.getSourceDataAtRow(row);
                    var id = rowData.id;
                    if(!id) {
                        let requiredFields = ['name'];
                        let hasAllRequired = requiredFields.every(field => rowData[field]);
                        if(hasAllRequired) { newEntries.push(rowData); }
                        return;
                    }
                    let updateData = {}; updateData[prop] = newVal;
                    rowsToUpdate.push({id: id, data: updateData});
                });
                rowsToUpdate.forEach(function(updateInfo) {
                    $.ajax({
                        url: '/admin/programs/' + updateInfo.id,
                        method: 'PUT',
                        data: updateInfo.data,
                        dataType: 'json'
                    })
                    .done(function() {
                        toastr.success('Program updated successfully.');
                    })
                    .fail(function(xhr) {
                        console.error('Update failed for program ' + updateInfo.id + ':', xhr.responseText);
                        toastr.error('Error updating program ' + updateInfo.id);
                    });
                });
                newEntries.forEach(function(entry) {
                    $.ajax({
                        url: '/admin/programs',
                        method: 'POST',
                        data: entry,
                        dataType: 'json'
                    })
                    .done(function(response) {
                        toastr.success('New program added successfully.');
                        programsHot.loadData(response.data);
                    })
                    .fail(function(xhr) {
                        console.error('Error adding new program:', xhr.responseText);
                        toastr.error('Error adding new program.');
                    });
                });
            },
            licenseKey: 'non-commercial-and-evaluation',
            hyperFormulaInstance: hfInstance
        });

        $.ajax({
            url: "{{ route('admin.programs.data') }}",
            method: 'GET',
            success: function(response) {
                programsHot.loadData(response);
            },
            error: function() {
                toastr.error('Error loading Programs data.');
            }
        });
    });
    </script>
@endpush
@endsection
