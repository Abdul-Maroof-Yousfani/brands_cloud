@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('production_work_order','work_no','WO');
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Finance</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Credit Note</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
         
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <h2 class="subHeadingLabelClass">Create Credit Note</h2>
                        </div>
                            <form method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Stores</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                      <input type="text" class="form-control" value="{{ $debit->store }}" name="store" placeholder="Office Staff()">                                                    
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Delivery Man</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                      <input type="text" class="form-control" name="delivery_man" value="{{ $debit->delivery_man }}" placeholder="Jahangir Ahmed">                                                    
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Date Time</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                      <input type="date" class="form-control" value="{{ $debit->date }}" name="date_and_time">                                                    
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Amount</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                      <input type="number" class="form-control" value="{{ $debit->amount }}" name="amount">                                                    
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Details</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                        <textarea class="form-control" name="details">{{ $debit->details }}</textarea>
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Credit</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                        <select class="form-control select2" name="credit">
                                                            <option value="">Select Credit</option>
                                                            <option value="select1">Select Credit</option>
                                                            <option value="select2" selected>Select Credit</option>
                                                        </select>
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Debit</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                        <select class="form-control select2" name="debit">
                                                            <option value="">Select Credit</option>
                                                            <option value="select1">Select Credit</option>
                                                            <option value="select2" selected>Select Credit</option>
                                                        </select>
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                             <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group" style="display: flex;">
                                                        <label class="control-label" style="margin-right: 10px;">On Record</label>
                                                        <div class="" style="padding-left: 0px;">
                                                            <input type="checkbox" {{ $debit->on_record ? "checked" : "" }} name="on_record"/>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Voucher Type</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                        <select class="form-control select2" name="voucher_type">
                                                            <option value="">Select Voucher Type</option>
                                                            @foreach($vouchers as $voucher)
                                                                <option value="{{ $voucher->id }}" {{ $debit->voucher_type == $voucher->id ? 'selected' : '' }}>{{ $voucher->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="control-label">Branch</label>
                                                    <br>
                                                    <div class="col-sm-8" style="padding-left: 0px;">
                                                        <select class="form-control select2" name="branch">
                                                            <option value="">Select Branch</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}" {{ $debit->branch == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <button type="cancel" class="btn btn-danger">Reset</button>
                                                    <button type="submit" class="btn btn-success">Update Voucher</button>  
                                                </div>
                                            </div>


                        
                                        
                                        </div>        
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection