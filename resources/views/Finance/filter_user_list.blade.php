<?php
use App\Helpers\CommonHelper;

$counter = 1;
foreach ($Users as $row1) {
?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center"><?php echo strtoupper($row1->name);?></td>
    <td class="text-center"><?php echo $row1->email;?></td>

    <!--- <td class="text-center"><?php //echo CommonHelper::getCompanyName($row1->company_id);?></td> -->
    <td class="text-center"><?php if($row1->status == 1){echo 'Active';} else{ echo 'Inactive';}?></td>
    <td class="text-center hidden-print">
    <div class="dropdown">
        <button class="drop-bt dropdown-toggle"
            type="button" data-toggle="dropdown"
            aria-expanded="false">
            ...
        </button>
        <ul class="dropdown-menu">
           
            <li>
                <?php if($row1->status == 1):?>
                    <a target="new" href="{{ route('userEditForm',$row1->id)}}" class="btn btn-sm btn-warning">
                    <i class="fa fa-pencil" aria-hidden="true"></i> 
                    Edit
                    </a>
                <button type="button" class="btn btn-xs btn-danger" id="BtnInactive<?php echo $row1->id?>" onclick="ActiveInActiveUser('<?php echo $row1->id?>','2')">Inactive</button>
                <?php else:?>
                <button type="button" class="btn btn-xs btn-danger" id="BtnActive<?php echo $row1->id?>" onclick="ActiveInActiveUser('<?php echo $row1->id?>','1')">Active</button>
                <?php endif;?>
            </li>
        </ul>
    </div>
    
        
    </td>
</tr>
<?php }?>


   <script>
$("#TableExportToCsv").DataTable({
    ordering: true,
    searching: true,
    paging: true,
    info: false,
    autoWidth: false, // prevent DataTables from auto-calculating width
});

</script>