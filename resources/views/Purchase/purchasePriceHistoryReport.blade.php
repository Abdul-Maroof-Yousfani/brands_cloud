@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .price-history-container {
        padding: 10px;
        background-color: #f8f9fb;
        min-height: 100vh;
    }
    
    .price-filter-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        padding: 25px;
    }
    
    .price-title {
        font-weight: 700;
        font-size: 15px;
        color: #333;
        margin-bottom: 20px;
        text-transform: uppercase;
        border-left: 4px solid #0073b7;
        padding-left: 10px;
    }
    
    .price-label {
        font-weight: 600;
        font-size: 13px;
        color: #444;
        margin-bottom: 8px;
    }
    
    .btn-price-submit {
        background-color: #8dbd63;
        color: white;
        padding: 8px 35px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .price-report-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .price-actions-bar {
        padding: 15px 25px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background-color: #fcfcfc;
        border-bottom: 1px solid #efefef;
    }
    
    .btn-ph-action {
        padding: 7px 18px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        color: white;
    }
    
    .btn-ph-excel { background-color: #4fc3f7; }
    .btn-ph-csv { background-color: #ffb74d; }
    .btn-ph-print { background-color: #81c784; }
    
    .price-header-print {
        text-align: center;
        padding: 35px 20px;
    }
    
    .price-header-print h3 { font-weight: 700; font-size: 22px; margin: 0; }
    .price-header-print h4 { font-weight: 500; font-size: 18px; margin: 8px 0; color: #555; }
    
    .price-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .price-table thead th {
        background-color: #f1f4f9;
        color: #333;
        font-weight: 700;
        border: 1px solid #e1e8ef;
        padding: 12px;
        text-align: center;
    }
    
    .price-table tbody td {
        border: 1px solid #e1e8ef;
        padding: 10px;
        text-align: center;
        color: #444;
    }
    
    .price-increase { color: #d32f2f; font-weight: 700; }
    .price-decrease { color: #388e3c; font-weight: 700; }
    .price-stable { color: #777; }
    
    .item-group-header {
        background-color: #f9fbff;
        font-weight: 700;
        text-align: left !important;
        padding-left: 20px !important;
        color: #0073b7 !important;
        font-size: 14px;
    }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="price-history-container">
            <div class="price-filter-card">
                <div class="price-title">PURCHASE PRICE HISTORY REPORT</div>
                <form id="priceHistoryForm">
                    <input type="hidden" name="m" value="{{ request()->m }}">
                    <div class="row">
                        <!-- Left filters -->
                        <div class="col-md-6">
                            <div class="form-group"><label class="price-label">Stores</label>
                                <select class="form-control select2" name="store_ids[]" multiple data-placeholder="All Stores">
                                    @foreach($Stores as $s) <option value="{{$s->id}}">{{$s->name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="form-group"><label class="price-label">Employee</label>
                                <select class="form-control select2" name="employees[]" multiple data-placeholder="All Employees">
                                    @foreach($Employees as $e) <option value="{{$e->id}}">{{$e->name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="form-group"><label class="price-label">Duration</label>
                                <select class="form-control select2" name="duration" id="phDuration">
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="today">Today</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="row" id="phCustomRange" style="display: none; margin-bottom: 15px;">
                                <div class="col-md-6"><input type="date" name="from_date" class="form-control"></div>
                                <div class="col-md-6"><input type="date" name="to_date" class="form-control"></div>
                            </div>
                            <div class="form-group"><label class="price-label">Branch</label>
                                <select class="form-control select2" name="branch_ids[]" multiple data-placeholder="All Branches">
                                    @foreach($Branches as $b) <option value="{{$b->id}}">{{$b->branch_name}}</option> @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn-price-submit" id="btnPhSubmit">Submit</button>
                        </div>
                        
                        <!-- Right filters -->
                        <div class="col-md-6">
                            <div class="form-group"><label class="price-label">Product Type</label>
                                <select class="form-control select2" name="product_type">
                                    <option value="1">Product</option>
                                    <option value="2">Raw Material</option>
                                </select>
                            </div>
                            <div class="form-group"><label class="price-label">Item</label>
                                <select class="form-control select2" name="item_ids[]" multiple data-placeholder="All Items">
                                    @foreach($Items as $i) <option value="{{$i->id}}">{{$i->product_name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="form-group"><label class="price-label">Brands</label>
                                <select class="form-control select2" name="brand_ids[]" multiple data-placeholder="All Brands">
                                    @foreach($Brands as $br) <option value="{{$br->id}}">{{$br->name}}</option> @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><div class="form-group"><label class="price-label">Type</label>
                                    <select class="form-control select2" name="types[]" multiple data-placeholder="All Types">
                                        @foreach($Types as $t) <option value="{{$t->type_id}}">{{$t->name}}</option> @endforeach
                                    </select>
                                </div></div>
                                <div class="col-md-6"><div class="form-group"><label class="price-label">Warehouse</label>
                                    <select class="form-control select2" name="warehouse_ids[]" multiple data-placeholder="All Warehouses">
                                        @foreach($Warehouses as $w) <option value="{{$w->id}}">{{$w->name}}</option> @endforeach
                                    </select>
                                </div></div>
                            </div>
                          
                        </div>
                    </div>
                </form>
            </div>

            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="price-report-card">
                <div class="price-actions-bar">
                    <button class="btn-ph-action btn-ph-excel" onclick="ExportPhToExcel('xlsx')">Export to Excel</button>
                    <button class="btn-ph-action btn-ph-csv" onclick="exportView('phDataTable')">Export to CSV</button>
                    <button class="btn-ph-action btn-ph-print" onclick="printView('phPrintArea')">Print</button>
                </div>
                
                <div id="phPrintArea">
                    <div class="price-header-print">
                      
                        <h4>Purchase Price History Report</h4>
                        <p id="phDateText">01 Apr 2026</p>
                    </div>
                    
                    <div class="table-responsive" id="phAjaxContainer" style="padding: 0 25px 25px 25px;">
                        <!-- AJAX Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        $('#phDuration').on('change', function() {
            if($(this).val() === 'custom') $('#phCustomRange').fadeIn();
            else $('#phCustomRange').fadeOut();
        });
        $('#btnPhSubmit').on('click', function() { GetPriceHistory(); });
        GetPriceHistory();
    });

    function GetPriceHistory(url = '{{ route("getPurchasePriceHistoryAjax") }}') {
        let formData = $('#priceHistoryForm').serialize();
        $('#phAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-refresh fa-spin fa-3x"></i><br><p class="mt-2">Analyzing Price Trends...</p></div>');

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#phAjaxContainer').html(response);
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    GetPriceHistory($(this).attr('href'));
                });
            },
            error: function() { $('#phAjaxContainer').html('<div class="text-center p-5 text-danger">Error fetching history.</div>'); }
        });
    }

    function ExportPhToExcel(type, fn, dl) {
        var elt = document.getElementById('phDataTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "History" });
        return dl ? XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Purchase Price History <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }
</script>
@endsection
