@extends('layouts.default')
@section('content')
<style>
    .report-card { border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: none; }
    .filter-section { background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; }
    .btn-generate { background: linear-gradient(135deg, #6c5ce7, #a29bfe); border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; color: white; transition: all 0.3s; }
    .btn-generate:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4); }
    .select2-container--default .select2-selection--multiple { border-radius: 8px; border: 1px solid #ddd; padding: 5px; }
</style>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="well_N">
        <div class="dp_sdw">
            <h1 style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">BA Daily Sales and Attendance Report</h1>
            
            <form id="reportForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Start Date</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" name="start_date" id="start_date" class="form-control" required value="{{ date('Y-m-01') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">End Date</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" name="end_date" id="end_date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Select BA (Employees)</label>
                            <div class="input-group">
                                <select name="employee_ids[]" id="employee_ids" class="form-control select2" multiple>
                                    @foreach($employees as $ba)
                                        <option value="{{ $ba->emp_id }}">{{ $ba->name }} ({{ $ba->emp_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="margin-top: 25px;">
                            <button type="submit" class="btn btn-primary" style="margin-top: 1px;">
                                <i class="fas fa-sync-alt mr-2"></i> Generate
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="reportResult" class="mt-4">
                <div class="text-center p-5 text-muted">
                    <i class="fas fa-chart-line fa-4x mb-3" style="opacity: 0.2;"></i>
                    <p>Select filters and click Generate to view the report</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@include('select2')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "All BAs",
            allowClear: true
        });

        $('#reportForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#reportResult').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Generating Report...</p></div>');

            $.ajax({
                url: "{{ route('ba.reports.attendance.generate') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#reportResult').html(response);
                },
                error: function() {
                    $('#reportResult').html('<div class="alert alert-danger">Error generating report. Please check the logs.</div>');
                }
            });
        });
    });
</script>
@endsection
