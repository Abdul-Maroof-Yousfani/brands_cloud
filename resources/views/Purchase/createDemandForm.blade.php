<?php
   $m = Session::get('run_company');
   use App\Helpers\PurchaseHelper;
   use App\Helpers\CommonHelper;
   use App\Helpers\NotificationHelper;
   ?>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')
<?php
   $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`demand_no`,3,length(substr(`demand_no`,3))-4),signed integer)) reg from `demand` where substr(`demand_no`,-4,2) = " . date('m') . " and substr(`demand_no`,-2,2) = " . date('y') . "")->reg;
   $demand_no = 'pr' . ($str + 1) . date('my');
   ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Purchase Request </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'pad/addDemandDetail?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="demandsSection[]"
                                                class="form-control requiredField" id="demandsSection" value="1" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">PR NO. <span
                                                            class="rflabelsteric"><strong>*</strong></span></label>
                                                    <input readonly type="text" class="form-control requiredField"
                                                        placeholder="" name="pr_no" id="pr_no"
                                                        value="{{strtoupper($demand_no)}}" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">PR Date.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" class="form-control requiredField"
                                                        max="<?php echo date('Y-m-d') ?>" name="demand_date_1"
                                                        id="demand_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Ref No. <span class="rflabelsteric"></label>
                                                    <input autofocus type="text" class="form-control"
                                                        placeholder="Ref  No" name="slip_no_1" id="slip_no_1"
                                                        value="" />
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label class="sf-label">Required Date.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" class="form-control requiredField"
                                                           min="<?php echo date('Y-m-d') ?>" name="required_date"
                                                           id="required_date" value="" />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                    <label class="sf-label">Department / Sub Department</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control select2"
                                                        name="sub_department_id_1" id="sub_department_id_1">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $key => $y)
                                                        <option value="{{ $y->id}}">
                                                            {{ $y->department_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Type</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField select2" name="v_type"
                                                        id="v_type">
                                                        <option value="">Select Type</option>
                                                        @foreach(NotificationHelper::get_all_type() as $row)
                                                        <option value="{{ $row->id}}">{{ $row->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" name="demand_type" id="demand_type">
                                            <div class="row">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Description</label>
{{--                                                    <span class="rflabelsteric"><strong>*</strong></span>--}}
                                                    <textarea name="description_1" id="description_1" rows="4" cols="50"
                                                        style="resize:none;"
                                                        class="form-control requiredFiel"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="headquid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div>
                                                    <h2 class="subHeadingLabelClass">Item Details</h2>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive" id="">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 250px" class="text-center hide">Category</th>
                                                            <th style="width: 250px" class="text-center hide">Item Code</th>
                                                            <th style="width: 250px" class="text-center">Brand</th>
                                                            <th style="width: 250px" class="text-center">Product Name</th>
                                                            <th style="width: 250px" class="text-center">Product Type</th>
                                                            <th style="width: 250px" class="text-center">Product Barcode</th>
                                                            <th style="width: 250px" class="text-center">Product Classification</th>
                                                            <th style="width: 250px" class="text-center">Product Trend</th>
                                                            <th style="width: 100px" class="text-center">UOM<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 130px" class="text-center">QTY<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 130px" class="text-center hide">Closing Stock<span class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="width: 130px" class="text-center hide">Last Order QTY</th>
                                                            <th style="width: 130px" class="text-center hide">Last Received QTY</th>
                                                            <th style="width: 100px" class="text-center hide">History</th>
                                                            <th style="width: 100px" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="AppnedHtml">
                                                        <tr id="" class="AutoNo main">
                                                            <td class="hide">
                                                                <select onchange="get_sub_category_by_id('CategoryId1')" name="category" id="CategoryId1" class="form-control category select2 normal_width" style="width:200px !important;">
                                                                    <option value="">Select</option>
                                                                    @foreach (CommonHelper::get_all_category() as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->main_ic }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="hide">
                                                                <select autofocus onchange="get_product_by_id('sub_category1')" name="SubCategory[]" id="sub_category1" class="form-control select2" style="width:200px !important;">
                                                                    <option value="">Select Sub Category</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select style="width: 150px;" onChange="get_product_by_brand(this,1)" name="brand_id[]" class="form-control select2" id="brand_id1">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_all_brand() as $item)
                                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select onChange="get_type_barcode_by_product('productName1')" name="item_id[]" id="productName1" class="form-control requiredField select2 itemsclass" style="width:200px !important;">
                                                                    <option value="">Select Products</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input readonly type="text" class="form-control" name="product_type[]" id="product_type1">
                                                            </td>
                                                            <td>
                                                                <input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode1">
                                                            </td>
                                                            <td>
                                                                <input readonly type="text" class="form-control" name="product_classification[]" id="product_classification1">
                                                            </td>
                                                            <td>
                                                                <input readonly type="text" class="form-control" name="product_trend[]" id="product_trend1">
                                                            </td>
                                                            <td>
                                                                <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id1">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control next-total requiredField" name="quantity[]" id="quantity1">
                                                            </td>
                                                            <td class="hide">
                                                                <input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock1">
                                                            </td>
                                                            <td class="hide">
                                                                <input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty1">
                                                            </td>
                                                            <td class="hide">
                                                                <input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty1">
                                                            </td>
                                                            <td class="text-center hide"><input onclick="view_history(1)" type="checkbox" id="view_history1"></td>
                                                            <td class="text-center">
                                                                <div style="display: flex; justify-content: center; gap: 5px;">
                                                                    <a href="#" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php echo Form::close();?>
                    </div>
                    <div class="mp-20 text-right">
                        {{ Form::submit('Submit', ['class' => 'btnn btn-success']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>


document.addEventListener('keydown', function (event) {
        // Check if the Tab key is pressed
        if (event.key === 'Tab') {
            // Get the focused element
            let activeElement = document.activeElement;

            // Check if the active element is the last input of the last row
            let lastRow = document.querySelector('table tr:last-child');
            let lastInput = lastRow ? lastRow.querySelector('input:last-child') : null;

            if (activeElement === lastInput) {
                // Prevent default tabbing behavior
                event.preventDefault();

                // Call functions to add a new row
                AddMoreDetails(); // Function to add more details
                getTableRowCount(); // Function to update row count

                // Optionally, focus the first input in the new row after adding it
                let newRow = document.querySelector('table tr:last-child');
                if (newRow) {
                    let firstInput = newRow.querySelector('input');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }
            }
        }
    });



var Counter = 1;

function AddMoreDetails() {
    Counter++;

    // Update tabindex for existing elements
    $('input, select').each(function() {
        if ($(this).is(':disabled') || $(this).prop('readonly')) {
            $(this).attr('tabindex', '-1');
        } else {
            $(this).removeAttr('tabindex');
        }
    });

    // Get the value of brand_id from the last row before appending
    var previousBrandId = $('#brand_id' + (Counter - 1)).val();

    var category = 'CategoryId' + Counter;
    var sub_category = 'sub_category' + Counter;
    var ProductName = 'productName' + Counter;

    // Append the new row
    $('#AppnedHtml').append(
        '<tr class="RemoveRows' + Counter + ' AutoNo main">' +
        '<td class="hide">' +
        '<select onchange="get_sub_category_by_id(`' + category + '`)" name="category" id="CategoryId' + Counter +
        '" class="form-control category select2 normal_width">' +
        '<option value="">Select</option>' +
        '@foreach (CommonHelper::get_all_category() as $category):' +
        '<option value="{{ $category->id }}"> {{ $category->main_ic }} </option>' +
        '@endforeach' +
        '</select>' +
        '</td>' +
        '<td class="hide">' +
        '<select onchange="get_product_by_id(`' + sub_category + '`)" name="SubCategoryId[]" id="sub_category' +
        Counter +
        '" class="form-control select2">' +
        '<option> Select Sub Category</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<select style="width: 150px;" onChange="get_product_by_brand(this,`' + Counter +
        '`)" name="brand_id[]" class="form-control" id="brand_id' + Counter + '">' +
        '<option value="">Select</option>' +
        '@foreach(CommonHelper::get_all_brand() as $item)' +
        '<option value="{{$item->id}}">' +
        '{{$item->name}}' +
        '</option>' +
        '@endforeach' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<select onchange="get_type_barcode_by_product(`' + ProductName + '`)" name="item_id[]" id="productName' +
        Counter +
        '" class="form-control select2 itemsclass">' +
        '<option value="">Select Product</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="product_type[]" id="product_type' + Counter + '">' +
        '</td>' +
        '<td>' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode' + Counter +
        '">' +
        '</td>' +
        '<td>' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="product_classification[]" id="product_classification' +
        Counter + '">' +
        '</td>' +
        '<td>' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="product_trend[]" id="product_trend' + Counter + '">' +
        '</td>' +
        '<td>' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="uom_id[]" id="uom_id' + Counter + '">' +
        '</td>' +
        '<td>' +
        '<input type="text" class="form-control next-total requiredField" name="quantity[]" id="quantity' + Counter + '">' +
        '</td>' +
        '<td class="hide">' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock' + Counter + '">' +
        '</td>' +
        '<td class="hide">' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty' +
        Counter + '">' +
        '</td>' +
        '<td class="hide">' +
        '<input tabindex="-1" readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty' +
        Counter + '">' +
        '</td>' +
        '<td class="text-center hide">' +
        '<input onclick="view_history(' + Counter +
        ')" type="checkbox" id="view_history' + Counter + '">' +
        '</td>' +
        '<td class="text-center">' +
        '<div style="display: flex; justify-content: center; gap: 5px;">' +
        '<button type="button" class="btn btn-danger" id="BtnRemove' + Counter + '" onclick="RemoveSection(' +
        Counter + ')"><span class="glyphicon glyphicon-trash"></span></button>' +
        '<a href="#" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span></a>' +
        '</div>' +
        '</td>' +
        '</tr>'
    );

    // Initialize Select2 for the new row
    $('#CategoryId' + Counter).select2();
    $('#sub_category' + Counter).select2();
    $('#brand_id' + Counter).select2();
    $('#productName' + Counter).select2();

    // Set the new brand_id select to the previous value
    if (previousBrandId) {
        $('#brand_id' + Counter).val(previousBrandId).trigger('change');
    }

    // Focus on the first editable (not readonly or disabled) input or select in the new row
    const newRow = $(`#AppnedHtml tr.main:last`);
    const firstEditableField = newRow.find('input:not([readonly]):enabled, select:not([readonly]):enabled').first();

    // Set focus on the first editable field
    if (firstEditableField.length) {
        firstEditableField.focus();
    }
}


$(document).on('keydown', '.next-total', function (event) {
    // Check if the Tab key is pressed and if this is the last .total field
    if (event.key === "Tab" && $(this).is('.next-total:last')) {
        event.preventDefault(); // Prevent the default Tab behavior
        AddMoreDetails(); // Add a new row

        // After adding a new row, focus the first editable (not readonly or disabled) input or select in the new row
        setTimeout(() => {
            const newField = $(`#AppnedHtml tr.main:last`)
                .find('input:not([readonly]):enabled, select:not([readonly]):enabled')
                .first();
            console.log('Focusing on:', newField);
            newField.focus();
        }, 0); // Use setTimeout to wait until the new row is fully rendered
    }
});




{{--function AddMoreDetails() {--}}

{{--    Counter++;--}}
{{--    var category = 'CategoryId' + Counter;--}}
{{--    var sub_category = 'sub_category' + Counter;--}}
{{--    var ProductName = 'productName' + Counter;--}}
{{--    $('#AppnedHtml').append(--}}
{{--        '<tr class="RemoveRows' + Counter + '  AutoNo main">' +--}}
{{--        '<td class="hide">' +--}}
{{--        '<select onchange="get_sub_category_by_id(`' + category + '`)" name="category" id="CategoryId' + Counter +--}}
{{--        '"  class="form-control category select2 normal_width">' +--}}
{{--        '<option value="">Select</option>' +--}}
{{--        '@foreach (CommonHelper::get_all_category() as $category):' +--}}
{{--        '<option value="{{ $category->id }}"> {{ $category->main_ic }} </option>' +--}}
{{--        '@endforeach' +--}}
{{--        '</select>' +--}}
{{--        '<td class="hide">' +--}}
{{--        '<select onchange="get_product_by_id(`' + sub_category + '`)" name="SubCategoryId[]" id="sub_category' +--}}
{{--        Counter +--}}
{{--        '" class="form-control select2">' +--}}
{{--        '<option> Select Sub Category</option>' +--}}
{{--        '</select>' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<select style="width: 150px;" onChange="get_product_by_brand(this,`' + Counter +--}}
{{--        '`)" name="brand_id[]" class="form-control" id="brand_id' + Counter + '">' +--}}
{{--        '<option value="">Select</option>' +--}}
{{--        '@foreach(CommonHelper::get_all_brand() as $item)' +--}}
{{--        '<option value="{{$item->id}}">' +--}}
{{--        '{{$item->name}}' +--}}
{{--        '</option>' +--}}
{{--        '@endforeach' +--}}
{{--        '</select>' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<select onchange="get_type_barcode_by_product(`' + ProductName + '`)" name="item_id[]" id="productName' +--}}
{{--        Counter +--}}
{{--        '" class="form-control select2 itemsclass">' +--}}
{{--        '<option value="">Select Product</option>' +--}}
{{--        '</select>' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<input readonly type="text" class="form-control" name="product_type[]" id="product_type' + Counter + '">' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode' + Counter +--}}
{{--        '">' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<input readonly type="text" class="form-control" name="product_classification[]" id="product_classification' +--}}
{{--        Counter +--}}
{{--        '">' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<input readonly type="text" class="form-control" name="product_trend[]" id="product_trend' + Counter +--}}
{{--        '">' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<input readonly type="text" class="form-control" name="uom_id[]" id="uom_id' + Counter + '">' +--}}
{{--        '</td>' +--}}
{{--        '<td>' +--}}
{{--        '<input type="text" class="form-control requiredField" name="quantity[]" id="quantity' + Counter + '">' +--}}
{{--        '</td>' +--}}
{{--        '<td class="hide">' +--}}
{{--        '<input readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock' + Counter +--}}
{{--        '">' +--}}
{{--        '</td>' +--}}
{{--        '<td class="hide">' +--}}
{{--        '<input readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty' +--}}
{{--        Counter + '">' +--}}
{{--        '</td>' +--}}
{{--        '<td class="hide">' +--}}
{{--        '<input readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty' +--}}
{{--        Counter + '">' +--}}
{{--        '</td>' +--}}
{{--        '<td  class="text-center hide" style=""><input onclick="view_history(' + Counter +--}}
{{--        ')" type="checkbox" id="view_history' + Counter + '">' +--}}

{{--        '</td>' +--}}
{{--        '<td  class="text-center" style="">' +--}}
{{--        '<button type="button" class="btn  btn-danger" id="BtnRemove' + Counter + '" onclick="RemoveSection(' +--}}
{{--        Counter + ')"><span class="glyphicon glyphicon-trash"></span></button>' +--}}
{{--        '<a href="#" style="display:block;margin-block:5px;" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span>  </a>'+--}}
{{--        '</td>' +--}}
{{--        '</tr>' +--}}
{{--        '</tr>' +--}}
{{--        '</tbody>' +--}}
{{--        '</table>');--}}
{{--    var AutoNo = $(".AutoNo").length;--}}
{{--    $('#span').text(AutoNo);--}}


{{--    $('#category_id' + Counter).select2();--}}
{{--    $('#item_id' + Counter).select2();--}}
{{--    var AutoCount = 1;--}}
{{--    $(".AutoCounter").each(function() {--}}
{{--        AutoCount++;--}}
{{--        $(this).html(AutoCount);--}}
{{--    });--}}

{{--}--}}



function RemoveSection(Row) {
    //            alert(Row);
    $('.RemoveRows' + Row).remove();
    $(".AutoCounter").html('');
    var AutoCount = 1;
    $(".AutoCounter").each(function() {
        AutoCount++;
        $(this).html(AutoCount);
    });
    var AutoNo = $(".AutoNo").length;
    $('#span').text(AutoNo);
}


function clear_fiel(id) {
    $('#' + id).prop('readonly', false);
    $('#' + id).val('');

}

$('.sam_jass').bind("enterKey", function(e) {


    $('#items').modal('show');
    e.preventDefault();

});
$('.sam_jass').keyup(function(e) {
    if (e.keyCode == 13) {
        selected_id = this.id;
        $(this).trigger("enterKey");
        e.preventDefault();

    }

});


$('.stop').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

function get_sub_category_by_id(id) {
    var category = $('#' + id).val();
    var index_val = id.replace("CategoryId", "");

    $('#sub_category' + index_val).html('');
    if (category) {
        $.ajax({
            url: '/pdc/get_sub_category_by_id',
            type: 'Get',
            data: {
                category: category
            },
            success: function(response) {
                $('#sub_category' + index_val).append(response);
            }
        });
    }

}

$(function() {

    $(".btn-success").click(function(e) {
        var purchaseRequest = new Array();
        var val;
        //$("input[name='demandsSection[]']").each(function(){
        purchaseRequest.push($(this).val());
        //});
        var _token = $("input[name='_token']").val();
        for (val of purchaseRequest) {
            jqueryValidationCustom();
            if (validate == 0) {

                $('#cashPaymentVoucherForm').submit();
            } else {
                return false;
            }
        }

    });
});

function get_product_by_id(id) {

    var sub_category = $('#' + id).val();
    var index_val = id.replace("sub_category", "");
    console.log('index value is ' + index_val);
    $('#productName' + index_val).html('');

    //  $('#productName').empty();
    var category = $('#CategoryId').val();
    //  var sub_category = $('#sub_category').val();
    //  var item_master_id = $('#item_master').val();
    if (sub_category > 0) {
        $.ajax({
            url: '/pdc/get_product_by_id',
            type: 'Get',
            data: {
                category: category,
                sub_category: sub_category
            },
            success: function(response) {
                $('#productName' + index_val).append(response);

            }
        });
    } else {
        $('#item_code').val('');
    }

}
</script>
<script>
function get_detail(id, number) {
    var item = $('#' + id).val();


    $.ajax({
        url: '{{url(' / pdc / get_data ')}}',
        data: {
            item: item
        },
        type: 'GET',
        success: function(response) {

            var data = response.split(',');
            $('#uom_id' + number).val(data[0]);
            $('#last_ordered_qty' + number).val(data[1]);
            $('#last_received_qty' + number).val(data[2]);
            $('#closing_stock' + number).val(data[3]);

        }
    })



}

function get_type_barcode_by_product(id) {

    var productName = $('#' + id).val();
    var index_val = id.replace("productName", "");
    $('#product_type' + index_val).html('');
    $('#product_barcode' + index_val).html('');
    $('#product_classification' + index_val).html('');
    $('#product_trend' + index_val).html('');
    $('#uom_id' + index_val).html('');

    //  $('#productName').empty();
    var category = $('#CategoryId').val();
    //  var sub_category = $('#sub_category').val();
    //  var item_master_id = $('#item_master').val();
    if (productName > 0) {
        $.ajax({
            url: '/pdc/get_type_barcode_by_product',
            type: 'Get',
            data: {
                // category: category,
                productName: productName
            },
            success: function(response) {
                $('#product_type' + index_val).val(response.product_type_id);
                $('#product_barcode' + index_val).val(response.product_barcode);
                $('#product_classification' + index_val).val(response.product_classification_id);
                $('#product_trend' + index_val).val(response.product_trend_id);
                $('#uom_id' + index_val).val(response.uom);
            }
        });
    } else {
        $('#item_code').val('');
    }
}


function get_product_by_brand(element, number) {
    var value = element.value;
    $(element).closest('.main').find('.itemsclass').empty();
    $.ajax({
        url: '{{ url("/getSubItemByBrand") }}',
        type: 'Get',
        data: {
            id: value
        },
        success: function(data) {
            $(element).closest('.main').find('.itemsclass').append(data);
        }
    });
}
</script>
<script>
function view_history(id) {

    var v = $('#item_id' + id).val();


    if ($('#view_history' + id).is(":checked")) {

        if (v != 'Select') {

            showDetailModelOneParamerter('pdc/viewHistoryOfItem?id=' + v);
        } else {

        }

    }





}
</script>
<script type="text/javascript">
$('.select2').select2();


function get_item_name(index) {
    var item = $('#item_id' + index).val();
    var uom = item.split('@');
    $('#uom_id' + index).val(uom[1]);
    $('#item_code' + index).val(uom[2]);
}





//   $(document).ready(function() {

//         $('#customer').select2();
//         $('#customer_name').select2();
//     })

//     $('body').on('click', '.removerow', function() {
//         // Show the confirmation dialog
//         Swal.fire({
//             icon: 'warning', // Change icon to warning for confirmation
//             title: 'Are you sure?',
//             text: 'This action cannot be undone.',
//             showCancelButton: true, // Show cancel button
//             confirmButtonColor: '#3085d6', // Set the confirm button color
//             cancelButtonColor: '#d33', // Set the cancel button color
//             confirmButtonText: 'Yes, remove it!' // Set the confirm button text
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 // Remove the row if the user confirmed
//                 $(this).closest('tr').remove();
//                 getTableRowCount()
//                 // Swal.fire(
//                 //     'Removed!',
//                 //     'The row has been removed.',
//                 //     'success' // Show a success message
//                 // );
//             }
//         });
//     });
    
//     $('#brand_id1').select2();
//     $('#product_id1').select2();

</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection