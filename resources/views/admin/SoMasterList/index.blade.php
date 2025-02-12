@extends('layouts.app')

@section('content')

@push('styles')
    <!-- Google Fonts for Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Handsontable CSS v8.4.0 including Horizon Light theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.css" />
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Select2 for multiselect dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
/* Container */
#soMasterListContainer {
    border: 2px solid #003366; /* Dark Blue for a modern touch */
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
    background-color: #f9f9f9; /* Light Gray */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

#soMasterListContainer:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

/* Table Base Styles */
.htHorizonLight {
    --ht-font-size: 15px;
    --ht-header-bg: #003366; /* Dark Blue */
    --ht-header-font-color: #ffffff; /* White */
    --ht-cell-bg: #ffffff; /* White */
    --ht-row-hover-bg: #d9edf7; /* Light Blue */
    --ht-border-color: #dcdcdc; /* Light Gray Border */
}

/* Header Styling */
.htHorizonLight th {
    font-family: 'Roboto', sans-serif;
    font-size: var(--ht-font-size);
    font-weight: bold;
    text-transform: uppercase;
    color: var(--ht-header-font-color);
    background-color: var(--ht-header-bg);
    padding: 12px;
    border: 1px solid var(--ht-border-color);
    text-align: center;
}

/* Table Cell Styling */
.htHorizonLight td {
    font-family: 'Roboto', sans-serif;
    font-size: var(--ht-font-size);
    color: #333333; /* Dark Gray Text */
    background-color: var(--ht-cell-bg);
    padding: 10px;
    border: 1px solid var(--ht-border-color);
    text-align: left;
}

/* Alternating Row Backgrounds */
.htHorizonLight tr:nth-child(even) td {
    background-color: #f7faff; /* Light Blue Background */
}

/* Hover Effect */
.htHorizonLight tr:hover td {
    background-color: var(--ht-row-hover-bg); /* Light Blue Hover */
    color: #003366; /* Dark Blue Text */
}

/* Current Cell Highlight */
.htHorizonLight td.current {
    border: 2px solid #ff5722; /* Accent Color - Orange */
    box-shadow: inset 0 0 0 2px #ff5722;
}

/* Scrollbar Customization */
.htHorizonLight .wtHolder::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.htHorizonLight .wtHolder::-webkit-scrollbar-thumb {
    background-color: #666666; /* Dark Gray */
    border-radius: 8px;
}
.htHorizonLight .wtHolder::-webkit-scrollbar-track {
    background-color: #eaeaea; /* Light Gray */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    #soMasterListContainer {
        margin-top: 10px;
        font-size: 13px;
    }

    .htHorizonLight th, .htHorizonLight td {
        padding: 8px;
    }
}
</style>


@endpush

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Error Message -->
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="excel-toolbar mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importCsvModal">Import CSV</button>
    <button class="btn btn-secondary" onclick="window.location.href='{{ route('admin.programs.index') }}'">View Programs</button>
    <button class="btn btn-secondary" onclick="window.location.href='{{ route('admin.majors.index') }}'">View Majors</button>
    <div class="form-group mb-3">
    <label for="programFilter">Filter by Program:</label>
    <select id="programFilter" class="form-control" style="width: 250px;">
        <option value="">-- All Programs --</option>
        @foreach($programOptions as $id => $program)
            <option value="{{ $program['name'] }}">{{ $program['name'] }}</option>
        @endforeach
    </select>
</div>

</div>
<!-- Handsontable Container -->
 
<div id="soMasterListContainer" class="htHorizonLight"></div>

<!-- Modals -->
<!-- Import CSV Modal -->
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.so_master_lists.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importCsvModalLabel">Import SO Master List CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="soCsvFile" class="form-label">Upload CSV File</label>
                        <input type="file" name="csv_file" id="soCsvFile" class="form-control" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>



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

