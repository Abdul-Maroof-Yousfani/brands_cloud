<?php
use App\Helpers\CommonHelper;
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

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="well_N">
        <div class="dp_sdw">
            <h1 style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">Stock Transfer Report</h1>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="from" class="form-control" id="from" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">To Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="to" class="form-control" id="to" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">SKU / Barcode</label>
                        <input type="text" name="sku" class="form-control" id="sku">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="margin-top: 25px;">
                        <button type="button" onclick="get_ajax_data()" class="btn btn-primary">
                            <i class="fa fa-refresh"></i> Generate
                        </button>
                    </div>
                </div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printReportDetail">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-condensed tableMargin">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Stock Transfer No#</th>
                                            <th class="text-center">Transfer Date</th>
                                            <th class="text-center">SKU Code</th>
                                            <th class="text-center">Item Name</th>
                                            <th class="text-center">Barcode</th>
                                            <th class="text-center">Transfer Quantity</th>
                                            <th class="text-center">Warehouse From</th>
                                            <th class="text-center">Warehouse To</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Created By (User)</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            
                                        </tbody>
                                        <tfoot id="tfoot">

                                        </tfoot>
                                    </table>
                                </div>
                                <div id="data"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('printReportDetail');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Stock Transfer Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }

    function get_ajax_data() {
        $.ajax({
            url: window.location.href,
            method: "GET",
            data: {
                from: $("#from").val(),
                to: $("#to").val(),
                sku: $("#sku").val()
            },
            beforeSend: function() {
                $('#data').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
                $("#tbody").html("");
                $("#tfoot").html("");
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

    // Load data on document ready
    $(document).ready(function() {
        get_ajax_data();
    });
</script>
@endsection
