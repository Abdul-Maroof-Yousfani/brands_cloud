<?php

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

?>

@extends('layouts.default')

@section('content')
@include('number_formate')
@include('select2')

<div class="container">

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Show session success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Show session error message --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2>Import Special Prices</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('special-prices.import') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <div class="form-group">
                    <label for="csv_file">CSV File</label>
                    <input type="file" class="form-control-file" id="csv_file" name="csv_file" required>
                    <small class="form-text text-muted">
                        Download the <a href="{{ asset('store_brand_discount.csv') }}" download>CSV template</a> for reference.
                    </small>
                </div>

             

                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>

    {{-- Show import success count --}}
    @if(session()->has('imported'))
        <div class="alert alert-success mt-3">
            Successfully imported {{ session('imported') }} records.
        </div>
    @endif

    {{-- Show import error messages --}}
    @if(session()->has('importErrors') && count(session('importErrors')) > 0)
        <div class="alert alert-danger mt-3">
            <h4>Errors:</h4>
            <ul>
                @foreach(session('importErrors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Show missing customers --}}
    @if(session()->has('missingCustomers') && count(session('missingCustomers')) > 0)
        <div class="alert alert-warning mt-3">
            <h5>Missing Customers:</h5>
            <ul>
                @foreach(session('missingCustomers') as $customer)
                    <li>{{ $customer }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Show missing brands --}}
    @if(session()->has('missingBrands') && count(session('missingBrands')) > 0)
        <div class="alert alert-warning mt-3">
            <h5>Missing Brands:</h5>
            <ul>
                @foreach(session('missingBrands') as $brand)
                    <li>{{ $brand }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
@endsection
