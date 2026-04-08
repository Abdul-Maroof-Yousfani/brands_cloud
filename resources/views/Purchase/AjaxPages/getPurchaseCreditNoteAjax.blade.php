<table class="table-cn-main" id="cnDataTable">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Credit Note No</th>
            <th>Ref Invoice No</th>
            <th>Date</th>
            <th>Item Details</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="cnBody">
        @php $grandTotal = 0; @endphp
        @forelse($purchase_data as $row)
            @php 
                $grandTotal += $row->net_amount;
                $statusHtml = '';
                if ($row->status == 1) $statusHtml = '<span class="status-badge-cn badge-approved-cn">Approved</span>';
                else $statusHtml = '<span class="status-badge-cn badge-pending-cn">Pending</span>';
            @endphp
            <tr>
                <td style="text-align: left; padding-left: 10px;">{{ $row->supplier_name }}</td>
                <td style="font-weight: 600;">{{ $row->pr_no }}</td>
                <td style="font-style: italic;">{{ $row->reference_invoice }}</td>
                <td>{{ date('d-m-Y', strtotime($row->pr_date)) }}</td>
                <td style="text-align: left; max-width: 250px;">{{ $row->product_name }}</td>
                <td style="font-weight: 600;">{{ (float)$row->return_qty + 0 }}</td>
                <td>{{ number_format($row->rate, 2) }}</td>
                <td style="font-weight: 700; color: #2c3e50;">{{ number_format($row->net_amount, 2) }}</td>
                <td>{!! $statusHtml !!}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center p-5 text-muted">No Purchase Credit Notes found for this criteria.</td>
            </tr>
        @endforelse
    </tbody>
    @if($purchase_data->count() > 0)
    <tfoot>
        <tr style="background-color: #f1f4f9; font-weight: 800; font-size: 13px;">
            <td colspan="7" class="text-right p-4">Grand Total (This Page):</td>
            <td class="p-4 text-center" style="color: #c62828;">{{ number_format($grandTotal, 2) }}</td>
            <td class="p-4"></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="row mt-4" style="font-size: 12px; padding: 0 10px;">
    <div class="col-md-6" style="padding-top: 10px;">
        Showing {{ $purchase_data->firstItem() }} to {{ $purchase_data->lastItem() }} of {{ $purchase_data->total() }} entries
    </div>
    <div class="col-md-6 text-right pagination-cn">
        {!! $purchase_data->links() !!}
    </div>
</div>

<style>
    .pagination { margin: 0; display: inline-flex; }
    .pagination li a, .pagination li span { padding: 5px 12px; border-radius: 4px; border: 1px solid #ddd; margin-left: 5px; color: #2980b9; text-decoration: none; font-weight: 600; }
    .pagination li.active span { background-color: #2980b9; color: white; border-color: #2980b9; }
</style>
