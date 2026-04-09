<?php
use App\Helpers\CommonHelper;
$Counter = 1;
?>
@foreach($results as $row)
<tr class="text-center">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($row->adj_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($row->adj_date);?></td>
    <td><?php echo $row->warehouse_name; ?></td>
    <td><?php echo strtoupper($row->description);?></td>
    <td><?php echo $row->username; ?></td>
    <td>
        <button type="button" class="btn btn-success btn-xs" onclick="showDetailModelOneParamerter('sdc/viewQtyAdjustmentDetail?m=<?php echo $m; ?>', '<?php echo $row->id; ?>', 'View Adjustment Detail')">View</button>
    </td>
</tr>
@endforeach
