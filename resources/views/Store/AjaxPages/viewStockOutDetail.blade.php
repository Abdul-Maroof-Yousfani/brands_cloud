<?php
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$id = $_GET['id'] ?? $_GET['pOne'] ?? 0; // Standardized parameter check
CommonHelper::companyDatabaseConnection($m);

$master = DB::table('stock_out')->where('so_no', $id)->first();
if(!$master) {
    $master = DB::table('stock_out')->where('id', $id)->first();
}

$detail = [];
if($master) {
    $detail = DB::table('stock_out_data as sod')
        ->join('subitem as si', 'si.id', '=', 'sod.item_id')
        ->join('warehouse as wf', 'wf.id', '=', 'sod.warehouse_from')
        ->join('warehouse as wt', 'wt.id', '=', 'sod.warehouse_to')
        ->select('sod.*', 'si.product_name', 'si.sku_code', 'wf.name as from_wh', 'wt.name as to_wh')
        ->where('sod.master_id', $master->id)
        ->get();
}
CommonHelper::reconnectMasterDatabase();
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">S.No</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">From Wh</th>
                        <th class="text-center">To Wh</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    foreach($detail as $row): 
                        $status = ($row->si_status == 1) ? '<span class="label label-success">Received</span>' : '<span class="label label-warning">Pending</span>';
                    ?>
                    <tr class="text-center">
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $row->product_name; ?></td>
                        <td><?php echo $row->sku_code; ?></td>
                        <td><?php echo $row->from_wh; ?></td>
                        <td><?php echo $row->to_wh; ?></td>
                        <td><?php echo number_format($row->qty, 2); ?></td>
                        <td><?php echo $status; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
