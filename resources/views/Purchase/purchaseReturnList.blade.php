<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;



$view=ReuseableCode::check_rights(31);
$edit=ReuseableCode::check_rights(32);
$delete=ReuseableCode::check_rights(33);
$export=ReuseableCode::check_rights(235);


$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');



$first_day_this_month = date('Y-m-01');
$last_day_this_month  = date('Y-m-t');

?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printIssuanceVoucherList','','1');?>
                            <?php if($export == true):?>
                        <?php echo CommonHelper::displayExportButton('issuanceVoucherList','','1')?>
                        <?php endif;?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Purchase Return List</span>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from"  value="<?php echo $first_day_this_month;?>" class="form-control" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $last_day_this_month;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                                    <label>Voucher Type</label>
                                    <select name="VoucherType" id="VoucherType" class="form-control">
                                        <option value="all">All</option>
                                        <option value="1">GRN</option>
                                        <option value="2">Purchase Invoice</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Supplier</label>
                                    <select name="SupplierId" id="SupplierId" class="form-control select2">
                                        <option value="all">ALL</option>
                                        <?php foreach($Supplier as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>


                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            
                            <div id="printDemandVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data">
                                                        <div class="table-responsive" >
                                                            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Purchase Return No.</th>
                                                                <th class="text-center">Purchase Return Date</th>
                                                                <th class="text-center">Supplier Name</th>
                                                                <th class="text-center">Grn No</th>
                                                                <th class="text-center hide">Grn Date</th>
                                                                <th class="text-center">Remarks</th>
                                                                <th class="text-center hide">Return Amount</th>
                                                                <th class="text-center hide">Net Stock</th>
                                                                <th class="text-center">Type</th>
                                                                <th class="text-center hidden-print">Action</th>
                                                                </thead>

                                                                <tbody id="ShowHide">

                                                                <?php
                                                                CommonHelper::companyDatabaseConnection($m);
                                                                $MasterData = DB::table('purchase_return')->where('status', '=', 1)->
                                                                whereBetween('pr_date',[$first_day_this_month,$last_day_this_month])->
                                                                orderBy('id', 'desc')->get();
                                                                CommonHelper::reconnectMasterDatabase();

                                                                $Counter = 1;
                                                                $paramOne = "pdc/viewPurchaseReturnDetail?m=".$m;
                                                                $paramThree = "View Issuance Detail";
                                                                $total_return=0;
                                                                $total_net_stock=0;
                                                                foreach($MasterData as $Fil):
                                                                $edit_url= url('/purchase/editPurchaseReturnForm/'.$Fil->id.'/'.$Fil->pr_no.'?m='.$m);
                                                                $net_stock = DB::Connection('mysql2')->table('stock')->where('voucher_no',$Fil->pr_no)->select('amount')->sum('amount');
                                                                $total_net_stock+=$net_stock;
                                                                $return_amount=  ReuseableCode::return_amount($Fil->grn_id,$Fil->type);


                                                            $po_no=     DB::Connection('mysql2')->table('goods_receipt_note')->where('grn_no',$Fil->grn_no)->value('po_no');
                                                                ?>
                                                                <tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
                                                                    <td><?php echo $Counter++;?></td>
                                                                    <td><?php echo strtoupper($Fil->pr_no);?></td>
                                                                    <td><?php echo CommonHelper::changeDateFormat($Fil->pr_date);?></td>
                                                                    <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$Fil->supplier_id);?></td>
                                                                    <td><?php echo strtoupper($Fil->grn_no.'</br>'.$po_no);?></td>
                                                                    <td class="hide"><?php echo CommonHelper::changeDateFormat($Fil->grn_date);?></td>
                                                                    <td><?php echo $Fil->remarks;?></td>
                                                                    <td class="text-right hide">{{number_format($return_amount,2)}}</td>
                                                                    <td class="text-right hide">{{number_format($net_stock,2)}}</td>
                                                                    <td><?php if ($Fil->type==1): echo 'GRN'; elseif($Fil->type==2): echo 'Purchase Invoice';   endif;?></td>
                                                                    <td style="width: 170px">
                                                                        @if($view==true)
                                                                            <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $Fil->pr_no;?>','View Purchase Return Detail')"   type="button" class="btn btn-success btn-xs">View</button>
                                                                        @endif
                                                                        @if($edit==true)
                                                                            <a href='<?php echo $edit_url;?>' type="button" class="btn btn-primary btn-xs">Edit</a>
                                                                        @endif
                                                                        @if($delete==true)
                                                                            <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $Fil->id?>" onclick="DeletePurchaseReturn('<?php echo $Fil->id?>','<?php echo $Fil->pr_no?>')">Delete</button>
                                                                        @endif
                                                                    </td>
                                                                    <?php $total_return+=$return_amount; ?>
                                                                </tr>

                                                                <?php endforeach;?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Demand Voucher List'))!!} ">
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

    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
    <script !src="">
        //issuanceDataFilter();
        $(document).ready(function(){
            $('.select2').select2();
        });

        function viewRangeWiseDataFilter()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            var SupplierId= $('#SupplierId').val();
            var VoucherType= $('#VoucherType').val();

            var m  = '<?php echo $m?>';
            $('#ShowHide').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/get_data_debit_note_ajax',
                type: 'Get',
                data: {from: from,to:to,m:m,SupplierId:SupplierId,VoucherType:VoucherType},

                success: function (response) {

                    $('#ShowHide').html(response);


                }
            });


        }
        function DeletePurchaseReturn(Id,PrNo)
        {
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';

                //$('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
                $.ajax({
                    url: '/pdc/deletePurchaseReturn',
                    type: 'Get',
                    data: {Id: Id,PrNo:PrNo,m:m},

                    success: function (response)
                    {
                        $('#RemoveTr'+response).remove();
                    }
                });
            }
            else {}
        }

        function ApprovedGoodIssuance(IssuanceId){
            var m = '<?php echo $_GET['m'];?>';
            $('#BtnApprove'+IssuanceId).prop('disabled',true);

            $.ajax({
                url: '<?php echo url('/')?>/pdc/ApprovedGoodIssuance',
                type: "GET",
                data: { IssuanceId:IssuanceId,m:m},
                success:function(data) {
                    $('#BtnApprove'+IssuanceId).css('display','none');
                    $('#BtnDelete'+IssuanceId).css('display','none');
                    $('#BtnEdit'+IssuanceId).css('display','none');

                }
            });
        }

        function Recieved(IssuanceId,m){
            var m = '<?php echo $_GET['m'];?>';
            $('#recieved'+IssuanceId).prop('disabled',true);

            $.ajax({
                url: '<?php echo url('/')?>/pdc/Recieved',
                type: "GET",
                data: { IssuanceId:IssuanceId,m:m},
                success:function(data) {
                    $('#recieved'+IssuanceId).css('display','none');
                    $('#BtnDelete'+IssuanceId).css('display','none');
                    $('#BtnEdit'+IssuanceId).css('display','none');

                }
            });
        }

        function issuanceDataFilter()
        {

            var FromDate= $('#FromDate').val();
            var ToDate= $('#ToDate').val();
            var IssuanceType = $('#IssuanceType').val();

            var m = '<?php echo $m?>';
//            if(ClientId !="" || RegionId !="")
//            {
            $('#ShowHide').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/issuanceDataFilter',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,IssuanceType:IssuanceType,m:m},

                success: function (response) {

                    $('#ShowHide').html(response);
                }
            });
//            }
//            else{
//                $('#FilterError').html('<p class="text-danger">Please Select Client OR Region...!</p>');
//            }



        }
    </script>
@endsection