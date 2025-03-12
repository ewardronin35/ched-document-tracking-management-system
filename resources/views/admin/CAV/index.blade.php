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
    body {
      font-family: 'Segoe UI', 'Calibri', sans-serif !important;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }
    
    /* Excel-like container */
    .excel-container {
  display: flex;
  flex-direction: column;
  height: 100vh; /* Full viewport height */
  max-height: 100vh;
  overflow: hidden; /* Prevent container scrolling */
}

    
    /* Excel-like toolbar */
    .excel-toolbar {
      display: flex;
      background-color: #1e4392; /* CHED Blue */
      color: white;
      padding: 8px 16px;
      align-items: center;
      border-bottom: 1px solid #1a3a7c;
      flex-wrap: wrap;
    }
    
    .toolbar-group {
      display: flex;
      margin-right: 20px;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .toolbar-title {
      font-size: 18px;
      font-weight: 500;
      margin-right: 30px;
      white-space: nowrap;
    }
    
    .toolbar-btn {
      background-color: transparent;
      border: none;
      color: white;
      cursor: pointer;
      padding: 5px 10px;
      margin: 0 3px;
      border-radius: 3px;
      font-size: 13px;
      display: flex;
      align-items: center;
      transition: background-color 0.2s;
    }
    
    .toolbar-btn:hover {
      background-color: #15346e;
    }
    
    .toolbar-btn i {
      margin-right: 5px;
    }
    
    /* Excel-like ribbon */
    .excel-ribbon {
      display: flex;
      background-color: #f3f2f1;
      padding: 8px 16px;
      border-bottom: 1px solid #d6d6d6;
      align-items: center;
      flex-wrap: wrap;
    }
    
    .ribbon-btn {
      background-color: transparent;
      border: 1px solid transparent;
      color: #333;
      cursor: pointer;
      padding: 5px 10px;
      margin: 0 3px;
      border-radius: 3px;
      font-size: 13px;
      display: flex;
      align-items: center;
    }
    
    .ribbon-btn:hover {
      background-color: #e6e6e6;
      border: 1px solid #d6d6d6;
    }
    
    .ribbon-btn i {
      margin-right: 5px;
      font-size: 14px;
    }
    
    /* Excel-like tabs */
    .excel-tabs-container {
      background-color: #f5f5f5;
      border-bottom: 1px solid #d6d6d6;
      overflow-x: auto;
      white-space: nowrap;
      padding-left: 10px;
      display: flex;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .excel-tabs {
      display: inline-flex;
      margin: 0;
      padding: 0;
      list-style: none;
    }
    
    .excel-tab {
      display: inline-block;
      padding: 8px 15px;
      background-color: #e6e6e6;
      border: 1px solid #d6d6d6;
      border-bottom: none;
      margin-right: 2px;
      border-radius: 3px 3px 0 0;
      overflow: hidden;

      cursor: pointer;
      color: #666;
      font-size: 13px;
      position: relative;
      transition: all 0.3s ease;
      top: 1px;
    }
    .excel-tab::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #1e4392;
  transition: width 0.3s ease;
}

.excel-tab.active::before {
  width: 100%;
}

    .excel-tab:hover {
      background-color: #f0f0f0;
    }
    
    .excel-tab.active {
      background-color: white;
      color: #1e4392;
      border-bottom: 2px solid white;
      font-weight: 500;
    }
    
    .excel-tab i {
      margin-right: 6px;
      color: #1e4392;
    }
    
    /* Excel-like grid content */
    .excel-content {
      flex: 1;
      position: relative;
      overflow: hidden;
      background-color: white;
    }
    
    .tab-pane {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: none;
    }
    
    .tab-pane.active {
      display: block;
    }
    @keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.7;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.excel-content.loading .tab-pane.active {
  animation: pulse 1.5s infinite;
}
    /* Handsontable Excel styling */
    .handsontable-wrapper {
      width: 100%;
      height: 100%;
      overflow: hidden;
      background-color: white;
    }
    
    .handsontable {
      font-family: 'Calibri', 'Segoe UI', sans-serif !important;
    }
    
    .handsontable .ht_master thead th {
      background: linear-gradient(180deg, #f7f7f7, #e1e1e1);
      color: #1e4392;
      border-right: 1px solid #d6d6d6;
      border-bottom: 1px solid #a9a9a9;
      font-weight: bold;
      padding: 5px;
      text-align: center;
    }
    
    .handsontable .ht_master .htCore td {
      border-right: 1px solid #e1e1e1;
      border-bottom: 1px solid #e1e1e1;
      padding: 4px 7px;
      font-size: 13px;
    }
    
    .handsontable .ht_master .htCore td.current {
      border: 1px solid #1e4392 !important;
    }
    
    .handsontable .htBorder.current {
      border: 2px solid #1e4392 !important;
    }
    
    .handsontable .htBorder.area {
      background-color: rgba(30, 67, 146, 0.1) !important;
      border: 1px solid #1e4392 !important;
    }
    
    /* Filter control panel */
    .filter-controls {
      padding: 10px;
      background-color: white;
      border-bottom: 1px solid #d6d6d6;
      display: none;
    }
    
    .filter-controls.active {
      display: block;
    }
    
    /* Search functionality */
    .search-box {
      position: relative;
      min-width: 200px;
      margin-left: auto;
    }
    
    .search-box input {
      width: 100%;
      padding: 8px 30px 8px 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    
    .search-box button {
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      background: none;
      border: none;
      cursor: pointer;
      padding: 0 10px;
    }
    
    /* Import panel styling */
    .import-panel {
      padding: 30px;
      max-width: 700px;
      margin: 20px auto;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }
    
    .import-panel h3 {
      color: #1e4392;
      margin-bottom: 20px;
    }
    
    .filepond--root {
      margin-bottom: 20px;
    }
    
    .filepond--panel-root {
      background-color: #f5f5f5;
      border: 1px dashed #1e4392;
    }
    
    /* Status bar */
    .excel-statusbar {
      display: flex;
      background-color: #f3f2f1;
      border-top: 1px solid #d6d6d6;
      padding: 5px 15px;
      color: #666;
      font-size: 12px;
      justify-content: space-between;
    }
    
    /* Toast styling */
    .toastr-top-right {
      top: 60px;
      right: 15px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
      .toolbar-title {
        font-size: 16px;
        margin-right: 15px;
      }
      
      .excel-tabs-container {
        padding-left: 5px;
      }
      
      .excel-tab {
        padding: 6px 10px;
        font-size: 12px;
      }
    }
    
    @media (max-width: 768px) {
      .excel-toolbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
      }
      
      .toolbar-title {
        margin-bottom: 8px;
      }
      
      .toolbar-group {
        margin-right: 0;
        margin-bottom: 8px;
        flex-wrap: wrap;
      }
      
      .search-box {
        width: 100%;
        margin-top: 8px;
      }
      
      .excel-ribbon {
        flex-wrap: wrap;
      }
      
      .ribbon-btn {
        margin-bottom: 5px;
      }
    }
  </style>
@endpush

@section ('content')
<div class="excel-container">
  <!-- Excel-like Toolbar -->
  <div class="excel-toolbar">
    <div class="toolbar-title">CAV Documents</div>
 
    <div class="toolbar-group">
      <button class="toolbar-btn" id="filter-today"><i class="fas fa-filter"></i> Today's Records</button>
      <button class="toolbar-btn"><i class="fas fa-print"></i> Print</button>
      <button class="toolbar-btn"><i class="fas fa-download"></i> Export</button>
      <button class="toolbar-btn" id="ribbon-filter"><i class="fas fa-filter"></i> Filter</button>
      <button class="toolbar-btn" id="ribbon-sort"><i class="fas fa-sort"></i> Sort</button>
      <button class="toolbar-btn" id="ribbon-find"><i class="fas fa-search"></i> Find</button>
      <button class="toolbar-btn" id="ribbon-add-row"><i class="fas fa-plus"></i> Add Row</button>
      <button class="toolbar-btn" id="ribbon-delete-row"><i class="fas fa-trash-alt"></i> Delete Row</button>
      <button class="toolbar-btn" id="ribbon-paste"><i class="fas fa-paste"></i> Paste</button>
    </div>
    
    <!-- Search Box -->
    <div class="search-box">
      <input type="text" id="search-input" placeholder="Search..." aria-label="Search records">
      <button type="button" id="search-button"><i class="fas fa-search"></i></button>
    </div>
  </div>
  
  <!-- Excel-like Ribbon -->

  
  <!-- Excel-like Filter Controls (hidden by default) -->
  <div class="filter-controls" id="filter-panel">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label>Date Range:</label>
          <select class="form-control form-control-sm" id="filter-date-range">
            <option value="all">All Dates</option>
            <option value="today">Today</option>
            <option value="this-week">This Week</option>
            <option value="this-month">This Month</option>
            <option value="this-quarter">This Quarter</option>
            <option value="custom">Custom...</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Region:</label>
          <select class="form-control form-control-sm" id="filter-region">
            <option value="all">All Regions</option>
            <option value="09-zamboanga-peninsula">09-Zamboanga Peninsula</option>
            <option value="region-i">Region I</option>
            <option value="region-ii">Region II</option>
            <option value="region-iii">Region III</option>
            <!-- Add more regions as needed -->
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Status:</label>
          <select class="form-control form-control-sm" id="filter-status">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button class="btn btn-primary btn-sm d-block w-100" id="apply-filters">Apply Filters</button>
        </div>
      </div>
    </div>
    
    <!-- Custom date range (hidden by default) -->
    <div class="row mt-2" id="custom-date-range" style="display: none;">
      <div class="col-md-3">
        <div class="form-group">
          <label>From:</label>
          <input type="date" class="form-control form-control-sm" id="filter-date-from">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>To:</label>
          <input type="date" class="form-control form-control-sm" id="filter-date-to">
        </div>
      </div>
    </div>
  </div>
  
  <!-- Excel-like Tabs -->
  <div class="excel-tabs-container">
    <ul class="excel-tabs" role="tablist">
      <li class="excel-tab active" id="records-tab" data-bs-toggle="tab" data-bs-target="#records">
        <i class="fas fa-table"></i> CAV Records
      </li>
      <li class="excel-tab" id="local-tab" data-bs-toggle="tab" data-bs-target="#local">
        <i class="fas fa-building"></i> CAV Local
      </li>
      <li class="excel-tab" id="abroad-tab" data-bs-toggle="tab" data-bs-target="#abroad">
        <i class="fas fa-globe"></i> CAV Abroad
      </li>
      <li class="excel-tab" id="osd-tab" data-bs-toggle="tab" data-bs-target="#osd">
        <i class="fas fa-clipboard-check"></i> CAV‑OSD
      </li>
      <li class="excel-tab" id="cert-tab" data-bs-toggle="tab" data-bs-target="#cert">
        <i class="fas fa-certificate"></i> Certifications
      </li>
      <li class="excel-tab" id="docauth-tab" data-bs-toggle="tab" data-bs-target="#docauth">
        <i class="fas fa-file-alt"></i> Document Authentications
      </li>
      <li class="excel-tab" id="condobpob-tab" data-bs-toggle="tab" data-bs-target="#condobpob">
        <i class="fas fa-file-contract"></i> CondoBPOB
      </li>
      <li class="excel-tab" id="import-tab" data-bs-toggle="tab" data-bs-target="#import">
        <i class="fas fa-upload"></i> Import
      </li>
      <li class="excel-tab" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports">
        <i class="fas fa-chart-bar"></i> Reports
      </li>
    </ul>
  </div>
  
  <!-- Excel-like Content Area -->
  <div class="excel-content">
    <!-- Import Tab -->
    <div class="tab-pane" id="import">
      <div class="import-panel">
        <h3><i class="fas fa-file-import"></i> Import Excel Data</h3>
        <p>Upload your Excel files to import data into the system. Supported formats: .xlsx, .xls</p>
        <div class="mb-4">
          <input type="file" id="filepond-input" name="file" multiple />
        </div>
        <div class="d-flex justify-content-between">
          <button class="btn btn-outline-secondary">Cancel</button>
          <button class="btn btn-primary" id="import-btn">
            <i class="fas fa-file-import"></i> Import Data
          </button>
        </div>
      </div>
    </div>
    
    <!-- Reports Tab -->
    <div class="tab-pane" id="reports">
      <div class="import-panel">
      <h3><i class="fas fa-chart-bar"></i> Reports</h3>
      <p>Generate interactive reports from your data.</p>
      <div class="row mb-4">
        <!-- Chart Section -->
        <div class="col-md-12">
        <div class="card mb-3">
          <div class="card-body">
          <h5 class="card-title">Reports Chart</h5>
          <canvas id="reportsChart" style="max-height:300px;"></canvas>
          </div>
        </div>
        </div>
        <!-- HEI Volume Input Section -->
        <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
          <h5 class="card-title">HEI Volume Analysis</h5>
          <div class="form-group mb-3">
            <label for="hei-select">Select HEI:</label>
            <select class="form-control" id="hei-select">
            <option value="">-- Select HEI --</option>
            <option value="hei1">HEI 1</option>
            <option value="hei2">HEI 2</option>
            <option value="hei3">HEI 3</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="volume-input">Input Volume:</label>
            <input type="number" class="form-control" id="volume-input" placeholder="Enter volume">
          </div>
          <div class="form-group mb-3">
            <label for="total-volume">Auto Total for Selected HEI:</label>
            <input type="number" class="form-control" id="total-volume" readonly value="0">
          </div>
          <button class="btn btn-primary" id="calculate-volume">Calculate Total</button>
          </div>
        </div>
        </div>
        <!-- Additional Reports Options -->
        <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
          <h5 class="card-title">CAV Records Summary</h5>
          <p class="card-text">Generate a summary report of all CAV records.</p>
          <button class="btn btn-primary">Generate Summary</button>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
          <h5 class="card-title">Regional Distribution</h5>
          <p class="card-text">View the distribution of records by region.</p>
          <button class="btn btn-primary">Generate Distribution</button>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    
    <!-- CAV Records Tab -->
    <div class="tab-pane active" id="records">
      <div class="handsontable-wrapper">  
        <div id="handsontable-container-records"></div>
      </div>
    </div>
    
    <!-- CAV Local Tab -->
    <div class="tab-pane" id="local">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-local"></div>
      </div>
    </div>
    
    <!-- CAV Abroad Tab -->
    <div class="tab-pane" id="abroad">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-abroad"></div>
      </div>
    </div>
    
    <!-- CAV‑OSD Tab -->
    <div class="tab-pane" id="osd">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-osd"></div>
      </div>
    </div>
    
    <!-- Certifications Tab -->
    <div class="tab-pane" id="cert">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-cert"></div>
      </div>
    </div>
    
    <!-- Document Authentications Tab -->
    <div class="tab-pane" id="docauth">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-docauth"></div>
      </div>
    </div>
    
    <!-- CondoBPOB Tab -->
    <div class="tab-pane" id="condobpob">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-condobpob"></div>
      </div>
    </div>
  </div>
  
  <!-- Excel-like Status Bar -->
  <div class="excel-statusbar">
    <div>Ready</div>
    <div>Records: <span id="record-count">0</span></div>
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
    // Configure toastr position
    toastr.options = {
      positionClass: 'toast-top-right',
      timeOut: 3000,
      closeButton: true
    };
    
    // Required validator for Handsontable cells
    function requiredValidator(value, callback) {
      callback(value !== null && value !== undefined && value !== '');
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Global object to store Handsontable instances and original data
      var hotInstances = {};
      var originalData = {};
      
      // Excel-like tab switching
      const tabButtons = document.querySelectorAll('.excel-tab');
      const tabPanes = document.querySelectorAll('.tab-pane');
      
      tabButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all tabs
          tabButtons.forEach(btn => btn.classList.remove('active'));
          tabPanes.forEach(pane => pane.classList.remove('active'));
          
          // Add active class to clicked tab
          this.classList.add('active');
          const targetId = this.getAttribute('data-bs-target').substring(1);
          document.getElementById(targetId).classList.add('active');
        });
      });
      
      // Filter panel toggle
      document.getElementById('ribbon-filter').addEventListener('click', function() {
        const filterPanel = document.getElementById('filter-panel');
        filterPanel.classList.toggle('active');
      });

      // Custom date range toggle
      document.getElementById('filter-date-range').addEventListener('change', function() {
        const customDateRange = document.getElementById('custom-date-range');
        if (this.value === 'custom') {
          customDateRange.style.display = 'flex';
        } else {
          customDateRange.style.display = 'none';
        }
      });

      // Initialize FilePond for file uploads in the Import tab
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
        labelIdle: 'Drag & Drop your Excel files or <span class="filepond--label-action">Browse</span>',
        server: {
          process: {
            url: '/admin/cavs/import-excel',
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            onload: (response) => {
              toastr.success('File uploaded successfully!', 'Success');
              console.log('File uploaded successfully:', response);
              setTimeout(() => {
                location.reload();
              }, 1000);
            },
            onerror: (response) => {
              toastr.error('Error uploading file. Please try again.', 'Error');
              console.error('File upload error:', response);
            }
          }
        }
      });
      
      // Quarter renderer for Handsontable
      function quarterRenderer(instance, td, row, col, prop, value, cellProperties) {
        // Use the built-in text renderer first
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        if (value !== null && value !== undefined && value !== '') {
          td.innerHTML = "Q" + value;
        } else {
          td.innerHTML = '';
        }
      }
      
      // Column definitions for the different tables
      
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
        { data: 'full_name_of_hei', title: 'Full Name of HEI', type: 'dropdown', source: @json($heiOptions ?? []), validator: requiredValidator },
        { data: 'address_of_hei', title: 'Address of HEI', validator: requiredValidator },
        { data: 'official_receipt_number', title: 'Official Receipt No.', validator: requiredValidator },
        { data: 'type_of_heis', title: 'Type of HEIs', validator: requiredValidator },
        { data: 'discipline_code', title: 'Discipline Code', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', type: 'dropdown', source: @json($programOptions ?? []), validator: requiredValidator },
        { data: 'major', title: 'Major', type: 'dropdown', source: @json($majorOptions ?? []), validator: requiredValidator },
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
        { data: 'purpose_of_cav', title: 'Purpose of CAV', validator: requiredValidator },
        { data: 'target_country', title: 'Target Country', validator: requiredValidator }
      ];

      // For CAV Local and CAV Abroad, use the same columns
      var columnsLocal = columnsRecords;
      var columnsAbroad = columnsRecords;

      // For CAV‑OSD
      var columnsOSD = [
        { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
        { data: 'o', title: 'O-', validator: requiredValidator },
        { data: 'seq', title: 'Sequence No.', validator: requiredValidator },
        { data: 'cav_osds', title: 'CAV-OSD', validator: requiredValidator },
        { data: 'surname', title: 'Surname', validator: requiredValidator },
        { data: 'first_name', title: 'First Name', validator: requiredValidator },
        { data: 'extension_name', title: 'Extension Name' },
        { data: 'middle_name', title: 'Middle Name' },
        { data: 'sex', title: 'Sex', type: 'dropdown', source: ['Male', 'Female'], validator: requiredValidator },
        { data: 'institution_code', title: 'Institution Code' },
        { data: 'full_name_of_hei', title: 'Full Name of HEI', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', validator: requiredValidator },
        { data: 'major', title: 'Major', validator: requiredValidator },
        { data: 'program_level', title: 'Program Level', validator: requiredValidator },
        { data: 'status_of_the_program', title: 'Status', validator: requiredValidator },
        { data: 'date_started', title: 'Date Started',  validator: requiredValidator },
        { data: 'semester1', title: 'Semester', type: 'dropdown', source: ['First Semester'], validator: requiredValidator },
        { data: 'semester2', title: 'Semester', type: 'dropdown', source: ['Second Semester'], validator: requiredValidator },
        { data: 'date_ended', title: 'Date Ended', validator: requiredValidator },
        { data: 'year_graduated', title: 'Graduation Date', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'so_no', title: 'SO No.', validator: requiredValidator },
        { data: 'or_no', title: 'OR No.', validator: requiredValidator },
        { data: 'date_applied', title: 'Date Applied', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator },
        { data: 'remarks', title: 'Remarks' }
      ];

      // For Certifications
      var columnsCert = [
        { data: 'quarter', title: 'Quarter', type: 'numeric', renderer: quarterRenderer, validator: requiredValidator },
        { data: 'o_prefix', title: 'No.', validator: requiredValidator },
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

      // For Document Authentications
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
      
      // For CondoBPOB
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
        { data: 'date_released', title: 'Date Released', type: 'date', dateFormat: 'YYYY-MM-DD', validator: requiredValidator }
      ];
      
      // Generic function to initialize a Handsontable instance with custom columns
      function initHandsontableWithColumns(containerId, dataEndpoint, columnsConfig) {
        var container = document.getElementById(containerId);
        fetch(dataEndpoint)
          .then(response => response.json())
          .then(data => {
            // Store original data for filtering/sorting/searching
            originalData[containerId] = JSON.parse(JSON.stringify(data));
            
            // Update record count in status bar
            document.getElementById('record-count').textContent = data.length;
            
            var hot = new Handsontable(container, {
              data: data,
              columns: columnsConfig,
              colHeaders: columnsConfig.map(col => col.title),
              rowHeaders: true,
              stretchH: 'all',
              rowHeights: 30,
              dropdownMenu: true,
              filters: true,
              minSpareRows: 1,
              contextMenu: true,
              manualRowResize: true,
              licenseKey: 'non-commercial-and-evaluation',
              colWidths: function(index) {
                // Adjust column widths based on content
                if (index === 0) return 80; // Quarter
                if ([3, 4, 6].includes(index)) return 120; // Name columns
                if ([9, 14].includes(index)) return 200; // HEI and Program Name
                return 150; // Default width
              },
              cells: function(row, col) {
                let cellProperties = {};
                if (row % 2 === 0) {
                  cellProperties.className = 'even-row';
                }
                cellProperties.renderer = function(instance, td, row, col, prop, value, cellProperties) {
                  Handsontable.renderers.TextRenderer.apply(this, arguments);
                  if (instance.getDataAtCell(row, col) === null || instance.getDataAtCell(row, col) === '') {
                    td.style.background = '#f9f9f9';
                  }
                };
                return cellProperties;
              },
              afterChange: function(changes, source) {
                if (source === 'edit' && changes) {
                  // We'll use an object to store timeouts for each row
                  if (!this._saveTimeouts) {
                    this._saveTimeouts = {};
                  }
                  // Collect unique rows that changed
                  const rowsToSave = new Set();
                  changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (oldValue !== newValue) {
                      rowsToSave.add(row);
                    }
                  });
                  // For each row, debounce the save by 500ms
                  rowsToSave.forEach(row => {
                    // Clear any existing timeout for this row
                    if (this._saveTimeouts[row]) {
                      clearTimeout(this._saveTimeouts[row]);
                    }
                    // Set a new timeout
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
                      // Remove the timeout flag
                      delete hot._saveTimeouts[row];
                    }, 500);
                  });
                }
              }
            });
            
            hotInstances[containerId] = hot;
            
            // Add tab-specific hooks
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
                  // Autofill CAV No (column 1) as plain 4-digit number
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
            if (containerId === 'handsontable-container-osd') {
              hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
                // Only run if on the spare (new) row
                if (coords.row === hot.countRows() - 1) {
                  var rowData = hot.getSourceDataAtRow(coords.row);
                  // Only auto-fill if we haven't already marked this row
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
                    // Autofill CAV-OSD (column 3) with the same sequence value
                    hot.setDataAtCell(coords.row, 3, newSeq);

                    // Mark this row as auto-filled so that subsequent clicks won't re-trigger auto-fill
                    hot.setDataAtRowProp(coords.row, '_autoFilled', true);
                  }
                }
              });
            }

            // For Certifications (containerId: handsontable-container-cert)
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
                  // Autofill CAV No. (column 2) as plain 4-digit number
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
                  // Autofill No. (column 1) as sequential integer
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
            
            // For CondoBPOB (containerId: handsontable-container-condobpob)
            if (containerId === 'handsontable-container-condobpob') {
              hot.addHook('afterOnCellMouseDown', function(event, coords, TD) {
                // Only run if on the spare (new) row
                if (coords.row === hot.countRows() - 1) {
                  // Autofill Quarter (assume it's column 0)
                  var quarterVal = hot.getDataAtCell(coords.row, 0);
                  if (!quarterVal) {
                    // Calculate quarter from current month
                    var month = new Date().getMonth() + 1;
                    var quarter = month <= 3 ? 1 : month <= 6 ? 2 : month <= 9 ? 3 : 4;
                    hot.setDataAtCell(coords.row, 0, quarter);
                  }
                  // Autofill No. (assume it's column 1) as a sequential integer
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
            toastr.error('Error loading data. Please refresh the page and try again.');
          });
      }
      
      // Initialize Handsontable instances for each tab
      initHandsontableWithColumns('handsontable-container-records', "{{ route('admin.cav.all') }}", columnsRecords);
      initHandsontableWithColumns('handsontable-container-local', "{{ route('admin.cav.local.all') }}", columnsLocal);
      initHandsontableWithColumns('handsontable-container-abroad', "{{ route('admin.cav.abroad.all') }}", columnsAbroad);
      initHandsontableWithColumns('handsontable-container-osd', "{{ route('admin.cavs_osd.all') }}", columnsOSD);
      initHandsontableWithColumns('handsontable-container-cert', "{{ route('admin.certifications.all') }}", columnsCert);
      initHandsontableWithColumns('handsontable-container-docauth', "{{ route('admin.document_authentications.all') }}", columnsDocAuth);
      initHandsontableWithColumns('handsontable-container-condobpob', "{{ route('admin.condobpobs.all') }}", columnsCondobpob);

      // Search functionality for the active tab
      document.getElementById('search-button').addEventListener('click', function() {
        performSearch();
      });
      
      document.getElementById('search-input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
          performSearch();
        }
      });
      
      function performSearch() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase().trim();
        
        // Find active tab
        const activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;
        
        const containerId = activeTab.querySelector('.handsontable-wrapper div').id;
        const hot = hotInstances[containerId];
        
        if (!hot || !originalData[containerId]) return;
        
        if (!searchTerm) {
          // Reset to original data if search term is empty
          hot.loadData(originalData[containerId]);
          document.getElementById('record-count').textContent = originalData[containerId].length;
          toastr.info('Search cleared.');
          return;
        }
        
        // Filter data based on search term
        const filteredData = originalData[containerId].filter(row => {
          // Convert row to string and check if it contains the search term
          return Object.values(row).some(value => {
            if (value === null || value === undefined) return false;
            return String(value).toLowerCase().includes(searchTerm);
          });
        });
        
        hot.loadData(filteredData);
        document.getElementById('record-count').textContent = filteredData.length;
        toastr.info(`Found ${filteredData.length} records matching '${searchTerm}'.`);
      }
      
      // Apply Filters button functionality
      document.getElementById('apply-filters').addEventListener('click', function() {
        // Find active tab
        const activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;
        
        const containerId = activeTab.querySelector('.handsontable-wrapper div').id;
        const hot = hotInstances[containerId];
        
        if (!hot || !originalData[containerId]) return;
        
        const dateRangeFilter = document.getElementById('filter-date-range').value;
        const regionFilter = document.getElementById('filter-region').value;
        const statusFilter = document.getElementById('filter-status').value;
        
        // Apply filters to data
        let filteredData = [...originalData[containerId]];
        
        // Date range filter
        if (dateRangeFilter !== 'all') {
          const today = new Date();
          let fromDate, toDate;
          
          if (dateRangeFilter === 'today') {
            fromDate = new Date(today.setHours(0, 0, 0, 0));
            toDate = new Date(today.setHours(23, 59, 59, 999));
          } else if (dateRangeFilter === 'this-week') {
            const day = today.getDay();
            const diff = today.getDate() - day + (day === 0 ? -6 : 1); // Adjust for Sunday
            fromDate = new Date(today.setDate(diff));
            fromDate.setHours(0, 0, 0, 0);
            toDate = new Date(fromDate);
            toDate.setDate(toDate.getDate() + 6);
            toDate.setHours(23, 59, 59, 999);
          } else if (dateRangeFilter === 'this-month') {
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
            toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0, 23, 59, 59, 999);
          } else if (dateRangeFilter === 'this-quarter') {
            const quarter = Math.floor(today.getMonth() / 3);
            fromDate = new Date(today.getFullYear(), quarter * 3, 1);
            toDate = new Date(today.getFullYear(), quarter * 3 + 3, 0, 23, 59, 59, 999);
          } else if (dateRangeFilter === 'custom') {
            const fromInput = document.getElementById('filter-date-from').value;
            const toInput = document.getElementById('filter-date-to').value;
            
            if (fromInput) {
              fromDate = new Date(fromInput);
              fromDate.setHours(0, 0, 0, 0);
            }
            
            if (toInput) {
              toDate = new Date(toInput);
              toDate.setHours(23, 59, 59, 999);
            }
          }
          
          // Filter data based on date range
          if (fromDate && toDate) {
            filteredData = filteredData.filter(row => {
              // Check date_released, date_applied, or other date fields based on tab
              let rowDate;
              if (row.date_released) {
                rowDate = new Date(row.date_released);
              } else if (row.date_applied) {
                rowDate = new Date(row.date_applied);
              } else if (row.date_ended) {
                rowDate = new Date(row.date_ended);
              }
              
              return rowDate && rowDate >= fromDate && rowDate <= toDate;
            });
          }
        }
        
        // Region filter
        if (regionFilter !== 'all') {
          filteredData = filteredData.filter(row => {
            return row.region && row.region.toLowerCase().includes(regionFilter.replace('-', ' '));
          });
        }
        
        // Status filter
        if (statusFilter !== 'all') {
          filteredData = filteredData.filter(row => {
            return row.status_of_the_program && row.status_of_the_program.toLowerCase() === statusFilter;
          });
        }
        
        hot.loadData(filteredData);
        document.getElementById('record-count').textContent = filteredData.length;
        toastr.info(`Applied filters. Found ${filteredData.length} matching records.`);
      });
      
      // Sort functionality
      document.getElementById('ribbon-sort').addEventListener('click', function() {
        // Find active tab
        const activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;
        
        const containerId = activeTab.querySelector('.handsontable-wrapper div').id;
        const hot = hotInstances[containerId];
        
        if (!hot) return;
        
        // Display a simple modal for sort options
        const sortField = prompt('Enter the column name to sort by (e.g., "surname", "date_applied"):');
        if (!sortField) return;
        
        const sortDirection = confirm('Sort in ascending order? (OK for ascending, Cancel for descending)');
        
        // Get current data
        const currentData = hot.getSourceData();
        
        // Sort data
        const sortedData = [...currentData].sort((a, b) => {
          if (!a[sortField] && !b[sortField]) return 0;
          if (!a[sortField]) return sortDirection ? 1 : -1;
          if (!b[sortField]) return sortDirection ? -1 : 1;
          
          // Handle different data types
          if (typeof a[sortField] === 'string') {
            return sortDirection ? 
              a[sortField].localeCompare(b[sortField]) : 
              b[sortField].localeCompare(a[sortField]);
          } else {
            return sortDirection ? 
              a[sortField] - b[sortField] : 
              b[sortField] - a[sortField];
          }
        });
        
        hot.loadData(sortedData);
        toastr.info(`Sorted data by ${sortField} in ${sortDirection ? 'ascending' : 'descending'} order.`);
      });

      // Filter Today's Records functionality for the "CAV Records" tab
      document.getElementById('filter-today').addEventListener('click', function() {
        const today = new Date().toISOString().split('T')[0];
        const containerId = 'handsontable-container-records';
        const hot = hotInstances[containerId];
        
        if (!hot || !originalData[containerId]) {
          toastr.error('Table data not loaded yet. Please try again.');
          return;
        }
        
        const filteredData = originalData[containerId].filter(row => 
          row.date_released && row.date_released.startsWith(today)
        );
        
        hot.loadData(filteredData);
        document.getElementById('record-count').textContent = filteredData.length;
        toastr.info(`Showing ${filteredData.length} records released today.`);
      });

      // Add Row functionality
      document.getElementById('ribbon-add-row').addEventListener('click', function() {
        // Find active tab
        const activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;
        
        const containerId = activeTab.querySelector('.handsontable-wrapper div').id;
        const hot = hotInstances[containerId];
        
        if (!hot) return;
        
        // Add a new row at the end (before the empty row)
        const rowCount = hot.countRows();
        hot.alter('insert_row', rowCount - 1, 1);
        
        // Scroll to the new row
        hot.scrollViewportTo(rowCount - 1);
        
        // Focus on the first cell of the new row
        hot.selectCell(rowCount - 1, 0);
        
        toastr.success('New row added.');
      });
      
      // Delete Row functionality
      document.getElementById('ribbon-delete-row').addEventListener('click', function() {
        // Find active tab
        const activeTab = document.querySelector('.tab-pane.active');
        if (!activeTab) return;
        
        const containerId = activeTab.querySelector('.handsontable-wrapper div').id;
        const hot = hotInstances[containerId];
        
        if (!hot) return;
        
        // Get currently selected row(s)
        const selected = hot.getSelected();
        if (!selected || selected.length === 0) {
          toastr.warning('Please select a row to delete first.');
          return;
        }
        
        // Get the range of selected rows
        const startRow = Math.min(selected[0][0], selected[0][2]);
        const endRow = Math.max(selected[0][0], selected[0][2]);
        const rowCount = endRow - startRow + 1;
        
        // Confirm deletion
        if (confirm(`Are you sure you want to delete ${rowCount} row(s)?`)) {
          // Delete the rows from the server first
          const rowsToDelete = [];
          for (let row = startRow; row <= endRow; row++) {
            const rowData = hot.getSourceDataAtRow(row);
            if (rowData && rowData.id) {
              rowsToDelete.push(rowData.id);
            }
          }
          
          // Send deletion requests for rows with IDs
          if (rowsToDelete.length > 0) {
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
            
            const promises = rowsToDelete.map(id => 
              fetch(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
                }
              })
            );
            
            Promise.all(promises)
              .then(() => {
                // Delete rows from the table
                hot.alter('remove_row', startRow, rowCount);
                toastr.success(`${rowCount} row(s) deleted successfully.`);
                
                // Update record count
                document.getElementById('record-count').textContent = hot.countRows() - 1; // Minus the empty row
              })
              .catch(error => {
                console.error('Error deleting rows:', error);
                toastr.error('Error deleting rows from the server.');
              });
          } else {
            // No server-side data, just remove from the table
            hot.alter('remove_row', startRow, rowCount);
            toastr.success(`${rowCount} row(s) deleted.`);
            
            // Update record count
            document.getElementById('record-count').textContent = hot.countRows() - 1; // Minus the empty row
          }
        }
      });

      // Import button functionality
      document.getElementById('import-btn').addEventListener('click', function() {
        if (pond.getFiles().length === 0) {
          toastr.warning('Please select files to import first.', 'No Files Selected');
          return;
        }
        pond.processFiles();
        toastr.info('Processing files...', 'Import Started');
      });
    });
  </script>
@endpush

@endsection