@extends('layouts.app')

@section('content')

@push('styles')
  <!-- Handsontable CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
  
  <!-- FilePond CSS and Plugins -->
  <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
  <link href="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.css" rel="stylesheet">
  <link href="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.css" rel="stylesheet">
  
  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
      body, .handsontable .ht_master .htCore td, .handsontable .ht_master thead th {
      font-family: 'Poppins', sans-serif !important;
    }
    /* Handsontable wrapper styling */
    .handsontable-wrapper {
      margin-top: 20px;
      width: 100%;
      height: 750px;
      overflow-x: auto;
      border: 1px solid #dee2e6;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      background-color: #fff;
      font-family: 'Roboto', Arial, sans-serif;
      font-size: 16px;
      color: #333;
      border-radius: 4px;
    }
    .handsontable .ht_master thead th {
      background: linear-gradient(180deg, #f0f0f5, #e0e0eb);
      color: #212529;
      border-bottom: 2px solid #007bff;
      font-weight: bold;
    }
    .handsontable .ht_master .htCore td {
      padding: 0.75rem;
      border-right: 1px solid #dee2e6;
      border-bottom: 1px solid #dee2e6;
      transition: background-color 0.2s ease;
    }
    .handsontable .wtHolder .wtHider table tbody tr:nth-child(odd) {
      background-color: #f9f9f9;
    }
    .handsontable .wtHolder .wtHider table tbody tr:hover {
      background-color: #e9ecef;
      cursor: pointer;
    }
    .handsontable .wtBorder.current {
      border: 2px solid #007bff !important;
    }
    /* Button styles */
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
    }
    /* Custom tab styling */
    .nav-tabs .nav-link {
      font-weight: 500;
      color: #495057;
    }
    .nav-tabs .nav-link.active {
      background-color: #fff;
      border-color: #dee2e6 #dee2e6 #fff;
      color: #212529;
    }
  </style>
@endpush
  <!-- Filter Today's Records Button (applies to CAV Records tab) -->

  <!-- Navigation Tabs -->
  <ul class="nav nav-tabs" id="excelTabs" role="tablist">
  
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="records-tab" data-bs-toggle="tab" data-bs-target="#records" type="button" role="tab">
        <i class="fa fa-table"></i> CAV Records
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="local-tab" data-bs-toggle="tab" data-bs-target="#local" type="button" role="tab">
        <i class="fa fa-building"></i> CAV Local
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="abroad-tab" data-bs-toggle="tab" data-bs-target="#abroad" type="button" role="tab">
        <i class="fa fa-globe"></i> CAV Abroad
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="osd-tab" data-bs-toggle="tab" data-bs-target="#osd" type="button" role="tab">
        <i class="fa fa-clipboard-check"></i> CAV‑OSD
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="cert-tab" data-bs-toggle="tab" data-bs-target="#cert" type="button" role="tab">
        <i class="fa fa-certificate"></i> Certifications
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="docauth-tab" data-bs-toggle="tab" data-bs-target="#docauth" type="button" role="tab">
        <i class="fa fa-file-alt"></i> Document Authentications
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="condobpob-tab" data-bs-toggle="tab" data-bs-target="#condobpob" type="button" role="tab">
        <i class="fa fa-file-alt"></i> CondoBPOB
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab">
        <i class="fa fa-upload"></i> Import
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="reports" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
        <i class="fa fa-file-alt"></i> Reports
      </button>
    </li>
    <li class="nav-item" role="presentation">
    <button id="filter-today" class="btn btn-primary">
      <i class="fa fa-filter"></i> Filter Today's Records
    </button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="excelTabsContent">
    <!-- Import Tab -->
    <div class="tab-pane fade" id="import" role="tabpanel">
      <div class="mt-3">
        <div class="mb-3">
          <input type="file" id="filepond-input" name="file" multiple />
        </div>
        <div class="mb-3">
          <button class="btn btn-primary" id="import-btn">
            <i class="fa fa-file-import"></i> Import Data
          </button>
        </div>
      </div>
    </div>
    <!-- CAV Records Tab -->
    <div class="tab-pane fade show active" id="records" role="tabpanel">
      <div class="handsontable-wrapper">  
        <div id="handsontable-container-records"></div>
      </div>
    </div>
    <!-- CAV Local Tab -->
    <div class="tab-pane fade" id="local" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-local"></div>
      </div>
    </div>
    <!-- CAV Abroad Tab -->
    <div class="tab-pane fade" id="abroad" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-abroad"></div>
      </div>
    </div>
    <!-- CAV‑OSD Tab -->
    <div class="tab-pane fade" id="osd" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-osd"></div>
      </div>
    </div>
    <!-- Certifications Tab -->
    <div class="tab-pane fade" id="cert" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-cert"></div>
      </div>
    </div>
    <!-- Document Authentications Tab -->
    <div class="tab-pane fade" id="docauth" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-docauth"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="condobpob" role="tabpanel">
  <div class="handsontable-wrapper">
    <div id="handsontable-container-condobpob"></div>
  </div>
