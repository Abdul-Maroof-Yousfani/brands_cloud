
<?php use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(37);
$edit=ReuseableCode::check_rights(211);
$delete=ReuseableCode::check_rights(38);
?>

<?php $counter = 1;$total=0; $total_t_amount=0;?>

@foreach($purchase_voucher as $row)
    <?php
    $net_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id',$row->id)->where('additional_exp',0)->sum('net_amount');


    $count= DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->grn_id)->count();
         if($count>1):
            //echo die('dup');
            endif;

    $net_amount_grn= DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->grn_id)->where('status',1)->sum('net_amount');
    $grn_date= DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->where('id',$row->grn_id)->value('grn_date');
    $t_amount= DB::Connection('mysql2')->table('transactions')->where('voucher_no',$row->pv_no)
            ->where('acc_id',97)
            ->where('debit_credit',1)->sum('amount');
    $total+=$net_amount; $total_t_amount+=$t_amount;?>

    <tr @if($t_amount!=$net_amount) @elseif($net_amount!=$net_amount_grn) style="background-color: cornflowerblue" @endif id="{{$row->id}}">
        <td class="text-center">{{$counter++.' '.$count}}</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->pv_no)}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->pv_date);?></td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->grn_no)}}<br>
            {{$grn_date}}
        </td>
        <td class="text-center">{{$row['slip_no']}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
        <td id="app{{ $row->id }}" class="text-center text-danger">@if($row->pv_status==1) Pending @elseif($row->pv_status==3) 1st Approve  @else Approved @endif </td>
        <td class="text-center">{{CommonHelper::get_supplier_name($row->supplier)}}</td>

        <td class="text-right">{{number_format($net_amount,2)}}</td>
        <td class="text-right">{{number_format($net_amount_grn,2)}}</td>
        <?php $total+=$row['total_net_amount']; ?>
        {{--
        <!-- <td class="text-center"> -->
            @if($view==true)
            <button
            onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"
            type="button" class="btn btn-success btn-xs">View</button>
            @endif

            @if($delete==true)<button onclick="delete_record('<?php echo $row->id?>','<?php echo $row->grn_no ?>','<?php echo $row->pv_no?>')" type="button" class="btn btn-danger btn-xs">Delete</button>@endif
            <!-- </td> -->
            --}}
            <td class="text-center">
                @if($view==true)
                    @if($row->grn_no == 0)

                            <button
                                onclick="showDetailModelOneParamerter('fdc/viewDirectPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"
                                type="button" class="btn btn-success btn-xs">View
                            </button>
                    @else
                            <button
                                    onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row->id ?>','View Purchase Voucher','<?php echo $m?>')"
                                    type="button" class="btn btn-success btn-xs">View
                            </button>
                    @endif
                    @endif


                <?php if($row->pv_status == 1):?>

                    @if($edit==true )
                        @if($row->grn_no == '0')
                            <a  href="{{ URL::asset('finance/editDirectPurchaseVoucherForm/'.$row->id.'?m='.$m) }}" class="btn btn-success btn-xs btn{{ $row->id }}">Edit </a>
                        @else
                            <a href="{{ URL::asset('finance/editPurchaseVoucherFormNew/'.$row->id.'?m='.$m) }}" class="btn btn-success btn-xs btn{{ $row->id }}">Edit </a>
                        @endif
                    @endif

                <?php else:?>

                <?php endif;?>


                @if($delete==true && $row->pv_status != 2)
                    <button  onclick="delete_record('<?php echo $row->id?>','<?php echo $row->grn_no ?>','<?php echo $row->pv_no?>')" type="button" class="btn btn-danger btn-xs btn{{ $row->id }}">Delete</button>
                @endif
            </td>

    </tr>


@endforeach
<tr>
    <td colspan="8">Total</td>
    <td>{{number_format($total,2)}}<br>
        {{--{{number_format($total_t_amount,2)}}--}}
    </td>
    <td></td>
    <td></td>
</tr>


