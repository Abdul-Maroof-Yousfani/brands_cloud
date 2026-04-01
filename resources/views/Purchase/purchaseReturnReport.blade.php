@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .return-report-container {
        padding: 10px;
        background-color: #f8f9fb;
        min-height: 100vh;
    }
    
    .return-filter-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        padding: 20px;
    }
    
    .return-title {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin-bottom: 20px;
        text-transform: uppercase;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .return-label {
        font-weight: 600;
        font-size: 13px;
        color: #444;
        margin-bottom: 8px;
    }
    
    .btn-return-submit {
        background-color: #8dbd63;
        color: white;
        padding: 6px 25px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        margin-top: 10px;
    }
    
    .return-data-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        padding: 0;
    }
    
    .return-actions-row {
        padding: 15px 25px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background-color: #fcfcfc;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    
    .btn-action-pr {
        padding: 6px 15px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        color: white;
    }
    
    .btn-action-excel { background-color: #4fc3f7; }
    .btn-action-csv { background-color: #ffb74d; }
    .btn-action-print { background-color: #81c784; }
    
    .return-header-print {
        text-align: center;
        padding: 30px 20px;
    }
    
    .return-header-print h3 {
        font-weight: 600;
        font-size: 20px;
        margin: 0;
    }
    
    .return-header-print h4 {
        font-weight: 500;
        font-size: 18px;
        margin: 10px 0;
    }
    
    .return-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .return-table thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 600;
        border: 1px solid #e9ecef;
        padding: 12px 10px;
        text-align: center;
    }
    
    .return-table tbody td {
        border: 1px solid #e9ecef;
        padding: 10px;
        text-align: center;
        color: #555;
    }
    
    .status-badge-pr {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-approved { background-color: #e8f5e9; color: #2e7d32; }
    .status-pending { background-color: #fff3e0; color: #ef6c00; }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="return-report-container">
            <div class="return-filter-card">
                <div class="return-title">PURCHASE RETURN REPORT</div>
                <form id="prReportForm">
                    <input type="hidden" name="m" value="{{ request()->m }}">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="return-label">Duration</label>
                                <select class="form-control select2" name="duration" id="prDuration">
                                    <option value="this_month">This Month</option>
                                    <option value="today">Today</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="row" id="prCustomRange" style="display: none;">
                                <div class="col-md-6"><input type="date" name="from_date" class="form-control"></div>
                                <div class="col-md-6"><input type="date" name="to_date" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="return-label">Principle</label>
                                <select class="form-control select2" name="principal">
                                    <option value="">All</option>
                                    @foreach($Principals as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-top: 25px;">
                            <button type="button" class="btn-return-submit" id="btnPrSubmit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="return-data-card">
                <div class="return-actions-row">
                    <button class="btn-action-pr btn-action-excel" onclick="ExportPrToExcel('xlsx')">Export to Excel</button>
                    <button class="btn-action-pr btn-action-csv" onclick="exportView('prDataTable')">Export to CSV</button>
                    <button class="btn-action-pr btn-action-print" onclick="printView('prPrintArea')">Print</button>
                </div>
                
                <div id="prPrintArea">
                    <div class="return-header-print">
                     
                        <h4>Purchase Return Report</h4>
                        <p id="prDateDisplay">01 Apr 2026 - 01 Apr 2026</p>
                    </div>
                    
                    <div class="table-responsive" id="prAjaxContainer" style="padding: 0 25px 25px 25px;">
                        <table class="return-table" id="prDataTable">
                            <thead>
                                <tr>
                                    <th>Return Invoice #</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                    <th>Item Details</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="prBody">
                                <!-- AJAX Data -->
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
        
        $('#prDuration').on('change', function() {
            if ($(this).val() === 'custom') $('#prCustomRange').fadeIn();
            else $('#prCustomRange').fadeOut();
        });

        $('#btnPrSubmit').on('click', function() {
            GetPurchaseReturnReport();
        });

        GetPurchaseReturnReport();
    });

    function GetPurchaseReturnReport(url = '{{ route("getPurchaseReturnReportAjax") }}') {
        let formData = $('#prReportForm').serialize();
        $('#prAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Generating Report...</div>');

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#prAjaxContainer').html(response);
                
                // Pagination handler
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    GetPurchaseReturnReport($(this).attr('href'));
                });
            },
            error: function() {
                $('#prAjaxContainer').html('<div class="text-center p-5 text-danger">Error loading report.</div>');
            }
        });
    }

    function ExportPrToExcel(type, fn, dl) {
        var elt = document.getElementById('prDataTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Purchase Return Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }
</script>
@endsection
