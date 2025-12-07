<?php
$m = Session::get('run_company');
use App\Helpers\CommonHelper;
use App\Helpers\ImportHelper;

$sys_code =CommonHelper::generateUniquePosNo('subitem','sys_no','ITEM');

?>


@section('content')


@include('select2');
<!-- <div class="well_N"> -->
    <!-- <div class="dp_sdw"> -->
        <div class="panel">
            <div class="panel-body">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <!-- <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <span class="subHeadingLabelClass">View New Sub Item</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#exampleModal" style="float: right;"> Import csv </button>
                                    </div>
                                </div>

                            </div> -->
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pad/addSubItemDetail?m='.$m.'','id'=>'addSubItemDetail'));?>
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
                                                                class="form-control requiredField select2">
                                                                <option value="">Select Category</option>

                                                            </select>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Brand :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <select style="width:100% !important;" autofocus name="brand" id="brand"
                                                                class="form-control  select2">
                                                                <option value="">Select Brand</option>
                                                                
                                                                <option value=""></option>
                                                                
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
                                                            <input style="width:100% !important;" type="text" name="sys_code" id="sys_code"
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
                                                              
                                                                <option value=""></option>
                                                              
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

                                                              
                                                                <option value="">
                                                                </option>
                                                                

                                                            </select>
                                                        </div>



                                                    </div>

                                                    <div class="row">


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Group :</label>
                                                            <select style="width:100% !important;" name="group_id" id="group_id"
                                                                class="form-control select2">
                                                                <option value="">Select Group</option>
                                                              
                                                             
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Classification :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" name="product_classification_id"
                                                                id="product_classification_id"
                                                                class="form-control select2 requiredField">
                                                                <option value="">Select Product Classification</option>
                                                              
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Type :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                            <select  style="width:100% !important;" name="product_type_id" id="product_type_id"
                                                                class="form-control select2 requiredField">
                                                                <option value="">Select Product Type</option>
                                                               
                                                               
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Product Trend :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>

                                                            <select style="width:100% !important;" name="product_trend_id" id="product_trend_id"
                                                                class="form-control select2 requiredField">
                                                                <option value="">Select Product Trend</option>
                                                              
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
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                    </div>


                                                    <table class="table table-bordere" id="">
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
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-primary">Clear
                                                        Form</button>
                                                </div>
                                                <?php
                                            echo Form::close();
                                            ?>
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
    <!-- </div> -->
<!-- </div> -->
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
                <form action="{{ url('sales/uploadProduct') }}" method="post" enctype="multipart/form-data">
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
                        <div class="col-sm-6 col-md-6"><button onclick="download_csv_file()" target="_self"
                                class="btn btn-info btn-sm btn-block">Download example</button></div>

                    </div>
                </form>

                <div class="col-sm-12 col-md-12">
                    <table class="table table-bordered table-sm mt-4">
                        <tbody>
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


                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
<script type="text/javascript">
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
            url: '/pdc/get_sub_category_by_id',
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
    var csv = 
        'SKU/Article,Product Name,Product Description,UOM,Packing,Product Barcode,Brand,Group,Category,Sub-Category,Product Classification,Product Type,Product Trend,Purchase Price,Sale Price,Mrp Price,Is Tax Apply,Tax Type,Tax Applied On,Tax %,Product Flat Discount(%),Min Qty,Max Qty,Image Link,H.S Code,Locality,Origin,Color,Product Status\n';

    // Define the dummy data corresponding to your table fields
    var csvFileData = [
        ['SKU001', 'Product A', 'Description A', 'Pcs', '10', '1234567', 'Brand A', 'Test Group', 'perfume', 'tester', 'Second Class', 'Product Type First', 'Product Trend First', '100.00', '120.00', '150.00', 'Yes', 'tax type 01', 'Sale', '10', '5', '10', '100', 'http://example.com/image1.jpg', '3901.2000', 'Local', 'Origin A', 'Red', 'Active'],
        ['SKU002', 'Product B', 'Description B', 'Kilogram', '20', '123456', 'Brand A', 'Test Group', 'perfume', 'tester', 'Second Class', 'Product Type First', 'Product Trend First', '200.00', '220.00', '250.00', 'No', 'tax type 02', 'Purchase', '15', '0', '20', '200', 'http://example.com/image2.jpg', '3916.9000', 'International', 'Origin B', 'Blue', 'Inactive'],
        // Add more rows as needed
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
    hiddenElement.download = 'ProductData.csv';
    hiddenElement.click();
}
</script>
<script type="text/javascript">
$('.select2').select2();
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

