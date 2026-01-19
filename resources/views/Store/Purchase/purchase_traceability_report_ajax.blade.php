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
                    <th>Principle</th>
                    <th>Brand</th>
                    <th>Warehouse</th>
                    <th>Region</th>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $purchase->po_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->pr_date)->format("d-M-Y") }}</td>
                            <td>{{ $purchase->po_amount }}</td>
                            <td>{{ $purchase->po_qty }}</td>
                            <td>{{ $purchase->grn_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->grn_date)->format("d-M-Y") }}</td>
                            <td>{{ $purchase->grn_amount }}</td>
                            <td>{{ $purchase->grn_qty }}</td>
                            <td>{{ $purchase->pi_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->pi_date)->format("d-M-Y") }}</td>
                            <td>{{ $purchase->invoice_amount }}</td>
                            <td>{{ $purchase->pv_qty }}</td>
                        </tr>
                @endforeach
            </tbody>

            {{-- ✅ FOOTER TOTALS --}}
       
        </table>
    </div>
</div>
