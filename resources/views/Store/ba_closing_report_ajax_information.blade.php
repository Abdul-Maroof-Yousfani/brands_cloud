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
                    <th>Overall Quantity</th>
                    <th>Sale Order Qty</th>
                    <th>Sale Return Qty</th>
                    <th>Remaining Balance</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($stocks as $row)
                    @php
                        $overall_qty = \App\Helpers\ReuseableCode::get_total_stock($row["item_id"]);
                        $sale_order_amount = $row["sale_order_amount"];
                        $sales_return_amount = $row["sale_return_amount"];
                    @endphp
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row['sku_code'] }}</td>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['barcode'] }}</td>
                        <td>{{ \App\Helpers\CommonHelper::get_product_type_name($row['item_type']) }}</td>

                        <!-- <td>{{ $row['item_type'] != 1 ? 'Commercial' : 'Non-Commercial' }}</td> -->
                        <td>{{ \App\Helpers\CommonHelper::get_brand_by_id($row['brand']) }}</td>
                        <td>{{ \App\Helpers\ReuseableCode::get_total_stock($row["item_id"]) }}</td>
                        <td>{{ $sale_order_amount }}</td>
                        <td>{{ $sales_return_amount }}</td>
                        <td>{{ ($overall_qty - $sale_order_amount) + $sales_return_amount }}</td>
                    </tr>
                @endforeach
            </tbody>


        </table>
    </div>
</div>
