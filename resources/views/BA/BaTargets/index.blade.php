@extends('layouts.default')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <div class="well_N">
        <div class="row align-items-center mb-4">
            <div class="col-md-12">
                <h1 class="mb-3">BA Target setup</h1>
                <div class="card p-3 shadow-sm" style="border-radius: 12px; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label font-weight-bold">Select Month</label>
                                <select id="month" name="month" class="form-select select2">
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label font-weight-bold">Select Year</label>
                                <select id="year" name="year" class="form-select select2">
                                    @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">Select BA (Employee)</label>
                                <select class="form-select select2" id="employee_id" name="employee_id">
                                    <option value="">Select BA</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->emp_id }}">{{ $emp->name }} ({{ $emp->emp_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label font-weight-bold">Target Basis</label>
                                <select class="form-select select2" id="target_type" name="target_type">
                                    <option value="amount">Amount wise</option>
                                    <option value="qty">QTY wise</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="baTargetData" class="mt-4">
            <!-- AJAX Content will load here -->
            <div class="card p-5 text-center text-muted" style="border: 2px dashed #ddd;">
                <h4>Please select BA and Date to setup targets</h4>
            </div>
        </div>

    </div>
    <input type="hidden" id="loadBaTargetRoute" value="{{ route('baTargets.loadBaWise') }}">

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap-5'
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