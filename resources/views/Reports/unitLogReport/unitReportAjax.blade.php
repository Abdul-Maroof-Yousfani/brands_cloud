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



<div id="unit_log_report">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3 style="text-align: center;">Unit Activity List</h3>
        </div>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <table class="table table-bordered sf-table-th sf-table-list" id="table_export1">
        <thead>
            <tr>
                <th style="width: 50px" class="text-center">S.No</th>
                <th style="width: 100px" class="text-center">Date</th>
                <th style="width: 120px" class="text-center">Item ID</th>
                <th style="" class="text-center">Item Description</th>
                <th style="width: 120px" class="text-center">Transaction Type</th>
                <th class="text-center" style="width:120px;">Warehouse</th>
                <th class="text-center" style="width:100px;">User</th>
                <th class="text-center" style="width:100px;">Ref</th>
                <th class="text-center" style="width:100px;">Received Qty</th>
                <th class="text-center" style="width:100px;">Issued Qty</th>
                <th class="text-center" style="width:100px;">Stock in transit</th>
                <th class="text-center" style="width:120px;">Balance</th>
            </tr>
        </thead>
        @php
            $received_qty = 0;
            $issued_qty = 0;
            
            // Adjust opening balance with transit stock (based on existing logic)
            $running_balance = $received_opening_bal - $issued_opening_bal + $transit_bal;
        @endphp
        <tbody id="<?php // echo $member_id; ?>">
            <tr style="background:#f9f9f9; font-weight:bold;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Opening Balance</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center">{{ number_format($received_opening_bal) }}</td>
                <td class="text-center">{{ number_format($issued_opening_bal) }}</td>
                <td class="text-center">{{ number_format($transit_bal) }}</td>
                <td class="text-center" style="background:#eef">{{ number_format($running_balance) }}</td>
            </tr>
            @foreach($unit_activities as $index => $unit_activity)
                @php
                    $is_received = false;
                    $is_issued = false;
                    $trans_type = "Unknown (" . $unit_activity->voucher_type . ")";
                    
                    if($unit_activity->voucher_type == 1) { // Wait, GRN
                        if(str_contains(strtolower($unit_activity->voucher_no), 'dn')) {
                            // Some old data might have dn as 1
                            $trans_type = "Sales";
                            $is_issued = true;
                        } else if(str_contains(strtolower($unit_activity->voucher_no), 'grn') || $unit_activity->voucher_no == '') {
                            $trans_type = "GRN";
                            $is_received = true;
                        } else if(str_contains(strtolower($unit_activity->voucher_no), 'tr')) {
                            // approve_transfer erroneously saves IN transfers as voucher_type 1
                            $trans_type = "Stock Transfer (In)";
                            $is_received = true;
                        } else {
                            $trans_type = "GRN / Opening";
                            $is_received = true;
                        }
                    } elseif($unit_activity->voucher_type == 2) {
                        $trans_type = "Purchase Return";
                        $is_received = true;
                    } elseif($unit_activity->voucher_type == 3) {
                        $trans_type = "Stock Transfer";
                        // Distinguish OUT and IN for transfer
                        if($unit_activity->warehouse_id_from == $unit_activity->warehouse_id) {
                            $is_issued = true;
                            $trans_type = "Stock Transfer (Out)";
                        } else {
                            $is_received = true;
                            $trans_type = "Stock Transfer (In)";
                        }
                    } elseif($unit_activity->voucher_type == 4) {
                        $trans_type = "Stock Received";
                        $is_received = true;
                    } elseif($unit_activity->voucher_type == 5 || $unit_activity->voucher_type == 50) {
                        $trans_type = "Sales";
                        $is_issued = true;
                    } elseif($unit_activity->voucher_type == 6) {
                        $trans_type = "Sales Return";
                        $is_received = true;
                    } elseif($unit_activity->voucher_type == 7) {
                        $trans_type = "Issuance";
                        $is_issued = true;
                    }
                    
                    if($is_received){
                        $running_balance += $unit_activity->qty;
                        $received_qty += $unit_activity->qty;
                    } elseif($is_issued){
                        $running_balance -= $unit_activity->qty;
                        $issued_qty += $unit_activity->qty;
                    }

                    $m = isset($_GET['m']) ? $_GET['m'] : '';
                    $url = "";
                    $text = "";
                    $voucher_no =  strtolower($unit_activity->voucher_no);
                    if(str_contains($voucher_no, "tr")) {
                        $url = "stdc/viewStockTransferDetail?m=".$m;
                        $text = "View Stock Transfer Detail";
                    } 
                    if(str_contains($voucher_no, "dn")) {
                        $delivery_note = App\Models\DeliveryNote::select("id")->where("gd_no", $voucher_no)->first();
                        $url = 'sales/viewDeliveryNoteDetail/' . ($delivery_note ? $delivery_note->id : '');
                        $text = "View Delivery Note";
                    }
                    if(str_contains($voucher_no, "so")) {
                        $sale_order = App\Models\Sales_Order::select("id")->where("so_no", $voucher_no)->first();
                        $url = 'selling/viewSaleOrderPrint/' . $voucher_no;
                        $text = "View Sale Order";
                    }
                    if(str_contains($voucher_no, "cr")) {
                        $url = '#'; // Optional logic for Credit Note View can be added
                        $text = "View Credit Note";
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($unit_activity->voucher_date)->format("d-M-Y") }}</td>
                    <td class="text-center">{{ $unit_activity->sub_item_id }}</td>
                    <td>{{ $unit_activity->product_name }}</td>
                    <td>{{ $trans_type }}</td>
                    <td>{{ $unit_activity->warehouse_name }}</td>
                    <td>{{ $unit_activity->username }}</td>
                    <td>
                        <a onclick="showDetailModelOneParamerter('{{ $url }}', '{{ $unit_activity->voucher_no }}','{{ $text }}')" style="cursor:pointer;">{{ $unit_activity->voucher_no }}</a>
                    </td>
                    <td class="text-center">{{ $is_received ? number_format($unit_activity->qty, 0) : '0' }}</td>
                    <td class="text-center">{{ $is_issued ? number_format($unit_activity->qty, 0) : '0' }}</td>
                    <td class="text-center">0</td> <!-- Transit on row level logic missing initially -->
                    <td class="text-center" style="background:#eef; font-weight:bold;">{{ number_format($running_balance) }}</td>
                </tr>
            @endforeach
            
            <tr style="background:#ddd; font-weight:bold;">
                <td colspan="8" class="text-right" style="padding-right: 15px;">Total Activity:</td>
                <td class="text-center">{{ number_format($received_qty) }}</td>
                <td class="text-center">{{ number_format($issued_qty) }}</td>
                <td></td>
                <td class="text-center">{{ number_format($running_balance) }}</td>
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
