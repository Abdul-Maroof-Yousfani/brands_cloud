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
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sale Order List</h3>
                </li>
            </ul>
        </div>

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">

            <ul class="cus-ul2">
                <li>
                    <a href="{{ route('createSaleOrder') }}" class="btn btn-primary">Create New Sale Order</a>
                </li>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="margin-top: 40px;">
                    <?php if (true):?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export
                        <b>(xlsx)</b></button>
                    <?php endif;?>
                </div>
                {{-- <li>
                    <input type="text" class="fomn1" onkeypress="viewRangeWiseDataFilter()" id="search"
                        placeholder="Search Anything">
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
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Customer</label>
                                                        <select name="customer_id" class="form-control select2">
                                                            <option value="">All Customers</option>
                                                            @foreach(App\Models\Customer::where('status', 1)->get() as $customer)
                                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>SO No#</label>
                                                        <input type="text" name="so_no" class="form-control" placeholder="SO No#">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>SI No</label>
                                                        <input type="text" name="gi_no" class="form-control" placeholder="SI No">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>From Date</label>
                                                        <input type="date" name="from" class="form-control" value="2025-10-01">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>To Date</label>
                                                        <input type="date" name="to" class="form-control" value="{{ date('Y-m-d') }}">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Approval</label>
                                                        <select name="status" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Approved</option>
                                                            <option value="0">Pending</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 hide">
                                                        <label>Payment</label>
                                                        <select name="payment_status" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="1">Received</option>
                                                            <option value="0">Pending</option>
                                                        </select>
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('filteredData');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Sale Report <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            filterationCommonGlobal('{{ route('getlistSaleOrder') }}');
        });



        $(document).ready(function () {
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