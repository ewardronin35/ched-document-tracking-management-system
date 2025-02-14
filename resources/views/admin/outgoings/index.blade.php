@extends('layouts.app')

@section('content')

@push('styles')
  <!-- Fonts and Handsontable CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <!-- FilePond CSS -->
  <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
  <!-- (Optional) FilePond plugin for file type validation -->
  <link href="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.css" rel="stylesheet">

  <style>
    body, .handsontable .ht_master .htCore td, .handsontable .ht_master thead th {
      font-family: 'Poppins', sans-serif !important;
    }
    .handsontable .ht_master .htCore td {
      border: 1px solid #dee2e6;
      padding: 0.75rem;
    }
    .handsontable .ht_master thead th {
      background: #f8f9fa;
      color: #495057;
      border: 1px solid #dee2e6;
      font-weight: 600;
    }
    .highlightedRow {
      background-color: #ffff99 !important;
    }
    #handsontable-outgoings,
    #handsontable-incomings,
    #handsontable-travel-memo,
    #handsontable-ono {
      max-height: 650px;
      overflow: auto;
    }
    .release-btn {
      padding: 2px 5px;
      font-size: 0.8rem;
    }
    .search-field {
      width: 200px;
    }
    .released-row {
      background-color: #d4edda !important;
    }
    .htQuarterLabel {
      font-weight: bold;
      background-color: #e9ecef;
    }
    /* Additional styling for the import tab */
    .filepond--root {
      margin-top: 1rem;
    }
    .gray-row {
  background-color: #e0e0e0 !important;  /* light gray for completely empty rows */
}
.green-row {
  background-color: #ccffcc !important;  /* green for within first day */
}
.yellow-row {
  background-color: #ffffcc !important;  /* yellow for 1–6 days */
}
.red-row {
  background-color: #ffcccc !important;  /* red for 7+ days */
}



  </style>
@endpush
<div class="container-fluid">
    <!-- Main Tabs: Incomings / Outgoings / Imports / Reports -->
    <ul class="nav nav-tabs" id="documentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="incomings-tab" data-bs-toggle="tab" data-bs-target="#incomings"
                    type="button" role="tab" aria-controls="incomings" aria-selected="true">
                Incomings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="outgoings-tab" data-bs-toggle="tab" data-bs-target="#outgoings"
                    type="button" role="tab" aria-controls="outgoings" aria-selected="false">
                Outgoings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="imports-tab" data-bs-toggle="tab" data-bs-target="#imports"
                    type="button" role="tab" aria-controls="imports" aria-selected="false">
                Imports
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports"
                    type="button" role="tab" aria-controls="reports" aria-selected="false">
                Reports
            </button>
        </li>
    </ul>

    <div class="tab-content" id="documentTabsContent">
        <!-- Incomings Tab (unchanged) -->
        <div class="tab-pane fade show active pt-3" id="incomings" role="tabpanel" aria-labelledby="incomings-tab">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <span>Incomings</span>
                    <input type="text" id="search-incomings" class="form-control ms-3 search-field" placeholder="Search Incomings">
                </div>
                <div id="handsontable-incomings" wire:ignore style="overflow-x:auto;"></div>
            </div>
        </div>

        <!-- Outgoings Tab (unchanged with its sub-tabs for Travel Memo & O No.) -->
        <div class="tab-pane fade pt-3" id="outgoings" role="tabpanel" aria-labelledby="outgoings-tab">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <span>All Outgoings</span>
                    <input type="text" id="search-outgoings" class="form-control ms-3 search-field" placeholder="Search Outgoings">
                </div>
                <div id="handsontable-outgoings" wire:ignore style="overflow-x:auto;"></div>
            </div>
            <!-- Subtabs for Travel Memo & O No. -->
            <ul class="nav nav-tabs" id="outgoingsSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="travel-memo-tab" data-bs-toggle="tab"
                            data-bs-target="#travel-memo" type="button" role="tab"
                            aria-controls="travel-memo" aria-selected="true">
                        Travel Memo
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ono-tab" data-bs-toggle="tab"
                            data-bs-target="#ono" type="button" role="tab"
                            aria-controls="ono" aria-selected="false">
                        O No. DATE OF RELEASED
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="outgoingsSubTabsContent">
                <!-- Travel Memo Subtab -->
                <div class="tab-pane fade show active p-3" id="travel-memo" role="tabpanel" aria-labelledby="travel-memo-tab">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <span>Travel Memo</span>
                            <input type="text" id="search-travel-memo" class="form-control ms-3 search-field" placeholder="Search Travel Memo">
                        </div>
                        <div class="card-body">
                            <div id="handsontable-travel-memo" wire:ignore style="overflow-x:auto;"></div>
                        </div>
                    </div>
                </div>
                <!-- O No. Subtab -->
                <div class="tab-pane fade p-3" id="ono" role="tabpanel" aria-labelledby="ono-tab">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <span>O No. DATE OF RELEASED</span>
                            <input type="text" id="search-ono" class="form-control ms-3 search-field" placeholder="Search O No.">
                        </div>
                        <div class="card-body">
                            <div id="handsontable-ono" wire:ignore style="overflow-x:auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Imports Tab: Separate sub-tabs for Incoming Import and Outgoing Import -->
        <div class="tab-pane fade pt-3" id="imports" role="tabpanel" aria-labelledby="imports-tab">
            <ul class="nav nav-tabs" id="importsSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="incoming-import-tab" data-bs-toggle="tab"
                            data-bs-target="#incoming-import" type="button" role="tab"
                            aria-controls="incoming-import" aria-selected="true">
                        Incoming Import
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="outgoing-import-tab" data-bs-toggle="tab"
                            data-bs-target="#outgoing-import" type="button" role="tab"
                            aria-controls="outgoing-import" aria-selected="false">
                        Outgoing Import
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="importsSubTabsContent">
                <!-- Incoming Import Subtab -->
                <div class="tab-pane fade show active p-3" id="incoming-import" role="tabpanel" aria-labelledby="incoming-import-tab">
                    <div class="card mb-4">
                        <div class="card-header">
                            <span>Import Incoming Excel Files (CSV, XLS, XLSX)</span>
                        </div>
                        <div class="card-body">
                            <input type="file"
                                   class="filepond"
                                   name="incoming_filepond"
                                   id="incoming-filepond"
                                   accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                    </div>
                </div>
                <!-- Outgoing Import Subtab -->
                <div class="tab-pane fade p-3" id="outgoing-import" role="tabpanel" aria-labelledby="outgoing-import-tab">
                    <div class="card mb-4">
                        <div class="card-header">
                            <span>Import Outgoing Excel Files (CSV, XLS, XLSX)</span>
                        </div>
                        <div class="card-body">
                            <input type="file"
                                   class="filepond"
                                   name="outgoing_filepond"
                                   id="outgoing-filepond"
                                   accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Tab (now its own main tab) -->
        <div class="tab-pane fade pt-3" id="reports" role="tabpanel" aria-labelledby="reports-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <span>Reports</span>
                </div>
                <div class="card-body">
                    <!-- Place your report generation interface here -->
                    <p>Report generation functionality can be implemented here.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <!-- FilePond JS -->
 <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
  <!-- FilePond plugin for file type validation -->
  <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
  <script>
  // Register the FilePond plugin
  FilePond.registerPlugin(FilePondPluginFileValidateType);

  // Initialize FilePond for Incoming Import
  FilePond.create(document.querySelector('input#incoming-filepond'), {
    name: 'incoming_filepond',
    instantUpload: true,
    allowMultiple: true,
    maxFiles: 2,
    acceptedFileTypes: ['.csv', '.xls', '.xlsx'],
    labelFileTypeNotAllowed: 'Only Excel or CSV files are allowed.',
    fileValidateTypeLabelExpectedTypes: 'Expects {allButLastType} or {lastType}',
    server: {
      process: {
        url: '{{ route("admin.incomings.import") }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        onload: (response) => {
          toastr.success('Incoming Import successful!');
          // Fetch updated incomings data
          fetch('{{ route("admin.incomings.data") }}')
            .then(res => res.json())
            .then(data => {
              const newData = data.data ? data.data : data;
              if (window.hotIncomings) {
                window.hotIncomings.loadData(newData);
              }
            })
            .catch(err => console.error('Error fetching updated incomings:', err));
          return response;
        },
        onerror: (response) => {
          toastr.error('Error importing incoming file.');
        }
      }
    }
  });

  // Initialize FilePond for Outgoing Import
  FilePond.create(document.querySelector('input#outgoing-filepond'), {
    name: 'outgoing_filepond',
    instantUpload: true,
    allowMultiple: true,
    maxFiles: 2,
    acceptedFileTypes: ['.csv', '.xls', '.xlsx'],
    labelFileTypeNotAllowed: 'Only Excel or CSV files are allowed.',
    fileValidateTypeLabelExpectedTypes: 'Expects {allButLastType} or {lastType}',
    server: {
      process: {
        url: '{{ route("admin.outgoings.import") }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        onload: (response) => {
          toastr.success('Outgoing Import successful!');
          // Fetch updated outgoings data
          fetch('{{ route("admin.outgoings.data") }}')
            .then(res => res.json())
            .then(data => {
              const newData = data.data ? data.data : data;
              if (window.hotOutgoings) {
                window.hotOutgoings.loadData(newData);
              }
            })
            .catch(err => console.error('Error fetching updated outgoings:', err));
          return response;
        },
        onerror: (response) => {
          toastr.error('Error importing outgoing file.');
        }
      }
    }
  });
