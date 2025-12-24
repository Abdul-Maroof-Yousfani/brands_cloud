<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$MenuPermission = true;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

if($accType =='user'):
    $user_rights = DB::table('menu_privileges')->where([['emp_code','=',Auth::user()->emp_code],['compnay_id','=',Session::get('run_company')]]);
    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
    if(in_array(81,$submenu_ids))
    {
        $MenuPermission = true;
    }
    else
    {
        $MenuPermission = false;
    }
endif;
?>
@extends('layouts.default')
@section('content')
@include('select2')
@include('modal')
@include('number_formate')

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

<script>
    var counter = 1;
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Create Direct Purchase Order Form</h2>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <?php
                        if($MenuPermission == true):?>
                            <?php else:?>
                            <span class="subHeadingLabelClass text-danger text-center" style="float: right">Permission
                                Denied <span style='font-size:45px !important;'>&#128546;</span></span>
                            <?php endif;
                        ?>
                        </div>
                    </div>
                    <?php if($MenuPermission == true):?>
                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'stad/insertDirectPurchaseOrder?m='.$m.'','id'=>'addPurchaseRequestDetail','class'=>'stop'));?>
                    <?php
                        $purchaseRequestNo=CommonHelper::get_unique_po_no(1);
                    ?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PO NO.</label>
                                            <input readonly type="text" class="form-control requiredField"
                                                placeholder="" name="po_no" id="po_no"
                                                value="{{strtoupper($purchaseRequestNo)}}" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PO DATE.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField"
                                                max="<?php echo date('Y-m-d') ?>" name="po_date" id="po_date"
                                                value="<?php echo date('Y-m-d') ?>" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Mode of delivery</label>
                                            <input type="text" class="form-control" placeholder="Terms Of Delivery"
                                                name="term_of_del" id="term_of_del" value="" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PO Type</label>
                                            <select onchange="get_po(this.id); po_type_change(this);" name="po_type"
                                                id="po_type" class="form-control">
                                                <option value="">Select Option</option>
                                                @foreach(CommonHelper::get_international_to_types_direct() as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>


                                    <div class="row">


                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Destination</label>
                                            <input style="text-transform: capitalize;" type="text" class="form-control"
                                                placeholder="" name="destination" id="destination" value="" />
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"> <a href="#"
                                                    onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');"
                                                    class="">Vendor</a></label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select onchange="get_address(); get_discount();" name="supplier_id"
                                                id="supplier_id" class="form-control requiredField select2">
                                                <option value="">Select Vendor</option>

                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"> <a href="#"
                                                    onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')"
                                                    class="">Currency</a></label>
                                            <span class="rflabelsteric"></span>
                                            <select onchange="claculation(1);get_rate()" name="curren" id="curren"
                                                class="form-control select2 requiredField">
                                                <option value="0,1"> PKR</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"> Currency Rate</label>
                                            <span class="rflabelsteric"></span>
                                            <input class="form-control" type="text" name="currency_rate"
                                                id="currency_rate" />

                                        </div>

                                        <input type="hidden" name="curren_rate" id="curren_rate" value="1" />

                                    </div>

                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Mode/ Terms Of Payment <span
                                                    class="rflabelsteric"></span></label>
                                            <input onkeyup="calculate_due_date()" value="0" type="number"
                                                class="form-control requiredField" placeholder=""
                                                name="model_terms_of_payment" id="model_terms_of_payment" value="" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Payment Due Date<span
                                                    class="rflabelsteric"></span></label>
                                            <input type="date" class="form-control" placeholder="" name="due_date"
                                                id="due_date" value="" readonly />
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="sf-label">Supplier's Address</label>
                                            <input style="text-transform: capitalize;" readonly type="text"
                                                class="form-control" placeholder="" name="address" id="addresss"
                                                value="" />
                                        </div>
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Supplier's NTN</label>
                                            <input readonly type="text" class="form-control" placeholder="Ntn"
                                                name="ntn" id="ntn_id" value="" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Remarks</label>
                                            <textarea name="Remarks" id="terms_and_condition" class="form-control"
                                                placeholder="Remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Terms & Condition</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="main_description" id="main_description" rows="4" cols="50" style="resize:none;font-size: 11px;" class="form-control requiredField">YOUR NTN NUMBER AND VALID INCOME TAX EXEMPTION WILL BE REQUIRED FOR PAYMENT, OTHER WISE INCOME TAX WILL BE DEDUCTED AS PER FOLLOWINGS:
                                                INCOME TAX:
                                                FOR COMPANIES SUPPLIES 4% & SERVICES 8% (FILER) / 12% (NON FILER)
                                                FOR INDIVIUALS OR AOP SUPPLIES 4.5% & SERVICES 10% (FILER) / 15% (NON FILER)
                                                SALES TAX ON SUPPLIES:
                                                A WITHOLDING AGENT SHALL DEDUCT AN AMOUNT AS PER SRO 897 /2013
                                                SALES TAX ON SERVICES:
                                                A WITHOLDING AGENT SHALL DEDUCT AN AMOUNT AS PER SRB WITHHOLDING RULES-2014
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th colspan="7" class="text-center">Purchase Order Detail</th>
                                                   
                                                    <th class="text-center">
                                                        <span class="badge badge-success" id="span">1</span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 250px" class="text-center">Brand</th>
                                                    <th class="text-center" style="width: 35%;">Product</th>
                                                    <th class="text-center">Product Type</th>
                                                    <th class="text-center">Product Barcode</th>
                                                    <th class="text-center">Product Classification</th>
                                                    <th class="text-center">Product Trend</th>
                                                    <th class="text-center">Uom<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Actual Qty<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Rate<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Amount(PKR)<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center"> Amount<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center hide">Discount %<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center hide">Discount Amount<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Net Amount<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Delete<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                    <th class="text-center">Add Row<span
                                                            class="rflabelsteric"><strong>*</strong></span></th>
                                                </tr>
                                            </thead>
                                            <tbody id="AppnedHtml">
                                                <tr title="1" class="AutoNo main">
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
                                                        <input readonly type="text" class="form-control"
                                                            name="product_type[]" id="product_type1">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control"
                                                            name="product_barcode[]" id="product_barcode1">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control"
                                                            name="classification_name[]" id="product_classification">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control"
                                                            name="product_trend[]" id="product_trend1">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control" name="uom_id[]"
                                                            id="uom_id1">
                                                        <input readonly type="hidden" class="form-control mainIcId"
                                                            name="cat_id[]" id="mainIcId_1">
                                                    </td>
                                                    <td>
                                                        <input type="text" onkeyup="claculation('1')"
                                                            class="form-control requiredField ActualQty"
                                                            name="actual_qty[]" id="actual_qty1"
                                                            placeholder="ACTUAL QTY" min="1" value="">
                                                    </td>

                                                    <td>
                                                        <input type="text" onkeyup="claculation('1')"
                                                            class="form-control requiredField ActualRate" name="rate[]"
                                                            id="rate1" placeholder="RATE" min="1" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="amount[]"
                                                            id="amount1" placeholder="AMOUNT" min="1" value="" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control actual_amount"
                                                            name="actual_amount[]" id="actual_amount1"
                                                            placeholder="AMOUNT" min="1" value="" readonly>
                                                    </td>
                                                    <td class="hide">
                                                        <input type="text " onkeyup="discount_percent(this.id)"
                                                            class="form-control " name="discount_percent[]"
                                                            id="discount_percent1" placeholder="DISCOUNT" min="1"
                                                            value="0">
                                                    </td>
                                                    <td class="hide">
                                                        <input type="text" onkeyup="discount_amount(this.id)"
                                                            class="form-control " name="discount_amount[]"
                                                            id="discount_amount1" placeholder="DISCOUNT" min="1"
                                                            value="0">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control net_amount_dis"
                                                            name="after_dis_amount[]" id="after_dis_amount1"
                                                            placeholder="NET AMOUNT" min="1" value="0.00" readonly>
                                                    </td>
                                                    <td style="background-color: #ccc">
                                                        <input onclick="view_history(1)" type="checkbox"
                                                            id="view_history1">
                                                    </td>
                                                    <td  class="text-center" >
                                                        <input style="width: inherit !important;" type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="+" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody>
                                                <tr style="font-size:large;font-weight: bold">
                                                    <td class="text-center" colspan="5">Total</td>
                                                    <td class="text-right" colspan="1"><input readonlyclass="form-control" type="text" id="actual_net" /> </td>
                                                    <td class="text-center" colspan="2"></td>
                                                    <td class="text-right" colspan="1"><input readonlyclass="form-control" type="text" id="net" /> </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                            <th class="text-center" colspan="3">Sales Tax Account Head</th>
                                            <th class="text-center" colspan="3">Sales Tax Amount</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select onchange="sales_tax(this.id)" class="form-control select2"
                                                        id="sales_taxx" name="sales_taxx">
                                                        <option value="0">Select</option>
                                                        @foreach (ReuseableCode::get_all_sales_tax() as $row)
                                                        <option value="{{ $row->percent.'@'.$row->acc_id }}"
                                                            {{($row->percent == "17.000")? 'selected' : ''}}>
                                                            {{ $row->percent }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-right" colspan="3">
                                                    <input onkeyup="tax_by_amount(this.id)" type="text"
                                                        class="form-control" name="sales_amount_td"
                                                        id="sales_amount_td" />
                                                </td>
                                                <input type="hidden" name="sales_amount" id="sales_tax_amount" />
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr style="font-size:large;font-weight: bold">
                                                <td class="text-center" colspan="3">Total Amount After Tax</td>
                                                <td id="" class="text-right" colspan="3"><input readonly
                                                        class="form-control" type="text" id="net_after_tax" /> </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <table>
                                <tr>
                                    <td style="text-transform: capitalize;" id="rupees"></td>
                                    <input type="hidden" value="" name="rupeess" id="rupeess1" />
                                </tr>
                            </table>
                            <input type="hidden" id="d_t_amount_1" />
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo Form::close();?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>


<!-- tabs button -->
<!-- <script>

    $(document).on('keydown', '.next-total', function (event) {
        if (event.key === "Tab" && $(this).is('.next-total:last')) {
            event.preventDefault();
            AddMoreDetails();

            setTimeout(() => {
                const newField = $('#AppnedHtml tr.main:last')
                    .find('input:not([readonly]):enabled, select:not([readonly]):enabled')
                    .first();
                newField.focus();
            }, 200);
        }
    });



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

    $(document).on('keydown', '.net_amount_dis', function (event) {
        // Check if the Tab key is pressed and if this is the last .net_amount_dis field
        if (event.key === "Tab" && $(this).is('.net_amount_dis:last')) {
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

</script> -->



<!-- AddMoreDetails -->
<script>



            var Counter = 1;

            // TAB key navigation handler
            $(document).on('keydown', 'input, select, textarea', function (e) {
                if (e.key === "Tab") {
                    let $inputs = $('input:enabled:not([readonly]), select:enabled:not([readonly])').filter(':visible');
                    let idx = $inputs.index(this);

                    // Agar last editable field hai
                    if (idx === $inputs.length - 1) {
                        e.preventDefault();
                        AddMoreDetails();

                        // Nayi row ka pehla editable focus
                        setTimeout(() => {
                            const $newField = $(`#AppnedHtml tr.main:last`)
                                .find('input:enabled:not([readonly]), select:enabled:not([readonly])')
                                .first();
                            if ($newField.length) $newField.focus();
                        }, 200);
                    }
                }
            });

// Replace your existing AddMoreDetails() with this one
function AddMoreDetails() {
    Counter++;

    // get previous brand select ki hui value
    var previousBrandId = $('#brand_id' + (Counter - 1)).val();

    // append new row
    $('#AppnedHtml').append(`
        <tr id="RemoveRows${Counter}" class="AutoNo main">
            <td>
                <select style="width: 150px;" 
                    onChange="get_product_by_brand(this, ${Counter})" 
                    name="brand_id[]" 
                    class="form-control select2 brand-select" 
                    id="brand_id${Counter}">
                    <option value="">Select</option>
                    @foreach(CommonHelper::get_all_brand() as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </td>

            <td>
                <select name="item_id[]" id="productName${Counter}" 
                    onchange="get_type_barcode_by_product('productName${Counter}')" 
                    class="form-control select2 product-select itemsclass" 
                    style="width:200px !important;">
                    <option value="">Select Products</option>
                </select>
            </td>
            <td><input readonly type="text" class="form-control" name="product_type[]" id="product_type${Counter}"></td>
            <td><input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode${Counter}"></td>
            <td><input readonly type="text" class="form-control" name="classification_name[]" id="product_classification${Counter}"></td>
            <td><input readonly type="text" class="form-control" name="product_trend[]" id="product_trend${Counter}"></td>
            <td>
                <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}">
                <input readonly type="hidden" class="form-control mainIcId" name="cat_id[]" id="mainIcId_${Counter}">
            </td>
            <td><input type="text" onkeyup="claculation(${Counter})" class="form-control ActualQty" name="actual_qty[]" id="actual_qty${Counter}" placeholder="ACTUAL QTY"></td>
            <td><input type="text" onkeyup="claculation(${Counter})" class="form-control ActualRate" name="rate[]" id="rate${Counter}" placeholder="RATE"></td>
            <td><input readonly type="text" class="form-control" name="amount[]" id="amount${Counter}" placeholder="AMOUNT"></td>
            <td><input readonly type="text" class="form-control actual_amount" name="actual_amount[]" id="actual_amount${Counter}" placeholder="AMOUNT"></td>
            <td class="hide"><input type="text" onkeyup="discount_percent(this.id)" class="form-control" value="0" name="discount_percent[]" id="discount_percent${Counter}" placeholder="DISCOUNT"></td>
            <td class="hide"><input type="text" onkeyup="discount_amount(this.id)" class="form-control" value="0" name="discount_amount[]" id="discount_amount${Counter}" placeholder="DISCOUNT"></td>
            <td><input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" placeholder="NET AMOUNT"></td>
            <td><input onclick="view_history(${Counter})" type="checkbox" id="view_history${Counter}"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})"> - </button>
            </td>
        </tr>
    `);


    // initialize select2 for new dropdown
    $('#brand_id' + Counter).select2();

    // agar previous row me koi brand select thi to nayi row me wahi lagao
    if (previousBrandId) {
        $('#brand_id' + Counter).val(previousBrandId).trigger('change');
    }



    // get references
    var $brand = $(`#brand_id${Counter}`);
    var $product = $(`#productName${Counter}`);

    // init select2 ON NEW ELEMENTS only
    try {
        if (typeof $brand.select2 === 'function') $brand.select2({ width: 'resolve' });
        if (typeof $product.select2 === 'function') $product.select2({ width: 'resolve' });
    } catch (e) { /* ignore if already init issues */ }

 

    // Helper: open product select2, focus search and scroll instantly to the SELECT element
    function openAndFocusProduct() {
        if (!$product.length) return;

        // INSTANT jump instead of smooth animate
        window.scrollTo({
            top: $product.offset().top - 120,
            behavior: "instant" // ⚡ no delay
        });

        try {
            $product.focus();

            $product.one('select2:open', function() {
                var searchBox = document.querySelector('.select2-container--open .select2-search__field');
                if (searchBox) searchBox.focus();
            });

            $product.select2('open');
        } catch (err) {
            setTimeout(function() {
                var searchBox = document.querySelector('.select2-container--open .select2-search__field');
                if (searchBox) searchBox.focus();
            }, 100);
        }
    }

    // If product already has options, open immediately
    if ($product.find('option').length > 1) {
        setTimeout(openAndFocusProduct, 50);
    } else {
        var opened = false;
        var obs = new MutationObserver(function() {
            if (opened) return;
            if ($product.find('option').length > 1) {
                opened = true;
                try { obs.disconnect(); } catch(e){}
                setTimeout(openAndFocusProduct, 50);
            }
        });

        try {
            obs.observe($product[0], { childList: true });
        } catch (e) {
            setTimeout(openAndFocusProduct, 300);
        }

        // safety fallback
        setTimeout(function() {
            if (!opened) {
                try { obs.disconnect(); } catch(e){}
                openAndFocusProduct();
            }
        }, 1500);
    }

    // re-bind your tab handler if you use it
    if (typeof bindTabEvent === 'function') bindTabEvent();
}





// Function to bind tab event t ||   tab to last
// function bindTabEvent() {
//     // Har row ka last input (aap choose karlo kis field pe naya row chahiye)
//     $('#AppnedHtml tr:last input, #AppnedHtml tr:last select').last().off('keydown').on('keydown', function(e){
//         if(e.key === "Tab") {
//             e.preventDefault(); // default tab skip karega
//             AddMoreDetails();
//         }
//     });
// }




// Function to bind tab event ||   tab to rate
function bindTabEvent() {
    // Sirf last row ke rate field ko target karo
    let $lastRate = $('#AppnedHtml tr:last').find('input[id^="rate"]');

    $lastRate.off('keydown').on('keydown', function(e){
        if (e.key === "Tab") {
            e.preventDefault(); // default tab skip karega

            // Nayi row add karo
            AddMoreDetails();

            // 👇 cursor ko new row ke rate field par le aao
            setTimeout(function () {
                let $newRate = $("#rate" + Counter);
                if ($newRate.length) {
                    $newRate.focus();
                }
            }, 200);
        }
    });
}



// Pehle row ke liye bind karo
$(document).ready(function(){
    bindTabEvent();
});





    // var Counter = 1;
    //     function AddMoreDetails() {
    //     Counter++;

    //     // Update tabindex for existing elements
    //     $('input, select').each(function () {
    //         if ($(this).is(':disabled') || $(this).prop('readonly')) {
    //             $(this).attr('tabindex', '-1');
    //         } else {
    //             $(this).removeAttr('tabindex');
    //         }
    //     });

    //     // Append new row to the table
    //     $('#AppnedHtml').append(`
    //         <tr id="RemoveRows${Counter}" class="AutoNo main">
    //             <td>
    //                 <select style="width: 150px;" onChange="get_product_by_brand(this, '${Counter}')" name="brand_id[]" class="form-control select2" id="brand_id${Counter}">
    //                     <option value="">Select</option>
    //                     @foreach(CommonHelper::get_all_brand() as $item)
    //                         <option value="{{$item->id}}">{{$item->name}}</option>
    //                     @endforeach
    //                 </select>
    //             </td>
    //             <td class="AutoCounter" title="${Counter}">
    //                 <select name="item_id[]" id="productName${Counter}" onchange="get_type_barcode_by_product('productName${Counter}'); itemChange(${Counter})" class="form-control select2 itemsclass">
    //                     <option value="">Select Products</option>
    //                 </select>
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control" name="product_type[]" id="product_type${Counter}" tabindex="-1">
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode${Counter}" tabindex="-1">
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control" name="classification_name[]" id="product_classification${Counter}" tabindex="-1">
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control" name="product_trend[]" id="product_trend${Counter}" tabindex="-1">
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}" tabindex="-1">
    //                 <input readonly type="hidden" class="form-control mainIcId" name="cat_id[]" id="mainIcId_${Counter}">
    //             </td>
    //             <td>
    //                 <input type="text" onkeyup="claculation(${Counter})" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty${Counter}" placeholder="ACTUAL QTY">
    //             </td>
    //             <td>
    //                 <input type="text" onkeyup="claculation(${Counter})" class="form-control requiredField ActualRate" name="rate[]" id="rate${Counter}" placeholder="RATE">
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control next-total" name="amount[]" id="amount${Counter}" placeholder="AMOUNT" tabindex="-1">
    //             </td>
    //             <td>
    //                 <input type="text" class="form-control actual_amount" name="actual_amount[]" id="actual_amount${Counter}" placeholder="AMOUNT" readonly tabindex="-1">
    //             </td>
    //             <td class="hide">
    //                 <input type="text" onkeyup="discount_percent(this.id)" class="form-control" value="0" name="discount_percent[]" id="discount_percent${Counter}" placeholder="DISCOUNT">
    //             </td>
    //             <td class="hide">
    //                 <input type="text" onkeyup="discount_amount(this.id)" class="form-control" value="0" name="discount_amount[]" id="discount_amount${Counter}" placeholder="DISCOUNT">
    //             </td>
    //             <td>
    //                 <input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" placeholder="NET AMOUNT" tabindex="-1">
    //             </td>
    //             <td>
    //                 <input onclick="view_history(${Counter})" type="checkbox" id="view_history${Counter}">&nbsp;
    //             </td>
    //             <td class="text-center">
    //                 <button type="button" class="btn btn-sm btn-danger" id="BtnRemove${Counter}" onclick="RemoveSection(${Counter})"> - </button>
    //             </td>
    //         </tr>
    //     `);

    //     // Update AutoNo and select2 initialization
    //     var AutoNo = $(".AutoNo").length;
    //     $('#span').text(AutoNo);
    //     $('.select2').select2();

    //     // Update AutoCounter titles
    //     $(".AutoCounter").each(function (index) {
    //         $(this).prop('title', index + 1);
    //     });
    // }
    
    function RemoveSection(Row) {
        $('#RemoveRows' + Row).remove();
        var AutoCount = 1;
        $(".AutoCounter").each(function() {
            AutoCount++;
            $(this).prop('title', AutoCount);
        });
        var AutoNo = $(".AutoNo").length;
        $('#span').text(AutoNo);

        net_amount();
        sales_tax('sales_taxx');
    }

    function get_po(id) {
        var number = $('#' + id).val();
        var po = $('#po_no').val();
        
        if (number == 1) {
            var res = po.slice(2, 9);
            var pl_no = 'PL' + res;
            $('#po_no').val(pl_no);
        }
        if (number == 2) {
            var res = po.slice(2, 9);
            var pl_no = 'PS' + res;
            $('#po_no').val(pl_no);
        }
        if (number == 3) {
            var res = po.slice(2, 9);
            var pl_no = 'PI' + res;
            $('#po_no').val(pl_no);
        }
    }

    function po_type_change(selectedElement) {
        var selectedValue = selectedElement.value;
        
        if (selectedValue) {
            $.ajax({
                url: '{{url('/pdc/get_currency_vendor_by_to_type_direct')}}',
                type: 'Get',
                data: {
                    id: selectedValue
                },
                success: function(response) {
                    $('#curren').empty().append(response.currencyOptions);
                    $('#supplier_id').empty().append(response.vendorOptions);
                }
            });
        }
    }

    // old comment
    // function AddMoreDetails() {
    //         Counter++;

    //         // Update tabindex for existing elements
    //         $('input, select').each(function () {
    //             if ($(this).is(':disabled') || $(this).prop('readonly')) {
    //                 $(this).attr('tabindex', '-1');
    //             } else {
    //                 $(this).removeAttr('tabindex');
    //             }
    //         });

    //         // Append new row to the table
    //         $('#AppnedHtml').append(`
    //             <tr id="RemoveRows${Counter}" class="AutoNo main">
    //                 <td>
    //                     <select style="width: 150px;" onChange="get_product_by_brand(this, '${Counter}')" name="brand_id[]" class="form-control select2" id="brand_id${Counter}">
    //                         <option value="">Select</option>
    //                         @foreach(CommonHelper::get_all_brand() as $item)
    //                             <option value="{{$item->id}}">{{$item->name}}</option>
    //                         @endforeach
    //                     </select>
    //                 </td>
    //                 <td class="AutoCounter" title="${Counter}">
    //                     <select name="item_id[]" id="item_${Counter}" onchange="get_type_barcode_by_product(${Counter});" class="form-control select2 itemsclass">
    //                         <option value="">Select</option>
    //                     </select>
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control" name="product_type[]" id="product_type${Counter}" tabindex="-1">
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode${Counter}" tabindex="-1">
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control" name="classification_name[]" id="classification_name${Counter}" tabindex="-1">
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control" name="product_trend[]" id="product_trend${Counter}" tabindex="-1">
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}" tabindex="-1">
    //                     <input readonly type="hidden" class="form-control mainIcId" name="cat_id[]" id="mainIcId_${Counter}">
    //                 </td>
    //                 <td>
    //                     <input type="text" onkeyup="claculation(${Counter})" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty${Counter}" placeholder="ACTUAL QTY">
    //                 </td>
    //                 <td>
    //                     <input type="text" onkeyup="claculation(${Counter})" class="form-control requiredField ActualRate" name="rate[]" id="rate${Counter}" placeholder="RATE">
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control next-total" name="amount[]" id="amount${Counter}" placeholder="AMOUNT" tabindex="-1">
    //                 </td>
    //                 <td>
    //                     <input type="text" class="form-control actual_amount" name="actual_amount[]" id="actual_amount${Counter}" placeholder="AMOUNT" readonly tabindex="-1">
    //                 </td>
    //                 <td class="hide">
    //                     <input type="text" onkeyup="discount_percent(this.id)" class="form-control" value="0" name="discount_percent[]" id="discount_percent${Counter}" placeholder="DISCOUNT">
    //                 </td>
    //                 <td class="hide">
    //                     <input type="text" onkeyup="discount_amount(this.id)" class="form-control" value="0" name="discount_amount[]" id="discount_amount${Counter}" placeholder="DISCOUNT">
    //                 </td>
    //                 <td>
    //                     <input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" placeholder="NET AMOUNT" tabindex="-1">
    //                 </td>
    //                 <td>
    //                     <input onclick="view_history(${Counter})" type="checkbox" id="view_history${Counter}">&nbsp;
    //                 </td>
    //                 <td class="text-center">
    //                     <button type="button" class="btn btn-sm btn-danger" id="BtnRemove${Counter}" onclick="RemoveSection(${Counter})"> - </button>
    //                 </td>
    //             </tr>
    //         `);

    //         // Update AutoNo and select2 initialization
    //         var AutoNo = $(".AutoNo").length;
    //         $('#span').text(AutoNo);
    //         $('.select2').select2();

    //         // Update AutoCounter titles
    //         $(".AutoCounter").each(function (index) {
    //             $(this).prop('title', index + 1);
    //         });
    // }
</script>


<!-- select2 -->
<script type="text/javascript">
    $('.select2').select2();
</script>

<script>
    var x = 0;
    function tax_by_amount(id) {


        var tax_percentage = $('#sales_taxx').val();



        if (tax_percentage == 0) {

            $('#' + id).val(0);
        } else {
            var tax_amount = parseFloat($('#' + id).val());

            // highlight end

            if (isNaN(tax_amount) == true) {
                tax_amount = 0;
            }
            var count = 1;
            var amount = 0;
            $('.net_amount_dis').each(function() {


                amount += +$('#after_dis_amountt_' + count).val();
                count++;
            });
            var total = parseFloat(tax_amount + amount).toFixed(2);
            $('#d_t_amount_1').val(total);


        }
        //            toWords(1);



    }
    function net_amount() {
        var amount = 0;
        var actual_amount = 0;
        $('.net_amount_dis').each(function(i, obj) {

            amount += +$('#' + obj.id).val();
        });
        $('.actual_amount').each(function(i, obj) {

            actual_amount += +$('#' + obj.id).val();
        });
        amount = parseFloat(amount);
        actual_amount = parseFloat(actual_amount);
        $('#net').val(amount);
        $('#actual_net').val(actual_amount);
        var sales_tax = parseFloat($('#sales_amount_td').val());

        $('#net_after_tax').val(amount + sales_tax);
        $('#d_t_amount_1').val(amount + sales_tax);
        toWords(1);

    }
    function view_history(id) {

        var v = $('#sub_' + id).val();


        if ($('#view_history' + id).is(":checked")) {
            if (v != null) {
                showDetailModelOneParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
            } else {
                alert('Select Item');
            }

        }





    }
    $(document).ready(function() {

        for (i = 1; i <= counter; i++) {
            $('#amount_' + i).number(true, 2);
            //   $('#rate_'+i).number(true,2);
            $('#purchase_approve_qty_' + i).number(true, 2);


            $('#after_dis_amountt' + i).number(true, 2);
            $('#rate_' + i).number(true, 2);
        }

        $('#d_t_amount_1').number(true, 2);
        $('#sales_amount_td').number(true, 2);

        $(".btn-success").click(function(e) {
            //alert();
            var purchaseRequest = new Array();
            var val;
            //$("input[name='demandsSection[]']").each(function(){
            purchaseRequest.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of purchaseRequest) {
                jqueryValidationCustom();
                if (validate == 0) {
                    //alert(response);
                    vala = 0;
                    var flag = false;
                    $('.ActualQty').each(function() {
                        vala = parseFloat($(this).val());
                        if (vala == 0) {
                            alert('Please Enter Correct Actual Qty....!');
                            $(this).css('border-color', 'red');
                            flag = true;
                            return false;
                        } else {
                            $(this).css('border-color', '#ccc');
                        }
                    });

                    // $('.ActualRate').each(function() {
                    //     vala = parseFloat($(this).val());
                    //     if (vala == 0) {
                    //         alert('Please Enter Correct Rate Qty....!');
                    //         $(this).css('border-color', 'red');
                    //         flag = true;
                    //         return false;
                    //     } else {
                    //         $(this).css('border-color', '#ccc');
                    //     }
                    // });
                    if (flag == true) {
                        return false;
                    }
                } else {
                    return false;
                }
            }

        });


        $(document).keypress("m", function(e) {
            if (e.ctrlKey)
                AddMoreDetails();
        });

    });
    function removeSeletedPurchaseRequestRows(id, counter) {
        var totalCounter = $('#totalCounter').val();
        if (totalCounter == 1) {
            alert('Last Row Not Deleted');
        } else {
            var lessCounter = totalCounter - 1;
            var totalCounter = $('#totalCounter').val(lessCounter);
            var elem = document.getElementById('removeSelectedPurchaseRequestRow_' + counter + '');
            elem.parentNode.removeChild(elem);
        }

    }
    $(document).ready(function() {
        //            toWords(1);
    });

    function claculation(number) {
        var qty = $('#actual_qty' + number).val();
        var rate = $('#rate' + number).val();
        var currency = $('#currency_rate').val();
        var actual = parseFloat(qty * rate).toFixed(2);

        if (currency == '') {
            currency = 1;
        }

        var total = parseFloat(qty * rate * currency).toFixed(2);

        $('#amount' + number).val(total);
        $('#actual_amount' + number).val(actual);


        var amount = 0;
        count = 1;
        $('.net_amount_dis').each(function(i, obj) {

            amount += +$('#' + obj.id).val();

            count++;
        });
        amount = parseFloat(amount);



        discount_percent('discount_percent' + number);
        net_amount();
        sales_tax('sales_taxx');
        //  toWords(1);
    }

    function sales_tax(id) {
        var sales_tax = 0;
        var sales_tax_per_value = $('#sales_taxx').val();
        sales_tax_per_value = sales_tax_per_value.split("@")[0];

        if (sales_tax_per_value != '0') {
            var net = $('#net').val();

            var sales_tax = (net / 100) * sales_tax_per_value;

        }
        console.log(sales_tax)
        $('#sales_amount_td').val(sales_tax);

        total_amount();

    }

    function total_amount() {
        var amount = 0;

        $('.net_amount_dis').each(function() {

            amount += +$(this).val();

        });
        $('#net').val(amount);

        var sales_tax = parseFloat($('#sales_amount_td').val());
        var net = (amount + sales_tax).toFixed(2);

        $('#net_after_tax').val(net);
        console.log(net);


    }


    function get_address() {
        var supplier = $('#supplier_id').val();

        supplier = supplier.split('@#');
        $('#addresss').val(supplier[1]);

        $('#ntn_id').val(supplier[2]);
        $('#model_terms_of_payment').val(supplier[3]);
        calculate_due_date();
    }
    function get_rate() {
        var currency_id = $('#curren').val();
        currency_id = currency_id.split(',');
        $('#curren_rate').val(currency_id[1]);
    }
</script>

<script>
    function open_sales_tax(id) {

        var dept_name = $('#' + id + ' :selected').text();


        if (dept_name == 'Add New') {

            showDetailModelOneParamerter('fdc/createAccountFormAjax/sales_taxx')
        }

    }

    function discount_percent(id) {
        var number = id.replace("discount_percent", "");
        var amount = $('#amount' + number).val();

        var x = parseFloat($('#' + id).val());

        if (x > 100) {
            alert('Percentage Cannot Exceed by 100');
            $('#' + id).val(0);
            x = 0;
        }

        x = x * amount;
        var discount_amount = parseFloat(x / 100).toFixed(2);
        $('#discount_amount' + number).val(discount_amount);
        var discount_amount = $('#discount_amount' + number).val();

        if (isNaN(discount_amount)) {

            $('#discount_amount' + number).val(0);
            discount_amount = 0;
        }



        var amount_after_discount = amount - discount_amount;

        $('#after_dis_amount' + number).val(amount_after_discount);
        var amount_after_discount = $('#after_dis_amount' + number).val();

        if (amount_after_discount == 0) {
            $('#after_dis_amount' + number).val(amount);
            $('#net_amounttd_' + number).val(amount);
            $('#net_amount' + number).val(amount_after_discount);
        } else {

            $('#net_amounttd_' + number).val(amount_after_discount);
            $('#after_dis_amount' + number).val(amount_after_discount);
        }

        $('#cost_center_dept_amount' + number).text(amount_after_discount);
        $('#cost_center_dept_hidden_amount' + number).val(amount_after_discount);


        sales_tax('sales_taxx');
        net_amount();
        //  toWords(1);


    }

    function discount_amount(id) {
        var number = id.replace("discount_amount", "");
        var amount = parseFloat($('#amount' + number).val());

        var discount_amount = parseFloat($('#' + id).val());

        if (discount_amount > amount) {
            alert('Amount Cannot Exceed by ' + amount);
            $('#discount_amount' + number).val(0);
            discount_amount = 0;
        }

        if (isNaN(discount_amount)) {

            $('#discount_amount' + number).val(0);
            discount_amount = 0;
        }

        var percent = (discount_amount / amount * 100).toFixed(2);
        $('#discount_percent' + number).val(percent);
        var amount_after_discount = amount - discount_amount;
        $('#after_dis_amount' + number).val(amount_after_discount);


        $('#net_amounttd_' + number).val(amount_after_discount);
        $('#net_amount_' + number).val(amount_after_discount);
        sales_tax('sales_taxx');
        //   toWords(1);
        net_amount();


    }

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


            }
        })



    }
    $(".remove").each(function() {

        $(this).html($(this).html().replace(/,/g, ''));
    });

    function calculate_due_date() {

        //            var days=parseFloat($('#model_terms_of_payment').val());
        //
        //            var tt = document.getElementById('po_date').value;
        //
        //
        //            var date = new Date(tt);
        //            var newdate = new Date(date);
        //            newdate.setDate(newdate.getDate() + days);
        //            var dd = newdate.getDate();
        //
        //
        //            var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
        //
        //            var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
        //            var y = newdate.getFullYear();
        //            var someFormattedDate =  + y+'-'+ mm +'-'+dd;
        //
        //            document.getElementById('due_date').value = someFormattedDate;

        var date = new Date($("#po_date").val());
        var days = parseFloat($('#model_terms_of_payment').val());
        days = days;

        if (!isNaN(date.getTime())) {
            date.setDate(date.getDate() + days);


            var yyyy = date.getFullYear().toString();
            var mm = (date.getMonth() + 1).toString(); // getMonth() is zero-based
            var dd = date.getDate().toString();
            var new_d = yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]);


            $("#due_date").val(new_d);
        } else {
            alert("Invalid Date");
        }


    }

    function itemChange(id) {
      
        $('#uom_id' + id).val($('#item_' + id).find(':selected').data("uom"));
        $('#product_barcode' + id).val($('#item_' + id).find(':selected').data("barcode"));
        $('#product_type' + id).val($('#item_' + id).find(':selected').data("type_id"));
        $('#classification_name' + id).val($('#item_' + id).find(':selected').data("classification_name"));
        $('#product_trend' + id).val($('#item_' + id).find(':selected').data("product_trend"));
        $('#mainIcId_' + id).val($('#item_' + id).find(':selected').data("cat"));
        $('#rate' + id).val($('#item_' + id).find(':selected').data("rate"));
        console.log($('#mainIcId_' + id).val());
    }
    
    function get_discount() {
        var m = '{{$m}}';
        $('.mainIcId').each(function() {
            var supplier = $('#supplier_id').val().split('@#');
            var category_id = $(this).val();
            var currentElement = $(this); // Store $(this) in a variable
            var index = currentElement.attr('id').split('_');

            $.ajax({
                url: '{{ url("stad/getSupplierDiscounts") }}',
                type: 'GET',
                data: {
                    category_id: category_id,
                    supplier_id: supplier[0],
                    m: m
                }, // Use supplier[0] instead of supplier_id
                success: function(response) {
                    if (isNaN(response)) {
                        response = 0;
                    }
                    $('#discount_percent' + index[1]).val(response);
                    $('#discount_percent' + index[1]).trigger('keyup');
                }
            });
        });
    }

    // function get_product_by_brand(element, number) {
    //     var value = element.value;
    //     $(element).closest('.main').find('.itemsclass').empty();
    //     $.ajax({
    //         url: '{{ url("/getSubItemByBrandWithDetail") }}',
    //         type: 'Get',
    //         data: {
    //             id: value
    //         },
    //         success: function(data) {
    //             $(element).closest('.main').find('.itemsclass').append(data);
    //         }
    //     });
    // }

  
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
                    $("#rate" + index_val).val(response.purchase_price);
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
document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        return false;
    }
});
</script>
@endsection