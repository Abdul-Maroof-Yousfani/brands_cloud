@extends('layouts.default')

@section('content')
@include('select2')

<style>
    .debit-note-container {
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
    
    .btn-submit-dn {
        background-color: #8dbd63;
        color: white;
        padding: 8px 30px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        margin-top: 5px;
        transition: all 0.3s;
    }
    
    .btn-submit-dn:hover { opacity: 0.9; transform: translateY(-1px); }
    
    .report-result-card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .export-actions-dn {
        padding: 15px 25px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background-color: #f9f9f9;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    
    .btn-export-dn {
        padding: 7px 20px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        color: white;
    }
    
    .btn-exc-dn { background-color: #4fc3f7; }
    .btn-csv-dn { background-color: #ffb74d; }
    .btn-prt-dn { background-color: #81c784; }
    
    .print-header-dn {
        text-align: center;
        padding: 40px 20px;
    }
    
    .print-header-dn h3 { font-size: 24px; font-weight: 700; margin: 0; color: #333; }
    .print-header-dn h4 { font-size: 18px; font-weight: 500; margin: 10px 0; color: #666; }
    
    #dnAjaxContainer {
        padding: 0 25px 25px 25px;
    }

    /* Status Styles */
    .status-badge-dn {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }
    
    .badge-approved { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .badge-pending { background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
    .badge-rejected { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    
    .debit-amount-highlight {
        color: #d32f2f;
        font-weight: 700;
    }
</style>

<div class="well_N">
    <div class="dp_sdw">
        <div class="debit-note-container">
            <div class="filter-section-top">
                <div class="report-title-main">PURCHASE DEBIT NOTE REPORT</div>
                <form id="dnReportForm">
                    <input type="hidden" name="m" value="{{ request()->m }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-labels-custom">Duration</label>
                                <select class="form-control select2" name="duration" id="dnDuration">
                                    <option value="this_month">This Month</option>
                                    <option value="today">Today</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div id="dnCustomRange" style="display: none; margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="date" name="from_date" class="form-control" placeholder="From">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" name="to_date" class="form-control" placeholder="To">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-submit-dn mt-3" id="btnDnSubmit">Submit</button>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-labels-custom">Principle (Supplier)</label>
                                <select class="form-control select2" name="principal">
                                    <option value="">All Suppliers</option>
                                    @foreach($Principals as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-labels-custom">Branch</label>
                                <select class="form-control select2" name="branch">
                                    <option value="">All Branches</option>
                                    @foreach($Branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="filter-labels-custom">Status</label>
                                <select class="form-control select2" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
            <div class="report-result-card">
                <div class="export-actions-dn">
                    <button class="btn-export-dn btn-exc-dn" onclick="ExportDnToExcel('xlsx')">Export to Excel</button>
                    <button class="btn-export-dn btn-csv-dn" onclick="exportView('dnDataTable')">Export to CSV</button>
                    <button class="btn-export-dn btn-prt-dn" onclick="printView('dnPrintArea')">Print</button>
                </div>
                
                <div id="dnPrintArea">
                    <div class="print-header-dn">
                        <h3>Brands Unlimited (Pvt) Ltd</h3>
                        <h4>Purchase Debit Note Report</h4>
                        <p id="dnDateDisplay"></p>
                    </div>
                    
                    <div class="table-responsive" id="dnAjaxContainer">
                        <!-- AJAX Table Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        
        $('#dnDuration').on('change', function() {
            if($(this).val() === 'custom') $('#dnCustomRange').show();
            else $('#dnCustomRange').hide();
        });
        
        $('#btnDnSubmit').on('click', function() { GetDebitNotes(); });
        
        // Initial load
        GetDebitNotes();
    });

    function GetDebitNotes(url = '{{ route("getPurchaseDebitNoteAjax") }}') {
        let formData = $('#dnReportForm').serialize();
        $('#dnAjaxContainer').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x"></i><br><p class="mt-3">Fetching report data...</p></div>');

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#dnAjaxContainer').html(response);
                
                // Re-bind pagination clicks
                $('.pagination a').on('click', function(e) {
                    e.preventDefault();
                    let pageUrl = $(this).attr('href');
                    GetDebitNotes(pageUrl);
                });
            },
            error: function() {
                $('#dnAjaxContainer').html('<div class="text-center p-5 text-danger">Error fetching report data. Please try again.</div>');
            }
        });
    }

    function ExportDnToExcel(type, fn, dl) {
        var elt = document.getElementById('dnDataTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "DebitNoteReport" });
        return dl ? XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Purchase Debit Note Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }

    function printView(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
</script>
@endsection
