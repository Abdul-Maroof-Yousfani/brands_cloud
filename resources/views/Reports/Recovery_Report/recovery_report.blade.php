<?php
use App\Helpers\CommonHelper;
use App\Helpers\SaleHelper;
?>
<style>
    .form-horizontal .form-group {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }
</style>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')

    <div class="well_N">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left">Outstanding Ageing Report</h3>
                            <div class="pull-right">
                                <button type="button" onclick="exportBtn('Outstanding_Ageing_Report_{{ date('Y-m-d') }}')"
                                    class="btn btn-success btn-sm">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                            </div>
                        </div>
                        <div class="panel-body" style="padding: 20px;">
                            <form method="get" id="list_data" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">From Date</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="date" name="from" class="form-control" id="from" value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">To Date</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="date" name="to" class="form-control" id="to" value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">SI No</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                                <input type="text" name="si" class="form-control" id="si" placeholder="Search SI Number...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Brands</label>
                                            <select name="brand_id[]" id="brand_id" class="form-control select2" multiple>
                                                @foreach(App\Helpers\CommonHelper::get_all_brand() as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Warehouses</label>
                                            <select name="warehouse_id[]" id="warehouse_id" class="form-control select2" multiple>
                                                @foreach(App\Helpers\CommonHelper::get_all_warehouse() as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Customers</label>
                                            <select name="customer_id[]" id="customer_id" class="form-control select2" multiple>
                                                @foreach(App\Helpers\CommonHelper::get_all_customers() as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Regions</label>
                                            <select name="region_id[]" id="region_id" class="form-control select2" multiple>
                                                @foreach(App\Helpers\CommonHelper::get_all_territories() as $territory)
                                                    <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">&nbsp;</label>
                                            <div style="display: block;">
                                                <button type="button" onclick="get_ajax_data()" class="btn btn-primary">
                                                    <i class="fa fa-refresh"></i> Generate
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="data"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select",
                width: '100%'
            });
            get_ajax_data();
        });

        function get_ajax_data() {
            $.ajax({
                url: window.location.href,
                type: "GET",
                data: $("#list_data").serialize(),
                beforeSend: function() {
                    $('#data').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
                },
                success: function(response) {
                    $('#data').html(response);
                },
                error: function(xhr) {
                    $('#data').html('<div class="alert alert-danger">Error loading data</div>');
                    console.error(xhr.responseText);
                }
            });
        }

        function exportExcel(filename) {
            var table = document.getElementById('exportTable');
            if (!table) {
                alert('No data available to export');
                return;
            }
            var ws = XLSX.utils.table_to_sheet(table, { raw: true });
            Object.keys(ws).forEach(function(cell) {
                if (cell[0] === '!') return;
                let value = ws[cell].v;
                if (typeof value === "string" && !isNaN(value) && value.trim() !== "") {
                    ws[cell].v = Number(value);
                    ws[cell].t = "n";
                }
            });
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet 1");
            XLSX.writeFile(wb, filename + ".xlsx");
        }

        function exportBtn(title) {
            exportExcel(title);
        }
    </script>
@endsection