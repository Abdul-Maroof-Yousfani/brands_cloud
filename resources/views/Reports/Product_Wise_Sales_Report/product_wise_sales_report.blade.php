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
            <h1 style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">Product Wise Sales Report</h1>
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList','','1');?>
                <?php if(true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                <?php endif;?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="from" class="form-control" id="from">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">To Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="to" class="form-control" id="to">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Brand</label>
                        <div class="input-group">
                            <select class="form-control select2" id="brand_id">
                                <option value="">Select Brand</option>
                                @foreach(\App\Helpers\CommonHelper::get_all_brand() as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Stores</label>
                        <div class="input-group">
                            <select class="form-control select2" id="store_id">
                                <option value="">Select Store</option>
                                @foreach(\App\Helpers\CommonHelper::get_customer() as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Products</label>
                        <div class="input-group">
                            <select class="form-control select2" id="subitem_id">
                                <option value="">Select Product</option>
                                @foreach(\App\Helpers\CommonHelper::get_subitems() as $subitem)
                                    <option value="{{ $subitem->id }}">{{ $subitem->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-3">
                        <div class="form-group" style="margin-top: 25px;">
                            <button type="button" onclick="get_ajax_data()" class="btn btn-primary" style="margin-top: 11px;margin-left: 20px;">
                                <i class="fa fa-refresh"></i> Generate
                            </button>
                        </div>
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
                                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width:150px;">S. No</th>
                                            <th class="text-center" style="width:150px;" >Date</th>
                                            <th class="text-center" style="width:150px;">Store</th>
                                            <th class="text-center" style="width:150px;">Location</th>
                                            <th class="text-center" style="width:150px;" >BA</th>
                                            <th class="text-center" style="width:150px;">MIS Code</th>
                                            <th class="text-center" style="width:150px;">Barcode</th>
                                            <th class="text-center" style="width:150px;">Product</th>
                                            <th class="text-center" style="width:150px;">Brand</th>
                                            <th class="text-center" style="width:150px;">Qty</th>
                                            <th class="text-center" style="width:150px;">Unit Price</th>
                                            <th class="text-center" style="width:150px;">Total Amount (SP)</th>
                                            <th class="text-center" style="width:150px;">Total Amount (TP)</th>
                                            <th class="text-center" style="width:150px;">Total Amount (MRP)</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            
                                        </tbody>
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
    <script !src="">
        $(".select2").select2();
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('printCashSaleVoucherDetail');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Product Wise Sales Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
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
            so: $('#so').val(),
            brand_id: $("#brand_id").val(),
            store_id: $("#store_id").val(),
            subitem_id: $("#subitem_id").val()
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