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

$view=true; // Simplified for now
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
                                    <span class="subHeadingLabelClass">Stock In List</span>
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
                                        <th class="text-center">Stock In No.</th>
                                        <th class="text-center">Stock Out No.</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Warehouse (To)</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody id="ShowHide">
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $MasterData = DB::table('stock_in as si')
                                        ->leftJoin('stock_in_data as sid', 'si.id', '=', 'sid.master_id')
                                        ->select('si.*', 'sid.warehouse_to', DB::raw('GROUP_CONCAT(DISTINCT sid.stock_out_no SEPARATOR ", ") as stock_out_nos'))
                                        ->groupBy('si.id')
                                        ->orderBy('si.id', 'desc')
                                        ->get();
                                    CommonHelper::reconnectMasterDatabase();

                                    $Counter = 1;
                                    foreach($MasterData as $row):
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $Counter++;?></td>
                                        <td><?php echo strtoupper($row->si_no);?></td>
                                        <td><?php echo strtoupper($row->stock_out_nos);?></td>
                                        <td><?php echo CommonHelper::changeDateFormat($row->si_date);?></td>
                                        <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'warehouse','name',$row->warehouse_to);?></td>
                                        <td><?php echo strtoupper($row->description);?></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-xs" onclick="showDetailModelOneParamerter('stdc/viewStockInDetail?m=<?php echo $m; ?>', '<?php echo $row->si_no; ?>', 'View Stock In Detail')">View</button>
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
