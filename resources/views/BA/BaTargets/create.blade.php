@extends('layouts.default')
@section('content')
    <style>
        .premium-filter-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        .filter-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4e73df;
            font-weight: 800;
            margin-bottom: 10px;
            display: block;
        }
        .setup-title {
            font-weight: 800;
            color: #2e3b4e;
            letter-spacing: -1px;
            margin-bottom: 25px;
        }
        .well_N {
            background: #f4f7fc;
            min-height: 100vh;
            padding: 30px;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="well_N">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%; margin-bottom: 25px;">
            <div>
                <h1 class="setup-title mb-1">BA Target Setup</h1>
                <p class="text-muted small mb-0">Manage performance targets for your Business Associates</p>
            </div>
            <div style="text-align: right;">
                <a href="{{ route('baTargets.import') }}" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="transition: all 0.3s; background: #7961f2; border: none; white-space: nowrap; display: inline-block; border-radius: 8px;">
                    Bulk Import Targets
                </a>
            </div>
        </div>
        
        <div class="premium-filter-card">
            <form id="filterForm">
                <div class="row align-items-center g-3">
                    <div class="col-md-2">
                        <label class="filter-label">Target Month</label>
                        <select id="month" name="month" class="form-select select2">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">Target Year</label>
                        <select id="year" name="year" class="form-select select2">
                            @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="filter-label">Business Associate</label>
                        <select class="form-select select2" id="employee_id" name="employee_id">
                            <option value="">Search BA name or ID...</option>
                            @foreach(App\Employees::whereIn('emp_id', App\BAFormation::pluck('employee_id')->unique())->get() as $emp)
                                <option value="{{ $emp->emp_id }}">{{ $emp->name }} ({{ $emp->emp_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="filter-label">Tracking Mode</label>
                        <select class="form-select select2" id="target_type" name="target_type">
                            <option value="amount">Amount (Revenue) wise</option>
                            <option value="qty">Quantity (Units) wise</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div id="baTargetData">
            <div class="card p-5 text-center text-muted" style="border: 2px dashed #cbd5e0; border-radius: 20px; background: transparent;">
                <h4 class="font-weight-bold" style="color: #a0aec0;">Select a BA & Period to begin setup</h4>
            </div>
        </div>
    </div>

    <input type="hidden" id="loadBaTargetRoute" value="{{ route('baTargets.loadBaWise') }}">


        <script>
            $(document).ready(function () {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });

                function loadBaData() {
                    let employee_id = $('#employee_id').val();
                    let month = $('#month').val();
                    let year = $('#year').val();
                    let month_year = year + '-' + month;
                    let target_type = $('#target_type').val();

                    if (!employee_id || !month || !year) {
                        $('#baTargetData').html('<div class="card p-5 text-center text-muted" style="border: 2px dashed #ddd;"><h4>Please select BA and Date to setup targets</h4></div>');
                        return;
                    }

                    $('#baTargetData').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading BA Setup...</p></div>');

                    $.ajax({
                        url: $('#loadBaTargetRoute').val(),
                        type: 'GET',
                        data: {
                            employee_id: employee_id,
                            month_year: month_year,
                            target_type: target_type
                        },
                        success: function (res) {
                            $('#baTargetData').html(res);
                        },
                        error: function (err) {
                            $('#baTargetData').html('<div class="alert alert-danger">Error loading data. Please try again.</div>');
                        }
                    });
                }

                $('#employee_id, #month, #year, #target_type').on('change', function () {
                    loadBaData();
                });
            });
        </script>
@endsection