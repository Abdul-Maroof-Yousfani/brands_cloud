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
                <div class="headquid">
                    <h2 class="subHeadingLabelClass">Pending Reseller SO Requests</h2>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="table table-bordered table-striped" id="myTable">
                        <thead>
                            <tr class="theme-bg">
                                <th>Req ID</th>
                                <th>Reseller Name</th>
                                <th>Reseller Email</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr>
                                <td>REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $req->reseller_name }}</td>
                                <td>{{ $req->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($req->request_date)->format('d-M-Y') }}</td>
                                <td>
                                    @if($req->status == 0)
                                        <span class="label label-warning">Pending</span>
                                    @elseif($req->status == 1)
                                        <span class="label label-success">Approved</span>
                                    @else
                                        <span class="label label-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.reseller_so.show', $req->id) }}?pageType={{ $_GET['pageType'] ?? '' }}&parentCode={{ $_GET['parentCode'] ?? '' }}&m={{ $m }}" class="btn btn-info btn-xs">Review Request</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
