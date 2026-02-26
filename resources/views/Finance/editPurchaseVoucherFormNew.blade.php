<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;

use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>


@extends('layouts.default')

@section('content')
@include('number_formate')
@include('select2')

<style>
.select2-container {
    font-size: 11px;
}
</style>

<?php
     $main_count=1;
    $count=1;
    $sales_tax_count=1;
    ?>
<?php echo Form::open(array('url' => 'fad/updatePurchaseVoucher','id'=>'cashPaymentVoucherForm')); ?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="EditId" id="EditId" value="{{$id}}">

@foreach($ids as $row)

<?php
        $rate = 0;
        $amt = 0;
        $TotAmt = 0;
        $TotalTaxAmount = 0;
        $TotalNetWithTax = 0;
        $total_amount=0;
        $sales_tax_amount=0;
        $grn_id=$row;
        $good_recipt_note=CommonHelper::get_goodreciptnotedata($row,0);
        $purchase_reqiest=CommonHelper::get_goodreciptnotedata($row,1);
        $currency = $purchase_reqiest->currency_rate;
        
        $po_no=$good_recipt_note->po_no;
        if($good_recipt_note->type==0):
            $po_date=CommonHelper::changeDateFormat($purchase_reqiest->purchase_request_date);
        else:
            $po_date='';
        endif;
        $bill_date=$good_recipt_note->bill_date;
        $no_days=$purchase_reqiest->terms_of_paym;
        $Date = $good_recipt_note->grn_date;
        $due_date =date('Y-m-d', strtotime($Date. ' + '.$no_days.' days'));
        ?>
