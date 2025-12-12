<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
?>
<style>
    p{margin:0;padding:0;font-size:13px;font-weight:500;}
    input.form-control.form-control2{margin:0!important;}
    .table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th{vertical-align:inherit !important;text-align:left !important;padding: 7px 5px !important;}
    .totlas{display:flex;justify-content:right;gap:70px;background:#ddd;width:18%;float:right;padding-right:8px;}
    .totlas p{font-weight:bold;}
    .psds{display:flex;justify-content:right;gap:88px;}
    .psds p{font-weight:bold;}
    .userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{padding:10px 5px !important;}
    .totlass{display:inline;background:transparent;margin-top:-25px;}
    .totlass h2{font-size:13px !important;}
</style>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="well_N">
        <div class="dp_sdw">
            <h1 style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">Sale Report</h1>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList','','1');?>
                <?php if(true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                <?php endif;?>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="from" class="form-control" id="from" value="2025-10-28">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">To Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="to" class="form-control" id="to" value="2025-10-28">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">S.O No</label>
                        <div class="input-group">
                            <input type="text" name="to" class="form-control" id="so">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" style="margin-top: 25px;">
                        <button type="button" onclick="get_ajax_data()" class="btn btn-primary" style="margin-top: 11px;margin-left: 20px;">
                            <i class="fa fa-refresh"></i> Generate
                        </button>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printCashSaleVoucherDetail">
                                <div class="table-responsive">
                                    <table  class="table table-bordered table-striped table-condensed tableMargin" id="data-table">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width:150px;">Item Description</th>
                                            <th class="text-center" style="width:150px;" >Brand</th>
                                            <th class="text-center" style="width:150px;" >Article No. / SKU</th>
                                            <th class="text-center" style="width:150px;">Barcode</th>
                                            <th class="text-center" style="width:150px;">CTN</th>
                                            <th class="text-center" style="width:150px;">PCS</th>
                                            <th class="text-center" style="width:150px;">Gross Amount</th>
                                            <th class="text-center" style="width:150px;">Discount</th>
                                            <th class="text-center" style="width:150px;">Tax</th>
                                            <th class="text-center" style="width:150px;">Net Amount</th>
                                            <th class="text-center" style="width:150px;">COGS</th>
                                            <th class="text-center" style="width:150px;">COGS VAL</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <!-- <div class="table-responsive"> -->
                                                    <div id="data"></div>
                                                <!-- </div> -->
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
    </div>
</div>
 <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data-table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Sale Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
        }
    </script>
<script>
    function get_ajax_data() {
        $.ajax({
        url: window.location.href,  // sends to the same page
        method: "GET",             // or "GET", "DELETE", etc.
        data: {
            from: $("#from").val(),
            to: $("#to").val(),
            so: $('#so').val()
        },
        beforeSend: function() {
            $('#data').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
            $("#tbody").html("");
        },
        success: function (response) {
           $("#tbody").html(response);
           $('#data').html('');
        },
        error: function(xhr) {
            $('#data').html('<div class="alert alert-danger">Error loading data</div>');
            console.error(xhr.responseText);
        }
    });
    }

</script>
@endsection