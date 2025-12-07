<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$approved=ReuseableCode::check_rights(156);
$m = $_GET['m'];

$currentDate = date('Y-m-d');

?>
<div class="row headquid">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
       
        <?php // FinanceHelper::displayApproveDeleteRepostButton($m,$row->jv_status,$row->status,$row->jv_no,'jv_no','jv_status','status');?>
            <?php echo CommonHelper::displayPrintButtonInView('printCashPaymentVoucherDetail','','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="printCashPaymentVoucherDetail" class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                            <h3 style="text-align: center;">Advance Payment Voucher</h3>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:30%; float:left;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                    <tr>
                                        <td style="width:40%;">Payment No.</td>
                                        <td style="width:60%;"><?php echo strtoupper($detail->payment_no);?></td>
                                    </tr>
                                    {{--<tr>--}}
                                        {{--<td style="width:40%;">Ref / Bill No.</td>--}}
                                        {{--<td style="width:60%;">< ?php echo $row->slip_no;?></td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <td>JV Date</td>
                                        <td><?php echo FinanceHelper::changeDateFormat($detail->created_at);?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{--<div style="width:30%; float:right;">--}}
                                {{--<table  class="table table-bordered table-striped table-condensed tableMargin">--}}
                                    {{--<tbody>--}}
                                    {{--<tr>--}}
                                        {{--<td style="width:40%;">Bill Date</td>--}}
                                        {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($row->bill_date);?></td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td style="width:40%;">Due Date</td>--}}
                                        {{--<td style="width:60%;">< ?php  echo FinanceHelper::changeDateFormat($row->due_date);?></td>--}}
                                    {{--</tr>--}}

                                    {{--</tbody>--}}
                                {{--</table>--}}
                            {{--</div>--}}
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width:50px;">S.No</th>
                                        <th class="text-center">Payment No</th>
                                        <th class="text-center">Customer Name</th>
                                        <th class="text-center">Amount </th>
                                        <th class="text-center">Amount Recieved No</th>
                                        <th class="text-center">Amount Issued No</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Issued Status</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">01</td>
                                            <td class="text-center">{{$detail->payment_no}}</td>
                                            <td class="text-center">{{CommonHelper::get_customer_name($detail->customer_id)}}</td>
                                            <td class="text-center">{{$detail->amount ?? '--'}}</td>
                                            <td class="text-center">{{$detail->amount_recieved_no ?? '--'}}</td>
                                            <td class="text-center">{{$detail->amount_issued_no ?? '--'}}</td>
                                            <td class="text-center">{{$detail->description ?? '--'}}</td>
                                            <td class="text-center"> {!! $detail->amount_issued_status == 1 ? "<span style='color:green'>Issued</span>" : "<span style='color:red'>Not Issued</span>" !!}</td>        
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h4>Description: </h4>
                                            <p style="    border: 1px solid black;
    padding: 13px 9px;"><?php echo $detail->description;  ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        


                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                            <h6 class="signature_bor">Prepared By: </h6>
                                            <b>   <p><?php echo strtoupper($detail->user_name);  ?></p></b>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                            <h6 class="signature_bor">Verified By:</h6>
                                            <b>   <p>{{ strtoupper($detail->user_name) }}</p></b>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                            <h6 class="signature_bor">Approved By:</h6>
                                            <b><p><?php echo strtoupper($detail->user_name)?></p></b>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($_GET['type'] == 'print'):?>
<script !src="">
    $(document).ready(function(){
//        printView('printCashPaymentVoucherDetail','','1');
//        function printView(param1,param2,param3) {


            $( ".qrCodeDiv" ).removeClass( "hidden" );
//            if(param2!="")
//            {
//                $('.'+param2).prop('href','');
//            }
            $('.printHide').css('display','none');
            var printContents = document.getElementById('printCashPaymentVoucherDetail').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            //if(param3 == 0){
            //location.reload();

            //}
//        }
    });
</script>
<?php endif;?>
