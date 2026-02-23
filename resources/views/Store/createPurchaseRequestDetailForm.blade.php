<?php


use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\NotificationHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$getAllInput = $request->all();
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
                        <span class="subHeadingLabelClass">Create Purchase Order Form</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>


                <?php echo Form::open(array('url' => 'stad/addPurchaseRequestDetail?m='.$m.'','id'=>'addPurchaseRequestDetail'));?>
                <?php


                $purchaseRequestNo=CommonHelper::get_unique_po_no(1);

                $counter = 1;
                
                CommonHelper::companyDatabaseConnection($m);


               
                // $seletedDemandVoucherListandCreatePurchaseRequest = DB::table('demand_data')
                //                 ->select('demand_data.demand_no','demand_data.demand_date','demand_data.sub_item_id',
                //                 'demand_data.sub_ic_desc','demand_data.qty','demand_data.id','demand.slip_no','demand_data.sub_ic_desc','d.rate','c.vendor_id')
                //                 ->join('demand','demand_data.demand_no','=','demand.demand_no')
                //                  ->LeftJoin('quotation as c','c.pr_id','=','demand.id')
                //                  ->LeftJoin('quotation_data as d','d.pr_data_id','=','demand_data.id')
                //                  ->where('d.quotation_status',2)
                //                  ->where(['demand.status' => '1','demand.demand_status' => '2'])
                //                     ->whereIn('demand_data.id',array($demand_data_id))
                //                   ->groupBy('demand_data.id')
                //                  ->get();

                 $data = DB::Connection('mysql2')->table('demand_data as a')
                 ->join('demand as b','a.master_id','=','b.id')
                 ->join('quotation_data as c','c.pr_data_id','=','a.id')
                 ->join('quotation as d','d.id','=','c.master_id')
                 ->whereIn('a.id',$demand_data_id)
                 ->where('c.quotation_status',1)
                 ->where('b.status',1)->where('d.quotation_status',2)

                 ->select('a.sub_item_id','d.comparative_number','a.id','c.vendor','a.demand_no'
                 ,'a.demand_date','a.qty','c.rate','c.tax_percent','c.amount','c.tax_per_item_amount','c.tax_total_amount','b.sub_department_id','d.gst','b.p_type','d.vendor_id')
                ->groupBy('a.id')->get();
                 $datas = DB::Connection('mysql2')->table('demand_data as a')
                 ->join('demand as b','a.master_id','=','b.id')
                 ->join('quotation_data as c','c.pr_data_id','=','a.id')
                 ->join('quotation as d','d.id','=','c.master_id')
                 ->whereIn('a.id',$demand_data_id)
                 ->where('c.quotation_status',1)
                 ->where('b.status',1)->where('d.quotation_status',2)

                 ->select('a.sub_item_id','d.comparative_number','a.id','c.vendor','a.demand_no'
                 ,'a.demand_date','a.qty','c.rate','c.tax_percent','c.amount','c.tax_per_item_amount','c.tax_total_amount','b.sub_department_id','d.gst','b.p_type','d.vendor_id')
                ->groupBy('a.id');
$comparativeNumbers = $datas->groupby('comparative_number')->pluck('comparative_number')->toArray();

                 
               
         
                 $quotations = DB::Connection('mysql2')->table('quotation_data')->where('pr_data_id',$data[0]->id)->select('voucher_no')->get();
                 
                 $voucherNos = $quotations->pluck('voucher_no')->toArray();

                 $implodedVoucherNos = implode(',', $voucherNos);



//                $quotation = DB::connection('mysql2')
//     ->table('quotation_data as qd')
//     ->join('quotation as q', 'qd.master_id', '=', 'q.id')
//     ->where('qd.pr_data_id', $data[0]->id)
//     ->select('q.comparative_number')
//     ->first();

//     // dd($quotation);

// $comparative = $quotation ? $quotation->comparative_number : null;


// $implodedComparatives = $comparative;



    // dd($quotations);
// Convert to array of comparative numbers
// $comparativeNumbers = $data->plu;

// Optional: implode into comma separated string
$implodedComparatives = implode(', ', $comparativeNumbers);

