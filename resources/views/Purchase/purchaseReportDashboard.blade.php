@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .report-dashboard {
        padding: 0px;
        min-height: 100vh;
    }
    
    .filter-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .report-card {
        background: #fff;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .report-header {
        padding: 30px;
        text-align: center;
        border-bottom: 1px solid #efefef;
    }
    
    .report-header h3 {
        margin: 5px 0;
        font-weight: 600;
        color: #333;
        font-size: 24px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .report-header h4 {
        color: #555;
        margin-bottom: 10px;
        font-size: 20px;
        font-weight: 400;
    }
    
    .report-actions {
        padding: 15px 30px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        background: #fdfdfd;
    }
    
    .search-wrapper {
        margin-right: auto;
        position: relative;
        width: 300px;
    }
    
    .search-input {
        width: 100%;
        padding: 8px 15px 8px 35px;
        border-radius: 20px;
        border: 1px solid #ddd;
        font-size: 13px;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }
    
    .btn-submit {
        background-color: #8dbd63;
        color: white;
        border: none;
        padding: 10px 40px;
        border-radius: 4px;
        margin-top: 15px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(141, 189, 99, 0.3);
    }
    
    .btn-submit:hover {
        background-color: #7aa554;
        color: white;
        box-shadow: 0 4px 8px rgba(141, 189, 99, 0.4);
    }
    
    .btn-export-excel { background-color: #00bcd4; color: white; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 500; }
    .btn-export-csv { background-color: #ff9800; color: white; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 500; }
    .btn-print { background-color: #4caf50; color: white; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 500; }
    
    .btn-export-excel:hover, .btn-export-csv:hover, .btn-print:hover { opacity: 0.9; color: white; text-decoration: none; }

    .report-table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }
    
    .report-table thead th {
        background-color: #fcfcfc;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #eef0f3;
        padding: 15px;
        text-align: center;
        font-size: 13px;
        border-top: 1px solid #efefef;
    }
    
    .report-table tbody td {
        padding: 15px;
        color: #555;
        border-bottom: 1px solid #f1f3f5;
        vertical-align: middle;
        text-align: center;
        font-size: 13px;
    }
    
    .report-table tbody tr:hover {
        background-color: #f9fbfe;
    }
    
    .status-badge {
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        min-width: 90px;
    }
    
    .status-approved { background-color: #c8e6c9; color: #2e7d32; }
    .status-pending { background-color: #ffecb3; color: #ff8f00; }
    
    .label-style {
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
        color: #444;
        font-size: 14px;
    }
    
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #dce1e7 !important;
        border-radius: 4px !important;
        min-height: 42px !important;
    }
    
    .total-highlight {
        font-weight: 700;
        color: #2c3e50;
        font-size: 14px;
    }

    .form-group-custom {
        margin-bottom: 20px;
    }
    
    .date-range-inputs {
        display: none;
    }
    
    .loader-wrapper {
        padding: 100px;
        text-align: center;
    }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="report-dashboard">
            <!-- Filters Area -->
            <div class="filter-container">
                <form id="purchaseReportForm">
            <input type="hidden" name="m" value="{{ request()->m }}">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="label-style">Duration</label>
                        <select class="form-control select2" name="duration" id="duration">
                            <option value="last_6_months">Last 6 Months</option>
                            <option value="today">Today</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="this_year">This Financial Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    
                    <div class="row date-range-inputs" id="customDateRange">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="label-style">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="label-style">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group-custom">
                        <label class="label-style">Branch</label>
                        <select class="form-control select2" name="branch" id="branch">
                            <option value="all">All Branches</option>
                            @foreach($Branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label class="label-style">Status</label>
                        <select class="form-control select2" name="status" id="status">
                            <option value="">All Statuses</option>
                            <option value="1">Pending</option>
                            <option value="2">Approved</option>
                            <option value="3">1st Approve</option>
                        </select>
                    </div>
                    
                    <button type="button" class="btn btn-submit" id="btnSubmit">
                        <i class="fa fa-refresh"></i> GENERATE REPORT
                    </button>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="label-style">Item</label>
                        <select class="form-control select2" name="item_ids[]" multiple id="items" data-placeholder="Select Items">
                            @foreach($Items as $item)
                                <option value="{{ $item->id }}">{{ $item->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group-custom">
                        <label class="label-style">Brands</label>
                        <select class="form-control select2" name="brand_ids[]" multiple id="brands" data-placeholder="All Brands Selected">
                            @foreach($Brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 form-group-custom">
                        <div class="col-md-12 form-group-custom">
                            <label class="label-style">Product Type</label>
                            <select class="form-control select2" name="product_type" id="product_type" data-placeholder="Select Product Type">
                                <option value="">All Product Types</option>
                                @foreach($ProductTypes as $pt)
                                    <option value="{{ $pt->product_type_id }}">{{ $pt->type }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="col-md-12 form-group-custom">
                            <label class="label-style">Warehouse</label>
                            <select class="form-control select2" name="warehouse_ids[]" multiple id="warehouses" data-placeholder="Select Warehouse">
                                @foreach($Warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Report Table Area -->
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <div class="report-card">
        <div class="report-actions">
            <div class="search-wrapper">
                <i class="fa fa-search search-icon"></i>
                <input type="text" id="tableSearch" class="search-input" placeholder="Search Supplier or Invoice No...">
            </div>
            <button class="btn btn-export-excel" onclick="ExportToExcel('xlsx')"><i class="fa fa-file-excel-o"></i> Excel</button>
            <button class="btn btn-export-csv" onclick="exportView('mainReportTable')"><i class="fa fa-file-text-o"></i> CSV</button>
            <button class="btn btn-print" onclick="printView('reportContent')"><i class="fa fa-print"></i> Print</button>
        </div>
        
        <div id="reportContent">
            <div class="report-header">
               
                <h3 id="displayCompanyName">Purchase Report </h3>
                <p class="text-muted" id="displayDateRange" style="font-weight: 500; font-size: 15px;">Loading dynamic entries...</p>
            </div>
            
            <div class="table-responsive" id="ajaxContainer">
                <table class="table report-table" id="mainReportTable">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Brand</th>
                            <th>Item Details</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportData">
                        <tr>
                            <td colspan="8" class="loader-wrapper">
                                <i class="fa fa-spinner fa-spin fa-3x mb-3" style="color: #8dbd63;"></i><br>
                                Fetching Latest Purchase Entries...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
        });
        
        // Toggle Custom Date Range
        $('#duration').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#customDateRange').fadeIn();
            } else {
                $('#customDateRange').fadeOut();
            }
        });

        // Search functionality
        $('#tableSearch').on('keyup', function() {
            GetPurchaseReport();
        });
        
        // Handle Submit
        $('#btnSubmit').on('click', function() {
            GetPurchaseReport();
        });

        // Initial Load
        GetPurchaseReport();
    });

    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('mainReportTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Purchase Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }

    function GetPurchaseReport(url = '{{ route("getPurchaseReportDashboardAjax") }}') {
        let formData = $('#purchaseReportForm').serialize();
        let search = $('#tableSearch').val();
        formData += '&search=' + encodeURIComponent(search);

        $('#ajaxContainer').html(`
            <div class="loader-wrapper p-5 text-center">
                <i class="fa fa-spinner fa-spin fa-3x mb-3" style="color: #8dbd63;"></i><br>
                <h5>GENERATING REPORT DATA...</h5>
                <p class="text-muted">Analyzing entries for the selected Period & Branch.</p>
            </div>
        `);

        updateHeaderInfo();

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#ajaxContainer').html(response);
                
                // Add pagination click handler
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    let pageUrl = $(this).attr('href');
                    GetPurchaseReport(pageUrl);
                });
            },
            error: function() {
                $('#ajaxContainer').html('<div class="p-5 text-center text-danger">Error fetching report data. Please check your connection and try again.</div>');
            }
        });
    }

    function updateHeaderInfo() {
        let durationText = $("#duration option:selected").text();
        if ($("#duration").val() === 'custom') {
            let from = $('#from_date').val() || 'Start';
            let to = $('#to_date').val() || 'End';
            $('#displayDateRange').text(from + ' to ' + to);
        } else {
            $('#displayDateRange').text(durationText);
        }
    }
</script>
@endsection
