<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$m = $_GET['m'];
$paramOne = $_GET['paramOne'];
$parentCode = $_GET['parentCode'];
if(empty($paramOne)){
 //   $subDepartmentsList = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.'');
}else{
   // $subDepartmentsList = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `id` = '.$paramOne.'');
}


?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                <?php
             
               
             $data= DB::Connection('mysql2')->table('quotation_data as a')
             ->join('demand_data as b','a.pr_data_id','=','b.id')
             ->join('quotation as c','a.master_id','=','c.id')
             ->join('demand as d','b.master_id','=','d.id')
             ->where('a.status',1)
             ->where('b.status',1)
             ->where('c.quotation_status',2)
             ->select('b.id','b.sub_item_id','c.comparative_number','c.voucher_no as quotation_no','d.demand_no','d.demand_date','a.vendor','b.qty','c.dept_id')
             ->orderBy('vendor')
            ->get()->toArray();

            
            $vendor = [];
                ?>
                <?php echo Form::open(array('url' => 'stad/createPurchaseRequestDetailForm?m='.$m.'&&parentCode='.$parentCode.'&&pageType=add#SFR','id'=>'createPurchaseRequestDetailForm_'));?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="departmentId" value="<?php ?>">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">



                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th class="text-center">Select</th>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Quotation NO.</th>
                                    <th class="text-center">Comparative Number</th>
                                    <th class="text-center">PR NO.</th>
                                    <th class="text-center">PR Date</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Req. Qty.</th>
                                </tr>
                                </thead>
                                <tbody id="filterDemandVoucherList">
                                @if (!empty($data))
                                        <?php $vendor = []; $counter = 1; ?>
                                    @foreach ($data as $row)
                                        @if (!in_array($row->vendor, $vendor))
                                                <?php $vendor[] = $row->vendor; ?>
                                                    <!-- Vendor Header Row with Check All -->
                                            <tr class="vendor-header text-center">
                                                <td colspan="7" style="font-weight: bold">
                                                    <input type="checkbox" class="checkAllVendor" data-vendor-id="{{ $row->vendor }}">
                                                    Check All for Vendor: {{ CommonHelper::get_supplier_name($row->vendor) }}
                                                </td>
                                            </tr>
                                        @endif
                                        <!-- Individual Rows for Each Product -->
                                        <tr class="text-center vendor-row vendor-{{ $row->vendor }}">
                                            <td>
                                                <input type="checkbox" name="checkAll[]" class="checkSingle_{{ $row->vendor }}"
                                                       id="{{ $row->id . '_' . $row->vendor }}" value="{{ $row->id }}">
                                            </td>
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ strtoupper($row->quotation_no) }}</td>
                                            <td>{{ strtoupper($row->comparative_number) }}</td>
                                            <td>{{ strtoupper($row->demand_no) }}</td>
                                            <td>{{ CommonHelper::changeDateFormat($row->demand_date) }}</td>
                                            <td>{{ CommonHelper::get_product_name($row->sub_item_id) }}</td>
                                            <td>{{ number_format($row->qty, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>


                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                    </div>
                </div>
                <?php echo Form::close();?>
                <?php ?>
                <div class="lineHeight">&nbsp;</div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $(".checkedAll_<?php echo $row->id?>").change(function(){
                            if(this.checked){
                                $(".checkSingle_<?php echo $row->id?>").each(function(){
                                    this.checked=true;
                                })
                            }else{
                                $(".checkSingle_<?php echo $row->id?>").each(function(){
                                    this.checked=false;
                                })
                            }
                        });

                        $(".checkSingle_<?php echo $row->id?>").click(function () {
                            if ($(this).is(":checked")){
                                var isAllChecked = 0;
                                $(".checkSingle_<?php echo $row->id?>").each(function(){
                                    if(!this.checked)
                                        isAllChecked = 1;
                                })
                                //if(isAllChecked == 0){ $(".checkedAll_<?php echo $row->id?>").prop("checked", true); }
                            }else {
                                $(".checkedAll_<?php echo $row->id?>").prop("checked", false);
                            }
                        });
                    });
                </script>

                <?php ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function checkCheckedBox(id,sIdOne,sIdTwo) {
        if ($('#'+id+':checked').length <= 1){
        }else{
         //   alert("Please select at least one checkbox Same Item.");
         //   $("input[name='checkAll_"+sIdOne+"_"+sIdTwo+"']:checkbox").prop('checked', false);
        }

    }
 
 	var vendor = [];
function check_supp(id)
{
    var numberOfChecked = $('input:checkbox:checked').length;
    if (numberOfChecked==0)
    {
        vendor = [];
    }
   if ( $('#'+id).is(':checked')==true)
   {
  
      var supp= id.split('_');

      if(jQuery.inArray(supp[1], vendor) !== -1)
      {
        //    alert('exists');
      }
      else 
      {

        if (vendor.length >0)
        {
           if (supp[1]!=vendor[0])
           {
            $('#'+id).prop('checked', false);
            alert('Supplier Should Be Same');
           }
        }
        else 
        {
            vendor.push(supp[1]);
        }
        
       
        
      }
   
       
   } 

  
}

    $(document).ready(function() {
        // Handle "Check All" for each vendor
        $('.checkAllVendor').on('click', function() {
            let vendorId = $(this).data('vendor-id');
            let isChecked = $(this).is(':checked');

            // Check/Uncheck all checkboxes for this vendor
            $(`.vendor-${vendorId} .checkSingle_${vendorId}`).prop('checked', isChecked);
        });

        // Uncheck "Check All" if any individual checkbox is unchecked
        $('.checkSingle_29').on('click', function() {
            let vendorId = $(this).closest('tr').attr('class').match(/vendor-(\d+)/)[1];

            if ($(`.vendor-${vendorId} .checkSingle_${vendorId}:checked`).length === $(`.vendor-${vendorId} .checkSingle_${vendorId}`).length) {
                $(`.checkAllVendor[data-vendor-id="${vendorId}"]`).prop('checked', true);
            } else {
                $(`.checkAllVendor[data-vendor-id="${vendorId}"]`).prop('checked', false);
            }
        });
    });


</script>