</script>



<script>
document.addEventListener("DOMContentLoaded", function() {

  function debugLog(msg) {
    console.log("[Handsontable Debug]", msg);
  }

  /* ---------------------------------------------------------------------
   * FETCH DATA from Blade
   * ------------------------------------------------------------------- */
  const outgoingsData   = @json($outgoings ?? []);
  const incomingsData   = @json($incomings ?? []);
  const travelMemoData  = @json($travelMemos ?? []);
  const onoData         = @json($onoOutgoings ?? []);


  /* ---------------------------------------------------------------------
   * Common helpers
   * ------------------------------------------------------------------- */
  function hyperlinkRenderer(instance, td, row, col, prop, value) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (value) {
      td.style.color = 'blue';
      td.style.textDecoration = 'underline';
      td.style.cursor = 'pointer';
      td.innerHTML = `<a href="#" class="outgoing-link" data-id="${value}">${value}</a>`;
    }
  }

  function quarterLabelRenderer(instance, td, row, col, prop, value) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (value) {
      td.innerText = 'Q' + value;
      td.classList.add('htQuarterLabel');
    }
  }

  function statusHighlightRenderer(instance, td, row, col, prop, value) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if (value === 'Released') {
      td.classList.add('released-row');
      td.title = 'This has been released.';
    } else {
      td.classList.remove('released-row');
      td.title = '';
    }
  }

  function clearHighlights(hotInstance) {
    const rowCount = hotInstance.countRows();
    const colCount = hotInstance.countCols();
    for (let row = 0; row < rowCount; row++) {
      for (let col = 0; col < colCount; col++) {
        const meta = hotInstance.getCellMeta(row, col);
        if (meta.className && meta.className.includes('highlightedRow')) {
          const newClass = meta.className.replace(/\bhighlightedRow\b/g, '').trim();
          hotInstance.setCellMeta(row, col, 'className', newClass);
        }
      }
    }
    hotInstance.render();
  }

  function highlightRow(hotInstance, rowIndex) {
    clearHighlights(hotInstance);
    const colCount = hotInstance.countCols();
    for (let col = 0; col < colCount; col++) {
      const meta = hotInstance.getCellMeta(rowIndex, col);
      const existingClass = meta.className || '';
      if (!existingClass.includes('highlightedRow')) {
        hotInstance.setCellMeta(rowIndex, col, 'className', existingClass + ' highlightedRow');
      }
    }
    hotInstance.render();
  }


  /* ---------------------------------------------------------------------
   * 1) INCOMINGS TABLE
   * ------------------------------------------------------------------- */
  const containerIncomings = document.getElementById('handsontable-incomings');
  let hotIncomings = null;

  // Release Button Renderer
  function releaseButtonRenderer(instance, td, row, col, prop, value) {
    Handsontable.dom.empty(td);
    const rowData = instance.getSourceDataAtRow(row);
    if (!rowData || !rowData.id) return;

    const btn = document.createElement('button');
    btn.className = 'btn btn-sm btn-primary release-btn';
    btn.innerText = 'Release';
    btn.dataset.row = row;
    td.appendChild(btn);
  }

  function handleRowRelease(event) {
  if (!event.target.classList.contains('release-btn')) return;
  const row = parseInt(event.target.dataset.row, 10);
  const rowData = hotIncomings.getSourceDataAtRow(row);
  if (!rowData || !rowData.id) {
    toastr.error('Cannot release a record without an ID.');
    return;
  }
  if (rowData.date_released) {
    toastr.info('This incoming record is already released.');
    return;
  }
  
  // Use SweetAlert2 instead of the native confirm
  Swal.fire({
    title: 'Are you sure?',
    text: "Do you really want to release this incoming record?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, release it!'
  }).then((result) => {
    if (result.isConfirmed) {
      releaseIncoming(row, rowData);
    }
  });
}

