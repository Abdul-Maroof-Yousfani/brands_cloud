<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;

$id = $_GET['id'];








$m = Session::get('run_company');
$approve=ReuseableCode::check_rights(16);

$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$purchaseRequestDetail = DB::table('purchase_request')->where('purchase_request_no','=',$id)->get();
$purchaseRequestDataDetail = DB::table("purchase_request_data")->where("master_id", $purchaseRequestDetail[0]->id)->get();
// dd($id);


// dd($id);
CommonHelper::reconnectMasterDatabase();

if($_GET['pageType']=='viewlist'){
    $EmailPrintSetting = $_GET['EmailPrintSetting'];
} else {
    $EmailPrintSetting = $_GET['EmailPrintSetting'];
}
?>

<div id="Pdfsetting" <?php if($EmailPrintSetting==2){ ?> style="display: none;" <?php } ?> >
    <!-- <button onclick="change()" type="button" class="btn btn-primary btn-xs">Show PKR</button> -->

    <style>
        textarea {
            border-style: none;
            border-color: Transparent;

        }
    </style>
    <div style="line-height:5px;">&nbsp;</div>
</div>

<?php
    foreach ($purchaseRequestDetail as $row) {
?>
<div class="row" >
    <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 printHide">
        <input type="text" name="email" id="email" value="" class="form-control" placeholder="Enter Email Address">
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 printHide">
        <button class="btn btn-primary btn-sm" onclick="EmailSent()"> Email Sent </button>
    </div> -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
         <?php
        if ($approve==true):
        echo StoreHelper::displayApproveDeleteRepostButtonPurchaseRequest($m,$row->purchase_request_status,$row->status,$row->id,'purchase_request_no','purchase_request_status','status','purchase_request','purchase_request_data');
        endif;
        ?> 
        <?php CommonHelper::displayPrintButtonInView('po_detail','LinkHide','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="po_detail">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                        echo ' '.'('.date('D', strtotime($x)).')';?></label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3 style="text-align: center;"><h3 style="text-align: center;">Purchase Order</h3></h3>
                </div>
            </div>


            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:40%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style=" width:253px !important;">PO NO.</td>
                                <td><?php echo strtoupper($row->purchase_request_no);?></td>
                            </tr>


                            <tr>
                                <td>PO Date</td>
                                <td><?php echo CommonHelper::changeDateFormat($row->purchase_request_date);?></td>
                            </tr>

                            <tr>
                                <td>PO Type</td>
                                <td><?php echo CommonHelper::get_po_type($row->po_type);?></td>
                            </tr>
                            <tr>
                                <td>Supplier Name</td>
                                <td><?php echo CommonHelper::get_supplier_name($row->supplier_id);?></td>
                            </tr>

                            <tr>
                                <td>STRN</td>
                                <td><?php echo $row->trn;?></td>
                            </tr>

                            <tr>
                                <td>Builty No</td>
                                <td><?php echo $row->builty_no;?></td>
                            </tr>
                            <!-- <tr>
                                <td  class="showw" style="width:60%;">Agent </td>
                                <td class="showw" style="width:40%;">{{CommonHelper::get_sub_dept_name($row->agent) ?? ''}}</td>
                            </tr> -->
                            </tbody>
                        </table>

                    </div>
                    <div style="width:40%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:253px !important;">Supplier Reference No.</td>
                                <td><?php echo $row->trn;?></td>
                            </tr>
                            <tr class="">
                                <td>Department / Sub Department</td>
                                <td><?php echo CommonHelper::getMasterTableValueById($m,'department','department_name',$row->sub_department_id);?></td>
                            </tr>
                            <tr>
                                <td>Terms Of Delivery</td>
                                <td><?php echo $row->term_of_del;?></td>
                            </tr>
                            <tr>
                                <td>Terms Of Payment</td>
                                <td><?php echo $row->terms_of_paym;?></td>
                            </tr>

                            <tr>
                                <td>Destination</td>
                                <td><?php echo $row->destination;?></td>
                            </tr>
                            <?php   $currency= CommonHelper::get_curreny_name($row->currency_id);?>



                            <tr>
                                <td  class="showw" style="width:60%;">Currency  </td>
                                <td class="showw" style="width:40%;">{{$currency}}</td>
                            </tr>
                            <tr>
                                <td  class="showw" style="width:60%;">Currency Rate </td>
                                <td class="showw" style="width:40%;">{{$row->currency_rate}}</td>
                            </tr>



                            <!-- <tr>
                                <td  class="showw" style="width:60%;">Commission </td>
                                <td class="showw" style="width:40%;">{{$row->commission}}</td>
                            </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <tr>
                                <th class="text-center">S.NO</th>
                                {{--<th class="text-center">PR NO</th>--}}
                                {{--<th style="font-size: 13px;" class="text-center">PR  Date </th>--}}
                                <th>SKU</th>
                                <th class="text-center">Product Name</th>
                                <th>Barcode</th>
                                <th class="text-center">UOM</th>

                                

                                <th class="text-center" >Qty</th>
                                <!-- <th class="text-center" >No Of Carton </th> -->
                                <th class="text-center" >Rate </th>
                                <th class="text-center">Amount(PKR)</th>
                                <th class="text-center">Tax%</th>
                                <th class="text-center">Tax Amount</th>
                                <!-- <th class="text-center">Tax</th> -->
                                <th class="text-center">Discount</th>

                                {{-- <th class="text-center">Discount%</th>
                                <th class="text-center">Discount Amount</th> --}}
                                <th class="text-center">Net Amount</th>
                                <th   class="text-center showw" style="display: none">Amount In PKR</th>
                                <!-- <th class="text-center printHide">View</th> -->

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            // $purchaseRequestDataDetail = DB::table('purchase_request_data')->where('master_id','=',$id)->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                                    $total=0;
                            $total_exchange=0;
                            $actual_amount =0;
                            $approved_qty_sum = 0;
                            $total_tax_amount = 0;
                            foreach ($purchaseRequestDataDetail as $row1){



  // Calculate tax amount
    $gross_amount = $row1->rate * $row1->purchase_approve_qty;
    $amount_after_discount = $gross_amount - $row1->discount_amount;
    $item_tax_amount = ($amount_after_discount / 100) * $row1->tax_rate;
    $net_amount = $amount_after_discount + $item_tax_amount;
    $net_amount_with_currency = $net_amount * $row->currency_rate;
    
    // Add to total tax amount
    $total_tax_amount += $item_tax_amount;

                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                {{--<td class="text-center">< ?php echo strtoupper($row1->demand_no);?></td>--}}
                                {{--<td class="text-center">< ?php echo  CommonHelper::changeDateFormat($row1->demand_date);?></td>--}}

                                <td><?php echo CommonHelper::get_product_sku($row1->sub_item_id); ?></td>
                                <td title="item_name={{CommonHelper::get_product_name($row1->sub_item_id)}}">
                                    <?php $accType = Auth::user()->acc_type;
                                    if($accType == 'client'):
                                    ?>
                                    <a class="LinkHide" href="<?php echo url('/') ?>/store/stockReportView?item_id=<?php echo $row1->sub_item_id?>&&pageType=&&parentCode=126&&m=1" target="_blank">
                                        <?php echo CommonHelper::get_product_name($row1->sub_item_id);//echo CommonHelper::get_item_name($row1->sub_item_id);?>
                                    </a>
                                    <?php else:?>
                                    <?php echo CommonHelper::get_product_name($row1->sub_item_id);?>
                                    <?php endif;?>
                                </td>
                                <td><?php echo CommonHelper::product_barcode($row1->sub_item_id); ?></td>


                                <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item_id);
                                $sub_ic_detail= explode(',',$sub_ic_detail)
                                ?>

                                <td> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?>
                                    <input type="hidden" value="<?php echo $row1->sub_item_id?>" id="sub_<?php echo $counter?>">
                                </td>
                                @php
                                    $approved_qty_sum += (int)$row1->purchase_approve_qty;

                                    
                                @endphp

                             <?php 
// 1. Gross Amount
$gross_amount = $row1->rate * $row1->purchase_approve_qty;

// 2. Amount after discount
$amount_after_discount = $gross_amount - $row1->discount_amount;

// 3. Tax amount on discounted amount
$item_tax_amount = ($amount_after_discount / 100) * $row1->tax_rate;

// 4. NET AMOUNT = Amount after discount + Tax
$net_amount = $amount_after_discount + $item_tax_amount;

// 5. Apply currency rate (if needed)
$net_amount_with_currency = $net_amount * $row->currency_rate;
?>

                                <td class="text-center"><?php echo (int)$row1->purchase_approve_qty;?></td>
                                <!-- <td class="text-center"><?php echo $row1->no_of_carton;?></td> -->
                                <td class="text-center"><?php echo number_format($row1->rate,2);?></td>
                                <td class="text-right"><?php echo number_format($row1->rate * $row1->purchase_approve_qty * $row->currency_rate,2);?></td>
                                <!-- <td class="text-right"><?php echo number_format($row1->rate * $row1->purchase_approve_qty,2);?></td> -->
                                <td class="text-right"><?php echo number_format($row1->tax_rate,2);?></td>
                                <td class="text-right"><?php echo number_format($item_tax_amount,2);?></td> 
                             
                                <td class="text-right"><?php echo number_format($row1->discount_amount,2);?></td>
                                   <td class="text-right"><?php echo number_format($row1->net_amount,2);?></td>
                                <td style="display: none"  class="text-right showw"><?php echo number_format($row1->net_amount*$row->currency_rate,2);?></td>
                                <!-- <td style="background-color: #ccc" class="printHide">
                                    <input onclick="view_history(<?php echo $counter?>)" type="checkbox" id="view_history<?php echo $counter?>">
                                </td> -->
                            </tr>
                            <?php
                                    $actual_amount +=  $row1->rate * $row1->purchase_approve_qty;
                                    $total+=$row1->net_amount;
                            $total_exchange+=$row1->sub_total*$row->currency_rate;
                            }
                            ?>

                            <tr>

                                <td style="background-color: darkgray" class="text-center" colspan="5">Total</td>
                                <td style="background-color: darkgray" class="text-center">{{ $approved_qty_sum }}</td>
                                <td style="background-color: darkgray" class="text-center"></td>
                                <td style="background-color: darkgray" class="text-right"  >{{number_format($actual_amount,2)}} ({{$currency}})</td>
                                 
                                
                                <td style="background-color: darkgray" class="text-center"></td>
                                <td style="background-color: darkgray" class="text-right"  ></td>
                                <td  style="background-color: darkgray" class="text-right"  colspan="4">{{number_format($total,2)}}</td>
                                <td  style="background-color: darkgray;display: none" class="text-right showw"  colspan="1">{{number_format($total_exchange,2)}}</td>
                            </tr>
                            </tbody>


                            <tr>
                                <td class="text-center" colspan="10">{{ 'Sales Tax :'. $row->sales_tax.' %' }}</td>
                                <td class="text-right" colspan="9">{{   number_format($row->sales_tax_amount,2)}}</td>
                            </tr>

                            <tr>

                                <td style="background-color: darkgray" class="text-center" colspan="10">Grand Total</td>
                                <td style="background-color: darkgray"  class="text-right" colspan="5">{{number_format($total+$row->sales_tax_amount,2)}}
                                    @php if ($currency==''):echo 'PKR';else:echo $currency;endif; @endphp</td>
                                <td style="background-color: darkgray;display: none"  class="text-right showw" colspan="6">{{number_format($total_exchange+$row->sales_tax_amount,2)}}

                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <table>
                    <tr><td style="text-transform: capitalize;">Amount In Words : <?php echo $row->amount_in_words .'('?>  <?php     if ($currency==''):echo 'PKR';else:echo 'PKR';endif;  ?> )</td></tr>
                </table>
                <div style="line-height:8px;">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">

                           <textarea style="font-size: 11px;resize: none" cols="100" rows="10"><?php echo 'Description:'.' '.strtoupper($row->description); ?></textarea>
                        </div>
                    </div>
                    <style>
                        .signature_bor {
                            border-top:solid 1px #CCC;
                            padding-top:7px;
                        }
                    </style>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Prepared By: </h6>
                                    <b>   <p><?php echo strtoupper($row->username);  ?></p></b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Checked By:</h6>
                                    <b>   <p><?php  ?></p></b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Approved By:</h6>
                                    <b>  <p><?php echo $row->approve_username?></p></b>
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
    <?php
        }
    ?>
</div>

    <script>

        function view_history(id) {

            var v = $('#sub_' + id).val();


            if ($('#view_history' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelTwoParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
                }
                else {
                    alert('Select Item');
                }

            }
        }

    function change()
    {
        if(!$('.showw').is(':visible'))
        {
            $(".showw").fadeIn();

        }
        else
        {
            $(".showw").fadeOut();
        }
    }

    function EmailSent()
    {
        if (confirm('Are you sure you want to Sent Email to this request'))
        {
            pageType="pageType1";
            EmailPrintSetting = "2";
            id = "<?php echo $id; ?>";
            m = "<?php echo $m; ?>";
            email = $("#email").val();
            $.ajax({
                url: '<?php echo url('/') ?>/stad/Email_Sent',
                type: 'get',
                data: {email:email, id:id, m:m,pageType:pageType, EmailPrintSetting:EmailPrintSetting},
                success: function (response)
                {
                    alert(response);
                }
            });
        } else
        {
            alert("Email Not Sended");
        }
    }




</script>

