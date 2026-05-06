@extends('layouts.default')

@section('content')
    <div class="well_N">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border: 1px solid #f0f0f0 !important;">
            <div class="card-body p-4">
                <form id="filterForm" class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Select Month</label>
                        <input type="month" name="date" id="filterDate" class="form-control shadow-none"
                            value="{{ date('Y-m') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Business Associate</label>
                        <select name="employee_id" id="filterBA" class="form-select select2">
                            <option value="">All Business Associates</option>
                            @foreach(\App\Employees::pluck('name', 'emp_id') as $eid => $ename)
                                <option value="{{ $eid }}">{{ $ename }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-2 justify-content-end mt-4">

                            <button type="submit" class="btn btn-primary shadow-sm py-2 px-4">
                                <i class="fa fa-filter me-2"></i> Filter List
                            </button>

                            <div class="btn-group shadow-sm">
                                <a href="javascript:void(0)" onclick="exportData('excel')"
                                    class="btn btn-success py-2 px-3">
                                    <i class="fa fa-file-excel me-2"></i> Excel
                                </a>
                                <a href="javascript:void(0)" onclick="exportData('pdf')" class="btn btn-danger py-2 px-3">
                                    <i class="fa fa-file-pdf me-2"></i> PDF
                                </a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-0">
                <div id="baTargetList" class="p-3">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Loading Targets...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="listRefreshRoute" value="{{ route('list.baTargets') }}">
    <input type="hidden" id="excelExportRoute" value="{{ route('baTargets.exportTemplate') }}">
@endsection

@section('script')
    <script>
        function exportData(type) {
            let date = $('#filterDate').val();
            let ba = $('#filterBA').val();
            let baseUrl = type === 'excel' ? $('#excelExportRoute').val() : "{{ route('target.report') }}?export=pdf"; // Temporary placeholder for PDF

            // Split date YYYY-MM
            let parts = date.split('-');
            let year = parts[0];
            let month = parseInt(parts[1]);

            let finalUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + `year=${year}&month=${month}&employee_id=${ba}`;

            if (type === 'pdf') {
                // For PDF, we might want to call a specific route we're about to add
                finalUrl = "{{ route('baTargets.listIndex') }}/export-pdf" + `?year=${year}&month=${month}&employee_id=${ba}`;
            }

            window.location.href = finalUrl;
        }

        $(document).ready(function () {
            function loadTargets(url = null) {
                let fetchUrl = url || $('#listRefreshRoute').val();
                let formData = $('#filterForm').serialize();

                $.ajax({
                    url: fetchUrl,
                    type: 'POST',
                    data: formData + '&_token={{ csrf_token() }}',
                    success: function (res) {
                        $('#baTargetList').html(res);
                    },
                    error: function () {
                        $('#baTargetList').html('<div class="alert alert-danger">Error loading data.</div>');
                    }
                });
            }

            $('#filterForm').submit(function (e) {
                e.preventDefault();
                loadTargets();
            });

            // Initialize Select2 if available
            if ($.fn.select2) {
                $('.select2').select2({
                    placeholder: "Select Business Associate",
                    allowClear: true,
                    width: '100%'
                });
            }

            loadTargets();

            // Handle pagination clicks
            $(document).on('click', '#paginationLinks a', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                loadTargets(url);
            });
        });
    </script>
@endsection