<input type="hidden" name="grn_no{{$sales_tax_count}}" value="{{$NewPurchaseVoucher->grn_no}}">
<input type="hidden" name="grn_id{{$sales_tax_count}}" value="{{$NewPurchaseVoucher->grn_id}}">
<div class="row well_N">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;"> </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Edit Purchase Voucher Forms</span>
                </div>
            </div>

            <h3 style="text-align: center">{{strtoupper($NewPurchaseVoucher->grn_no)}}</h3>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="demandsSection[]" class="form-control requiredField"
                                        id="demandsSection" value="{{$sales_tax_count}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">PV No. <span
                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly type="text" class="form-control requiredField"
                                                placeholder="" name="pv_no<?php echo $sales_tax_count ?>"
                                                id="pv_no<?php echo $sales_tax_count ?>" value="{{$NewPurchaseVoucher->pv_no}}" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">PV Date.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input onblur=""
                                                onchange="calculate_due_date('<?php echo $sales_tax_count?>')"
                                                type="date" class="form-control requiredField"
                                                max="<?php echo date('Y-m-d') ?>"
                                                name="purchase_date<?php echo $sales_tax_count ?>"
                                                id="purchase_date<?php echo $sales_tax_count ?>"
                                                value="{{$NewPurchaseVoucher->pv_date}}" />
                                        </div>
                                        <input type="hidden" name="dept_id{{ $sales_tax_count }}"
                                            value="{{ $good_recipt_note->sub_department_id }}" />
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">PV Day.</label>

                                            <input readonly type="text" class="form-control"
                                                name="pv_day<?php echo $sales_tax_count ?>"
                                                id="pv_day<?php echo $sales_tax_count ?>" />
                                        </div>

                                        <input type="hidden" name="p_type_id{{ $sales_tax_count }}"
                                            value="{{ $good_recipt_note->p_type }}" />

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Ref / Bill No. <span
                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly type="text" class="form-control" placeholder="Ref / Bill No"
                                                name="slip_no<?php echo $sales_tax_count ?>"
                                                id="slip_no<?php echo $sales_tax_count ?>"
                                                value="{{$NewPurchaseVoucher->slip_no}}" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Bill Date.</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly type="date" class="form-control"
                                                name="bill_date<?php echo $sales_tax_count ?>"
                                                id="bill_date<?php echo $sales_tax_count ?>" value="{{$NewPurchaseVoucher->bill_date}}" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Due Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly autofocus value="{{$NewPurchaseVoucher->due_date}}" type="date"
                                                name="due_date<?php echo $sales_tax_count ?>"
                                                id="due_date<?php echo $sales_tax_count ?>"
                                                class="form-control requiredField" />
                                        </div>

                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">



                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label"> <a href="#"
                                                    onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');"
                                                    class="">Vendor</a></label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input readonly class="form-control"
                                                name="supp_id<?php echo $sales_tax_count ?>"
                                                id="supp_id<?php echo $sales_tax_count ?>"
                                                value="{{ucwords(CommonHelper::get_supplier_name($NewPurchaseVoucher->supplier))}}">
                                            <input type="hidden" id="supplier_id<?php echo $sales_tax_count ?>"
                                                name="supplier_id<?php echo $sales_tax_count ?>"
                                                value="{{$NewPurchaseVoucher->supplier}}" />

                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Mode/ Terms Of Payment<span
                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly
                                                onkeyup="calculate_due_date('<?php echo $sales_tax_count?>')"
                                                type="number" class="form-control" placeholder=""
                                                name="model_terms_of_payment<?php echo $sales_tax_count?>"
                                                id="model_terms_of_payment<?php echo $sales_tax_count?>"
                                                value="<?php echo $no_days?>" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                            <label class="sf-label">Vendor Current Amount <span
                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly type="number" class="form-control" placeholder=""
                                                name="current_amount<?php echo $sales_tax_count ?>"
                                                id="current_amount<?php echo $count ?>" value="" />
                                        </div>


                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">PO No & PO Date <span
                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                            <input readonly type="text" class="form-control" placeholder=""
                                                name="po_no_date<?php echo $sales_tax_count ?>"
                                                id="po_no_date<?php echo $count ?>"
                                                value="{{strtoupper($po_no.'--'.$po_date)}}" />
                                        </div>

                                         <input type="hidden" name="p_type<?php echo $sales_tax_count ?>" value="{{$NewPurchaseVoucher->purchase_type}}">

                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Description</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <textarea name="description<?php echo $sales_tax_count ?>"
                                                id="description<?php echo $sales_tax_count ?>" rows="4" cols="50"
                                                style="resize:none;"
                                                class="form-control requiredField">{{$NewPurchaseVoucher->description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>


                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <div class="addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">


                                            <table id="" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                                <th style="width: 100px" class="text-center">SKU Code<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>

                                                        <th style="width: 150px;" class="text-center hidden-print"><a
                                                                tabindex="-1" href="#"
                                                                onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')"
                                                                class="">Product</a>
                                                    <th style="width: 100px" class="text-center">Barcode<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>

                                                        <th style="width: 100px" class="text-center">UOM <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>

                                                        <th style="width: 200px;" class="text-center">Qty. <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Return Qty. <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Rate. <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Gross Amount. <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Tax% <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Tax Amount <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Discount Amount
                                                            <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 200px;" class="text-center">Net Amount <span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($NewPurchaseVoucherData as $row1)

                                                    <?php
                                                           $grn_data = CommonHelper::get_goodreciptnotedata_child($row1->grn_data_id);
                                                           $return_qty= ReuseableCode::purchase_return_qty($row1->grn_data_id);
                                                           
                                                           $qty=$row1->qty;
                                                           $rate=$row1->rate;
                                                           $amount=$row1->amount;
                                                           $tax_rate=$row1->tax_rate;
                                                           $tax_amount=$row1->tax_amount;
                                                           $discount_amount=$row1->discount_amount;
                                                           $net_amount=$row1->net_amount;

                                                           $TotalTaxAmount += $tax_amount;
                                                           $TotalNetWithTax += $net_amount;
                                                           ?>

                                                    <input type="hidden" name="demandDataSection_{{$sales_tax_count}}[]"
                                                        class="form-control requiredField" id="demandDataSection_{{$sales_tax_count}}"
                                                        value="{{$count}}" />
                                                    <input type="hidden" name="grn_data_id_{{$sales_tax_count}}_{{$count}}"
                                                        id="grn_data_id_{{$sales_tax_count}}_{{$count}}" value="{{$row1->grn_data_id}}" />
                                                    <tr>
<td>
                                                             <?php
                                                                    
                                                                    
                                                                    echo CommonHelper::get_product_sku($row1->sub_item);
                                                                    
                                                                    ?>
                                                        </td>
                                                        <td title="{{CommonHelper::get_product_name($row1->sub_item)}}"
                                                            class="text-center" style="width: 30%;">
                                                            <input type="hidden"
                                                                name="sub_item_id_{{$sales_tax_count}}_{{$count}}"
                                                                value="{{$row1->sub_item}}" />


                                                            <?php
                                                                    $sub_ic_detail=CommonHelper::get_subitem_detail($row1->sub_item);
                                                                    $sub_ic_detail= explode(',',$sub_ic_detail);
                                                                    
                                                                    echo CommonHelper::get_product_name($row1->sub_item);
                                                                    
                                                                    ?>
                                                        </td>

                                                        
                                                        <td>
                                                             <?php
                                                                    
                                                                    
                                                                    echo CommonHelper::product_barcode($row1->sub_item);
                                                                    
                                                                    ?>
                                                        </td>
                                                        <td>
                                                            <input readonly type="text"
                                                                value="<?php echo CommonHelper::get_uom_name($row1->uom);?>"
                                                                name="uom_{{$sales_tax_count}}_{{$count}}" id="uom_{{$sales_tax_count}}_{{$count}}" class="form-control" />
                                                            <input type="hidden" name="uom_id_{{$sales_tax_count}}_{{$count}}"
                                                                id="uom_id_{{$sales_tax_count}}_{{$count}}"
                                                                value="{{$row1->uom}}" />
                                                        </td>

                                                        <td>
                                                            <input readonly value="{{$qty}}" type="number" step="0.01"
                                                                name="qty_{{$sales_tax_count}}_{{$count}}"
                                                                id="qty_{{$sales_tax_count}}_{{$count}}"
                                                                class="form-control qty" />
                                                        </td>

                                                        <td>
                                                            <input readonly value="{{$return_qty}}" type="number"
                                                                step="0.01" name="return_qty_{{$sales_tax_count}}_{{$count}}"
                                                                id="return_qty_{{$sales_tax_count}}_{{$count}}"
                                                                class="form-control qty" />
                                                        </td>

                                                        <td>
                                                            <input readonly
                                                                onkeyup="calculation_amount(this.id,'<?php echo $count ?>','<?php echo $sales_tax_count?>')"
                                                                value="<?php echo $row1->rate?>" type="text" step="0.01"
                                                                name="rate_{{$sales_tax_count}}_{{$count}}"
                                                                id="rate_{{$sales_tax_count}}_{{$count}}"
                                                                class="form-control requiredField rate" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="amount_{{$sales_tax_count}}_{{$count}}"
                                                                id="amount_{{$sales_tax_count}}_{{$count}}"
                                                                class="form-control requiredField amount{{$sales_tax_count}}"
                                                                value="<?php echo $amount;?>" readonly />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="tax_rate_{{$sales_tax_count}}_{{$count}}"
                                                                id="tax_rate_{{$sales_tax_count}}_{{$count}}"
                                                                class="form-control requiredField tax_rate{{$sales_tax_count}}"
                                                                value="<?php echo $tax_rate;?>" readonly />
                                                        </td>
                                                       <td>
                                                    <input type="text" name="tax_amount_{{$sales_tax_count}}_{{$count}}"
                                                        id="tax_amount_{{$sales_tax_count}}_{{$count}}"
                                                        class="form-control requiredField tax_amount{{$sales_tax_count}}"
                                                        value="<?php echo number_format($tax_amount, 2); ?>" readonly />
                                                </td>

                                                        <td><input readonly class="form-control" type="text"
                                                                id="discount_amount_{{$sales_tax_count}}_{{$count}}"
                                                                name="discount_amount_{{$sales_tax_count}}_{{$count}}"
                                                                value="<?php echo number_format($discount_amount, 2); ?>"></td>

                                                        <td><input readonly class="form-control net_amount{{$sales_tax_count}}"
                                                                id="net_amount_{{$sales_tax_count}}_{{ $count }}" 
                                                                name="net_amount_{{$sales_tax_count}}_{{$count}}"
                                                                value="{{number_format($net_amount, 2)}}"></td>
                                                    </tr>
                                                    <?php  $count++; ?>
                                                    @endforeach
                                                      <tr class="text-center">
                                                        <td class="text-center" colspan="9"></td>
                                                        <td class="text-center" colspan="2">TOTAL</td>
                                                        <td><input type="text" maxlength="15"
                                                                class="form-control text-right" name="Totalamount"
                                                                value="<?php echo number_format($TotalNetWithTax, 2); ?>"
                                                                id="Totalamount<?php echo $sales_tax_count?>" readonly="">
                                                        </td>
                                                    </tr>
                                                     <input type="hidden" name="Totalamount"
                                                                value="<?php echo number_format($TotalNetWithTax, 2); ?>"
                                                                id="Totalamount<?php echo $sales_tax_count?>">
                                                    <tr class="text-center" style="background: gainsboro">
                                                        <td class="text-center" colspan="7"></td>
                                                        <?php
                                                            $SalesTaxAccId = $NewPurchaseVoucher->sales_tax_acc_id;
                                                            $SalesTaxAmount = $NewPurchaseVoucher->sales_tax_amount;
                                                            ?>
                                                        <td colspan="2">WithHolding Tax</td>
                                                        <td colspan="2">

                                                            <select name="SalesTaxesAccId<?php echo $sales_tax_count?>"
                                                                class="form-control select2"
                                                                id="SalesTaxesAccId<?php echo $sales_tax_count?>"
                                                                onchange="sales_tax_calc('<?php echo $sales_tax_count?>')">
                                                                <option value="">Select Head</option>
                                                                @foreach(ReuseableCode::get_all_sales_tax() as $row_tax)
                                                                <option value="{{ $row_tax->id}}" data-rate="{{$row_tax->rate}}" {{ $SalesTaxAccId == $row_tax->id ? 'selected' : '' }}>{{$row_tax->rate}} %
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text"
                                                                name="SalesTaxAmount<?php echo $sales_tax_count?>"
                                                                id="SalesTaxAmount<?php echo $sales_tax_count?>"
                                                                class="form-control text-right"
                                                                value="<?php echo $SalesTaxAmount?>"
                                                                onkeyup="sales_tax_calc('<?php echo $sales_tax_count?>')"
                                                                readonly
                                                                ></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="rupees{{$main_count}}" class="text-center" colspan="9">
                                                        </td>
                                                        <td class="text-center" colspan="1">Grand Total</td>
                                                        <td colspan="2"><input type="text" name="NetTotal"
                                                                id="NetTotal<?php echo $sales_tax_count?>"
                                                                class="form-control number_form" readonly
                                                                value="<?php echo number_format($TotalNetWithTax - $SalesTaxAmount, 2); ?>"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="demandsSection"></div>


                </div>
            </div>
        </div>
    </div>
</div>
<?php $sales_tax_count++;  $main_count++;?> 
@endforeach
<input type="hidden" id="main_count" value="{{$main_count}}" />
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
    </div>
</div>
<?php echo Form::close();?>

<script>
var th = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

function toWordss(id) {

    s = $('#NetTotal' + id + '').val();

    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1)
        x = s.length;
    if (x > 15)
        return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) { // 0235
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk)
                str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }

    if (x != s.length) {
        var y = s.length;
        str += 'point ';
        for (var i = x + 1; i < y; i++)
            str += dg[n[i]] + ' ';
    }
    result = str.replace(/\s+/g, ' ') + 'Only';

    $('#rupees' + id).text(result);
}

