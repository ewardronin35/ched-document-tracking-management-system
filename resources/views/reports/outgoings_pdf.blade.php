<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CHED - Outgoing Reports - {{ $documentType }}</title>

    <style>
        /* Force short bond paper (Letter) with custom margins */
        @page {
            size: Letter;
            margin: 15mm 10mm;
        }

        /* Because Dompdf and similar libraries often ignore external CSS @import,
           we reference the local file directly below. */
    </style>

    <!-- Include the local Bootstrap CSS file -->
    <link 
        rel="stylesheet" 
        href="{{ public_path('css/bootstrap.min.css') }}" 
        type="text/css" 
    />

    <!-- Custom overrides or additions -->
    <style>
        /* Optional: Add a thin black border around the entire PDF content */
        .bordered-container {
            border: 1px solid #000;
            padding: 15px; /* a bit more padding than default */
        }

        /* If you want a thick horizontal rule after the header row */
        .thick-hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        /* Table of data (outgoings) */
        .outgoings-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        .outgoings-table th,
        .outgoings-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .outgoings-table thead th {
            background-color: #d3d3d3;
        }

        /* Footer styling */
        .report-footer {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-top: 15px;
            border-top: 1px solid #000; 
            padding-top: 5px;
        }
    </style>
</head>
<body>

<div class="container bordered-container">
    
    <!-- Header row with two logos and centered text -->
    <div class="row align-items-center">
        <!-- Left logo (col-auto keeps the logo sized) -->
        <div class="col-auto">
            <img 
                src="{{ public_path('images/logo.png') }}" 
                alt="CHED Logo" 
                style="width:50px;height:50px;"
            >
        </div>

        <!-- Centered text (col uses flex to expand, text-center ensures center alignment) -->
        <div class="col text-center">
            <h4 class="m-0 fw-bold">CHED - eTrack</h4>
        </div>

        <!-- Right logo -->
        <div class="col-auto">
            <img 
                src="{{ public_path('images/logo2.png') }}" 
                alt="Secondary Logo" 
                style="width:50px;height:50px;"
            >
        </div>
    </div>

    <!-- Optional thick horizontal line under the header -->
    <hr class="thick-hr" />

    <!-- Report Type Title -->
    <h5 class="text-center my-3">Report Type: {{ $documentType }}</h5>

    <!-- Outgoings Table -->
    <table class="outgoings-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Date Released</th>
                <th>Category</th>
                <th>Addressed To</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Remarks</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outgoings as $index => $outgoing)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $outgoing->date_released }}</td>
                    <td>{{ $outgoing->category }}</td>
                    <td>{{ $outgoing->addressed_to }}</td>
                    <td>{{ $outgoing->email }}</td>
                    <td>{{ $outgoing->subject_of_letter }}</td>
                    <td>{{ $outgoing->remarks }}</td>
                    <td>{{ $outgoing->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <p>Commission on Higher Education - CHED eTrack System</p>
        <p>Generated on: {{ date('F d, Y') }}</p>
    </div>

</div>
</body>
</html>
