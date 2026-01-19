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
                    <th>Item Description</th>
                    <th>Ctn</th>
                    <th>Pcs</th>
                    <th>Gross Amount</th>
                    <th>Discount</th>
                    <th>Net Amount</th>
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
                          
                        </tr>
                @endforeach
            </tbody>

            {{-- ✅ FOOTER TOTALS --}}
       
        </table>
    </div>
</div>
