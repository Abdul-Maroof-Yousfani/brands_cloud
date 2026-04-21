<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

use Illuminate\Support\Facades\Session;
$view = true;
$edit = true;
$export = true;
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
                           <h2 class="subHeadingLabelClass">Vendor Credit List</h2>
                        </div>
                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                              <div class="headquid">
                                  <?php echo CommonHelper::displayPrintButtonInBlade('PrintVendorCreditList', '', '1'); ?>
                                <?php if ($export == true) : ?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')"><i class="fa fa-external-link" aria-hidden="true"></i> Export </button>
                                <?php endif; ?>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintVendorCreditList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="userlittab table table-bordered sf-table-list" id="VendorCreditListTable">
                                            <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center col-sm-2">Vendor</th>
                                                <th class="text-center col-sm-3">Description</th>
                                                <th class="text-center">Account</th>
                                                <th class="text-center">Branch</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center col-sm-2">Action</th>
                                            </thead>
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
                url: `vendorCreditNote/${id}/delete`,
                type: "get",
                data: $(this).serialize(),
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
                url: `{{ url('vendorCreditNote') }}/${id}/approve`, 
                type: "get",
                success: function(response) {
                    let row = $(el).closest("tr");
                    if(row.length) {
                        row.find(".approve").text("Approved");
                        row.find(".btn-success").prop("disabled", true);
                        row.find("li:contains('Approve')").remove();
                    }
                    if($(el).parent().hasClass('text-right')) {
                        $(el).remove();
                    }
                    alert("Approved successfully");
                },
                error: function() {
                    alert("Approval failed!");
                }
            });
        }
    }
    $.ajax({
        url: window.location.href,
        type: "get",
        success: function(response) {
            $("#data").html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            alert("Something went wrong!");
        }
    });
</script>
<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script !src="">
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('VendorCreditListTable');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('Vendor Credit List <?php echo date('d-m-Y') ?>.' + (type || 'xlsx')));
    }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
