<!DOCTYPE html>
<html>
<head>
    <title>Outgoing Reports - {{ $documentType }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Outgoing Reports for {{ $documentType }}</h2>
    <table>
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
            @foreach($outgoings as $outgoing)
                <tr>
                    <td>{{ $outgoing->no }}</td>
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
</body>
</html>
