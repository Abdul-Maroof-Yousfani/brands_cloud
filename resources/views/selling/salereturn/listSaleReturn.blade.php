@extends('layouts.default')

@section('content')
@include('select2')
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Sale</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sale Return List</h3>
                </li>
            </ul>
        </div>

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">

            <ul class="cus-ul2">
                <li>
                    <a href="{{ route('createSaleReturn') }}" class="btn btn-primary">Create New Sale Return</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" onkeypress="viewRangeWiseDataFilter()" id="search" placeholder="Search Anything" >
                </li> --}}
                {{-- <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">

                                    <div class="row">

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="filterForm">
                                                <div class="row ">
                                                    <div class="col-md-1 mb-3">
                                                        <label for="customers" class="form-label">Show Entries</label>
                                                        <select name="per_page" class="form-control">
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-11 mb-3 ">
                                                        <div class="row justify-content-end text-right">
                                                            <div class="col-md-2 mb-3">
                                                                <label>User </label>
                                                                <select name="username[]" multiple class="form-control select2">
                                                                    <option disabled>Select User</option>
                                                                    @foreach($username as $item)
                                                                        <option value="{{ $item->username}}">{{ $item->username}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label>Date</label>
                                                                <input type="date" name="date" class="form-control" />
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label for="customers" class="form-label">Status</label>
                                                                <select name="status" class="form-control">
                                                                    <option value="">All</option>
                                                                    <option value="1">Approved</option>
                                                                    <option value="0">Pending</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label for="customers" class="form-label">Search</label>
                                                                <input type="text" class="form-control" id="search"
                                                                    placeholder="Search by Doc No or Customer"
                                                                    name="search" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                            <div id="filteredData">
                                                <div class="text-center spinnerparent">
                                                    <div class="loader" role="status"></div>
                                                </div>
                                            </div>
                                            {{-- <div class="">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center col-md-1">So No.</th>
                                                            <th class="text-center  col-md-4">Customer Name</th>
                                                            <th class="text-center  col-md-1">Order Date</th>
                                                            <th class="text-center  col-md-1">Amount</th>
                                                            <th class="text-center  col-md-1">Approval Status</th>
                                                            <th class="text-center  col-md-2">Status</th>
                                                            <th class="text-center  col-md-2">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data">

                                                    </tbody>

                                                </table>
                                            </div> --}}
                                        </div>
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
        $(document).ready(function() {
            $('.select2').select2();
            filterationCommonGlobal('{{ route('getListSaleReturn') }}');
           
        });



$(document).ready(function() {
    let saleOrderId = localStorage.getItem("showSaleOrderId");
    let route = localStorage.getItem("showSaleOrderRoute");

    if (saleOrderId && route) {
        showDetailModelOneParamerter(route, saleOrderId, 'View Sale Order');
        // clear after use
        localStorage.removeItem("showSaleOrderId");
        localStorage.removeItem("showSaleOrderRoute");
    }
});



    </script>
@endsection
