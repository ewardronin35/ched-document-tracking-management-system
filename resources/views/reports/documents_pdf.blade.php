<!DOCTYPE html>
<html>
<head>
    <title>Document Management Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Document Management Report</h1>
        <p>Generated at: {{ $generatedAt }}</p>
    </div>

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
        <p>Total Documents: {{ $documents->count() }}</p>
        <p>© {{ date('Y') }} Document Management System</p>
    </div>
</body>
</html>