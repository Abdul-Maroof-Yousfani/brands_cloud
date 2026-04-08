<table class="price-table" id="phDataTable">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Item Name</th>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Rate (Purchase Price)</th>
            <th>Amount</th>
            <th>Discount</th>
            <th>Tax %</th>
            <th>Tax Amt</th>
            <th>Net Total</th>
            <th>Trend</th>
        </tr>
    </thead>
    <tbody id="phBody">
        @php 
            $currentItem = null; 
            $prevPrice = 0;
        @endphp
        @forelse($purchase_data as $row)
            @if($currentItem != $row->product_name)
                <tr class="item-group-header">
                    <td colspan="12">{{ $row->product_name }} @if($row->brand_name) - ({{ $row->brand_name }}) @endif</td>
                </tr>
                @php 
                    $currentItem = $row->product_name; 
                    $prevPrice = 0; 
                @endphp
            @endif

            @php
                $trendHtml = '<span class="price-stable"><i class="fa fa-minus"></i> Initial</span>';
                if ($prevPrice > 0) {
                    if ($row->rate > $prevPrice) {
                        $diff = (($row->rate - $prevPrice) / $prevPrice) * 100;
                        $trendHtml = '<span class="price-increase"><i class="fa fa-arrow-up"></i> +'.number_format($diff, 1).'%</span>';
                    } elseif ($row->rate < $prevPrice) {
                        $diff = (($prevPrice - $row->rate) / $prevPrice) * 100;
                        $trendHtml = '<span class="price-decrease"><i class="fa fa-arrow-down"></i> -'.number_format($diff, 1).'%</span>';
                    } else {
                        $trendHtml = '<span class="price-stable"><i class="fa fa-minus"></i> No Change</span>';
                    }
                }
                $prevPrice = $row->rate;
            @endphp

            <tr>
                <td style="text-align: left; padding-left: 20px;">{{ $row->supplier_name }}</td>
                <td>{{ $row->product_name }}</td>
                <td>{{ $row->pv_no }}</td>
                <td>{{ date('d-m-Y', strtotime($row->pv_date)) }}</td>
                <td>{{ (float)$row->qty + 0 }}</td>
                <td style="font-weight: 700;">{{ number_format($row->rate, 2) }}</td>
                <td>{{ number_format($row->amount, 2) }}</td>
                <td>{{ number_format($row->discount_amount, 2) }}</td>
                <td>{{ number_format($row->tax_rate, 2) }}%</td>
                <td>{{ number_format($row->tax_amount, 2) }}</td>
                <td style="font-weight: 600;">{{ number_format($row->net_amount, 2) }}</td>
                <td>{!! $trendHtml !!}</td>

            </tr>
        @empty
            <tr>
                <td colspan="12" class="text-center p-5 text-muted">No price history found for the selected criteria.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="row mt-4" style="font-size: 13px;">
    <div class="col-md-6" style="padding: 10px 0 0 10px;">
        Showing {{ $purchase_data->firstItem() }} to {{ $purchase_data->lastItem() }} of {{ $purchase_data->total() }} entries
    </div>
    <div class="col-md-6 text-right pagination-ph">
        {!! $purchase_data->links() !!}
    </div>
</div>

<style>
    .pagination { margin: 0; display: inline-flex; }
    .pagination li a, .pagination li span { padding: 5px 12px; border-radius: 4px; border: 1px solid #ddd; margin-left: 5px; color: #0073b7; text-decoration: none; }
    .pagination li.active span { background-color: #0073b7; color: white; border-color: #0073b7; }
</style>
