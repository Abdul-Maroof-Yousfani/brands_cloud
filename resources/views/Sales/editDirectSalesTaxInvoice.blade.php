<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$m  = Session::get('company_run');
if($accType == 'client'){
    // $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
?>

@extends('layouts.default')

@section('content')
    @include('loader')
    @include('number_formate')
    @include('select2')
    <style>
		*{font-size:12px!important;}
		label{text-transform:capitalize;}
    </style>
    <div class="row well_N" style="display: none;" id="main">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Direct Sales Tax Invoice</span>
                    </div>
                </div>
				<hr style="border-bottom: 1px solid #f1f1">
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
					<form  method="POST" action="{{route('updateDirectSalesTaxInvoice',$sale_tax_invoice->id)}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php // echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php // echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

										<div class="row">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
												<h2 class="subHeadingLabelClass">Sales Tax Invoice</h2>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<div class="row">
													<div class="row">
														<?php

														//$gi_no=$sales_order->so_no;
														$so_date=date('Y-m-d');//$sales_order->so_date;
														//$gi_no=str_replace("SO","GI",$gi_no);
														$gi_no= SalesHelper::get_unique_no_sales_tax_invoice(date('y'),date('m'));
														?>


														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Invoice No<span class="rflabelsteric"><strong>*</strong></span></label>
															<input readonly type="text" class="form-control" placeholder="" name="gi_no" id="gi_no" value="{{strtoupper($sale_tax_invoice->gi_no)}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Invoice Date<span class="rflabelsteric"><strong>*</strong></span></label>
															<input  autofocus type="date" onkeyup="calculate_due_date()" class="form-control requiredField" placeholder="" name="gi_date" id="gi_date" value="{{$sale_tax_invoice->gi_date}}" />
														</div>

													</div>
													<div class="row">

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label  class="sf-label">Mode / Terms Of Payment <span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="text" class="form-control requiredField" placeholder="" name="model_terms_of_payment" id="model_terms_of_payment" value="{{$sale_tax_invoice->model_terms_of_payment}}" />
														</div>
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Other Reference(s) <span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="text" class="form-control requiredField" placeholder="" name="other_refrence" id="other_refrence" value="{{$sale_tax_invoice->other_refrence}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Buyer's Order No<span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="text" class="form-control requiredField" placeholder="" name="order_no" id="order_no" value="{{$sale_tax_invoice->order_no}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Buyer's Order Date<span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="date" class="form-control requiredField" placeholder="" name="order_date" id="order_date" value="{{$sale_tax_invoice->order_date}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Despatched Document No<span class="rflabelsteric"><strong>*</strong></span></label>
															<input   type="text" class="form-control requiredField" placeholder="" name="despacth_document_no" id="despacth_document_no" value="{{$sale_tax_invoice->despacth_document_no}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Despatched Document Date</label>
															<input   type="date" class="form-control" placeholder="" name="despacth_document_date"  id="despacth_document_date" value="{{$sale_tax_invoice->despacth_document_date}}" />
														</div>




													</div>
													<div class="row">

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Despatched through<span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="text" class="form-control requiredField" placeholder="" name="despacth_through" id="despacth_through" value="{{$sale_tax_invoice->despacth_through}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Destination<span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="text" class="form-control requiredField" placeholder="" name="destination" id="destination" value="{{$sale_tax_invoice->destination}}" />
														</div>


														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Terms Of Delivery<span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="text" class="form-control requiredField" placeholder="" name="terms_of_delivery" id="terms_of_delivery" value="{{$sale_tax_invoice->terms_of_delivery}}" />
														</div>

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Buyer's Name <span class="rflabelsteric"><strong>*</strong></span></label>
															<select style="width: 100%"  name="buyers_id" id="ntn" onchange="get_ntn()" class="form-control select2 requiredField">
																<option value="">Select</option>
																@foreach(SalesHelper::get_all_customer() as $row)
																	<option @if($row->id == $sale_tax_invoice->buyers_id) selected  @endif value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn}}">{{$row->name}}</option>
																@endforeach
															</select>
														</div>
													</div>

													<div class="row">
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Buyer's Ntn </label>
															<input  readonly type="text" class="form-control" placeholder="" name="buyers_ntn" id="buyers_ntn" value="" />
														</div>
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Buyer's Sales Tax No </label>
															<input  readonly type="text" class="form-control" placeholder="" name="buyers_sales" id="buyers_sales" value="" />
														</div>
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label">Due Date <span class="rflabelsteric"><strong>*</strong></span></label>
															<input  type="date" class="form-control requiredField" placeholder="" name="due_date" id="due_date" value="{{$sale_tax_invoice->due_date}}" />
														</div>
															<?php
															$accounts=DB::connection('mysql2')
															->table('accounts')
															->where('status', 1)
															->where('parent_code', 'like', '5%')
															->get();
															?>
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
															<label class="sf-label">Cr Account<span class="rflabelsteric requiredField"><strong>*</strong></span></label>
															<select class="form-control" id="acc_id" name="acc_id" >
																<option value="">Select</option>
																@foreach($accounts as $row)
																	<option @if($row->id == $sale_tax_invoice->acc_id) selected @endif  value="{{$row->id}}">{{$row->name}}</option>
																@endforeach
															</select>
														</div>
													</div>

													<div class="row">
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<label class="sf-label"> Currency Rate</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input class="form-control" value="{{$sale_tax_invoice->currency_rate}}" type="text" name="currency_rate" id="currency_rate" />
														</div>
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<!-- <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label> -->
															<label class="sf-label">Currency</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select onchange="" name="curren" id="curren" class="form-control  requiredField">
																@foreach(CommonHelper::get_all_currency() as $row)
																	<option @if($row->id == $sale_tax_invoice->currency) selected @endif value="{{$row->id}}">{{$row->curreny}}</option>
																@endforeach;
															</select>
														</div>
                                        				<input type="hidden" name="demand_type" id="demand_type">
													</div>
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<label class="sf-label">Description</label>
															<span class="rflabelsteric">
															<textarea  name="description" id="description" rows="4" cols="50" style="resize:none;text-transform: capitalize" class="form-control">{{$sale_tax_invoice->description}}</textarea>
														</div>
													</div>
                                    			</div>
											</div>
											<div class="col-md-4">
												<div>
													<h2 class="subHeadingLabelClass">Other Details</h2>
												</div>
												<div class="padt">
													<ul class="sale-l">
														<li>Balance Amount</li>
														<li class="text-right"><input
																name="Balance-Amount"
																class="form-control form-control2"
																value="0.00" type="text" readonly></li>
													</ul>
													<ul class="sale-l">
														<li>Amount Limit</li>
														<li class="text-right"><input
																name="Amount-Limit"
																class="form-control form-control2"
																value="0.00" type="text" readonly></li>
													</ul>
													<ul class="sale-l">
														<li>Current Balance Due</li>
														<li class="text-right"><input
																name="Current-Balance-Due"
																class="form-control form-control2"
																value="0.00" type="text" readonly></li>
													</ul>
													<ul class="sale-l">
														<li>N.T.N No</li>
														<li class="text-right"><input
																name="n-t-n"
																class="form-control form-control2"
																value="65656298" type="text" readonly>
														</li>
													</ul>
													<ul class="sale-l">
														<li>S.T No</li>
														<li class="text-right"
															id="grand_total_top"> <input
																name="s-t-no"
																class="form-control form-control2"
																value="32656568" type="text" readonly>
														</li>
													</ul>
													<ul class="sale-l">
														<li>Payment Terms</li>
														<li class="text-right"
															id="grand_total_top"><input
																name="Payment-Terms"
																class="form-control form-control2"
																value="5% advance 50% on delivery"
																type="text" readonly>
														</li>
													</ul>
												</div>

											</div>
                                	</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							   <h2 class="subHeadingLabelClass">Item Details</h2>
	                        </div>
	                        <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>
	                        <div class="row">
	                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                              <div class="table-responsive" >
	                                 <table class="table table-bordered">
	                                    <thead>
	                                       <tr class="text-center">
	                                          <th colspan="6" class="text-center">Sales Order Detail</th>
	                                          <th colspan="2" class="text-center">
	                                             <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
	                                          </th>
	                                          <th class="text-center">
	                                             <span class="badge badge-success" id="span">1</span>
	                                          </th>
	                                       </tr>
	                                       <tr>
	                                          <th class="text-center" style="width: 20%;">Item</th>
	                                          <th class="text-center" >Uom<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center" > QTY.<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center hide"> WareHouse.<span class="rflabelsteric"><strong>*</strong></span></th>
											  <th style="width: 10%" class="text-center hide" >Batch Code <span class="rflabelsteric"><strong>*</strong></span></th>
											  <th class="text-center hide" >In Stock<span class="rflabelsteric"><strong>*</strong></span></th>
											  <th class="text-center">Rate<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center">Amount<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center">Sales Tax %<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center">Tax Amount<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center">Net Amount<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center">Delete<span class="rflabelsteric"><strong>*</strong></span></th>
	                                       </tr>
	                                    </thead>
	                                    <tbody id="AppnedHtml">
											@foreach($sale_tax_invoice_data as $key=>$sale_data)
	                                    <tr class="cnt" title="1">
											<td>
												<?php $uom_name =  CommonHelper::get_uom($sale_data->item_id); ?>
												<select onchange="get_uom('{{$key }}')" name="sub_ic_des[]" id="sub_ic_des{{$key }}" class="form-control select2">
													<option value="">Select</option>
														@foreach ( CommonHelper::get_all_subitem_non_stock() as $row )
														<?php $uom =  CommonHelper::get_uom($row->id); ?>
														<option @if($row->id ==  $sale_data->item_id)selected @endif value="{{ $row->id.','.$uom }}" >{{ $row->sub_ic }}</option>
														@endforeach
												</select>
											</td>
	                                	<td>
	                                          <input readonly type="text" class="form-control" value="{{$uom_name}}" name="uom_id[]" id="uom_id{{$key}}" >
	                                    </td>
	                                    <td>
	                                         <input type="text" onkeyup="claculation({{$key}})" onblur="claculation({{$key}})" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty{{$key}}"  min="1" value="{{($sale_data->qty)}}">
	                                    </td>
										<td class="hide">
											<select onchange="get_stock(this.id,{{$key}});ApplyAll({{$key}})" class="form-control  ClsAll ShowOn{{$key}}" name="warehouse[]" id="warehouse{{$key}}">
												<option value="">Select</option>
													@foreach(CommonHelper::get_all_warehouse() as $row)
													<option @if($row->id == $sale_data->warehouse_id) selected @endif value="{{$row->id}}">{{$row->name}}</option>
													@endforeach
											</select>
										</td>
										<td class="hide">
											<select onchange="get_stock_qty(this.id,{{$key}})" class="form-control " name="batch_code[]" id="batch_code{{$key}}">
												<option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
											</select>
										</td>
										<td class="hide">
											<input  class="form-control instock" name="instock[]" type="text" value="" id="instock{{$key}}"/>
										</td>
	                                    <td>
	                                        <input type="text" onkeyup="claculation({{$key}})" onblur="claculation({{$key}})" class="form-control requiredField" name="rate[]" id="rate{{$key}}"  min="1" value="{{$sale_data->rate}}">
	                                    </td>
	                                    <td>
	                                         <input type="text" class="form-control amount" name="amount[]" id="amount{{$key}}" placeholder="AMOUNT" min="1" value="{{($sale_data->amount)}}" readonly>
	                                    </td>
	                                     <td style="width: 110px">
	                                           <select onchange="tax_percent(this.id)"  class="form-control" name="tax[]" id="tax_percent{{$key}}">
												<option value="0,0">Select</option>
													@foreach (ReuseableCode::invoice_tax() as $row )
														<option value='{{ $row->acc_id.','.$row->tax_rate }}'>{{$row->tax_rate }}</option>
													@endforeach
											   </select>
	                                    </td>
	                                    <td>
	                                    	<input readonly type="text"  class="form-control requiredField tax_amount" name="tax_amount[]" id="tax_amount{{$key}}"  min="1" value="{{($sale_data->tax_amount)}}">
	                                    </td>
	                                    <td>
	                                        <input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount{{$key}}"  min="1" value="{{($sale_data->amount+$sale_data->tax_amount)}}" readonly>
	                                    </td>
	                                    <td style="background-color: #ccc">
											{{-- <input onclick="view_history(1)" type="checkbox" id="view_history1"> --}}
										</td>
	                                </tr>
									@endforeach
	                        </tbody>
	                        <tbody>
	                               <tr  style="font-size:large;font-weight: bold">
	                                  <td class="text-center" colspan="6">Total</td>
	                                   <td id="" class="text-right" colspan="2"><input readonly class="form-control" type="text" value="{{$sale_data->amount}}" id="net"/> </td>
	                                   <td></td>
	                                </tr>
	                        </tbody>
	                    </table>
	                              </div>
	                           </div>
	                        </div>

	                        <div  class="form-group form-inline text-right">
	                           <label for="email">Total Before Tax </label>
	                           <input readonly type="text" class="form-control" value="{{$sale_data->amount}}" id="total">
	                        </div>
	                        <div  class="form-group form-inline text-right">
	                           <label for="email">Total After Tax </label>
	                           <input readonly type="text" class="form-control" id="total_after_sales_tax" value="{{$sale_data->amount+$sale_data->tax_amount}}" name="total_after_sales_tax">
	                        </div>
	                        <table>
	                           <tr>
	                              <td style="text-transform: capitalize;" id="rupees"></td>
	                              <input type="hidden" value="" name="rupeess" id="rupeess1"/>
	                           </tr>
	                        </table>
	                        <input type="hidden" id="d_t_amount_1" >
	                     </div>


						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
							<div class="col-md-10">
								<h2 class="subHeadingLabelClass">Sub Total</h2>
							</div>
							<div class="col-md-3">
								<div class="padt">
									<!-- <ul class="sale-l sale-l2">
										<li>Total Product</li>
										<li class="text-left">
											<input name="total-product"
												class="form-control form-control2" value="2"
												type="text">
										</li>
									</ul> -->
									<ul class="sale-l sale-l2">
										<li>Total Qty</li>
										<li class="text-left">
											<input name="total_qty" class="form-control form-control2"
												id="total_qty" value="" type="text" readonly>
										</li>
									</ul>
								</div>
							</div>

							<!-- <div class="col-md-3">
								<div class="padt">
									<ul class="sale-l sale-l2">
										<li>Total FOC</li>
										<li class="text-left">
											<input name="total-fac" class="form-control form-control2"
												value="3" type="text">
										</li>
									</ul>
								</div>
							</div> -->

							<div class="col-md-3">
								<div class="padt">
									<ul class="sale-l sale-l2">
										<li>Gross Amount</li>
										<li class="text-left"><input name="total_gross_amount"
												id="total_gross_amount"
												class="form-control form-control2" value="" type="text"readonly>
										</li>
									</ul>
									<!-- <ul class="sale-l sale-l2">
										<li>Total Qty</li>
										<li class="text-left"><input name="total-qty"
												class="form-control form-control2" value="4,181"
												type="text"></li>
									</ul> -->
									<!-- <ul class="sale-l sale-l2">
										<li>Disc</li>
										<li class="text-left"><input name="disc"
												class="form-control form-control2" value="0"
												type="text"></li>
									</ul> -->
									<!-- <ul class="sale-l sale-l2">
										<li>Disc 2</li>
										<li class="text-left"><input name="disc2"
												class="form-control form-control2" value="0"
												type="text"></li>
									</ul> -->
									<ul class="sale-l sale-l2">
										<li>Tax</li>
										<li class="text-left"><input name="total_sales_tax"
												id="total_sales_tax" class="form-control form-control2"
												value="" type="text"readonly></li>
									</ul>
									<ul class="sale-l sale-l2">
										<li>Net Amount</li>
										<li class="text-left"><input name="total_amount_after_sale_tax"
												id="total_amount_after_sale_tax"
												class="form-control form-control2" value="" type="text"readonly>
										</li>
									</ul>
								</div>
							</div>
						</div>


	                     <div class="row">
	                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							   <h2 class="subHeadingLabelClass">Addional Expenses</h2>
	                        </div>
	                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
	                           <div class="table-responsive">
	                              <table class="table table-bordered sf-table-list">
	                                 <thead>
	                                    <th class="text-center">Account Head</th>
	                                    <th class="text-center">Expense Amount</th>
	                                    <th class="text-center">
	                                       <button type="button" class="btn btn-xs btn-primary" id="BtnAddMoreExpense" onclick="AddMoreExpense()">More Expense</button>
	                                    </th>
	                                 </thead>
	                                 <tbody id="AppendExpense">
	                                 </tbody>
	                              </table>
	                           </div>
	                        </div>
	                     </div>
	                  </div>
	               </div>
	               <div class="demandsSection"></div>
                   <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                        <button type="submit" id="BtnSaveAndPrint" class="btn btn-info" >Save & Print</button>
                    </div>
                </div>
			</form>
            </div>
        </div>
    </div>


    <script>
   var CounterExpense = '<?php  $sale_tax_invoice_data->count();?>';
   function AddMoreExpense()
   {
   	CounterExpense++;
   	$('#AppendExpense').append("<tr id='RemoveExpenseRow"+CounterExpense+"'>" +
   			"<td>"+
   			"<select class='form-control requiredField select2' name='account_id[]' id='account_id"+CounterExpense+"'><option value=''>Select Account</option><?php foreach(CommonHelper::get_all_account() as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
   			"</td>"+
   			"</td>" +
   			"<td>" +
   			"<input type='number' name='expense_amount[]' id='expense_amount"+CounterExpense+"' class='form-control requiredField'>" +
   			"</td>" +
   			"<td class='text-center'>" +
   			"<button type='button' id='BtnRemoveExpense"+CounterExpense+"' class='btn btn-sm btn-danger' onclick='RemoveExpense("+CounterExpense+")'> - </button>" +
   			"</td>" +
   			"</tr>");
   	$('#account_id'+CounterExpense).select2();
   }

   function RemoveExpense(Row)
   {
   	$('#RemoveExpenseRow'+Row).remove();
   }

   var Counter = '{{ $key }}';

   function AddMoreDetails()
   {


   	Counter++;
   	$('#AppnedHtml').append(`<tr class="cnt" id="RemoveRows${Counter}"><td><select onchange="get_uom('${Counter}')" name="sub_ic_des[]" id="sub_ic_des${Counter}" class="form-control select2">
													<option value="">Select</option>
														@foreach ( CommonHelper::get_all_subitem() as $row )
														<?php $uom =  CommonHelper::get_uom($row->id); ?>
														<option value="{{ $row->id.','.$uom }}" >{{ $row->sub_ic }}</option>
														@endforeach
												</select>
											</td>
	                                	<td>
	                                          <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}" >
	                                    </td>
	                                    <td>
	                                         <input type="text" onkeyup="claculation(${Counter})" onblur="claculation(${Counter})" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty${Counter}"  min="1" value="">
	                                    </td>
										<td class="hide">
											<select onchange="get_stock(this.id,${Counter});ApplyAll(${Counter})" class="form-control  ClsAll ShowOn${Counter}" name="warehouse[]" id="warehouse${Counter}">
												<option value="">Select</option>
													@foreach(CommonHelper::get_all_warehouse() as $row)
													<option value="{{$row->id}}">{{$row->name}}</option>
													@endforeach
											</select>
										</td>
										<td class="hide">
											<select onchange="get_stock_qty(this.id,${Counter})" class="form-control" name="batch_code[]" id="batch_code${Counter}">
												<option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
											</select>
										</td>
										<td class="hide">
											<input  class="form-control instock"  type="text" name="instock[]" id="instock${Counter}"/>
										</td>
	                                    <td>
	                                        <input type="text" onkeyup="claculation(${Counter})" onblur="claculation(${Counter})" class="form-control requiredField" name="rate[]" id="rate${Counter}"  min="1" value="">
	                                    </td>
	                                    <td>
	                                         <input type="text" class="form-control amount" name="amount[]" id="amount${Counter}" placeholder="AMOUNT" min="1" value="0.00" readonly>
	                                    </td>
	                                     <td style="width: 110px">
	                                           <select onchange="tax_percent(this.id)"  class="form-control" name="tax[]" id="tax_percent${Counter}">
												<option value="0,0">Select</option>
													@foreach (ReuseableCode::invoice_tax() as $row )
														<option value='{{ $row->acc_id.','.$row->tax_rate }}'>{{$row->tax_rate }}</option>
													@endforeach
											   </select>
	                                    </td>
	                                    <td>
	                                    	<input readonly type="text"  class="form-control requiredField tax_amount" name="tax_amount[]" id="tax_amount${Counter}"  min="1" value="0.00">
	                                    </td>
	                                    <td>
	                                        <input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}"  min="1" value="0.00" readonly>
	                                    </td>
	                                    <td style="background-color: #ccc">
											<button type="button" class="btn btn-sm btn-danger" id="BtnRemove${Counter}" onclick="RemoveSection(${Counter})"> - </button>
										</td>
	                            </tr>`);
   	$('.select2').select2();

   	var AutoCount=1;
   	$(".AutoCounter").each(function(){
   		AutoCount++;
   		$(this).prop('title', AutoCount);

   	});
   	$('.sam_jass').bind("enterKey",function(e){

   		var check =(this.id).split('_');

   		if ($('#product_'+check[1]).val()!='')
   		{
   			alert('Bundles Selectd Against This');
   			return false;
   		}
   		$('#items').modal('show');


   	});
   	$('.sam_jass').keyup(function(e){
   		if(e.keyCode == 13)
   		{
   			selected_id=this.id;
   			$(this).trigger("enterKey");


   		}

   	});

   	$('.sam_jass').bind("enterKeyy",function(e){


   		$('#budles_dataa').modal('show');


   	});

   	$('.sam_jass').keyup(function(e){
   		if(e.keyCode == 113)
   		{
   			selected_id=this.id;
   			$(this).trigger("enterKeyy");


   		}

   	});


   	$('.sami').bind("enterKey",function(e){


   		$('#items_searc_for_bundless').modal('show');


   	});
   	$('.sami').keyup(function(e){
   		if(e.keyCode == 13)
   		{
   			selected_idd=this.id;
   			$(this).trigger("enterKey");


   		}

   	});
   	var itemsCount = $(".cnt").length;

   	$('#span').text(itemsCount);
   }
   function RemoveSection(Row) {
   //            alert(Row);
   	$('#RemoveRows' + Row).remove();
   	//   $(".AutoCounter").html('');
   	var AutoCount = 1;
   	var AutoCount=1;
   	$(".AutoCounter").each(function() {
   		AutoCount++;
   		$(this).prop('title', AutoCount);
   	});
   	var itemsCount = $(".cnt").length;

   	$('#span').text(itemsCount);
   }


