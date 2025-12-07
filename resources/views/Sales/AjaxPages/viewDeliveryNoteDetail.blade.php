<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;



$total_amount = 0;
// $m = $_GET['m'];
$m = 1;
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;

$buyers_detail= CommonHelper::get_buyer_detail($delivery_note->buyers_id);
$so_detail = CommonHelper::get_so_by_SONO($delivery_note->so_no);

// if($accType == 'client'){
//     $m = $_GET['m'];
// }else{
//     $m = Auth::user()->company_id;
// }





$checkifbarcodescanningproduct = DB::Connection('mysql2')->table('delivery_note_data')
    ->leftJoin('subitem','delivery_note_data.item_id','subitem.id')
    ->where('subitem.is_barcode_scanning',1)
    ->where('master_id',$delivery_note->id);


$scannedqrcode=        DB::Connection('mysql2')->table('stock_barcodes')->where('voucher_no',$delivery_note->gd_no);
$scannedqrcodeqty = $scannedqrcode->count();
$grnqty=        $checkifbarcodescanningproduct->sum('qty');


$total_qty = 0;
$total_foc = 0;
$total_discount_amount = 0;
$total_gross_amount = 0;
$total_tax_amount = 0;
$total_amount_after_tax = 0;


foreach ($delivery_note_data as $sale_order_item) {
    $saleOrderDetail = CommonHelper::get_item_detials($sale_order_item->so_data_id);

    $total_qty += $sale_order_item->qty;
    $total_foc += $sale_order_item->foc;
    $total_discount_amount += $saleOrderDetail->discount_amount_1;
    $total_gross_amount += $saleOrderDetail->sub_total;
    $total_tax_amount += $sale_order_item->tax_amount;
    $total_amount_after_tax += $sale_order_item->amount;
}
?>
<style>
    p{margin:0;padding:0;font-size:13px;font-weight:500;}
    input.form-control.form-control2{margin:0!important;}
    .table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th{vertical-align:inherit !important;text-align:left !important;padding: 7px 5px !important;}
    .totlas{display:flex;justify-content:right;gap:70px;background:#ddd;width:18%;float:right;padding-right:8px;}
    .totlas p{font-weight:bold;}
    .psds{display:flex;justify-content:right;gap:88px;}
    .psds p{font-weight:bold;}
    .userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{padding:10px 5px !important;}
    .totlass{display:inline;background:transparent;margin-top:-25px;}
    .totlass h2{font-size:13px !important;}
</style>
<?php

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::newdisplayPrintButtonInView('printReport','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printReport">
    @if($scannedqrcodeqty != $grnqty)
        <div class="alert alert-danger text-left fw-bold">
            All QR codes must be scanned first. Scanned Barcodes: {{$scannedqrcodeqty}}, Required Barcodes: {{$grnqty}}.`
        </div>
    @endif





    @if($delivery_note->status == 0)
            @if($scannedqrcodeqty != $grnqty)
                <input type="button" value="Approve" readonly  id="errorbutton" class="btn btn-success btn-xs ">
            @else
                <div class="">
                        <?php echo Form::open(array('url' => 'sad/approveDeliveryNote?m='.$delivery_note->id.'','id='.$delivery_note->id.'','id'=>'approveDeliveryNote','class'=>'stop'));?>
                            <!-- {{ Form::submit('Submit', ['class' => 'btn btn-success']) }} -->
                    <input type="hidden" name="pageType"
                           value="<?php // echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode"
                           value="<?php // echo $_GET['parentCode']?>">
                    {{ Form::submit('Approve', ['class' => 'btn btn-success btn-xs btn-abc hidden-print']) }}

                </div>
            @endif

    @endif


    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well sales_or">

            <div class="contra">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="contr">
                            <h2 class="subHeadingLabelClass">Brands Unlimited (Pvt) Ltd</h2>
                            <p>301-305, 3rd Floor, Kavish Crown Plaza
                                Sharah-e-Faisal, karachi.</p>

                            <p>N.T.N #:5098058-8 </p>
                            <p>S.t #: 3277876156235</p>


                            <p>Bill To:</p>
                            <p>
                                <strong>{{$buyers_detail->name}}</strong>
                                <br>
                                <br>
                                {{$buyers_detail->address}}<br>
                                {{ CommonHelper::get_all_country_by_id($buyers_detail->country)->name ?? ''}}<br>
                                {{$buyers_detail->phone_1}}<br>
                                N.T.N #:
                                {{isset($buyers_detail->cnic_ntn) ? $buyers_detail->cnic_ntn : "-" }}
                                S.T #: {{isset($buyers_detail->strn) ? $buyers_detail->strn : "-"}}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6  text-right">
                        <div class="contr2">
                            <h2 class="subHeadingLabelClass">Goods Delivery Note</h2>
                            <br>
                            <p>Document # {{$delivery_note->so_no}}</p>
                            <p>Date: {{$delivery_note->so_date}}</p>
                            <br>
                            <div class="table-responsive">
                                <table class="sale-list userlittab table table-bordered sf-table-list" style="border: 1px solid #000;">
                                    <tbody>
                                        <tr>
                                            <td>Amount Limited</td>
                                            <td style="text-align: right;">
                                                {{ $so_detail->credit_limit }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Balance Amount</td>
                                            <td style="text-align: right;">
                                                {{ $so_detail->balance_amount }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Current Balance Due</td>
                                            <td style="text-align: right;">
                                                {{ number_format($total_amount_after_tax, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                                @php $buyers_warehouse_name = CommonHelper::buyers_id_with_warehouse_name($delivery_note->buyers_id);
                                
                                
                                
                                @endphp
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="term">
                                        <p>Terms:</p>
                                        <p>SO Date: {{$delivery_note->so_date}}</p>
                                        <p>Warehouse: {{$buyers_warehouse_name}}</p>
                                        <p>Payment Terms: 30 Days</p>
                                        <p>Salesperson Mobile #</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="term">
                                        <p>SO #: {{$delivery_note->so_no}}</p>
                                        <p>GDN #: <?php echo strtoupper($delivery_note->gd_no);?></p>
                                        <p>Branch: {{$delivery_note->branch}}</p>
                                        <p>Salesperson: {{$buyers_detail->SaleRep}}</p>
                                        <p><strong></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <!-- <h2 class="subHeadingLabelClass">Item Details</h2> -->
                        <div class="table-responsive">
                            <table class="table sale_older_tab userlittab table table-bordered sf-table-list sale-list">
                                <thead>
                                    <tr>
                                        <th style="background: #000 !important; color:#fff !important;width: 1% !important; text-align: center;">S.No</th>
                                        <th style="background: #000 !important; color:#fff !important;width: 60% !important;">Product</th>
                                        {{--   <th style="background: #000 !important; color:#fff !important;">Item & Description</th>--}}
                                        <th style="background: #000 !important; color:#fff !important; text-align: center !important">Barcode</th>
                                        <th style="background: #000 !important; color:#fff !important; text-align: center !important">Qty</th>
                                        
                                        <th style="background: #000 !important; color:#fff !important; text-align: center !important">FOC</th>
                                        <!-- <th style="background: #000 !important; color:#fff !important;">MRP</th>
                                        <th style="background: #000 !important; color:#fff !important;">Rate</th>
                                        <th style="background: #000 !important; color:#fff !important;">Gross Amount</th>
                                        <th style="background: #000 !important; color:#fff !important;">Disc (%)</th>
                                        <th style="background: #000 !important; color:#fff !important;">Disc Amount</th> -->
                                        {{--  <th style="background: #000 !important; color:#fff !important;">Disc 2(%)</th>--}}
                                        {{--  <th style="background: #000 !important; color:#fff !important;">Disc 2 Amount</th>--}}
                                        <!-- <th style="background: #000 !important; color:#fff !important;">Tax (%)</th>
                                        <th style="background: #000 !important; color:#fff !important;">Tax Amount</th>
                                        <th style="background: #000 !important; color:#fff !important;">Total Amount</th> -->
                                    </tr>
                                </thead>
                                <tbody id="data">
                                    @foreach($delivery_note_data as $index => $sale_order_item)
                                    @php
                                        $saleOrderDetail = CommonHelper::get_item_detials($sale_order_item->so_data_id);
                                        $product = CommonHelper::get_product_name($sale_order_item->item_id);
                                        $productid = CommonHelper::get_product_sku($sale_order_item->item_id);
                                        $productbarcode = CommonHelper::product_barcode($sale_order_item->item_id);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center;">
                                            {{ $index + 1 }}
                                        </td>
                                        <td style="width: 1%">
                                            ({{$productid ?? ""}})-{{$product ?? "----"}}
                                        </td>
                                        <td  style="text-align: center !important;" class="wsale2">
                                            <p>  {{$productbarcode ?? "--"}}</p>
                                        </td>
                                        <td  style="text-align: center !important;" class="wsale2"><p>{{number_format($sale_order_item->qty)}}</p></td>
                                        <td style="text-align: center !important;">{{number_format($sale_order_item->foc)}}</td>
                                        <!-- <td style="text-align: center !important;">{{number_format($sale_order_item->mrp_price)}}</td>
                                        <td style="text-align: center !important;">{{number_format($sale_order_item->rate)}}</td>
                                        <td style="text-align: center !important;">{{number_format($saleOrderDetail->sub_total,2)}}</td>
                                        <td style="text-align: center !important;">{{number_format($saleOrderDetail->discount_percent_1,2)}}%</td>
                                        <td style="text-align: center !important;">{{number_format($saleOrderDetail->discount_amount_1,2)}}</td>
                                        <td  style="text-align: center !important;">{{number_format($sale_order_item->tax)}}%</td>
                                        <td  style="text-align: center !important;">{{number_format($sale_order_item->tax_amount,2)}}</td>
                                        <td style="text-align: center !important;">{{number_format($saleOrderDetail->amount,2)}}</td> -->
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;font-size:13px!important;font-weight:400!important;">Sub Total</th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p id="total_qty">{{number_format($total_qty)}}</p></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p id="total_qty">{{number_format($total_foc)}}</p></th>
                                        <!-- <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p style="text-align: center !important;" id="total-fac">{{ number_format($total_gross_amount, 2) }}</p></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p style="text-align: center !important;" id="total-fac">{{ number_format($total_discount_amount, 2) }}</p></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p style="text-align: center !important;" id="total-fac">{{ number_format($total_tax_amount, 2) }}</p></th>
                                        <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p style="text-align: center !important;" id="total-fac">{{ number_format($total_amount_after_tax, 2) }}</p></th> -->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row align-items-top">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <!-- <div class="totlas totlass">
                                <h2>Note</h2>
                                <p>{{ $so_detail->remark ?? 'N/A' }}</p>
                                
                            </div> -->
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <br>
                                <br>
                                <br>
                            <!-- <div class="totals3">
                                <div class="psds">
                                    {{ CommonHelper::get_sale_tax_persentage_by_id($so_detail->sale_taxes_id)}}
                                    <p id="sale_taxes_amount_rate" style="margin:0 !important;padding:0 !important;font-size:13px !important;font-weight:500 !important;">{{$so_detail->sale_taxes_amount_rate}}</p>
                                </div>
                                <div class="totlas">
                                    <p>Total</p>
                                    <p>{{ number_format((float)$total_amount_after_tax + (float)$so_detail->sale_taxes_amount_rate, 2) }}</p>
                                </div>
                            </div> -->
                             
                    <div class="totlas totlass">
                        <h2>Note</h2>
                        <p>{{ $so_detail->remark ?? 'N/A' }}</p>
                    </div>
            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-top">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    
                </div>
                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="totlas totlass">
                        <h2>Note</h2>
                        <p>{{ $so_detail->remark ?? 'N/A' }}</p>
                    </div>
                </div> -->
            </div>


            <div class="row">
                <div class="sgnature">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <div class="row">

                            <!-- <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">

                                <h2 class="subHeadingLabelClass"><?php echo strtoupper($delivery_note->username);?></h2>
                                <p><strong>Prepared By</strong> </p>
                            </div> -->

                             <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">
                                                    <p><strong>{{$delivery_note->username}}</strong> </p>
                                                    <p><strong>Prepared By</strong> </p>
                                                </div>
                             <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">
                                                    <p><strong></strong> </p>
            
                                                    <p><strong>Approved By</strong> </p>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">
                                                    <p><strong></strong> </p>

                                                    <p><strong>Received By</strong> </p>
                                                </div>
                        </div>

                    </div>
                    <br><br>
                      <div class="vomp">
                                        <p><strong style="margin-left: 50px;">
                                            Creation Time :
                                            {{ \Carbon\Carbon::parse($delivery_note->timestamp)->format('d-M-Y h:i A') }}
                                        </strong></p>

                                     
                                    </div>
                </div>
            </div>



            <div class="row hide">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left">

                    {{--<label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo CommonHelper::changeDateFormat($currentDate);?></label>--}}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <h3 style="text-align: center;">Delivery Note</h3>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    {{--< ?php $nameOfDay = date('l', strtotime($currentDate)); ?>--}}
                    {{--<label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;">< ?php echo '&nbsp;'.$nameOfDay;?></label>--}}

                </div>
            </div>
            
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row hide">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:49%; float:left;">
                        <table class="table " style="border: solid 1px  black">
                            <tbody>
                                <?php $customer_data= CommonHelper::byers_name($delivery_note->buyers_id);?>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">Buyer's Name</td>
                                    <td class="text-left" style="border: solid 1px black;">
                                        <?php echo ucwords($customer_data->name)?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Buyer's Order NO
                                    </td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;">
                                        <?php echo $delivery_note->order_no.' ';    ?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Buyer's Order Date
                                    </td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;">
                                        <?php echo CommonHelper::changeDateFormat($delivery_note->order_date);?></td>
                                </tr>
                                <tr>
                                    <?php $SalesOrder = CommonHelper::get_single_row('sales_order','id',$delivery_note->master_id);?>
                                    <td class="text-left" style="width:60%;">Buyer's Unit</td>
                                    <td class="text-left" style="width:40%;"><?php echo $SalesOrder->buyers_unit;?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">Buyer's Address</td>
                                    <td style="border: solid 1px black;font-size: xx-small" class="text-left">
                                        <?php echo  ucwords($customer_data->address);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Destination</td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;">
                                        <?php echo $delivery_note->destination;?></td>
                                </tr>


                            </tbody>
                        </table>

                    </div>

                    <div style="width:50%; float:right;">
                        <table class="table " style="border: solid 1px black; border: solid 1px black;">
                            <tbody>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:50%;">DN NO.</td>
                                    <td class="text-left" style="border: solid 1px black;width:50%;">
                                        <?php echo strtoupper($delivery_note->gd_no);?></td>
                                </tr>

                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">DN Date</td>
                                    <td class="text-left">
                                        <?php echo CommonHelper::changeDateFormat($delivery_note->gd_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:50%;">SO NO.</td>
                                    <td class="text-left" style="border: solid 1px black;width:50%;">
                                        <?php echo strtoupper($delivery_note->so_no);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">SO Date</td>
                                    <td class="text-left" style="border: solid 1px black;">
                                        <?php echo CommonHelper::changeDateFormat($delivery_note->so_date);?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;">Other Reference(S)</td>
                                    <td class="text-left" style="border: solid 1px black;">
                                        <?php echo $delivery_note->other_refrence?></td>
                                </tr>
                                <tr>
                                    <td class="text-left" style="border: solid 1px black;width:60%;">Terms Of Delivery
                                    </td>
                                    <td class="text-left" style="border: solid 1px black;width:40%;">
                                        <?php echo $delivery_note->terms_of_delivery;?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div style="text-align: left" class="printHide">
                    <label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />Printable
                        Format </label>
                    <label class="text-left"><input type="checkbox" onclick="show_hide2()" id="formats2" />Bundle
                        Printable Format </label>
                </div>




                <div id="actual" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="tablee" class="table " style="border: solid 1px black;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="border:1px solid black;">S.NO</th>
                                    <th class="text-center" style="border:1px solid black;">Item</th>
                                    <th class="text-center" style="border:1px solid black;">Uom</th>
                                    <th class="text-center" style="border:1px solid black;">QTY. <span
                                            class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center hide" style="border:1px solid black;">Rate</th>
                                    <th class="text-center hide" style="border:1px solid black;">Amount</th>
                                    <th class="text-center hide" style="border:1px solid black;">Tax %</th>
                                    <th class="text-center hide" style="border:1px solid black;">Tax Amount</th>
                                    <th class="text-center hide" style="border:1px solid black;">Net Amount</th>
                                    <th class="text-center printHide" style="border:1px solid black;">View</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $count=1;
                                 $total_before_tax=0;
                                 $total_tax=0;
                                 $total_after_tax=0;
                                ?>
                                @foreach ( $delivery_note_data as $row )

                                <?php

                                $total_before_tax += $row->qty * $row->rate;
                                $total_tax += $row->tax_amount;
                                $total_after_tax += $row->amount;
                                ?>
                                <tr>
                                    <td style="border:1px solid black;"> {{ $count++ }} </td>
                                    <td style="border:1px solid black;">
                                        {{  CommonHelper::get_item_name($row->item_id) }}</td>
                                    <td style="border:1px solid black;">{{ CommonHelper::get_uom($row->item_id) }}</td>
                                    <td class="text-right" style="border:1px solid black;">{{ $row->qty }}</td>
                                    <td class="text-right hide" style="border:1px solid black;">
                                        {{ number_format($row->rate,2) }}</td>
                                    <td class="text-right hide" style="border:1px solid black;">
                                        {{ number_format($row->qty * $row->rate,2) }}</td>
                                    <td class="text-right hide" style="border:1px solid black;">{{ $row->tax }}</td>
                                    <td class="text-right hide" style="border:1px solid black;">
                                        {{ number_format($row->tax_amount,2) }}</td>
                                    <td class="text-right hide" style="border:1px solid black;">
                                        {{ number_format($row->amount,2) }}</td>
                                </tr>
                                @endforeach

                                <tr class="hide" style="font-size: large;font-weight: bold">
                                    <td colspan="4" style="border:1px solid black;"> Total </td>
                                    <td class="text-right" colspan="1" style="border:1px solid black;">
                                        {{ number_format($total_before_tax,2) }} </td>
                                    <td></td>
                                    <td class="text-right" colspan="1" style="border:1px solid black;">
                                        {{ number_format($total_tax,2) }} </td>
                                    <td class="text-right" colspan="1" style="border:1px solid black;">
                                        {{ number_format($total_after_tax,2) }} </td>
                                </tr>

                            </tbody>
                        </table>



                        <table style="display: none;" id="" class="table table-bordered tra">
                            <tr class="">
                                <th class="text-center" style="width:50px;">S.No</th>
                                <th class="text-center">Account</th>
                                <th class="text-center" style="width:150px;">Debit</th>
                                <th class="text-center" style="width:150px;">Credit</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                           $data=  DB::Connection('mysql2')->table('transactions')->where('status',1)->where('voucher_no',$delivery_note->gd_no)->orderBy('id','DESC')->get();
                            $total_debit=0;
                            $total_credit=0;
                            $counter=1;
                            foreach ($data as $row1):
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $counter++;?></td>
                                    <td><?php echo FinanceHelper::getAccountNameByAccId($row1->acc_id,Session::get('run_company'));?>
                                    </td>
                                    <td class="debit_amount text-right">@if($row1->debit_credit ==
                                        1){{number_format($row1->amount,2)}} @php $total_debit+=$row1->amount @endphp
                                        @endif </td>
                                    <td class="debit_amount text-right">@if($row1->debit_credit ==
                                        0){{number_format($row1->amount,2)}} @php $total_credit+=$row1->amount @endphp
                                        @endif </td>

                                </tr>
                                <?php endforeach;
                            ?>
                                <tr class="sf-table-total">
                                    <td colspan="2">
                                        <label for="field-1" class="sf-label"><b>Total</b></label>
                                    </td>
                                    <td class="text-right"><b><?php echo number_format($total_debit,2);?></b></td>
                                    <td class="text-right"><b><?php echo number_format($total_credit,2);?></b></td>
                                </tr>
                            </tbody>
                        </table>

                        <label class="check printHide">
                            Show Voucher
                            <input id="check" type="checkbox" onclick="checkk()" class="check">
                        </label>

                    </div>
                </div>



                <div style="line-height:8px;">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row text-left">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">

                            <textarea><?php echo 'Description:'.' '.strtoupper($delivery_note->description); ?></textarea>
                        </div>
                    </div>
                    <style>
                        .signature_bor {border-top: solid 1px #CCC;padding-top: 7px;}
                    </style>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Prepared By: </h6>
                                    <b>
                                        <p><?php echo strtoupper($delivery_note->username);?></p>
                                    </b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Checked By:</h6>
                                    <b>
                                        <p><?php  ?></p>
                                    </b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Approved By:</h6>
                                    <b>
                                        <p>
                                    </b>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
                <!--
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                        <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request Voucher Detail (Office Use)'))!!} ">
                    </div>
                    <!-->
            </div>
        </div>



    </div>

</div>

<script>
    $('#errorbutton').on('click', function () { // Replace 'yourButtonID' with the actual ID of your button
        Swal.fire({
            icon: 'error',
            title: 'Scanning Incomplete',
            text: `All QR codes must be scanned first.`,
            confirmButtonColor: '#3085d6'
        });
    });
function change()

{


    if (!$('.showw').is(':visible')) {
        $(".showw").css("display", "block");

    } else {
        $(".showw").css("display", "none");

    }

}

function show_hide() {
    if ($('#formats').is(":checked")) {
        $("#actual").css("display", "none");
        $("#printable").css("display", "block");
    } else {
        $("#actual").css("display", "block");
        $("#printable").css("display", "none");
    }
}

function show_hide2() {
    if ($('#formats2').is(":checked")) {
        $(".ShowHideHtml").fadeOut("slow");
        $(".bundleHide").fadeOut("slow");

        //                $("#printable").css("display", "block");
    } else {
        $(".ShowHideHtml").fadeIn("slow");
        $(".bundleHide").fadeIn("slow");

        //                $("#printable").css("display", "none");
    }
}


function remove_bundle(id) {
    //Q$('#'+id).css('display','none');
}

function diss(id) {
    $('#' + id).remove();
}

function checkk() {

    if ($("#check").is(":checked")) {


        $('.tra').css('display', 'block');
    } else {
        $('.tra').css('display', 'none');
    }
}
</script>


<!-- printView sale order -->
<script>
    function printView(divId) {
        var element = document.getElementById(divId);
        if (!element) {
            alert("Element with ID '" + divId + "' not found!");
            return;
        }

        var content = element.innerHTML;
        var mywindow = window.open('', 'PRINT', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>Print</title>');

        // ✅ Bootstrap CSS include
        mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">');


        mywindow.document.write(`
            <style>
                @page{size:A4;margin:1em;}
                .table-responsive .sale_older_tab > caption + thead > tr:first-child > th,.sale_older_tab > colgroup + thead > tr:first-child > th,.sale_older_tab > thead:first-child > tr:first-child > th,.sale_older_tab > caption + thead > tr:first-child > td,.sale_older_tab > colgroup + thead > tr:first-child > td,.sale_older_tab > thead:first-child > tr:first-child > td{border-top:0;font-size:10px !important;padding:9px 5px !important;}
                .table-responsive .sale_older_tab > thead > tr > th,.sale_older_tab > tbody > tr > th,.sale_older_tab > tfoot > tr > th,.sale_older_tab > thead > tr > td,.sale_older_tab > tbody > tr > td,.table > tfoot > tr > td{padding:2px 5px !important;font-size:11px !important;border-top:1px solid #000000 !important;border-bottom:1px solid #000000 !important;border-left:1px solid #000000 !important;border-right:1px solid #000000 !important;}
                .table-responsive{height:inherit !important;}
                .sales_or{position:relative !important;height:100% !important;}
                .sgnature{position:absolute !important;bottom:0px !important;}
                p{margin:0;padding:0;font-size:13px !important;font-weight:500;}
                .mt-top{margin-top:-72px !important;}
                .sale-list.userlittab > thead > tr > td,.sale-list.userlittab > tbody > tr > td,.sale-list.userlittab > tfoot > tr > td{font-size:12px !important;text-align:left !important;}
                .sale-list.table-bordered > thead > tr > th,.sale-list.table-bordered > tbody > tr > th,.sale-list.table-bordered > tfoot > tr > th{font-size:12px !important;margin:0 !important;vertical-align:inherit !important;padding:0px 17px !important;text-align:left !important;}
                input.form-control.form-control2{margin:0 !important;}
                .totlas{display:flex !important;justify-content:right !important;gap:70px !important;background:#ddd !important;width:30% !important;float:right !important;padding-right:8px !important;}
                .totlas p{font-weight:bold !important;}
                .psds{display:flex !important;justify-content:right !important;gap:88px !important;}
                .psds p{font-weight:bold !important;}
                .totlass{display:inline!important;background:transparent!important;margin-top:-25px!important;}
                .totlass h2{font-size:13px !important;}
                .col-lg-6{width:50% !important;}
                .col-lg-12{width:100% !important;}
                .contr h2{font-size:17px !important; font-weight:bold !important;color:#000 !important;}
                .contr2 h2{font-size:17px !important; font-weight:bold !important;color:#000 !important;}
                .col-lg-4{width:33.33333333% !important;}
            </style>
        `);
        mywindow.document.write('</head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
    }
</script>