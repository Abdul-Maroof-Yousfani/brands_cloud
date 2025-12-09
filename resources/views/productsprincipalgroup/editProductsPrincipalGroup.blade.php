@extends('layouts.default')

@section('content')

<div class="row well_N align-items-center mb-3">
    <div class="col-lg-6">
        <ul class="cus-ul mb-0">
            <li><h1 class="m-0">Inventory Master</h1></li>
            <li>
                <h3 class="mt-2">
                    <span class="glyphicon glyphicon-chevron-right"></span>  
                    Edit Products Principal Group
                </h3>
            </li>
        </ul>
    </div>

    <div class="col-lg-6 text-right">
        <a href="{{ route('listProductsPrincipalGroup') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="col-lg-12">
        <div class="well_N p-4">
            <div class="dp_sdw p-4">

                <div class="panel">
                    <div class="panel-body">

                        <div class="headquid mb-4">
                            <h2 class="subHeadingLabelClass">Edit Products Principal Group</h2>
                        </div>
             <form action="{{ route('updateProductsPrincipalGroup', $response->id) }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="row">

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Products Principal Group 
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                    </label>
                                    <input type="text" 
                                           name="products_principal_group" 
                                           class="form-control" 
                                           value="{{ $response->products_principal_group }}" 
                                           required>
                                </div>

                                <div class="col-md-12 text-right mt-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Update
                                    </button>
                                    <a href="{{ route('listProductsPrincipalGroup') }}" 
                                       class="btn btn-secondary px-4 ml-2">
                                        Cancel
                                    </a>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
