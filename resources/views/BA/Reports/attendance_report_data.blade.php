<style>
    .table-report { font-size: 11px; }
    .table-report th { background: #f1f2f6; text-align: center; vertical-align: middle !important; border: 1px solid #dfe6e9 !important; padding: 8px 4px !important; }
    .table-report td { text-align: center; vertical-align: middle !important; border: 1px solid #dfe6e9 !important; padding: 6px 4px !important; }
    .date-header { background: #6c5ce7 !important; color: white !important; }
    .total-header { background: #2d3436 !important; color: white !important; }
    .sticky-col { position: sticky; left: 0; background: white; z-index: 10; }
    .table-responsive { max-height: 600px; overflow-y: auto; }
</style>

<div class="d-flex justify-content-end mb-3 gap-2">
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i> Print</button>
</div>

<div class="table-responsive">
    <table class="table table-report table-hover">
        <thead>
            <tr>
                <th rowspan="2">S.No</th>
                <th rowspan="2">BA Code</th>
                <th rowspan="2">BA Name</th>
                <th rowspan="2">Zone</th>
                <th rowspan="2">Customer</th>
                <th rowspan="2">Brand(s)</th>
                @foreach($dates as $date)
                    <th colspan="4" class="date-header">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</th>
                @endforeach
                <th colspan="4" class="total-header">Total Summary</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                    <th>In</th>
                    <th>Out</th>
                    <th>Tgt</th>
                    <th>Ach</th>
                @endforeach
                <th>Pres</th>
                <th>Abs</th>
                <th>Tgt</th>
                <th>Ach</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $ba)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ba['emp_id'] }}</td>
                <td style="text-align: left; font-weight: 600;">{{ $ba['name'] }}</td>
                <td style="text-align: left;">{{ $ba['zone'] }}</td>
                <td style="text-align: left;">{{ $ba['customer'] }}</td>
                <td style="text-align: left;">{{ $ba['brands'] }}</td>
                @foreach($dates as $date)
                    <td>{{ $ba['days'][$date]['time_in'] }}</td>
                    <td>{{ $ba['days'][$date]['time_out'] }}</td>
                    <td style="color: #0984e3;">{{ $ba['days'][$date]['target'] }}</td>
                    <td style="font-weight: bold; color: {{ $ba['days'][$date]['ach'] >= $ba['days'][$date]['target'] ? '#00b894' : '#d63031' }};">
                        {{ $ba['days'][$date]['ach'] }}
                    </td>
                @endforeach
                <td style="background: #e1f5fe; font-weight: bold;">{{ $ba['total_present'] }}</td>
                <td style="background: #ffebee; font-weight: bold;">{{ $ba['total_absent'] }}</td>
                <td style="background: #f1f2f6; font-weight: bold; color: #0984e3;">{{ $ba['total_target'] }}</td>
                <td style="background: #f1f2f6; font-weight: bold; color: {{ $ba['total_ach'] >= $ba['total_target'] ? '#00b894' : '#d63031' }};">
                    {{ $ba['total_ach'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
