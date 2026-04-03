@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .qr-history-container {
        padding: 15px;
        background-color: #f8fafc;
        min-height: 100vh;
    }
    
    .filter-section-qr {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        padding: 30px;
        border-top: 4px solid #3498db;
    }
    
    .report-title-qr {
        font-weight: 800;
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-title-qr i {
        color: #3498db;
    }
    
    .filter-labels-qr {
        font-weight: 600;
        font-size: 13px;
        color: #444;
        margin-bottom: 8px;
        display: block;
    }
    
    .btn-submit-qr {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 10px 35px;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 14px;
        margin-top: 25px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
    }
    
    .btn-submit-qr:hover { 
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        opacity: 0.95;
    }
    
    .btn-submit-qr:active { transform: translateY(0); }
    
    .report-result-card-qr {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .export-actions-qr {
        padding: 20px 30px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        background-color: #fcfcfc;
        border-bottom: 1px solid #eee;
    }
    
    .btn-export-qr {
        padding: 8px 22px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-export-qr:hover { opacity: 0.9; transform: scale(1.02); }
    
    .btn-exc-qr { background-color: #27ae60; }
    .btn-csv-qr { background-color: #e67e22; }
    .btn-prt-qr { background-color: #7f8c8d; }
    
    #qrAjaxContainer {
        padding: 25px;
        min-height: 400px;
    }

    /* Status & Type Badges */
    .qr-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    
    .status-generated { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
    .status-scanned { background-color: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
    .status-salereturn { background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffcc80; }

    .qr-code-text {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 700;
        background: #f1f2f6;
        padding: 2px 6px;
        border-radius: 4px;
        color: #e74c3c;
    }

    .print-header-qr {
        display: none;
        text-align: center;
        padding: 40px 20px;
        border-bottom: 2px solid #333;
        margin-bottom: 30px;
    }

    @media print {
        .print-header-qr { display: block; }
        .filter-section-qr, .export-actions-qr, .btn-submit-qr { display: none; }
    }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="qr-history-container">
            <div class="filter-section-qr">
                <div class="report-title-qr">
                    <i class="fa fa-qrcode fa-lg"></i> Product QR Code History Report
                </div>
                <form id="qrReportForm">
                    <input type="hidden" name="m" value="{{ request()->m }}">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="filter-labels-qr">Duration</label>
                                <select class="form-control select2" name="duration" id="qrDuration">
                                    <option value="this_month">This Month</option>
                                    <option value="today">Today</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div id="qrCustomRange" style="display: none; margin-top: 15px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="date" name="from_date" class="form-control input-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" name="to_date" class="form-control input-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="filter-labels-qr">Product</label>
                                <select class="form-control select2" name="product">
                                    <option value="">All Products</option>
                                    @foreach($Products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }} ({{ $product->sku_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="filter-labels-qr">Scan Type</label>
                                <select class="form-control select2" name="scan_type">
                                    <option value="">All Types</option>
                                    <option value="1">Generated</option>
                                    <option value="0">Scanned</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="filter-labels-qr">User</label>
                                <select class="form-control select2" name="user">
                                    <option value="">All Users</option>
                                    @foreach($Users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                           <div class="form-group">
                                <label class="filter-labels-qr">Sort By</label>
                                <select class="form-control select2" name="sort_by">
                                    <option value="sb.created_at-desc">Date (Newest)</option>
                                    <option value="sb.created_at-asc">Date (Oldest)</option>
                                    <option value="si.product_name-asc">Product Name (A-Z)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 text-right">
                            <button type="button" class="btn-submit-qr" id="btnQrSubmit">
                                <i class="fa fa-search"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="report-result-card-qr">
                <div class="export-actions-qr">
                    <button class="btn-export-qr btn-exc-qr" onclick="ExportQrToExcel('xlsx')">
                        <i class="fa fa-file-excel-o"></i> Excel
                    </button>
                    <button class="btn-export-qr btn-prt-qr" onclick="printQRView('qrPrintArea')">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
                
                <div id="qrPrintArea">
                    <div class="print-header-qr">
                        <h2 style="margin:0; color:#2c3e50;">BRANDS UNLIMITED (PVT) LTD</h2>
                        <h3 style="margin:5px 0; color:#7f8c8d;">Product QR Code History Report</h3>
                        <p style="margin:10px 0; font-size:12px; color:#95a5a6;">Printed on: {{ date('d-M-Y H:i A') }}</p>
                    </div>
                    
                    <div class="table-responsive" id="qrAjaxContainer">
                        <!-- AJAX Table Content -->
                        <div class="text-center p-5">
                            <i class="fa fa-qrcode fa-5x text-light-grey" style="color:#eee"></i>
                            <p class="mt-3 text-muted">Click submit to load QR code history</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if(typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({ width: '100%' });
        }
        
        $('#qrDuration').on('change', function() {
            if($(this).val() === 'custom') $('#qrCustomRange').fadeIn();
            else $('#qrCustomRange').fadeOut();
        });
        
        $('#btnQrSubmit').on('click', function() { GetQRHistory(); });
        
        // Initial load
        GetQRHistory();
    });

    function GetQRHistory(url = '{{ route("getQRCodeHistoryAjax") }}') {
        let formData = $('#qrReportForm').serialize();
        $('#qrAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x" style="color:#3498db"></i><p class="mt-4 font-weight-bold">Scanning lifecycle history...</p></div>');

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#qrAjaxContainer').html(response);
                
                // Re-bind pagination clicks
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    GetQRHistory($(this).attr('href'));
                });
            },
            error: function() {
                $('#qrAjaxContainer').html('<div class="text-center p-5 text-danger"><i class="fa fa-exclamation-triangle fa-2x"></i><br>Error fetching history.</div>');
            }
        });
    }

    function ExportQrToExcel(type) {
        var elt = document.getElementById('qrDataTable');
        if(!elt) { alert('No data available to export'); return; }
        var wb = XLSX.utils.table_to_book(elt, { sheet: "QR_History" });
        XLSX.writeFile(wb, 'QR_Code_History_Report_{{ date('Ymd') }}.' + type);
    }

    function printQRView(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
</script>
@endsection
