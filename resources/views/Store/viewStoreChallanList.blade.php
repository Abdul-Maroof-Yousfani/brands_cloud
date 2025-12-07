<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Store.'.$accType.'storeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Store Challan Voucher List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <input type="hidden" name="functionName" id="functionName" value="stdc/filterStoreChallanVoucherList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="tbodyId" id="tbodyId" value="filterStoreChallanVoucherList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly" class="form-control" />
                            <input type="hidden" name="filterType" id="filterType" value="1" readonly="readonly" class="form-control" />
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Sub Department</label>
                                    <input type="hidden" readonly name="selectSubDepartmentId" id="selectSubDepartmentId" class="form-control" value="">
                                    <input list="selectSubDepartment" name="selectSubDepartment" id="selectSubDepartmentTwo" class="form-control">
                                    <?php echo CommonHelper::subDepartmnetSelectList($m);?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Select Voucher Status</label>
                                    <select name="selectVoucherStatus" id="selectVoucherStatus" class="form-control">
                                        <?php echo CommonHelper::voucherStatusSelectList();?>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('printStoreChallanVoucherList','margin-top: 32px','1');?>
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printStoreChallanVoucherList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered sf-table-list">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">S.C. No.</th>
                                                            <th class="text-center">S.C. Date</th>
                                                            <th class="text-center">Slip No.</th>
                                                            <th class="text-center">Sub Department</th>
                                                            <th class="text-center">S.C Status</th>
                                                            <th class="text-center hidden-print">Action</th>
                                                            </thead>
                                                            <tbody id="filterStoreChallanVoucherList"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Store Challan Voucher List'))!!} ">
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('assets/custom/js/customStoreFunction.js') }}"></script>
@endsection