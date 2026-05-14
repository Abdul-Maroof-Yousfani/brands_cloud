@extends('layouts.reseller')

@section('content')
<div class="container-fluid">
    <div class="well_N">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading d-flex justify-content-between align-items-center">
                                <h4>SO Request List</h4>
                                <a href="{{ route('reseller.so.create') }}" class="btn btn-primary btn-sm">Create New Request</a>
                            </div>
                            <div class="panel-body" style="background:#fff; padding: 20px;">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Date</th>
                                            <th>Customer Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requests as $request)
                                        <tr>
                                            <td>REQ-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($request->request_date)->format('d-M-Y') }}</td>
                                            <td>{{ $request->customer_name }}</td>
                                            <td>
                                                @if($request->status == 0)
                                                    <span class="badge badge-warning" style="background:#f0ad4e;">Pending</span>
                                                @elseif($request->status == 1)
                                                    <span class="badge badge-success" style="background:#5cb85c;">Approved & Processed</span>
                                                @else
                                                    <span class="badge badge-danger" style="background:#d9534f;">Rejected</span>
                                                @endif
                                            <td>
                                                <a href="{{ route('reseller.so.show', $request->id) }}" class="btn btn-info btn-sm">View Details</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No SO Requests found.</td>
                                        </tr>
                                        @endforelse
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
@endsection
