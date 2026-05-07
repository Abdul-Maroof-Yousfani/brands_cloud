@extends('layouts.default')

@section('content')
<style>
    .filter-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #f0f0f0;
    }
    .report-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
        border: none;
    }
    .metric-item {
        padding: 4px 8px;
        border-radius: 6px;
        margin-bottom: 3px;
        font-size: 11px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }
    .metric-target { border-left: 3px solid #3b82f6; color: #1e40af; }
    .metric-sale { border-left: 3px solid #10b981; color: #065f46; }
    .metric-return { border-left: 3px solid #ef4444; color: #991b1b; }
    .metric-balance { border-left: 3px solid #f59e0b; color: #92400e; background: #fffbeb; }
    
    .total-summary-box {
        background: #f1f5f9;
        padding: 10px;
        border-radius: 8px;
        font-size: 12px;
    }
    .table-report th {
        background: #1e293b !important;
        color: #fff !important;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }
    .empty-state {
        padding: 60px;
        text-align: center;
        background: #f8fafc;
        border-radius: 15px;
        border: 2px dashed #e2e8f0;
    }
</style>

<div class="well_N">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">BA Targets Performance Report</h1>
        <div>
            <span class="badge bg-primary px-3 py-2 shadow-sm">
                <i class="fa fa-calendar me-1"></i> {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}
            </span>
            <span class="badge bg-info px-3 py-2 shadow-sm text-uppercase ms-1">
                <i class="fa fa-tag me-1"></i> {{ $target_type ?? 'qty' }} BASIS
            </span>
        </div>
    </div>

    <div class="filter-card">
        <form action="{{ route('target.report') }}" method="GET" id="reportFilterForm" class="row g-3 align-items-center">
            <div class="col-xl-2 col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Select Month</label>
                <input type="month" name="date" id="reportDate" class="form-control" value="{{ $year }}-{{ sprintf('%02d', $month) }}">
            </div>
            <div class="col-xl-4 col-md-4">
                <label class="form-label small fw-bold text-muted mb-1">Business Associate</label>
                <select name="employee_id" id="reportBA" class="form-select select2">
                    <option value="" {{ is_null($employee_filter) ? 'selected' : '' }}>All Business Associates</option>
                    @foreach($all_employees as $eid => $ename)
                        <option value="{{ $eid }}" {{ !is_null($employee_filter) && $employee_filter == $eid ? 'selected' : '' }}>{{ $ename }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">Target Basis</label>
                <select name="target_type" class="form-select select2">
                    <option value="qty" {{ ($target_type ?? 'qty') == 'qty' ? 'selected' : '' }}>Quantity wise</option>
                    <option value="amount" {{ ($target_type ?? 'qty') == 'amount' ? 'selected' : '' }}>Amount wise</option>
                </select>
            </div>
            <div class="col-xl-4 col-md-3">
                <div class="d-flex gap-2 justify-content-end mt-4">
                      <button type="submit" class="btn btn-primary shadow-sm py-2 px-4">
                        <i class="fa fa-sync-alt me-1"></i> Filter
                    </button>
                    <div class="btn-group shadow-sm">
                        <a href="javascript:void(0)" onclick="exportReport('excel')" class="btn btn-success py-2 px-3">
                            <i class="fa fa-file-excel me-1"></i> Excel
                        </a>
                        <a href="javascript:void(0)" onclick="exportReport('pdf')" class="btn btn-danger py-2 px-3">
                            <i class="fa fa-file-pdf me-1"></i> PDF
                        </a>
                    </div>
                  
                </div>
            </div>
        </form>
    </div>

    @if(count($reports) > 0)
        @php
            $matrix = [];
            $allBrands = [];
            foreach($reports as $item){
                $empId = $item->employee_id ?? 0;
                $matrix[$empId][$item->brand_id] = ($matrix[$empId][$item->brand_id] ?? 0) + $item->target;
                if(!isset($allBrands[$item->brand_id])){
                    $allBrands[$item->brand_id] = $brands[$item->brand_id] ?? 'Brand #' . $item->brand_id;
                }
            }
            $uniqueBrandIds = array_keys($allBrands);
        @endphp

        <div class="report-card">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-report align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th style="min-width: 220px;" class="text-start">Business Associate (BA)</th>
                            @foreach($uniqueBrandIds as $bId)
                                <th>{{ $allBrands[$bId] }}</th>
                            @endforeach
                            <th width="200">Consolidated Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matrix as $empId => $brandTargets)
                            <tr>
                                <td class="p-3">
                                    <div class="fw-bold text-dark fs-6">{{ $employees[$empId] ?? 'Unknown BA' }}</div>
                                    <small class="text-muted"><i class="fa fa-id-badge me-1"></i> ID: {{ $empId }}</small>
                                </td>
                                @php 
                                    $rowTotalTarget = 0; $rowTotalSale = 0;
                                    $rowTotalReturn = 0; $rowTotalBalance = 0;
                                @endphp
                                @foreach($uniqueBrandIds as $bId)
                                    @php 
                                        $target = $brandTargets[$bId] ?? 0; 
                                        $sale = $salesData[$empId][$bId] ?? 0;
                                        $return = $returnsData[$empId][$bId] ?? 0;
                                        $netSale = max(0, $sale - $return);
                                        $balance = $target - $netSale;

                                        $rowTotalTarget += $target; $rowTotalSale += $sale;
                                        $rowTotalReturn += $return; $rowTotalBalance += $balance;
                                    @endphp
                                    <td class="p-2">
                                        @if($target > 0 || $sale > 0 || $return > 0)
                                            <div class="metric-item metric-target">
                                                <span>Target</span>
                                                <span class="fw-bold">{{ number_format($target) }}</span>
                                            </div>
                                            <div class="metric-item metric-sale">
                                                <span>Sale</span>
                                                <span class="fw-bold">{{ number_format($sale) }}</span>
                                            </div>
                                            <div class="metric-item metric-return">
                                                <span>Return</span>
                                                <span class="fw-bold">{{ number_format($return) }}</span>
                                            </div>
                                            <div class="metric-item metric-balance">
                                                <span>Balance</span>
                                                <span class="fw-bold">{{ number_format($balance) }}</span>
                                            </div>
                                        @else
                                            <div class="text-center text-muted small">-</div>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="p-2">
                                    <div class="total-summary-box">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Target:</span>
                                            <span class="fw-bold text-primary">{{ number_format($rowTotalTarget) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Sale:</span>
                                            <span class="fw-bold text-success">{{ number_format($rowTotalSale) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Return:</span>
                                            <span class="fw-bold text-danger">{{ number_format($rowTotalReturn) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-top mt-1 pt-1">
                                            <span class="fw-bold">Balance:</span>
                                            <span class="fw-bold text-warning text-dark">{{ number_format($rowTotalBalance) }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td class="p-3">GRAND TOTAL</td>
                            @php 
                                $grandTotalTarget = 0; $grandTotalSale = 0;
                                $grandTotalReturn = 0; $grandTotalBalance = 0;
                                $brandGrandTotals = [];
                            @endphp
                            @foreach($uniqueBrandIds as $bId)
                                @php
                                    $bt = 0; $bs = 0; $br = 0; $bb = 0;
                                    foreach($matrix as $empId => $brandTargets) {
                                        $t = $brandTargets[$bId] ?? 0;
                                        $s = $salesData[$empId][$bId] ?? 0;
                                        $r = $returnsData[$empId][$bId] ?? 0;
                                        $ns = max(0, $s - $r);
                                        $bt += $t; $bs += $s; $br += $r; $bb += ($t - $ns);
                                    }
                                    $grandTotalTarget += $bt; $grandTotalSale += $bs;
                                    $grandTotalReturn += $br; $grandTotalBalance += $bb;
                                @endphp
                                <td class="p-2">
                                    <div class="text-primary small">T: {{ number_format($bt) }}</div>
                                    <div class="text-success small">S: {{ number_format($bs) }}</div>
                                    <div class="text-danger small">R: {{ number_format($br) }}</div>
                                    <div class="text-dark border-top mt-1 pt-1">B: {{ number_format($bb) }}</div>
                                </td>
                            @endforeach
                            <td class="p-2">
                                <div class="total-summary-box border-primary" style="border-left: 3px solid #3b82f6;">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Target:</span>
                                        <span class="fw-bold text-primary">{{ number_format($grandTotalTarget) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Sale:</span>
                                        <span class="fw-bold text-success">{{ number_format($grandTotalSale) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Return:</span>
                                        <span class="fw-bold text-danger">{{ number_format($grandTotalReturn) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-top mt-1 pt-1">
                                        <span class="fw-bold">Balance:</span>
                                        <span class="fw-bold text-dark">{{ number_format($grandTotalBalance) }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="empty-state shadow-sm">
            <div class="mb-3">
                <i class="fa fa-folder-open fa-4x text-muted" style="opacity: 0.3;"></i>
            </div>
            <h3 class="text-dark fw-bold">No Records Found</h3>
            <p class="text-muted">We couldn't find any target records for <strong>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</strong>.</p>
            <a href="{{ route('baTargets.create') }}" class="btn btn-outline-primary px-4 mt-2">
                <i class="fa fa-plus me-1"></i> Setup New Targets
            </a>
        </div>
    @endif
</div>
@endsection

@section('script')
<script>
    function exportReport(type) {
        let date = $('#reportDate').val();
        let ba = $('#reportBA').val();
        let target_type = $('select[name="target_type"]').val();
        
        // Split date YYYY-MM
        let parts = date.split('-');
        let year = parts[0];
        let month = parseInt(parts[1]);

        let baseUrl = type === 'excel' ? "{{ route('baTargets.exportTemplate') }}" : "{{ route('baTargets.exportPdf') }}";
        let finalUrl = baseUrl + `?year=${year}&month=${month}&employee_id=${ba}&target_type=${target_type}`;
        
        window.location.href = finalUrl;
    }

    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: "Select",
                allowClear: true,
                width: '100%'
            });

            // Trigger submit when any select2 changes
            $('.select2').on('change', function() {
                $('#reportFilterForm').submit();
            });
        }
    });
</script>
@endsection
