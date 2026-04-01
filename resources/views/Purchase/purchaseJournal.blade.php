@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .purchase-journal-container {
        padding: 10px;
        background-color: #f8f9fb;
        min-height: 100vh;
    }
    
    .journal-filter-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .journal-filter-header {
        background-color: #0073b7;
        color: white;
        padding: 10px 15px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
    }
    
    .journal-filter-body {
        padding: 20px;
    }
    
    .journal-report-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 0;
    }
    
    .journal-report-actions {
        padding: 20px 30px 10px 30px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .btn-journal-action {
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        color: white;
    }
    
    .btn-excel { background-color: #4fc3f7; }
    .btn-csv { background-color: #ffb74d; }
    .btn-print { background-color: #81c784; }
    
    .journal-report-header {
        text-align: center;
        padding: 20px;
    }
    
    .journal-report-header h3 {
        margin: 0;
        font-weight: 400;
        color: #333;
        font-size: 20px;
    }
    
    .journal-report-header h4 {
        margin: 5px 0;
        font-weight: 600;
        color: #333;
        font-size: 18px;
    }
    
    .journal-report-header p {
        color: #777;
        font-size: 14px;
    }
    
    .journal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    
    .journal-table thead th {
        background-color: #f1f4f7;
        color: #333;
        font-weight: 600;
        border: 1px solid #dee2e6;
        padding: 10px 5px;
        text-align: center;
    }
    
    .journal-table tbody td {
        border: 1px solid #dee2e6;
        padding: 8px 5px;
        text-align: center;
        color: #444;
    }
    
    .journal-table tbody tr:hover {
        background-color: #f9f9f9;
    }
    
    .journal-label {
        font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    
    .btn-journal-submit {
        background-color: #8dbd63;
        color: white;
        padding: 8px 30px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .search-wrapper-journal {
        margin-right: auto;
        position: relative;
        width: 250px;
    }
    
    .search-input-journal {
        width: 100%;
        padding: 7px 10px 7px 30px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 13px;
    }
    
    .search-icon-journal {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .pagination-journal {
        padding: 15px 30px;
    }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="purchase-journal-container">
            <!-- Filter Section -->
            <div class="journal-filter-card">
                <div class="journal-filter-header">PURCHASE JOURNAL</div>
                <div class="journal-filter-body">
                    <form id="journalFilterForm">
                        <input type="hidden" name="m" value="{{ request()->m }}">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="journal-label">Principle</label>
                                    <select class="form-control select2" name="principals[]" multiple data-placeholder="Select Principle">
                                        @foreach($Principals as $principal)
                                            <option value="{{ $principal->id }}">{{ $principal->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="journal-label">Duration</label>
                                    <select class="form-control select2" name="duration" id="journalDuration">
                                        <option value="last_6_months">Last 6 Months</option>
                                        <option value="today">Today</option>
                                        <option value="last_30_days">Last 30 Days</option>
                                        <option value="this_year">This Financial Year</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                                <div class="row" id="journalCustomRange" style="display: none;">
                                    <div class="col-md-6">
                                        <input type="date" name="from_date" class="form-control" placeholder="From">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" name="to_date" class="form-control" placeholder="To">
                                    </div>
                                </div>
                                <div style="margin-top: 15px;">
                                    <button type="button" class="btn-journal-submit" id="btnJournalSubmit">Submit</button>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="journal-label">Product Type</label>
                                            <select class="form-control select2" name="product_type">
                                                <option value="">All Product Types</option>
                                                <option value="1">Product</option>
                                                <option value="2">Raw Material</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="journal-label">Item</label>
                                            <select class="form-control select2" name="item_ids[]" multiple data-placeholder="All Items Selected">
                                                @foreach($Items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="journal-label">Brands</label>
                                            <select class="form-control select2" name="brand_ids[]" multiple data-placeholder="All Brands Selected">
                                                @foreach($Brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="journal-label">Type</label>
                                            <select class="form-control select2" name="types[]" multiple data-placeholder="All Types Selected">
                                                @foreach($Types as $type)
                                                    <option value="{{ $type->type_id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="journal-label">Warehouse</label>
                                            <select class="form-control select2" name="warehouse_ids[]" multiple data-placeholder="All Warehouses Selected">
                                                @foreach($Warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Results Section -->
            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="journal-report-card">
                <div class="journal-report-actions">
                    <div class="search-wrapper-journal">
                        <i class="fa fa-search search-icon-journal"></i>
                        <input type="text" id="journalSearch" class="search-input-journal" placeholder="Search Bill or Supplier...">
                    </div>
                    <button class="btn-journal-action btn-excel" onclick="ExportJournalToExcel('xlsx')">Export to Excel</button>
                    <button class="btn-journal-action btn-csv" onclick="exportView('journalDataTable')">Export to CSV</button>
                    <button class="btn-journal-action btn-print" onclick="printView('journalPrintArea')">Print</button>
                </div>
                
                <div id="journalPrintArea">
                    <div class="journal-report-header">
                       
                        <h4>Purchase Journal</h4>
                        <p id="journalDateHeader">01 Oct 2025 - 01 Apr 2026</p>
                    </div>
                    
                    <div class="table-responsive" id="journalAjaxContainer">
                        <table class="journal-table" id="journalDataTable">
                            <thead>
                                <tr>
                                    <th>Bill #</th>
                                    <th>Invoice # (Custom)</th>
                                    <th>Date</th>
                                    <th>Principle</th>
                                    <th>Notes</th>
                                    <th>Item Name</th>
                                    <th>Ctn</th>
                                    <th>Pcs</th>
                                    <th>Packing</th>
                                    <th>Total Pcs</th>
                                    <th>Unit Price</th>
                                    <th>Gross Amount</th>
                                    <th>Disc %</th>
                                    <th>Disc</th>
                                    <th>Tax %</th>
                                    <th>Tax</th>
                                    <th>Net Amount</th>
                                </tr>
                            </thead>
                            <tbody id="journalBody">
                                <!-- AJAX Content -->
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
        $('.select2').select2({ width: '100%' });
        
        $('#journalDuration').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#journalCustomRange').fadeIn();
            } else {
                $('#journalCustomRange').fadeOut();
            }
        });

        $('#btnJournalSubmit').on('click', function() {
            GetPurchaseJournal();
        });

        $('#journalSearch').on('keyup', function() {
            GetPurchaseJournal();
        });

        // Initial load
        GetPurchaseJournal();
    });

    function GetPurchaseJournal(url = '{{ route("getPurchaseJournalAjax") }}') {
        let formData = $('#journalFilterForm').serialize();
        let search = $('#journalSearch').val();
        formData += '&search=' + encodeURIComponent(search);

        $('#journalAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x" style="color:#0073b7"></i><br><p class="mt-2">Generating Purchase Journal...</p></div>');

        // Update header
        let durationText = $("#journalDuration option:selected").text();
        $('#journalDateHeader').text(durationText);

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#journalAjaxContainer').html(response);
                
                // Handle pagination click
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    GetPurchaseJournal($(this).attr('href'));
                });
            },
            error: function() {
                $('#journalAjaxContainer').html('<div class="text-center p-5 text-danger">Error loading data.</div>');
            }
        });
    }

    function ExportJournalToExcel(type, fn, dl) {
        var elt = document.getElementById('journalDataTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "Journal" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Purchase Journal <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }
</script>
@endsection
