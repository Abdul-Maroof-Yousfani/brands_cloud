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
    $customerTotals = [];
    foreach ($customersMap as $id => $customer) {
        $customerTotals[$id] = 0;
    }
    $grandTotal   = 0;
    $transitTotal = 0;
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h3>Customer Wise Product Stock</h3>
            <h5>{{ date('d-M-Y', strtotime($to_date)) }}</h5>
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

                    {{-- ✅ CUSTOMER HEADERS --}}
                    @foreach($customersMap as $customer)
                        <th>{{ $customer }}</th>
                    @endforeach

                    <th>Total</th>
                </tr>
            </thead>
            <tbody> 
                @php $counter = 1; @endphp

                @foreach($stocks as $row)
                    @php
                        $rowTotal = 0;

                        $transitVal = (int) ($row['transit_stock'] ?? 0);
                        $transitTotal += $transitVal;
                    @endphp

                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row['sku_code'] }}</td>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['barcode'] }}</td>
                        <td>{{ $row['item_type'] ?? 'N/A' }}</td>
                        <td>{{ $row['brand'] ?? 'N/A' }}</td>

                        {{-- ✅ CUSTOMER STOCK CELLS --}}
                        @foreach($customersMap as $id => $customer)
                            @php
                               
                                $val = (int)($row[$customer] ?? 0);  // use warehouse name, not ID
                                $customerTotals[$id] += $val;
                                $rowTotal = $val;

                                // $rowTotal += $qty;
                                // $customerTotals[$id] += $qty;
                            @endphp
                            <td class="text-end">{{ $rowTotal }}</td>
                        @endforeach
                    

                        {{-- Row Total --}}
                        <td class="text-end">{{ $rowTotal }}</td>

                        @php $grandTotal += $rowTotal; @endphp
                    </tr>
                @endforeach
            </tbody>

            {{-- ✅ FOOTER TOTALS --}}
            <tfoot>
                <tr class="totals-row">
                    <td colspan="6" class="text-end">Total</td>

                    @foreach($customersMap as $id => $customer)
                        <td class="text-end">
                            {{ $customerTotals[$id] }}
                        </td>
                    @endforeach

                    <td class="text-end">{{ $grandTotal }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
