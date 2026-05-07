@extends('layouts.default')

@section('content')
<style>
    .filter-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #f0f0f0;
    }
    .report-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
        border: none;
    }
    .table-report th {
        background: #1e293b !important;
        color: #fff !important;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }
</style>

<div class="well_N">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">{{ $report_title }}</h1>
    </div>

    <div class="filter-card">
        <form id="salesFilterForm" class="row g-3 align-items-center">
            <div class="col-xl-3 col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Business Associate</label>
                <select name="employee_id" id="employee_id" class="form-select select2">
                    <option value="">All Business Associates</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Store / Customer</label>
                <select name="customer_id" id="customer_id" class="form-select select2">
                    <option value="">All Stores</option>
                    @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ date('Y-m-01') }}">
            </div>
            <div class="col-xl-2 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="mt-4 d-flex gap-2">
                    <button type="button" id="filterBtn" class="btn btn-primary w-100 shadow-sm">
                        <i class="fa fa-search me-1"></i> Search
                    </button>
                    <button type="button" id="exportBtn" class="btn btn-success shadow-sm">
                        <i class="fa fa-file-excel"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="reportContainer">
        <div class="report-card text-center py-5">
            <p class="text-muted mb-0">Select filters and click search to view report.</p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                width: '100%'
            });
        }

        $('#filterBtn').click(function() {
            loadReport();
        });

        $('#exportBtn').click(function() {
            let formData = $('#salesFilterForm').serialize();
            window.location.href = "{{ route('list.ba_sales_report') }}?" + formData + "&export=excel";
        });

        function loadReport() {
            let formData = $('#salesFilterForm').serialize();
            $('#reportContainer').html('<div class="report-card text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-primary mb-2"></i><br>Loading Report...</div>');

            $.ajax({
                url: "{{ route('list.ba_sales_report') }}",
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#reportContainer').html(response);
                },
                error: function() {
                    $('#reportContainer').html('<div class="alert alert-danger">Error loading report data.</div>');
                }
            });
        }
    });
</script>
@endsection
