@extends('layouts.default')

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="row well_N align-items-center">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <ul class="cus-ul">
            <li><h1>Purchase Master</h1></li>
            <li><h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Currency List</h3></li>
        </ul>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right" style="padding-top:15px;">
        <a href="{{ route('currency.create') }}" class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span> Add New Currency
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong><span class="glyphicon glyphicon-ok-circle"></span></strong> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong><span class="glyphicon glyphicon-exclamation-sign"></span></strong> {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="headquid">
                                    <h2 class="subHeadingLabelClass">Currency Management</h2>
                                </div>

                                <div class="lineHeight">&nbsp;</div>

                                {{-- Search Bar --}}
                                <div class="row" style="margin-bottom:15px;">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <input type="text" id="searchInput" class="form-control"
                                               placeholder="Search by name or code..." onkeyup="filterTable()">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover sf-table-list" id="currencyTable">
                                        <thead>
                                            <tr class="text-center" style="background-color:#3c8dbc; color:#fff;">
                                                <th class="text-center" style="width:60px;">#</th>
                                                <th class="text-center">Currency Name (Symbol)</th>
                                                <th class="text-center">Rate (PKR)</th>
                                                <th class="text-center">Added By</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center" style="width:150px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($currencies as $i => $currency)
                                            <tr class="text-center">
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    <span class="label label-info" style="font-size:13px;">
                                                        <strong>{{ $currency->name }}</strong>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="label label-success" style="font-size:13px;">
                                                        {{ number_format($currency->rate, 4) }}
                                                    </span>
                                                </td>
                                                <td>{{ $currency->username ?? '-' }}</td>
                                                <td>{{ $currency->date ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('currency.edit', $currency->id) }}"
                                                       class="btn btn-xs btn-warning"
                                                       title="Edit">
                                                        <span class="glyphicon glyphicon-pencil"></span> Edit
                                                    </a>
                                                    &nbsp;
                                                    <form action="{{ route('currency.destroy', $currency->id) }}"
                                                          method="POST" style="display:inline-block;"
                                                          onsubmit="return confirm('Are you sure you want to delete \'{{ $currency->name }}\'?');">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-xs btn-danger" title="Delete">
                                                            <span class="glyphicon glyphicon-trash"></span> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted" style="padding:30px;">
                                                    <span class="glyphicon glyphicon-info-sign"></span>
                                                    No currencies found. <a href="{{ route('currency.create') }}">Add one now.</a>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-muted" style="margin-top:8px;">
                                    Total: <strong>{{ $currencies->count() }}</strong> currencies
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function filterTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var rows  = document.querySelectorAll('#currencyTable tbody tr');
    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        row.style.display = text.indexOf(input) > -1 ? '' : 'none';
    });
}
</script>

@endsection