// Now you can use $comparativeNumbers array to show individually
// foreach ($comparativeNumbers as $num) {
//     echo $num . '<br>'; // alag-alag show
// }



            
                $vendor_id= $data[0]->vendor;        
                $dept_id= $data[0]->sub_department_id;  
                $p_type= $data[0]->p_type;
                $sales_tax= $data[0]->gst;    
             // $vendor_id=$seletedDemandVoucherListandCreatePurchaseRequest[0]->vendor_id;
                CommonHelper::reconnectMasterDatabase();

                ?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">PO NO.</label>
                                        <input readonly type="text" class="form-control requiredField" placeholder=""
                                            name="po_no" id="po_no" value="{{strtoupper($purchaseRequestNo)}}" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">PO DATE.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField"
                                            max="<?php echo date('Y-m-d') ?>" name="po_date" id="po_date"
                                            value="<?php echo date('Y-m-d') ?>" />
                                    </div>

                                    <input type="hidden" name="dept_id" value="{{ $dept_id }}" />
                                    <input type="hidden" name="vendor_id" class="vendor_id" value="{{ $data[0]->vendor_id}}" />

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                        <label class="sf-label">Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="sub_department_name" id="sub_department_name"
                                            class="form-control" readonly
                                            value="{{ CommonHelper::get_sub_dept_name($dept_id) }}">
                                    </div>


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none">
                                        <label class="sf-label">Supplier Reference No.</label>
                                        <input autofocus type="text" class="form-control" placeholder="Ref No"
                                            name="slip_no" id="slip_no" value="-" />
                                    </div>
                                    <!--
                                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                                        <label class="sf-label">Supplier Order No</label>
                                                                                        <input type="text" class="form-control" placeholder="Supplier Order No" name="s_order_no" id="s_order_no" value="" />
                                                                                    </div>
                                    <!-->

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Terms Of Delivery</label>
                                        <input type="text" class="form-control" placeholder="Terms Of Delivery"
                                            name="term_of_del" id="term_of_del" value="" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">PO Type</label>
                                        <select onchange="get_po(this.id); po_type_change(this);" name="po_type"
                                            id="po_type" class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach(CommonHelper::get_local_to_types() as $item)
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
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                        <label class="sf-label">Quotations</label>
                                        <input readonly style="text-transform: capitalize;" type="text" class="form-control"
                                            placeholder="" name="quotation" id="quotation" value="{{$implodedVoucherNos}}" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Comparative number</label>
                                        <input readonly style="text-transform: capitalize;" type="text" class="form-control"
                                             value="{{$implodedComparatives}}" />
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
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select onkeypress="claculation(1);get_rate()" name="curren" id="curren"
                                            class="form-control select2 requiredField">
                                            <!-- <option value="0,1"> PKR</option> -->

                                            <option value="">Select Currency</option>
                                            <!-- @foreach(CommonHelper::get_all_currency() as $row)
                                            <option value="{{$row->id.','.$row->rate}}">{{$row->name}}</option>
                                            @endforeach; -->

                                        </select>

                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label"> Currency Rate</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input class="form-control requiredField" value="1" type="text"
                                            name="currency_rate" id="currency_rate" />

                                    </div>

                                    <input type="hidden" name="curren_rate" id="curren_rate" value="1" />

                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Mode/ Terms Of Payment <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        <input onkeyup="calculate_due_date()" type="number"
                                            class="form-control requiredField" placeholder=""
                                            name="model_terms_of_payment" id="model_terms_of_payment" value="" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Due Date <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        <input type="date" class="form-control requiredField" placeholder=""
                                            name="due_date" id="due_date" value="" readonly />
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Purchase Type <span
                                                class="rflabelsteric"><strong>*</strong></span></label>
                                        <input readonly type="text" class="form-control requiredField" placeholder=""
                                            value="{{ NotificationHelper::get_type_name( $p_type) }}" name="p_type"
                                            id="p_type" readonly />
                                    </div>

                                    <input type="hidden" value="{{ $p_type }}" name="p_type_id" />
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
                                            placeholder="STRN">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Builty No</label>
                                        <input type="text" name="builty_no" id="builty_no" class="form-control"
                                            placeholder="Builty No">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control"
                                            placeholder="Terms & Condition"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Terms & Condition</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="main_description" id="main_description" rows="4" cols="50"
                                            style="resize:none;font-size: 11px;" class="form-control requiredField">YOUR NTN NUMBER AND VALID INCOME TAX EXEMPTION WILL BE REQUIRED FOR PAYMENT, OTHER WISE INCOME TAX WILL BE DEDUCTED AS PER FOLLOWINGS:
