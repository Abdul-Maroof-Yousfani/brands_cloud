<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
?>
<style>
    p {
        margin: 0;
        padding: 0;
        font-size: 13px;
        font-weight: 500;
    }

    input.form-control.form-control2 {
        margin: 0 !important;
    }

    .table-bordered>thead>tr>th,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>th {
        vertical-align: inherit !important;
        text-align: left !important;
        padding: 7px 5px !important;
    }

    .totlas {
        display: flex;
        justify-content: right;
        gap: 70px;
        background: #ddd;
        width: 18%;
        float: right;
        padding-right: 8px;
    }

    .totlas p {
        font-weight: bold;
    }

    .psds {
        display: flex;
        justify-content: right;
        gap: 88px;
    }

    .psds p {
        font-weight: bold;
    }

    .userlittab>thead>tr>td,
    .userlittab>tbody>tr>td,
    .userlittab>tfoot>tr>td {
        padding: 10px 5px !important;
    }

    .totlass {
        display: inline;
        background: transparent;
        margin-top: -25px;
    }

    .totlass h2 {
        font-size: 13px !important;
    }
</style>
@extends('layouts.default')
@section('content')
    @include('select2')
    @include('modal')

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">

            <div class="dp_sdw">
                <h1 style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">Custom Sale Tax Report</h1>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList', '', '1'); ?>
                    <?php if(true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export
                        <b>(xlsx)</b></button>
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
                            <label class="control-label">S.I No</label>
                                <input type="text" name="to" class="form-control" id="so">
                        </div>
                    </div>
                    
                </div>
                    <div class="row">

<div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Brands</label>
                            <select class="form-control select2" id="brand_id">
                                <option value="">Select Brand</option>
                                @foreach (\App\Helpers\CommonHelper::get_all_brand() as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Customers</label>
                                <select class="form-control select2" id="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach (\App\Helpers\CommonHelper::get_customer() as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Regions</label>
                                <select class="form-control select2" id="region_id">
                                    <option value="">Select Region</option>
                                    @foreach (\App\Helpers\CommonHelper::get_all_territories() as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Warehouses</label>
                                <select class="form-control select2" id="warehouse_id">
                                    <option value="">Select Warehouse</option>
                                    @foreach (\App\Helpers\CommonHelper::get_all_warehouse() as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-top: 25px;">
                                <button type="button" onclick="get_ajax_data()" class="btn btn-primary"
                                    style="margin-top: 11px;margin-left: 20px;">
                                    <i class="fa fa-refresh"></i> Generate
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div style="line-height:5px;">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printCashSaleVoucherDetail">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed tableMargin">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Region</th>
                                                    <th class="text-center">State</th>
                                                    <th class="text-center">Warehouse</th>
                                                    <th class="text-center">Buyer Name</th>
                                                    <th class="text-center">Brand Name</th>
                                                    <th class="text-center">Product</th>
                                                    <th class="text-center">Barcode</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Invoice No</th>
                                                    <th class="text-center">Group</th>
                                                    <th class="text-center">HS Code</th>
                                                    <th class="text-center">Doc Date</th>
                                                    <th class="text-center">MRP</th>
                                                    <th class="text-center">Rate</th>
                                                    <th class="text-center">QTY</th>
                                                    <th class="text-center">Gross Sales</th>
                                                    <th class="text-center">Discount Amount</th>
                                                    <th class="text-center">Tax %</th>
                                                    <th class="text-center">S.Tax Value</th>
                                                    <th class="text-center">Net Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">

                                            </tbody>
                                            <tfoot>
                                                <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                    <td colspan="12" class="text-right">Total:</td>
                                                    <td id="total_retail">0.00</td>
                                                    <td id="total_rate">0.00</td>
                                                    <td id="total_pcs">0.00</td>
                                                    <td id="total_gross">0.00</td>
                                                    <td id="total_discount">0.00</td>
                                                    <td></td>
                                                    <td id="total_tax">0.00</td>
                                                    <td id="total_net">0.00</td>
                                                </tr>
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
        $(".select2").select2();
    </script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('printCashSaleVoucherDetail');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('Custom Sale Tax Report <?php echo date('d-m-Y'); ?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        function get_ajax_data() {
            $.ajax({
                url: window.location.href, // sends to the same page
                method: "GET", // or "GET", "DELETE", etc.
                data: {
                    from: $("#from").val(),
                    to: $("#to").val(),
                    so: $('#so').val(),
                    brand_id: $("#brand_id").val(),
                    customer_id: $("#customer_id").val(),
                    region_id: $("#region_id").val(),
                    warehouse_id: $("#warehouse_id").val()
                },
                beforeSend: function() {
                    $('#data').html(
                        '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
                    $("#tbody").html("");
                },
                success: function(response) {
                    console.log(response);
                    $("#tbody").html(response);
                    $('#data').html('');
                    
                    // Calculate totals
                    let total_retail = 0;
                    let total_rate = 0;
                    let total_pcs = 0;
                    let total_gross = 0;
                    let total_discount = 0;
                    let total_tax = 0;
                    let total_net = 0;

                    $('.retail_val').each(function() { total_retail += parseFloat($(this).text()) || 0; });
                    $('.rate_val').each(function() { total_rate += parseFloat($(this).text()) || 0; });
                    $('.pcs_val').each(function() { total_pcs += parseFloat($(this).text()) || 0; });
                    $('.gross_val').each(function() { total_gross += parseFloat($(this).text()) || 0; });
                    $('.discount_val').each(function() { total_discount += parseFloat($(this).text()) || 0; });
                    $('.tax_val').each(function() { total_tax += parseFloat($(this).text()) || 0; });
                    $('.net_val').each(function() { total_net += parseFloat($(this).text()) || 0; });

                    $('#total_retail').text(total_retail.toFixed(2));
                    $('#total_rate').text(total_rate.toFixed(2));
                    $('#total_pcs').text(total_pcs.toFixed(2));
                    $('#total_gross').text(total_gross.toFixed(2));
                    $('#total_discount').text(total_discount.toFixed(2));
                    $('#total_tax').text(total_tax.toFixed(2));
                    $('#total_net').text(total_net.toFixed(2));
                },
                error: function(xhr) {
                    $('#data').html('<div class="alert alert-danger">Error loading data</div>');
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
