@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .credit-note-container {
        padding: 15px;
        background-color: #f4f7f6;
        min-height: 100vh;
    }
    
    .filter-section-top {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        padding: 25px;
    }
    
    .report-title-main {
        font-weight: 700;
        font-size: 15px;
        color: #2c3e50;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #edeff0;
        padding-bottom: 12px;
    }
    
    .filter-labels-custom {
        font-weight: 600;
        font-size: 13px;
        color: #555;
        margin-bottom: 10px;
    }
    
    .btn-submit-cn {
        background-color: #8dbd63;
        color: white;
        padding: 8px 30px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        margin-top: 5px;
        transition: all 0.3s;
    }
    
    .btn-submit-cn:hover { opacity: 0.9; transform: translateY(-1px); }
    
    .report-result-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .export-actions-cn {
        padding: 15px 25px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background-color: #f9f9f9;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    
    .btn-export-cn {
        padding: 7px 20px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        color: white;
    }
    
    .btn-exc-cn { background-color: #4fc3f7; }
    .btn-csv-cn { background-color: #ffb74d; }
    .btn-prt-cn { background-color: #81c784; }
    
    .print-header-cn {
        text-align: center;
        padding: 40px 20px;
    }
    
    .print-header-cn h3 { font-size: 24px; font-weight: 700; margin: 0; color: #333; }
    .print-header-cn h4 { font-size: 18px; font-weight: 500; margin: 10px 0; color: #666; }
    
    .table-cn-main {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    
    .table-cn-main thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        border: 1px solid #dee2e6;
        padding: 12px 8px;
        text-align: center;
    }
    
    .table-cn-main tbody td {
        border: 1px solid #dee2e6;
        padding: 10px 8px;
        text-align: center;
    }
    
    .status-badge-cn {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .badge-approved-cn { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-pending-cn { background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="credit-note-container">
            <div class="filter-section-top">
                <div class="report-title-main">PURCHASE CREDIT NOTE</div>
                <form id="cnReportForm">
                    <input type="hidden" name="m" value="{{ request()->m }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="filter-labels-custom">Duration</label>
                                <select class="form-control select2" name="duration" id="cnDuration">
                                    <option value="this_month">This Month</option>
                                    <option value="today">Today</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div id="cnCustomRange" style="display: none; margin-top: 10px;">
                                <input type="date" name="from_date" class="form-control mb-2">
                                <input type="date" name="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="filter-labels-custom">Principle</label>
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
                                <label class="filter-labels-custom">Branch</label>
                                <select class="form-control select2" name="branch">
                                    <option value="">All Branches</option>
                                    @foreach($Branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="padding-top: 30px;">
                            <button type="button" class="btn-submit-cn" id="btnCnSubmit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="report-result-card">
                <div class="export-actions-cn">
                    <button class="btn-export-cn btn-exc-cn" onclick="ExportCnToExcel('xlsx')">Export to Excel</button>
                    <button class="btn-export-cn btn-csv-cn" onclick="exportView('cnDataTable')">Export to CSV</button>
                    <button class="btn-export-cn btn-prt-cn" onclick="printView('cnPrintArea')">Print</button>
                </div>
                
                <div id="cnPrintArea">
                    <div class="print-header-cn">
                       
                        <h4>Purchase Credit Note Report</h4>
                        <p id="cnDateDisplay"></p>
                    </div>
                    
                    <div class="table-responsive" id="cnAjaxContainer" style="padding: 0 25px 25px 25px;">
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
        $('#cnDuration').on('change', function() {
            if($(this).val() === 'custom') $('#cnCustomRange').slideDown();
            else $('#cnCustomRange').slideUp();
        });
        $('#btnCnSubmit').on('click', function() { GetCreditNotes(); });
        GetCreditNotes();
    });

    function GetCreditNotes(url = '{{ route("getPurchaseCreditNoteAjax") }}') {
        let formData = $('#cnReportForm').serialize();
        $('#cnAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i><br><p class="mt-3">Loading Credit Notes...</p></div>');

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#cnAjaxContainer').html(response);
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    GetCreditNotes($(this).attr('href'));
                });
            },
            error: function() { $('#cnAjaxContainer').html('<div class="text-center p-5 text-danger">Error fetching credit notes.</div>'); }
        });
    }

    function ExportCnToExcel(type, fn, dl) {
        var elt = document.getElementById('cnDataTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "CreditNote" });
        return dl ? XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Purchase Credit Notes <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }
</script>
@endsection
