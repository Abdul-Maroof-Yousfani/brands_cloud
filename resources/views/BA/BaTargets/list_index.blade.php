@extends('layouts.default')

@section('content')
<div class="well_N">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="mb-0">BA Target List</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('baTargets.index') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Setup Targets
            </a>
            <a href="{{ route('target.report') }}" class="btn btn-info text-white">
                <i class="fa fa-chart-bar"></i> View Report
            </a>
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
@endsection

@section('script')
<script>
    $(document).ready(function() {
        function loadTargets(url = null) {
            let fetchUrl = url || $('#listRefreshRoute').val();
            
            $.ajax({
                url: fetchUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    $('#baTargetList').html(res);
                },
                error: function() {
                    $('#baTargetList').html('<div class="alert alert-danger">Error loading data.</div>');
                }
            });
        }

        loadTargets();

        // Handle pagination clicks
        $(document).on('click', '#paginationLinks a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            loadTargets(url);
        });
    });
</script>
@endsection
