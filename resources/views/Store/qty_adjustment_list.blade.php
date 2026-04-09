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
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Quantity Adjustment List</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <a href="{{ url('store/qty_adjustment_form?m=' . $m) }}" class="btn btn-primary btn-sm">Create New Adjustment</a>
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
                                        <tr class="bg-primary">
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Adj No.</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Warehouse</th>
                                            <th class="text-center">Remarks</th>
                                            <th class="text-center">Username</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ShowHide">
                                    <?php
                                    CommonHelper::companyDatabaseConnection($m);
                                    $results = DB::table('qty_adjustment as qa')
                                        ->leftJoin('warehouse as w', 'qa.warehouse_id', '=', 'w.id')
                                        ->select('qa.*', 'w.name as warehouse_name')
                                        ->where('qa.status', 1)
                                        ->orderBy('qa.id', 'desc')
                                        ->get();
                                    CommonHelper::reconnectMasterDatabase();

                                    $Counter = 1;
                                    foreach($results as $row):
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $Counter++;?></td>
                                        <td><?php echo strtoupper($row->adj_no);?></td>
                                        <td><?php echo CommonHelper::changeDateFormat($row->adj_date);?></td>
                                        <td><?php echo $row->warehouse_name; ?></td>
                                        <td><?php echo strtoupper($row->description);?></td>
                                        <td><?php echo $row->username; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-xs" onclick="showDetailModelOneParamerter('stdc/viewQtyAdjustmentDetail?m=<?php echo $m; ?>', '<?php echo $row->id; ?>', 'View Adjustment Detail')">View</button>
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

    <script>
        function GetFilteredDate() {
            var fromDate = $('#FromDate').val();
            var toDate = $('#ToDate').val();
            var m = '<?php echo $m; ?>';

            $.ajax({
                url: '<?php echo url('stdc/filterQtyAdjustmentList'); ?>',
                type: 'GET',
                data: {
                    fromDate: fromDate,
                    toDate: toDate,
                    m: m
                },
                success: function(response) {
                    $('#ShowHide').html(response);
                }
            });
        }
    </script>
@endsection
