<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$view=true;
?>

@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Stock Out List</span>
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" id="FromDate" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" id="ToDate" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="Filter" class="btn btn-sm btn-primary" onclick="GetFilteredDate();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Stock Out No.</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">From Warehouse</th>
                                        <th class="text-center">To Warehouse</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="ShowHide">
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $MasterData = DB::table('stock_out as so')
                                        ->leftJoin('stock_out_data as sod', 'so.id', '=', 'sod.master_id')
                                        ->select('so.*', 'sod.warehouse_from', 'sod.warehouse_to', DB::raw('SUM(sod.si_status) as received_count'), DB::raw('COUNT(sod.id) as total_items'))
                                        ->groupBy('so.id')
                                        ->orderBy('so.id', 'desc')
                                        ->get();
                                    CommonHelper::reconnectMasterDatabase();

                                    $Counter = 1;
                                    foreach($MasterData as $row):
                                        $status_label = ($row->received_count >= $row->total_items) ? '<span class="label label-success">Received</span>' : '<span class="label label-warning">In Transit</span>';
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $Counter++;?></td>
                                        <td><?php echo strtoupper($row->so_no);?></td>
                                        <td><?php echo CommonHelper::changeDateFormat($row->so_date);?></td>
                                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$row->warehouse_from);?></td>
                                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$row->warehouse_to);?></td>
                                        <td><?php echo strtoupper($row->description);?></td>
                                        <td><?php echo $status_label; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-xs" onclick="showDetailModelOneParamerter('stdc/viewStockOutDetail?m=<?php echo $m; ?>', '<?php echo $row->so_no; ?>', 'View Stock Out Detail')">View</button>
                                        </td>
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
    </div>
    </div>
@endsection