function calculate_due_date(Row) {

    var date = new Date($("#purchase_date" + Row).val());
    var days = parseFloat($('#model_terms_of_payment' + Row).val());
    days = days;

    if (!isNaN(date.getTime())) {
        date.setDate(date.getDate() + days);


        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth() + 1).toString(); // getMonth() is zero-based
        var dd = date.getDate().toString();
        var new_d = yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]);


        $("#due_date" + Row).val(new_d);
    } else {
        alert("Invalid Date");
    }

    change_day(Row);
}

function change_day(index) {

    var date = $('#purchase_date' + index).val();

    var d = new Date(date);

    var weekday = new Array(7);
    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var n = weekday[d.getDay()];

    if (document.getElementById("pv_day" + index)) {
        document.getElementById("pv_day" + index).value = n;
    }
}

$(document).ready(function() {

    $('.number_form').number(true, 2);
    var main_count = $('#main_count').val();
    for (var i = 1; i < main_count; i++) {
        toWordss(i); 
        change_day(i);
    }
    $('.select2').select2();
})



$(".btn-success").click(function(e) {
    var rvs = new Array();
    var val;
    $("input[name='demandsSection[]']").each(function() {
        rvs.push($(this).val());
    });
    // Add validation logic if needed
});


function calculation_amount(id, count, SectionIndex) {
    var quantity = $("#qty_" + SectionIndex + "_" + count).val();
    var rate = $("#" + id).val();
    var amount = quantity * rate;
    $("#amount_" + SectionIndex + "_" + count).val(amount);
    
    var tax_rate = parseFloat($("#tax_rate_" + SectionIndex + "_" + count).val()) || 0;
    var tax_amount = (amount * tax_rate) / 100;
    $("#tax_amount_" + SectionIndex + "_" + count).val(tax_amount.toFixed(2));
    
    var discount_amount = parseFloat($("#discount_amount_" + SectionIndex + "_" + count).val()) || 0;
    var net_amount = (amount + tax_amount) - discount_amount;

    $('#net_amount_' + SectionIndex + "_" + count).val(net_amount.toFixed(2));
    
    var total_net = 0;
    $('.net_amount' + SectionIndex).each(function(i, obj) {
        total_net += parseFloat($(obj).val()) || 0;
    });
    $('#Totalamount' + SectionIndex).val(total_net.toFixed(2));
    sales_tax_calc(SectionIndex)
}

function sales_tax_calc(SectionIndex) {
    var NetAmount = parseFloat($('#Totalamount' + SectionIndex).val()) || 0;
    var AccId = $('#SalesTaxesAccId' + SectionIndex).val();
    var rate = $('#SalesTaxesAccId' + SectionIndex).find(':selected').data('rate') || 0;
    
    var SalesTaxAmount = 0;
    
    if (AccId != '') {
        $('#SalesTaxAmount' + SectionIndex).prop('disabled', false);
        SalesTaxAmount = (NetAmount * rate) / 100;
        $('#SalesTaxAmount' + SectionIndex).val(SalesTaxAmount.toFixed(2));
    } else {
        $('#SalesTaxAmount' + SectionIndex).prop('disabled', true);
        SalesTaxAmount = 0;
        $('#SalesTaxAmount' + SectionIndex).val(0);
    }

    $('#NetTotal' + SectionIndex).val(parseFloat(NetAmount - SalesTaxAmount).toFixed(2));
    toWordss(SectionIndex);
}

</script>
@endsection