</script>
<script>
   function view_history(id)
   {
   	var v= $('#sub_ic_des'+id).val();
   	if ($('#view_history' + id).is(":checked"))
   	{
   		if (v!=null)
   		{
   			showDetailModelOneParamerter('sdc/sals_history?id='+v);
   		}
   		else
   		{
   			alert('Select Item');
   		}
   	}
   }

   var x=0;


   $('.sam_jass').bind("enterKey",function(e){

   	var check =(this.id).split('_');

   	if ($('#product_'+check[1]).val()!='')
   	{
   		alert('Bundles Selectd Against This');
   		return false;
   	}
   	$('#items').modal('show');


   });
   $('.sam_jass').keyup(function(e){
   	if(e.keyCode == 13)
   	{
   		selected_id=this.id;
   		$(this).trigger("enterKey");


   	}

   });

   $('.sam_jass').bind("enterKeyy",function(e){


   	$('#budles_dataa').modal('show');


   });

   $('.sam_jass').keyup(function(e){
   	if(e.keyCode == 113)
   	{
   		selected_id=this.id;
   		$(this).trigger("enterKeyy");


   	}

   });


   $('.stop').on('keyup keypress', function(e) {
   	var keyCode = e.keyCode || e.which;
   	if (keyCode === 13) {

   		e.preventDefault();
   		return false;
   	}
   });



   function net_amount()
   {
   	var amount=0;
   	$('.amount').each(function (i, obj) {

   		amount += +$('#'+obj.id).val();
		   console.log(obj.id);
   	});
   	amount=parseFloat(amount).toFixed(3);


   	$('#net').val(amount);
   	$('#total').val(amount);


    var net_amount=0;
	   $('.net_amount_dis').each(function (i, obj) {

		net_amount += +$('#'+obj.id).val();
});
net_amount=parseFloat(net_amount).toFixed(3);
$('#total_after_sales_tax').val(net_amount);

   }








   $(document).ready(function() {


   	$(".btn-success").click(function(e){

   		//alert();
   		var purchaseRequest = new Array();
   		var val;
   		//$("input[name='demandsSection[]']").each(function(){
   		purchaseRequest.push($(this).val());



   		//});
   		var _token = $("input[name='_token']").val();
   		for (val of purchaseRequest) {
   			jqueryValidationCustom();
   			if(validate == 0){
   				//alert(response);
   			}else{
   				return false;
   			}
   		}

   	});
   });
   function removeSeletedPurchaseRequestRows(id,counter){
   	var totalCounter = $('#totalCounter').val();
   	if(totalCounter == 1){
   		alert('Last Row Not Deleted');
   	}else{
   		var lessCounter = totalCounter - 1;
   		var totalCounter = $('#totalCounter').val(lessCounter);
   		var elem = document.getElementById('removeSelectedPurchaseRequestRow_'+counter+'');
   		elem.parentNode.removeChild(elem);
   	}

   }

   $(document).ready(function() {

	off();
   });


   function claculation(number)
   {
   	var  qty=$('#actual_qty'+number).val();
   	var  rate=$('#rate'+number).val();
   	var total=parseFloat(qty*rate).toFixed(2);
   	$('#amount'+number).val(total);

   	var amount = 0;
   	count=1;
   	$('.net_amount_dis').each(function (i, obj) {

   		amount += +$('#'+obj.id).val();

   		count++;
   	});
   	amount=parseFloat(amount);


	tax_percent('tax_percent'+number);
   	net_amount();

   	//  toWords(1);
   }


   function off()
   {

   }



