<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export = ReuseableCode::check_rights(240);
$ItemId = '';
$ItemName = '';
if (isset($_GET['item_id'])) {
    $ItemId = $_GET['item_id'];
    $ItemName = CommonHelper::get_item_name($ItemId);
}
?>


@extends('layouts.default')
@section('content')
    @include('select2')
    @include('modal')



    <style>
        .form-horizontal .form-group {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>


    <div class="container-fluid">
        <div class="well_N">
            <!-- <div class="dp_sdw"> -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">

                            <div class="panel-heading clearfix">
                                <h3 class="panel-title pull-left">Purchase Traceability Report</h3>
                                <div class="pull-right">
                                    <button type="button"
                                        onclick="exportBtn('Purchase_Traceability_Report_{{ date('Y-m-d') }}')"
                                        class="btn btn-success btn-sm">
                                        <i class="fa fa-file-excel-o"></i> Export Excel
                                    </button>
                                    <button type="button" 
                                        onclick="exportPDF('Purchase_Traceability_Report_{{ date('Y-m-d') }}')"
                                        class="btn btn-danger btn-sm" style="margin-left: 5px;">
                                        <i class="fa fa-file-pdf-o"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="get" id="list_data" class="form-horizontal">
                                <input type="hidden" name="m" value="{{ request()->m }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Supplier</label>
                                            <select class="form-control select2" name="suppliers[]" multiple data-placeholder="All Suppliers">
                                                @foreach($Suppliers as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Product</label>
                                            <select class="form-control" name="product_id" id="product_id">
                                                <option value="">All Products</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Brands</label>
                                            <select class="form-control select2" name="brand_ids[]" multiple data-placeholder="All Brands" id="brand_id">
                                                @foreach($Brands as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Warehouse</label>
                                            <select class="form-control select2" name="warehouse_ids[]" multiple data-placeholder="All Warehouses" id="warehouse_id">
                                                @foreach($Warehouses as $w)
                                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" name="from_date" class="form-control" value="{{ date('Y-m-01') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" name="to_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="margin-top: 25px;">
                                            <button type="button" onclick="get_ajax_data()" class="btn btn-primary" style="width: 100%;">
                                                <i class="fa fa-refresh"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row well_N">
        <div class="col-md-12  container-fluid">
            <div class="panel panel-default ">
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
    <!-- </div> -->

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Only initialize datepicker if the plugin is available
            if ($.fn.datepicker) {
                $('input[type="date"]').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            }

            // Load initial data
            get_ajax_data();
        });


        function get_ajax_data() {
            $.ajax({
                url: $("#list_data").attr('action'),
                type: "GET",
                data: $("#list_data").serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                beforeSend: function() {
                    $('#data').html(
                        '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
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


        // function exportExcel(filename) {
        //     var table = document.getElementById('exportTable');

        //     // Loop through table rows and convert "Item Name" cell to string
        //     for (let i = 1; i < table.rows.length; i++) { // skip header
        //         let cell = table.rows[i].cells[2]; // assuming 3rd column is "Item Name"
        //         if (cell && cell.innerText && !isNaN(Date.parse(cell.innerText))) {
        //             // Add a leading apostrophe to prevent Excel from parsing as date
        //             cell.innerText = "'" + cell.innerText;
        //         }
        //     }

        //     var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet 1", raw: true });
        //     XLSX.writeFile(workbook, filename + '.xlsx');
        // }

        function exportExcel(filename) {
            var table = document.getElementById('exportTable');

            // Convert table to worksheet with proper parsing
            var ws = XLSX.utils.table_to_sheet(table, {
                raw: true
            });

            // Loop through all cells and convert numeric-looking strings into numbers
            Object.keys(ws).forEach(function(cell) {
                if (cell[0] === '!') return; // skip metadata

                let value = ws[cell].v;

                // If value is a number in string format, convert to Number
                if (typeof value === "string" && !isNaN(value) && value.trim() !== "") {
                    ws[cell].v = Number(value);
                    ws[cell].t = "n"; // set type as number
                }
            });

            // Create workbook
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet 1");

            // Save as Excel file
            XLSX.writeFile(wb, filename + ".xlsx");
        }

        function exportBtn(title) {
            exportExcel(title);
        }

        function exportPDF(filename) {
            var element = document.getElementById('exportTable');
            var opt = {
                margin:       10,
                filename:     filename + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'mm', format: 'a3', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>


    <script>
        $(document).ready(function() {
            console.log("jQuery working...");

            $('#product_id').select2({
                placeholder: 'Search Product',
                allowClear: true
            });

            // Dynamically bind input event when dropdown opens
            $('#product_id').on('select2:open', function() {
                setTimeout(function() {
                    let input = $('.select2-container--open .select2-search__field');
                    input.off('input').on('input', function() {
                        let keyword = $(this).val();
                        if (keyword.length >= 2) {
                            fetchProducts(keyword);
                        }
                    });
                }, 50);
            });
        });

        function fetchProducts(keyword) {
            $.ajax({
                url: "{{ route('ajax.search.product') }}",
                type: "GET",
                data: {
                    q: keyword
                },
                dataType: 'json',
                success: function(response) {
                    let productDropdown = $('#product_id');

                    // Only remove options that are not selected
                    productDropdown.find('option:not(:selected)').remove();

                    // Add new options using the 'text' field returned by backend
                    response.forEach(function(item) {
                        if (!productDropdown.find('option[value="' + item.id + '"]').length) {
                            let newOption = new Option(item.text, item.id, false, false);
                            productDropdown.append(newOption);
                        }
                    });

                    productDropdown.trigger('change');
                },
                error: function(xhr) {
                    console.error("Product search failed:", xhr.responseText);
                }
            });
        }
    </script>

    <script>
        function getWarehousesByTerritory() {
            let territory_id = $('#territory_id').val();

            $.ajax({
                url: '{{ route('ajax.get.warehouses') }}',
                type: 'GET',
                data: {
                    territory_id: territory_id
                },
                success: function(data) {
                    let $warehouse = $('#warehouse_id');

                    $warehouse.empty().append('<option value="">All Warehouses</option>');

                    $.each(data, function(i, warehouse) {
                        $warehouse.append('<option value="' + warehouse.id + '">' + warehouse.name +
                            '</option>');
                    });

                    // Refresh Select2 dropdown
                    $warehouse.trigger('change.select2');
                },
                error: function(xhr) {
                    console.error('Error fetching warehouses:', xhr.responseText);
                }
            });
        }



        // $('#warehouse_id').on('change', function () {
        //     let warehouse_id = $(this).val();

        //     $.ajax({
        //         url: '{{ route('ajax.get.brands') }}',
        //         type: 'GET',
        //         data: { warehouse_id: warehouse_id },
        //         success: function (data) {
        //             let $brand = $('#brand_id');

        //             $brand.empty().append('<option value="">All Brands</option>');

        //             $.each(data, function (i, brand) {
        //                 $brand.append('<option value="' + brand.id + '">' + brand.name + '</option>');
        //             });

        //             // Refresh select2
        //             $brand.trigger('change.select2');
        //         },
        //         error: function (xhr) {
        //             console.error('Error fetching brands:', xhr.responseText);
        //         }
        //     });
        // });

        $('#warehouse_id').on('change', function() {
            let warehouse_ids = $(this).val(); // Get multiple selected IDs

            $.ajax({
                url: '{{ route('ajax.get.brands') }}',
                type: 'GET',
                data: {
                    warehouse_id: warehouse_ids
                }, // Pass array
                success: function(data) {
                    let $brand = $('#brand_id');

                    $brand.empty().append('<option value="">All Brands</option>');

                    // Avoid duplicates using a set or checking IDs
                    let brandIds = new Set();

                    data.forEach(function(brand) {
                        if (!brandIds.has(brand.id)) {
                            brandIds.add(brand.id);
                            $brand.append('<option value="' + brand.id + '">' + brand.name +
                                '</option>');
                        }
                    });

                    $brand.trigger('change.select2');
                },
                error: function(xhr) {
                    console.error('Error fetching brands:', xhr.responseText);
                }
            });
        });
    </script>
@endsection
