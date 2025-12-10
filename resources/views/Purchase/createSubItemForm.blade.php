<?php
$m = Session::get('run_company');
use App\Helpers\CommonHelper;
use App\Helpers\ImportHelper;

$sys_code =CommonHelper::generateUniquePosNo('subitem','sys_no','ITEM');

?>
@extends('layouts.default')

@section('content')


@include('select2');
<div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {!! session('error') !!}
    </div>
@endif

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="subHeadingLabelClass">Add New Sub Item</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#exampleModal" style="float: right;"> Import csv </button>
                                    </div>
                                </div>

                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <form action="{{ url('pad/addSubItemDetail?m='.$m.'') }}" method="POST" id="addSubItemDetail">
                                                <?php // echo Form::open(array('url' => 'pad/addSubItemDetail?m='.$m.'','id'=>'addSubItemDetail'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Category :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" autofocus name="CategoryId" id="CategoryId"
                                                                onchange="get_sub_category_by_id()"
                                                                class="form-control requiredField select2">
                                                                <option value="">Select Category</option>
                                                                @foreach(CommonHelper::get_category()->get() as $key => $y)
                                                                <option value="{{ $y->id}}">{{ $y->main_ic}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Sub Category :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" autofocus name="SubCategoryId" id="SubCategoryId"
                                                                class="form-control  select2">
                                                                <option value="">Select Category</option>

                                                            </select>
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Principal Group :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" autofocus name="principal_group" id="principal_group"
                                                                class="form-control  select2" onchange="get_brand_by_principal_group(this)">
                                                                <option value="">Select Principal Group</option>
                                                                @foreach(App\Helpers\CommonHelper::get_all_principal_groups() as $principal) 
                                                                    <option value="{{ $principal->id }}">{{ $principal->products_principal_group }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Brand :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <select style="width:100% !important;" autofocus name="brand" id="brand"
                                                                class="form-control  select2">
                                                                <option value="">Select Brand</option>
                                                                @foreach($brand as $key => $row)
                                                                <option value="{{ $row->id}}">{{ $row->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                            <label>Sub Item Name :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input  style="width:100% !important;" type="text" name="sub_item_name" id="sub_item_name"
                                                                value="" class="form-control" />
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Sys Code :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input style="width:100% !important;" type="text" name="item_code" id="sys_code"
                                                                value="{{$sys_code}}" class="form-control" readonly />
                                                        </div>




                                                    </div>

                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>SKU Code :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="sku" id="sku" value=""
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Name :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input  style="width:100% !important;" type="text" name="product_name" id="product_name"
                                                                value="" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Description :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="product_description"
                                                                id="product_description" value="" class="form-control" />
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Unit of Measurement :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" name="uom_id" id="uom_id"
                                                                class="form-control requiredField select2">
                                                                <option value="">Select UOM</option>
                                                                @foreach($uom as $key => $i)
                                                                <option value="{{ $i->id}}">{{ $i->uom_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">  
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Packing :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input style="width:100% !important;" value="0" class="form-control text-right requiredField"
                                                                type="number" name="packing" id="packing">
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Barcode :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="product_barcode" id="product_barcode"
                                                                value="" class="form-control" />
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                            <label>Type</label>
                                                            <select style="width: 100%" name="maintain" id="maintain"
                                                                class="form-control requiredField">

                                                                @foreach(CommonHelper::get_all_demand_type() as $row)
                                                                <option value="{{ $row->id }}">{{ ucwords($row->name) }}
                                                                </option>
                                                                @endforeach

                                                            </select>
                                                        </div>



                                                    </div>

                                                    <div class="row">


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Group :</label>
                                                            <select style="width:100% !important;" name="group_id" id="group_id"
                                                                class="form-control select2">
                                                                <option value="">Select Group</option>
                                                                @foreach($group as $key => $i)
                                                                <option value="{{ $i->id}}">{{ $i->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Classification :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" name="product_classification_id"
                                                                id="product_classification_id"
                                                                class="form-control select2 requiredField">
                                                                <option value="">Select Product Classification</option>
                                                                @foreach($product_classification as $key => $i)
                                                                <option value="{{ $i->id}}">{{ $i->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Type :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                            <select  style="width:100% !important;" name="product_type_id" id="product_type_id"
                                                                class="form-control select2 requiredField">
                                                                <option value="">Select Product Type</option>
                                                                @foreach($product_type as $key => $i)
                                                                <option value="{{ $i->product_type_id}}">{{ $i->type}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Trend :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                            <select style="width:100% !important;" name="product_trend_id" id="product_trend_id"
                                                                class="form-control select2 requiredField">
                                                                <option value="">Select Product Trend</option>
                                                                @foreach($product_trend as $key => $i)
                                                                <option value="{{ $i->id}}">{{ $i->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Purchase Price :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="purchase_price" id="purchase_price"
                                                                value="" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Sale Price :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="sale_price" id="sale_price" value=""
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>MRP Price :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="mrp_price" id="mrp_price" value=""
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Tax Applied :</label><br>
                                                            <span class="rflabelsteric"></span>
                                                            <input  type="checkbox" id="tax_applied" name="tax_applied"value="1">

                                                            <label for="tax_applied"> Yes </label>
                                                            <input type="checkbox" id="tax_applied" name="tax_applied"value="0">
                                                            <label for="tax_applied"> No </label>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Tax Type :</label>
                                                            <select style="width:100% !important;" name="tax_type_id" id="tax_type_id"
                                                                class="form-control select2">
                                                                <option value="">Select Tax Type</option>
                                                                @foreach($tax_type as $key => $i)
                                                                <option value="{{ $i->id}}">{{ $i->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Tax Applied On :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="tax_applied_on" id="tax_applied_on"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Tax Policy :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input style="width:100% !important;" type="text" name="tax_policy" id="tax_policy" value=""
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Tax :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input style="width:100% !important;" value="0" class="form-control text-right requiredField"
                                                                type="number" name="tax" id="tax">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Discount :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" value="0" class="form-control text-right" type="number"
                                                                name="discount" id="discount">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Min Qty :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" value="0" class="form-control text-right" type="number"
                                                                name="min_qty" id="min_qty">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Max Qty :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" value="0" class="form-control text-right" type="number"
                                                                name="max_qty" id="max_qty">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Image :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" class="form-control text-right" type="file" name="image"
                                                                id="image">
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Locality :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" class="form-control text-right" type="text"
                                                                name="locality" id="locality">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Origin :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" class="form-control text-right" type="text" name="origin"
                                                                id="origin">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Color :</label>
                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                            <input style="width:100% !important;" class="form-control text-right" type="text" name="color"
                                                                id="color">
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>H.S Code :</label>
                                                            <select style="width:100% !important;" name="hs_code_id" id="hs_code_id"
                                                                class="form-control select2">
                                                                <option value="">Select Hs code</option>
                                                                @foreach(ImportHelper::getAllHsCode() as $key => $i)
                                                                <option value="{{ $i->id}}">{{ $i->hs_code}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Status :</label>
                                                            <select style="width:100% !important;" name="product_status" class="form-control text-right">
                                                                <option>Select Option</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                            </select>

                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Expiry Date Required  :</label>
                                                            <select style="width:100% !important;" name="is_expiry_required" class="form-control">
                                                                <option value="1">Yes</option>
                                                                <option selected value="0">No</option>
                                                            </select>

                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Barcode Scanning Required  :</label>
                                                            <select style="width:100% !important;" name="is_barcode_scanning" class="form-control">
                                                                <option value="1">Yes</option>
                                                                <option selected value="0">No</option>
                                                            </select>

                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                    </div>


                                                    <table class="table table-bordere hide" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" style="">SR No</th>
                                                                <th style="" class="text-center">Warehouse<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                                <th style="" class="text-center"> Closing Stock<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                                <th class="text-center">Closing Value<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="append_bundle">
                                                            <?php $counter=1; ?>


                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                            <tr>
                                                                <td>{{$counter++}}</td>
                                                                <input type="hidden" name="warehouse[]"
                                                                    value="{{$row->id}}" />
                                                                <td class="text-center">{{$row->name}}</td>
                                                                <td><input step="any" type="number"
                                                                        class="form-control requiredField" value="0"
                                                                        name="closing_stock[]"
                                                                        id="closing_stock{{$counter}}" /> </td>
                                                                <td><input step="any" type="number"
                                                                        class="form-control requiredField" value="0"
                                                                        name="closing_val[]" id="closing_val{{$counter}}" />
                                                                </td>
                                                            </tr>
                                                            @endforeach



                                                        </tbody>

                                                        <tbody>
                                                            <tr style="font-size:large;font-weight: bold">
                                                                <td class="text-center" colspan="2">Total</td>
                                                                <td id="" class="text-right" colspan="1"><input readonly
                                                                        class="form-control clear" type="text"
                                                                        id="total_qty" /> </td>
                                                                <td id="" class="text-right" colspan="1"><input readonly
                                                                        class="form-control clear" type="text"
                                                                        id="total_rate" /> </td>


                                                            </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <br>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                    {{-- {{ Form::submit('Submit', ['class' => 'btn btn-success']) }} --}}
                                                    <input type="submit" class="btn btn-success" value="Submit">
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear
                                                        Form</button>
                                                </div>
                                                <?php
                                            // echo Form::close();
                                            ?>
                                            </form>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="importProducts___BV_modal_body_" class="modal-body">
                <form action="{{ url('sales/uploadProduct') }}" method="post" id="csv" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-sm-12 col-md-12">
                            <fieldset class="form-group" id="__BVID__194">
                                <div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                                    <input type="file" name='import_file' label="Choose File" required>
                                    <div id="File-feedback" class="d-block invalid-feedback">Field must be in
                                        csvformat</div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            {{-- <button onclick="download_csv_file()" target="_self"
                                class="btn btn-info btn-sm btn-block">Download example</button></div> --}}
                                <!-- <a class="btn btn-info btn-sm btn-block" href="{{ asset("product_uploader_example.xlsx") }}">Download example</a> -->
<a href="{{ asset('product_uploader_example.csv') }}" download>Download</a>


                    </div>
                </form>

                <div class="col-sm-12 col-md-12">
                    <table class="table table-bordered table-sm mt-4">
                        <tbody>
                            <tr>
                                <td>Product Code</td>
                                <th><span class="badge badge-outline-success">This Field is Optional (Use only if you want to update the product against any specific Product)</span>
                                </th>
                            </tr>
                            <tr>
                                <td>SKU/Article</td>
                                <th><span class="badge badge-outline-success">This Field is required and
                                        must be unique</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Product Name</td>
                                <th><span class="badge badge-outline-success">Field is required</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Product Description</td>
                                <th><span class="badge badge-outline-success">This Field is required</span>
                                </th>


                            </tr>
                            <tr>
                                <td>UOM</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Packing</td>
                                <th><span class="badge badge-outline-info">This Field is optional</span></th>
                            </tr>
                            <tr>
                                <td>Product barcode</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Brand</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Group</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Category</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Sub-Category</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Product Classification</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Product Type</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Product Trend</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Purchase Price</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Sale Price</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Mrp Price</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Is Tax Apply</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Tax Type</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Tax Applied On</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Tax %</td>
                                <th><span class="badge badge-outline-info">This Field is required</span></th>
                            </tr>
                            <tr>
                                <td>Product Flat Discount(%)</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Min Qty</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Max Qty</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Image Link</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>H.S Code</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Locality</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Origin</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Color</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Product Status</td>
                                <th><span class="badge badge-outline-success">This Field is required</span>
                                </th>
                            </tr>
                            <tr>
                                <td>Tax Policy</td>
                                <th><span class="badge badge-outline-success">This Field is optional</span>
                                </th>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
<script type="text/javascript">

function get_brand_by_principal_group(element) {
    var principal_group_id = $(element).val();
    $.ajax({
        // url: '/purchase/get_brand_by_principal_group',
          url: "{{ route('get_brand_by_principal_group') }}",
        type: 'Get',
        data: { principal_group_id: principal_group_id },
        success: function(response) {
            $('#brand').empty().select2({
                data: response
            });
            $('#brand').select2('open');

        }
    });
}

function get_item_master() {
    var category = $('#CategoryId').val();
    var sub_category = $('#sub_category').val();
    if (category > 0 && sub_category > 0) {
        $.ajax({
            url: '/pdc/get_item_master',
            type: 'Get',
            data: {
                category: category,
                sub_category: sub_category
            },
            success: function(response) {
                $('#item_master').html(response);
            }
        });
    }

}

function get_sub_category_by_id() {
    $('#SubCategoryId').empty();
    var category = $('#CategoryId').val();
    if (category) {
        $.ajax({
            // url: '/pdc/get_sub_category_by_id',
              url: "{{ route('get_sub_category_by_id') }}",
            type: 'Get',
            data: {
                category: category
            },
            success: function(response) {
                $('#SubCategoryId').append(response);
            }
        });
    }

}

function get_sub_item_code() {
    var category = $('#CategoryId').val();
    var sub_category = $('#sub_category').val();
    var item_master_id = $('#item_master').val();
    if (category > 0 && sub_category > 0 && item_master_id > 0) {
        $.ajax({
            url: '/pdc/get_sub_item_code',
            type: 'Get',
            data: {
                category: category,
                sub_category: sub_category,
                item_master_id: item_master_id
            },
            success: function(response) {
                $('#item_code').val(response);
            }
        });
    } else {
        $('#item_code').val('');
    }

}

$(document).ready(function() {

        $('#addSubItemDetail').on('submit', function(e) {
        // Form 2 ko disable karna, taake Form 2 ka action na ho
            $('#csv input, #csv button').prop('disabled', true);  // Disable Form 2 elements

            // Prevent Form 2 from submitting if it's disabled
            $('#csv').on('submit', function(e) {
                e.preventDefault();  // Prevent Form 2 from submitting
            });

        // Form 1 ko normal tareeqe se submit hone dena
        });

        // Optional: Form 1 ko reset karte waqt Form 2 ko re-enable karna
        $('#addSubItemDetail').on('reset', function() {
            $('#csv input, #csv button').prop('disabled', false); // Re-enable Form 2
        });

    $('#bank_detail').change(function() {
        if ($(this).is(':checked')) {

            $(".banks").css("display", "block");
            $(".required").addClass("requiredField");

            //   $("#pra").addClass("requiredField");
        } else {
            $(".banks").css("display", "none");
            $(".required").removeClass("requiredField");
            //  $('#pra').val("");
            // $("#pra").removeClass("requiredField");
        }
    });


    $(".btn-success").click(function(e) {
        var subItem = new Array();
        var val;
        //$("input[name='chartofaccountSection[]']").each(function(){
        subItem.push($(this).val());
        //});
        var _token = $("input[name='_token']").val();
        for (val of subItem) {

            jqueryValidationCustom();
            if (validate == 0) {

                $('.btn-success').prop('disabled', true);
                $("form").submit();
                //return false;
            } else {
                return false;
            }
        }
    });
});

function download_csv_file() {
    // Define the heading for each row of the data based on your table fields
    // var csv = 
    //     'SKU/Article,Product Name,Product Description,UOM,Packing,Product Barcode,Brand,Group,Category,Sub-Category,Product Classification,Product Type,Product Trend,Purchase Price,Sale Price,Mrp Price,Is Tax Apply,Tax Type,Tax Applied On,Tax %,Product Flat Discount(%),Min Qty,Max Qty,Image Link,H.S Code,Locality,Origin,Color,Product Status\n';
    var csv = 'S No,System Code,SKU / Article No,Product Name,Product Description,UOM,Packing,Product Barcode,Brand,Group,Category,Sub-Category,Product Classification,Product Type,Product Trend,Purchase Price,Sale Price,MRP Price,Is Tax Apply,Tax Type,Tax Applied On,Tax Policy,Tax %,Product Flat Discount(%),Min Qty,Max Qty,Image Link,HS Code,Locality,Origin,Color,Product Status\n';

    var csvFileData = [
        [1, 1201, "Kate LIPSTICK - My real pink 034-005","asd", "PC", 1, "3607343811798", "Rimmel", "Cosmetics", "Body mist", "Lipstick", "C", "Commercial", "Ongoing", 4500, 1095, 1610, "yes", "Include in", "MRP", "Tax Before Discount", 25,0, "", "","", "","","", "imported", "", "Active"],
        [2, 1202, "Kate LIPSTICK - Tender mauve 034-008","asd", "PC", 1, "3607343811745", "Rimmel", "Cosmetics", "Body spray", "Lipstick", "B", "Commercial", "Ongoing", 3600, 1095, 1610, "yes", "Tax on", "MRP", "Tax Before Discount", 25,0, "", "","", "","","", "imported", "", "Active"]
      

    ];


    // Merge the data with CSV
    csvFileData.forEach(function(row) {
        csv += row.join(',');
        csv += "\n";
    });

    // Create a download link for the CSV file
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = 'Product_import_example.csv';
    hiddenElement.click();
}
</script>
<script type="text/javascript">
$('.select2').select2();
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection