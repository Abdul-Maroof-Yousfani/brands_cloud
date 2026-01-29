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
use App\Helpers\ReuseableCode;

$buyer_detail = CommonHelper::get_buyer_detail($sales_order->buyers_id);

?>
@extends('layouts.default')

@section('content')
    @include('loader')
    @include('number_formate')
    @include('select2')
    <?php // ReuseableCode::get_stock(1804,1,7.3,0); ?>
    <style>
        * {
            font-size: 12px !important;

        }

        label {
            text-transform: capitalize;
        }
    </style>
    <?php $so_no= SalesHelper::get_unique_no(date('y'),date('m')); ?>

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
                                                    <span class="subHeadingLabelClass">Create Goods Delivery Note</span>
                                                </div>
                                            </div>
                                            <hr style="border-bottom: 1px solid #f1f1">
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'sad/addeDeliveryNote?m='.$m.'','id'=>'createSalesOrder','class'=>'stop underfieldvalidation'));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="pageType"
                                                       value="<?php // echo $_GET['pageType']?>">
                                                <input type="hidden" name="parentCode"
                                                       value="<?php // echo $_GET['parentCode']?>">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="panel">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div
                                                                        class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padt pos-r">

                                                                    <div class="row">
                                                                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                                                            <h2 class="subHeadingLabelClass">GDN Details
                                                                            </h2>
                                                                        </div>

                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <div class="row">
                                                                                <?php
                                                                                //$gd_no=$sales_order->so_no;
                                                                                $gd_date=$sales_order->so_date;
                                                                                //$gd_no=str_replace("SO","gd",$gd_no);
                                                                                $gd_no= SalesHelper::get_unique_no_delivery_note(date('y'),date('m'));
                                                                                ?>

                                                                                <div class="row">

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Delivery
                                                                                            Note
                                                                                            No<span
                                                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                                                        <input readonly type="text"
                                                                                               class="form-control requiredField"
                                                                                               placeholder="" name="gd_no"
                                                                                               id="gd_no"
                                                                                               value="{{strtoupper($gd_no)}}" />
                                                                                    </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Delivery
                                                                                            Note
                                                                                            Date<span
                                                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                                                        <input autofocus type="date"
                                                                                               class="form-control requiredField"
                                                                                               placeholder="" name="gd_date"
                                                                                               id="gd_date"
                                                                                               value="{{date('Y-m-d')}}" />
                                                                                    </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Mode / Terms
                                                                                            Of
                                                                                            Payment <span
                                                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                                                        <input
                                                                                                type="text"
                                                                                                class="form-control requiredv "
                                                                                                name="model_terms_of_payment"
                                                                                                id="model_terms_of_payment"
                                                                                                placeholder=""
                                                                                                value="{{ old('model_terms_of_payment', $sales_order->model_terms_of_payment) }}"
                                                                                        />      </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">SO No. <span
                                                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                                                        <input readonly type="text"
                                                                                               class="form-control requiredField"
                                                                                               placeholder="" name="so_no"
                                                                                               id="so_no"
                                                                                               value="{{$sales_order->so_no}}" />
                                                                                    </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Supplier's
                                                                                            Ref Date
                                                                                            <span
                                                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                                                        <input readonly type="date"
                                                                                               class="form-control requiredField"
                                                                                               placeholder="" name="so_date"
                                                                                               id="so_date"
                                                                                               value="{{$sales_order->so_date}}" />
                                                                                    </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Despatched
                                                                                            Document Date</label>
                                                                                        <input type="date"
                                                                                               class="form-control"
                                                                                               placeholder=""
                                                                                               name="despacth_document_date"
                                                                                               id="despacth_document_date"
                                                                                               value="" />
                                                                                    </div>

                                                                                </div>


                                                                                <div class="row">
                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Despatched
                                                                                            through<span
                                                                                                    class="rflabelsteric"></span></label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder=""
                                                                                               name="despacth_through"
                                                                                               id="despacth_through"
                                                                                               value="{{$sales_order->desptch_through}}" />
                                                                                    </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label
                                                                                                class="sf-label">Destination<span
                                                                                                    class="rflabelsteric"></span></label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder=""
                                                                                               name="destination"
                                                                                               id="destination"
                                                                                               value="{{$sales_order->destination}}" />
                                                                                    </div>

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
                                                                                                <option @if($sales_order->
                                                                                            buyers_id==$row->id)
                                                                                                            selected @endif
                                                                                                        value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn}}">{{$row->name}}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>

                                                                                    <div
                                                                                            class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <input type="hidden"
                                                                                               name="buyers_id"
                                                                                               value="{{$sales_order->buyers_id}}" />
                                                                                        <label class="sf-label">Buyer's Ntn
                                                                                        </label>
                                                                                        <input readonly type="text"
                                                                                               class="form-control"
                                                                                               placeholder="" name="buyers_ntn"
                                                                                               id="buyers_ntn" value="" />
                                                                                    </div>

                                                                                </div>


                                                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">SO No. </label>
                                                                                    <input name="sale_order_no" readonly
                                                                                        class="form-control" value="{{$so_no}}"
                                                                                        type="text">
                                                                                </div>
    
                                                                            </div>
    
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">SO Date</label>
                                                                                    <input name="sale_order_date"
                                                                                        value="{{date('Y-m-d')}}" class="form-control"
                                                                                        type="date">
                                                                                </div>
                                                                            </div>
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <label class="control-label">Store Name</label>
                                                                                <select onchange="getCustomer(this)"
                                                                                    name="customer_name" class="form-control"
                                                                                    id="customer_name">
                                                                                    <option value="">Select</option>
                                                                                    @foreach(CommonHelper::get_customer() as $item)
                                                                                    <option value="{{$item->id}}" id="tax{{$item->id}}">
                                                                                        {{$item->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Phone No </label>
                                                                                    <input name="phone_no" class="form-control"
                                                                                        type="number">
                                                                                </div>
                                                                            </div>
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Address</label>
                                                                                    <input name="address" class="form-control"
                                                                                        type="text">
                                                                                </div>
                                                                            </div>
    
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Branch</label>
                                                                                    <input name="branch" class="form-control"
                                                                                        type="text">
                                                                                </div>
                                                                            </div>
    
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Salesperson </label>
                                                                                    <input name="saleperson" class="form-control"
                                                                                        type="text">
                                                                                </div>
                                                                            </div>
    
    
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Salesperson Mobile
                                                                                    </label>
                                                                                    <input name="saleperson_mobile" class="form-control"
                                                                                        type="text">
                                                                                </div>
                                                                            </div>
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                <div class="form-group">
                                                                                    <label class="control-label">Special Price Mappeds
                                                                                    </label>
                                                                                    <input readonly name="special_price_mapped" value=""
                                                                                        class="form-control" id="special_price_mapped"
                                                                                        type="text">
                                                                                </div>
                                                                            </div> -->
                                                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                    <label class="sf-label">Other Reference(s) <span class="rflabelsteric"><strong>*</strong></span></label>
                                                                                    <input readonly type="text" class="form-control" placeholder="" name="other_refrence" id="other_refrence" value="{{$sales_order->other_refrence}}" />
                                                                                </div> -->

                                                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                    <label class="sf-label">Buyer's Order No<span class="rflabelsteric"></span></label>
                                                                                    <input readonly type="text" class="form-control" placeholder="" name="order_no" id="order_no" value="{{$sales_order->order_no ?? '-'}}" />
                                                                                </div> -->

                                                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                    <label class="sf-label">Buyer's Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                                    <input readonly type="date" class="form-control requiredField" placeholder="" name="order_date" id="order_date" value="{{$sales_order->purchase_order_date}}" />
                                                                                </div> -->

                                                                                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Despatched Document No<span class="rflabelsteric"><strong>*</strong></span></label>
                                                                                        <input  type="text" class="form-control" placeholder="" name="despacth_document_no" id="despacth_document_no" value="" />
                                                                                    </div> -->

                                                                                <!--  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                    <label class="sf-label">Terms Of Delivery<span class="rflabelsteric"></span></label>
                                                                                    <input readonly type="text" class="form-control" placeholder="" name="terms_of_delivery" id="terms_of_delivery" value="{{$sales_order->terms_of_delivery}}" />
                                                                                </div> -->

                                                                                <!--  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                        <label class="sf-label">Buyer's Sales Tax No </label>
                                                                                        <input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="" />
                                                                                    </div> -->

                                                                                <!--  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                                    <label class="sf-label">Due Date <span class="rflabelsteric"></span></label>
                                                                                    <input readonly type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="{{$sales_order->due_date}}" />
                                                                                </div> -->

                                                                                <div class="row">
                                                                                    <div
                                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <label
                                                                                                class="sf-label">Description</label>
                                                                                        <span class="rflabelsteric">
                                                                                        <textarea name="description"
                                                                                                  id="description" rows="4"
                                                                                                  cols="50"
                                                                                                  style="resize:none;text-transform: capitalize"
                                                                                                  class="form-control"></textarea>

                                                                                        <input type="hidden"
                                                                                               name="virtual_warehouse_check"
                                                                                               value="{{$sales_order->virtual_warehouse_check}}" />
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="demand_type"
                                                                                   id="demand_type">

                                                                        </div>

                                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hide">
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
                                                                                                value="{{isset($sales_order->balance_amount) ? $sales_order->balance_amount : '-'}}"
                                                                                                type="text" readonly></li>
                                                                                </ul>
                                                                                <ul class="sale-l">
                                                                                    <li>Amount Limit</li>
                                                                                    <li class="text-right"><input
                                                                                                name="Amount-Limit"
                                                                                                class="form-control form-control2"
                                                                                                value="{{isset($sales_order->credit_limit) ? $sales_order->credit_limit : '-'}}"
                                                                                                type="text" readonly></li>
                                                                                </ul>
                                                                                <ul class="sale-l">
                                                                                    <li>Current Balance Due</li>
                                                                                    <li class="text-right"><input
                                                                                                name="Current-Balance-Due"
                                                                                                class="form-control form-control2"
                                                                                                value="{{isset($sales_order->current_balance_due) ? $sales_order->current_balance_due : '-'}}"
                                                                                                type="text" readonly></li>
                                                                                </ul>
                                                                                <ul class="sale-l">
                                                                                    <li>N.T.N No</li>
                                                                                    <li class="text-right"><input
                                                                                                name="n-t-n"
                                                                                                class="form-control form-control2"
                                                                                                value="{{isset($buyer_detail->cnic_ntn) ? $buyer_detail->cnic_ntn : '-'}}"
                                                                                                type="text" readonly></li>
                                                                                </ul>
                                                                                <ul class="sale-l">
                                                                                    <li>S.T No</li>
                                                                                    <li class="text-right"
                                                                                        id="grand_total_top">
                                                                                        <input name="s-t-no"
                                                                                               class="form-control form-control2"
                                                                                               value="{{isset($buyer_detail->strn) ? $buyer_detail->strn : '-'}}"
                                                                                               type="text" readonly>
                                                                                    </li>
                                                                                </ul>
                                                                                <!-- <ul class="sale-l">
                                                                                    <li>Payment Terms</li>
                                                                                    <li class="text-right" id="grand_total_top">
                                                                                        <input name="Payment-Terms"
                                                                                            class="form-control form-control2"
                                                                                            value="5% advance 50% on delivery"
                                                                                            type="text">
                                                                                    </li>
                                                                                </ul> -->
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="lineHeight">&nbsp;</div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <span ondblclick="show()" class="subHeadingLabelClass">Item
                                                                Details</span>
                                                                <input class="hide" checked type="checkbox"
                                                                       id="amount_data" />
                                                            </div>
                                                            <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>
                                                            <div id="addMoreDemandsDetailRows_1"
                                                                 class="panel addMoreDemandsDetailRows_1">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="table-responsive">
                                                                        <table
                                                                                class="table table-bordered table-striped table-condensed tableMargin">
                                                                            <thead>
                                                                            <tr>
                                                                                <th class="text-center">S.NO</th>
                                                                                <th class="text-center">Item</th>
                                                                                <th class="text-center">Barcode</th>

                                                                                <th class="text-center">Uom</th>


                                                                                <th class="text-center">QTY. <span
                                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                <th class="text-center">Ordered QTY. <span
                                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                <th class="text-center">DN QTY. <span class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                <th class="text-center hide">FOC. <span
                                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>

                                                                                <th class="text-center">WareHouse From
                                                                                    <span
                                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                @if($sales_order->virtual_warehouse_check
                                                                                == 1)
                                                                                    <th class="text-center">WareHouse To
                                                                                        <span
                                                                                                class="rflabelsteric"><strong>*</strong></span>
                                                                                    </th>
                                                                                @endif
                                                                                <th class="hide" style="width: 150px"
                                                                                    class="text-center">Batch Code <span
                                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                <th class="text-center">C.Stock<span
                                                                                            class="rflabelsteric"><strong>*</strong></span>
                                                                                </th>
                                                                                <th class="text-center hide">Rate</th>
                                                                                <th class="text-center hide">MRP</th>

                                                                                <th class="text-center hide">Tax %</th>
                                                                                <th class="text-center hide">Tax Amount
                                                                                </th>
                                                                                <th class="text-center hide">Net Amount
                                                                                </th>
                                                                                <!-- <th class="text-center hidee">0 QTY</th> -->

                                                                                <!-- 
                                                                                            <th style="width: 150px;">Brand</th>
                                                                                            <th>Item & Description</th>
                                                                                            <th>Qty</th>
                                                                                            <th>FOC</th>
                                                                                            <th>MRP</th>
                                                                                            <th>Rate</th>
                                                                                            <th>Gross Amount</th>
                                                                                            <th>Disc (%)</th>
                                                                                            <th>Disc Amount</th>
                                                                                            <th class="hidee">Disc 2(%)</th>
                                                                                            <th class="hidee">Disc 2 Amount</th>
                                                                                            <th class="hidee">Tax (%)</th>
                                                                                            <th class="hidee">Tax Amount</th>
                                                                                            <th class="hidee">Total Amount</th> -->

                                                                            </tr>
                                                                            </thead>
                                                                            <input type="hidden" name="master_id"
                                                                                   id="master_id"
                                                                                   value="{{$sales_order->id}}" />
                                                                            <tbody>
                                                                            <?php
                                                                            $counter=1;
                                                                            $id_count=0;
                                                                            $working_counter=0;
                                                                            $total=0;
                                                                            $total_qty=0;

                                                                            ?>


                                                                            <?php

                                                                            // echo "<pre>";
                                                                            // var_dump($sale_order_data);
                                                                            foreach ($sale_order_data as $row1)
                                                                            {   

                                                                                
                                                                            // var_dump($row1);
                                                                            if ($row1->bundles_id==0):



                                                                                $qty = $dnQty =SalesHelper::get_dn_total_qty($row1->id);
                                                                                $foc=SalesHelper::get_dn_total_foc($row1->id);

                                                                                $foc =$row1->foc - $foc;
                                                                                $qty=$row1->qty - $qty;
                                                                                
                                                                                if (true):
                                                                                $working_counter++;
                                                                                $id_count++;    
                                                                                
                                                                                ?>
                                                                            {{--hidden data--}}
                                                                            <input type="hidden"
                                                                                   name="data_id{{$id_count}}" id="data_id"
                                                                                   value="{{$row1->id}}" />
                                                                            <input type="hidden"
                                                                                   name="groupby{{$id_count}}" id="groupby"
                                                                                   value="{{$row1->groupby}}" />
                                                                            <input type="hidden"
                                                                                   name="item_id{{$id_count}}"
                                                                                   id="item_id{{$id_count}}"
                                                                                   value="{{$row1->item_id}}" />
                                                                            <input type="hidden"
                                                                                   name="rate{{$id_count}}"
                                                                                   id="rate{{$id_count}}"
                                                                                   value="{{$row1->rate}}" />
                                                                            <input type="hidden"
                                                                                   name="discount_percent{{$id_count}}"
                                                                                   id="discount_percent{{$id_count}}"
                                                                                   value="{{$row1->tax}}" />
                                                                            <input type="hidden"
                                                                                   name="discount_amount{{$id_count}}"
                                                                                   id="discount_amount{{$id_count}}"
                                                                                   value="{{$row1->tax_amount}}" />
                                                                            <input type="hidden"
                                                                                   name="amount{{$id_count}}"
                                                                                   id="amount{{$id_count}}"
                                                                                   value="{{$row1->amount}}" />


                                                                            {{--hidden data End --}}


                                                                            <tr title="">
                                                                                <td class="text-center"
                                                                                    class="text-center">
                                                                                        <?php echo $counter.' ('.$row1->id.')';?>
                                                                                </td>
                                                                                <td id="{{$row1->item_id}}"
                                                                                    class="text-left">

                                                                                    @php
                                                                                        $product_data =
                                                                                        explode(',',CommonHelper::get_subitem_detail($row1->item_id));

                                                                                        $product_name=$product_data[6];
                                                                                    @endphp
                                                                                    <textarea readonly
                                                                                              class="form-control"
                                                                                              name="desc{{$id_count}}"
                                                                                              style="margin: 0px 221.973px 0px 0px; resize: none; ">{{$product_name}}</textarea>
                                                                                </td>

                                                                                
                                                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                                                    $sub_ic_detail= explode(',',$sub_ic_detail);
                                                                                    ?>
                                                                                <td>
                                                                                    <input type="text" value='{{ $sub_ic_detail[3] }}' style="width: 150px; text-align: center;" class="form-control" readonly />
                                                                                </td>
                                                                                <td class="text-left">
                                                                                        <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?>
                                                                                </td>

                                                                                    <?php $total_qty+=$row1->qty; ?>
                                                                                <td class="text-right">
                                                                                    <input style="width:150px;"
                                                                                           oninput="calc('{{$id_count}}'); totalQty();"
                                                                                           onblur="calc('{{$id_count}}')"
                                                                                           class="form-control qty {{'item'.$row1->item_id}}"
                                                                                           type="text"
                                                                                           name="send_qty{{$id_count}}"
                                                                                           id="send_qty{{$id_count}}"
                                                                                           value="{{$qty}}" />
                                                                                    <input type="hidden" class=""
                                                                                           name="qty{{$id_count}}"
                                                                                           id="qty{{$id_count}}"
                                                                                           value="{{$qty}}" />
                                                                                    <input type="hidden" class=""
                                                                                           name="qtyMain{{$id_count}}"
                                                                                           id="qtyMain{{$id_count}}"
                                                                                           value="{{$row1->qty}}" />
                                                                                </td>
                                                                                <td class="text-left">
                                                                                    {{$row1->qty}}
                                                                                </td>
                                                                                <td class="text-left">
                                                                                    {{$dnQty}}
                                                                                </td>

                                                                                <td class="text-right hide">
                                                                                    <input style="width:150px;"
                                                                                           onkeyup="calc('{{$id_count}}')"
                                                                                           onblur="calc('{{$id_count}}')"
                                                                                           class="form-control foc {{'item'.$row1->item_id}}"
                                                                                           type="text"
                                                                                           name="foc_qty{{$id_count}}"
                                                                                           id="foc_qty{{$id_count}}"
                                                                                           value="{{$foc}}" />
                                                                                    <input style="width:150px;"
                                                                                           type="hidden" class=""
                                                                                           name="foc{{$id_count}}"
                                                                                           id="foc{{$id_count}}"
                                                                                           value="{{$foc}}" />
                                                                                </td>


                                                                                    <?php $type =  CommonHelper::get_item_type($row1->item_id); ?>
                                                                                <td>
                                                                                    @if($buyer_detail->warehouse_from !=
                                                                                    0)
                                                                                        <!-- <input style="width:150px;" readonly
                                                                                               class="form-control {{$row1->item_id}}"
                                                                                               type="text"
                                                                                               value="{{CommonHelper::get_name_warehouse($buyer_detail->warehouse_from)}}"
                                                                                               id="warehouse{{$id_count}}" />
                                                                                        <input style="width:150px;" readonly
                                                                                               class="form-control {{$row1->item_id}}"
                                                                                               type="hidden"
                                                                                               name="warehouse{{$id_count}}"
                                                                                               value="{{$buyer_detail->warehouse_from}}"
                                                                                               id="warehouse{{$id_count}}" /> -->


                                                                                               @if($row1->warehouse_id)
                                                                                                    <input style="width:150px;" readonly
                                                                                                        class="form-control {{ $row1->item_id }}"
                                                                                                        type="text"
                                                                                                        value="{{ CommonHelper::get_name_warehouse($row1->warehouse_id) }}"
                                                                                                        id="warehouse{{ $id_count }}" />

                                                                                                    <input style="width:150px;" readonly
                                                                                                        class="form-control {{ $row1->item_id }}"
                                                                                                        type="hidden"
                                                                                                        name="warehouse{{ $id_count }}"
                                                                                                        value="{{ $row1->warehouse_id }}"
                                                                                                        id="warehouse{{ $id_count }}" />
                                                                                                @else
                                                                                                    <input style="width:150px;" readonly
                                                                                                        class="form-control {{ $row1->item_id }}"
                                                                                                        type="text"
                                                                                                        value="{{ CommonHelper::get_name_warehouse($buyer_detail->warehouse_from) }}"
                                                                                                        id="warehouse{{ $id_count }}" />

                                                                                                    <input style="width:150px;" readonly
                                                                                                        class="form-control {{ $row1->item_id }}"
                                                                                                        type="hidden"
                                                                                                        name="warehouse{{ $id_count }}"
                                                                                                        value="{{ $buyer_detail->warehouse_from }}"
                                                                                                        id="warehouse{{ $id_count }}" />
                                                                                                @endif

                                                                                    @else
                                                                                        <select style="width:150px;"
                                                                                                onchange="get_stock(this.id,'{{$id_count}}'); ApplyAll('<?php echo $id_count?>');"
                                                                                                class="form-control requiredField ClsAll ShowOn<?php echo $counter?>"
                                                                                                name="warehouse{{$id_count}}"
                                                                                                id="warehouse{{$id_count}}">
                                                                                            <option value="">Select</option>
                                                                                            @foreach(CommonHelper::get_all_warehouse()
                                                                                            as $row)
                                                                                                <option value="{{$row->id}}">
                                                                                                    {{$row->name}}</option>
                                                                                            @endforeach

                                                                                        </select>
                                                                                    @endif


                                                                                </td>
                                                                                @if($sales_order->virtual_warehouse_check == 1)
                                                                                    <td>
                                                                                        <select style="width:150px;"
                                                                                                onchange=""
                                                                                                class="form-control ClsAll ShowOn<?php echo $counter?>"
                                                                                                name="warehouse_to{{$id_count}}"
                                                                                                id="warehouse_to{{$id_count}}">
                                                                                            <option value="">Select</option>
                                                                                            @foreach(CommonHelper::get_all_virtual_warehouse()
                                                                                            as $row)
                                                                                                <option value="{{$row->id}}" selected>
                                                                                                    {{$row->name}}</option>
                                                                                            @endforeach

                                                                                        </select>
                                                                                    </td>
                                                                                @endif



                                                                                <td class="hide">


                                                                                    <select style="width:150px;"
                                                                                            onchange="get_stock_qty(this.id,'{{$id_count}}')"
                                                                                            class="form-control requiredField"
                                                                                            name="batch_code{{$id_count}}"
                                                                                            id="batch_code{{$id_count}}">
                                                                                        <option value="">
                                                                                            Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                        </option>

                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                        <!-- <?php
                                                                                        $stockqty=ReuseableCode::get_stock($row1->item_id,$buyer_detail->warehouse_from);

                                                                                        ?> -->

                                                                                         <?php
                                                                                $warehouseId = $row1->warehouse_id ?? $buyer_detail->warehouse_from;
                                                                                $stockqty = ReuseableCode::get_stock($row1->item_id, $warehouseId);
                                                                            ?> 

                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control instock {{$row1->item_id}}"
                                                                                           type="text" value="{{$stockqty}}"
                                                                                           id="instock{{$id_count}}" />

                                                                                </td>


                                                                                <td class="text-right hide">
                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control" type="text"
                                                                                           name="send_rate{{$id_count}}"
                                                                                           id="send_rate{{$id_count}}"
                                                                                           value="{{$row1->rate}}" />

                                                                                </td>
                                                                                <td class="text-right hide">
                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control" type="text"
                                                                                           name="mrp_price{{$id_count}}"
                                                                                           id="mrp_price{{$id_count}}"
                                                                                           value="{{$row1->mrp_price}}" />

                                                                                </td>
                                                                                <td class="text-right hide">
                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control" type="text"
                                                                                           name="send_discount{{$id_count}}"
                                                                                           id="send_discount{{$id_count}}"
                                                                                           value="{{$row1->tax}}" />
                                                                                </td>

                                                                                <td class="text-right hide">

                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control" type="text"
                                                                                           name="send_discount_amount{{$id_count}}"
                                                                                           id="send_discount_amount{{$id_count}}"
                                                                                           value="{{$row1->tax_amount}}" />
                                                                                </td>
                                                                                @php
                                                                                    $netAmount = $row1->amount;
                                                                                @endphp
                                                                                <td class="text-right">
                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control amount comma_seprated"
                                                                                           type="hidden"
                                                                                           name="send_amount{{$id_count}}"
                                                                                           id="send_amount{{$id_count}}"
                                                                                           value="{{$netAmount}}" />
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <input style="width:150px;" readonly
                                                                                           class="form-control amount comma_seprated"
                                                                                           type="hidden"
                                                                                           name="send_amount_new{{$id_count}}"
                                                                                           id="send_amount_new{{$id_count}}"
                                                                                           value="{{$netAmount}}" />
                                                                                </td>


                                                                                <!-- <td><input type="checkbox" class="" id="check{{$id_count}}" onclick="required_none('{{$id_count}}','{{$qty}}')" ></td> -->
                                                                                <input style="width:150px;"
                                                                                       type="hidden"
                                                                                       name="bundles_id{{$working_counter}}"
                                                                                       value="0" />
                                                                            </tr>
                                                                            <?php endif;

                                                                            else:  ?>



                                                                                <?php $product_data=DB::Connection('mysql2')->table('sales_order_data')->where('bundles_id',$row1->bundles_id)->select('*')->get();



                                                                                $item_count=$counter+0.1;
                                                                                $bundle_stop=1;
                                                                            foreach ($product_data as $bundle_data):


                                                                                $qty=SalesHelper::get_dn_total_qty($bundle_data->id);

                                                                                $qty=$bundle_data->qty-$qty;

                                                                            if ($qty>0):
                                                                                $working_counter++;
                                                                                $id_count++;
                                                                                ?>
                                                                            <input type="hidden"
                                                                                   name="groupby{{$id_count}}" id="groupby"
                                                                                   value="{{$bundle_data->groupby}}" />
                                                                            @if ($bundle_stop==1)
                                                                                <tr
                                                                                        style="font-size: larger;font-weight: bold;background-color: lightyellow">
                                                                                    <td class="text-center"
                                                                                        class="text-center">
                                                                                            <?php echo $counter;?>
                                                                                    </td>
                                                                                    <td id="" class="text-left">
                                                                                            <?php echo $row1->product_name;?>
                                                                                    </td>
                                                                                    <td class="text-left">
                                                                                            <?php  echo CommonHelper::get_uom_name($row1->bundle_unit);   ?>
                                                                                    </td>
                                                                                    <td class="text-right">
                                                                                            <?php echo number_format($row1->bqty,3)?>
                                                                                    </td>
                                                                                    <td></td>
                                                                                    <td class="text-right hidee">
                                                                                            <?php echo number_format($row1->bundle_rate,2);?>
                                                                                    </td>
                                                                                    <td class="text-right hidee">
                                                                                            <?php echo number_format($row1->amount,2);?>
                                                                                    </td>
                                                                                    <td class="text-right hidee">
                                                                                            <?php echo number_format($row1->b_percent,2);?>
                                                                                    </td>
                                                                                    <td class="text-right hidee">
                                                                                            <?php echo number_format($row1->b_dis_amount,2);?>
                                                                                    </td>
                                                                                    <td class="text-right hidee">
                                                                                            <?php echo number_format($row1->b_net,2);?>
                                                                                    </td>

                                                                                </tr>
                                                                                    <?php $bundle_stop++ ?>
                                                                            @endif

                                                                            <input type="hidden"
                                                                                   name="data_id{{$id_count}}" id="data_id"
                                                                                   value="{{$bundle_data->id}}" />
                                                                            <input type="hidden" name="qty{{$id_count}}"
                                                                                   id="qty{{$id_count}}"
                                                                                   value="{{$qty}}" />
                                                                            <input type="hidden"
                                                                                   name="bundles_id{{$id_count}}"
                                                                                   value="{{$bundle_data->bundles_id}}" />
                                                                            <input type="hidden"
                                                                                   name="item_id{{$id_count}}"
                                                                                   id="item_id{{$id_count}}"
                                                                                   value="{{$bundle_data->item_id}}" />

                                                                            <tr title="{{CommonHelper::get_item_name($bundle_data->item_id)}}"
                                                                                style="background-color: lightyellow">
                                                                                <td class="text-center"
                                                                                    class="text-center">
                                                                                        <?php echo $item_count;?></td>
                                                                                <td id="{{$bundle_data->item_id}}"
                                                                                    class="text-left">
                                                                                        <?php echo $bundle_data->desc;?>
                                                                                    <textarea readonly
                                                                                              name="desc{{$id_count}}"
                                                                                              class="form-control"
                                                                                              style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"> {{$bundle_data->desc}}</textarea>
                                                                                </td>



                                                                                    <?php $sub_ic_detail=CommonHelper::get_subitem_detail($row1->item_id);
                                                                                    $sub_ic_detail= explode(',',$sub_ic_detail)
                                                                                    ?>
                                                                                <td class="text-left">
                                                                                        <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?>
                                                                                </td>

                                                                                <td class="text-right"> <input
                                                                                            onkeyup="calc('{{$id_count}}')"
                                                                                            onblur="calc('{{$id_count}}')"
                                                                                            class="form-control qty {{'item'.$bundle_data->item_id}}"
                                                                                            type="text"
                                                                                            name="send_qty{{$id_count}}"
                                                                                            id="send_qty{{$id_count}}"
                                                                                            value="{{$qty}}" />
                                                                                    <input type="hidden"
                                                                                           name="qty{{$id_count}}"
                                                                                           id="qty{{$id_count}}"
                                                                                           value="{{$qty}}" />
                                                                                </td>
                                                                                <td><select
                                                                                            onchange="get_stock(this.id,'{{$id_count}}');ApplyAll('<?php echo $id_count?>')"
                                                                                            class="form-control requiredField ClsAll ShowOn<?php echo $counter?>"
                                                                                            name="warehouse{{$working_counter}}"
                                                                                            id="warehouse{{$working_counter}}">
                                                                                        <option value="">Select</option>
                                                                                        @foreach(CommonHelper::get_all_warehouse()
                                                                                        as $row)
                                                                                            <option value="{{$row->id}}">
                                                                                                {{$row->name}}</option>
                                                                                                <?php ?> @endforeach
                                                                                    </select></td>


                                                                                <td><select
                                                                                            onchange="get_stock_qty(this.id,'{{$id_count}}')"
                                                                                            class="form-control requiredField"
                                                                                            name="batch_code{{$id_count}}"
                                                                                            id="batch_code{{$id_count}}">
                                                                                        <option value="">
                                                                                            Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                        </option>
                                                                                        f
                                                                                    </select></td>
                                                                                <td><input readonly
                                                                                           class="form-control instock {{$bundle_data->item_id}}"
                                                                                           type="text" value=""
                                                                                           id="instock{{$id_count}}" />
                                                                                </td>


                                                                                <td class="text-right hidee">
                                                                                    <input readonly class="form-control"
                                                                                           type="text"
                                                                                           name="send_rate{{$id_count}}"
                                                                                           id="send_rate{{$id_count}}"
                                                                                           value="{{$bundle_data->rate}}" />

                                                                                </td>
                                                                                    <?php if ($bundle_data->discount=='') ?>

                                                                                <td class="text-right hidee">
                                                                                    <input readonly class="form-control"
                                                                                           type="text"
                                                                                           name="send_discount{{$id_count}}"
                                                                                           id="send_discount{{$id_count}}"
                                                                                           value="{{$bundle_data->discount}}" />

                                                                                </td>


                                                                                <td class="text-right hidee">

                                                                                    <input readonly class="form-control"
                                                                                           type="text"
                                                                                           name="send_discount_amount{{$id_count}}"
                                                                                           id="send_discount_amount{{$id_count}}"
                                                                                           value="{{$bundle_data->discount_amount}}" />
                                                                                </td>
                                                                                <td class="text-right hidee">
                                                                                    <input readonly
                                                                                           class="form-control amount comma_seprated"
                                                                                           type="text"
                                                                                           name="send_amount{{$id_count}}"
                                                                                           id="send_amount{{$id_count}}"
                                                                                           value="{{$bundle_data->amount}}" />
                                                                                </td>
                                                                                <td><input type="checkbox" class=""
                                                                                           id="check{{$id_count}}"
                                                                                           onclick="required_none('{{$id_count}}','{{$qty}}')">
                                                                                </td>
                                                                            </tr>
                                                                            <?php endif;   $item_count+=0.1; endforeach ;$bundle_stop=1; ?>




                                                                            <?php endif;




                                                                                $total+=$row1->amount;
                                                                                $counter++;


                                                                            }
                                                                            ?>
                                                                            </tbody>








                                                                            <input type="hidden" id="count" name="count"
                                                                                   value="{{$id_count}}" />
                                                                            <!-- <?php  echo 'amir'.$id_count;  ?> -->
                                                                            <tr>

                                                                                <td id="total_"
                                                                                    style="background-color: darkgray"
                                                                                    class="text-center" colspan="3">Total
                                                                                </td>
                                                                                <td style="background-color: darkgray"
                                                                                    class="text-right" colspan="1">
                                                                                    <input style="font-weight: bolder"
                                                                                           class="form-control" readonly
                                                                                           type="text" id="total_qty"
                                                                                           value="{{$total_qty,3}}" />
                                                                                </td>
                                                                                <td style="background-color: darkgray"
                                                                                    class="text-right hidee nett"
                                                                                    colspan="7"><input
                                                                                            style="font-weight: bolder"
                                                                                            class="form-control text-right hide comma_seprated"
                                                                                            readonly type="text"
                                                                                            id="total_amount"
                                                                                            value="{{$total}}" /></td>


                                                                            </tr>



                                                                            @if($sales_order->sales_tax >0)
                                                                                    <?php  $total+=$sales_order->sales_tax; ?>
                                                                                <tr class="hidee">
                                                                                    <td class="text-center" colspan="9"></td>
                                                                                    <td class="text-right" colspan="1"><b>(Sales
                                                                                            Tax 17%)</td>
                                                                                    <td colspan="1"> <input
                                                                                                style="font-weight: bolder"
                                                                                                class="form-control text-right comma_seprated"
                                                                                                readonly type="text"
                                                                                                name="sales_tax_apply"
                                                                                                id="sales_tax"
                                                                                                value="{{$sales_order->sales_tax}}" />
                                                                                    </td>
                                                                                </tr>
                                                                            @endif


                                                                            @if($sales_order->sales_tax_further >0)
                                                                                    <?php $total+=$sales_order->sales_tax_further; ?>
                                                                                <tr class="hidee">
                                                                                    <td class="text-center" colspan="9"></td>
                                                                                    <td class="text-right" colspan="2">
                                                                                        <b>(Sales Tax Further 3%)</b>
                                                                                        {{   number_format($sales_order->sales_tax_further,2)}}
                                                                                    </td>
                                                                                </tr>
                                                                            @endif

                                                                            <tr class="hide">

                                                                                <td class="text-center" colspan="9"></td>
                                                                                <td class="text-right" colspan="1"><b>(Grand
                                                                                        Total)</td>
                                                                                <td colspan="1"> <input
                                                                                            style="font-weight: bolder"
                                                                                            class="form-control text-right comma_seprated"
                                                                                            readonly type="text" name="grand"
                                                                                            id="grand"
                                                                                            value="{{$sales_order->sales_tax+$total}}" />
                                                                                </td>


                                                                            </tr>

                                                                        </table>

                                                                        <?php $count=1;
                                                                        ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" id="id_count" value="{{$id_count}}" />
                                                            <table>
                                                                <tr>
                                                                    <td style="text-transform: capitalize;" id="rupees">
                                                                    </td>
                                                                    <input type="hidden" value="" name="rupeess"
                                                                           id="rupeess1" />
                                                                </tr>
                                                            </table>
                                                            <input type="hidden" id="d_t_amount_1">
                                                            <!--
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                    <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('1')" value="Add More Demand's Rows" />
                                                                    <input type="button" onclick="removeDemandsRows()" class="btn btn-sm btn-danger" name="Remove" value="Remove">

                                                                </div>
                                                            <!-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="demandsSection"></div>

                                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <div class="col-md-10">
                                                <h2 class="subHeadingLabelClass">Sub Total</h2>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="padt">
                                                    <ul class="sale-l sale-l2">
                                                                    <li>Total Product</li>
                                                                    <li class="text-left">
                                                                        <input name="total-product" class="form-control form-control2"  value="2" type="text">
                                                                    </li>
                                                                </ul>
                                                    <ul class="sale-l sale-l2">
                                                        <li>Total Qty</li>
                                                        <li class="text-left">
                                                            <input name="total_qty" class="form-control form-control2"
                                                                id="total_qty" value="{{$sales_order->total_qty}}" type="text">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                            <div class="padt">
                                                                <ul class="sale-l sale-l2">
                                                                    <li>Total FOC</li>
                                                                    <li class="text-left">
                                                                        <input name="total-fac" class="form-control form-control2"  value="3" type="text">
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>  

                                            <div class="col-md-3">
                                                <div class="padt">
                                                    <ul class="sale-l sale-l2">
                                                        <li>Gross Amount</li>
                                                        <li class="text-left"><input name="total_gross_amount"
                                                                id="total_gross_amount"
                                                                class="form-control form-control2" value="" type="text">
                                                        </li>
                                                    </ul>
                                                    <ul class="sale-l sale-l2">
                                                                    <li>Total Qty</li>
                                                                    <li class="text-left"><input name="total-qty" class="form-control form-control2"  value="4,181" type="text"></li>
                                                                </ul>
                                                    <ul class="sale-l sale-l2">
                                                                    <li>Disc</li>
                                                                    <li class="text-left"><input name="disc" class="form-control form-control2"  value="0" type="text"></li>
                                                                </ul>
                                                    <ul class="sale-l sale-l2">
                                                                    <li>Disc 2</li>
                                                                    <li class="text-left"><input name="disc2" class="form-control form-control2"  value="0" type="text"></li>
                                                                </ul>
                                                    <ul class="sale-l sale-l2">
                                                        <li>Tax Amount</li>
                                                        <li class="text-left"><input name="total_sales_tax"
                                                                id="total_sales_tax" class="form-control form-control2"
                                                                value="" type="text"></li>
                                                    </ul>
                                                    <ul class="sale-l sale-l2">
                                                        <li>Net Amount</li>
                                                        <li class="text-left"><input name="total_amount_after_sale_tax"
                                                                id="total_amount_after_sale_tax"
                                                                class="form-control form-control2" value="" type="text">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div> -->

                                            <!-- <div class="col-md-12 padtb">

                                                        <div class="col-md-10"></div>
                                                        <div class="col-md-2 my-lab">
                                                            <label for="">
                                                            Total Amount
                                                            </label>
                                                            <input type="text" readonly value="" name="grand_total" id="grand_total" class="form-control">
                                                            <label for="">
                                                                Total Tax
                                                            </label>
                                                            <input type="text" readonly value="" name="total_tax" id="total_tax" class="form-control">

                                                            <label for="">
                                                            Total Amount With Tax
                                                            </label>
                                                            <input type="text" readonly value="" name="grand_total_with_tax" id="grand_total_with_tax" class="form-control">

                                                        </div>
                                                    </div> -->

                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3 my-lab" id="subm">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-primary mr-1']) }}
                                                    <a type="button" href="{{url('selling/listSaleOrder')}}"
                                                       class="btnn btn-secondary" data-dismiss="modal">Clear
                                                        Form</a>
                                                </div>
                                            </div>
                                            <!-- <div class="row">
                                                <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">



                                                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                                                        <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                                                                        <!
                                                </div>
                                            </div> -->
                                            <?php echo Form::close();?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function ApplyAll(number) {
            // console.log(number);
            var count = $('#id_count').val();

            // if (number==1)
            // {
            //     for (i=1; i<=count; i++)
            //     {

            //             var selectedVal = $('#warehouse'+number).val();
            //             $('.ClsAll').val(selectedVal);
            //             get_stock('warehouse'+i,i);


            //     }
            // }

            //            if(Cnt == 1){
            //                var selectedVal = $('#warehouse'+Cnt).val();
            //                $('.ClsAll').val(selectedVal);
            //            }
        }
        $(document).ready(function() {

            get_ntn();
            $('.comma_seprated').number(true, 3);
            //    $('.hidee').fadeIn();
            var cal_count = $('#count').val();
            for (i = 1; i <= cal_count; i++) {

                calc(i);
            }




            $(".btn-success").click(function(e) {

                var demands = new Array();
                var val;
                //	$("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());

                //});
                var _token = $("input[name='_token']").val();

                for (val of demands) {

                    jqueryValidationCustom();
                    if (validate == 0) {


                    } else {
                        return false;
                    }
                }

            });






        });


        function show() {


        }

        $('#amount_data').change(function() {

            if ($(this).is(':checked')) {
                $('.hidee').fadeIn(1000);
                $('.resize').attr("rows", "5");
                $('.resize').attr("cols", "20");
                $('#total_').attr('colspan', 3);
                $('.nett').attr('colspan', 6);
            } else {


                $('.hidee').fadeOut();
                $('#total_').attr('colspan', 3);
                $('.resize').attr("cols", "50");
            }

        });




        function sales_tax() {
            var total = parseFloat($('#total_amount').val());
            var sales_tax = (total / 100) * 17;
            $('#sales_tax').val(sales_tax);


            var strn = $('#buyers_sales').val();
            if (strn == '') {

                var sales_tax_further = (total / 100) * 3;
                $('#sales_tax_further').val(sales_tax_further);

            } else {
                sales_tax_further = 0;
                $('#sales_tax_further').val(0);
            }

            var total = sales_tax + sales_tax_further;
            $('#sales_total').val(total);

            var total_amount = parseFloat($('#total').val());
            var total_after_sales_tax = total_amount + total;
            $('#total_after_sales_tax').val(total_after_sales_tax);

            $('#d_t_amount_1').val(total_after_sales_tax);


            var sales_tax = parseFloat($('#sales_tax').val());
            var amount = parseFloat($('#total_amount').val());
            if (isNaN(sales_tax)) {
                sales_tax = 0;
            }
            $('#grand').val(sales_tax + amount);
            toWords(1);
        }

        $("form").submit(function(e) {









            $("#subm").css("display", "none");
            var validation = 1;
            var id_count = 1;
            var val = 0;
            $('.instock').each(function(i, obj) {
                var val = parseFloat($('#' + obj.id).val());

                var number = obj.id;

                var cls = obj.className;

                cls = cls.split(' ');
                cls = cls[2];
                cls = 'item' + cls;

                var qty = 0;
                $('.' + cls).each(function(i, obj) {
                    qty += +parseFloat($('#' + obj.id).val());
                });




                number = number.replace("instock", "");
                var qty = parseFloat($('#send_qty' + number).val());


                var total = val - qty;


                if (total < 0 || isNaN(total)) {

                    //   alert('something went wrong');
                    if ($("#check" + id_count).prop('checked') == false) {
                        $('#' + obj.id).css("background-color", "red");

                        validation = 0;
                    }


                } else {
                    $('#' + obj.id).css("background-color", "");
                }

                id_count++;
            });

            if (validation == 0)

            {
                alert('something went wrong');
                $("#subm").css("display", "block");
                e.preventDefault();

            } else {
                $("#subm").css("display", "none");
                $('form').submit();



            }

        });
    </script>


    <script>
        function get_stock(warehouse, number) {

            var warehouse = $('#' + warehouse).val();
            var item = $('#item_id' + number).val();
            var batch_code = '';

            // console.log(warehouse,item,batch_code);

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise',
                type: "GET",
                data: {
                    warehouse: warehouse,
                    item: item,
                    batch_code: batch_code
                },
                success: function(data) {

                    data = data.split('/');
                    let hold = data[2]
                    // console.log(hold);
                    let remainingQty = data[0] - hold;

                    $('#instock' + number).val(remainingQty);
                    //     $('#rate'+number).val(data[1]);
                    //     var amount=data[0]*data[1];
                    //     $('#net_amount'+number).val(amount);
                    if (remainingQty == 0) {
                        $("#" + item).css("background-color", "red");
                        $("#subm").css("display", "none");
                    } else {
                        $("#" + item).css("background-color", "");
                    }
                    $('#batch_code' + number).html(data);
                }
            });

        }


        function get_stock_qty(warehouse, number) {
            var warehouse = $('#warehouse' + number).val();
            var item = $('#item_id' + number).val();
            var batch_code = $('#batch_code' + number).val();


            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise',
                type: "GET",
                data: {
                    warehouse: warehouse,
                    item: item,
                    batch_code: batch_code
                },
                success: function(data) {
                    //   $('#batch_code'+number).html(data);

                    data = data.split('/');
                    let hold = data[2]
                    console.log(hold);

                    $('#instock' + number).val(data[0] - hold);
                    //     $('#rate'+number).val(data[1]);
                    //     var amount=data[0]*data[1];
                    //     $('#net_amount'+number).val(amount);
                    if (data[0] == 0) {
                        $("#" + item).css("background-color", "red");
                    } else {
                        $("#" + item).css("background-color", "");
                    }

                }
            });

        }
    </script>

    <script>
        function get_ntn() {
            var ntn = $('#ntn').val();
            ntn = ntn.split('*');
            $('#buyers_ntn').val(ntn[1]);
            $('#buyers_sales').val(ntn[2]);
            sales_tax();
        }


        function send_qty_get(num) {
            var item_id = $('#item_id' + num).val();
            var send_qty = 0
            $('.item' + item_id).each(function(i, obj) {
                send_qty += +parseFloat($(this).val());
            });

            return send_qty;
        }
        function calc(num) {
            let send_qty = parseFloat($('#send_qty' + num).val()) || 0;
            let total_amount = parseFloat($('#send_amount_new' + num).val()) || 0;
            var qty = parseFloat($('#qty' + num).val());
            var actual_qty = parseFloat($('#qtyMain' + num).val());
        
            if (send_qty > qty) {
                alert('amount can not greater than sales order QTY');
                $('#send_qty' + num).val(qty);
                return false;
            }
            // Calculate unit price from original total and original qty
            let unit_price = total_amount / actual_qty;
        
            // New total based on changed qty
            let new_total = unit_price * send_qty;
        
            $('#send_amount' + num).val(new_total.toFixed(2));
        }
        // Original One
        // function calc(num) {
        //     var send_qty = parseFloat($('#send_qty' + num).val());
        //     var foc_qty = parseFloat($('#foc_qty' + num).val());
        //     var actual_foc_qty = parseFloat($('#foc' + num).val());
        //     var actual_qty = parseFloat($('#qty' + num).val());

        //     // console.log(foc_qty,actual_foc_qty);
        //     if (foc_qty > actual_foc_qty) {
        //         alert('FOC can not be greater than Sales order FOC Qty');
        //         $('#foc_qty' + num).val(actual_foc_qty);
        //         net();
        //         return false;
        //     }

        //     if (send_qty > actual_qty) {
        //         alert('amount can not greater than sales order QTY');
        //         $('#send_qty' + num).val(actual_qty);
        //         net();
        //         return false;
        //     }

        //     var rate = parseFloat($('#send_rate' + num).val());
        //     var total = (send_qty - foc_qty) * rate;



        //     // discount
        //     var x = parseFloat($('#send_discount' + num).val());
        //     if (isNaN(x)) {
        //         x = 0;
        //     }


        //     if (x > 0) {

        //         x = x * total;

        //         var discount_amount = parseFloat(x / 100);
        //         //    console.log(typeof discount_amount);
        //         // $('#send_discount_amount' + num).val(discount_amount.toFixed(2));
        //         total = total + discount_amount;

        //     }


        //     // discount end

        //     $('#send_amount' + num).val(total);


        //     net();
        //     //   sales_tax();


        // }

        function totalGrossAmount() {
            var totalGrossAmount = 0;
            $('.gross_amount').each(function() {
                var grossAmount = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
                totalGrossAmount += grossAmount;
            });
            $('#total_gross_amount').val(totalGrossAmount);
        }

        function totalTaxAmount() {
            var totalTaxAmount = 0;
            $('.total_tax').each(function() {
                var taxAmount = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
                totalTaxAmount += taxAmount;
            });
            $('#total_sales_tax').val(totalTaxAmount);
        }

        function totalAmount() {
            var totalAmount = 0;
            $('.total').each(function() {
                var amount = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
                totalAmount += amount;
            });
            $('#total_amount_after_sale_tax').val(totalAmount);
        }


        function net() {

            var amount = 0;
            $('.amount').each(function(i, obj) {

                amount += +parseFloat($('#' + obj.id).val());



            });
            amount = parseFloat(amount);
            $('#total_amount').val(amount);
            $('#grand').val(amount);



            var qty = 0;
            var foc = 0;
            $('.qty').each(function(i, obj) {

                qty += +parseFloat($('#' + obj.id).val());

            });
            qty = parseFloat(qty);
            $('.foc').each(function(i, obj) {

                foc += +parseFloat($('#' + obj.id).val());

            });
            var totalQty = qty - foc;
            $('#total_qty').val(totalQty);

        }

        function totalQty() {
            var totalQty = 0;
            $('.qty').each(function() {
                var qty = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
                totalQty += qty;
            });
            $('#total_qty').val(totalQty);
        }


        function required_none(number, qry) {
            if ($("#check" + number).prop('checked') == true) {
                $("#batch_code" + number).removeClass("requiredField");
                $('#send_qty' + number).attr('readonly', true);
                $('#send_qty' + number).val(0);
                calc(number);
                //     sales_tax();
                net();
            } else {
                $("#batch_code" + number).addClass("requiredField");
                $('#send_qty' + number).attr('readonly', false);
                $('#send_qty' + number).val(qry);
                calc(number);
                //    sales_tax();
                net();

            }
        }


        // $(document).ready(function() {
        //     // Function to capitalize and rephrase field names
        //     function formatFieldName(name) {
        //         return name.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
        //     }
        //
        //     // Validation on forms with class .underfieldvalidation
        //     $('.underfieldvalidation').on('submit', function(event) {
        //         event.preventDefault();
        //
        //         let isValid = true;
        //         let missingFields = [];
        //
        //         // Check each required field
        //         $(this).find('select[required], input[required], textarea[required], input[type="checkbox"][required], input[type="radio"][required]').each(function() {
        //             // If the field is not filled out
        //             if (!$(this).val() || (($(this).attr('type') === 'checkbox' || $(this).attr('type') === 'radio') && !$(this).is(':checked'))) {
        //                 isValid = false;
        //
        //                 // Get the field's data-message or name attribute
        //                 let fieldName = $(this).attr('data-message') || $(this).attr('name');
        //                 if (fieldName) {
        //                     fieldName = formatFieldName(fieldName);
        //                     missingFields.push(fieldName);
        //                 }
        //             }
        //         });
        //
        //         if (!isValid) {
        //             // Show SweetAlert error if there are missing fields
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Some fields are missing',
        //                 text: 'Please fill in the following fields: ' + missingFields.join(', '),
        //                 confirmButtonColor: '#3085d6'
        //             });
        //         } else {
        //             // If valid, submit the form (or you can handle it accordingly)
        //             this.submit();
        //         }
        //     });
        // });

    </script>




    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection