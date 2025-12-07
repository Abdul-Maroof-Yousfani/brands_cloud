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
                        <label class="control-label">Brands</label>
                        <select name="brand_id[]" id="brand_id" class="form-control select2">
                            <option value="" selected>Select Brands</option>
                            @foreach(App\Helpers\CommonHelper::get_all_brand() as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Warehouses</label>
                        <select name="warehouse_id[]" id="warehouse_id" class="form-control select2">
                            <option value="" selected>Select Warehouses</option>
                            @foreach(App\Helpers\CommonHelper::get_all_warehouse() as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">SI No</label>
                        <div class="input-group">
                            <input type="text" name="si_no" class="form-control" id="si">
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
                                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                                        <thead>
                                        <tr>
                                            <th>S No.</th>
                                            <th>Branch</th>
                                            <th>Region</th>
                                            <th>Warehouse</th>
                                            <th>Customer ID</th>
                                            <th>Cust Name</th>
                                            <th>Cust Address</th>
                                            <th>Doc Type</th>
                                            <th>Docno</th>
                                            <th>Brand</th>
                                            <th>Date</th>
                                            <th>Sales Officer Name</th>
                                            <th>Invoice Amount</th>
                                            <th>Receipt Doc No</th>
                                            <th>Receipt Amount</th>
                                            <th>Receipt Mode</th>
                                            <th>Sale Return Doc No</th>
                                            <th>Sale Return Amount</th>
                                            <th>Adjustment Doc No</th>
                                            <th>Adjust Amount</th>
                                            <th>Adjustment Remarks</th>
                                            <th>Unadjusted Amount</th>
                                            <th>Total Adjustment</th>
                                            <th>Outstanding</th>
                                            <th>Difference</th>
                                            <th>1-45</th>
                                            <th>46-90</th>
                                            <th>91-179</th>
                                            <th>More than 180 days</th>
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
          $('.select2').select2({
                placeholder: "Select",
                width: '100%' // ensures the dropdown matches container width
            });
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('printCashSaleVoucherDetail');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Outstanding Ageing Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
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
            si: $('#si').val(),
            warehouse_id: $("#warehouse_id").val(),
            brand_id: $("#brand_id").val()
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