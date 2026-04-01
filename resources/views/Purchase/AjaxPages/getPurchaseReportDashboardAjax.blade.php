<table class="table report-table" id="mainReportTable">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Item Details</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="reportData">
        @php $grandTotal = 0; @endphp
        @forelse($purchase_data as $row)
            @php $grandTotal += $row->net_amount; @endphp
            <tr>
                <td style="text-align: left; padding-left: 30px;">{{ $row->supplier_name }}</td>
                <td><strong>{{ $row->pv_no }}</strong></td>
                <td>{{ date('d M Y', strtotime($row->pv_date)) }}</td>
                <td>{{ $row->product_name }} <br><small class="text-muted">{{ $row->brand_name }}</small></td>
                <td>{{ number_format($row->qty, 2) }}</td>
                <td>{{ number_format($row->rate, 2) }}</td>
                <td class="total-highlight">{{ number_format($row->net_amount, 2) }}</td>
                <td>
                    @if($row->pv_status == 2)
                        <span class="status-badge status-approved">Approved</span>
                    @elseif($row->pv_status == 1)
                        <span class="status-badge status-pending">Pending</span>
                    @elseif($row->pv_status == 3)
                        <span class="status-badge status-pending">1st Approve</span>
                    @else
                        <span class="status-badge">{{ $row->pv_status }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                    <i class="fa fa-info-circle fa-2x mb-3"></i><br>
                    No purchase records found for the selected criteria.
                </td>
            </tr>
        @endforelse

        @if($purchase_data->count() > 0)
            <tr style="background-color: #fefefe; border-top: 2px solid #edeff2;">
                <td colspan="6" class="text-right" style="padding-right: 50px;"><strong>Page total:</strong></td>
                <td class="total-highlight" style="font-size: 16px; color: #d32f2f;">{{ number_format($grandTotal, 2) }}</td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="row" style="padding: 15px 30px;">
    <div class="col-md-6 text-left">
        Showing {{ $purchase_data->firstItem() }} to {{ $purchase_data->lastItem() }} of {{ $purchase_data->total() }} entries
    </div>
    <div class="col-md-6 text-right pagination-wrapper">
        {!! $purchase_data->links() !!}
    </div>
</div>

<style>
    .pagination {
        margin: 0;
    }
    .pagination-wrapper .pagination li a, .pagination-wrapper .pagination li span {
        padding: 5px 12px;
        border-radius: 4px;
        margin-left: 5px;
    }
</style>
