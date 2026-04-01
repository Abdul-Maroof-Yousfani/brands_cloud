@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .pending-payment-container {
        padding: 15px;
        background-color: #f4f7f6;
        min-height: 100vh;
    }
    
    .payment-filter-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        padding: 25px;
    }
    
    .payment-title {
        font-weight: 700;
        font-size: 15px;
        color: #2c3e50;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #edeff0;
        padding-bottom: 12px;
    }
    
    .payment-label {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        margin-bottom: 10px;
    }
    
    .btn-payment-submit {
        background-color: #8dbd63;
        color: white;
        padding: 8px 30px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        margin-top: 5px;
        transition: all 0.3s;
    }
    
    .btn-payment-submit:hover { opacity: 0.9; transform: translateY(-1px); }
    
    .payment-report-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .payment-actions-bar {
        padding: 15px 25px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background-color: #f9f9f9;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    
    .btn-pay-action {
        padding: 7px 20px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        color: white;
    }
    
    .btn-pay-excel { background-color: #4fc3f7; }
    .btn-pay-csv { background-color: #ffb74d; }
    .btn-pay-print { background-color: #81c784; }
    
    .payment-header-print {
        text-align: center;
        padding: 40px 20px;
    }
    
    .payment-header-print h3 { font-size: 24px; font-weight: 700; margin: 0; color: #333; }
    .payment-header-print h4 { font-size: 18px; font-weight: 500; margin: 10px 0; color: #666; }
    
    .payment-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    
    .payment-table thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        border: 1px solid #dee2e6;
        padding: 15px 10px;
        text-align: center;
    }
    
    .payment-table tbody td {
        border: 1px solid #dee2e6;
        padding: 12px 10px;
        text-align: center;
    }
    
    .status-pill {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .status-paid { background-color: #e8f5e9; color: #2e7d32; }
    .status-unpaid { background-color: #ffebee; color: #c62828; }
    .status-partial { background-color: #fff3e0; color: #ef6c00; }
    
    .pending-amount-hl {
        font-weight: 700;
        color: #c62828;
    }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="pending-payment-container">
            <div class="payment-filter-card">
                <div class="payment-title">PENDING PURCHASE PAYMENTS</div>
                <form id="pendingPaymentForm">
                    <input type="hidden" name="m" value="{{ request()->m }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="payment-label">Duration</label>
                                <select class="form-control select2" name="duration" id="pmDuration">
                                    <option value="this_month">This Month</option>
                                    <option value="today">Today</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div id="pmCustomRange" style="display: none; margin-top: 10px;">
                                <input type="date" name="from_date" class="form-control mb-2">
                                <input type="date" name="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="payment-label">Principal</label>
                                <select class="form-control select2" name="principal">
                                    <option value="">All Principals</option>
                                    @foreach($Principals as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="payment-label">Payment Status</label>
                                <select class="form-control select2" name="status">
                                    <option value="pending">Pending (All Outstanding)</option>
                                    <option value="unpaid">Unpaid Only</option>
                                    <option value="partial">Partial Only</option>
                                    <option value="paid">Fully Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="padding-top: 30px;">
                            <button type="button" class="btn-payment-submit" id="btnPmSubmit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="payment-report-card">
                <div class="payment-actions-bar">
                    <button class="btn-pay-action btn-pay-excel" onclick="ExportPmToExcel('xlsx')">Export to Excel</button>
                    <button class="btn-pay-action btn-pay-csv" onclick="exportView('pmDataTable')">Export to CSV</button>
                    <button class="btn-pay-action btn-pay-print" onclick="printView('pmPrintArea')">Print</button>
                </div>
                
                <div id="pmPrintArea">
                    <div class="payment-header-print">
                     
                        <h4>Pending Purchase Payment Report</h4>
                        <p id="pmDateDisplay">01 Apr 2026 - 01 Apr 2026</p>
                    </div>
                    
                    <div class="table-responsive" id="pmAjaxContainer" style="padding: 0 25px 25px 25px;">
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
        $('#pmDuration').on('change', function() {
            if($(this).val() === 'custom') $('#pmCustomRange').slideDown();
            else $('#pmCustomRange').slideUp();
        });
        $('#btnPmSubmit').on('click', function() { GetPendingPayments(); });
        GetPendingPayments();
    });

    function GetPendingPayments(url = '{{ route("getPendingPurchasePaymentAjax") }}') {
        let formData = $('#pendingPaymentForm').serialize();
        $('#pmAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i><br><p class="mt-3">Fetching Outstanding Invoices...</p></div>');

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#pmAjaxContainer').html(response);
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    GetPendingPayments($(this).attr('href'));
                });
            },
            error: function() { $('#pmAjaxContainer').html('<div class="text-center p-5 text-danger">Error loading pending payments.</div>'); }
        });
    }

    function ExportPmToExcel(type, fn, dl) {
        var elt = document.getElementById('pmDataTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "Pending" });
        return dl ? XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Pending Purchase Payments <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }
</script>
@endsection
