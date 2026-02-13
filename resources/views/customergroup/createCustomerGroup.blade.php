@extends('layouts.default')

@section('content')

<div class="row well_N align-items-center">
    <div class="col-md-4">
        <ul class="cus-ul">
            <li><h1>Sales Master</h1></li>
            <li>
                <h3>
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    &nbsp; Create Customer Group
                </h3>
            </li>
        </ul>
    </div>

    <div class="col-md-8 text-right">
        <a href="{{ route('listCustomerGroup') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(session('dataInsert'))
<div class="alert alert-success">
    {{ session('dataInsert') }}
</div>
@endif

<div class="well_N">
    <div class="dp_sdw">

        <div class="panel">
            <div class="panel-body">

                <div class="headquid">
                    <h2 class="subHeadingLabelClass">Create Customer Group</h2>
                </div>

                  <form action="{{ route('storeCustomerGroup') }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row mt-4">

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-5 control-label">
                                    Customer Group <span class="rflabelsteric"><strong>*</strong></span>
                                </label>

                                <div class="col-sm-7">
                                    <input type="text" 
                                           class="form-control"
                                           name="customer_group"
                                           id="customer_group"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6"></div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary btn-lg px-4">Save</button>
                            <a href="{{ route('listCustomerGroup') }}" class="btn btn-secondary btn-lg px-4">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
