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
                    <th>purchase price</th>
                    <th>Supplier Invoice</th>
                    <th>Supplier Name</th>
                     <th>Stock in Transit (Pcs)</th>

                    @foreach($warehouses as $warehouseName)
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
                        <td>{{ $row['packing'] }}</td>
                        <td>{{ $row['purchase_price'] }}</td>

                  @php
    $supplier_invoice_no = DB::connection('mysql2')
        ->table('goods_receipt_note')
        ->where('grn_no', $row['voucher_no'])
        ->value('supplier_invoice_no');
@endphp
<td>{{ $supplier_invoice_no ?? 'N/A' }}</td>
                  @php
    $supplier_name = DB::connection('mysql2')
        ->table('supplier')
        ->where('id', $row['supplier_id'])
        ->value('name');
@endphp
<td>{{ $supplier_name ?? 'N/A' }}</td>



                         <td>{{ number_format($transitVal) }}</td>

                        @foreach($warehouses as $id => $wName)
                            @php
                                $val = (int)($row[$wName] ?? 0);  // use warehouse name, not ID
                                $warehouseTotals[$id] += $val;
                                $rowTotal += $val;
                            @endphp
                            <td>{{ number_format($val) }}</td>
                        @endforeach

                        <td>{{ number_format($rowTotal) }}</td>
                        @php $grandTotal += $rowTotal; @endphp
                    </tr>
                @endforeach
            </tbody>

            {{-- Footer Total Row --}}
            <tfoot>
                <tr class="totals-row">
                    <td colspan="10" class="text-end">Total</td>
                      <td>{{ number_format($transitTotal) }}</td> 

                    @foreach($warehouses as $id => $wName)
                        <td>{{ number_format($warehouseTotals[$id]) }}</td>
                    @endforeach

                    <td>{{ number_format($grandTotal) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
