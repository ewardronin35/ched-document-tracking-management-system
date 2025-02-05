@extends('layouts.app')

@section('content')

@push('styles')
  <!-- Handsontable CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
  
  <!-- FilePond CSS and Plugins -->
  <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
  <link href="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.css" rel="stylesheet">
  <link href="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.css" rel="stylesheet">

  <style>
    /* Modern Excel-like Handsontable styling */
    .handsontable-wrapper {
      margin-top: 20px;
      width: 100%;
      height: 600px; /* Increased height for expansion */
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

<div class="container-fluid my-4">
  <!-- Toggle Import Section Button -->
  <button class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#importCollapse" aria-expanded="false" aria-controls="importCollapse">
    Toggle Import Section
  </button>

  <!-- Collapsible Import Section -->
  <div class="collapse mb-4" id="importCollapse">
    <div class="mb-3">
      <input type="file" id="filepond-input" name="filepond" multiple />
    </div>
    <div class="mb-3">
      <button class="btn btn-primary" id="import-btn">
        <i class="fas fa-file-import"></i> Import CAVs
      </button>
    </div>
  </div>

  <!-- Filter Today's Records Button -->
  <div class="mb-3">
    <button id="filter-today" class="btn btn-primary">Filter Today's Records</button>
  </div>

  <!-- Navigation Tabs -->
  <ul class="nav nav-tabs" id="excelTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="records-tab" data-bs-toggle="tab" data-bs-target="#records" type="button" role="tab">
        CAV Records
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="local-tab" data-bs-toggle="tab" data-bs-target="#local" type="button" role="tab">
        CAV Local
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="abroad-tab" data-bs-toggle="tab" data-bs-target="#abroad" type="button" role="tab">
        CAV Abroad
      </button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="excelTabsContent">
    <div class="tab-pane fade show active" id="records" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="local" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-local"></div>
      </div>
    </div>
    <div class="tab-pane fade" id="abroad" role="tabpanel">
      <div class="handsontable-wrapper">
        <div id="handsontable-container-abroad"></div>
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Global object to store Handsontable instances
      var hotInstances = {};

      // Initialize FilePond for CSV upload
      FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
      );

      const pond = FilePond.create(document.querySelector('input[type="file"]#filepond-input'), {
        acceptedFileTypes: ['text/csv', 'application/vnd.ms-excel'],
        maxFiles: 5,
        server: {
          process: {
            url: '/admin/cavs/import-csv',
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

      var heiOptions = @json($heiOptions);
      var programOptions = @json($programOptions);

      var columns = [
        { data: 'cav_no', title: 'CAV No', validator: requiredValidator },
        { data: 'region', title: 'Region', validator: requiredValidator },
        { data: 'surname', title: 'Surname', validator: requiredValidator },
        { data: 'first_name', title: 'First Name', validator: requiredValidator },
        { data: 'extension_name', title: 'Extension Name' },
        { data: 'middle_name', title: 'Middle Name' },
        { data: 'sex', title: 'Sex', type: 'dropdown', source: ['Male', 'Female'], validator: requiredValidator },
        { data: 'institution_code', title: 'Institution Code' },
        { data: 'full_name_of_hei', title: 'Full Name of HEI', type: 'dropdown', source: heiOptions, validator: requiredValidator },
        { data: 'address_of_hei', title: 'Address of HEI', validator: requiredValidator },
        { data: 'official_receipt_number', title: 'Official Receipt No.', validator: requiredValidator },
        { data: 'type_of_heis', title: 'Type of HEIs', validator: requiredValidator },
        { data: 'discipline_code', title: 'Discipline Code', validator: requiredValidator },
        { data: 'program_name', title: 'Program Name', type: 'dropdown', source: programOptions, validator: requiredValidator },
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

      function requiredValidator(value, callback) {
        callback(value !== null && value !== undefined && value !== '');
      }

      function initHandsontable(containerId, dataEndpoint) {
        var container = document.getElementById(containerId);
        fetch(dataEndpoint)
          .then(response => response.json())
          .then(data => {
            var hot = new Handsontable(container, {
              data: data,
              columns: columns,
              colHeaders: columns.map(col => col.title),
              rowHeaders: true,
              stretchH: 'all',
              rowHeights: 40,
              className: 'ht-middle ht-center',
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
                      td.style.background = '#FFCCCC';
                  }
                };
                return cellProperties;
              },
              afterChange: function(changes, source) {
                if (source === 'edit' && changes) {
                  changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (oldValue !== newValue) {
                      const hot = this;
                      const rowData = hot.getSourceDataAtRow(row);
                      const requiredFields = ['cav_no', 'region', 'surname', 'first_name', 'sex', 'institution_code', 'full_name_of_hei'];
                      const isValid = requiredFields.every(field => rowData[field]);
                      
                      if (!isValid) {
                        toastr.error('Please fill in all required fields.');
                        return;
                      }

                      const payload = { ...rowData };

                      if (rowData.id) {
                        fetch(`/admin/cav/${rowData.id}`, {
                          method: 'PUT',
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
                          toastr.success('Record updated successfully.');
                        })
                        .catch(error => {
                          toastr.error('Error updating record.');
                          console.error(error);
                        });
                      } else {
                        fetch(`/admin/cav`, {
                          method: 'POST',
                          headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                          },
                          body: JSON.stringify(payload)
                        })
                        .then(response => response.json())
                        .then(data => {
                          toastr.success('Record created successfully.');
                          if(data.id) {
                            hot.setDataAtCell(row, hot.propToCol('id'), data.id);
                          }
                        })
                        .catch(error => {
                          toastr.error('Error creating record.');
                          console.error(error);
                        });
                      }
                    }
                  });
                }
              }
            });
            // Store the Handsontable instance for later reference
            hotInstances[containerId] = hot;
          })
          .catch(error => {
            console.error('Error fetching data for', containerId, error);
          });
      }

      // Initialize Handsontable instances for tabs
      initHandsontable('handsontable-container', "{{ route('admin.cav.all') }}");
      initHandsontable('handsontable-container-local', "{{ route('admin.cav.local') }}");
      initHandsontable('handsontable-container-abroad', "{{ route('admin.cav.abroad') }}");

      // Filter Today's Records button functionality
      document.getElementById('filter-today').addEventListener('click', function() {
        const today = new Date().toISOString().split('T')[0];
        fetch("{{ route('admin.cav.all') }}")
          .then(response => response.json())
          .then(data => {
            const filteredData = data.filter(row => row.date_released && row.date_released.startsWith(today));
            // Use the stored Handsontable instance
            const hot = hotInstances['handsontable-container'];
            if (hot) hot.loadData(filteredData);
          })
          .catch(error => {
            console.error('Error fetching data for today\'s filter:', error);
          });
      });

      document.getElementById('import-btn').addEventListener('click', function() {
        alert('Import function not implemented yet.');
      });
    });
  </script>
@endpush
