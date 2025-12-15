
<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$m = $_GET['m'];?>
<?php echo Form::open(array('url' => 'sales/CreateSalesTaxInvoice?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>


 <style>
 .pagination{float:right;}
.nowrap{white-space:nowrap;}
.text-right-amount{text-align:right !important;}
.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td{padding:8px 4px !important;background:#ddd;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9' /%3E%3C/svg%3E") !important;background-repeat:no-repeat;background-position:center;background-size:12px;color:#6e6b7b;width:5% !important;height:14px;content:'';right:0.3rem;top:1.3rem;}
/* th.userlittab.text-center.col-sm-1.sorting_asc{width:33px !important;}
*/
 .userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{font-weight:300 !important;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{width:8px !important;height:20px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9' /%3E%3C/svg%3E") !important;}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{color:#333 !important;border:1px solid #428bca!important;background-color:white;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fff),color-stop(100%,#dcdcdc));background:-webkit-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-moz-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-ms-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-o-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:#428bca !important;width:25px !important;height:30px!important;line-height:15px;color:#fff !important;}
.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td{padding:8px 8px !important;}
.userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{padding:10px 8px !important;}

 </style>

<div class="panel">
    <div class="panel-body" id="PrintEmpExitInterviewList">
        <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                <div class="table-responsive">
                    <table class="userlittab table table-bordered sf-table-list" id="data-table">
                        <thead>
                        <th style=" width:65px;" class="text-center col-sm-1">Check / Uncheck</th>
                        <th style=" width:35px;"  class="text-center col-sm-1">S.No</th>
                        <th style=" width:100px;"class="text-center col-sm-1">GDN No</th>
                        <th style=" width:110px;" class="text-center col-sm-1">GDN Date</th>
                        <!-- <th style=" width:110px;"class="text-center col-sm-1">Payment Terms</th> -->
                        <th style=" width:110px;" class="text-center col-sm-1">Order Date</th>
                        <th  style=" width:400px;"class="text-center">Customer</th>

                        <th  style=" width:80px;"class="text-center">Total Qty.</th>
                        <th style=" width:110px;" class="text-center">Total Amount.</th>
                        {{--<th class="text-center">View</th>--}}
                        {{--<th class="text-center">Create Sales Tax Invoice</th>--}}
                        {{--<th class="text-center">Edit</th>--}}
                        {{--<th class="text-center">Delete</th>--}}
                        <th style=" width:80px;" class="text-center">View</th>
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
                            <!-- <td class="text-center">{{$row['model_terms_of_payment']}}</td> -->
                            @php
                                $sale_order = App\Models\Sales_Order::where("so_no", $row->so_no)->first();
                            @endphp
                            <!-- <td class="text-center"><?php  echo \Carbon\Carbon::parse($sale_order->so_date)->format("d-M-Y"); ?></td> -->

                              <td class="text-center">
                                                        
                                                    <?php echo $sale_order->timestamp ? \Carbon\Carbon::parse($sale_order->timestamp)->format("d-M-Y") : "";?> <br>
                                                <?php echo $sale_order->timestamp ? \Carbon\Carbon::parse($sale_order->timestamp)->format("h:i:s A") : "";?> 
                                                
                                                </td>
                            <td class="text-center"><strong>{{$customer->name}}</strong></td>
                            <td class="text-right">{{number_format(round($data->qty),0)}}</td>
                            <td class="text-right">{{number_format(round($data->amount+$row->sales_tax_amount +  $sale_taxes_amount_rate),0)}}</td>
                            <td class="text-center">
                                  <div class="dropdown">

                                      <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                      <ul class="dropdown-menu">
                                          <li>
                                              <button style=" width:100%;" onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id?>','','View Delivery Note')" type="button" class="btn btn-success btn-xs">View</button>
                                              
                                            </li>
                                </div>
                            </td>
                            

                        </tr>


                        @endforeach


                        <tr>
                            <td class="text-center" colspan="6" style="font-size: 13px;"><strong>Total</strong></td>
                            <td class="text-right" colspan="1" style="font-size: 13px;color: #333"><strong>{{number_format($total,2)}}</strong></td>
                            <td class="text-center" colspan="1" style="font-size: 13px;"></td>
                            <td class="text-center" colspan="1" style="font-size: 13px;"></td>
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



