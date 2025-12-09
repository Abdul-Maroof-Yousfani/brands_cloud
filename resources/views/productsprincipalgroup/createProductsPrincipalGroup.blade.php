@extends('layouts.default')

@section('content')

<div class="row well_N align-items-center">
    <div class="col-md-4">
        <ul class="cus-ul">
            <li><h1>Inventory Master</h1></li>
            <li>
                <h3>
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    &nbsp; Create Products Principal Group
                </h3>
            </li>
        </ul>
    </div>

    <div class="col-md-8 text-right">
        <a href="{{ route('listProductsPrincipalGroup') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="well_N">
    <div class="dp_sdw">

        <div class="panel">
            <div class="panel-body">

                <div class="headquid">
                    <h2 class="subHeadingLabelClass">Create Products Principal Group</h2>
                </div>

                  <form action="{{ route('storeProductsPrincipalGroup') }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row mt-4">

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-5 control-label">
                                    Products Principal Group <span class="rflabelsteric"><strong>*</strong></span>
                                </label>

                                <div class="col-sm-7">
                                    <input type="text" 
                                           class="form-control"
                                           name="products_principal_group"
                                           id="products_principal_group"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6"></div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg px-4">Save</button>
                            <a href="{{ route('listProductsPrincipalGroup') }}" class="btn btn-secondary btn-lg px-4">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
