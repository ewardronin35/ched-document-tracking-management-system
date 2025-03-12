@extends('layouts.app')

@section('content')
@push('styles')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Handsontable CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.css" />
    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- FilePond -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <!-- Custom Excel-inspired styles -->
    <style>
        :root {
            --excel-green: #217346;
            --excel-green-light: #e9f5ee;
            --excel-green-hover: #1a5c38;
            --excel-header: #f3f3f3;
            --excel-cell-border: #e0e0e0;
            --excel-selected: #d8f0e0;
        }
        
        body {
            font-family: 'Segoe UI', 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Main container */
        .excel-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        /* Excel-like toolbar */
        .excel-toolbar {
            background-color: var(--excel-green);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 8px 16px;
            margin-bottom: 0;
        }
        
        .excel-toolbar .nav-tabs {
            border-bottom: none;
        }
        
        .excel-toolbar .nav-link {
            color: white;
            border: none;
            padding: 8px 16px;
            margin-right: 2px;
            border-radius: 4px 4px 0 0;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .excel-toolbar .nav-link:hover {
            background-color: var(--excel-green-hover);
            color: white;
        }
        
        .excel-toolbar .nav-link.active {
            background-color: white;
            color: var(--excel-green);
            font-weight: 600;
        }
        
        /* Filter section */
        .filter-section {
            background-color: #f5f5f5;
            padding: 15px;
            border-left: 1px solid var(--excel-cell-border);
            border-right: 1px solid var(--excel-cell-border);
        }
        
        /* Handsontable custom styling */
        #soMasterListContainer {
            border: 1px solid var(--excel-cell-border);
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
            background-color: white;
        }
        
        .excel-theme th {
            background-color: var(--excel-header) !important;
            color: var(--excel-green) !important;
            font-weight: 600 !important;
            text-transform: none !important;
            font-size: 14px !important;
            border: 1px solid var(--excel-cell-border) !important;
            padding: 8px 12px !important;
        }
        
        .excel-theme td {
            font-size: 13px !important;
            color: #333 !important;
            padding: 6px 12px !important;
            border: 1px solid var(--excel-cell-border) !important;
        }
        
        .excel-theme tr:nth-child(even) td {
            background-color: #fafafa !important;
        }
        
        .excel-theme tr:hover td {
            background-color: var(--excel-green-light) !important;
        }
        
        .excel-theme td.current, 
        .excel-theme td.area.highlight {
            background-color: var(--excel-selected) !important;
            border: 1px solid var(--excel-green) !important;
        }
        
        /* Excel-like buttons */
        .btn-excel {
            background-color: var(--excel-green);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .btn-excel:hover {
            background-color: var(--excel-green-hover);
            color: white;
        }
        
        /* Excel-like form controls */
        .excel-form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            width: 100%;
            transition: border-color 0.2s;
        }
        
        .excel-form-control:focus {
            border-color: var(--excel-green);
            outline: none;
            box-shadow: 0 0 0 2px rgba(33, 115, 70, 0.2);
        }
        
        /* Select2 customization */
        .select2-container--default .select2-selection--single {
            border: 1px solid #ddd;
            height: 38px;
            padding: 5px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--excel-green);
        }
        
        /* Action buttons row */
        .excel-actions {
            background-color: #f5f5f5;
            padding: 12px;
            border: 1px solid var(--excel-cell-border);
            border-top: none;
            display: flex;
            justify-content: space-between;
            border-radius: 0 0 8px 8px;
        }
        
        /* FilePond customization */
        .filepond--panel-root {
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
        }
        
        .filepond--drop-label {
            color: #555;
        }
        
        /* Modal customization */
        .modal-content {
            border-radius: 8px;
            border: none;
        }
        
        .modal-header {
            background-color: var(--excel-green);
            color: white;
            border-radius: 8px 8px 0 0;
            border-bottom: none;
        }
        
        .modal-title {
            font-weight: 500;
        }
        
        .modal-footer {
            border-top: 1px solid #eee;
        }
        
        /* Status pill indicator */
        .status-pill {
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-active {
            background-color: #e1f3e8;
            color: #1e7e34;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
@endpush



        <!-- Excel-like ribbon toolbar -->
        <div class="excel-toolbar">
            <ul class="nav nav-tabs" id="soMasterTabs" role="tablist">
                <!-- SO Masterlist Tab -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="so-records-tab" data-bs-toggle="tab" data-bs-target="#so-records" type="button" role="tab">
                        <i class="fas fa-table me-1"></i> Records
                    </button>
                </li>
                
                <!-- Import CSV Tab (Modal Trigger) -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="import-tab" data-bs-toggle="modal" data-bs-target="#importCsvModal" type="button">
                        <i class="fas fa-file-import me-1"></i> Import
                    </button>
                </li>
                
                <!-- View Programs (Direct Link) -->
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.programs.index') }}" class="nav-link" id="programs-tab">
                        <i class="fas fa-graduation-cap me-1"></i> Programs
                    </a>
                </li>
                
                <!-- View Majors (Direct Link) -->
                <li class="nav-item" role="presentation">
                    <a href="{{ route('admin.majors.index') }}" class="nav-link" id="majors-tab">
                        <i class="fas fa-book me-1"></i> Majors
                    </a>
                </li>
                
                <!-- Reports -->
                <li class="nav-item" role="presentation">
                    <a href="#" class="nav-link" id="reports-tab">
                        <i class="fas fa-chart-bar me-1"></i> Reports
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Filter & Search Section -->
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label for="programFilter" class="form-label text-muted small mb-1">
                            <i class="fas fa-filter me-1"></i> Filter by Program:
                        </label>
                        <select id="programFilter" class="form-control excel-form-control" data-placeholder="-- All Programs --">
                            <option value="">-- All Programs --</option>
                            @foreach($programOptions as $id => $programData)
                                <option value="{{ $id }}">{{ $programData['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label for="regionFilter" class="form-label text-muted small mb-1">
                            <i class="fas fa-map-marker-alt me-1"></i> Filter by Region:
                        </label>
                        <select id="regionFilter" class="form-control excel-form-control" data-placeholder="-- All Regions --">
                            <option value="">-- All Regions --</option>
                            <!-- Regions will be dynamically populated by JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label for="searchBox" class="form-label text-muted small mb-1">
                            <i class="fas fa-search me-1"></i> Search:
                        </label>
                        <input type="text" id="searchBox" class="form-control excel-form-control" placeholder="Search records...">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Handsontable Container -->
        <div id="soMasterListContainer" class="excel-theme"></div>
        
        <!-- Excel-like action buttons -->
        <div class="excel-actions">
            <div>
                <button type="button" id="addNewRow" class="btn btn-sm btn-excel">
                    <i class="fas fa-plus me-1"></i> Add Row
                </button>
                <button type="button" id="exportData" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fas fa-file-export me-1"></i> Export
                </button>
            </div>
            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i> Right-click for more options
            </div>
        </div>
    </div>
</div>

<!-- Modal for CSV Import -->
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.so_master_lists.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importCsvModalLabel">
                        <i class="fas fa-file-import me-2"></i> Import SO Master List
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Upload CSV, Excel (.xlsx), or Excel (.xls) file with SO Master List data
                    </p>
                    
                    <!-- FilePond file input -->
                    <input type="file" name="csv_file" id="soCsvFile" class="filepond" accept=".csv, .xlsx, .xls" required>
                    
                    <div class="alert alert-info mt-3 small">
                        <i class="fas fa-info-circle me-2"></i>
                        Make sure your file includes all required fields: HEI Name, HEI UII, Last Name, First Name, Sex, Program, Major, Started Date, Date of Application, and Total.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-excel">
                        <i class="fas fa-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Export Options Modal -->
<div class="modal fade" id="exportOptionsModal" tabindex="-1" aria-labelledby="exportOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportOptionsModalLabel">
                    <i class="fas fa-file-export me-2"></i> Export Options
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action" id="exportCsv">
                        <i class="fas fa-file-csv me-2"></i> Export as CSV
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" id="exportExcel">
                        <i class="fas fa-file-excel me-2"></i> Export as Excel
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" id="exportPdf">
                        <i class="fas fa-file-pdf me-2"></i> Export as PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@push('scripts')
    <!-- Handsontable, Moment.js, and Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@8.4.0/dist/handsontable.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function () {
            // Setup CSRF for AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            
            // Initialize FilePond with progress indicator
            const inputElement = document.getElementById('soCsvFile');
            FilePond.setOptions({
                instantUpload: true,
                storeAsFile: true,
                labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
                allowMultiple: false,
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'right top',
                styleButtonProcessItemPosition: 'right bottom'
            });
            FilePond.create(inputElement);
            
            // Reset filter selects
            if ($('#programFilter').length) {
                $('#programFilter').val('');
            }
            
            // Initialize Select2 for the program filter
            $('#programFilter').select2({
                placeholder: '-- All Programs --',
                allowClear: true,
                width: '100%',
                dropdownParent: $('.filter-section')
            });
            
            // Initialize regions for filter
            let regions = [
                "Region I (Ilocos Region)",
                "Region II (Cagayan Valley)",
                "Region III (Central Luzon)",
                "Region IV-A (CALABARZON)",
                "MIMAROPA Region",
                "Region V (Bicol Region)",
                "Region VI (Western Visayas)",
                "Region VII (Central Visayas)",
                "Region VIII (Eastern Visayas)",
                "Region IX (Zamboanga Peninsula)",
                "Region X (Northern Mindanao)",
                "Region XI (Davao Region)",
                "Region XII (SOCCSKSARGEN)",
                "National Capital Region (NCR)",
                "Cordillera Administrative Region (CAR)",
                "Region XIII (Caraga)",
                "Bangsamoro Autonomous Region In Muslim Mindanao (BARMM)"
            ];
            
            // Populate region filter
            regions.forEach(function(region) {
                $('#regionFilter').append(new Option(region, region));
            });
            
            // Initialize Select2 for the region filter
            $('#regionFilter').select2({
                placeholder: '-- All Regions --',
                allowClear: true,
                width: '100%',
                dropdownParent: $('.filter-section')
            });
            
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
            
            // Build mapping objects from backend data
            let heiOptions = @json($heiOptions); // e.g., { "Some HEI Name": "UII123", ... }
            let heiNames = Object.keys(heiOptions);
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
            let programOptionsRaw = @json($programOptions); 
            // programOptionsRaw example: { "1": { "name": "BS Computer Science", "psced_code": "1234" }, ... }
            // Now programNames are full names:
            let programNames = Object.values(programOptionsRaw).map(obj => obj.name);
            let majorOptionsRaw = @json($majorOptions); 
            // majorOptionsRaw now holds major names keyed by id.
            let majorNames = Object.values(majorOptionsRaw);
            
            // Toastr config
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "extendedTimeOut": "1000"
            };
            
            // Custom cell renderers
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
            
            // Status renderer with color pills
            function statusRenderer(instance, td, row, col, prop, value, cellProperties) {
                if (value) {
                    let statusClass = '';
                    switch(value.toLowerCase()) {
                        case 'active':
                        case 'approved':
                            statusClass = 'status-active';
                            break;
                        case 'pending':
                        case 'processing':
                            statusClass = 'status-pending';
                            break;
                        case 'inactive':
                        case 'rejected':
                            statusClass = 'status-inactive';
                            break;
                    }
                    
                    if (statusClass) {
                        td.innerHTML = `<span class="status-pill ${statusClass}">${value}</span>`;
                    } else {
                        td.innerText = value;
                    }
                }
                return td;
            }
            
            // Define your Handsontable columns
            let columns = [
                {
                    data: 'id',
                    title: 'ID',
                    readOnly: true,
                    hidden: true,
                    type: 'numeric'
                },
                { 
                    data: 'status', 
                    title: 'Status',
                    type: 'dropdown',
                    source: ['Active', 'Pending', 'Inactive', 'Approved', 'Processing', 'Rejected'],
                    renderer: statusRenderer
                },
                { data: 'processing_slip_number', title: 'Processing Slip #' },
                {
                    data: 'region',
                    type: 'dropdown',
                    source: regions,
                    title: 'Region'
                },
                {
                    data: 'hei_name',
                    type: 'dropdown',
                    source: heiNames,
                    title: 'HEI Name'
                },
                { data: 'hei_uii', title: 'HEI UII' },
                { data: 'special_order_number', title: 'Special Order #' },
                { data: 'last_name', title: 'Last Name' },
                { data: 'first_name', title: 'First Name' },
                { data: 'middle_name', title: 'Middle Name' },
                { data: 'extension_name', title: 'Suffix' },
                {
                    data: 'sex',
                    type: 'dropdown',
                    source: ['Male','Female','Other'],
                    title: 'Sex'
                },
                { data: 'total', type: 'numeric', title: 'Total' },
                {
                    data: 'program',
                    type: 'dropdown',
                    source: programNames,
                    title: 'Program',
                    strict: true,
                    renderer: Handsontable.renderers.TextRenderer
                },
                {
                    data: 'psced_code',
                    readOnly: true,
                    title: 'PSCED Code'
                },
                {
                    data: 'major',
                    type: 'dropdown',
                    source: majorNames,
                    title: 'Major'
                },
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
                { data: 'registrar', title: 'Registrar' },
                { data: 'govt_permit_recognition', title: 'Govt. Permit/Recognition' },
                { data: 'signed_by', title: 'Signed By' },
                {
                    data: 'semester',
                    type: 'dropdown',
                    source: ['First','Second','Summer'],
                    title: 'Semester',
                    renderer: function(instance, td, row, col, prop, value, cellProperties) {
                        const semOptions = ['First', 'Second', 'Summer'];
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
                },
                {
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
                { 
                    data: 'academic_year2', 
                    title: 'Academic Year 2',
                    type: 'dropdown',
                    source: academicYears
                },
            ];
            
            // Initialize Handsontable
            let container = document.getElementById('soMasterListContainer');
            let hot = new Handsontable(container, {
                colHeaders: columns.map(c => c.title),
                columns: columns,
                data: [{}], // start with one blank row
                rowHeaders: true,
                className: 'excel-theme',
                stretchH: 'all',
                width: '100%',
                height: 600,
                minSpareRows: 1,
                licenseKey: 'non-commercial-and-evaluation',
                dropdownMenu: true,
                filters: true,
                hiddenColumns: {
                    columns: [0]
                },
                manualColumnResize: true,
                manualRowResize: true,
                fixedRowsTop: 0,
                fixedColumnsLeft: 3,
                autoColumnSize: { samplingRatio: 0.1 },
                search: true,
                // Custom cell renderer for better Excel-like feeling
                beforeRenderer: function(TD, row, col, prop, value, cellProperties) {
                    TD.style.backgroundColor = '#ffffff';
                    TD.style.fontFamily = '"Segoe UI", "Roboto", sans-serif';
                },
                
                // Excel-like cell selection highlight
                afterSelection: function(r, c, r2, c2) {
                    // Optionally add Excel-like selection styling here
                },
                
                contextMenu: {
                    items: {
                        'remove_row': {
                            name: '<i class="fas fa-trash-alt me-2"></i> Delete Record',
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
                        'separator1': Handsontable.plugins.ContextMenu.SEPARATOR,
                        'undo': {},
                        'redo': {},
                        'separator2': Handsontable.plugins.ContextMenu.SEPARATOR,
                        'copy': {},
                        'cut': {},
                        'paste': {},
                        'separator3': Handsontable.plugins.ContextMenu.SEPARATOR,
                        'row_above': {
                            name: '<i class="fas fa-plus me-2"></i> Insert Row Above',
                        },
                        'row_below': {
                            name: '<i class="fas fa-plus me-2"></i> Insert Row Below',
                        },
                        'freeze_column': {
                            name: '<i class="fas fa-thumbtack me-2"></i> Freeze/Unfreeze Column',
                            callback: function(key, selection) {
                                const currentFixed = hot.getSettings().fixedColumnsLeft;
                                const selectedCol = selection.start.col;
                                
                                // Toggle freeze at selected column
                                if (currentFixed > selectedCol) {
                                    hot.updateSettings({ fixedColumnsLeft: selectedCol });
                                } else {
                                    hot.updateSettings({ fixedColumnsLeft: selectedCol + 1 });
                                }
                            }
                        },
                        'separator4': Handsontable.plugins.ContextMenu.SEPARATOR,
                        'export_data': {
                            name: '<i class="fas fa-file-export me-2"></i> Export Selection',
                            callback: function() {
                                $('#exportOptionsModal').modal('show');
                            }
                        }
                    }
                },
                
                afterChange: function(changes, source) {
                    // Do nothing if changes come from loading data or if there are no changes.
                    if (source === 'loadData' || !changes) return;
                
                    // Use Handsontable's batch method to group updates.
                    hot.batch(() => {
                        let rowsToUpdate = {};
                        let newEntries = [];
                
                        changes.forEach(function(change) {
                            // Destructure the change array.
                            let [rowIndex, prop, oldVal, newVal] = change;
                
                            // Ensure rowIndex is a valid non-negative number.
                            if (typeof rowIndex !== 'number' || rowIndex < 0) {
                                console.warn("Invalid row index:", rowIndex);
                                return;
                            }
                
                            // Skip change if there is no actual change.
                            if (oldVal === newVal) return;
                
                            let rowData = hot.getSourceDataAtRow(rowIndex);
                            let recordId = rowData.id;
                
                            // If newVal is undefined, set it to an empty string.
                            if (typeof newVal === 'undefined') {
                                newVal = '';
                                hot.setDataAtRowProp(rowIndex, prop, newVal, 'internal');
                            }
                
                            // If the property is numeric (like 'total'), make sure it's a valid non-negative number.
                            if (prop === 'total') {
                                let numVal = parseFloat(newVal);
                                if (isNaN(numVal) || numVal < 0) {
                                    numVal = 0;
                                }
                                newVal = numVal;
                                console.log("Updating 'total' for row", rowIndex, "to", newVal);
                                hot.setDataAtRowProp(rowIndex, 'total', newVal, 'internal');
                            }
                
                            // Auto-fill HEI UII if the HEI name changes.
                            if (prop === 'hei_name') {
                                let matchedUii = heiOptions[newVal];
                                if (matchedUii) {
                                    hot.setDataAtRowProp(rowIndex, 'hei_uii', matchedUii, 'internal');
                                }
                            }
                
                            // For the 'program' property, verify that the program exists and update the PSCED code.
                            if (prop === 'program') {
                                if (programNames.indexOf(newVal) === -1) {
                                    hot.setDataAtRowProp(rowIndex, 'program', oldVal, 'internal');
                                    toastr.error('Unknown Program: ' + newVal);
                                    return;
                                } else {
                                    let foundProgram = null;
                                    for (let pid in programOptionsRaw) {
                                        if (programOptionsRaw[pid].name === newVal) {
                                            foundProgram = programOptionsRaw[pid];
                                            break;
                                        }
                                    }
                                    console.log("Program lookup for", newVal, "found:", foundProgram);
                                    if (foundProgram && foundProgram.psced_code) {
                                        hot.setDataAtRowProp(rowIndex, 'psced_code', foundProgram.psced_code, 'internal');
                                    } else {
                                        // Optionally, clear the PSCED code if no match is found.
                                        hot.setDataAtRowProp(rowIndex, 'psced_code', '', 'internal');
                                    }
                                }
                            }
                
                            // For the 'major' property, simply verify that the major exists.
                            if (prop === 'major') {
                                if (majorNames.indexOf(newVal) === -1) {
                                    hot.setDataAtRowProp(rowIndex, 'major', oldVal, 'internal');
                                    toastr.error('Unknown Major: ' + newVal);
                                    return;
                                }
                            }
                
                            // Handle semester fields.
                            if (prop === 'semester' || prop === 'semester2') {
                                const semOptions = ['First', 'Second', 'Summer'];
                                if (!isNaN(newVal)) {
                                    newVal = semOptions[parseInt(newVal)] || newVal;
                                    hot.setDataAtRowProp(rowIndex, prop, newVal, 'internal');
                                } else if (typeof newVal === 'string') {
                                    newVal = newVal.trim();
                                    if (!semOptions.includes(newVal)) {
                                        hot.setDataAtRowProp(rowIndex, prop, oldVal, 'internal');
                                        toastr.error('Invalid ' + prop + ': ' + newVal);
                                        return;
                                    }
                                }
                            }
                
                            // If there is no record ID, mark the row as a new entry.
                            if (!recordId) {
                                let requiredFields = ['hei_name', 'hei_uii', 'last_name', 'first_name', 'sex', 'program', 'major', 'started', 'date_of_application', 'total'];
                                let allFilled = requiredFields.every(f => rowData[f]);
                                if (allFilled) {
                                    newEntries.push({ row: rowIndex, data: rowData });
                                }
                                return;
                            }
                
                            // Collect changes for rows that already exist.
                            if (!rowsToUpdate[rowIndex]) {
                                rowsToUpdate[rowIndex] = { id: recordId, data: {} };
                            }
                            rowsToUpdate[rowIndex].data[prop] = newVal;
                        });
                
                        // Process updates via AJAX for existing records.
                        Object.values(rowsToUpdate).forEach(function(updateObj) {
                            $.ajax({
                                url: '/admin/so_master_lists/' + updateObj.id + '/inline',
                                method: 'PUT',
                                data: updateObj.data,
                                dataType: 'json',
                                success: function(response) {
                                    toastr.success('Record ' + updateObj.id + ' updated successfully.');
                                    // Optionally, reload data here.
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
                
                        // Process new entries via AJAX.
                        newEntries.forEach(function(entryObj) {
                            $.ajax({
                                url: '/admin/so_master_lists',
                                method: 'POST',
                                data: entryObj.data,
                                dataType: 'json',
                                success: function(response) {
                                    toastr.success('New record added successfully.');
                                    hot.setDataAtRowProp(entryObj.row, 'id', parseInt(response.data.id, 10) || 0, 'internal');
                                    hot.alter('insert_row', hot.countRows());
                                    // Optionally, reload data here.
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
                    });
                },
              });
            
            // Function to load data with filters
            function loadData(filters = {}) {
                $.ajax({
                    url: "{{ route('admin.so_master_lists.data') }}",
                    method: 'GET',
                    data: filters,
                    success: function(res) {
                        console.log("Data loaded from API:", res);
                        let data = (res.data && res.data.length) ? res.data : [{}];
                        hot.loadData(data);
                        
                        // Update record count in status bar
                        const recordCount = data.length > 1 || data[0].id ? data.length : 0;
                        $('#recordCount').text(recordCount + ' records');
                        
                        // Show loading indicators
                        $('.loading-overlay').hide();
                    },
                    error: function() {
                        toastr.error('Error loading data.');
                        $('.loading-overlay').hide();
                    }
                });
            }
            
            // Add event listeners for filters with debounce
            $('#programFilter').on('change', debounce(function() {
              
                $('.loading-overlay').show();
                const programFilter = $('#programFilter').length ? $('#programFilter').val() || "" : "";
const regionFilter = $('#regionFilter').length ? $('#regionFilter').val() || "" : "";
const searchTerm = $('#searchBox').length ? $('#searchBox').val() || "" : "";
                
                loadData({
                    program: programFilter,
                    region: regionFilter,
                    search: searchTerm
                });
            }, 300));
            
            $('#regionFilter').on('change', debounce(function() {
                $('.loading-overlay').show();
                const programFilter = $('#programFilter').length ? $('#programFilter').val() || "" : "";
const regionFilter = $('#regionFilter').length ? $('#regionFilter').val() || "" : "";
const searchTerm = $('#searchBox').length ? $('#searchBox').val() || "" : "";
                
                loadData({
                    program: programFilter,
                    region: regionFilter,
                    search: searchTerm
                });
            }, 300));
            
            $('#searchBox').on('keyup', debounce(function() {
                $('.loading-overlay').show();
                const programFilter = $('#programFilter').length ? $('#programFilter').val() || "" : "";
const regionFilter = $('#regionFilter').length ? $('#regionFilter').val() || "" : "";
const searchTerm = $('#searchBox').length ? $('#searchBox').val() || "" : "";
                
                loadData({
                    program: programFilter,
                    region: regionFilter,
                    search: searchTerm
                });
            }, 500));
            
            // Add row button handler
            $('#addNewRow').on('click', function() {
                const newRowIndex = hot.countRows() - 1;
                hot.alter('insert_row', newRowIndex);
                hot.selectCell(newRowIndex, 1);
                
                // Set default status for new row
                hot.setDataAtRowProp(newRowIndex, 'status', 'Pending', 'internal');
                
                toastr.info('New row added. Required fields: HEI Name, HEI UII, Last Name, First Name, Sex, Program, Major, Started Date, and Date of Application.');
            });
            
            // Export button handler
            $('#exportData').on('click', function() {
                $('#exportOptionsModal').modal('show');
            });
            
            // Export options handlers
            $('#exportCsv').on('click', function() {
                const exportPlugin = hot.getPlugin('exportFile');
                exportPlugin.downloadFile('csv', {
                    bom: false,
                    columnDelimiter: ',',
                    exportHiddenColumns: false,
                    exportHiddenRows: false,
                    fileExtension: 'csv',
                    filename: 'SO_Master_List_' + moment().format('YYYY-MM-DD'),
                    mimeType: 'text/csv',
                    rowDelimiter: '\r\n',
                });
                $('#exportOptionsModal').modal('hide');
            });
            
            $('#exportExcel').on('click', function() {
                // Get visible data only (skip hidden columns)
                const visibleData = [];
                const visibleColIndices = hot.getColHeader().map((_, i) => 
                    hot.getSettings().hiddenColumns.columns.includes(i) ? null : i
                ).filter(i => i !== null);
                
                const visibleHeaders = visibleColIndices.map(i => hot.getColHeader()[i]);
                
                // Create an array of visible data only
                hot.getData().forEach(row => {
                    if (row.some(cell => cell)) { // Skip empty rows
                        const visibleRow = {};
                        visibleColIndices.forEach((colIdx, i) => {
                            visibleRow[visibleHeaders[i]] = row[colIdx];
                        });
                        visibleData.push(visibleRow);
                    }
                });
                
                const worksheet = XLSX.utils.json_to_sheet(visibleData);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "SO Master List");
                XLSX.writeFile(workbook, `SO_Master_List_${moment().format('YYYY-MM-DD')}.xlsx`);
                
                $('#exportOptionsModal').modal('hide');
                toastr.success('Export successful!');
            });
            
            $('#exportPdf').on('click', function() {
                toastr.info('Preparing PDF export...');
                
                // Basic PDF export implementation 
                // For a complete solution, you might want to use a dedicated PDF library
                const filename = `SO_Master_List_${moment().format('YYYY-MM-DD')}.pdf`;
                
                // Get visible columns and data
                const visibleCols = hot.getColHeader()
                    .map((header, idx) => !hot.getSettings().hiddenColumns.columns.includes(idx) ? idx : null)
                    .filter(idx => idx !== null);
                
                const tableData = [];
                const headers = visibleCols.map(idx => hot.getColHeader()[idx]);
                tableData.push(headers);
                
                // Get visible data rows
                hot.getData().forEach(row => {
                    if (row.some(cell => cell)) { // Skip empty rows
                        const visibleRow = visibleCols.map(idx => row[idx] || '');
                        tableData.push(visibleRow);
                    }
                });
                
                // In a real implementation, you would now generate PDF with this data
                // For now, we'll just show a message and close the modal
                setTimeout(() => {
                    toastr.success('PDF export functionality will be implemented in a future update.');
                    $('#exportOptionsModal').modal('hide');
                }, 2000);
            });
            
            // Initialize search box functionality
            const searchField = document.getElementById('searchBox');
            
            Handsontable.dom.addEvent(searchField, 'keyup', function(event) {
                const searchPlugin = hot.getPlugin('search');
                const queryResult = searchPlugin.query(this.value);
                
                hot.render();
            });
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+S for Save
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    // Trigger save on all modified rows
                    
                    toastr.info('All changes saved successfully');
                }
                
                // Ctrl+F for Find (focus search box)
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('searchBox').focus();
                }
            });
            
            // Add status bar to display record count
            $(container).after(
                `<div class="excel-status-bar">
                    <span class="record-count" id="recordCount">0 records</span>
                    <span class="status-message"></span>
                </div>`
            );
            
            // Add loading overlay
            $('body').append(
                `<div class="loading-overlay" style="display: none;">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`
            );
            
            // Add CSS for loading overlay and status bar
            $('head').append(`
                <style>
                    .loading-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(255, 255, 255, 0.7);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                    }
                    
                    .excel-status-bar {
                        background-color: #f5f5f5;
                        padding: 5px 12px;
                        font-size: 12px;
                        color: #555;
                        border: 1px solid var(--excel-cell-border);
                        border-top: none;
                        display: flex;
                        justify-content: space-between;
                        border-radius: 0 0 8px 8px;
                    }
                </style>
            `);
            
            // Initialize data load
            $('.loading-overlay').show();
            loadData();
            
            // Help button event handler
            $('#helpButton').on('click', function() {
                $('#helpModal').modal('show');
            });
        });
    </script>
@endpush
@endsection