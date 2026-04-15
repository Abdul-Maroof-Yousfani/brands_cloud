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
            
            <h1 style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">Recovery Report</h1>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList','','1');?>
                <?php if(true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                <?php endif;?>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="from" class="form-control" id="from" value="2025-10-01">
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
                        <label class="control-label">Voucher No</label>
                        <div class="input-group">
                            <input type="text" name="v_no" class="form-control" id="v_no">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control select2">
                            <option value="">All Customers</option>
                            @foreach(App\Models\Customer::where('status', 1)->get() as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Banks (Deposit To)</label>
                        <select name="internal_bank_id" id="internal_bank_id" class="form-control select2">
                            <option value="">All Banks</option>
                            @foreach(CommonHelper::get_all_bank_account() as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Customer Bank</label>
                        <select name="customer_bank_id" id="customer_bank_id" class="form-control select2">
                            <option value="">All Customer Banks</option>
                            @foreach(DB::connection('mysql2')->table('bank_detail')->get() as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Principle group</label>
                        <select name="principal_group_id" id="principal_group_id" class="form-control select2">
                            <option value="">All Groups</option>
                            @foreach(CommonHelper::get_all_principal_groups() as $group)
                                <option value="{{ $group->id }}">{{ $group->products_principal_group }}</option>
                            @endforeach
                        </select>
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
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printCashSaleVoucherDetail">
                                <div class="table-responsive">
                                    <table  class="table table-bordered table-striped table-condensed tableMargin" id="data-table">
                                        <thead>
                                        <tr>
                                            <th>Voucher No</th>
                                            <th>Date</th>
                                            <th>Stores</th>
                                            <th>Memo</th>
                                            <th>Principle group</th>
                                            <th>Region</th>
                                            <th>Mode</th>
                                            <th>Banks</th>
                                            <th>Cheque No</th>
                                            <th>Customer Bank</th>
                                            <th>Cheque date</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="11" class="text-right">Total:</th>
                                                <th id="total_footer" class="text-right">0</th>
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
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data-table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Recovery Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
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
            v_no: $('#v_no').val(),
            customer_id: $('#customer_id').val(),
            internal_bank_id: $('#internal_bank_id').val(),
            customer_bank_id: $('#customer_bank_id').val(),
            principal_group_id: $('#principal_group_id').val()
        },
        beforeSend: function() {
            $('#data').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
            $("#tbody").html("");
        },
        success: function (response) {
            $("#tbody").html(response);
            $('#data').html('');
            
            // Calculate total
            var total = 0;
            $('.total_amount').each(function() {
                var val = $(this).text().replace(/,/g, '');
                if (!isNaN(parseFloat(val))) {
                    total += parseFloat(val);
                }
            });
            $('#total_footer').text(total.toLocaleString());
        },
        error: function(xhr) {
            $('#data').html('<div class="alert alert-danger">Error loading data</div>');
            console.error(xhr.responseText);
        }
    });
    }


</script>
@endsection