</div>
  </div>
</div>

@endsection

@push('scripts')
  <!-- Handsontable Script -->
  <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
  <!-- FilePond JS and Plugins -->
  <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
  <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
  <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
  <!-- Toastr for notifications -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

  <script>
    // Global required validator for Handsontable cells.


    function requiredValidator(value, callback) {
      callback(value !== null && value !== undefined && value !== '');
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Global object to store Handsontable instances by container ID.
      var hotInstances = {};

      // Initialize FilePond for file uploads in the Import tab.
      FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
      );
      const pond = FilePond.create(document.querySelector('#filepond-input'), {
        acceptedFileTypes: [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ],
        maxFiles: 5,
        server: {
          process: {
            url: '/admin/cavs/import-excel', // updated route
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            onload: (response) => {
              console.log('File uploaded successfully:', response);
            },
            onerror: (response) => {
              console.error('File upload error:', response);
            }
          }
        }
      });
      function quarterRenderer(instance, td, row, col, prop, value, cellProperties) {
  // Use the built-in text renderer first.
  Handsontable.renderers.TextRenderer.apply(this, arguments);
  if (value !== null && value !== undefined && value !== '') {
    td.innerHTML = "Q" + value;
  } else {
    td.innerHTML = '';
  }
}
      // Column definitions for the different tables.

      // For CAV Records, CAV Local, CAV Abroad (assumed the same)
      var columnsRecords = [
        { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
        { data: 'cav_no', title: 'CAV No', validator: requiredValidator },
        { data: 'region', title: 'Region', validator: requiredValidator },
        { data: 'surname', title: 'Surname', validator: requiredValidator },
        { data: 'first_name', title: 'First Name', validator: requiredValidator },
        { data: 'extension_name', title: 'Extension Name' },
        { data: 'middle_name', title: 'Middle Name' },
        { data: 'sex', title: 'Sex', type: 'dropdown', source: ['Male', 'Female'], validator: requiredValidator },
        { data: 'institution_code', title: 'Institution Code' },
        { data: 'full_name_of_hei', title: 'Full Name of HEI', type: 'dropdown', source: @json($heiOptions), validator: requiredValidator },
        { data: 'address_of_hei', title: 'Address of HEI', validator: requiredValidator },
        { data: 'official_receipt_number', title: 'Official Receipt No.', validator: requiredValidator },
        { data: 'type_of_heis', title: 'Type of HEIs', validator: requiredValidator },
        { data: 'discipline_code', title: 'Discipline Code', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', type: 'dropdown', source: @json($programOptions), validator: requiredValidator },
        { data: 'major', title: 'Major', validator: requiredValidator },
        { data: 'program_level', title: 'Program Level', validator: requiredValidator },
        { data: 'status_of_the_program', title: 'Status', validator: requiredValidator },
        { data: 'date_started', title: 'Date Started', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_ended', title: 'Date Ended', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'graduation_date', title: 'Graduation Date', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'units_earned', title: 'Units Earned', type: 'numeric', validator: requiredValidator },
        { data: 'special_order_no', title: 'Special Order No.', validator: requiredValidator },
        { data: 'series', title: 'Series' },
        { data: 'date_applied', title: 'Date Applied', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'airway_bill_no', title: 'Airway Bill No.', validator: requiredValidator },
        { data: 'serial_number_of_security_paper', title: 'Serial Number of Security Paper', validator: requiredValidator },
        { data: 'target_country', title: 'Target Country', validator: requiredValidator }
      ];

      // For CAV Local and CAV Abroad, use the same columns.
      var columnsLocal = columnsRecords;
      var columnsAbroad = columnsRecords;

      // For CAV‑OSD.
      var columnsOSD = [
        { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
        { data: 'o', title: 'O-', validator: requiredValidator },
        { data: 'seq', title: 'Sequence No.', validator: requiredValidator },
        { data: 'cav_osds', title: 'CAV-OSD', validator: requiredValidator },
        { data: 'certification', title: 'Certification', validator: requiredValidator },
        { data: 'surname', title: 'Surname', validator: requiredValidator },
        { data: 'first_name', title: 'First Name', validator: requiredValidator },
        { data: 'extension_name', title: 'Extension Name' },
        { data: 'middle_name', title: 'Middle Name' },
        { data: 'full_name_of_hei', title: 'Full Name of HEI', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', validator: requiredValidator },
        { data: 'major', title: 'Major', validator: requiredValidator },
        { data: 'date_of_entry', title: 'Date of Entry', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_ended', title: 'Date Ended', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'year_graduated', title: 'Year Graduated', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'so_no', title: 'SO No.', validator: requiredValidator },
        { data: 'or_no', title: 'OR No.', validator: requiredValidator },
        { data: 'date_applied', title: 'Date Applied', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'remarks', title: 'Remarks' }
      ];

      // For Certifications.
      var columnsCert = [
        { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
        { data: 'o_prefix', title: 'O-', validator: requiredValidator },
        { data: 'cav_no', title: 'CAV No.', validator: requiredValidator },
        { data: 'certificate', title: 'Certificate', validator: requiredValidator },
        { data: 'surname', title: 'Surname', validator: requiredValidator },
        { data: 'first_name', title: 'First Name', validator: requiredValidator },
        { data: 'extension_name', title: 'Extension Name' },
        { data: 'middle_name', title: 'Middle Name' },
        { data: 'full_name_of_hei', title: 'Full Name of HEI', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', validator: requiredValidator },
        { data: 'major', title: 'Major', validator: requiredValidator },
        { data: 'date_started', title: 'Date Started', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_ended', title: 'Date Ended', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'year_graduated', title: 'Year Graduated', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'so_no', title: 'SO No.', validator: requiredValidator },
        { data: 'or_no', title: 'OR No.', validator: requiredValidator },
        { data: 'date_applied', title: 'Date Applied', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'remarks', title: 'Remarks' }
      ];

      // For Document Authentications.
      var columnsDocAuth = [
        { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
        { data: 'No', title: 'No.', validator: requiredValidator },
        { data: 'document_type', title: 'Document Type', validator: requiredValidator },
        { data: 'surname', title: 'Surname', validator: requiredValidator },
        { data: 'first_name', title: 'First Name', validator: requiredValidator },
        { data: 'extension_name', title: 'Extension Name' },
        { data: 'middle_name', title: 'Middle Name' },
        { data: 'full_name_of_hei', title: 'Full Name of HEI', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', validator: requiredValidator },
        { data: 'major', title: 'Major', validator: requiredValidator },
        { data: 'date_of_entry', title: 'Date of Entry', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_ended', title: 'Date Ended', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'year_graduated', title: 'Year Graduated', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'so_no', title: 'SO No.', validator: requiredValidator },
        { data: 'or_no', title: 'OR No.', validator: requiredValidator },
        { data: 'date_applied', title: 'Date Applied', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'remarks', title: 'Remarks' }
      ];
      
      var columnsCondobpob = [
  { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
  { data: 'No', title: 'No.', validator: requiredValidator },
  { data: 'surname', title: 'Surname', validator: requiredValidator },
  { data: 'first_name', title: 'First Name', validator: requiredValidator },
  { data: 'extension_name', title: 'Extension Name' },
  { data: 'middle_name', title: 'Middle Name' },
  { data: 'sex', title: 'Sex', type: 'dropdown', source: ['Male', 'Female'], validator: requiredValidator },
  { data: 'or_number', title: 'OR. No.', validator: requiredValidator },
  { data: 'name_of_hei', title: 'Name of HEI', validator: requiredValidator },
  { data: 'special_order_no', title: 'Special Order No.', validator: requiredValidator },
  { data: 'type_of_correction', title: 'Type of Correction', validator: requiredValidator },
  { data: 'from_date', title: 'From Date', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
  { data: 'to_date', title: 'To Date', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
  { data: 'date_applied', title: 'Date Applied', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
  { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },  
  // Add the rest of your fields here...
];
      // Generic function to initialize a Handsontable instance with custom columns.
      function initHandsontableWithColumns(containerId, dataEndpoint, columnsConfig) {
        var container = document.getElementById(containerId);
        fetch(dataEndpoint)
          .then(response => response.json())
          .then(data => {
            var hot = new Handsontable(container, {
              data: data,
              columns: columnsConfig,
              colHeaders: columnsConfig.map(col => col.title),
              rowHeaders: true,
              stretchH: 'all',
              rowHeights: 40,
              dropdownMenu: true,
              filters: true,
              minSpareRows: 1,
              contextMenu: true,
              manualRowResize: true,
              licenseKey: 'non-commercial-and-evaluation',
              cells: function(row, col) {
                let cellProperties = {};
                if (row % 2 === 0) {
                  cellProperties.className = 'even-row';
                }
                cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
                  Handsontable.renderers.TextRenderer.apply(this, arguments);
                  if (instance.getDataAtCell(row, col) === null || instance.getDataAtCell(row, col) === '') {
                    td.style.background = '#ded9d9';
                  }
                };
                return cellProperties;
              },
              afterChange: function(changes, source) {
  if (source === 'edit' && changes) {
    // We'll use an object to store timeouts for each row.
    if (!this._saveTimeouts) {
      this._saveTimeouts = {};
    }
    // Collect unique rows that changed.
    const rowsToSave = new Set();
    changes.forEach(([row, prop, oldValue, newValue]) => {
      if (oldValue !== newValue) {
        rowsToSave.add(row);
      }
    });
    // For each row, debounce the save by 500ms.
    rowsToSave.forEach(row => {
      // Clear any existing timeout for this row.
      if (this._saveTimeouts[row]) {
        clearTimeout(this._saveTimeouts[row]);
      }
      // Set a new timeout.
      this._saveTimeouts[row] = setTimeout(() => {
        const hot = this;
        const rowData = hot.getSourceDataAtRow(row);
        const payload = { ...rowData };
        let baseUrl = '/admin/cav';
        if (containerId === 'handsontable-container-osd') {
          baseUrl = '/admin/cavs_osd';
        } else if (containerId === 'handsontable-container-cert') {
          baseUrl = '/admin/certifications';
        } else if (containerId === 'handsontable-container-docauth') {
          baseUrl = '/admin/document_authentications';
        } else if (containerId === 'handsontable-container-condobpob') {
          baseUrl = '/admin/condobpobs';
        }

        let url = rowData.id ? `${baseUrl}/${rowData.id}` : baseUrl;
        let method = rowData.id ? 'PUT' : 'POST';
        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        })
        .then(response => {
          if (!response.ok) {
            return response.json().then(errorData => {
              throw new Error(JSON.stringify(errorData.errors));
            });
          }
          return response.json();
        })
        .then(data => {
          toastr.success('Record saved successfully.');
          if (!rowData.id && data.id) {
            hot.setDataAtRowProp(row, 'id', data.id);
          }
        })
        .catch(error => {
          toastr.error('Error saving record.');
          console.error(error);
        });
        // Remove the timeout flag.
        delete hot._saveTimeouts[row];
      }, 500);
    });
  }
}

            });
            hotInstances[containerId] = hot;
            if (['handsontable-container-records', 'handsontable-container-local', 'handsontable-container-abroad'].includes(containerId)) {
  hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
    if (coords.row === hot.countRows() - 1) {
      // Autofill Quarter (column 0)
      var quarterVal = hot.getDataAtCell(coords.row, 0);
      if (!quarterVal) {
        var month = new Date().getMonth() + 1;
        var quarter = month <= 3 ? 1 : month <= 6 ? 2 : month <= 9 ? 3 : 4;
        hot.setDataAtCell(coords.row, 0, quarter);
      }
      // Autofill CAV No (column 1) as plain 4-digit number.
      var cavVal = hot.getDataAtCell(coords.row, 1);
      if (!cavVal) {
        var maxCav = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 1);
          if (value) {
            var num = parseInt(value, 10);
            if (!isNaN(num) && num > maxCav) { maxCav = num; }
          }
        }
        var newCav = (maxCav + 1).toString().padStart(4, '0');
        hot.setDataAtCell(coords.row, 1, newCav);
      }
    }
  });
}

// For CAV‑OSD (containerId: handsontable-container-osd)
// Expected column order: 
// 0: Quarter, 1: O-, 2: Sequence No., 3: CAV-OSD, then others.
if (containerId === 'handsontable-container-osd') {
  hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
    // Only run if on the spare (new) row.
    if (coords.row === hot.countRows() - 1) {
      var rowData = hot.getSourceDataAtRow(coords.row);
      // Only auto-fill if we haven't already marked this row.
      if (!rowData._autoFilled) {
        // Autofill Quarter (column 0)
        var month = new Date().getMonth() + 1;
        var quarter = month <= 3 ? 1 : month <= 6 ? 2 : month <= 9 ? 3 : 4;
        hot.setDataAtCell(coords.row, 0, quarter);

        // Autofill O- (column 1) in format "O-0001"
        var maxO = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 1);
          if (value) {
            var s = String(value);
            if (s.indexOf("O-") === 0) {
              s = s.substring(2);
            }
            var n = parseInt(s, 10);
            if (!isNaN(n) && n > maxO) { maxO = n; }
          }
        }
        var newO = "O-" + (maxO + 1).toString().padStart(4, '0');
        hot.setDataAtCell(coords.row, 1, newO);

        // Autofill Sequence No. (column 2) in format "CAV-OSDS-YYYY-0001"
        var currentYear = new Date().getFullYear();
        var prefix = "CAV-OSDS-" + currentYear + "-";
        var maxSeq = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 2);
          if (value && String(value).indexOf(prefix) === 0) {
            var seqNumStr = String(value).substring(prefix.length);
            var seqNum = parseInt(seqNumStr, 10);
            if (!isNaN(seqNum) && seqNum > maxSeq) { maxSeq = seqNum; }
          }
        }
        var newSeq = prefix + (maxSeq + 1).toString().padStart(4, '0');
        hot.setDataAtCell(coords.row, 2, newSeq);
        // Autofill CAV-OSD (column 3) with the same sequence value.
        hot.setDataAtCell(coords.row, 3, newSeq);

        // Mark this row as auto-filled so that subsequent clicks won't re-trigger auto-fill.
        hot.setDataAtRowProp(coords.row, '_autoFilled', true);
      }
    }
  });
}


// For Certifications (containerId: handsontable-container-cert)
// Expected column order: 
// 0: Quarter, 1: O-, 2: CAV No., 3: Certificate, ...
if (containerId === 'handsontable-container-cert') {
  hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
    if (coords.row === hot.countRows() - 1) {
      // Autofill Quarter (column 0)
      var quarterVal = hot.getDataAtCell(coords.row, 0);
      if (!quarterVal) {
        var month = new Date().getMonth() + 1;
        var quarter = month <= 3 ? 1 : month <= 6 ? 2 : month <= 9 ? 3 : 4;
        hot.setDataAtCell(coords.row, 0, quarter);
      }
      // Autofill O- (column 1) in format "O-0001"
      var oVal = hot.getDataAtCell(coords.row, 1);
      if (!oVal) {
        var maxO = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 1);
          if (value) {
            var numString = value.indexOf("O-") === 0 ? value.substring(2) : value;
            var num = parseInt(numString, 10);
            if (!isNaN(num) && num > maxO) { maxO = num; }
          }
        }
        var newO = "O-" + (maxO + 1).toString().padStart(4, '0');
        hot.setDataAtCell(coords.row, 1, newO);
      }
      // Autofill CAV No. (column 2) as plain 4-digit number.
      var cavVal = hot.getDataAtCell(coords.row, 2);
      if (!cavVal) {
        var maxCav = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 2);
          if (value) {
            var num = parseInt(value, 10);
            if (!isNaN(num) && num > maxCav) { maxCav = num; }
          }
        }
        var newCav = (maxCav + 1).toString().padStart(4, '0');
        hot.setDataAtCell(coords.row, 2, newCav);
      }
    }
  });
}

