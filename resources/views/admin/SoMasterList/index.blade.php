@extends('layouts.app')

@section('content')
@push('styles')
    <!-- Google Fonts for Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Handsontable CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.css" />
    <!-- Select2 (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Container */
        #soMasterListContainer {
            border: 2px solid #003366;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        #soMasterListContainer:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        /* Handsontable Horizon Light Theme */
        .htHorizonLight {
            --ht-font-size: 15px;
            --ht-header-bg: #003366;
            --ht-header-font-color: #ffffff;
            --ht-cell-bg: #ffffff;
            --ht-row-hover-bg: #d9edf7;
            --ht-border-color: #dcdcdc;
        }
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
        .htHorizonLight td {
            font-family: 'Roboto', sans-serif;
            font-size: var(--ht-font-size);
            color: #333333;
            background-color: var(--ht-cell-bg);
            padding: 10px;
            border: 1px solid var(--ht-border-color);
            text-align: left;
        }
        .htHorizonLight tr:nth-child(even) td {
            background-color: #f7faff;
        }
        .htHorizonLight tr:hover td {
            background-color: var(--ht-row-hover-bg);
            color: #003366;
        }
        .htHorizonLight td.current {
            border: 2px solid #ff5722;
            box-shadow: inset 0 0 0 2px #ff5722;
        }
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

<div class="excel-toolbar mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importCsvModal">Import CSV</button>
    <button class="btn btn-secondary" onclick="window.location.href='{{ route('admin.programs.index') }}'">View Programs</button>
    <button class="btn btn-secondary" onclick="window.location.href='{{ route('admin.majors.index') }}'">View Majors</button>

    <div class="form-group mb-3">
  <label for="programFilter">Filter by Program:</label>
  <select id="programFilter" class="form-control" style="width: 250px;">
    <option value="">-- All Programs --</option>
    @foreach($programOptions as $id => $programData)
      <option value="{{ $id }}">{{ $programData['name'] }}</option>
    @endforeach
  </select>
</div>

</div>

<!-- Main Handsontable Container -->
<div id="soMasterListContainer" class="htHorizonLight"></div>

<!-- Modal for CSV Import -->
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.so_master_lists.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importCsvModalLabel">Import SO Master List CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

@endsection

@section('scripts')
@push('scripts')
    <!-- Handsontable, Moment.js, and Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Setup CSRF for AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
          // Initialize Select2 if not already initialized.
  if (!$('#programFilter').data('select2')) {
    $('#programFilter').select2({
      placeholder: '-- All Programs --',
      allowClear: true,
      width: '250px'
    });
  }
  
  // Debounce helper
  function debounce(fn, wait) {
    let timeout;
    return function() {
      let args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        fn.apply(null, args);
      }, wait);
    };
  }

  // Function to load grid data filtered by program ID
  function loadData(programId = '') {
    $.ajax({
      url: "{{ route('admin.so_master_lists.data') }}",
      method: 'GET',
      data: { program: programId },
      success: function(res) {
        let data = (res.data && res.data.length) ? res.data : [{}];
        hot.loadData(data);
      },
      error: function() {
        toastr.error('Error loading data.');
      }
    });
  }

  // Attach change event to the filter
  $('#programFilter').on('change', debounce(function() {
    console.log('Selected value:', $(this).val());

    // Use a fallback empty string if .val() returns a falsey value.
    let selectedProgram = $(this).val() || '';
    loadData(selectedProgram);
  }, 300));

            // 1) Build data structures for HEI dropdown
            let heiOptions = @json($heiOptions); // { "Some HEI Name": "UII123", ... }
            let heiNames   = Object.keys(heiOptions);

            // 2) Program dropdown
            let programOptionsRaw = @json($programOptions); 
            //  programOptionsRaw looks like: 
            //  {
            //    "1": { "name": "BS Computer Science", "psced_code": "1234" },
            //    "2": { "name": "BS Info Tech", "psced_code": "5678" },
            //    ...
            //  }
            let academicYears = [
  "2029-2030", "2028-2029", "2027-2028", "2026-2027", "2025-2026",
  "2024-2025", "2023-2024", "2022-2023", "2021-2022", "2020-2021",
  "2019-2020", "2018-2019", "2017-2018", "2016-2017", "2015-2016",
  "2014-2015", "2013-2014", "2012-2013", "2011-2012", "2010-2011",
  "2009-2010", "2008-2009", "2007-2008", "2006-2007", "2005-2006",
  "2004-2005", "2003-2004", "2002-2003", "2001-2002", "2000-2001",
  "1999-2000", "1998-1999", "1997-1998", "1996-1997", "1995-1996",
  "1994-1995", "1991-1992", "1990-1991", "1986-1987", "1984-1985",
  "1983-1984", "1982-1983", "1981-1982", "1980-1981", "1979-1980",
  "1978-1979", "1977-1978", "1976-1977", "1975-1976", "1974-1975",
  "1973-1974", "1972-1973", "1971-1972", "1970-1971", "1969-1970",
  "1968-1969", "1967-1968", "1966-1967", "1965-1966", "1964-1965",
  "1963-1964", "1962-1963", "1961-1962", "1960-1961", "1959-1960",
  "1958-1959", "1957-1958", "1956-1957", "1955-1956", "1954-1955",
  "1953-1954", "1952-1953", "1951-1952", "1950-1951", "1949-1950",
  "1948-1949", "1947-1948", "1946-1947", "1945-1946"
];
            // We'll create an array of program names for the dropdown
            let programNames = Object.values(programOptionsRaw).map(obj => obj.name);
            // We'll also map programName -> programId so we can retrieve the ID to save
            let programNameToId = {};
            for (let pid in programOptionsRaw) {
                programNameToId[ programOptionsRaw[pid].name ] = pid;
            }

            // 3) Major dropdown
            let majorOptionsRaw = @json($majorOptions); 
            //  majorOptionsRaw looks like: { "1": "Networking", "2": "Management", ... }

            // We'll create an array of major names:
            let majorNames = Object.values(majorOptionsRaw); // e.g. ["Networking", "Management", ...]
            // Map majorName -> majorId
            let majorNameToId = {};
            for (let mid in majorOptionsRaw) {
                majorNameToId[ majorOptionsRaw[mid] ] = mid;
            }

            // 4) Initialize Select2 for the Program filter (top of page)
            $('#programFilter').select2({
                placeholder: '-- All Programs --',
                allowClear: true,
                width: '250px'
            });

            // Optional: Toastr config
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "extendedTimeOut": "1000"
            };

            // Simple date renderer for Handsontable
            function dateRenderer(instance, td, row, col, prop, value, cellProperties) {
                if (value) {
                    let date = moment(value, moment.ISO_8601, true);
                    if (date.isValid()) {
                        td.innerText = date.format('YYYY-MM-DD');
                    } else {
                        td.innerText = value;
                    }
                }
                Handsontable.renderers.TextRenderer.apply(this, arguments);
            }

            // 5) Define your columns
            let columns = [
                { data: 'id', readOnly: true, title: 'No.' },
                { data: 'status', title: 'Status' },
                { data: 'processing_slip_number', title: 'Processing Slip #' },
                { data: 'region', title: 'Region' },

                // HEI Name & UII
                {
                    data: 'hei_name',
                    type: 'dropdown',
                    source: heiNames,
                    title: 'HEI Name'
                },
                {
                    data: 'hei_uii',
                    title: 'HEI UII'
                },

                { data: 'special_order_number', title: 'Special Order #' },
                { data: 'last_name', title: 'Last Name' },
                { data: 'first_name', title: 'First Name' },
                { data: 'middle_name', title: 'Middle Name' },
                { data: 'extension_name', title: 'Suffix' },

                // Sex
                {
                    data: 'sex',
                    type: 'dropdown',
                    source: ['Male','Female','Other'],
                    title: 'Sex'
                },

                // total
                {
                    data: 'total',
                    type: 'numeric',
                    title: 'Total'
                },

                // Program
                {
                    data: 'program_id', 
                    type: 'dropdown',
                    source: programNames,
                    title: 'Program',
                    strict: true // user must pick an exact match from dropdown

                    
                },

                // PSCED (auto-fill after Program is chosen)
                {
                    data: 'psced_code',
                    readOnly: true,
                    title: 'PSCED Code'
                },

                // Major
                {
                    data: 'major_id',
                    type: 'dropdown',
                    source: majorNames,
                    title: 'Major'
                },

                // Dates
                {
                    data: 'started',
                    type: 'date',
                    dateFormat: 'YYYY-MM-DD',
                    renderer: dateRenderer,
                    title: 'Started'
                },
                {
                    data: 'ended',
                    type: 'date',
                    dateFormat: 'YYYY-MM-DD',
                    renderer: dateRenderer,
                    title: 'Ended'
                },
                {
                    data: 'date_of_application',
                    type: 'date',
                    dateFormat: 'YYYY-MM-DD',
                    renderer: dateRenderer,
                    title: 'Date of App'
                },
                {
                    data: 'date_of_issuance',
                    type: 'date',
                    dateFormat: 'YYYY-MM-DD',
                    renderer: dateRenderer,
                    title: 'Date of Issuance'
                },

                // Registrar, etc.
                { data: 'registrar', title: 'Registrar' },
                { data: 'govt_permit_recognition', title: 'Govt. Permit/Recognition' },
                { data: 'signed_by', title: 'Signed By' },

                // Semester
                {
  data: 'semester',
  type: 'dropdown',
  source: ['First','Second','Summer'],
  title: 'Semester',
  renderer: function(instance, td, row, col, prop, value, cellProperties) {
    const semOptions = ['First', 'Second', 'Summer'];
    // If the value is numeric, convert it.
    if (!isNaN(value)) {
      value = semOptions[parseInt(value)] || value;
    }
    Handsontable.renderers.TextRenderer.apply(this, [instance, td, row, col, prop, value, cellProperties]);
  }
},
                {
  data: 'academic_year',
  type: 'dropdown',
  source: academicYears,
  title: 'Academic Year'
},                {
                    data: 'date_of_graduation',
                    type: 'date',
                    dateFormat: 'YYYY-MM-DD',
                    renderer: dateRenderer,
                    title: 'Date of Grad'
                },
                {
  data: 'semester2',
  type: 'dropdown',
  source: ['First','Second','Summer'],
  title: 'Semester2',
  renderer: function(instance, td, row, col, prop, value, cellProperties) {
    const semOptions = ['First', 'Second', 'Summer'];
    if (!isNaN(value)) {
      value = semOptions[parseInt(value)] || value;
    }
    Handsontable.renderers.TextRenderer.apply(this, [instance, td, row, col, prop, value, cellProperties]);
  }
},
                { data: 'academic_year2', title: 'AY2' }
            ];

            // 6) Initialize Handsontable
            let container = document.getElementById('soMasterListContainer');
            let hot = new Handsontable(container, {
                colHeaders: columns.map(c => c.title),
                columns: columns,
                data: [{}], // a single blank row at start
                rowHeaders: true,
                className: 'htHorizonLight',
                stretchH: 'all',
                width: '100%',
                height: 600,
                minSpareRows: 1,
                licenseKey: 'non-commercial-and-evaluation',
                dropdownMenu: true,
                filters: true,
                contextMenu: {
                    items: {
                        'remove_row': {
                            name: 'Delete Row',
                            callback: function(key, selection) {
                                let rowIndex = selection.start.row;
                                let rowData = hot.getSourceDataAtRow(rowIndex);
                                if (rowData.id) {
                                    if (confirm('Are you sure you want to delete this record?')) {
                                        $.ajax({
                                            url: '/admin/so_master_lists/' + rowData.id,
                                            method: 'DELETE',
                                            success: function() {
                                                toastr.success('Record deleted successfully!');
                                                hot.alter('remove_row', rowIndex);
                                            },
                                            error: function() {
                                                toastr.error('Error deleting record.');
                                            }
                                        });
                                    }
                                } else {
                                    toastr.warning('Cannot delete an empty row.');
                                }
                            }
                        },
                        'undo': {},
                        'redo': {},
                        'copy': {},
                        'cut': {},
                        'paste': {},
                        'separator': Handsontable.plugins.ContextMenu.SEPARATOR,
                        'row_above': {},
                        'row_below': {},
                        'col_left': {},
                        'col_right': {}
                    }
                },

                afterChange: function(changes, source) {
    if (source === 'loadData' || !changes) return;

    let rowsToUpdate = {};
    let newEntries = []; // To store new rows: { row: rowIndex, data: rowData }

    changes.forEach(function(change) {
        let rowIndex = change[0],
            prop = change[1],
            oldVal = change[2],
            newVal = change[3];

        // Skip if no actual change
        if (oldVal === newVal) return;

        let rowData = hot.getSourceDataAtRow(rowIndex);
        let recordId = rowData.id;

        // If newVal is an empty string, convert it to null
        if (newVal === "") {
            newVal = null;
            hot.setDataAtRowProp(rowIndex, prop, newVal, 'edit');
        }

        // If the HEI name changes, auto-fill the corresponding HEI UII
        if (prop === 'hei_name') {
            let matchedUii = heiOptions[newVal];
            if (matchedUii) {
                hot.setDataAtRowProp(rowIndex, 'hei_uii', matchedUii, 'edit');
            }
        }

        // Map Program Name to its numeric ID and update PSCED Code
        if (prop === 'program_id') {
            if (!isNaN(newVal)) {
    newVal = parseInt(newVal);
    // Update PSCED code using the numeric ID.
    hot.setDataAtRowProp(rowIndex, 'psced_code', programOptionsRaw[newVal].psced_code, 'edit');
  } else {
    // Otherwise, lookup the program name.
    let matchedId = programNameToId[newVal]; // newVal is the program name
    if (matchedId) {
      newVal = parseInt(matchedId);
      hot.setDataAtRowProp(rowIndex, 'program_id', newVal, 'edit');
      hot.setDataAtRowProp(rowIndex, 'psced_code', programOptionsRaw[matchedId].psced_code, 'edit');
    } else {
      hot.setDataAtRowProp(rowIndex, 'program_id', oldVal, 'edit');
      toastr.error('Unknown Program: ' + newVal);
      return;
    }
  }
}
        // Map Major Name to its numeric ID
        if (prop === 'major_id') {
  if (!isNaN(newVal)) {
    newVal = parseInt(newVal);
    hot.setDataAtRowProp(rowIndex, 'major_id', newVal, 'edit');
  } else {
    let matchedMid = majorNameToId[newVal]; // newVal is the major name
    if (matchedMid) {
      newVal = parseInt(matchedMid);
      hot.setDataAtRowProp(rowIndex, 'major_id', newVal, 'edit');
    } else {
      hot.setDataAtRowProp(rowIndex, 'major_id', oldVal, 'edit');
      toastr.error('Unknown Major: ' + newVal);
      return;
    }
  }
}
if (prop === 'semester') {
  const semOptions = ['First', 'Second', 'Summer'];
  // If newVal is numeric, convert it.
  if (!isNaN(newVal)) {
      newVal = semOptions[parseInt(newVal)] || newVal;
      hot.setDataAtRowProp(rowIndex, 'semester', newVal, 'edit');
  } else if (typeof newVal === 'string') {
      // If it’s already a string, you can optionally trim it
      newVal = newVal.trim();
      // Optionally, verify it’s one of the allowed values.
      if (!semOptions.includes(newVal)) {
          hot.setDataAtRowProp(rowIndex, 'semester', oldVal, 'edit');
          toastr.error('Invalid semester: ' + newVal);
          return;
      }
  }
}
if (prop === 'semester2') {
  const semOptions = ['First', 'Second', 'Summer'];
  if (!isNaN(newVal)) {
      newVal = semOptions[parseInt(newVal)] || newVal;
      hot.setDataAtRowProp(rowIndex, 'semester2', newVal, 'edit');
  } else if (typeof newVal === 'string') {
      newVal = newVal.trim();
      if (!semOptions.includes(newVal)) {
          hot.setDataAtRowProp(rowIndex, 'semester2', oldVal, 'edit');
          toastr.error('Invalid semester2: ' + newVal);
          return;
      }
  }
}

        // If there is no record ID, then it’s a new row
        if (!recordId) {
            let requiredFields = [
                'hei_name','hei_uii','last_name','first_name',
                'sex','program_id','major_id','started',
                'date_of_application','total'
            ];
            // Check if all required fields are present
            let allFilled = requiredFields.every(f => rowData[f]);
            if (allFilled) {
                newEntries.push({ row: rowIndex, data: rowData });
            }
            return;
        }

        // For existing records, accumulate the changes to update
        if (!rowsToUpdate[rowIndex]) {
            rowsToUpdate[rowIndex] = { id: recordId, data: {} };
        }
        rowsToUpdate[rowIndex].data[prop] = newVal;
    });

    // Process updates for existing records via AJAX
    Object.values(rowsToUpdate).forEach(function(updateObj) {
        $.ajax({
            url: '/admin/so_master_lists/' + updateObj.id + '/inline',
            method: 'PUT',
            data: updateObj.data,
            dataType: 'json',
            success: function(response) {
                toastr.success('Record ' + updateObj.id + ' updated successfully.');
            },
            error: function(xhr) {
                let msg = 'An unknown error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    msg = Object.values(xhr.responseJSON.error).flat().join("\n");
                }
                toastr.error('Error updating record ' + updateObj.id + ':\n' + msg);
            }
        });
    });

    // Process new records via AJAX
    newEntries.forEach(function(entryObj) {
        $.ajax({
            url: '/admin/so_master_lists',
            method: 'POST',
            data: entryObj.data,
            dataType: 'json',
            success: function(response) {
                toastr.success('New record added successfully.');
                // Update the row's "No." column with the new record's ID
                hot.setDataAtRowProp(entryObj.row, 'id', response.data.id, 'edit');
                // Insert a new blank row for further entries
                hot.alter('insert_row', hot.countRows());
            },
            error: function(xhr) {
                let msg = 'An unknown error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    msg = Object.values(xhr.responseJSON.error).flat().join("\n");
                }
                toastr.error('Error adding new record:\n' + msg);
            }
        });
    });
}

            });
            loadData();

// Attach change event to the program filter to reload the grid with the selected program ID


            // 7) Load existing data on page load
         
            // 9) Filter by Program name (column index for program_id is 13 above)

        });
        
    </script>
@endpush
@endsection