let updatingRowColors = false;

function updateRowColors(hotInstance) {
  if (updatingRowColors) return;
  updatingRowColors = true;

  const totalRows = hotInstance.countRows();
  const totalCols = hotInstance.countCols();

  for (let row = 0; row < totalRows; row++) {
    // First, clear any existing custom color classes on this row.
    for (let col = 0; col < totalCols; col++) {
      let meta = hotInstance.getCellMeta(row, col);
      if (meta.className) {
        // Remove any of the custom classes.
        meta.className = meta.className.replace(/\b(gray-row|green-row|yellow-row|red-row)\b/g, '').trim();
        hotInstance.setCellMeta(row, col, 'className', meta.className);
      }
    }

    // Get the row data.
    const rowData = hotInstance.getSourceDataAtRow(row);
    let newClass = "";
    console.log("Row", row, "data:", rowData);

    // If the row is completely empty (user has not filled it), mark it gray.
    if (isRowEmpty(rowData)) {
      newClass = "gray-row";
      console.log("Row", row, "is empty; marking as gray");

    }
    // Otherwise, if there's a date_received but no date_released...
    else if (rowData.date_received && !rowData.date_released) {
      const receivedDate = new Date(rowData.date_received);
      const currentDate = new Date();
      const diffDays = Math.floor((currentDate - receivedDate) / (1000 * 60 * 60 * 24));
      console.log("Row", row, "diffDays:", diffDays);

      // Apply thresholds:
      // - If less than 1 day has passed, mark green.
      // - If 1 to less than 7 days have passed, mark yellow.
      // - If 7 or more days have passed, mark red.
      if (diffDays < 1) {
        newClass = "green-row";
      } else if (diffDays < 7) {
        newClass = "yellow-row";
      } else {
        newClass = "red-row";
      }
    }

    // If a class was determined, apply it to every cell in the row.
    if (newClass) {
      for (let col = 0; col < totalCols; col++) {
        let meta = hotInstance.getCellMeta(row, col);
        meta.className = meta.className ? meta.className + " " + newClass : newClass;
        hotInstance.setCellMeta(row, col, "className", meta.className);
      }
    }
  }
  updatingRowColors = false;
}


