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
                                    <span class="subHeadingLabelClass">View Location List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form id="filterForm">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label for="customers" class="form-label">Search</label>
                                            <input type="text" class="form-control" id="search" placeholder="Type here Location Name, ID" name="search" value="">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label>Location Type</label>
                                            <select name="warehouse_type" id="warehouse_type" class="form-control">
                                                <option value="">Location Type</option>
                                                @foreach ($isvirtual as $item)
                                                <option value="{{ $item->is_virtual }}">{{ $item->is_virtual == 1 ? 'Virtual': 'Non Virtual' }}</option>    
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>

                                                            <th class="text-center">Branch Name</th>



                                                            </thead>


                                                            <tbody id="filteredData">
                                                            <?php $count=1; ?>
                                                            @foreach($warehouse as $row)

                                                                <?php $array = explode('-',$row->code);
                                                                $level = count($array);
                                                                $nature = $array[0]; ?>
                                                                <tr>
                                                                    <td class="text-center">{{$count++}}</td>
                                                                    <td class="text-center">{{$row->name}}</td>
                                                                    <td class="text-center">{{$row->is_virtual === 1 ? 'Virtual': 'Non Virtual'}}</td>





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
            filterationCommonGlobal('{{ route('viewWarehouseListAjax') }}');
        });
    </script>

@endsection