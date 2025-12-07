<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(134);
$edit=ReuseableCode::check_rights(135);
$delete=ReuseableCode::check_rights(136);
$approved=ReuseableCode::check_rights(137);
$counter = 1;
$makeTotalAmount = 0;

foreach ($NewRvs as $row1) {

$received_data=   SalesHelper::get_received_data($row1->id);
 $operational = DB::Connection('mysql2')->selectOne('select accounts.operational as  name from new_rv_data as data
		inner join `accounts` on accounts.id = data.acc_id where  data.master_id = \''.$row1->id.'\'')->name;
?>
<tr @if($operational==0) style="background-color: lightcoral" @endif class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>">
    {{--<td class="text-center">--}}
    {{--< ?php if($row1->pv_status ==1):?>--}}
    {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
    {{--< ?php endif;?>--}}
    {{--</td>--}}
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>

    <td class="text-center"><?php echo $row1->cheque_no;?></td>
    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date).'</br>'.$operational;?></td>
    <td class="text-center">{{number_format($received_data->net_amount,2)}}</td>
    <td class="text-center">{{number_format($received_data->tax_amount,2)}}</td>
    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>
    {{--<td class="text-center">< ?php echo $row1->slip_no;?></td>--}}
    <?php //die();?>

    <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
        <?php if($row1->rv_status == 1):?>
        <span class="text-danger" >Pending</span>
        <?php else:?>
        <span class="text-success">Approved</span>
        <?php endif;?>
    </td>
    <?php   //$count=CommonHelper::check_amount_in_ledger($row1->rv_no,$row1->id,2) ?>
    <td class="text-center hidden-print">
        <?php //if($row1->rv_status ==1):?>

        <?php //endif;?>
        <?php if($view == true):?>
        <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','<?php echo $row1->id;?>','View Reciept Voucher Detail','<?php echo $m?>','')" class="btn btn-xs btn-success">View</a>
        <!-- <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','<?php echo $row1->id;?>','View Bank Reciept Voucher Detail','<?php echo $m?>','')" class="btn btn-xs btn-success">View</a> -->
        <?php endif;?>


            @if($row1->rv_status==1)
                <?php if($edit == true):?>
                  <a target="_blank" href="<?php echo url('sales/editVoucherList/'.$row1->id.'?m='.$m)?>" class="btn btn-xs btn-success">Edit</a>
                <?php endif;?>




            @endif


            <?php if($delete == true):?>
            <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->rv_no?>" type="button"
                   onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')"
                   value="Delete" />

                   
                                                        
                                                           
                                                       
            <?php endif;?>
            <a onclick="change_colour('{{$row1->id}}')" target="_blank" href="<?php echo url('sdc/viewReceiptVoucherPrint?id='.$row1->id.'&&m='.$m)?>" class="btn btn-xs btn-success">Print</a>
    </td>
</tr>
<?php
}
?>
<tr>
    <th colspan="8" class="text-center">xxxxx</th>
</tr>