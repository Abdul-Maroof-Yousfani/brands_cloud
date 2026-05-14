@extends('layouts.default')
@section('content')
    <div class="container-fluid">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <span class="subHeadingLabelClass">Item Conversion List</span>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <a href="{{ url('store/item-conversion?m=' . $m) }}" class="btn btn-primary">Add New Conversion</a>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                
                                <table class="table table-bordered sf-table-list">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Voucher No</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Item ID</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Warehouse</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($conversions as $key => $conv)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $conv->voucher_no }}</td>
                                                <td>{{ $conv->voucher_date }}</td>
                                                <td>
                                                    @if($conv->voucher_type == 1)
                                                        <span class="label label-success">IN</span>
                                                    @else
                                                        <span class="label label-danger">OUT</span>
                                                    @endif
                                                </td>
                                                <td>{{ $conv->sub_item_id }}</td>
                                                <td>{{ $conv->qty }}</td>
                                                <td>{{ $conv->rate }}</td>
                                                <td>{{ $conv->amount }}</td>
                                                <td>{{ $conv->warehouse_id }}</td>
                                                <td>{{ $conv->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
