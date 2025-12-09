<?php
// use App\Helpers\App\Helpers\CommonHelper;
// use App\Helpers\FinanceHelper;
// $from = Input::get('fromDate');
// $to = Input::get('toDate');
// $acc_id = explode(',', Input::get('accountName'));
// $acc_id = $acc_id[0];

// // paid to
// $cost_center = Input::get('paid_to');

// if ($cost_center != 0):
//     $clause = 'and sub_department_id="' . $cost_center . '"';
// else:
//     $clause = '';
// endif;

// // end
// $m = Input::get('m');

?>
<style>
    .hov:hover {
        background-color: yellow;
    }
</style>



<div id="">
    <div class="row">
       
    </div>
    <div class="row">
     
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 style="text-align: center;">Unit Activity List</h3>
        </div>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <table class="table table-bordered sf-table-th sf-table-list" id="table_export1">
     
        <thead>

            <tr>
                <th style="width: 100px" class="text-center">S.No</th>
                <th style="width: 120px" class="text-center">Item ID</th>
                <th style="width: 120px" class="text-center">Date</th>
                <th style="width: 120px" class="text-center">Item Description</th>
                <th style="width: 120px" class="text-center">Transaction Type</th>
                <th class="text-center" style="width:100px;">Warehouse</th>
                <th class="text-center" style="width:100px;">User</th>
                <th class="text-center" style="width:100px;">Ref</th>
                <th class="text-center" style="width:100px;">Received Qty</th>
                <th class="text-center" style="width:100px;">Issued Qty</th>
                <th class="text-center" style="width:100px;">Stock in transit</th>
            </tr>
        </thead>
        @php
            $received_qty = 0;
            $issued_qty = 0;
        @endphp
        <tbody id="<?php // echo $member_id;
        ?>">
            @foreach($unit_activities as $index => $unit_activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $unit_activity->sub_item_id }}</td>
                    <td>{{ $unit_activity->voucher_date }}</td>
                    <td>{{ $unit_activity->product_name }}</td>
                    <td>{{ $unit_activity->voucher_type }}</td>
                    @if($unit_activity->voucher_type == 1)
                        <td>GRN</td>
                    @elseif($unit_activity->voucher_type == 2)
                        <td>Purchase Return</td>
                    @elseif($unit_activity->voucher_type == 3)
                        <td>Stock Transfer</td>
                    @elseif($unit_activity->voucher_type == 4)
                        <td>Stock Received</td>
                    @elseif($unit_activity->voucher_type == 5)
                        <td>Sales</td>
                    @elseif($unit_activity->voucher_type == 7)
                        <td>Issuance</td>
                    @endif
                    <td>{{ $unit_activity->warehouse_name }}</td>
                    <td>{{ $unit_activity->username }}</td>
                    <td>{{ $unit_activity->voucher_no }}</td>
                    @if($unit_activity->voucher_type == 2 || $unit_activity->voucher_type == 3 || $unit_activity->voucher_type == 4)
                        <td>{{ number_format($unit_activity->qty, 0) }}</td>
                        @php
                            $received_qty += $unit_activity->qty;
                        @endphp
                    @else
                        @php
                            $received_qty += 0;
                        @endphp
                        <td>0</td>
                    @endif
                    @if($unit_activity->voucher_type == 1 || $unit_activity->voucher_type == 5 || $unit_activity->voucher_type == 7)
                        @php
                            $issued_qty += $unit_activity->qty;
                        @endphp
                        <td>{{  number_format($unit_activity->qty, 0) }}</td>
                    @else
                        @php
                            $issued_qty += 0;
                        @endphp
                        <td>0</td>
                    @endif
                    <td></td>
                </tr>
            @endforeach
            

            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $received_qty }}</td>
                <td>{{ $issued_qty }}</td>
            </tr>

        </tbody>
    </table>
</div>
<script>
    $(document).ready(function(e) {
        $('#print2').click(function() {
            $("div").removeClass("table-responsive");
            $("div").removeClass("well");
            $("a").removeAttr("href");
            //$("a.link_hide").contents().unwrap();
            var content = $("#content").html();
            document.body.innerHTML = content;
            //var content = document.getElementById('header').innerHTML;
            //var content2 = document.getElementById('content').innerHTML;
            window.print();
            location.reload();
        });
    });
</script>
