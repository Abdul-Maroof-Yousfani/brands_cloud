<div class="report-card mb-4">
    <h5 class="fw-bold mb-3 text-primary">BA-Wise Adjustment Summary</h5>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>BA Name</th>
                    <th class="text-center">Total Gain Qty</th>
                    <th class="text-center">Total Loss Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $summary = [];
                    foreach($adjustments as $adj) {
                        $ba = $adj->user->name ?? 'N/A';
                        if($adj->voucher_type == 1) {
                            $summary[$ba]['gain'] = ($summary[$ba]['gain'] ?? 0) + $adj->qty;
                        } else {
                            $summary[$ba]['loss'] = ($summary[$ba]['loss'] ?? 0) + $adj->qty;
                        }
                    }
                @endphp
                @foreach($summary as $ba => $totals)
                    <tr>
                        <td>{{ $ba }}</td>
                        <td class="text-center fw-bold text-success">{{ number_format($totals['gain'] ?? 0) }}</td>
                        <td class="text-center fw-bold text-danger">{{ number_format($totals['loss'] ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="report-card">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-report align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Voucher #</th>
                    <th>BA Name</th>
                    <th>Store / Store Qty</th>
                    <th>Item Details</th>
                    <th class="text-center">Adjustment Qty</th>
                    <th class="text-center">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $adj)
                    <tr>
                        <td>{{ date('d-M-Y', strtotime($adj->voucher_date)) }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $adj->voucher_no }}</span></td>
                        <td>{{ $adj->user->name ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $adj->customer->name ?? 'N/A' }}</strong><br>
                            <small class="text-muted">Recorded by Mobile App</small>
                        </td>
                        <td>
                            <strong>{{ $adj->product->product_name ?? 'N/A' }}</strong><br>
                            <small class="text-muted">SKU: {{ $adj->product->sku_code ?? '-' }} | Barcode: {{ $adj->product->product_barcode ?? '-' }}</small>
                        </td>
                        <td class="text-center fw-bold">{{ number_format($adj->qty) }}</td>
                        <td class="text-center">
                            @if($adj->voucher_type == 1)
                                <span class="badge bg-success-soft text-success px-3">GAIN / INCREASE</span>
                            @else
                                <span class="badge bg-danger-soft text-danger px-3">LOSS / DECREASE</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No stock adjustment records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .bg-success-soft { background-color: #e6fffa; }
    .bg-danger-soft { background-color: #fff5f5; }
</style>
