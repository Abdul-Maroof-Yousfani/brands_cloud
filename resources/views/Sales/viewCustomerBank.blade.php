<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Customer Bank List</h2>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12">
                            @if(Session::has('success'))
                                <div class="alert alert-success">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                            <table class="table table-bordered table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Bank Name</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $row->bank_name }}</td>
                                        <td>{{ $row->username }}</td>
                                        <td>{{ ucfirst($row->status) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('sales/editCustomerBank/'.$row->id.'?m='.$m) }}" class="btn btn-primary btn-xs">Edit</a>
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
    </div>
    
    <script>
        $(document).ready(function() {
            if ( $.fn.DataTable.isDataTable('#dataTable') ) {
                $('#dataTable').DataTable().destroy();
            }
            $('#dataTable').DataTable();
        });
    </script>
@endsection
