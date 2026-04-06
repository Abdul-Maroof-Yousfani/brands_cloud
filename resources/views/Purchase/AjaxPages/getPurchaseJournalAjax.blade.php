<table class="journal-table" id="journalDataTable">
    <thead>
        <tr>
            <th>Bill #</th>
            <th>Reference #</th>
            <th>Date</th>
            <th>Principle</th>
            <th>Notes</th>
            <th>Item Name</th>
            <th>Ctn</th>
            <th>Pcs</th>
            <th>Packing</th>
            <th>Total Pcs</th>
            <th>Unit Price</th>
            <th>Gross Amount</th>
            <th>Disc %</th>
            <th>Disc</th>
            <th>Tax %</th>
            <th>Tax</th>
            <th>Net Amount</th>
        </tr>
    </thead>
    <tbody id="journalBody">
        @php 
            $totalGross = 0; 
            $totalNet = 0;
            $totalTax = 0;
            $totalDisc = 0;
        @endphp
        @forelse($purchase_data as $row)
            @php 
                $totalGross += $row->amount;
                $totalNet += $row->net_amount;
                $totalTax += $row->tax_amount;
                $totalDisc += $row->discount_amount;
                
                $pack_size = ($row->pack_size > 0) ? $row->pack_size : 1;
                $ctn = floor($row->qty / $pack_size);
                $pcs = $row->qty % $pack_size;
                
                $disc_percent = ($row->amount > 0) ? ($row->discount_amount / $row->amount) * 100 : 0;
                $tax_percent = ($row->amount > 0) ? ($row->tax_amount / $row->amount) * 100 : 0;
            @endphp
            <tr>
                <td style="font-weight: 600;">{{ $row->pv_no }}</td>
                <td>{{ $row->vendor_invoice }}</td>
                <td>{{ date('d-m-Y', strtotime($row->pv_date)) }}</td>
                <td style="text-align: left; padding: 10px;">{{ $row->supplier_name }}</td>
                <td><small>{{ $row->notes }}</small></td>
                <td style="text-align: left; padding: 10px;">{{ $row->product_name }}</td>
                <td>{{ $ctn }}</td>
                <td>{{ $pcs }}</td>
                <td>{{ $row->packing }}</td>
                <td style="font-weight: 600;">{{ number_format($row->qty, 0) }}</td>
                <td>{{ number_format($row->rate, 2) }}</td>
                <td>{{ number_format($row->amount, 2) }}</td>
                <td>{{ number_format($disc_percent, 1) }}%</td>
                <td>{{ number_format($row->discount_amount, 2) }}</td>
                <td>{{ number_format($tax_percent, 1) }}%</td>
                <td>{{ number_format($row->tax_amount, 2) }}</td>
                <td style="font-weight: 700; color: #d32f2f;">{{ number_format($row->net_amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="17" class="text-center p-5 text-muted">No journal entries found.</td>
            </tr>
        @endforelse
    </tbody>
    @if($purchase_data->count() > 0)
    <tfoot>
        <tr style="background-color: #f1f4f7; font-weight: 700;">
            <td colspan="9" class="text-right">Page Total:</td>
            <td class="text-center">{{ number_format($purchase_data->sum('qty'), 0) }}</td>
            <td></td>
            <td class="text-center">{{ number_format($totalGross, 2) }}</td>
            <td></td>
            <td class="text-center">{{ number_format($totalDisc, 2) }}</td>
            <td></td>
            <td class="text-center">{{ number_format($totalTax, 2) }}</td>
            <td class="text-center" style="color: #d32f2f;">{{ number_format($totalNet, 2) }}</td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="row mt-3">
    <div class="col-md-6" style="padding-left: 30px; font-size: 13px;">
        Showing {{ $purchase_data->firstItem() }} to {{ $purchase_data->lastItem() }} of {{ $purchase_data->total() }} entries
    </div>
    <div class="col-md-6 text-right pagination-journal" style="padding-right: 30px;">
        {!! $purchase_data->links() !!}
    </div>
</div>

<style>
    .pagination { margin: 0; }
    .pagination li a, .pagination li span { padding: 5px 12px; border-radius: 4px; border: 1px solid #ddd; margin-left: 5px; color: #0073b7; }
    .pagination li.active span { background-color: #0073b7; color: white; border-color: #0073b7; }
</style>
