<!DOCTYPE html>
<html>
<head>
    <title>Quarterly Document Report {{ $data->year }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0078d4;
            padding-bottom: 10px;
        }
        
        .header img {
            max-height: 60px;
            margin: 0 15px;
        }
        
        .header h1 {
            margin: 10px 0;
            color: #0078d4;
            font-size: 24px;
        }
        
        .header h2 {
            margin: 5px 0;
            color: #666;
            font-size: 18px;
        }
        
        .report-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .report-info p {
            margin: 5px 0;
        }
        
        .report-filter {
            font-weight: bold;
            color: #0078d4;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th {
            background-color: #0078d4;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        table tr.total-row {
            font-weight: bold;
            background-color: #e6e6e6;
        }
        
        .summary-section {
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        .summary-section h3 {
            color: #0078d4;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .summary-card {
            width: 30%;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .primary-card {
            background-color: #f0f7ff;
            border: 1px solid #0078d4;
        }
        
        .success-card {
            background-color: #f0fff4;
            border: 1px solid #38a169;
        }
        
        .warning-card {
            background-color: #fffbeb;
            border: 1px solid #dd6b20;
        }
        
        .card-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .card-label {
            font-size: 14px;
            color: #666;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .section-title {
            background-color: #0078d4;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        
        .chart-placeholder {
            height: 200px;
            border: 1px dashed #ccc;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #666;
        }
        
        @page {
            size: letter portrait;
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="width: 20%; text-align: left; border: none;">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" />
                </td>
                <td style="width: 60%; text-align: center; border: none;">
                    <h1>CHED Document Tracking System</h1>
                    <h2>Quarterly Document Report</h2>
                </td>
                <td style="width: 20%; text-align: right; border: none;">
                    <img src="{{ public_path('images/logo2.png') }}" alt="Secondary Logo" />
                </td>
            </tr>
        </table>
    </div>
    
    <div class="report-info">
        <p><strong>Report Period:</strong> 
            @if($data->quarterFilter)
                Q{{ $data->quarterFilter }} {{ $data->year }}
            @else
                Full Year {{ $data->year }}
            @endif
        </p>
        <p><strong>Document Type:</strong> 
            @if($data->docType == 'all')
                All Documents
            @elseif($data->docType == 'incoming')
                Incoming Documents Only
            @elseif($data->docType == 'outgoing')
                Outgoing Documents Only
            @endif
        </p>
        <p><strong>Generated On:</strong> {{ date('F d, Y') }}</p>
    </div>
    
    <div class="summary-section">
        <h3>Summary Statistics</h3>
        <div class="summary-cards">
            <div class="summary-card primary-card">
                <div class="card-value">
                    {{ array_sum($data->incomingCounts) + array_sum($data->outgoingCounts) }}
                </div>
                <div class="card-label">Total Documents</div>
            </div>
            <div class="summary-card warning-card">
                <div class="card-value">{{ array_sum($data->incomingCounts) }}</div>
                <div class="card-label">Incoming Documents</div>
            </div>
            <div class="summary-card success-card">
                <div class="card-value">{{ array_sum($data->outgoingCounts) }}</div>
                <div class="card-label">Outgoing Documents</div>
            </div>
        </div>
    </div>
    
    <div class="section-title">Quarterly Breakdown</div>
    
    <table>
        <thead>
            <tr>
                <th>Quarter</th>
                <th>Incoming Documents</th>
                <th>Outgoing Documents</th>
                <th>Total</th>
                <th>% of Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalIncoming = array_sum($data->incomingCounts);
            $totalOutgoing = array_sum($data->outgoingCounts);
            $grandTotal = $totalIncoming + $totalOutgoing;
            @endphp
            
            @foreach($data->quarterlyDetails as $index => $quarter)
            <tr>
                <td>{{ $quarter->label }}</td>
                <td>{{ $quarter->incomingCount }}</td>
                <td>{{ $quarter->outgoingCount }}</td>
                <td>{{ $quarter->incomingCount + $quarter->outgoingCount }}</td>
                <td>
                    @if($grandTotal > 0)
                        {{ round((($quarter->incomingCount + $quarter->outgoingCount) / $grandTotal) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
            </tr>
            @endforeach
            
            <tr class="total-row">
                <td>Total</td>
                <td>{{ $totalIncoming }}</td>
                <td>{{ $totalOutgoing }}</td>
                <td>{{ $grandTotal }}</td>
                <td>100%</td>
            </tr>
        </tbody>
    </table>
    
    @if($data->docType != 'incoming' && !empty($data->outgoingCategories))
    <div class="section-title">Outgoing Categories</div>
    
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->outgoingCategories as $category => $count)
            <tr>
                <td>{{ $category }}</td>
                <td>{{ $count }}</td>
                <td>
                    @if($totalOutgoing > 0)
                        {{ round(($count / $totalOutgoing) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="footer">
        <p>Commission on Higher Education - Document Tracking System</p>
        <p>This is an official report generated by the CHED eTrack System</p>
        <p>Report ID: {{ md5(time()) }}</p>
    </div>
</body>
</html>