function releaseIncoming(row, rowData) {
  const incomingId = rowData.id;
  const today = new Date().toISOString().split('T')[0];
  const releaseUrl = `/admin/incomings/${incomingId}/release`;
  const button = containerIncomings.querySelector(`.release-btn[data-row="${row}"]`);
  if (button) button.disabled = true;

  fetch(releaseUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ date_released: today })
  })
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => { throw err; });
      }
      return response.json();
    })
    .then(updated => {
      toastr.success('Incoming record released successfully.');
      // Update the Incomings table with the new release date and highlight the row.
      hotIncomings.setDataAtRowProp(row, 'date_released', updated.data.date_released, 'internal');
      updateRowColors(hotIncomings);


      // Process the new outgoing record, if provided.
      if (updated.data.outgoing) {
        const newOutgoing = updated.data.outgoing;
        console.log("New outgoing record from server:", newOutgoing);

        // Add the new outgoing record to your global outgoingsData array.
        outgoingsData.push({
          ...newOutgoing,
          no: String(newOutgoing.id).padStart(4, '0')
        });

        const outgoingTabTrigger = document.getElementById('outgoings-tab');
        if (outgoingTabTrigger) {
          new bootstrap.Tab(outgoingTabTrigger).show();
          console.log("Switched to Outgoings tab.");
        } else {
          console.warn("Outgoings tab element not found.");
        }
        
        // Also update sub-tables if the category is "travel order" or "ono"
        if ((newOutgoing.category || '').toLowerCase() === 'travel order') {
          travelMemoData.push({
            ...newOutgoing,
            no: String(newOutgoing.id).padStart(4, '0')
          });
          if (window.hotTravelMemo) {
            window.hotTravelMemo.loadData(travelMemoData);
          }
        } else if ((newOutgoing.category || '').toLowerCase() === 'ono') {
          onoData.push({
            ...newOutgoing,
            no: String(newOutgoing.id).padStart(4, '0')
          });
          if (window.hotOno) {
            window.hotOno.loadData(onoData);
          }
        }

        // Now update the Outgoings table.
        if (window.hotOutgoings) {
          // Reload the table data from the global outgoingsData array.
          window.hotOutgoings.loadData(outgoingsData);
          window.hotOutgoings.render();

          // Wait briefly to ensure the table is rendered.
          setTimeout(() => {
            const newOutgoingId = newOutgoing.id;
            console.log("New outgoing ID:", newOutgoingId);
            // Find the index of the new outgoing record in outgoingsData.
            const newRowIndex = outgoingsData.findIndex(item =>
              parseInt(item.id, 10) === parseInt(newOutgoingId, 10)
            );
            console.log("New row index found:", newRowIndex);
            if (newRowIndex !== -1) {
              window.hotOutgoings.selectCell(newRowIndex, 0);
              highlightRow(window.hotOutgoings, newRowIndex);
            } else {
              console.warn("New outgoing record not found in outgoingsData.");
            }
          }, 1000); // Delay (in milliseconds) to allow the table to render
        } else {
          console.warn("Outgoings table instance (hotOutgoings) is not available.");
        }
      }
    })
    .catch(error => {
      console.error("Error releasing incoming record:", error);
      let errorMsg = 'Error releasing incoming record.';
      if (error.errors) {
        const firstKey = Object.keys(error.errors)[0];
        errorMsg += " " + error.errors[firstKey].join(' ');
      } else if (error.message) {
        errorMsg += " " + error.message;
      }
      toastr.error(errorMsg);
    })
    .finally(() => {
      if (button) {
        button.disabled = false;
      }
    });
}

  if (containerIncomings) {
    const incomingsArray = incomingsData.map(item => ({
      id:               item.id,
      quarter:          item.quarter,
      No:               item.No,
      location:         item.location,
      chedrix_2025:     item.chedrix_2025,
      reference_number: item.reference_number,
      date_received:    item.date_received,
      time_emailed:     item.time_emailed,
      sender_name:      item.sender_name,
      sender_email:     item.sender_email,
      subject:          item.subject,
      remarks:          item.remarks,
      date_time_routed: item.date_time_routed,
      routed_to:        item.routed_to,
      date_acted_by_es: item.date_acted_by_es,
      outgoing_details: item.outgoing_details,
      year:             item.year,
      outgoing_id:      item.outgoing_id,
      date_released:    item.date_released
    }));
    if (incomingsArray.length === 0) {
  incomingsArray.push({});
}

// The "spare" row is the *last* row
const lastIncomingIndex = incomingsArray.length - 1;
const lastIncomingRow = incomingsArray[lastIncomingIndex];

// If that row is empty (or you want to forcibly fill it):
// Check if it has any data. If it's truly blank => fill with defaults.
const isBlank = !lastIncomingRow.id && !lastIncomingRow.reference_number && !lastIncomingRow.No;
if (isBlank) {
  // 1) Quarter
  const currentMonth = new Date().getMonth() + 1;
  const quarter = Math.floor((currentMonth - 1) / 3) + 1;
  lastIncomingRow.quarter = quarter;

  // 2) CHEDRIX
  lastIncomingRow.chedrix_2025 = 'CHEDRIX-2025';

  // 3) No (auto-increment from existing)
  const allNoValues = incomingsArray
    .map(r => parseInt(r.No, 10))
    .filter(n => !isNaN(n));
  const maxNo = allNoValues.length ? Math.max(...allNoValues) : 0;
  lastIncomingRow.No = String(maxNo + 1).padStart(4, '0');
}
    const incomingsColumns = [
      { data: 'quarter',          title: 'Quarter', renderer: quarterLabelRenderer, readOnly: true },
      { data: 'chedrix_2025',     title: 'CHEDRIX 2025' },
      { data: 'location',         title: 'Location', type: 'dropdown', source: ['e', 'm/zc', 'm/pag'] },
      { data: 'No',               title: 'No.' },
      { data: 'reference_number', title: 'Reference #' },
      { data: 'date_received',    title: 'Date Received' },
      { data: 'time_emailed',     title: 'Time Emailed' },
      { data: 'sender_name',      title: 'Sender Name' },
      { data: 'sender_email',     title: 'Sender Email' },
      { data: 'subject',          title: 'Subject' },
      { data: 'remarks',          title: 'Remarks' },
      { data: 'date_time_routed', title: 'Date Routed' },
      { data: 'routed_to',        title: 'Routed To' },
      { data: 'date_acted_by_es', title: 'Date Acted by ES' },
      { data: 'outgoing_details', title: 'Outgoing Details' },
      { data: 'year',             title: 'Year' },
      {
        data: 'outgoing_id',
        title: 'Outgoing ID',
        renderer: hyperlinkRenderer,
        readOnly: true
      },
      { data: 'date_released',    title: 'Date Released'},
      {
        data: null,
        title: 'Actions',
        renderer: releaseButtonRenderer
      }
    ];

    hotIncomings = new Handsontable(containerIncomings, {
      data: incomingsArray,
      colHeaders: incomingsColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',
      search: true, // enable search plugin

      height: 650,
      maxHeight: 650,
      minSpareRows: 1, // always keep 1 blank row at bottom

      columns: incomingsColumns,
      afterRender: function() {
    updateRowColors(this);
    setTimeout(() => {
    updateRowColors(this);
    this.render(); // force a re-render after meta updates
  }, 0);
  },
      afterChange: function(changes, source) {
        console.log("Outgoings afterChange called. Source:", source, "Changes:", changes);
        if (!changes) return;
        // 1) Skip if source is loadData or internal (to avoid repeated calls after autofill)
        if (source === 'loadData' || source === 'internal') {
          return;
        }
        // 2) Only proceed for user edits, paste, autofill, etc.
        if (!['edit','Autofill','Paste','Undo'].includes(source)) {
          return;
        }

        debugLog("Incomings afterChange => source=" + source + ", changes=", changes);

        // Auto-fill for last row
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
          const lastRowIndex = this.countRows() - 1;
          if (rowIndex === lastRowIndex) {
            const rowData = this.getSourceDataAtRow(rowIndex) || {};

            // quarter
            if (!rowData.quarter) {
              const currentMonth = new Date().getMonth() + 1;
              const quarter = Math.floor((currentMonth - 1) / 3) + 1;
              this.setDataAtRowProp(rowIndex, 'quarter', quarter, 'internal');
            }

            // chedrix_2025
            if (!rowData.chedrix_2025) {
              this.setDataAtRowProp(rowIndex, 'chedrix_2025', 'CHEDRIX-2025', 'internal');
            }

            // No
            if (!rowData.No) {
              const allNoValues = this.getDataAtProp('No')
                .filter(val => !!val)
                .map(val => parseInt(val, 10))
                .filter(num => !isNaN(num));
              const maxNo = allNoValues.length ? Math.max(...allNoValues) : 0;
              const nextNo = String(maxNo + 1).padStart(4, '0');
              this.setDataAtRowProp(rowIndex, 'No', nextNo, 'internal');
            }
          }
        });

        // Now do the usual server save - one fetch per row changed
        let rowMap = {};
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
          if (oldVal !== newVal) {
            if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
            rowMap[rowIndex][prop] = newVal;
          }
        });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          debugLog("Saving row to server => rowIndex=" + rowIndex, rowData);

          const payload = {
            quarter:          rowData.quarter,
            chedrix_2025:     rowData.chedrix_2025,
            location:         rowData.location,
            No:               rowData.No,
            reference_number: rowData.reference_number,
            date_received:    rowData.date_received,
            time_emailed:     rowData.time_emailed,
            sender_name:      rowData.sender_name,
            sender_email:     rowData.sender_email,
            subject:          rowData.subject,
            remarks:          rowData.remarks,
            date_time_routed: rowData.date_time_routed,
            routed_to:        rowData.routed_to,
            date_acted_by_es: rowData.date_acted_by_es,
            outgoing_details: rowData.outgoing_details,
            year:             rowData.year,
            outgoing_id:      rowData.outgoing_id,
            date_released:    rowData.date_released
          };
          console.log("Payload for row " + rowIndex + ":", payload);

