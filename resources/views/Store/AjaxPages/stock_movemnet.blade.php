<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
?>

<table id="EmpExitInterviewList" class="table table-bordered table-responsive">
    <thead>
    <thead>
    <th colspan="9" class="text-center"><h3><h3 style="text-align: center;"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h3></h3></th>
    </thead>
    <thead>
        <th colspan="9" class="text-center"><h3>Stock Movement Report Inventory</h3></th>
    </thead>
    <thead>
        <th colspan="9" class="text-center"><h4>From : {{CommonHelper::changeDateFormat($from).' TO: '.CommonHelper::changeDateFormat($to)}}</h4></th>
    </thead>
    <thead>
        <th colspan="9" class="text-right"><p style="float: right;">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></p></th>
    </thead>
    <thead>
    <th class="text-center">S.No</th>
    <th  class="text-center">Item</th>
    <th class="text-center">Open. QTY</th>
    <th class="text-center">IN QTY</th>
    <th class="text-center">OUT QTY</th>
    <th class="text-center">Variance QTY</th>
    <th class="text-center">Adj. QTY</th>
    <th class="text-center">Open Return</th>
    <th class="text-center">IN Stock QTY</th>
    </thead>
    <tbody id="">
        @php
         $count=1;
         $total_open_qty=0;
         $total_in_qty=0;
        $cl_qty=0;
        $tot_out_qty=0;
        $total_incomplete_return=0;
        $total_purchase_side=0;
        $total_variance_qty=0;
        $total_adj_qty=0;
        @endphp
        <?php
        $cr_no=[];
       $dataa= DB::Connection('mysql2')->select('select a.cr_no from credit_note a
               inner JOIN credit_note_data b
               ON a.id=b.master_id
               inner join delivery_note_data c
               ON b.voucher_data_id=c.id
               inner join delivery_note d
               ON c.master_id=d.id
               where a.status=1
               and b.status=1
               and a.type=1
               and d.sales_tax_invoice=1
               GROUP by a.cr_no');


                foreach($dataa as $row):

                $cr_no[]='"'.$row->cr_no.'"';


                endforeach;
                $cr_value= implode(',',$cr_no);
                 //implode(',',$data->cr_no);

        ?>
        @foreach($data as $row)

        <?php

        $purchase_side=0;
                        // open process
        $open_data=ReuseableCode::get_opening($from,$to,$accyeafrom,$row->sub_item_id,1);
        $open_qty=$open_data[0];

                // in process
        // We exclude variance (4) and adjustment (10, 11) from core IN if we want it clean, 
        // but let's see: user said "Add column variance Qty (from transfer in out variance)".
        // If we keep in_qty as is, it's double display but correct total. 
        // Let's make IN and OUT core types (1,6 and 2,3) to avoid double counting in display.
        
        $type='1,6'; 
        $in_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,$type);
        $in_qty=$in_data[0];

                // out process
        $type='2,3';
        $out_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,$type);
        $out_qty=$out_data[0];

        // Variance: Transfer IN (4) - Transfer OUT (5)
        $tr_in_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,'4');
        $tr_out_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,'5');
        $variance_qty = $tr_in_data[0] - $tr_out_data[0];

        // Adjustment: (Return (10) + Make (11)) - (Cons (8) + Issue (9))
        $adj_in_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,'10,11');
        $adj_out_data=ReuseableCode::get_stock_type_wise($from,$to,$row->sub_item_id,'8,9');
        $adj_qty = $adj_in_data[0] - $adj_out_data[0];

        $remianig_qty=0;
        // Total In-Stock QTY = Open + IN - OUT + Variance + Adjustment
        $remianig_qty=$open_qty + $in_qty - $out_qty + $variance_qty + $adj_qty;

        ?>
        <tr title="{{$row->sub_item_id}}">
            <td>{{$count++}}</td>
            <td><small>{{$row->product_name}}</small></td>
            <td><small>{{number_format($open_qty,2)}}</small></td>
            <td><small>{{number_format($in_qty,2)}}</small></td>
            <td><small>{{number_format($out_qty,2)}}</small></td>
            <td><small>{{number_format($variance_qty,2)}}</small>@php $total_variance_qty+=$variance_qty; @endphp</td>
            <td><small>{{number_format($adj_qty,2)}}</small>@php $total_adj_qty+=$adj_qty @endphp</td>

            <?php
            $incomplete_dn=0;
            $incomplete_val=0;
            $incomplete_return = DB::Connection('mysql2')->selectOne('select sum(c.amount) amount
                from stock as c
                inner join purchase_return as a on c.voucher_no=a.pr_no
                where a.type=1 and a.status=1 and a.pr_date between "'.$from.'" and "'.$to.'"
                and c.sub_item_id="'.$row->sub_item_id.'"');

            if (!empty($incomplete_return->amount)):
                $incomplete_val = $incomplete_return->amount;
            endif;
            ?>

            <?php
                $clause='';
            if ($cr_value!=''):
                $clause='and a.cr_no not in ('.$cr_value.')';
            endif;
            $incomplete_sales_retrun = DB::Connection('mysql2')->selectOne('select sum(c.amount) amount
                from stock as c
                inner join credit_note a on c.voucher_no=a.cr_no
                where a.type=1 '.$clause.' and a.status=1 
                and a.cr_date between "'.$from.'" and "'.$to.'"
                and c.sub_item_id="'.$row->sub_item_id.'"');
            ?>

            <td>
                <?php
                if (!empty($incomplete_sales_retrun->amount)):
                    $incomplete_sales_retrun_val = $incomplete_sales_retrun->amount;
                else:
                    $incomplete_sales_retrun_val = 0;
                endif;
                echo number_format($incomplete_val + $incomplete_sales_retrun_val, 2);
                $total_purchase_side += $incomplete_val + $incomplete_sales_retrun_val ?>
            </td>

            <td style="font-weight: bold"><small>{{number_format($remianig_qty,2)}}</small></td>
        </tr>

            <?php
            $total_open_qty+=$open_qty;
            $total_in_qty+=$in_qty;
            $tot_out_qty+=$out_qty;
            $cl_qty+=$remianig_qty;
            ?>

        @endforeach
        <tr>
            <td colspan="2">Total</td>
            <td colspan="1">{{number_format($total_open_qty,2)}}</td>
            <td colspan="1">{{number_format($total_in_qty,2)}}</td>
            <td colspan="1">{{number_format($tot_out_qty,2)}}</td>
            <td colspan="1">{{number_format($total_variance_qty,2)}}</td>
            <td colspan="1">{{number_format($total_adj_qty,2)}}</td>
            <td colspan="1">{{number_format($total_purchase_side,2)}}</td>
            <td colspan="1">{{number_format($cl_qty,2)}}</td>
        </tr>
    </tbody>
</table>


<p>Purchase: {{ReuseableCode::stock_type_amount($from,$to,1)}}</p>
<p>Purchase Return: {{ReuseableCode::stock_type_amount($from,$to,2)}}</p>
<p>Sales Return: {{ReuseableCode::stock_type_amount($from,$to,6)}}</p>


