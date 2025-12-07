<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

use Illuminate\Support\Facades\Session;
$view = ReuseableCode::check_rights(124);
$edit = ReuseableCode::check_rights(125);
$export = ReuseableCode::check_rights(258);
$this->m = Session::get('run_company');
?>
@extends('layouts.default')
@section('content')

@include('select2')
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="headquid">
                           <h2 class="subHeadingLabelClass">Debit List</h2>
                        </div>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                              <div class="headquid">
                                  <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList', '', '1'); ?>
                                <?php if ($export == true) : ?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')"><i class="fa fa-external-link" aria-hidden="true"></i> Export </button>
                                <?php endif; ?>
                              </div>
                            </div>
                        </div>
                    </div>


                    <!--<div class="row">-->

                    <!--    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">-->
                    <!--        <label>From Date</label>-->
                    <!--        <input type="Date" name="from" id="from" value="{{date('Y-m-01')}}" class="form-control" />-->
                    <!--    </div>-->
                    <!--    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">-->
                    <!--        <label>To Date</label>-->
                    <!--        <input type="Date" name="to" id="to" max="<?php ?>" value="{{date('Y-m-t')}}" class="form-control" />-->
                    <!--    </div>-->
                    <!--    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">-->
                    <!--        <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />-->
                    <!--    </div>-->
                    <!--</div>-->


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="userlittab table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center col-sm-1">Store</th>
                                                <th class="text-center col-sm-2">Delivery Man</th>
                                                <th class="text-center col-sm-2">Description</th>
                                                <th class="text-center">Credit</th>
                                                <th class="text-center">On Record</th>
                                                <th class="text-center">Voucher Type</th>
                                                <th class="text-center">Branch</th>
                                                <th class="text-center">Status</th>

                                                <th class="text-center col-sm-2">Action</th>
                                            </thead>
                                            <?php $count = 0; ?>
                                            <tbody id="data">
                                                
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
    </div>
</div>
<script>
    function deleteCredit(el, id) {
        let is_confirmed = confirm("Are you sure?");
        if(is_confirmed) {
            $.ajax({
                url: `debitNote/${id}/delete`, // empty = current URL (self)
                type: "get",
                data: $(this).serialize(), // serialize form data
                success: function(response) {
                    $(el).closest("tr").remove();
                },
               
            });
        }
    }
    function approve(el, id) {
        let is_confirmed = confirm("Are you sure?");
        if(is_confirmed) {
            $.ajax({
                url: `debitNote/${id}/approve`, // empty = current URL (self)
                type: "get",
                success: function(response) {
                    $(el).closest("tr").find(".approve").text("Approved");
                    $(el).closest("tr").find(".btn-success").prop("disabled", true);
                },
            });
        }
    }
    $.ajax({
        url: window.location.href, // empty = current URL (self)
        type: "get",
        data: $(this).serialize(), // serialize form data
        success: function(response) {
            $("#data").html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            alert("Something went wrong!");
        }
    });
</script>

</script>
<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script !src="">
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('EmpExitInterviewList');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('Sales Return <?php echo date('d-m-Y') ?>.' + (type || 'xlsx')));
    }
    function delete_cate(id) {
                if (confirm('Are You Sure ? You want to delete this recored...!')) {
                    var m = '<?php echo $this->m ?>';
                    var url = '<?php echo url('/') ?>';
                    $.ajax({
                        url: url + '/recipe/recipeDelete',
                        type: 'Get',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            $('#remove' + response).remove();
                            console.log(response)
                        }
                    });
                } else {}
            }
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
