<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; padding: 30px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #1e293b; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #1e293b; color: white; text-align: left; padding: 12px; font-size: 12px; text-transform: uppercase; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .footer { margin-top: 30px; font-size: 10px; color: #94a3b8; text-align: right; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f1f5f9; padding: 10px; text-align: right; margin-bottom: 20px; border-radius: 8px;">
        <button onclick="window.print()" style="background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Print / Save as PDF
        </button>
    </div>

    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Reporting Period: <strong>{{ $month }} {{ $year }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Business Associate</th>
                <th>Store / Customer</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Target Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['employee_name'] }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td>{{ $row['brand_name'] }}</td>
                    <td style="text-transform: uppercase;">{{ $row['target_type'] }}</td>
                    <td style="font-weight: bold;">{{ number_format($row['target_value'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ date('Y-m-d H:i:s') }}
    </div>

    <script>
        // Optional: Auto trigger print dialog if needed
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
