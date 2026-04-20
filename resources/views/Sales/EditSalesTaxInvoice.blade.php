<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');


if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

$customerDetail = CommonHelper::get_buyer_detail($sales_tax_invoice->buyers_id);
?>
@extends('layouts.default')

@section('content')
@include('loader')
@include('number_formate')
@include('select2')


<style>
* {
    font-size: 12px !important;

}

label {
    text-transform: capitalize;
}
</style>


<div class="row well_N" style="display: none;" id="main">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- <div class="well_N"> -->
                <div class="dp_sdw2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <span class="subHeadingLabelClass">Edit Sales Tax Invoice</span>
                                            </div>
                                        </div>
                                        <hr style="border-bottom: 1px solid #f1f1">
                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row">
                                            <?php echo Form::open(array('url' => 'sad/updateSalesTaxInvoice?m='.$m.'','id'=>'createSalesOrder'));?>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="id" value="{{$sales_tax_invoice->id}}">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                                        <h2 class="subHeadingLabelClass">GDN Details
                                                                        </h2>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                        
                                                                        <div class="row">

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Invoice
                                                                                    No<span
                                                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder="" name="gi_no"
                                                                                    id="gi_no"
                                                                                    value="{{strtoupper($sales_tax_invoice->gi_no)}}" />
                                                                            </div>

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Invoice
                                                                                    Date<span
                                                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                                                <input autofocus type="date"
                                                                                    onkeyup="calculate_due_date()"
                                                                                    class="form-control requiredField"
                                                                                    placeholder="" name="gi_date"
                                                                                    id="gi_date"
                                                                                    value="{{$sales_tax_invoice->gi_date}}" />
                                                                            </div>

                                                                        </div>

                                                                        <div class="row">
                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">SO NO. <span
                                                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder="" name="so_no"
                                                                                    id="so_no"
                                                                                    value="{{$sales_tax_invoice->so_no}}" />
                                                                            </div>

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">SO Date
                                                                                    <span
                                                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                                                <input readonly type="date"
                                                                                    class="form-control"
                                                                                    placeholder="" name="so_date"
                                                                                    id="so_date"
                                                                                    value="{{$sales_tax_invoice->so_date}}" />
                                                                            </div>

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Invoice Due Date<span
                                                                                        class="rflabelsteric"><strong></strong></span></label>
                                                                                <input type="date"
                                                                                    class="form-control"
                                                                                    placeholder="" name="due_date"
                                                                                    id="due_date"
                                                                                    value="{{$sales_tax_invoice->due_date}}" />
                                                                            </div>

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Despatched
                                                                                    Document No<span
                                                                                        class="rflabelsteric"></span></label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder=""
                                                                                    name="despacth_document_no"
                                                                                    id="despacth_document_no"
                                                                                    value="{{$sales_tax_invoice->despacth_document_no}}" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Despatched
                                                                                    Document Date</label>
                                                                                <input readonly type="date"
                                                                                    class="form-control"
                                                                                    placeholder=""
                                                                                    name="despacth_document_date"
                                                                                    id="despacth_document_date"
                                                                                    value="{{$sales_tax_invoice->despacth_document_date}}" />
                                                                            </div>

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Despatched
                                                                                    through<span
                                                                                        class="rflabelsteric"></span></label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder=""
                                                                                    name="despacth_through"
                                                                                    id="despacth_through"
                                                                                    value="{{$sales_tax_invoice->desptch_through}}" />
                                                                            </div>

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label
                                                                                    class="sf-label">Destination<span
                                                                                        class="rflabelsteric"></span></label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder=""
                                                                                    name="destination"
                                                                                    id="destination"
                                                                                    value="{{$sales_tax_invoice->destination}}" />
                                                                            </div>


                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Terms Of
                                                                                    Delivery<span
                                                                                        class="rflabelsteric"></span></label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder=""
                                                                                    name="terms_of_delivery"
                                                                                    id="terms_of_delivery"
                                                                                    value="{{$sales_tax_invoice->terms_of_delivery}}" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Buyer's Name
                                                                                    <span
                                                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                                                <select style="width: 100%" disabled
                                                                                    name="" id="ntn"
                                                                                    onchange="get_ntn()"
                                                                                    class="form-control select2">
                                                                                    <option>Select</option>
                                                                                    @foreach(SalesHelper::get_all_customer()
                                                                                    as $row)
                                                                                    <option @if($sales_tax_invoice->
                                                                                        buyers_id==$row->id)
                                                                                        selected
                                                                                        @endif
                                                                                        value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn}}">{{$row->name}}
                                                                                    </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <input type="hidden" name="buyers_id"
                                                                                value="{{$sales_tax_invoice->buyers_id}}" />

                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Buyer's Ntn
                                                                                </label>
                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    placeholder="" name="buyers_ntn"
                                                                                    id="buyers_ntn" value="" />
                                                                            </div>


                                                                            <?php
                                                                                $accounts=DB::Connection('mysql2')->table('accounts')->where('status',1)->whereIn('id',array(266, 267))->get();
                                                                            ?>
                                                                            <div
                                                                                class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hide">
                                                                                <label class="sf-label">Cr
                                                                                    Account<span
                                                                                        class="rflabelsteric requiredField"><strong>*</strong></span></label>
                                                                                <select class="form-control"
                                                                                    id="acc_id" name="acc_id">
                                                                                    <option value="">Select</option>
                                                                                    @foreach($accounts as $row)
                                                                                    <option @if($row->id == $sales_tax_invoice->acc_id)
                                                                                        selected
                                                                                        @endif
                                                                                        value="{{$row->id}}">{{$row->name}}
                                                                                    </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <input type="hidden" name="demand_type"
                                                                                id="demand_type">
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 "> 
                                                                                <label class="sf-label">Description</label>
                                                                                <span class="rflabelsteric">
                                                                                    <textarea name="description" id="description" rows="4" cols="50" style="resize:none;text-transform:capitalize" class="form-control">{{$sales_tax_invoice->description}}</textarea>
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                        {{-- SO Fields --}}
                                                                        <?php
                                                                            $relatedSO = DB::connection('mysql2')->table('sales_order')->where('so_no', $sales_tax_invoice->so_no)->first();
                                                                        ?>
                                                                        <div class="row" style="margin-top:10px;">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Mode / Terms Of Payment</label>
                                                                                <input readonly type="text" class="form-control" name="model_terms_of_payment" value="{{$relatedSO->model_terms_of_payment ?? ''}}" />
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Sales Person</label>
                                                                                <input readonly type="text" class="form-control" name="sales_person" value="{{$relatedSO->sales_person ?? ''}}" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" style="margin-top:5px;">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Warehouse</label>
                                                                                <?php
                                                                                    $warehouseName = '';
                                                                                    if(!empty($relatedSO->warehouse_from)) {
                                                                                        $wh = DB::connection('mysql2')->table('warehouse')->where('id', $relatedSO->warehouse_from)->first();
                                                                                        $warehouseName = $wh ? $wh->name : '';
                                                                                    }
                                                                                ?>
                                                                                <input readonly type="text" class="form-control" name="warehouse_name" value="{{$warehouseName}}" />
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Special Price Mapped</label>
                                                                                <input readonly type="text" class="form-control" name="special_price_mapped" value="{{$customerDetail->special_price ?? 'no'}}" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" style="margin-top:5px;">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="sf-label">Principal Group</label>
                                                                                <?php
                                                                                    $principalNames = '';
                                                                                    if(!empty($relatedSO->principal_group_ids)) {
                                                                                        $pgIds = explode(',', $relatedSO->principal_group_ids);
                                                                                        $pgNames = DB::connection('mysql2')->table('products_principal_group')->whereIn('id', $pgIds)->pluck('products_principal_group')->toArray();
                                                                                        $principalNames = implode(', ', $pgNames);
                                                                                    }
                                                                                ?>
                                                                                <input readonly type="text" class="form-control" name="principal_group" value="{{$principalNames}}" />
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div>
                                                                            <h2 class="subHeadingLabelClass">Other
                                                                                Details</h2>
                                                                        </div>
                                                                        <div class="padt">
                                                                            <ul class="sale-l">
                                                                                <li>Balance Amount</li>
                                                                                <li class="text-right"><input
                                                                                        name="Balance-Amount"
                                                                                        class="form-control form-control2"
                                                                                        value="{{$sales_tax_invoice->balance_amount ?? '0.00'}}" type="text"></li>
                                                                            </ul>
                                                                            <ul class="sale-l">
                                                                                <li>Amount Limit</li>
                                                                                <li class="text-right"><input
                                                                                        name="Amount-Limit"
                                                                                        class="form-control form-control2"
                                                                                        value="{{$sales_tax_invoice->credit_limit ?? '0.00'}}" type="text"></li>
                                                                            </ul>
                                                                            <ul class="sale-l">
                                                                                <li>Current Balance Due</li>
                                                                                <li class="text-right"><input
                                                                                        name="Current-Balance-Due"
                                                                                        class="form-control form-control2"
                                                                                        value="{{$sales_tax_invoice->current_balance_due ?? '0.00'}}" type="text"></li>
                                                                            </ul>
                                                                            <ul class="sale-l">
                                                                                <li>N.T.N No</li>
                                                                                <li class="text-right"><input
                                                                                        name="cnic_ntn"
                                                                                        class="form-control form-control2"
                                                                                        value="{{$customerDetail->cnic_ntn ?? ''}}" type="text">
                                                                                </li>
                                                                            </ul>
                                                                            <ul class="sale-l">
                                                                                <li>S.T No</li>
                                                                                <li class="text-right" id="grand_total_top"> <input name="s-t-no" class="form-control form-control2" value="{{$customerDetail->strn ?? ''}}" type="text">
                                                                                </li>
                                                                            </ul>
                                                                            <ul class="sale-l hide">
                                                                                <li>Payment Terms</li>
                                                                                <li class="text-right" id="grand_total_top"><input name="Payment-Terms" class="form-control form-control2" value="5% advance 50% on delivery" type="text">
                                                                                </li>
                                                                            </ul>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="lineHeight">&nbsp;</div>
                                                        <span ondblclick="show()" class="subHeadingLabelClass">Item Details</span>
                                                        <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>

                                                            <div id="addMoreDemandsDetailRows_1"
                                                                class="panel addMoreDemandsDetailRows_1">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered sf-table-list">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-center">S.NO</th>
                                                                                <th class="text-center">Item</th>
                                                                                <th style="display: none"
                                                                                    class="text-center">So Data ID</th>
                                                                                <th class="text-center">Uom</th>
                                                                                <th class="text-center hide">Orderd QTY
                                                                                </th>
                                                                                <th class="text-center">DN QTY</th>
                                                                                <th class="text-center hide">Return QTY
                                                                                </th>
                                                                                <th class="text-center">QTY. <span
                                                                                        class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                <th class="text-center hidee">Rate</th>
                                                                                <th class="text-center hidee">Tax %</th>
                                                                                <th class="text-center">Tax Amount</th>
                                                                                <th class="text-center hidee">Amount
                                                                                </th>
                                                                                <th class="text-center hidee">Net Amount
                                                                                </th>

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                $id_count=0;
                                                                                $total_qty=0;
                                                                                $total=0;
                                                                                $total_tax_amount_php=0;
                                                                                $total_gross_amount_php=0;
                                                                            ?>
                                                                            @foreach($sales_tax_invoice_data as $row1)
                                                                            <?php
                                                                                $id_count++;
                                                                                $dn_qty = $row1->gd_qty;
                                                                                $return_qty = 0; 
                                                                                $current_qty = $row1->qty;
                                                                                $rate = $row1->rate;
                                                                                $tax_percent = $row1->tax;
                                                                                
                                                                                // Fetch saved values from database
                                                                                $tax_amount = $row1->tax_amount;
                                                                                $item_net_amount = $row1->amount;
                                                                                $gross_amount = $item_net_amount - $tax_amount;
                                                                                
                                                                                $total_qty += $current_qty;
                                                                                $total_tax_amount_php += $tax_amount;
                                                                                $total_gross_amount_php += $gross_amount;
                                                                                $total += $item_net_amount;
                                                                            ?>
                                                                            <input type="hidden"
                                                                                name="sales_tax_invoice_data_id{{$id_count}}"
                                                                                value="{{$row1->id}}">
                                                                            <input type="hidden"
                                                                                name="item_id{{$id_count}}"
                                                                                id="item_id{{$id_count}}"
                                                                                value="{{$row1->item_id}}" />
                                                                                
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    {{$id_count}}
                                                                                </td>
                                                                                <td class="text-left">
                                                                                    {{CommonHelper::get_item_name($row1->item_id)}}
                                                                                </td>
                                                                                <td style="display: none">
                                                                                    {{$row1->so_data_id}}
                                                                                </td>

                                                                                <td class="text-left">
                                                                                    <?php 
                                                                                        $sub_ic_detail = CommonHelper::get_subitem_detail($row1->item_id);
                                                                                        $sub_ic_detail = explode(',', $sub_ic_detail);
                                                                                        echo CommonHelper::get_uom_name($sub_ic_detail[0] ?? '');
                                                                                    ?>
                                                                                </td>

                                                                                <td class="text-center hide">
                                                                                    {{$row1->so_qty}}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{$dn_qty}}
                                                                                </td>
                                                                                <td class="text-center hide">
                                                                                    {{$return_qty}}
                                                                                </td>

                                                                                <td class="text-right">
                                                                                    <input readonly type="text"
                                                                                        class="form-control qty dnqty"
                                                                                        name="qty{{$id_count}}"
                                                                                        id="qty{{$id_count}}"
                                                                                        value="{{$current_qty}}" />
                                                                                </td>

                                                                                <td class="text-right hidee">
                                                                                    <input readonly type="text"
                                                                                        class="form-control rate"
                                                                                        name="rate{{$id_count}}"
                                                                                        id="rate{{$id_count}}"
                                                                                        value="{{$rate}}" />
                                                                                </td>

                                                                                <td class="text-right hidee">
                                                                                    <input readonly type="text"
                                                                                        class="form-control tax_percent"
                                                                                        name="tax{{$id_count}}"
                                                                                        id="tax{{$id_count}}"
                                                                                        value="{{$tax_percent}}" />
                                                                                </td>

                                                                                <td class="text-right">
                                                                                    <input readonly type="text"
                                                                                        class="form-control tax_amount"
                                                                                        name="tax_amount{{$id_count}}"
                                                                                        id="tax_amount{{$id_count}}"
                                                                                        value="{{number_format($tax_amount, 2, '.', '')}}" />
                                                                                </td>

                                                                                <td class="text-right hidee">
                                                                                    <input readonly type="text"
                                                                                        class="form-control gross_amount"
                                                                                        name="amount{{$id_count}}"
                                                                                        id="amount{{$id_count}}"
                                                                                        value="{{number_format($gross_amount, 2, '.', '')}}" />
                                                                                </td>

                                                                                <td class="text-right hidee">
                                                                                    <input readonly type="text"
                                                                                        class="form-control amount total comma_seprated"
                                                                                        name="net_amount{{$id_count}}"
                                                                                        id="net_amount{{$id_count}}"
                                                                                        value="{{number_format($item_net_amount, 2, '.', '')}}" />
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                            <input type="hidden" name="count" id="count"
                                                                                value="{{$id_count}}" />
                                                                        </tbody>
                                                                        <tr class="hide">
                                                                            <td style="background-color: darkgray;font-weight: bolder;font-size: x-large"class="text-center" colspan="9">W.H Tax
                                                                            </td>
                                                                            <td colspan="1"style="background-color: darkgray;font-weight: bolder;font-size: x-large">
                                                                                <input readonly type="text" class="text-right comma_seprated"name="wh_tax" value="{{$customerDetail->wh_tax ?? 0}}" id="wh_tax" />
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="hide">
                                                                            <td style="background-color: darkgray;font-weight: bolder;font-size: x-large" class="text-center" colspan="9">Adv. Tax</td>
                                                                            <td colspan="1"style="background-color: darkgray;font-weight: bolder;font-size: x-large">
                                                                                <input readonly type="text" class="text-right comma_seprated" name="adv_tax" value="{{$customerDetail->adv_tax ?? 0}}" id="adv_tax" />
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <table>
                                                                <tr>
                                                                    <td style="text-transform: capitalize;" id="rupees"></td>
                                                                    <input type="hidden" value="" name="rupeess" id="rupeess1" />
                                                                </tr>
                                                            </table>
                                                            <input type="hidden" id="d_t_amount_1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
                                            <div class="demandsSection"></div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                <div class="col-md-10">
                                                    <h2 class="subHeadingLabelClass">Sub Total</h2>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="padt">
                                                        <ul class="sale-l sale-l2">
                                                            <li>Total Qty</li>
                                                            <li class="text-left">
                                                                <input name="total_qty" class="form-control form-control2" id="total_qty" value="{{number_format($total_qty, 3, '.', '')}}" type="text" readonly>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-md-3" style="float: right; margin-bottom: 20px;">
                                                    <div class="padt">
                                                        <ul class="sale-l sale-l2">
                                                            <li>Gross Amount</li>
                                                            <li class="text-left"><input name="total_gross_amount"
                                                                    id="total_gross_amount"
                                                                    class="form-control form-control2" value="{{number_format($total_gross_amount_php, 2, '.', '')}}" type="text"
                                                                    readonly>
                                                            </li>
                                                        </ul>
                                               
                                                        <ul class="sale-l sale-l2">
                                                            <li>Tax</li>
                                                            <li class="text-left"><input name="total_sales_tax"
                                                                    id="total_sales_tax" class="form-control form-control2"
                                                                    value="{{number_format($total_tax_amount_php, 2, '.', '')}}" type="text" readonly></li>
                                                        </ul>
                                                        <ul class="sale-l sale-l2">
                                                            <li>PST</li>
                                                            <li class="text-left"><input name="pst_amount"
                                                                    id="pst_amount"
                                                                    class="form-control form-control2" value="{{ $sales_tax_invoice->adv_tax ?? 0 }}" type="text"
                                                                    readonly>
                                                            </li>
                                                        </ul>
                                                        <ul class="sale-l sale-l2 hide">
                                                            <li>WH Tax Amount</li>
                                                            <li class="text-left"><input name="wh_tax_amount"
                                                                    id="wh_tax_amount"
                                                                    class="form-control form-control2" value="{{ $sales_tax_invoice->wh_tax ?? 0 }}" type="text"
                                                                    readonly>
                                                            </li>
                                                        </ul>
                                                        <ul class="sale-l sale-l2 hide">
                                                            <li>Adv Tax Amount</li>
                                                            <li class="text-left"><input name="adv_tax_amount"
                                                                    id="adv_tax_amount"
                                                                    class="form-control form-control2" value="" type="text"
                                                                    readonly>
                                                            </li>
                                                        </ul>
                                                        <ul class="sale-l sale-l2">
                                                            <li>Net Amount</li>
                                                            <li class="text-left"><input name="total_amount_after_sale_tax"
                                                                    id="total_amount_after_sale_tax"
                                                                    class="form-control form-control2" value="{{number_format($sales_tax_invoice->total + $sales_tax_invoice->adv_tax, 2, '.', '')}}" type="text"
                                                                    readonly>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3 my-lab">
                                                    {{ Form::submit('Update', ['class' => 'btn btn-primary mr-1']) }}
                                                    <a type="button" href="{{url('selling/listSalesTaxInvoice')}}?m={{$m}}"
                                                        class="btnn btn-secondary" data-dismiss="modal">Clear
                                                        Form</a>
                                                </div>
                                            </div>
                                            <?php echo Form::close();?>
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

    <script>
    $(document).ready(function() {
        totalQty();
        totalGrossAmount();
        totalTaxAmount();
        totalAmount();
        get_ntn();
    });

    function get_ntn() {
        var ntn = $('#ntn').val();
        if (ntn) {
            ntn = ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
        }
    }

    function totalQty() {
        var totalQty = 0;
        $('.dnqty').each(function() {
            var qty = parseFloat($(this).val()) || 0; 
            totalQty += qty;
        });
        $('#total_qty').val(totalQty.toFixed(3));
    }

    function totalGrossAmount() {
        var totalGrossAmount = 0;
        $('.gross_amount').each(function() {
            var grossAmount = parseFloat($(this).val()) || 0; 
            totalGrossAmount += grossAmount;
        });
        $('#total_gross_amount').val(totalGrossAmount.toFixed(2));
    }

    function totalTaxAmount() {
        var totalTaxAmount = 0;
        $('.tax_amount').each(function() {
            var taxAmount = parseFloat($(this).val()) || 0;
            totalTaxAmount += taxAmount;
        });
        $('#total_sales_tax').val(totalTaxAmount.toFixed(2));
    }
    
    function totalAmount() {
        var totalAmount = 0;
        $('.amount').each(function() {
            var amount = parseFloat($(this).val()) || 0; 
            totalAmount += amount;
        });

        let WH_Tax = parseFloat($('#wh_tax').val()) || 0; 
        let ADV_Tax = parseFloat($('#adv_tax').val()) || 0; 

        let wh_tax_amount = (WH_Tax / 100) * totalAmount;
        let adv_tax_amount = (ADV_Tax / 100) * totalAmount;
        let pst_amount = parseFloat($('#pst_amount').val()) || 0;

       let finalNet = Math.round(totalAmount + pst_amount);
        
        $('#wh_tax_amount').val(wh_tax_amount.toFixed(2));
        $('#adv_tax_amount').val(adv_tax_amount.toFixed(2));
        $('#total_amount_after_sale_tax').val(finalNet.toFixed(2));
    }

    function calculate_due_date() {
        // Mock function if needed, or implement actual logic from SO date
    }
    </script>
    
    <script type="text/javascript">
    $('.select2').select2();
    </script>
    @endsection