INCOME TAX:
FOR COMPANIES SUPPLIES 4% & SERVICES 8% (FILER) / 12% (NON FILER)
FOR INDIVIUALS OR AOP SUPPLIES 4.5% & SERVICES 10% (FILER) / 15% (NON FILER)
SALES TAX ON SUPPLIES:
A WITHOLDING AGENT SHALL DEDUCT AN AMOUNT AS PER SRO 897 /2013
SALES TAX ON SERVICES:
A WITHOLDING AGENT SHALL DEDUCT AN AMOUNT AS PER SRB WITHHOLDING RULES-2014</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <?php
                                    $counter1 = 1;
                                    $all_total=0;

                                    foreach ($data as $row){ ?>

                                    <table class="table table-bordered sf-table-list">
                                        <thead>
                                            <th class="text-center">Sr No</th>
                                            <th class="text-center">Pr No</th>
                                            <th class="text-center">Pr Date</th>
                                            <th class="text-center" colspan="1">Product Name</th>
                                            <th class="text-center">Product Type</th>
                                            <th class="text-center">Product Barcode</th>
                                            <th class="text-center">Product Classification</th>
                                            <th class="text-center">Product Trend</th>
                                            <th class="text-center">UOM</th>
                                            <th class="text-center">Req. Qty</th>
                                            <th class="text-center">Remarks</th>

                                        </thead>
                                        <tbody id="filterDemandVoucherList">
                                            <tr id="removeSelectedPurchaseRequestRow_<?php echo $counter1;?>"
                                                class="text-center">
                                                <input type="hidden" name="seletedPurchaseRequestRow[]" readonly
                                                    id="seletedPurchaseRequestRow" value="<?php echo $counter1;?>"
                                                    class="form-control" />
                                                <input type="hidden" name="demandNo_<?php echo $counter1;?>" readonly
                                                    id="demandNo_<?php echo $counter1;?>"
                                                    value="<?php echo $row->demand_no;?>" class="form-control" />
                                                <input type="hidden" name="demandDate_<?php echo $counter1;?>" readonly
                                                    id="demandDate_<?php echo $counter1;?>"
                                                    value="<?php echo $row->demand_date;?>" class="form-control" />
                                                <input type="hidden" name="demandType_<?php echo $counter1;?>" readonly
                                                    id="demandType_<?php echo $counter1;?>" value="<?php ?>"
                                                    class="form-control" />
                                                <input type="hidden" name="demandSendType_<?php echo $counter1;?>"
                                                    readonly id="demandSendType_<?php echo $counter1;?>"
                                                    value="<?php ?>" class="form-control" />
                                                <input type="hidden" name="demand_data_id<?php echo $counter1;?>"
                                                    id="demand_data_id<?php echo $counter1;?>" value="{{$row->id}}">

                                                <?php   $sub_ic_detail=CommonHelper::get_subitem_detail($row->sub_item_id);

                                            $sub_ic_detail= explode(',',$sub_ic_detail);
                                                $rate=$sub_ic_detail[2];
                                                if ($rate==''):
                                                $rate=0;
                                                endif;
                                            ?>

                                                <td rowspan="2" style="padding: 55px 0px 0px 0px;">
                                                    <?php echo $counter1?></td>
                                                <td><?php echo strtoupper($row->demand_no)?></td>
                                                <td><?php echo CommonHelper::changeDateFormat($row->demand_date)?></td>
                                                <td colspan="1">
                                                    <?php $sub_item_id = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$row->sub_item_id);?>
                                                    <?php $product_name = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_name',$row->sub_item_id);?>

                                                    <a href="<?php echo url('/') ?>/store/item_detaild_supplier_wise?&sub_item_id=<?php echo $row->sub_item_id ?>"
                                                        target="_blank">{{ ($product_name != '' ? $product_name  : $sub_item_id)}}</a>

                                                    <input type="hidden" name="subItemId_<?php echo $counter1;?>"
                                                        readonly id="subItemId_<?php echo $counter1;?>"
                                                        value="<?php echo $row->sub_item_id;?>" class="form-control" />
                                                    <input type="hidden" name="mainIcId_<?php echo $counter1;?>"
                                                        readonly id="mainIcId_<?php echo $counter1;?>"
                                                        value="<?php echo $sub_ic_detail[5];?>"
                                                        class="form-control mainIcId" />
                                                </td>

                                                <td> <?php 
                                                    $product_type = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_type_id',$row->sub_item_id);
                                                   $product_type_name = CommonHelper::get_product_type_by_id($product_type);
                                                echo $product_type_name?></td>

                                                <td> <?php 
                                                    $product_barcode = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_barcode',$row->sub_item_id);
                                                
                                                echo $product_barcode?></td>

                                                <td> <?php 
                                                    $product_classification = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_classification_id',$row->sub_item_id);
                                                   $product_classification_name = CommonHelper::get_classification_by_id($product_classification);
                                                echo $product_classification_name?></td>

                                                <td> <?php 
                                                    $product_trend = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','product_trend_id',$row->sub_item_id);
                                                   $product_trend_name = CommonHelper::get_product_trend_by_id($product_trend);
                                                echo $product_trend_name?></td>

                                                <td> <?php echo CommonHelper::get_uom_name($sub_ic_detail[0]);?></td>
                                                <td class="text-center">

                                                    {{$row->qty}}
                                                    <input type="hidden"
                                                        name="purchase_request_qty_<?php echo $counter1 ?>"
                                                        value="{{$row->qty}}"
                                                        id="purchase_request_qty_<?php echo $counter1 ?>" />
                                                </td>

                                                <td><textarea class="form-control"
                                                        name="description_<?php echo $counter1;?>"></textarea></td>
                                            </tr>

                                            <tr>


                                                <td class="text-center">
                                                    <label for="">Approved. Qty.</label>
                                                    <input onkeyup="claclation('<?php echo  $counter1 ?>')" type="text"
                                                        name="purchase_approve_qty_<?php echo $counter1?>"
                                                        id="purchase_approve_qty_<?php echo $counter1?>"
                                                        class="form-control requiredField approveQty" min="1"
                                                        value="{{$row->qty}}" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Rate.</label>
                                                    <input readonly onkeyup="claculation('<?php echo $counter1 ?>')" type="text"
                                                        name="rate_<?php echo $counter1?>"
                                                        id="rate_<?php echo $counter1?>"
                                                        class="form-control requiredField ApproveRate" step="0.001"
                                                        value="{{($row->rate)}}" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Tax.</label>
                                                    <input readonly onkeyup="claculation('<?php echo $counter1 ?>')" type="text"
                                                        name="rate_<?php echo $counter1?>"
                                                        id="rate_<?php echo $counter1?>"
                                                        class="form-control requiredField ApproveRate" step="0.001"
                                                        value="{{($row->tax_percent)}}" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Rate After Tax.</label>
                                                    <input readonly type="text"
                                                        name="tax_per_item_amount_<?php echo $counter1?>"
                                                        id="tax_per_item_amount_<?php echo $counter1?>"
                                                        class="form-control requiredField ApproveRate" step="0.001"
                                                        value="{{($row->tax_per_item_amount)}}" />
                                                </td>
                                                <!--

<!--><?php $total=$row->amount;?>
                                                <td class="text-center">
                                                    <label for="">Amount</label>
                                                    <input readonly style="text-align: right" type="text"
                                                        name="amount_<?php echo $counter1?>"
                                                        id="amount_<?php echo $counter1?>"
                                                        class="form-control requiredField amount text-right" min="1"
                                                        value="{{$total}}" step="0.01" />
                                                </td>
                                                <!--
                                                                    <td class="text-center"><a onclick="removeSeletedPurchaseRequestRows('<?php echo $row->id?>','<?php echo $counter1?>')" class="btn btn-xs btn-danger">Remove</a></td>

                                                                    <!-->
                                                <td class="text-center">
                                                    <label for="">Discount %</label>
                                                    <input onkeyup="discount_percent(this.id)" value="0"
                                                        class="form-control requiredField" type="text"
                                                        name="discount_percent_<?php echo $counter1?>"
                                                        id="discount_percent_<?php echo $counter1?>" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Discount Amount</label>
                                                    <input onkeyup="discount_amount(this.id)"
                                                        class="form-control requiredField" type="text"
                                                        name="discount_amount_<?php echo $counter1?>"
                                                        id="discount_amount_<?php echo $counter1?>" />
                                                </td>
                                                <td class="text-center">
                                                    <label for="">Net Amount</label>
                                                    <input readonly class="form-control net_amount_dis" type="text"
                                                        value="{{$row->amount}}"
                                                        name="after_dis_amountt_<?php echo $counter1?>"
                                                        id="after_dis_amountt_<?php echo $counter1?>" />
                                                </td>
                                            </tr>


                                            <script>
                                            counter = '  <?php echo $counter1;  ?>';
                                            </script>
                                            <?php
                                        $all_total+=$total;
                                        $counter1++;
                                        }
                                        ?>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                                <table class="table table-bordered sf-table-list">
                                    <thead class="hide">
                                        <th class="text-center" colspan="3">Sales Tax Account Head</th>
                                        <th class="text-center" colspan="3">Sales Tax Amount</th>
                                    </thead>
                                    <tbody>
                                        <tr class="hide">
                                            <td>
                                                <select onchange="sales_tax(this.id);open_sales_tax(this.id);"
                                                    class="form-control select2" id="sales_taxx" name="sales_taxx">
                                                    <option value="0">Select Sales Tax</option>

                                                    @foreach(ReuseableCode::get_all_sales_tax() as $row)

                                                    <option @if ($sales_tax==$row->rate) selected @endif
                                                        value="{{$row->rate}}">{{$row->rate}} %</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-right" colspan="3">
                                                <input onkeyup="tax_by_amount(this.id)" type="text" class="form-control"
                                                    name="sales_amount_td" id="sales_amount_td" />
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
                                <input type="hidden" value="{{0}}" name="rupeess" id="rupeess1" />
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




    // $(document).ready(function() {
    //
    //     for (i = 1; i <= counter; i++) {
    //         $('#amount_' + i).number(true, 2);
    //         //   $('#rate_'+i).number(true,2);
    //         $('#purchase_approve_qty_' + i).number(true, 2);
    //         $('#discount_percent_' + i).number(true, 2);
    //         $('#discount_amount_' + i).number(true, 2);
    //         $('#after_dis_amountt_' + i).number(true, 2);
    //         $('#rate_' + i).number(true, 2);
    //
    //         claculation(i);
    //
    //     }
    //     get_address();
    //     $('#d_t_amount_1').number(true, 2);
    //     $('#sales_amount_td').number(true, 2);
    //
    //     $(".btn-success").click(function(e) {
    //         var purchaseRequest = new Array();
    //         var val;
    //         //$("input[name='demandsSection[]']").each(function(){
    //         purchaseRequest.push($(this).val());
    //         //});
    //         var _token = $("input[name='_token']").val();
    //         for (val of purchaseRequest) {
    //
    //             jqueryValidationCustom();
    //             if (validate == 0) {
    //                 //alert(response);
    //                 vala = 0;
    //                 var flag = false;
    //                 $('.approveQty').each(function() {
    //                     vala = parseFloat($(this).val());
    //                     if (vala == 0) {
    //                         alert('Please Enter Correct Approved Qty....!');
    //                         $(this).css('border-color', 'red');
    //                         flag = true;
    //                         return false;
    //                     } else {
    //                         $(this).css('border-color', '#ccc');
    //                     }
    //                 });
    //
    //                 $('.ApproveRate').each(function() {
    //                     vala = parseFloat($(this).val());
    //                     if (vala == 0) {
    //                         alert('Please Enter Rate....!');
    //                         $(this).css('border-color', 'red');
    //                         flag = true;
    //                         return false;
    //                     } else {
    //                         $(this).css('border-color', '#ccc');
    //                     }
    //                 });
    //                 if (flag == true) {
    //                     return false;
    //                 }
    //             } else {
    //                 return false;
    //             }
    //         }
    //
    //     });
    // });

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
        toWords(1);
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
        discount_percent('discount_percent_' + number);
        toWords(1);
    }


    function sales_taxx(id) {
        var sales_tax_per_value = $('#' + id).val();

        if (sales_tax_per_value != 0) {
            var sales_tax_per = $('#' + id + ' :selected').text();
            sales_tax_per = sales_tax_per.split('(');
            sales_tax_per = sales_tax_per[1];
            sales_tax_per = sales_tax_per.replace('%)', '');

        } else {
            sales_tax_per = 0;
        }

        count = 1;
        var amount = 0;
        $('.net_amount_dis').each(function() {


            amount += +$('#after_dis_amountt_' + count).val();
            count++;
        });


        var x = parseFloat(sales_tax_per * amount);
        var s_tax_amount = parseFloat(x / 100).toFixed(2);

        $('#sales_tax_amount').val(s_tax_amount);
        $('#sales_amount_td').val(s_tax_amount);

        var amount = 0;
        count = 1;
        $('.net_amount_dis').each(function() {


            amount += +$('#after_dis_amountt_' + count).val();
            count++;
        });
        amount = parseFloat(amount);
        s_tax_amount = parseFloat(s_tax_amount);
        var total_amount = (amount + s_tax_amount).toFixed(2);
        $('.td_amount').text(total_amount);
        $('#d_t_amount_1').val(total_amount);
        toWords(1);



    }

    function get_address() {
        var supplier = $('#supplier_id').val();
        supplier = supplier.split('@#');

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

    // function po_type_change(selectedElement) {
    //     // $('#curren').empty();

    // var vendor_id = $('.vendor_id').val();
    //     var selectedValue = selectedElement.value;
    //     if (selectedValue) {
    //         $.ajax({
    //             url: '{{url('/pdc/get_currency_vendor_by_to_type')}}',
    //             type: 'Get',
    //             data: {
    //                 id: selectedValue,
    //                 vendor_id: vendor_id
    //             },
    //             success: function(response) {
    //                 $('#curren').empty().append(response.currencyOptions);
    //                 $('#supplier_id').empty().append(response.vendorOptions);
    //             }
    //         });
    //     }
    // }

    function po_type_change(selectedElement) {
    $vendor_id = $('.vendor_id').val();
    var selectedValue = selectedElement.value;
    if (selectedValue) {
        $.ajax({
            url: '{{url('/pdc/get_currency_vendor_by_to_type')}}',
            type: 'GET',
            data: {
                id: selectedValue,
                vendor_id: $vendor_id,
            },
            success: function(response) {
                // Currency fill
                $('#curren').empty().append(response.currencyOptions);

                // Vendor fill
                $('#supplier_id').empty().append(response.vendorOptions);

                // --- Auto select first Vendor ---
                let firstVendor = $("#supplier_id option:eq(1)");
                if (firstVendor.length) {
                    firstVendor.prop("selected", true);
                    get_address();
                    get_discount();
                }

                // --- Auto select first Currency ---
                let firstCurrency = $("#curren option:eq(1)");
                if (firstCurrency.length) {
                    firstCurrency.prop("selected", true);
                    claculation(1);
                    get_rate();
                }
            }
        });
    }
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
        toWords(1);
        //   net_amount_func();


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
$(document).ready(function () {
    // --- PO Type --- //
    let firstPO = $("#po_type option:eq(1)");
    if (firstPO.length) {
        firstPO.prop("selected", true);
        po_type_change(document.getElementById('po_type'));
        get_po('po_type'); // agar aapko get_po bhi chalana hai
    }

    // --- Vendor --- //
    let firstVendor = $("#supplier_id option:eq(1)");
    if (firstVendor.length) {
        firstVendor.prop("selected", true);
        get_address();
        get_discount();
    }

    // --- Currency --- //
    let firstCurrency = $("#curren option:eq(1)");
    if (firstCurrency.length) {
        firstCurrency.prop("selected", true);
        claculation(1);
        get_rate();
    }
});


    function net_amount() {
        var amount = 0;
        $('.net_amount_dis').each(function() {


            amount += +$(this).val();

        });

        return amount;
    }
    </script>



    <script type="text/javascript">
    $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


    @endsection