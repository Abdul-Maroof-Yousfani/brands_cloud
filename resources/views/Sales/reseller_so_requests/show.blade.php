<?php
$accType = Auth::user()->acc_type;
$m = ($accType == 'client') ? Session::get('run_company') : Auth::user()->company_id;
?>
@extends('layouts.default')

@section('content')
<div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="headquid" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="subHeadingLabelClass">Review SO Request: REQ-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <a href="{{ route('admin.reseller_so.index') }}?pageType={{ $_GET['pageType'] ?? '' }}&parentCode={{ $_GET['parentCode'] ?? '' }}&m={{ $m }}" class="btn btn-default">Back to List</a>
                </div>
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-4">
                        <p><strong>Reseller Name:</strong> {{ $request->reseller_name }}</p>
                        <p><strong>Reseller Email:</strong> {{ $request->email }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Request Date:</strong> {{ \Carbon\Carbon::parse($request->request_date)->format('d-M-Y') }}</p>
                        <p><strong>Status:</strong> 
                            @if($request->status == 0)
                                <span class="label label-warning">Pending</span>
                            @elseif($request->status == 1)
                                <span class="label label-success">Approved</span>
                            @else
                                <span class="label label-danger">Rejected</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>
                <h4>Requested Products</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="theme-bg">
                                <th>#</th>
                                <th>SKU Code</th>
                                <th>Product Name</th>
                                <th>Requested Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->sku_code }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                                                        
                @if($request->status == 0)
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12 text-right">
                        <form action="{{ route('admin.reseller_so.approve', $request->id) }}" method="POST" style="display:inline-block; text-align: left;">
                            {{ csrf_field() }}
                            <div class="form-group" style="display:inline-block; margin-right: 15px; vertical-align: top; text-align: left;">
                                <label>Select Warehouse for Stock IN</label>
                                <select name="warehouse_id" class="form-control" required style="width: 250px;">
                                    <option value="">-- Select Warehouse --</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="display:inline-block; vertical-align: top; margin-top: 25px;">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this request? This will create a Stock IN entry.');">Approve Request</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection
