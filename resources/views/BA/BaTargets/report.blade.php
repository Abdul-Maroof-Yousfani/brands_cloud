@extends('layouts.default')

@section('content')
<div class="well_N">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <h2 class="mb-0 fw-semibold">BA Targets Report</h2>

    <form action="{{ route('target.report') }}" method="GET" class="d-flex gap-2 align-items-end">
        <div>
            
            <input type="month"
                   name="date"
                   class="form-control form-control-sm"
                   value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" style="margin-left: 27px;margin-top: 40px;">
        </div>

        <button class="btn btn-primary btn-sm px-4" style="margin-left: 67px;margin-top: 31px;">Filter</button>
    </form>
</div>


    @if(count($reports) > 0)
        @php
            // Prepare Data for Matrix
            $matrix = [];
            $allBrands = [];
            // Assuming $brands is keyed by ID
            
            // Get all unique brands present in the reports to form columns
            // Or use the passed $brands if we want to show all brands even if no target (but better to show only relevant ones)
            
            foreach($reports as $item){
                $matrix[$item->customer_id][$item->brand_id] = $item->target;
                if(!isset($allBrands[$item->brand_id])){
                    $allBrands[$item->brand_id] = $brands[$item->brand_id] ?? 'Brand #' . $item->brand_id;
                }
            }
            
            // Sort brands potentially?
            // ksort($allBrands); 
            $uniqueBrandIds = array_keys($allBrands);
        @endphp
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="min-width: 200px;">Customer</th>
                        @foreach($uniqueBrandIds as $bId)
                            <th>{{ $allBrands[$bId] }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrix as $custId => $brandTargets)
                        <tr>
                            <td>{{ $customers[$custId] ?? 'Unknown Customer' }}</td>
                            @php 
                                $rowTotalTarget = 0; 
                                $rowTotalSale = 0;
                                $rowTotalReturn = 0;
                                $rowTotalBalance = 0;
                            @endphp
                            @foreach($uniqueBrandIds as $bId)
                                @php 
                                    $target = $brandTargets[$bId] ?? 0; 
                                    $sale = $salesData[$custId][$bId] ?? 0;
                                    $return = $returnsData[$custId][$bId] ?? 0;
                                    $netSale = max(0, $sale - $return);
                                    $balance = $target - $netSale;

                                    if($target > 0) $rowTotalTarget += $target;
                                    $rowTotalSale += $sale;
                                    $rowTotalReturn += $return;
                                    $rowTotalBalance += $balance;
                                @endphp
                                <td class="text-center" style="font-size: 0.85em; vertical-align: middle;">
                                    @if($target > 0 || $sale > 0 || $return > 0)
                                        <div style="border-bottom: 1px solid #dee2e6; margin-bottom: 2px;">
                                            <span class="badge bg-primary" title="Target">Target: {{ number_format($target) }}</span>
                                        </div>
                                        <div style="border-bottom: 1px solid #dee2e6; margin-bottom: 2px;">
                                            <span class="badge bg-success" title="Sale">Sale: {{ number_format($sale) }}</span>
                                        </div>
                                        <div style="border-bottom: 1px solid #dee2e6; margin-bottom: 2px;">
                                            <span class="badge bg-danger" title="Return">Return: {{ number_format($return) }}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning text-dark" title="Balance">Balance: {{ number_format($balance) }}</span>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center" style="vertical-align: middle;">
                                <div style="font-weight: bold;">
                                    <div class="text-primary">Total Target: {{ number_format($rowTotalTarget) }}</div>
                                    <div class="text-success">Total Sale: {{ number_format($rowTotalSale) }}</div>
                                    <div class="text-danger">Total Return: {{ number_format($rowTotalReturn) }}</div>
                                    <div class="text-warning text-dark">Total Balance: {{ number_format($rowTotalBalance) }}</div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    @else
        <div class="alert alert-info">No records found for {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}.</div>
    @endif
</div>
@endsection
