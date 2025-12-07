
<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
echo Form::open(array('url' => 'finance/CreateReceiptVoucherForDebit?m='.$m,'id'=>'cashPaymentVoucherForm'));?>
<table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
    <thead>
         <th class="text-center col-sm-1">S.No</th>
        <th class="text-center col-sm-1">Store</th>
        <th class="text-center col-sm-2">Delivery Man</th>
        <th class="text-center col-sm-2">Description</th>
        <th class="text-center">Debit</th>
        <th class="text-center">On Record</th>
        <th class="text-center">Voucher Type</th>
        <th class="text-center">Branch</th>
        <th class="text-center">Amount</th>
        <th class="text-center">Received Amount</th>
        <th class="text-center">Status</th>
        <th class="text-center">view</th>
    </thead>
    <tbody id="data">
    
        <?php
            $total_amount = 0;
            
            $counter = 1;
        ?>
    @foreach($Invoice as $row)
     @php
            $received_amount = \App\Helpers\SalesHelper::get_received_payment_for_debit($row->rv_no);
               $amount = $row->amount - $received_amount;
               if($amount <= 0) continue;
           @endphp
        <?php
        // CommonHelper::companyDatabaseConnection($_GET['m']);
        // $data=SalesHelper::getTotalAmountSalesTaxInvoice($row->id);
        // $get_freight=SalesHelper::get_freight($row->id);
        $customer=CommonHelper::byers_name($row->store);
        // $rece=SalesHelper::get_received_payment($row->id);
        // $return_amount=SalesHelper::get_sales_return_from_sales_tax_invoice($row->id);
        // CommonHelper::reconnectMasterDatabase();

        //                                             $saleOrderDetail = CommonHelper::get_so_by_SONO($row->so_no);


        //     $sale_taxes_amount_rate = 0;
        //     if($saleOrderDetail){
        //         $sale_taxes_amount_rate = $saleOrderDetail->sale_taxes_amount_rate ?? 0;
        //     }

   
        //  $rema=$data->total+$get_freight-$return_amount-$rece;
        //         if($rema > 0):
        ?>
        <tr title="{{$row->id}}" id="{{$row->id}}">

            <td class="text-center">
                <input name="checkbox[]" onclick="check(),supplier_check('',this.id)"
                       class="checkbox1 form-control AllCheckbox AddRemoveClass<?php echo $row->store?>"
                       id="<?php echo $row->store?>" type="checkbox" value="{{$row->id}}"
                       onchange="CheckUncheck('<?php echo $counter?>','<?php echo $row->store?>')" style="height: 30px;">
            </td>

            <td class="text-center">
                {{ \App\Helpers\CommonHelper::byers_name($row->store)->name ?? "N/A" }}
            </td>

             <td class="text-center">
                {{ $row->delivery_man }}
            </td>

            
            <td class="text-center">
                {{ $row->details }}
            </td>

            <td class="text-center">
                {{ $row->debit != "-" ? \App\Helpers\CommonHelper::get_account_name($row->debit)->name ?? "N/A" : "N/A" }}
            </td>

            <td class="text-center">
                {{ $row->on_record == 0 ? "No" : "Yes" }}
            </td>

            
            <td class="text-center">
                {{ \App\Helpers\CommonHelper::get_vouchers($row->voucher_type)[0]->name ?? "N/A" }}
            </td>

            
            <td class="text-center">
                {{ \App\Helpers\CommonHelper::get_branch_by_id($row->branch) }}
            </td>

          
          

           @if($amount > 0)
            <td>
                {{ number_format($amount, 2) }}
            </td>
            @else
            <td>
                0
            </td>
            @endif

              <td class="text-center">
               {{ number_format($row->amount, 2) }}
           </td>

             <td class="text-center">
                {{ $row->status == 1 ? "Approved" : "Pending" }}
            </td>



            
            <td class="text-center"><button
                        onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                        type="button" class="btn btn-success btn-xs">View</button></td>


            <?php
                $total_amount += $row->amount;
            ?>

            {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
            {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
        </tr>

    @endforeach

    <tr>
        <tr>
            <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
            <td class="text-right" colspan="1" style="font-size: 20px;color: white">&nbsp;</td>
            <td class="text-right" colspan="1" style="font-size: 20px;">{{ number_format($total_amount, 2) }}</td>
            <td class="text-right" colspan="2" style="font-size: 20px;">&nbsp;</td>
        </tr>
        
    </tr>
    <tr>
        <td colspan="10">
            <input type="submit" value="Create" class="btn btn-sm btn-primary BtnEnDs BtnSub" id="add">
        </td>
    </tr>
    </tbody>
</table>
<?php Form::close(); ?>
