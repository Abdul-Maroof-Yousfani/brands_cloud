<table class="return-table" id="prDataTable">
    <thead>
        <tr>
            <th>Return Invoice #</th>
            <th>Doc Ref #</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Item Details</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="prBody">
        @php 
            $totQty = 0; 
            $totNet = 0;
        @endphp
        @forelse($purchase_data as $row)
            @php 
                $totQty += $row->return_qty;
                $totNet += $row->net_amount;
            @endphp
            <tr>
                <td style="font-weight: 600;">{{ $row->pr_no }}</td>
                <td style="font-weight: 600;">{{ $row->type == 2 && !empty($row->pv_no) ? $row->pv_no : $row->grn_no }}</td>
                <td style="text-align: left; padding-left: 15px;">{{ $row->supplier_name }}</td>
                <td>{{ date('d-m-Y', strtotime($row->pr_date)) }}</td>
                <td style="text-align: left; padding-left: 15px;">{{ $row->product_name }}</td>
                <td style="font-weight: 600;">{{ (float)$row->return_qty + 0 }}</td>
                <td>{{ number_format($row->rate, 2) }}</td>
                <td style="font-weight: 600; color: #d32f2f;">{{ number_format($row->net_amount, 2) }}</td>
                <td>
                    @if($row->status == 1)
                        <span class="status-badge-pr status-approved">Approved</span>
                    @else
                        <span class="status-badge-pr status-pending">Pending</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center p-5 text-muted">No return records found for this period.</td>
            </tr>
        @endforelse
    </tbody>
    @if($purchase_data->count() > 0)
    <tfoot>
        <tr style="background-color: #f8f9fa; font-weight: 700;">
            <td colspan="5" class="text-right p-3">Page Grand Total:</td>
            <td class="text-center p-3">{{ (float)$totQty + 0 }}</td>
            <td class="p-3"></td>
            <td class="text-center p-3" style="color: #d32f2f;">{{ number_format($totNet, 2) }}</td>
            <td class="p-3"></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="row mt-4" style="font-size: 13px;">
    <div class="col-md-6" style="padding: 10px 0 0 10px;">
        Showing {{ $purchase_data->firstItem() }} to {{ $purchase_data->lastItem() }} of {{ $purchase_data->total() }} entries
    </div>
    <div class="col-md-6 text-right pagination-pr">
        {!! $purchase_data->links() !!}
    </div>
</div>

<style>
    .pagination { margin: 0; display: inline-flex; }
    .pagination li a, .pagination li span { padding: 5px 12px; border-radius: 4px; border: 1px solid #ddd; margin-left: 5px; color: #0073b7; text-decoration: none; }
    .pagination li.active span { background-color: #0073b7; color: white; border-color: #0073b7; }
</style>
