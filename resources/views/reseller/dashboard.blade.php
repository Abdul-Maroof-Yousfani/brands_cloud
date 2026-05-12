@extends('layouts.reseller')

@section('content')
<div class="well_N">
    <div>
        <span style="display: block;">
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12 priorMainBox">
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Today's SO Requests</h6>
                                    <p>{{date('Y-m-d')}}</p>
                                </div>
                                <img src="{{ url('assets/img/miniBar.svg') }}" alt="">
                                <h4>0</h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month SO Requests</h6>
                                    <p>{{date('Y-m')}}</p>
                                </div>
                                <img src="{{ url('assets/img/miniBar.svg') }}" alt="">
                                <h4>0</h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Stock Balance</h6>
                                </div>
                                <img src="{{ url('assets/img/miniBar.svg') }}" alt="">
                                <h4>0.00</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="printBankPaymentVoucherList">
                                    <div class="panel ">
                                        <div id="PrintPanel">
                                            <div id="ShowHide">
                                                <div class="table-responsive dashTable mhe">
                                                    <div class="dashTableHeading printListBtn">
                                                        <h6>Recent Sales Orders Requests</h6>
                                                        <a class="btn btn-primary" href="#">View All SO Requests</a>
                                                    </div>
                                                    <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv">
                                                        <thead class="bgBlueofTd">
                                                            <tr>
                                                                <th class="text-center">Order No</th>
                                                                <th class="text-center">Order Date</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data" class="dashTableBody">
                                                            <tr>
                                                                <td class="text-center" colspan="4">No Data Available</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
        </span>
    </div>
</div>
@endsection
