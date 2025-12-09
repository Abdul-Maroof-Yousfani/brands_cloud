
<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$m = $_GET['m'];?>
<?php echo Form::open(array('url' => 'sales/CreateSalesTaxInvoice?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
<div class="panel">
    <div class="panel-body" id="PrintEmpExitInterviewList">
        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered sf-table-list" id="data-table">
                        <thead>
                        <th class="text-center col-sm-1">Check / Uncheck</th>
                        <th class="text-center col-sm-1">S.No</th>
                        <th class="text-center col-sm-1">GD No</th>
                        <th class="text-center col-sm-1">GD Date</th>
                        <th class="text-center col-sm-1">Payment Terms</th>
                        <th class="text-center col-sm-1">Order Date</th>
                        <th class="text-center">Customer</th>

                        <th class="text-center">Total Qty.</th>
                        <th class="text-center">Total Amount.</th>
                        {{--<th class="text-center">View</th>--}}
                        {{--<th class="text-center">Create Sales Tax Invoice</th>--}}
                        {{--<th class="text-center">Edit</th>--}}
                        {{--<th class="text-center">Delete</th>--}}
                        <th class="text-center">View</th>
                        </thead>
                        <tbody id="data">
                        <?php $counter = 1;$total=0;?>

                        @foreach($delivery_note as $row)

                        <?php
                        $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id); 

                      
                        
                         $saleOrderDetail = CommonHelper::get_so_by_SONO($row->so_no);
                                                    $sale_taxes_amount_rate = 0;
                                                    if($saleOrderDetail){
                                                        $sale_taxes_amount_rate = $saleOrderDetail->sale_taxes_amount_rate ?? 0;
                                                    }
                        ?>



                        <?php
                        $customer=CommonHelper::byers_name($row->buyers_id); ?>
                        <tr title="{{$row->id}}" id="{{$row->id}}">
                            <td class="text-center">
                                <input name="checkbox[]" class="checkbox1" id="1chk<?php echo $counter?>" type="checkbox" value="<?php echo $row->id?>" onclick="checking()" />
                            </td>
                            <td class="text-center">{{$counter++}}</td>
                            <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>
                            <td class="text-center"><?php  echo \Carbon\Carbon::parse($row->gd_date)->format("d-M-Y") ;?></td>
                            <td class="text-center">{{$row['model_terms_of_payment']}}</td>
                            @php
                                $sale_order = App\Models\Sales_Order::where("so_no", $row->so_no)->first();
                            @endphp
                            <td class="text-center"><?php  echo \Carbon\Carbon::parse($sale_order->so_date)->format("d-M-Y"); ?></td>
                            <td class="text-center">{{$customer->name}}</td>
                            <td class="text-right">{{number_format($data->qty,3)}}</td>
                            <td class="text-right">{{number_format($data->amount+$row->sales_tax_amount +  $sale_taxes_amount_rate,3)}}</td>
                            <td class="text-center">
                                <button onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id?>','','View Delivery Note')" type="button" class="btn btn-success btn-xs">View</button>
                            </td>
                            

                        </tr>


                        @endforeach


                        <tr>
                            <td class="text-center" colspan="6" style="font-size: 20px;">Total</td>
                            <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}</td>
                            <td class="text-center" colspan="1" style="font-size: 20px;"></td>
                            <td class="text-center" colspan="1" style="font-size: 20px;"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn btn-sm btn-primary" id="BtnPayment" disabled>Create Sales Tax Invoice</button>
                </div>
            </div>
    </div>

</div>
<script !src="">
    function checking()
    {
        var lenght =0;
        $('.checkbox1').each(function()
        {
            if ($(this).is(':checked'))
            {
                lenght++;
            }
        });
        if(lenght > 0)
        {
            $('#BtnPayment').prop('disabled',false);
        }
        else
        {
            $('#BtnPayment').prop('disabled',true);
        }
    }
</script>
<script>
    // data-table

    $("#data-table").DataTable({
        ordering: true, 
        searching: true, 
        paging: true, 
        info: false
    })
</script>