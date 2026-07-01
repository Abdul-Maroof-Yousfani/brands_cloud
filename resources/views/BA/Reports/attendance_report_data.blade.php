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
        <tbody id="reportTbody">
            @if(isset($reportData) && count($reportData) > 0)
                @include('BA.Reports.attendance_report_rows')
            @endif
        </tbody>
        <tfoot id="reportTfoot">
            <tr style="background: #2d3436; color: white; font-weight: bold;">
                <td colspan="6" style="text-align: right;">GRAND TOTAL:</td>
                @foreach($dates as $date)
                    <td></td>
                    <td></td>
                    <td>{{ number_format($grandTotals['days'][$date]['target'], 2) }}</td>
                    <td>{{ number_format($grandTotals['days'][$date]['ach'], 0) }}</td>
                @endforeach
                <td>{{ number_format($grandTotals['present'], 0) }}</td>
                <td>{{ number_format($grandTotals['absent'], 0) }}</td>
                <td>{{ number_format($grandTotals['target'], 2) }}</td>
                <td>{{ number_format($grandTotals['ach'], 0) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