// For Document Authentications (containerId: handsontable-container-docauth)
// Expected column order: 
// 0: Quarter, 1: No., 2: Document Type, ...
if (containerId === 'handsontable-container-docauth') {
  hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
    if (coords.row === hot.countRows() - 1) {
      // Autofill Quarter (column 0)
      var quarterVal = hot.getDataAtCell(coords.row, 0);
      if (!quarterVal) {
        var month = new Date().getMonth() + 1;
        var quarter = month <= 3 ? 1 : month <= 6 ? 2 : month <= 9 ? 3 : 4;
        hot.setDataAtCell(coords.row, 0, quarter);
      }
      // Autofill No. (column 1) as sequential integer.
      var noVal = hot.getDataAtCell(coords.row, 1);
      if (!noVal) {
        var maxNo = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 1);
          if (value) {
            var num = parseInt(value, 10);
            if (!isNaN(num) && num > maxNo) { maxNo = num; }
          }
        }
        
        var newNo = maxNo + 1;
        hot.setDataAtCell(coords.row, 1, newNo);
      }
    }
  });
}
if (containerId === 'handsontable-container-condobpob') {
  hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
    // Only run if on the spare (new) row.
    if (coords.row === hot.countRows() - 1) {
      // Autofill Quarter (assume it's column 0)
      var quarterVal = hot.getDataAtCell(coords.row, 0);
      if (!quarterVal) {
        // Calculate quarter from current month.
        var month = new Date().getMonth() + 1;
        var quarter = month <= 3 ? 1 : month <= 6 ? 2 : month <= 9 ? 3 : 4;
        hot.setDataAtCell(coords.row, 0, quarter);
      }
      // Autofill No. (assume it's column 1) as a sequential integer.
      var noVal = hot.getDataAtCell(coords.row, 1);
      if (!noVal) {
        var maxNo = 0;
        for (var i = 0; i < hot.countRows(); i++) {
          if (i === coords.row) continue;
          var value = hot.getDataAtCell(i, 1);
          if (value) {
            var num = parseInt(value, 10);
            if (!isNaN(num) && num > maxNo) { maxNo = num; }
          }
        }
        var newNo = maxNo + 1;
        hot.setDataAtCell(coords.row, 1, newNo);
      }
    }
  });
}
})
    .catch(error => {
      console.error('Error fetching data for', containerId, error);
    });
}
      // Initialize Handsontable instances for each tab.
      initHandsontableWithColumns('handsontable-container-records', "{{ route('admin.cav.all') }}", columnsRecords);
      initHandsontableWithColumns('handsontable-container-local', "{{ route('admin.cav.local') }}", columnsLocal);
      initHandsontableWithColumns('handsontable-container-abroad', "{{ route('admin.cav.abroad') }}", columnsAbroad);
      initHandsontableWithColumns('handsontable-container-osd', "{{ route('admin.cavs_osd.all') }}", columnsOSD);
      initHandsontableWithColumns('handsontable-container-cert', "{{ route('admin.certifications.all') }}", columnsCert);
      initHandsontableWithColumns('handsontable-container-docauth', "{{ route('admin.document_authentications.all') }}", columnsDocAuth);
      initHandsontableWithColumns('handsontable-container-condobpob', "{{ route('admin.condobpobs.all') }}", columnsCondobpob);

      // Filter Today's Records functionality for the "CAV Records" tab.
      document.getElementById('filter-today').addEventListener('click', function() {
        const today = new Date().toISOString().split('T')[0];
        fetch("{{ route('admin.cav.all') }}")
          .then(response => response.json())
          .then(data => {
            const filteredData = data.filter(row => row.date_released && row.date_released.startsWith(today));
            const hot = hotInstances['handsontable-container-records'];
            if (hot) hot.loadData(filteredData);
          })
          .catch(error => {
            console.error("Error fetching data for today's filter:", error);
          });
      });

      // Import button functionality.
      document.getElementById('import-btn').addEventListener('click', function() {
        alert('Import function not implemented yet.');
      });
    });
  
  </script>
@endpush
 