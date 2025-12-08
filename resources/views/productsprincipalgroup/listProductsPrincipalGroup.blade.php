@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Inventory Master</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Products Principal Group </h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    <a href="{{ route('createProductsPrincipalGroup') }}" class="btn btn-primary">Create New</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
     
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <table class="table cus-tab">
                                                <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Products Principal Group</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                                @php
                                                   $i = 1; 
                                                @endphp
                                                @foreach($responses as $response)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $response->products_principal_group }}</td>
                                                    <td>
                                                        @if($response->status == 1)
                                                            <span class="label label-success">Active</span>
                                                        @else
                                                            <span class="label label-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"
                                                                type="button" data-toggle="dropdown"
                                                                aria-expanded="false">
                                                                ...
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="{{ route('editProductsPrincipalGroup', $response->id) }}" class="btn btn-sm btn-warning">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                                                                </a>
                                                                <a href="{{ route('deleteProductsPrincipalGroup', $response->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                                </a>
                                                            </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
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

@endsection

