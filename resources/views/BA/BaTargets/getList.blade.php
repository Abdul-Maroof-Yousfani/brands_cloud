<style>
    .target-section {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 8px;
    }
    .target-section.amount-based {
        background: #fff;
        border: 1px solid #eef2ff;
    }
    .target-title {
        font-size: 10px;
        letter-spacing: 0.5px;
        color: #6c757d;
        display: block;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding-bottom: 3px;
        margin-bottom: 8px;
    }
    .badge-target {
        padding: 5px 10px;
        font-weight: 500;
        margin-right: 4px;
        margin-bottom: 4px;
        border-radius: 6px;
        font-size: 11px;
    }
    .summary-box {
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-block;
        width: 100%;
        margin-bottom: 4px;
        background: #fff;
        border: 1px solid #eee;
    }
    .summary-qty {
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    .summary-amt {
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
</style>

<div class="table-responsive">
    <table class="table table-hover table-bordered mb-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <thead class="table-light text-dark">
            <tr class="text-center">
                <th width="50">#</th>
                <th>Month / Year</th>
                <th>Business Associate (BA)</th>
                <th>Customer / Store</th>
                <th>Assigned Targets Breakdown</th>
                <th width="180">Total Summary</th>
            </tr>
        </thead>
        <tbody>
        @foreach($BaTargets as $key => $row)
            <tr class="text-center align-middle">
                <td>{{ $BaTargets->firstItem() + $key }}</td>
                <td class="fw-bold">
                    <span class="text-primary">{{ date('F', mktime(0, 0, 0, $row->month, 1)) }}</span><br>
                    <small class="text-muted">{{ $row->year }}</small>
                </td>
                <td class="text-start">
                    <div class="fw-bold">{{ $employees[$row->employee_id] ?? 'BA: ' . $row->employee_id }}</div>
                    <small class="text-muted">ID: {{ $row->employee_id }}</small>
                </td>
                <td class="text-start">
                    <div class="fw-bold text-dark">{{ $row->customer_name }}</div>
                </td>
                <td class="text-start p-3">
                    @if(!empty($row->qty_targets))
                        <div class="target-section">
                            <span class="target-title text-uppercase fw-bold"><i class="fa fa-cubes me-1"></i> QTY Based Targets</span>
                            <div class="d-flex flex-wrap">
                                @foreach ($row->qty_targets as $bid => $qty)
                                    <span class="badge badge-target bg-success text-white shadow-sm">
                                        {{ $brands[$bid] ?? $bid }}: <span class="fw-bold">{{ number_format($qty) }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(!empty($row->amount_targets))
                        <div class="target-section amount-based">
                            <span class="target-title text-uppercase fw-bold"><i class="fa fa-money-bill me-1"></i> Amount Based Targets</span>
                            <div class="d-flex flex-wrap">
                                @foreach ($row->amount_targets as $bid => $amt)
                                    <span class="badge badge-target bg-primary text-white shadow-sm">
                                        {{ $brands[$bid] ?? $bid }}: <span class="fw-bold">{{ number_format($amt) }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </td>
                <td class="text-center">
                    @if(!empty($row->qty_targets))
                        <div class="summary-box summary-qty text-start">
                            <small class="d-block" style="font-size: 9px; opacity: 0.8;">TOTAL QUANTITY</small>
                            <span class="fs-6 fw-bold">{{ number_format(array_sum($row->qty_targets)) }}</span>
                        </div>
                    @endif
                    @if(!empty($row->amount_targets))
                        <div class="summary-box summary-amt text-start">
                            <small class="d-block" style="font-size: 9px; opacity: 0.8;">TOTAL AMOUNT</small>
                            <span class="fs-6 fw-bold">{{ number_format(array_sum($row->amount_targets)) }}</span>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>

<div id="paginationLinks" class="mt-4 d-flex justify-content-center">
    {{ $BaTargets->links() }}
</div>