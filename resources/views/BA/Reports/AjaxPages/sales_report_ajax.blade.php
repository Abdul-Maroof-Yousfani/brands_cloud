<div class="report-card mb-4">
    <h5 class="fw-bold mb-3">BA-Wise Sales Summary</h5>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>BA Name</th>
                    <th>Brand</th>
                    <th class="text-center">Total Qty Sold</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $summary = [];
                    foreach($sales as $sale) {
                        foreach($sale->details as $detail) {
                            $ba = $sale->user->name ?? 'N/A';
                            $br = $detail->brand->name ?? 'N/A';
                            $summary[$ba][$br] = ($summary[$ba][$br] ?? 0) + $detail->qty;
                        }
                    }
                @endphp
                @foreach($summary as $ba => $brands)
                    @foreach($brands as $br => $qty)
                        <tr>
                            <td>{{ $ba }}</td>
                            <td>{{ $br }}</td>
                            <td class="text-center fw-bold">{{ number_format($qty) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="report-card">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-report align-middle mb-0" id="salesTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>BA Name</th>
                    <th>Store / Distributor</th>
                    <th>Item Details</th>
                    <th class="text-center">Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    @foreach($sale->details as $index => $detail)
                        <tr>
                            @if($index == 0)
                                <td rowspan="{{ count($sale->details) }}">{{ date('d-M-Y', strtotime($sale->sale_order_date)) }}</td>
                                <td rowspan="{{ count($sale->details) }}">{{ $sale->user->name ?? 'N/A' }}</td>
                                <td rowspan="{{ count($sale->details) }}">{{ $sale->distributor->name ?? 'N/A' }}</td>
                            @endif
                            <td>
                                <strong>{{ $detail->product->product_name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">
                                    SKU: {{ $detail->product->sku_code ?? '-' }} | 
                                    Barcode: {{ $detail->product->product_barcode ?? '-' }} |
                                    Brand: {{ $detail->brand->name ?? '-' }}
                                </small>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($detail->qty) }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No sales records found for selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    if (typeof XLSX !== 'undefined') {
        // Functionality for export if needed
    }
</script>