<!-- Import Majors Modal -->
<div class="modal fade" id="importMajorsModal" tabindex="-1" aria-labelledby="importMajorsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.majors.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importMajorsModalLabel">Import Majors CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="majorCsvFile" class="form-label">Upload CSV File</label>
                        <input type="file" name="csv_file" id="majorCsvFile" class="form-control" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import Majors</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
@push('scripts')
    <!-- Handsontable JS v8.4.0 and Moment.js -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>
    <script>
$(document).ready(function () {
    // Setup CSRF token for AJAX
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
        "extendedTimeOut": "1000"
    };

    // Date Renderer Function
    function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
        if (value) {
            let date;
            if (Object.prototype.toString.call(value) === '[object Date]') {
                date = moment(value);
            } else {
                let cleanedValue = String(value).replace(/\.\d+Z$/, 'Z');
                date = moment(cleanedValue);
            }
            td.innerText = date.isValid() ? date.format('YYYY-MM-DD') : value;
        }
        Handsontable.renderers.TextRenderer.apply(this, arguments);
    }
// Example: heiOptions = { 'HEI Name 1': 'UII-001', 'HEI Name 2': 'UII-002' };
var heiOptions = {!! json_encode($heiOptions) !!}; // Use 'HEI Name' as keys and 'UII' as values
var heiNames = Object.keys(heiOptions); // Extract HEI names for the dropdown


