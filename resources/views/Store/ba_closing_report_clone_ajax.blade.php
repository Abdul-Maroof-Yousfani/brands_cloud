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
            <h3>BA Closing Stock Report (Clone)</h3>
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
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; $total = 0; @endphp
                @foreach($stocks as $row)
                @php
                $product = \App\Helpers\CommonHelper::get_product_by_sku($row['sku_code']);
                        $rowTotal = \App\Helpers\ReuseableCode::get_ba_stock_wo_warehouse($product->id);
                        $total += $rowTotal;

                        
                           $transitVal = (int)($row['transit_stock'] ?? 0); // New field
                        $transitTotal += $transitVal;
                    @endphp
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row['sku_code'] }}</td>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['barcode'] }}</td>
                        <td>{{ $row['item_type'] ?? 'N/A' }}</td>

                        <!-- <td>{{ $row['item_type'] != 1 ? 'Commercial' : 'Non-Commercial' }}</td> -->
                        <td>{{ $row['brand'] ?? 'N/A' }}</td>

                        <td>{{ $rowTotal }}</td>
                       
                    </tr>
                @endforeach
            </tbody>

            {{-- Footer Total Row --}}
            <tfoot>
                <tr class="totals-row">
                    <td colspan="6" class="text-end">Total</td>
                      <td>{{ ($total) }}</td>

                </tr>
            </tfoot>
        </table>
    </div>
</div>



