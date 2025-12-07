<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$id = $_GET['id'];
$m = $_GET['m'];
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$storeChallanDetail = DB::table('store_challan')->where('store_challan_no','=',$id)->get();
CommonHelper::reconnectMasterDatabase();
foreach ($storeChallanDetail as $row) {
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printStoreChallanVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printStoreChallanVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo StoreHelper::displayApproveDeleteRepostButtonTwoTable($m,$row->store_challan_status,$row->status,$row->store_challan_no,'store_challan_no','store_challan_status','status','store_challan','store_challan_data');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php echo CommonHelper::getCompanyName($m);?>
                        </div>
                        <br />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                             style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                            <?php StoreHelper::checkVoucherStatus($row->store_challan_status,$row->status);?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                    <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:30%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">Store Challan No.</td>
                                <td style="width:60%;"><?php echo $row->store_challan_no;?></td>
                            </tr>
                            <tr>
                                <td>Store Challan Date</td>
                                <td><?php echo CommonHelper::changeDateFormat($row->store_challan_date);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="width:30%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:60%;">Slip No.</td>
                                <td style="width:40%;"><?php echo $row->slip_no;?></td>
                            </tr>
                            <tr>
                                <td>Department / Sub Department</td>
                                <td><?php echo CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:50px;">S.No</th>
                                <th class="text-center">Demand No</th>
                                <th class="text-center">Demand Date</th>
                                <th class="text-center">Category Name</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center" style="width:150px;">Issue Qty.</th>
                                <th class="text-center" style="width:150px;">Return Qty.</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            CommonHelper::companyDatabaseConnection($m);
                            $storeChallanDataDetail = DB::table('store_challan_data')->where('store_challan_no','=',$id)->get();
                            CommonHelper::reconnectMasterDatabase();
                            $counter = 1;
                            foreach ($storeChallanDataDetail as $row1){
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo $row1->demand_no;?></td>
                                <td class="text-center"><?php echo CommonHelper::changeDateFormat($row1->demand_date);?></td>
                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'category','main_ic',$row1->category_id);?></td>
                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row1->sub_item_id);?></td>
                                <td class="text-center"><?php echo $row1->issue_qty;?></td>
                                <td class="text-center"><?php echo StoreHelper::getReturnQtyByStoreChallanNo($m,$row1->category_id,$row1->sub_item_id,$id,'store_challan_no');?></td>
                            </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="line-height:8px;">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th colspan="5"><?php echo $row->description;?></th>
                                    </tr>
                                    <tr>
                                        <th style="width:15%;">Printed On</th>
                                        <th style="width:15%;"><?php echo Auth::user()->name; ?></th>
                                        <th style="width:15%;">Created By</th>
                                        <th style="width:15%;"><?php echo $row->username;?></th>
                                        <th style="width:20%;">Approved By</th>
                                        <th style="width:20%;"><?php echo $row->approve_username;?></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Store Challan Voucher Detail'))!!} ">
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>