var heiNameToId = {};
Object.keys(heiOptions).forEach(id => {
    heiNameToId[heiOptions[id]] = id; // Map name to ID
});

    // Program and Major Options (Names mapped to IDs)
    var programOptions = {!! json_encode($programOptions) !!}; // {id: {name, psced_code}}
    var majorOptions = {!! json_encode($majorOptions) !!};     // {id: name}

    // Create Reverse Mappings: Name to ID
    var programNameToId = {};
    for (let id in programOptions) {
        programNameToId[programOptions[id].name] = id;
    }

    var majorNameToId = {};
    for (let id in majorOptions) {
        majorNameToId[majorOptions[id]] = id;
    }

    // Arrays for Dropdown Sources
    var programNames = Object.values(programOptions).map(obj => obj.name);
    var majorNames = Object.values(majorOptions);

    // Container Element
    var container = document.getElementById('soMasterListContainer');

    // Initialize Handsontable
    var hot = new Handsontable(container, {
        filters: true,
        dropdownMenu: true,
        data: [],
        nestedHeaders: [
            
            [
                'No.', 
                'HEI NAME', 'HEI UII', 
                'Last Name', 'First Name', 'Middle Name', 'Suffix', 
                'Sex', 'Program', 'PSCED Code', 
                'Major ', 'Started', 'Ended', 
                'Date of Application (YYYY/MM/DD)', 'Date of Issuance (YYYY/MM/DD)', 
                'REGISTRAR', 'GOVT. PERMIT/ RECOGNITION', 'TOTAL', 
                'Semester ', 'Academic Year ', 
                'Date of Graduation (YYYY/MM/DD)'
            ]
        ],
        columns: [
            {data: 'id', readOnly: true, title: 'No.'},
            {data: 'hei_name', title: 'HEI NAME',         source: heiNames, type: 'dropdown'},
            {data: 'hei_uii', title: 'HEI UII',           source: heiNames, type: 'dropdown'},
            {data: 'last_name', title: 'Last Name'},
            {data: 'first_name', title: 'First Name'},
            {data: 'middle_name', title: 'Middle Name'},
            {data: 'extension_name', title: 'Suffix'},
            {data: 'sex', type: 'dropdown', source: ['Male', 'Female', 'Other'], title: 'Sex'},
            {data: 'program_id', type: 'dropdown', source: programNames, title: 'Program (Please select program from dropdown menu)'},
            {data: 'psced_code', readOnly: true, title: 'PSCED Code'}, // Auto-filled
            {data: 'major_id', type: 'dropdown', source: majorNames, title: 'Major (Select major from dropdown menu)'},
            {data: 'started', type: 'date', dateFormat: 'YYYY-MM-DD', renderer: dateRenderer, title: 'Started'},
            {data: 'ended', type: 'date', dateFormat: 'YYYY-MM-DD', renderer: dateRenderer, title: 'Ended'},
            {data: 'date_of_application', type: 'date', dateFormat: 'YYYY-MM-DD', renderer: dateRenderer, title: 'Date of Application (YYYY/MM/DD)'},
            {data: 'date_of_issuance', type: 'date', dateFormat: 'YYYY-MM-DD', renderer: dateRenderer, title: 'Date of Issuance (YYYY/MM/DD)'},
            {data: 'registrar', title: 'REGISTRAR'},
            {data: 'govt_permit_reco', title: 'GOVT. PERMIT/ RECOGNITION'},
            {data: 'total', type: 'numeric', title: 'TOTAL'},
            {data: 'semester', type: 'dropdown', source: [1,2,3,4,5,6,7,8], title: 'Semester (Select semester from dropdown menu)'},
            {data: 'academic_year', title: 'Academic Year (e.g. 2023-2024)'},
            {data: 'date_of_graduation', type: 'date', dateFormat: 'YYYY-MM-DD', renderer: dateRenderer, title: 'Date of Graduation (YYYY/MM/DD)'}
        ],
        colWidths: [60, 150, 100, 120, 120, 120, 200, 80, 250, 120, 200, 120, 150, 150, 150, 200, 80, 150, 150, 150],
        rowHeaders: true,
        stretchH: 'all',
        minSpareRows: 1, // Keep one empty row for new entries
        width: '100%',
        height: 750,
        licenseKey: 'non-commercial-and-evaluation',
        contextMenu: {
            items: {
                'remove_row': {
                    name: 'Delete Row',
                    callback: function(key, selection) {
                        var row = selection.start.row;
                        var rowData = hot.getSourceDataAtRow(row);
                        var id = rowData.id;
                        if(id) {
                            if(confirm('Are you sure you want to delete this student?')) {
                                $.ajax({
                                    url: '/admin/so_master_lists/' + id,
                                    method: 'DELETE',
                                    success: function() {
                                        toastr.success('Student deleted successfully!');
                                        hot.alter('remove_row', row);
                                    },
                                    error: function() {
                                        toastr.error('Error deleting student.');
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

            let rowsToUpdate = {};
            let newEntries = [];

            changes.forEach(function(change) {
                var row = change[0],
                    prop = change[1],
                    oldVal = change[2],
                    newVal = change[3];
                if(oldVal === newVal) return;

                var rowData = hot.getSourceDataAtRow(row);
                var id = rowData.id;

                // Handle Program Change: Map name back to ID and auto-fill PSCED Code
                if(prop === 'program_id') {
                    let matchedId = programNameToId[newVal];
                    if(matchedId) {
                        newVal = matchedId;
                        let psced = programOptions[matchedId].psced_code;
                        hot.setDataAtRowProp(row, 'psced_code', psced, 'edit');
                    } else {
                        toastr.error('Invalid Program selected.');
                        return;
                    }
                }
                // Handle Major Change: Map name back to ID
                else if(prop === 'major_id') {
                    let majorId = majorNameToId[newVal];
                    if(majorId) {
                        newVal = majorId;
                    } else {
                        toastr.error('Invalid Major selected.');
                        return;
                    }
                }
               // Map HEI Name to UII
               if (prop === 'hei_name') {
            const uii = heiOptions[newVal]; 
            if (uii) {
                hot.setDataAtRowProp(row, 'hei_uii', uii);
            } else {
                toastr.error('Invalid HEI Name selected.');
                hot.setDataAtRowProp(row, 'hei_uii', '');
            }
        }
    
        
                // If no ID, treat as new entry
                if(!id) {
                    let requiredFields = [
                        'hei_name', 'hei_uii', 'last_name', 'first_name', 
                        'sex', 'program_id', 'major_id', 'started', 
                        'date_of_application', 'total', 'semester', 'academic_year'
                    ];
                    let hasAllRequired = requiredFields.every(field => rowData[field]);
                    if(hasAllRequired) {
                        newEntries.push(rowData);
                    }
                    return;
                }

                // Accumulate changes for existing rows
                if(!rowsToUpdate[row]) {
                    rowsToUpdate[row] = { id: id, data: {} };
                }
                rowsToUpdate[row].data[prop] = newVal;
            });

            // Send AJAX requests for existing rows
            Object.values(rowsToUpdate).forEach(function(updateInfo) {
                $.ajax({
                    url: '/admin/so_master_lists/' + updateInfo.id + '/inline',
                    method: 'PUT',
                    data: updateInfo.data,
                    dataType: 'json'
                })
                .done(function(response) {
                    toastr.success('Row ' + updateInfo.id + ' updated successfully.');
                })
                .fail(function(xhr) {
                    console.error('Update failed for row ' + updateInfo.id + ':', xhr.responseText);
                    let errors = xhr.responseJSON && xhr.responseJSON.error;
                    let errorMessages = errors ? '' : 'An unknown error occurred.';
                    if (errors) {
                        $.each(errors, function(key, messages) {
                            $.each(messages, function(i, msg) {
                                errorMessages += msg + '\n';
                            });
                        });
                    }
                    toastr.error('Error updating row ' + updateInfo.id + ':\n' + errorMessages);
                });
            });

            // Handle new entries
            newEntries.forEach(function(entry) {
                // Convert program and major IDs to their names before sending
                entry.program_id = programNameToId[entry.program_id];
                entry.major_id = majorNameToId[entry.major_id];

                delete entry.id; // Remove ID for new entry
                $.ajax({
                    url: '/admin/so_master_lists',
                    method: 'POST',
                    data: entry,
                    dataType: 'json'
                })
                .done(function(response) {
                    toastr.success('New student added successfully.');
                    hot.loadData(response.data); // Reload data to reflect new entry
                })
                .fail(function(xhr) {
                    console.error('Error adding new student:', xhr.responseText);
                    let errors = xhr.responseJSON && xhr.responseJSON.error;
                    let errorMessages = errors ? '' : 'An unknown error occurred.';
                    if (errors) {
                        $.each(errors, function(key, messages) {
                            $.each(messages, function(i, msg) {
                                errorMessages += msg + '\n';
                            });
                        });
                    }
                    toastr.error('Error adding new student:\n' + errorMessages);
                });
            });
        }
    });
    function debounce(func, wait) {
  let timeout;
  return function(...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), wait);
  };
}

// Listen for changes on the external program filter dropdown
$('#programFilter').on('change', debounce(function() {
    var selectedProgram = $(this).val();
    var filtersPlugin = hot.getPlugin('filters');

    // Clear existing conditions before applying a new one
    filtersPlugin.clearConditions();

    // If a specific program is selected, apply a condition to column index 8 (program_id)
    if (selectedProgram) {
        filtersPlugin.addCondition(8, 'eq', selectedProgram);
    }

    // Trigger filtering
    filtersPlugin.filter();
}, 300));  // 300ms delay

    // Load initial data
    $.ajax({
        url: "{{ route('admin.so_master_lists.data') }}",
        method: 'GET',
        success: function(response) {
            hot.loadData(response.data);
        },
        error: function() {
            toastr.error('Error loading data.');
        }
    });

    // Handle the Add Student Form Submission
    $('#addStudentForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                toastr.success('Student added successfully!');
                hot.loadData(response.data);
                $('#addStudentModal').modal('hide');
            },
            error: function(xhr) {
                console.error('Error adding student:', xhr.responseText);
                let errors = xhr.responseJSON && xhr.responseJSON.error;
                let errorMessages = errors ? '' : 'An unknown error occurred.';
                if (errors) {
                    $.each(errors, function(key, messages) {
                        $.each(messages, function(i, msg) {
                            errorMessages += msg + '\n';
                        });
                    });
                }
                toastr.error('Error adding student:\n' + errorMessages);
            }
        });
    });
});
</script>


@endpush
@endsection