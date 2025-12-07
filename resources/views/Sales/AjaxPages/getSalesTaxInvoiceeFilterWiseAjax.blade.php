<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(118);
$edit=ReuseableCode::check_rights(119);
$delete=ReuseableCode::check_rights(120);
$counter = 1;$total=0;

?>
<?php $main_total=0;
        $open=0;
        $parttial=0;
        $complete=0;
 $radio;

?>
@foreach($sales_tax_invoice as $row)
    <?php $customer=CommonHelper::byers_name($row->buyers_id);
    $data=SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row->id);
    $freight=SalesHelper::get_freight($row->id);
            $BuyersUnit = '';
            $BuyerOrderNo = '';
            if($row->so_id != 0 ):
    $SoData = DB::Connection('mysql2')->table('sales_order')->where('id',$row->so_id)->select('buyers_unit')->first();
            $BuyersUnit = $SoData->buyers_unit;
            // $BuyerOrderNo = $SoData->order_no;
            endif;




   $amount=$data->amount+$row->sales_tax+$freight+$row->sales_tax_further;;
   $received_amount=SalesHelper::get_received_amount($row->id);
   $main_amount=$amount;
   $diffrence= $main_amount-$received_amount;

            if ($diffrence<0):
            $diffrence=0;
            endif;
            if ($diffrence<=0.5):
                $diffrence=0;
            endif;


  if ($diffrence==$main_amount):
       $status='Open';

      $open++;
   elseif($main_amount!='' && $diffrence!=0 && $diffrence<$main_amount):
        $status='partial';
       $parttial++;
    elseif($diffrence<=0):
        $status='Complete';
        $complete++;
   endif;

    $validation='all';
    if ($radio==1):

        if ($diffrence==$main_amount):
            $validation=$status;
        endif;
    endif;
    if ($radio==2):
        if($main_amount!='' && $diffrence!=0 && $diffrence<$main_amount):
            $validation=$status;
        endif; endif;
    if ($radio==3):
        if($diffrence<=0):
            $validation=$status;

        endif; endif;
    if ($radio==''):
        $validation=$status;
    endif;

   // $amount_data=SalesHelper::get_total_amount_for_invoice_by_id($row->id);
  //  echo $amount_data->amount+$data->addition_amount+$data->sales_tax;

    ?>
    @if ($validation==$status )

        <?php
      //  $amount=  DB::Connection('mysql2')->table('transactions')->where('voucher_no',$row->gi_no)->where('status',1)->where('voucher_type',6)->where('debit_credit',1)->sum('amount');



    ?>
    <tr @if($status=='Open') style="background-color: #fdc8c8" @elseif($status=='partial') style="background-color: #c9d6ec" @endif title="{{$row->id}}" id="{{$row->id}}">
        <td title="{{number_format($diffrence)}}" class="text-center">{{$counter++}}</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gi_no)}}</td>
        <!-- <td>
            <input type="text" id="ScNo<?php echo $row->id?>" class="form-control" value="<?php echo $row->sc_no?>">
            <button type="button" class=" btn btn-xs btn-success" id="BtnUpdate<?php echo $row->id?>" onclick="UpdateValue('<?php echo $row->id?>')">Update</button>
            <span id="ScNoError<?php echo $row->id?>"></span>
        </td> -->
        <td class="text-center"><?php echo $BuyersUnit?></td>
        <td class="text-center"><?php echo $BuyerOrderNo?></td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gi_date);?></td>
        <td class="text-center">{{$row->model_terms_of_payment}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
        <td class="text-center">{{$customer->name}}</td>
        <td  class="text-right">{{number_format($amount,2)}}</td>


        <td class="text-center">{{$status}}</td>
        <td class="text-center">
            {{-- {{$row->username}} --}} 
            @if ($row->si_status==1) Pending @elseif ($row->si_status==2) 1st Approved @else Approved @endif
        </td>


        <td class="text-center">
            <?php if($view == true):?>
            <button onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                    type="button" class="btn btn-success btn-xs">View</button>
            <?php endif;?>
            <?php if($edit == true):?>
            {{--<button onclick="sales_tax('< ?php echo $row->id?>','< ?php echo $m ?>')"--}}
                    {{--type="button" class="btn btn-primery btn-xs">Edit Sales Tax Invoice</button>--}}
                <?php endif;?>
                <?php if($delete == true):?>
            <button onclick="sales_tax_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                    type="button" class="btn btn-danger btn-xs">Delete</button>
            <?php endif;?>

            @if(empty($row->approve_user_1) ||  empty($row->approve_user_1) &&  (!empty($row->so_no)) )
            <a target="_blank" class="btn btn-xs btn-primary" href="{{route('editDirectSalesTaxInvoice',$row->id)}}">Edit</a>
       @endif

                <a target="_blank" class="btn btn-xs btn-info" href="<?php echo url('/')?>/sales/PrintSalesTaxInvoiceDirect?id=<?php echo $row->id?>">Print</a>
        </td>



        {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
        {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
    </tr>
    <?php $main_total+=$amount ?>
    @endif
@endforeach
<tr style="font-size: large;font-weight: bold">
    <td colspan="9">Total</td>
    <td colspan="2" class="text-center">{{number_format($main_total,2)}}</td>
    <td colspan="2"></td>
</tr>

<tr>
    <td class="text-center" colspan="10" style="font-size: 20px;">Total</td>
    <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}</td>
    <td class="text-center" colspan="2" style="font-size: 20px;"></td>

</tr>
<tr>
    <td colspan="10"></td>
    <td colspan="2" style="font-size: 18px;"><strong>Open</strong></td>
    <td style="font-size: 18px;"><strong><?php echo $open?></strong></td>
</tr>
<tr>
    <td colspan="10"></td>
    <td colspan="2" style="font-size: 18px;"><strong>Partial</strong></td>
    <td style="font-size: 18px;"><strong><?php echo $parttial?></strong></td>
</tr>
<tr>
    <td colspan="10"></td>
    <td colspan="2" style="font-size: 18px;"><strong>Complete</strong></td>
    <td style="font-size: 18px;"><strong><?php echo $complete?></strong></td>
</tr>