const incomingId = rowData.id;
const hasValidId = incomingId && incomingId !== 'undefined';
console.log("Row data being saved:", rowData);

const url = hasValidId ? `/admin/incomings/${incomingId}` : `/admin/incomings`;
const method = hasValidId ? 'PUT' : 'POST';


          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('Incoming record saved successfully.');
              
              // If newly created => update local ID
              if (!incomingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
              }
              // If server returned a "no", override local
              if (data?.data?.no) {
                this.setDataAtRowProp(rowIndex, 'No', data.data.no, 'internal');
              }
            })
            .catch(err => {
              toastr.error('Error saving incoming record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/incomings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('Incoming record deleted successfully.');
              })
              .catch(() => {
                toastr.error('Error deleting incoming record.');
              });
          }
        });
      },

      afterOnCellMouseDown: function(event, coords) {
        const outgoingIdColIndex = incomingsColumns.findIndex(col => col.data === 'outgoing_id');
        if (coords.col === outgoingIdColIndex) {
          const rowData = this.getSourceDataAtRow(coords.row);
          if (!rowData || !rowData.outgoing_id) return;
          const outgoingId = rowData.outgoing_id;
          if (!window.hotOutgoings) return;

          // Switch to Outgoings tab
          const outgoingTabTrigger = document.getElementById('outgoings-tab');
          if (outgoingTabTrigger) {
            const outgoingTab = new bootstrap.Tab(outgoingTabTrigger);
            outgoingTab.show();
            console.log("Switched to Outgoings tab.");

            setTimeout(() => {
              const rowIndex = outgoingsData.findIndex(item => parseInt(item.id, 10) === parseInt(outgoingId, 10));
              if (rowIndex !== -1) {
                window.hotOutgoings.selectCell(rowIndex, 0);
                window.hotOutgoings.scrollViewportTo(rowIndex, 0);
                highlightRow(window.hotOutgoings, rowIndex);
              } else {
                toastr.warning('Outgoing record not found.');
              }
            }, 300);
          }
        }
      },

      afterRender() {
        // Re-bind release button events
        const buttons = containerIncomings.querySelectorAll('.release-btn');
        buttons.forEach(btn => {
          btn.removeEventListener('click', handleRowRelease);
          btn.addEventListener('click', handleRowRelease);
        });
      }
    });

    // Searching
    const searchIncomings = document.getElementById('search-incomings');
    if (searchIncomings) {
      const searchPlugin = hotIncomings.getPlugin('search');
      searchIncomings.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotIncomings.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotIncomings = hotIncomings;
  }
  function isRowEmpty(rowData) {
  if (!rowData) return true;
  // Only check key fields that a user should fill manually
  const fieldsToCheck = ['reference_number', 'date_received', 'sender_name', 'sender_email', 'subject', 'remarks'];
  for (let field of fieldsToCheck) {
    if (rowData[field] && rowData[field].toString().trim() !== '') {
      return false;
    }
  }
  return true;
}



  /* ---------------------------------------------------------------------
   * 2) OUTGOINGS TABLE
   * ------------------------------------------------------------------- */
  const containerOutgoings = document.getElementById('handsontable-outgoings');
  let hotOutgoings = null;

  if (containerOutgoings) {
    const outgoingsArray = outgoingsData.map(item => ({
      no:                item.no,
      chedrix_2025:      item.chedrix_2025,
      o:                 item.o,
      date_released:     item.date_released,
      category:          item.category,
      addressed_to:      item.addressed_to,
      email:             item.email,
      subject_of_letter: item.subject_of_letter,
      remarks:           item.remarks,
      libcap_no:         item.libcap_no,
      status:            item.status,
      incoming_id:       item.incoming_id,
      travel_date:       item.travel_date,
      es_in_charge:      item.es_in_charge,
      quarter_label:     item.quarter_label
    }));
    if (outgoingsArray.length === 0) {
  outgoingsArray.push({});
}

const lastOutgoingIndex = outgoingsArray.length - 1;
const lastOutgoingRow = outgoingsArray[lastOutgoingIndex];

// If it’s blank => fill with defaults
const blankOutgoing = !lastOutgoingRow.id && !lastOutgoingRow.no;
if (blankOutgoing) {
  lastOutgoingRow.chedrix_2025 = 'CHEDRIX-2025';
  lastOutgoingRow.o = 'O';

  // Auto-increment "no"
  const existingNos = outgoingsArray
    .map(r => parseInt(r.no, 10))
    .filter(n => !isNaN(n));
  const maxNo = existingNos.length ? Math.max(...existingNos) : 0;
  lastOutgoingRow.no = String(maxNo + 1).padStart(4, '0');
}
    const outgoingsColumns = [
      { data: 'quarter_label',  title: 'Quarter Label', readOnly: true }, // e.g. "Q1 JAN-FEB-MAR"
      { data: 'no',                title: 'No.' },
      { data: 'chedrix_2025',      title: 'CHEDRIX 2025' },
      { data: 'o',                 title: 'O' },
      { data: 'date_released',     title: 'Date Released' },
      { data: 'category',          title: 'Category' },
      { data: 'addressed_to',      title: 'Addressed To' },
      { data: 'email',             title: 'Email' },
      { data: 'subject_of_letter', title: 'Subject' },
      { data: 'remarks',           title: 'Remarks' },
      { data: 'libcap_no',         title: 'LIBCAP #' },
      { data: 'status',            title: 'Status', renderer: statusHighlightRenderer },
      {
        data: 'incoming_id',
        title: 'Incoming ID',
        renderer: hyperlinkRenderer,
        readOnly: true
      },
      { data: 'travel_date',       title: 'Travel Date' },
      { data: 'es_in_charge',      title: 'ES-In Charge' }
    ];

    hotOutgoings = new Handsontable(containerOutgoings, {
      data: outgoingsArray,
      colHeaders: outgoingsColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',
      search: true, // enable search plugin

      height: 500,
      maxHeight: 500,
      minSpareRows: 1,

      columns: outgoingsColumns,
      afterRender: function() {
  updateRowColors(this);
  setTimeout(() => {
    updateRowColors(this);
    this.render();
  }, 0);
},
      afterChange: function(changes, source) {
        if (!changes) return;
  if (source === 'loadData' || source === 'internal') return;
  if (!['edit','Autofill','Paste','Undo'].includes(source)) return;

        debugLog("Outgoings afterChange => source=" + source + ", changes=", changes);


        changes.forEach(function(change) {
    const [rowIndex, property, oldVal, newVal] = change;
    if (property === 'date_released' && newVal) {
      const date = new Date(newVal);
      // Compute quarter: month 0-2 => Q1, 3-5 => Q2, etc.
      const quarter = Math.floor(date.getMonth() / 3) + 1;
      // Set the display quarter label (e.g., "Q2")
      this.setDataAtRowProp(rowIndex, 'quarter_label', 'Q' + quarter, 'internal');
      // Set the hidden numeric quarter field
      this.setDataAtRowProp(rowIndex, 'quarter', quarter, 'internal');
    }
  }, this);


        // Auto-fill in last row
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
    const lastRowIndex = this.countRows() - 1;
    if (rowIndex === lastRowIndex) {
        const rowData = this.getSourceDataAtRow(rowIndex) || {};
        // If date_released is present but quarter_label is missing, compute it.
        if (rowData.date_released && !rowData.quarter_label) {
            const date = new Date(rowData.date_released);
            // Compute quarter: months 0-2 = Q1, 3-5 = Q2, etc.
            const quarter = Math.floor(date.getMonth() / 3) + 1;
            this.setDataAtRowProp(rowIndex, 'quarter_label', 'Q' + quarter, 'internal');
        }
        // Set defaults for chedrix_2025, o and no as before:
        if (!rowData.chedrix_2025) {
            this.setDataAtRowProp(rowIndex, 'chedrix_2025', 'CHEDRIX-2025', 'internal');
        }
        if (!rowData.o) {
            this.setDataAtRowProp(rowIndex, 'o', 'O', 'internal');
        }
        if (!rowData.no) {
            const existingNos = this.getDataAtProp('no')
                .filter(val => !!val)
                .map(val => parseInt(val, 10))
                .filter(num => !isNaN(num));
            const maxNo = existingNos.length ? Math.max(...existingNos) : 0;
            const nextNo = String(maxNo + 1).padStart(4, '0');
            this.setDataAtRowProp(rowIndex, 'no', nextNo, 'internal');
        }
    }
});

        // Save to server
        let rowMap = {};
  changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
    if (oldVal !== newVal) {
      if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
      rowMap[rowIndex][prop] = newVal;
    }
  });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          debugLog("Saving outgoings => rowIndex=" + rowIndex, rowData);

          const payload = {
            quarter: rowData.quarter, // now included!
            chedrix_2025:      rowData.chedrix_2025 || 'CHEDRIX-2025',
            o:                 rowData.o || 'O',
            date_released:     rowData.date_released,
            category:          rowData.category,
            addressed_to:      rowData.addressed_to,
            email:             rowData.email,
            subject_of_letter: rowData.subject_of_letter,
            remarks:           rowData.remarks,
            libcap_no:         rowData.libcap_no,
            status:            rowData.status || 'Pending',
            incoming_id:       rowData.incoming_id,
            travel_date:       rowData.travel_date,
            es_in_charge:      rowData.es_in_charge
          };

          const outgoingId = rowData.id;
          const url    = outgoingId ? `/admin/outgoings/${outgoingId}` : `/admin/outgoings`;
          const method = outgoingId ? 'PUT' : 'POST';

          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('Outgoing record saved successfully.');

              // If newly created => store new ID => update "no"
              if (!outgoingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
                this.setDataAtRowProp(rowIndex, 'no', String(data.data.id).padStart(4, '0'), 'internal');
              }

              // Sync sub-tables if category changed
              const updatedCat = (data.data?.category || '').toLowerCase();

              // TRAVEL ORDER
              if (updatedCat === 'travel order') {
                const tmIndex = travelMemoData.findIndex(x => x.id == data.data.id);
                if (tmIndex === -1) {
                  travelMemoData.push({
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  });
                } else {
                  travelMemoData[tmIndex] = {
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  };
                }
                if (window.hotTravelMemo) {
                  window.hotTravelMemo.loadData(travelMemoData);
                }
              } else {
                // remove from Travel Memo if not travel order
                const tmIndex = travelMemoData.findIndex(x => x.id == data.data.id);
                if (tmIndex !== -1 && updatedCat !== 'travel order') {
                  travelMemoData.splice(tmIndex, 1);
                  if (window.hotTravelMemo) {
                    window.hotTravelMemo.loadData(travelMemoData);
                  }
                }
              }

              // ONO
              if (updatedCat === 'ono') {
                const onoIndex = onoData.findIndex(x => x.id == data.data.id);
                if (onoIndex === -1) {
                  onoData.push({
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  });
                } else {
                  onoData[onoIndex] = {
                    ...data.data,
                    no: String(data.data.id).padStart(4, '0')
                  };
                }
                if (window.hotOno) {
                  window.hotOno.loadData(onoData);
                }
              } else {
                // remove from ONO if not ono
                const onoIndex = onoData.findIndex(x => x.id == data.data.id);
                if (onoIndex !== -1 && updatedCat !== 'ono') {
                  onoData.splice(onoIndex, 1);
                  if (window.hotOno) {
                    window.hotOno.loadData(onoData);
                  }
                }
              }
            })
            .catch(err => {
              toastr.error('Error saving outgoing record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/outgoings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('Outgoing record deleted successfully.');

                // Also remove from sub-categories if present
                const tmIndex = travelMemoData.findIndex(x => x.id === rowData.id);
                if (tmIndex !== -1) {
                  travelMemoData.splice(tmIndex, 1);
                  if (window.hotTravelMemo) {
                    window.hotTravelMemo.loadData(travelMemoData);
                  }
                }
                const onoIndex = onoData.findIndex(x => x.id === rowData.id);
                if (onoIndex !== -1) {
                  onoData.splice(onoIndex, 1);
                  if (window.hotOno) {
                    window.hotOno.loadData(onoData);
                  }
                }
              })
              .catch(() => {
                toastr.error('Error deleting outgoing record.');
              });
          }
        });
      },

      afterOnCellMouseDown: function(event, coords) {
        const incomingIdColIndex = outgoingsColumns.findIndex(col => col.data === 'incoming_id');
        if (coords.col === incomingIdColIndex) {
          const rowData = this.getSourceDataAtRow(coords.row);
          if (!rowData || !rowData.incoming_id) return;

          const incomingId = rowData.incoming_id;
          if (!window.hotIncomings) return;

          // Switch to Incomings tab
          const incomingTabTrigger = document.getElementById('incomings-tab');
          if (incomingTabTrigger) {
            const incomingTab = new bootstrap.Tab(incomingTabTrigger);
            incomingTab.show();

            setTimeout(() => {
              const rowIndex = incomingsData.findIndex(item => parseInt(item.id, 10) === parseInt(incomingId, 10));
              if (rowIndex !== -1) {
                window.hotIncomings.selectCell(rowIndex, 0);
                window.hotIncomings.scrollViewportTo(rowIndex, 0);
                highlightRow(window.hotIncomings, rowIndex);
              } else {
                toastr.warning('Incoming record not found.');
              }
            }, 300);
          }
        }
      }
    });

    // Search
    const searchOutgoings = document.getElementById('search-outgoings');
    if (searchOutgoings) {
      const searchPlugin = hotOutgoings.getPlugin('search');
      searchOutgoings.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotOutgoings.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotOutgoings = hotOutgoings;
  }


  /* ---------------------------------------------------------------------
   * 3) TRAVEL MEMO TABLE (category='TRAVEL ORDER')
   * ------------------------------------------------------------------- */
  const containerTravelMemo = document.getElementById('handsontable-travel-memo');
  let hotTravelMemo = null;

  if (containerTravelMemo) {
    const travelMemoArray = travelMemoData.map(item => ({
      id:           item.id,
      no:           item.no,
      quarter_label:item.quarter_label,
      o:            item.o,
      date_released:item.date_released,
      addressed_to: item.addressed_to,
      email:        item.email,
      travel_date:  item.travel_date,
      es_in_charge: item.es_in_charge
    }));

    const travelMemoColumns = [
      { data: 'quarter_label', title: 'QTR',  renderer: quarterLabelRenderer, readOnly: true },
      { data: 'no',            title: 'No.',  readOnly: true },
      { data: 'o',             title: 'O' },
      { data: 'date_released', title: 'DATE OF RELEASED' },
      { data: 'addressed_to',  title: 'ADDRESSED TO' },
      { data: 'email',         title: 'EMAIL' },
      { data: 'travel_date',   title: 'TRAVEL DATE' },
      { data: 'es_in_charge',  title: 'ES-INCHARGE' }
    ];

    hotTravelMemo = new Handsontable(containerTravelMemo, {
      data: travelMemoArray,
      colHeaders: travelMemoColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',

      height: 650,
      maxHeight: 650,
      minSpareRows: 1,

      columns: travelMemoColumns,

      afterChange: function(changes, source) {
        if (!changes) return;
        // we only save on direct user edits
        if (!['edit','Paste','Autofill','Undo'].includes(source)) {
          return;
        }

        let rowMap = {};
        changes.forEach(([rowIndex, prop, oldVal, newVal]) => {
          if (oldVal !== newVal) {
            if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
            rowMap[rowIndex][prop] = newVal;
          }
        });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          const payload = {
            category:      'TRAVEL ORDER',
            o:             rowData.o,
            date_released: rowData.date_released,
            addressed_to:  rowData.addressed_to,
            email:         rowData.email,
            travel_date:   rowData.travel_date,
            es_in_charge:  rowData.es_in_charge
          };

          const outgoingId = rowData.id;
          const url    = outgoingId ? `/admin/outgoings/${outgoingId}` : `/admin/outgoings`;
          const method = outgoingId ? 'PUT' : 'POST';

          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('Travel Memo record saved successfully.');
              if (!outgoingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
                this.setDataAtRowProp(rowIndex, 'no', String(data.data.id).padStart(4, '0'), 'internal');
              }
              // sync in travelMemoData
              const idx = travelMemoData.findIndex(x => x.id == data.data.id);
              if (idx === -1) {
                travelMemoData.push({
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                });
              } else {
                travelMemoData[idx] = {
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                };
              }
              this.loadData(travelMemoData);
            })
            .catch(err => {
              toastr.error('Error saving Travel Memo record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/outgoings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('Travel Memo record deleted successfully.');
              })
              .catch(() => {
                toastr.error('Error deleting Travel Memo record.');
              });
          }
        });
      }
    });

    const searchTravelMemo = document.getElementById('search-travel-memo');
    if (searchTravelMemo) {
      const searchPlugin = hotTravelMemo.getPlugin('search');
      searchTravelMemo.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotTravelMemo.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotTravelMemo = hotTravelMemo;
  }


  /* ---------------------------------------------------------------------
   * 4) O NO. TABLE (category='ONO')
   * ------------------------------------------------------------------- */
  const containerOno = document.getElementById('handsontable-ono');
  let hotOno = null;

  if (containerOno) {
    const onoArray = onoData.map(item => ({
      id:            item.id,
      no:            item.no,
      o:             item.o,
      date_released: item.date_released,
      addressed_to:  item.addressed_to,
      subject:       item.subject,
      remarks:       item.remarks,
      libcap_no:     item.libcap_no,
      status:        item.status
    }));

    const onoColumns = [
      { data: 'no',            title: 'No.', readOnly: true },
      { data: 'o',             title: 'O' },
      { data: 'date_released', title: 'DATE OF RELEASED' },
      { data: 'addressed_to',  title: 'ADDRESSED TO' },
      { data: 'subject',       title: 'SUBJECT' },
      { data: 'remarks',       title: 'REMARKS' },
      { data: 'libcap_no',     title: 'LIBCAP #' },
      { data: 'status',        title: 'STATUS', renderer: statusHighlightRenderer }
    ];

    hotOno = new Handsontable(containerOno, {
      data: onoArray,
      colHeaders: onoColumns.map(col => col.title),
      rowHeaders: true,
      dropdownMenu: true,
      filters: true,
      columnSorting: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation',

      height: 500,
      maxHeight: 500,
      minSpareRows: 1,

      columns: onoColumns,

      afterChange: function(changes, source) {
        if (!changes) return;
        if (!['edit','Paste','Autofill','Undo'].includes(source)) {
          return;
        }

        let rowMap = {};
        changes.forEach(([rowIndex, prop, oldValue, newValue]) => {
          if (oldValue !== newValue) {
            if (!rowMap[rowIndex]) rowMap[rowIndex] = {};
            rowMap[rowIndex][prop] = newValue;
          }
        });

        Object.keys(rowMap).forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (!rowData) return;

          const payload = {
            category:          'ONO',
            o:                 rowData.o,
            date_released:     rowData.date_released,
            addressed_to:      rowData.addressed_to,
            subject_of_letter: rowData.subject,
            remarks:           rowData.remarks,
            libcap_no:         rowData.libcap_no,
            status:            rowData.status
          };

          const outgoingId = rowData.id;
          const url    = outgoingId ? `/admin/outgoings/${outgoingId}` : `/admin/outgoings`;
          const method = outgoingId ? 'PUT' : 'POST';

          fetch(url, {
            method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          })
            .then(res => {
              if (!res.ok) {
                return res.json().then(err => { throw err; });
              }
              return res.json();
            })
            .then(data => {
              toastr.success('O No. record saved successfully.');
              if (!outgoingId && data?.data?.id) {
                this.setDataAtRowProp(rowIndex, 'id', data.data.id, 'internal');
                this.setDataAtRowProp(rowIndex, 'no', String(data.data.id).padStart(4, '0'), 'internal');
              }
              // re-sync in onoData
              const idx = onoData.findIndex(x => x.id == data.data.id);
              if (idx === -1) {
                onoData.push({
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                });
              } else {
                onoData[idx] = {
                  ...data.data,
                  no: String(data.data.id).padStart(4, '0')
                };
              }
              this.loadData(onoData);
            })
            .catch(err => {
              toastr.error('Error saving O No. record.');
              console.error(err);
            });
        });
      },

      afterRemoveRow: function(index, amount, physicalRows) {
        physicalRows.forEach(rowIndex => {
          const rowData = this.getSourceDataAtRow(rowIndex);
          if (rowData && rowData.id) {
            fetch(`/admin/outgoings/${rowData.id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              }
            })
              .then(() => {
                toastr.success('O No. record deleted successfully.');
              })
              .catch(() => {
                toastr.error('Error deleting O No. record.');
              });
          }
        });
      }
    });

    // Search
    const searchOno = document.getElementById('search-ono');
    if (searchOno) {
      const searchPlugin = hotOno.getPlugin('search');
      searchOno.addEventListener('keyup', function() {
        searchPlugin.query = this.value;
        const result = searchPlugin.search(this.value);
        if (result.length) {
          hotOno.selectCell(result[0].row, result[0].col);
        }
      });
    }

    window.hotOno = hotOno;
  }
  function customDateRenderer(instance, td, row, col, prop, value, cellProperties) {
  // Use the standard TextRenderer as a base.
  Handsontable.renderers.TextRenderer.apply(this, arguments);

  // Get the entire row data.
  const rowData = instance.getSourceDataAtRow(row);
  
  // For an empty row (example: no "No" value) then mark it red.
  if (!rowData || !rowData.No) {
    td.style.backgroundColor = "#ffcccc"; // light red background
    return;
  }

  // If the record is not yet released, apply a date-based coloring.
  // (Assume that if date_released is empty, then it is not released.)
  if (!rowData.date_released && rowData.date_received) {
    const received = new Date(rowData.date_received);
    const now = new Date();
    const diffDays = Math.floor((now - received) / (1000 * 60 * 60 * 24));

    // Apply colors based on the number of days since the record was received.
    if (diffDays < 1) {
      td.style.backgroundColor = "#ccffcc"; // green
    } else if (diffDays < 3) {
      td.style.backgroundColor = "#ffffcc"; // light yellow
    } else if (diffDays >= 7) {
      td.style.backgroundColor = "#ffcccc"; // red
    } else {
      td.style.backgroundColor = ""; // no special color
    }
  }
}


  /* ---------------------------------------------------------------------
   * 5) CLICK HANDLER FOR OUTGOING LINKS (to jump from Incoming -> Outgoing)
   * ------------------------------------------------------------------- */
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('outgoing-link')) {
      e.preventDefault();
      const outgoingId = e.target.dataset.id;
      const outgoingTabTrigger = document.getElementById('outgoings-tab');
      if (outgoingTabTrigger) {
        const outgoingTab = new bootstrap.Tab(outgoingTabTrigger);
        outgoingTab.show();
      }
      setTimeout(() => {
        const rowIndex = outgoingsData.findIndex(item => parseInt(item.id, 10) === parseInt(outgoingId, 10));
        if (rowIndex !== -1 && window.hotOutgoings) {
          window.hotOutgoings.selectCell(rowIndex, 0);
          window.hotOutgoings.scrollViewportTo(rowIndex, 0);
          highlightRow(window.hotOutgoings, rowIndex);
        } else {
          toastr.warning('Outgoing record not found.');
        }
      }, 300);
    }
  });

});
</script>
@endpush