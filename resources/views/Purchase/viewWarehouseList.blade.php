<?php
use App\Helpers\FinanceHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
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
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Warehouse List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {{-- <form id="filterForm">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label for="customers" class="form-label">Search</label>
                                            <input type="text" class="form-control" id="search" placeholder="Type here Location Name, ID" name="search" value="">
                                        </div>
                                    </form> --}}
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>

                                                            <th class="text-center">Warehouse Name</th>
                                                            <th class="text-center">Territory</th>
                                                            <th class="text-center">Action</th>

{{-- warehouse.edit --}}

                                                            </thead>


                                                            <tbody id="filteredData">
                                                            <?php $count=1; ?>
                                                            @foreach(\App\Helpers\CommonHelper::get_all_warehouse() as $row)
                                                                <tr>
                                                                    <td class="text-center">{{$count++}}</td>
                                                                    <td class="text-center">{{$row->name}}</td>
                                                                    <td class="text-center">{{ App\Helpers\CommonHelper::get_territory_name($row->territory_id) }}</td>
                                                                    <td class="text-center">
                                                                        <a href="{{ route('warehouse.edit', ['id' => $row->id, 'm' => request()->get("m"), 'pageType' => "1", 'parentCode' => request()->get("parentCode")]) }}" type="button" class="btn btn-primary">Edit</a>
                                                                        <form style="display: inline-block" method="post" action="{{ route('warehouse.delete', ['id' => $row->id, 'm' => request()->get("m"), 'pageType' => "1", 'parentCode' => request()->get("parentCode")]) }}">
                                                                            {{ csrf_field() }}
                                                                            {{ method_field("DELETE") }}
                                                                            <input type="hidden" name="m" value="{{ request()->get("m") }}"/>
                                                                            <input type="hidden" name="pageType" value="1"/>
                                                                            <input type="hidden" name="parentCode" value="{{ request()->get("parentCode") }}"/>
                                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                                        </form>
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
            
        });
    </script>

@endsection