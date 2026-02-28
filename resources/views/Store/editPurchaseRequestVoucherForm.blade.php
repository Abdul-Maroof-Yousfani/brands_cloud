<?php


use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>

@extends('layouts.default')

@section('content')
@include('select2')
@include('number_formate')

<script>
var counter = 1;
</script>


<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
        @include('Store.'.$accType.'storeMenu')
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Purchase Order Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>


                <?php echo Form::open(array('url' => 'stad/editPurchaseRequestVoucherDetail?m='.$m.'','id'=>'addPurchaseRequestDetail'));?>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <input type="hidden" name="id" value="{{$id}}">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">PO NO.</label>
                                        <input readonly type="text" class="form-control requiredField" placeholder=""
                                            name="po_no" id="po_no" value="{{$purchase_order->purchase_request_no}}" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">PO DATE.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField"
                                            max="<?php echo date('Y-m-d') ?>" name="po_date" id="po_date"
                                            value="{{$purchase_order->purchase_request_date}}" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Terms Of Delivery</label>
                                        <input type="text" class="form-control" placeholder="Terms Of Delivery"
                                            name="term_of_del" id="term_of_del"
                                            value="{{$purchase_order->term_of_del}}" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">PO Type</label>
                                        <select onchange="get_po(this.id);po_type_change(this);" name="po_type"
                                            id="po_type" class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach(CommonHelper::get_local_to_types() as $item)
                                            <option
                                                value="{{$item->id}}" {{$item->id == $purchase_order->po_type ? 'selected' :''}}>
                                                {{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @php
                                    $pr_nos = $purchase_order_data->pluck("demand_no")->toArray();

                                @endphp

                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Destination</label>
                                        <input style="text-transform: capitalize;" type="text" class="form-control"
                                            placeholder="" name="destination" id="destination" value="{{$purchase_order->destination}}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Department</label>
                                        <input type="text" class="form-control" name="department" id="department"
                                            value="{{ $purchase_order->department ?? '' }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Comparative number(s)</label>
                                        <input readonly style="text-transform: capitalize;" type="text" class="form-control"
                                             value="{{ implode(', ', \App\Helpers\CommonHelper::get_comparative_number($pr_nos)) }}" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label"> Vendor </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="get_address(); get_discount();" name="supplier_id"
                                            id="supplier_id" class="form-control requiredField select2">
                                            <option value="">Select Vendor</option>
                                            <?php
                                            foreach (CommonHelper::get_all_supplier() as $row1){
                                            $address= CommonHelper::get_supplier_address($row1->id);
                                            ?>
                                            <option
                                                value="<?php echo $row1->id.'@#'.$address.'@#'.$row1->ntn.'@#'.$row1->terms_of_payment.'@#'.$row1->strn?>"
                                                <?php if($purchase_order->supplier_id == $row1->id): echo "selected"; endif;?>>
                                                <?php echo ucwords($row1->name)?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label"> Currency </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onchange="claculation(1);get_rate()" name="curren" id="curren"
                                            class="form-control select2 requiredField">
                                            <option>Select Currency</option>
                                            <option value="0,1"
                                                <?php if($purchase_order->currency_id == 0): echo "selected"; endif;?>>
                                                PKR</option>
                                            @foreach(CommonHelper::get_all_currency() as $row)
                                            <option value="{{$row->id.','.$row->rate}}" {{$row->id == $purchase_order->currency_id ? 'selected' : ''}}>{{$row->name}}</option>
                                            @endforeach;
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label"> Currency Rate</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input class="form-control requiredField" type="text" name="currency_rate"
                                            id="currency_rate" value="<?php echo $purchase_order->currency_rate;?>" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Mode/ Terms Of Payment <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        <input onkeyup="calculate_due_date()" type="number"
                                            class="form-control requiredField" placeholder=""
                                            name="model_terms_of_payment" id="model_terms_of_payment"
                                            value="<?php echo $purchase_order->terms_of_paym?>" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Due Date <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        <input type="date" class="form-control requiredField" placeholder=""
                                            name="due_date" id="due_date" value="<?php echo $purchase_order->due_date?>"
                                            readonly />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Supplier Type <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        @if(\App\Helpers\CommonHelper::get_type($purchase_order->p_type))
                                        <input readonly type="text" class="form-control requiredField" placeholder=""
                                            value="{{ \App\Helpers\CommonHelper::get_type($purchase_order->p_type)->name }}"
                                            id="p_type" disabled />
                                        
                                        @else

                                            <input type="text" class="form-control requiredField" placeholder=""
                                            value="{{ $purchase_order->p_type }}" 
                                            id="p_type" />

                                        @endif
                                    </div>
                                </div>






                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <label class="sf-label">Supplier's Address</label>
                                        <input style="text-transform: capitalize;" readonly type="text"
                                            class="form-control" placeholder="" name="address" id="addresss" value="" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Supplier's NTN</label>
                                        <input readonly type="text" class="form-control" placeholder="Ntn" name="ntn"
                                            id="ntn_id" value="" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">STRN <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        <input type="text" name="trn" id="trn" class="form-control requiredField"
                                            placeholder="TRN" value="<?php echo $purchase_order->trn?>">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Builty No</label>
                                        <input type="text" name="builty_no" id="builty_no" class="form-control"
                                            placeholder="Builty No" value="<?php echo $purchase_order->builty_no?>">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control"
                                            placeholder="Terms & Condition"><?php echo $purchase_order->remarks?></textarea>
                                    </div>
                                </div>


                            </div>
                            <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Terms & Condition</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="main_description" id="main_description" rows="4" cols="50"
                                            style="resize:none;font-size: 11px;"
                                            class="form-control requiredField">{{$purchase_order->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                            <th class="text-center">Sr No</th>
                                            <th class="text-center">Pr No</th>
                                            <th class="text-center">Pr Date</th>
                                            <th class="text-center" colspan="2">Product Name</th>
                                            <th class="text-center">Product Type</th>
                                            <th class="text-center">Product Barcode</th>
                                            <th class="text-center">Product Classification</th>
                                            <th class="text-center">Product Trend</th>
                                            <th class="text-center">UOM</th>
                                            <th class="text-center">Req. Qty</th>
                                            <th class="text-center">Remarks</th>
                                        </thead>
                                        <tbody id="filterDemandVoucherList">
                                            <?php
                    $counter1 = 1;
                    $all_total=0;
                    foreach ($purchase_order_data as $row) { ?>
                                            <tr id="removeSelectedPurchaseRequestRow_<?php echo $counter1;?>"
                                                class="text-center">
                                                <td rowspan="2" style="padding: 55px 0px 0px 0px;">
                                                    <?php echo $counter1?>
                                                </td>
                                                <td><?php echo strtoupper($row->demand_no)?></td>
                                                <td><?php echo CommonHelper::changeDateFormat($row->demand_date)?></td>
                                                <td colspan="2">
                                                    <?php $sub_item_id = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row->sub_item_id);?>
                                                    <?php $product_name = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_name',$row->sub_item_id);?>
                                                    <a href="<?php echo url('/') ?>/store/item_detaild_supplier_wise?&sub_item_id=<?php echo $row->sub_item_id ?>"
                                                        target="_blank">{{ ($product_name != '' ? $product_name  : $sub_item_id)}}</a>
                                                    <input type="hidden" name="subItemId_<?php echo $counter1;?>"
                                                        readonly id="subItemId_<?php echo $counter1;?>"
                                                        value="<?php echo $row->sub_item_id;?>" class="form-control" />
                                                </td>
                                                <td><?php $product_type_name = CommonHelper::get_product_type_by_id(CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_type_id',$row->sub_item_id)); echo $product_type_name?>
                                                </td>
                                                <td><?php $product_barcode = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_barcode',$row->sub_item_id); echo $product_barcode?>
                                                </td>
                                                <td><?php $product_classification_name = CommonHelper::get_classification_by_id(CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_classification_id',$row->sub_item_id)); echo $product_classification_name?>
                                                </td>
                                                <td><?php $product_trend_name = CommonHelper::get_product_trend_by_id(CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_trend_id',$row->sub_item_id)); echo $product_trend_name?>
                                                </td>
                                                <td><?php echo CommonHelper::get_uom_name(CommonHelper::get_subitem_detail($row->sub_item_id)[0]);?>
                                                </td>
                                                <td class="text-center">{{$row->purchase_request_qty}}<input
                                                        type="hidden"
                                                        name="purchase_request_qty_<?php echo $counter1 ?>"
                                                        value="{{$row->purchase_request_qty}}"
                                                        id="purchase_request_qty_<?php echo $counter1 ?>" /></td>
                                                <td colspan="2"><textarea cols="10" rows="2" class="form-control"
                                                        name="description_<?php echo $counter1 ?>">{{$row->description}}</textarea>
                                                </td>
                                                <input type="hidden" name="seletedPurchaseRequestRow[]" id="seletedPurchaseRequestRow" readonly
                                                    value="<?php echo $counter1;?>" class="form-control" />
                                                <input type="hidden" name="demandNo_<?php echo $counter1;?>" readonly
                                                    value="<?php echo $row->demand_no;?>" class="form-control" />
                                                <input type="hidden" name="demandDate_<?php echo $counter1;?>" readonly
                                                    value="<?php echo $row->demand_date;?>" class="form-control" />
                                                <input type="hidden" name="demandType_<?php echo $counter1;?>" readonly
                                                    value="" class="form-control" />
                                                <input type="hidden" name="demandSendType_<?php echo $counter1;?>"
                                                    readonly value="" class="form-control" />
                                                <input type="hidden" name="demand_data_id<?php echo $counter1;?>"
                                                    value="{{$row->demand_data_id}}">
                                                <input type="hidden" name="order_data_id<?php echo $counter1;?>"
                                                    value="{{$row->id}}">
                                            </tr>
                                            <tr class="text-center">
                                                <td class="text-center hide">
                                                    <label for="">Req. Qty.</label>
                                                    {{$row->purchase_request_qty}}
                                                    <input type="hidden"
                                                        name="purchase_request_qty_<?php echo $counter1 ?>"
                                                        value="{{$row->purchase_request_qty}}"
                                                        id="purchase_request_qty_<?php echo $counter1 ?>" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Approved. Qty.</label>
                                                    <input onkeyup="claculation('<?php echo  $counter1 ?>')" type="text"
                                                        name="purchase_approve_qty_<?php echo $counter1?>"
                                                        id="purchase_approve_qty_<?php echo $counter1?>"
                                                        class="form-control requiredField approveQty" min="1"
                                                        value="{{$row->purchase_approve_qty}}" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Rate.</label>
                                                    <input readonly onkeyup="claculation('<?php echo $counter1 ?>')"
                                                        type="text" name="rate_<?php echo $counter1?>"
                                                        id="rate_<?php echo $counter1?>"
                                                        class="form-control requiredField ApproveRate" step="0.001"
                                                        value="{{($row->rate)}}" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Amount</label>
                                                    <input readonly style="text-align: right" type="text"
                                                        name="amount_<?php echo $counter1?>"
                                                        id="amount_<?php echo $counter1?>"
                                                        class="form-control requiredField amount text-right" min="1"
                                                        value="{{$row->sub_total}}" step="0.01" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Discount %</label>
                                                    <input onkeyup="discount_percent(this.id)"
                                                        class="form-control requiredField" type="text"
                                                        name="discount_percent_<?php echo $counter1?>"
                                                        id="discount_percent_<?php echo $counter1?>"
                                                        value="<?php echo $row->discount_amount?>" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Discount Amount</label>
                                                    <input onkeyup="discount_amount(this.id)"
                                                        class="form-control requiredField" type="text"
                                                        name="discount_amount_<?php echo $counter1?>"
                                                        id="discount_amount_<?php echo $counter1?>"
                                                        value="<?php echo $row->discount_amount?>" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Net Amount</label>
                                                    <input readonly class="form-control net_amount_dis" type="text"
                                                        value="{{$row->net_amount}}"
                                                        name="after_dis_amountt_<?php echo $counter1?>"
                                                        id="after_dis_amountt_<?php echo $counter1?>" />
                                                </td>
                                            </tr>
                                            <?php $counter1++; } ?>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="count" value="{{$counter1}}">
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
                                                <select onchange="sales_tax(this.id);open_sales_tax(this.id);"
                                                    class="form-control select2" id="sales_taxx" name="sales_taxx">
                                                    <option value="0">Select Sales Tax</option>

                                                    @foreach(ReuseableCode::get_all_sales_tax() as $row)

                                                    <option value="{{$row->rate}}"
                                                        {{ $purchase_order->sales_tax == $row->rate ? 'selected' : '' }}>
                                                        {{$row->percent}}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </select> -->
                                            </td>
                                            <td class="text-right" colspan="3">
                                                <input onkeyup="tax_by_amount(this.id)" type="text" class="form-control"
                                                    name="sales_amount_td" id="sales_amount_td"
                                                    value="<?php echo $purchase_order->sales_tax_amount?>" />
                                            </td>
                                            <input type="hidden" name="sales_amount" id="sales_tax_amount" />
                                        </tr>

                                        <tr>
                                            <td style="background-color: darkgray" colspan="3" class="text-center">
                                                Total</td>

                                            <td style="background-color: darkgray" colspan="2" class="text-right">
                                                <input
                                                    style="background-color: darkgray;text-align: right;font-weight: bold"
                                                    class="td_amount form-control" type="text" name="total_amount"
                                                    id="d_t_amount_1" value="{{$all_total}}" />
                                            </td>
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



                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>
</div>
<script>
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
    toWords(1);



}









$(document).ready(function() {

    for (i = 1; i <= counter; i++) {
        $('#amount_' + i).number(true, 2);
        //   $('#rate_'+i).number(true,2);
        $('#purchase_approve_qty_' + i).number(true, 2);
        $('#discount_percent_' + i).number(true, 2);
        $('#discount_amount_' + i).number(true, 2);
        $('#after_dis_amountt_' + i).number(true, 2);
    }

    $('#d_t_amount_1').number(true, 2);
    $('#sales_amount_td').number(true, 2);

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
                //alert(response);
            } else {
                return false;
            }
        }

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
    // get_address();
    net_amount();
    // toWords(1);

    sales_tax('sales_taxx');
    open_sales_tax('sales_taxx');
    // $('#sales_amount_td').trigger('keyup');
    // $('#po_type').trigger('change');
    tax_by_amount('sales_amount_td')
});


function claculation(number) {
    var qty = $('#purchase_approve_qty_' + number).val();
    var rate = $('#rate_' + number).val();

    var total = parseFloat(qty * rate).toFixed(2);
    $('#amount_' + number).val(total);

    var amount = 0;
    count = 1;
    $('.net_amount_dis').each(function() {


        amount += +$('#after_dis_amountt_' + count).val();
        count++;
    });
    amount = parseFloat(amount);


    sales_tax('sales_taxx');
    open_sales_tax('sales_taxx');
    discount_percent('discount_percent_' + number);
    toWords(1);
}


function sales_tax(id) {
    var sales_tax = 0;
    var sales_tax_per_value = $('#sales_taxx').val();


    var net = net_amount();

    if (sales_tax_per_value != '0') {


        var sales_tax = (net / 100) * sales_tax_per_value;

    }

    $('#sales_amount_td').val(sales_tax);

    $('#d_t_amount_1').val(net + sales_tax);


}

function get_address() {
    var supplier = $('#supplier_id').val();

    supplier = supplier.split('@#');

    $('#addresss').val(supplier[1]);
    $('#ntn_id').val(supplier[2]);
    $('#model_terms_of_payment').val(supplier[3]);
    $('#trn').val(supplier[4]);
    calculate_due_date();
}

function calculate_due_date() {

    var days = parseFloat($('#model_terms_of_payment').val()) - 1;
    var tt = document.getElementById('po_date').value;

    var date = new Date(tt);
    var newdate = new Date(date);
    newdate.setDate(newdate.getDate() + days);
    var dd = newdate.getDate();

    var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
    var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
    var y = newdate.getFullYear();
    var someFormattedDate = +y + '-' + mm + '-' + dd;

    document.getElementById('due_date').value = someFormattedDate;
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
                $('#discount_percent_' + index[1]).val(response);
                $('#discount_percent_' + index[1]).trigger('keyup');
            }
        });
    });
}


