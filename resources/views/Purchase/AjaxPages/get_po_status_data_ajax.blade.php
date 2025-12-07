<?php
$m = $_GET['m'];
use App\Helpers\CommonHelper;
$Counter = 1;
//$paramurl = "pdc/viewJobOrderDetail?m=".$m;
//$paramOne = "pdc/viewIssuanceDetail?m=".$m;
//$paramThree = "View Issuance Detail";
//$paramFour= url('/purchase/editGoodIssuance/');

foreach($Data as $dataFil):
$Item = CommonHelper::get_single_row('subitem','id',$dataFil->sub_item_id);
if($PoStatus !=3):
$Warehouse = CommonHelper::get_single_row('warehouse','id',$dataFil->warehouse_id);
        endif;
$Supp =  DB::Connection('mysql2')->select('select supplier_id from purchase_request WHERE purchase_request_no = "'.$dataFil->purchase_request_no.'"');
$Supplier = CommonHelper::get_single_row('supplier','id',$Supp[0]->supplier_id);
?>
<tr class="text-center" >
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($dataFil->purchase_request_no)?></td>
    <td><?php echo CommonHelper::changeDateFormat($dataFil->purchase_request_date)?></td>

    <td><?php echo $Supplier->name;?></td>
    <td><?php echo $Item->sub_ic?></td>
    <td><?php if($PoStatus !=3):echo $Warehouse->name;endif;?></td>
    <td><?php echo number_format($dataFil->purchase_approve_qty,2)?></td>
    <td><?php echo number_format($dataFil->purchase_recived_qty,2)?></td>
    <td><?php echo number_format($dataFil->purchase_approve_qty-$dataFil->purchase_recived_qty,2)?></td>

</tr>
<?php endforeach;?>