
<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\QuotationHelper; 
 use App\Helpers\ReuseableCode; 

$view=ReuseableCode::check_rights(396);
$edit=ReuseableCode::check_rights(397);
$delete=ReuseableCode::check_rights(398);
$summary=ReuseableCode::check_rights(399);

?>



<?php 
$count=1;
$pr_no = [];
?>
@foreach ($data as $row)

  
<?php $status = 0 ;?>
   @if (!in_array($row->pr_no, $pr_no))
   <?php $pr_no[] = $row->pr_no; ?>
    <tr class="text-center">
    <td  colspan="10" style="font-weight: bold"> 
        <?php   echo 'PR No : '. strtoupper($row->pr_no);
        echo '<br>'.'PR Date : '.CommonHelper::changeDateFormat($row->demand_date);
         echo '<br>'.'Comparative no : '.$row->comparative_number ?? 'N/A' ;
        echo '<br><p style="color: #c59f9f">'.'Status : '.QuotationHelper::check_quotation_status($row->quotation_approve).'</p>';
        ?></td> 
   <?php $status =  1 ?>
</tr>
@endif


<tr class="text-center">
    <td>{{ $count++ }}</td>
    <td>{{ strtoupper($row->voucher_no) }}</td>
    <td>{{ CommonHelper::changeDateFormat($row->voucher_date) }}</td>
    <td>{{ CommonHelper::get_supplier_name($row->vendor_id) }}</td>
    <td>{{ $row->ref_no }}</td>
    <td>{{ number_format($row->amount,3) }}</td>
    <!-- <td>{{QuotationHelper::check_quotation_status($row->quotation_status)}}</td> -->
    <td>{{$row->quotation_status == 2 ? 'Approved' : 'Pending'}}</td>

    <td>

    <div class="dropdown">
            <button class="drop-bt dropdown-toggle"
                type="button" data-toggle="dropdown"
                aria-expanded="false">
                ...
            </button>
            <ul class="dropdown-menu">
                <li>
                @if ($view==true)
   <a onclick="showDetailModelOneParamerter('quotation/view_quotation?m=<?php echo Session::get('run_company')?>','<?php echo $row->id;?>','Quotation')"
   type="button" class="btnn btn-success">View</a>
   @endif

   @if ($edit==true)
   <a  href="{{ url('quotation/edit_quotation/'.$row->pr_id.'/'.$row->id) }}" class="btn btn-warning">Edit</a>
   @endif
   @if ($delete==true)
   <a onclick="delete_quotation('{{$row->id}}')"
      type="button" class="btn btn-danger">Delete</a>
  
   @endif
   @if ($summary==true)
    <a onclick="showDetailModelOneParamerter('quotation/qutation_summary?m=<?php echo Session::get('run_company')?>','<?php echo $row->pr_id;?>','Quotation')"
      type="button" class="btnn btn-secondary">Summary</a>
      @endif
                  </li>
            </ul>
        </div>
       
   </td>
</tr>



@endforeach