function po_type_change(selectedElement) {
    // $('#curren').empty();
    var selectedValue = selectedElement.value;
    if (selectedValue) {
        $.ajax({
            url: '/pdc/get_currency_vendor_by_to_type',
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



function discount_percent(id) {

    var number = id.replace("discount_percent_", "");
    var amount = $('#amount_' + number).val();

    var x = parseFloat($('#' + id).val());

    if (x > 100) {
        alert('Percentage Cannot Exceed by 100');
        $('#' + id).val(0);
        x = 0;
    }

    x = x * amount;
    var discount_amount = parseFloat(x / 100).toFixed(2);
    $('#discount_amount_' + number).val(discount_amount);
    var discount_amount = $('#discount_amount_' + number).val();

    if (isNaN(discount_amount)) {

        $('#discount_amount_' + number).val(0);
        discount_amount = 0;
    }



    var amount_after_discount = amount - discount_amount;

    $('#after_dis_amountt_' + number).val(amount_after_discount);
    var amount_after_discount = $('#after_dis_amountt_' + number).val();

    if (amount_after_discount == 0) {
        $('#after_dis_amountt_' + number).val(amount);
        $('#net_amounttd_' + number).val(amount);
        $('#net_amount_' + number).val(amount_after_discount);
    } else {
        $('#net_amounttd_' + number).val(amount_after_discount);
        $('#net_amount_' + number).val(amount_after_discount);
    }

    $('#cost_center_dept_amount' + number).text(amount_after_discount);
    $('#cost_center_dept_hidden_amount' + number).val(amount_after_discount);


    sales_tax('sales_taxx');
    open_sales_tax('sales_taxx');

    toWords(1);


}


function discount_amount(id) {
    var number = id.replace("discount_amount_", "");
    var amount = parseFloat($('#amount_' + number).val());

    var discount_amount = parseFloat($('#' + id).val());

    if (discount_amount > amount) {
        alert('Amount Cannot Exceed by ' + amount);
        $('#discount_amount_' + number).val(0);
        discount_amount = 0;
    }

    if (isNaN(discount_amount)) {

        $('#discount_amount_' + number).val(0);
        discount_amount = 0;
    }

    var percent = (discount_amount / amount * 100).toFixed(2);
    $('#discount_percent_' + number).val(percent);
    var amount_after_discount = amount - discount_amount;
    $('#after_dis_amountt_' + number).val(amount_after_discount);


    //  $('#net_amounttd_'+number).val(amount_after_discount);
    //   $('#net_amount_'+number).val(amount_after_discount);
    sales_tax('sales_taxx');
    open_sales_tax('sales_taxx');
    toWords(1);
    //   net_amount_func();


}

function net_amount() {
    var amount = 0;
    $('.net_amount_dis').each(function() {


        amount += +$(this).val();

    });

    var total = amount - $('#sales_amount_td').val();
    $('#d_t_amount_1').val(total);

    return amount;
}
</script>



<script type="text/javascript">
$('.select2').select2();
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection