@extends('layouts.default')
@section('content')
@include('select2')

<style>
    .filter-section {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .filter-label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }
    .report-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .report-header h2 {
        margin: 5px 0;
        font-weight: bold;
    }
    .report-header h3 {
        margin: 5px 0;
        font-size: 18px;
    }
    .report-header p {
        margin: 5px 0;
        font-size: 16px;
    }
    .table-responsive {
        background: #fff;
        padding: 10px;
        border-radius: 5px;
    }
    .btn-submit {
        background-color: #7cb342;
        color: white;
        font-weight: bold;
        padding: 8px 25px;
        border: none;
        border-radius: 4px;
        margin-top: 10px;
    }
    .btn-submit:hover {
        background-color: #689f38;
        color: white;
    }
    .export-buttons {
        margin-bottom: 15px;
        text-align: right;
    }
    .export-buttons .btn {
        margin-left: 5px;
    }
</style>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="well_N">
        <div class="filter-section">
            <form id="filterForm">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="filter-label">Stores</label>
                            <select name="customer_ids[]" id="customer_ids" class="form-control select2" multiple>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="filter-label">Employee</label>
                            <select name="employee_ids[]" id="employee_ids" class="form-control select2" multiple>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->sub_department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="filter-label">Duration</label>
                            <select id="duration" class="form-control">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="last_6_months">Last 6 Months</option>
                                <option value="this_year">This Year</option>
                                <option value="custom" selected>Custom Range</option>
                            </select>
                        </div>
                        <div id="custom_dates" class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">From Date</label>
                                    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ date('Y-m-d', strtotime('-6 months')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">To Date</label>
                                    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="get_report_data()" class="btn btn-submit">Submit</button>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-7">
                     
                        <div class="form-group">
                            <label class="filter-label">Item</label>
                            <select name="item_ids[]" id="item_ids" class="form-control select2" multiple>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="filter-label">Brands</label>
                            <select name="brand_ids[]" id="brand_ids" class="form-control select2" multiple>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Type</label>
                                    <select name="type_ids[]" id="type_ids" class="form-control select2" multiple>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Warehouse</label>
                                    <select name="warehouse_ids[]" id="warehouse_ids" class="form-control select2" multiple>
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="filter-label">Zone</label>
                            <select name="zone_ids[]" id="zone_ids" class="form-control select2" multiple>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="export-buttons">
            <button class="btn btn-info" onclick="exportExcel()">Export to Excel</button>
            <button class="btn btn-warning" onclick="exportCSV()">Export to CSV</button>
            <button class="btn btn-success" onclick="window.print()">Print</button>
        </div>

        <div id="reportContainer">
            <div class="report-header">
                <h2>Brands Unlimited (Pvt) Ltd</h2>
                <h3>Sales Return Journal</h3>
                <p id="dateRangeDisplay">{{ date('d M Y', strtotime('-6 months')) }} - {{ date('d M Y') }}</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="reportTable">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th>Bill #</th>
                            <th>Date</th>
                            <th>Ref Document</th>
                            <th>Customer</th>
                            <th>Notes</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>HS Code</th>
                            <th>Ctn</th>
                            <th>Pcs</th>
                            <th>Packing</th>
                            <th>Total Pcs</th>
                            <th>Unit Price</th>
                            <th>Gross Amount</th>
                            <th>Disc %</th>
                            <th>Disc</th>
                            <th>Disc 2 %</th>
                            <th>Disc 2</th>
                            <th>Tax %</th>
                            <th>Tax</th>
                            <th>Net Amount</th>
                        </tr>
                    </thead>
                    <tbody id="reportBody">
                        <tr>
                            <td colspan="20" class="text-center">Please click Submit to generate report</td>
                        </tr>
                    </tbody>
                    <tfoot id="reportFooter">
                        <!-- Totals will be here -->
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: 'All'
        });

        $('#duration').change(function() {
            var val = $(this).val();
            var from = $('#from_date');
            var to = $('#to_date');
            var today = new Date();
            
            if (val == 'today') {
                from.val(formatDate(today));
                to.val(formatDate(today));
            } else if (val == 'yesterday') {
                var yesterday = new Date();
                yesterday.setDate(today.getDate() - 1);
                from.val(formatDate(yesterday));
                to.val(formatDate(yesterday));
            } else if (val == 'last_6_months') {
                var sixMonthsAgo = new Date();
                sixMonthsAgo.setMonth(today.getMonth() - 6);
                from.val(formatDate(sixMonthsAgo));
                to.val(formatDate(today));
            }
            // Add other duration logic as needed
        });
    });

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    function get_report_data() {
        var formData = $('#filterForm').serialize();
        
        // Update header date range
        var from = $('#from_date').val();
        var to = $('#to_date').val();
        $('#dateRangeDisplay').text(from + ' - ' + to);

        // Get 'm' from URL
        const urlParams = new URLSearchParams(window.location.search);
        var m = urlParams.get('m');
        
        $.ajax({
            url: "{{ route('sale_return_v2.view') }}?" + window.location.search.substring(1),
            method: 'GET',
            data: formData,
            beforeSend: function() {
                $('#reportBody').html('<tr><td colspan="20" class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</td></tr>');
            },
            success: function(response) {
                $('#reportBody').html(response);
                calculateTotals();
            },
            error: function(xhr) {
                $('#reportBody').html('<tr><td colspan="20" class="text-center text-danger">Error loading data.</td></tr>');
            }
        });
    }

    function calculateTotals() {
        // Logic to calculate totals from table rows
    }

    function exportExcel() {
        // Excel export logic
    }
</script>

@endsection
