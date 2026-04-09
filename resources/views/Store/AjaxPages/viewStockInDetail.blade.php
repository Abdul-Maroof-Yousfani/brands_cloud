<?php
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$id = $_GET['id'] ?? $_GET['pOne'] ?? 0; // Standardized parameter check
CommonHelper::companyDatabaseConnection($m);

$master = DB::table('stock_in')->where('si_no', $id)->first();
if(!$master) {
    $master = DB::table('stock_in')->where('id', $id)->first();
}

$detail = [];
if($master) {
    $detail = DB::table('stock_in_data as sid')
        ->join('subitem as si', 'si.id', '=', 'sid.item_id')
        ->join('warehouse as wt', 'wt.id', '=', 'sid.warehouse_to')
        ->select('sid.*', 'si.product_name', 'si.sku_code', 'wt.name as to_wh')
        ->where('sid.master_id', $master->id)
        ->get();
}
CommonHelper::reconnectMasterDatabase();
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-success">
                        <th class="text-center">S.No</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Stock Out No</th>
                        <th class="text-center">Warehouse To</th>
                        <th class="text-center">Qty In</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    foreach($detail as $row): 
                    ?>
                    <tr class="text-center">
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $row->product_name; ?></td>
                        <td><?php echo $row->sku_code; ?></td>
                        <td><?php echo strtoupper($row->stock_out_no); ?></td>
                        <td><?php echo $row->to_wh; ?></td>
                        <td><?php echo number_format($row->qty, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
