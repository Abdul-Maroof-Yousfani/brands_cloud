@extends('layouts.reseller')

@section('content')
<div class="container-fluid">
    <div class="well_N">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading d-flex justify-content-between align-items-center">
                        <h4>SO Request Details - REQ-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</h4>
                        <a href="{{ route('reseller.so.list') }}" class="btn btn-default btn-sm">Back to List</a>
                    </div>
                    <div class="panel-body" style="background:#fff; padding: 20px;">
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <strong>Request Date:</strong><br>
                                {{ \Carbon\Carbon::parse($request->request_date)->format('d-M-Y') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Status:</strong><br>
                                @if($request->status == 0)
                                    <span class="badge badge-warning" style="background:#f0ad4e;">Pending</span>
                                @elseif($request->status == 1)
                                    <span class="badge badge-success" style="background:#5cb85c;">Approved & Processed</span>
                                @else
                                    <span class="badge badge-danger" style="background:#d9534f;">Rejected</span>
                                @endif
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5>Requested Products</h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU Code</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ isset($products[$item->product_id]) ? $products[$item->product_id]->sku_code : 'N/A' }}</td>
                                    <td>{{ isset($products[$item->product_id]) ? $products[$item->product_id]->product_name : 'Unknown Product' }}</td>
                                    <td>{{ $item->qty }}</td>
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
@endsection
