<style>
.table-responsive { overflow-y: auto; }
.totals-row { font-weight: bold; background-color: #f5f5f5; }
.table-bordered > thead > tr > th {
    white-space: nowrap !important;
    position: sticky;
    top: 0;
    z-index: 2;
}
.table-wrapper { max-height: 900px; overflow-y: auto; }
</style>

@php
    $warehouseTotals = [];
    foreach ($warehouses as $id => $name) {
        $warehouseTotals[$id] = 0;
    };
    $grandTotal = 0;
     $transitTotal = 0; 
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h3>Closing Stock Report</h3>
            <h5>{{ date('d-M-Y', strtotime($to_date)) }}</h5>
            <!-- <h5>{{ date('d-M-Y', strtotime($from_date)) }} to {{ date('d-M-Y', strtotime($to_date)) }}</h5> -->
        </div>
    </div>

    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-striped" id="exportTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>SKU Code</th>
                    <th>Item Name</th>
                    <th>Barcode</th>
                    <th>Item Type</th>
                    <th>Brand</th>
                    <th>Packing</th>
                     <th>Stock in Transit (Pcs)</th>

                    @foreach($warehouses as $id => $warehouseName)
                        <th>{{ $warehouseName }}</th>
                    @endforeach

                    <th>Total</th> {{-- Row total --}}
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($stocks as $row)
                    @php
                        $rowTotal = 0;
                        $transitVal = (float)($row['transit_stock'] ?? 0);
                        $transitTotal += $transitVal;
                    @endphp
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row['sku_code'] }}</td>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['barcode'] }}</td>
                        <td>{{ $row['item_type'] ?? 'N/A' }}</td>
                        <td>{{ $row['brand'] ?? 'N/A' }}</td>
                        <td>{{ $row['packing'] }}</td>
                        <td>{{ number_format($transitVal, 0) }}</td>

                        @foreach($warehouses as $id => $wName)
                            @php
                                $val = 0;
                                if (isset($row['warehouses'][$id])) {
                                    $val = array_sum($row['warehouses'][$id]);
                                }
                                $warehouseTotals[$id] += $val;
                                $rowTotal += $val;
                            @endphp
                            <td>{{ number_format($val, 0) }}</td>
                        @endforeach

                        <td>{{ number_format($rowTotal, 0) }}</td>
                        @php $grandTotal += $rowTotal; @endphp
                    </tr>
                @endforeach
            </tbody>

            {{-- Footer Total Row --}}
            <tfoot>
                <tr class="totals-row">
                    <td colspan="7" class="text-right">Total</td>
                    <td>{{ number_format($transitTotal, 0) }}</td>

                    @foreach($warehouses as $id => $wName)
                        <td>{{ number_format($warehouseTotals[$id], 0) }}</td>
                    @endforeach

                    <td>{{ number_format($grandTotal, 0) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
