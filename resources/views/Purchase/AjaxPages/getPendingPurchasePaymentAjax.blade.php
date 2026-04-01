<table class="payment-table" id="pmDataTable">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Invoice No</th>
            <th>Invoice Date</th>
            <th>Total Amount</th>
            <th>Paid Amount</th>
            <th>Pending Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="pmBody">
        @php 
            $totTotal = 0; 
            $totPaid = 0;
            $totPending = 0;
        @endphp
        @forelse($purchase_data as $row)
            @php 
                $total = $row->total_amount ?? 0;
                $paid = $row->paid_amount ?? 0;
                $pending = $total - $paid;
                
                $totTotal += $total;
                $totPaid += $paid;
                $totPending += $pending;
                
                $statusHtml = '';
                if ($paid >= $total && $total > 0) $statusHtml = '<span class="status-pill status-paid">Paid</span>';
                elseif ($paid > 0) $statusHtml = '<span class="status-pill status-partial">Partial</span>';
                else $statusHtml = '<span class="status-pill status-unpaid">Unpaid</span>';
            @endphp
            <tr>
                <td style="text-align: left; padding-left: 20px;">{{ $row->supplier_name }}</td>
                <td style="font-weight: 600;">{{ $row->pv_no }}</td>
                <td>{{ date('d-m-Y', strtotime($row->pv_date)) }}</td>
                <td style="font-weight: 600;">{{ number_format($total, 2) }}</td>
                <td style="color: #2e7d32; font-weight: 600;">{{ number_format($paid, 2) }}</td>
                <td class="pending-amount-hl">{{ number_format($pending, 2) }}</td>
                <td>{!! $statusHtml !!}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center p-5 text-muted">Excellent! No pending payments found for this criteria.</td>
            </tr>
        @endforelse
    </tbody>
    @if($purchase_data->count() > 0)
    <tfoot>
        <tr style="background-color: #f1f4f9; font-weight: 800; font-size: 14px;">
            <td colspan="3" class="text-right p-4">Grand Total (This Page):</td>
            <td class="p-4 text-center">{{ number_format($totTotal, 2) }}</td>
            <td class="p-4 text-center" style="color: #2e7d32;">{{ number_format($totPaid, 2) }}</td>
            <td class="p-4 text-center" style="color: #c62828;">{{ number_format($totPending, 2) }}</td>
            <td class="p-4"></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="row mt-4" style="font-size: 13px;">
    <div class="col-md-6" style="padding: 10px 0 0 10px;">
        Showing {{ $purchase_data->firstItem() }} to {{ $purchase_data->lastItem() }} of {{ $purchase_data->total() }} entries
    </div>
    <div class="col-md-6 text-right pagination-pm">
        {!! $purchase_data->links() !!}
    </div>
</div>

<style>
    .pagination { margin: 0; display: inline-flex; }
    .pagination li a, .pagination li span { padding: 6px 14px; border-radius: 4px; border: 1px solid #ddd; margin-left: 5px; color: #2980b9; text-decoration: none; font-weight: 600; }
    .pagination li.active span { background-color: #2980b9; color: white; border-color: #2980b9; }
</style>