</script>
<script>
	function ApplyAll(number)
        {
            var count =$('#id_count').val();

            if (number==1)
            {
                for (i=1; i<=count; i++)
                {

                 var selectedVal = $('#warehouse'+number).val();
                 $('.ClsAll').val(selectedVal);
                 get_stock('warehouse'+i,i);


                }
            }

        }

		function get_stock(warehouse,number)
        {


            var warehouse=$('#'+warehouse).val();
            var item=$('#sub_ic_des'+number).find(":selected").val();
			var myArray = item.split(",");

            var batch_code='';

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:myArray[0]},
                success:function(data)
                {

                   $('#batch_code'+number).html(data);
                }
            });

        }


		function get_stock_qty(warehouse,number)
        {


            var warehouse=$('#warehouse'+number).val();
			var myArray=$('#sub_ic_des'+number).find(":selected").val();
			var  item= myArray.split(",");
            var batch_code=$('#batch_code'+number).find(":selected").val();
            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
                type: "GET",
                data: {warehouse:warehouse,item:item[0]},
                success:function(data)
                {

                 //   $('#batch_code'+number).html(data);

                    data=data.split('/');

                    $('#instock'+number).val(data[0]);

                    if (data[0]==0)
                    {
                        $("#"+item).css("background-color", "red");
                    }
                    else
                    {
                        $("#"+item).css("background-color", "");
                    }

                }
            });

        }


   function tax_percent(id)
   {
   	var  number= id.replace("tax_percent","");
   	var amount = parseFloat($('#amount' + number).val());

   	var x = $('#'+id).val();

	x=x.split(',');
	x=parseFloat(x[1]);


   	if (x >100)
   	{
   		alert('Percentage Cannot Exceed by 100');
   		$('#'+id).val(0);
   		x=0;
   	}

   	x=x*amount;
   	var tax_amount =parseFloat( x / 100).toFixed(2);
   	$('#tax_amount'+number).val(tax_amount);

   	var tax_amount=parseFloat($('#tax_amount'+number).val());


   	if (isNaN(tax_amount))
   	{

   		$('#tax_amount'+number).val(0);
   		tax_amount=0;
   	}



   	var amount_after_discount=parseFloat(amount+tax_amount).toFixed(3);



   	$('#after_dis_amount'+number).val(amount_after_discount);
   	var amount_after_discount=$('#after_dis_amount'+number).val();

   	if (amount_after_discount==0)
   	{
   		$('#after_dis_amount'+number).val(amount);
   		$('#net_amounttd_'+number).val(amount);
   		$('#net_amount'+number).val(amount_after_discount);
   	}

   	else
   	{

   		$('#net_amounttd_'+number).val(amount_after_discount);
   		$('#after_dis_amount'+number).val(amount_after_discount);
   	}

   	$('#cost_center_dept_amount'+number).text(amount_after_discount);
   	$('#cost_center_dept_hidden_amount'+number).val(amount_after_discount);


   //	sales_tax('sales_taxx');
   	net_amount();
 //  	sales_tax();
   	//  toWords(1);


   }



   function get_detail(id,number)
   {

   	var item=$('#'+id).val();
   	$.ajax({
   		url:'{{url('/pdc/get_data')}}',
   		data:{item:item},
   		type:'GET',
   		success:function(response)
   		{

   			var data=response.split(',');
   			$('#uom_id'+number).val(data[0]);

   		}
   	})



   }
   $(".remove").each(function(){

   	$(this).html($(this).html().replace(/,/g , ''));
   });
   function get_ntn()
   {
   	var ntn=$('#ntn').val();
   	ntn=ntn.split('*');
   	$('#buyers_ntn').val(ntn[1]);
   	$('#buyers_sales').val(ntn[2]);
   	$('#model_terms_of_payment').val(ntn[3]);
   	calculate_due_date();
   	sales_tax();
   }



   function calculate_due_date()
   {

   	var days=parseFloat($('#model_terms_of_payment').val())-1;
   	var tt = document.getElementById('so_date').value;

   	var date = new Date(tt);
   	var newdate = new Date(date);
   	newdate.setDate(newdate.getDate() + days);
   	var dd = newdate.getDate();

   	var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
   	var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
   	var y = newdate.getFullYear();
   	var someFormattedDate =  + y+'-'+ mm +'-'+dd;

   	document.getElementById('due_date').value = someFormattedDate;
   }
   function sales_tax()
   {

   	var total=	parseFloat($('#net').val());
   	if (isNaN(total))
   	{
   		total=0;
   	}

   	if($("#sales_tax_applicable").prop('checked') == false)
   	{
   		total=0;
   	}

   	var sales_tax_percent=parseFloat($('#sales_percent').val());
   	var sales_tax=((total/100)*sales_tax_percent).toFixed(2);
   	$('#sales_tax').val(sales_tax);


   	var strn= $('#buyers_sales').val();
   	var total=	parseFloat($('#net').val());

   	if($("#sales_tax_further_applicable").prop('checked') == false)
   	{
   		total=0;
   	}

   	if (strn=='')
   	{
   		var sales_tax_percent=parseFloat($('#sales_percent_other').val());
   		var sales_tax_further=((total/100)*sales_tax_percent).toFixed(2);
   		$('#sales_tax_further').val(sales_tax_further);

   	}
   	else
   	{
   		sales_tax_further=0;
   		$('#sales_tax_further').val(0);
   	}

   	total_cal();


   	toWords(1);
   }


   function total_cal()
   {
   	var sales_tax_amount=parseFloat($('#sales_tax').val());
   	var sales_tax_amount_further=parseFloat($('#sales_tax_further').val());
   	var total=sales_tax_amount+sales_tax_amount_further;
   	$('#sales_total').val(total);

   	var before_tax=parseFloat($('#net').val());


   	$('#total').val(before_tax);
   	var after_tax=parseFloat($('#sales_total').val());
   	var total_after=before_tax+after_tax;
   	$('#total_after_sales_tax').val(total_after);

   	$('#d_t_amount_1').val(total_after);


   }


   function applicable()
   {
   	sales_tax();
   }

   function get_uom(id)
   {
		var sub_ic_data = $('#sub_ic_des'+id).val();
		sub_ic_data = sub_ic_data.split(',');
		$('#uom_id'+id).val(sub_ic_data[1]);
   }
</script>
<script type="text/javascript">
   $('.select2').select2();
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
