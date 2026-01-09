@extends('layouts.default')
@include("select2")
@section('content')
<div class="well_N">
    <h1>List of Brands</h1>
    <div class="row form-mar">
        <div class="col-lg-3">
            <div class="form-group">
                <label for="exampleSelect">Principle Group:</label>
                <select class="form-control select2" multiple id="principal_group" onchange="getData()">
                    <option value="" disabled>Select Principal Group</option>
                    @foreach(\App\Helpers\CommonHelper::get_all_principal_groups() as $principal_group)
                        <option value="{{ $principal_group->id }}">{{ $principal_group->products_principal_group }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <table class="table table-bordered sf-table-list">
        <thead >
            <tr class="text-center">
                <th class="text-center">SR No</th>
                <th class="text-center">Name</th>
                <th class="text-center">Description</th>
                <th>Principal Group</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody id="body">
  
        </tbody>
    </table>
</div>
<script>
    $(".select2").select2();

    function getData() {
            $.ajax({
                url: window.location.href,
                type: 'get',
                data: {
                    principal_group: $("#principal_group").val()
                },
                success: function (response)
                {
                    $("#body").html(response);
                }
            });
    }

    $(document).ready(function() {
        getData();
    })
 </script>
@endsection