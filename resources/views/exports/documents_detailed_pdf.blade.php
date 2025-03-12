<!DOCTYPE html>
<html>
<head>
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .statistics-section {
            background-color: #f4f4f4;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>{{ $reportTitle }}</h1>
        <p>Generated at: {{ $generatedAt }}</p>
    </div>

    <div class="statistics-section">
        <h2>Report Statistics</h2>
        <table>
            <tr>
                <th>Total Documents</th>
                <td>{{ $statistics['total_documents'] }}</td>
            </tr>
            <tr>
                <th>Accepted Documents</th>
                <td>{{ $statistics['accepted_documents'] }}</td>
            </tr>
            <tr>
                <th>Rejected Documents</th>
                <td>{{ $statistics['rejected_documents'] }}</td>
            </tr>
        </table>

        <h3>Document Type Distribution</h3>
        <table>
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statistics['document_types'] as $type => $count)
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2>Document Details</h2>
    <table>
        <thead>
            <tr>
                <th>Tracking Number</th>
                <th>Full Name</th>
                <th>Document Type</th>
                <th>Status</th>
                <th>Approval Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
            <tr>
                <td>{{ $document->tracking_number }}</td>
                <td>{{ $document->full_name }}</td>
                <td>{{ $document->document_type }}</td>
                <td>{{ $document->status }}</td>
                <td>{{ $document->approval_status }}</td>
                <td>{{ $document->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Document Management System</p>
    </div>
</body>
</html>