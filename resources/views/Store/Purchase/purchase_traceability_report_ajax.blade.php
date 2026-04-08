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
            <h3>Purchase Traceability Report</h3>
            <h5>{{ date('d-M-Y', strtotime(now())) }}</h5>
        </div>
    </div>

    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-striped" id="exportTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Supplier</th>
                    <th>Warehouse</th>
                    <th>Region</th>
                    <th>Product Name</th>
                    <th>PO #</th>
                    <th>PO Date</th>
                    <th>PO Amount</th>
                    <th>PO Quantity</th>
                    <th>GRN #</th>
                    <th>GRN Date</th>
                    <th>GRN Amount</th>
                    <th>GRN Quantity</th>
                    <th>Purchase Invoice #</th>
                    <th>Purchase Invoice Date</th>
                    <th>Purchase Invoice Amount</th>
                    <th>Purchase Invoice Quantity</th>

                </tr>
            </thead>
            <tbody> 
            
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \App\Helpers\CommonHelper::get_supplier_name($purchase->supplier_id) }}</td>
                            <td>{{ $purchase->warehouse_name }}</td>
                            <td>{{ $purchase->region_name }}</td>
                            <td>{{ $purchase->product_name }}</td>
                            <td>{{ $purchase->po_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->pr_date)->format("d-M-Y") }}</td>
                            <td>{{ number_format($purchase->po_amount, 2) }}</td>
                            <td>{{ (float)$purchase->po_qty + 0 }}</td>
                            <td>{{ $purchase->grn_no }}</td>
                            <td>{{ !empty($purchase->grn_date) ? \Carbon\Carbon::parse($purchase->grn_date)->format("d-M-Y") : '' }}</td>
                            <td>{{ number_format($purchase->grn_amount, 2) }}</td>
                            <td>{{ (float)$purchase->grn_qty + 0 }}</td>
                            <td>{{ $purchase->pi_no }}</td>
                            <td>{{ !empty($purchase->pi_date) ? \Carbon\Carbon::parse($purchase->pi_date)->format("d-M-Y") : '' }}</td>
                            <td>{{ number_format($purchase->invoice_amount, 2) }}</td>
                            <td>{{ (float)$purchase->invoice_qty + 0 }}</td>
                        </tr>
                @endforeach
            </tbody>

            {{-- ✅ FOOTER TOTALS --}}
       
        </table>
    </div>
</div>
