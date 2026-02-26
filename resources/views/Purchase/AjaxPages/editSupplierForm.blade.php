<?php
use App\Helpers\PurchaseHelper;

use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')

    @include('select2');
    <div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Edit Vendors</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <?php echo Form::open(array('url' => 'pad/editSupplierDetail?m='.$m.'','id'=>''));?>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $supplier->id }}"/>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Vendor Code :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input readonly id="vendor_code" type="text" class="form-control requiredField" value="{{ $supplier->vendor_code }}">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Name :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input name="name" id="name" value="{{ $supplier->name }}" type="text" class="form-control requiredField">
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Company Name :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input autofocus type="text"  name="company_name" id="company_name" value="{{ $supplier->company_name }}" class="form-control requiredField" />
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Contact Person :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input  type="text" name="contact_person[]" id="contact_person1" value="{{($supplier_info)?$supplier_info->contact_person : '' }}" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                    <label>Country :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select   name="country" id="country" class="form-control">
                                                                        <option value="">Select Country :</option>
                                                                        @foreach($countries as $key => $y)
                                                                            <option @if($supplier->country == $y->id) selected @endif value="{{ $y->id}}">{{ $y->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                    <label>State :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select  name="state" id="state" class="form-control">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                    <label>City :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                                    <select  name="city" id="city" class="form-control">

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div class="container-fluid well">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="AppendHtml">
                                                    <div class="row">

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Contact No :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input  type="text" name="contact_no[]" id="contact_no1" value="{{ ($supplier_info)?$supplier_info->contact_no : '' }}" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                            <label>Fax :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input  type="text" name="fax[]" id="fax1" value="" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Address :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input  type="text" name="address[]" id="address1" value="{{ ($supplier_info)? $supplier_info->address : '' }}" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                                            <label>Work Phone:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input  type="text" name="work_phone[]" id="work_phone1" value="" class="form-control" />
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Print Cheque As :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input  type="text" name="print_check_as" id="print_check_as" value="{{ $supplier->print_check_as }}" class="form-control" />
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Email :</label>
                                                            <input  type="email" name="email" id="email" value="{{ $supplier->email }}" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hide">
                                                    <button type="button" class="btn btn-xs btn-primary" id="BtnAddMore" onclick="AddMoreRows()" style="margin: 35px 0px 0px 0px; width: 50px; height: 25px;"> + </button>
                                                </div>
                                                </div>


                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                            <label>Terms Of Payment:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input  type="number" name="term" id="term" value="" class="form-control" />
                                                        </div>
                                                        <!--
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Vendor Type :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select   name="vendor_type" id="vendor_type"  class="form-control requiredField">

                                                                <option value="">SELECT</option>
                                                                <option value="">{{strtoupper('outside services/professional fees')}}</option>


                                                                <option value="1">{{strtoupper('advertising/marketing/promotion')}}</option>
                                                                <option value="2">{{strtoupper('rent and occupancy related')}}</option>
                                                                <option value="3">{{strtoupper('supplies')}}</option>
                                                                <option value="4">{{strtoupper('taxes and licenses')}}</option>
                                                                <option value="5">{{strtoupper('employee fringe benefits')}}</option>
                                                                <option value="6">{{strtoupper('utilities')}}</option>
                                                                <option value="7">{{strtoupper('travel and entertainment')}}</option>
                                                                <option value="8">{{strtoupper('insurance')}}</option>
                                                                <option value="9">{{strtoupper('security')}}</option>
                                                                <option value="10">{{strtoupper('auto')}}</option>


                                                            </select>

                                                        </div>
<!-->


                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">

                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hide">
                                                            <label>Website :</label>


                                                            <input  type="text" name="website" id="website" value="" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Credit Limit :</label>
                                                            <span class="rflabelsteric"></span>


                                                            <input  type="text" name="credit_limit" id="credit_limit" value="{{ $supplier->credit_limit }}" class="form-control" />
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                                                            <label>NTN :</label>
                                                            <input style="" placeholder="NTN" type="text" value="{{ $supplier->ntn }}" name="ntn" class="form-control" id="ntn"/>

                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">

                                                            <label>STRN :</label>
                                                            <input style="" placeholder="STRN" type="text" value="{{ $supplier->strn }}" name="strn" class="form-control" id="strn"/>

                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Credit Days :</label>
                                                            <span class="rflabelsteric"></span>
                                                            <input type="text" name="credit_days" id="credit_days" value="{{ $supplier->credit_days }}" class="form-control" />
                                                        </div>

                                                      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <label>Type :</label>
    <span class="rflabelsteric"><strong>*</strong></span>
    <select name="to_type" id="to_type" class="form-control">
        <option value="">Select Option</option>
        @foreach(CommonHelper::get_all_to_types() as $item)
            <option value="{{ $item->id }}" 
                @if(isset($supplier) && $supplier->to_type_id == $item->id) selected @endif>
                {{ $item->name }}
            </option>
        @endforeach
    </select>
</div>


                                                        @php 
                                                    $category_id=json_decode($supplier->category_id);
                                                    $discount=json_decode($supplier->discount);
                                                    $con=0;
                                                @endphp
                                                @if(!empty($category_id))        
                                                <div class="container">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Category</label>
                                                    </div>
                                                    <div class="col-sm-6 text-right">
                                                        <span class="btn btn-sm btn-primary add-category" style="cursor:pointer;text-align:right;">+</span>
                                                    </div>
                                                    </div>
                                                    
                                                    <span class="rflabelsteric"></span>
                                                    <span class="category_area">
                                                    @foreach($category_id as $key => $cat)
                                                    @php $con++; @endphp
                                                    <div class="category-container" id="cat{{$con}}">
                                                    @if($key!=0)
                                                    <button type="button" class="remove-category btn btn-danger btn-sm" onclick="removeCategory({{$con}})">-</button>    
                                                    @endif
                                                    <select name="category[]" id="category"class="form-control">
                                                        <option>Select Option</option>
                                                        @foreach(DB::connection('mysql2')->table('category')->where('status',1)->get() as $val)
                                                        <option @if($val->id==$category_id[$key]) selected @endif value="{{ $val->id }}">{{ $val->main_ic }}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                    @endforeach
                                                    </span>    
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Discount % :</label>
                                                    <span class="rflabelsteric"></span>
                                                     
                                                    <span class="discount_area">
                                                    @php $con=0; @endphp
                                                    @foreach($category_id as $key => $cat)
                                                    @php $con++; @endphp
                                                    <div class="discount-container" id="dis{{$con}}"> 
                                                    <input style="margin-top:@if($key!=0) 42px @endif;" type="text" name="discount_percent[]" id="discount_percent" value="{{ $discount[$key] }}" class="form-control" />
                                                    </div>
                                                    @endforeach    
                                                </span>
                                                </div>
                                                </div>
                                                @else
                                               
                                                <div class="container">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Category</label>
                                                    </div>
                                                    <div class="col-sm-6 text-right">
                                                        <span class="btn btn-sm btn-primary add-category" style="cursor:pointer;text-align:right;">+</span>
                                                    </div>
                                                    </div>
                                                    
                                                    <span class="rflabelsteric"></span>
                                                    <select name="category[]" id="category"class="form-control">
                                                        <option>Select Option</option>
                                                        @foreach(DB::connection('mysql2')->table('category')->where('status',1)->get() as $val)
                                                        <option value="{{ $val->id }}">{{ $val->main_ic }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="category_area"></span>    
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Discount % :</label>
                                                    <span class="rflabelsteric"></span>
                                                    <input type="text" name="discount_percent[]" id="discount_percent" value="" class="form-control" />
                                                    <span class="discount_area"></span>
                                                </div>
                                                </div>
                                                @endif

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label for="o_blnc" >Opening Balance :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            @if($transactions)
                                                                <input type="number" name="o_blnc" maxlength="15" value="{{  $transactions->amount }}"  min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField" value="0" autocomplete="off"/>
                                                            @else
                                                                <input type="number" name="o_blnc" maxlength="15" value="0"  min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField" value="0" autocomplete="off"/>
                                                            @endif    
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label for="o_blnc" >Opening Type :</label>
                                                            @if($transactions)
                                                                <select class="form-control" name="nature">
                                                                    <option @if($transactions->debit_credit==0) selected @endif value="0">Credit</option>
                                                                    <option @if($transactions->debit_credit==1) selected @endif value="1">Debit</option>
                                                                </select>
                                                            @else
                                                                <select class="form-control" name="nature">
                                                                    <option value="0" selected>Credit</option>
                                                                    <option value="1">Debit</option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                         <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label for="o_blnc">WithHolding Tax :</label>
                                                           <select name="with_holding_tax" id="with_holding_tax"
                                                                class="form-control select2"
                                                               
                                                               >
                                                                <option value="">Select WithHolding</option>
                                                                @foreach(ReuseableCode::get_all_sales_tax() as $row_tax)
                                                                <option @if($supplier->with_holding_tax == $row_tax->id) selected @endif value="{{ $row_tax->id}}" data-rate="{{$row_tax->rate}}" >{{$row_tax->rate}} %
                                                                </option>

                                                             
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 hide">
                                                            <label for="o_blnc" >TaxPayer Status </label> <br>
                                                            <span>COMPANY</span> <input type="checkbox" name="acompany_status[]" id="COMPANY" value="COMPANY"><br>
                                                            <span>INDIVIDUAL</span> <input type="checkbox" name="acompany_status[]" id="INDIVIDUAL" value="INDIVIDUAL"><br>
                                                            <span>AOP</span> <input type="checkbox" name="acompany_status[]" id="AOP" value="AOP"><br>
                                                            <span>BUSINESS INDIVIDUAL</span> <input type="checkbox" name="acompany_status[]" id="BUSINESS_INDIVIDUAL" value="BUSINESS_INDIVIDUAL">
                                                        </div>



                                                    </div>
                                                </div>



                                                <div>&nbsp;</div>

                                                <div class="well hide">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <span class="subHeadingLabelClass"><input id="bank_detail" type="checkbox"> Bank Details </input>  </span>
                                                        </div>

                                                        <div id="" style="display: none;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banks">
                                                            <div class="row">

                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                    <label>Bank Acc. No :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>

                                                                    <input  type="text" name="acc_no" id="acc_no" value="" class="form-control required" />
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                    <label>Bank Name :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>


                                                                    <input  type="text" name="bank_name" id="bank_name" value="" class="form-control required" />
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                    <label>Branch  Name :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>


                                                                    <input  type="text" name="branch_name" id="branch_name" value="" class="form-control required" />
                                                                </div>




                                                            </div>
                                                        </div>

                                                        <div id="" style="display: none;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banks">
                                                            <div class="row">

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label>Bank Address :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>

                                                                    <input  type="text" name="bank_address" id="bank_address" value="" class="form-control required" />
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <label>Swift Code :</label>
                                                                    <span class="rflabelsteric"><strong>*</strong></span>


                                                                    <input  type="text" name="swift_code" id="swift_code" value="" class="form-control required" />
                                                                </div>





                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>





                                                <?php   //register income criteria ?>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        tabindex="15"
                                                                        type="checkbox"
                                                                        id="regd_in_income_tax"
                                                                        name="regd_in_income_tax"
                                                                        value="1"
                                                                        {{ $supplier->resgister_income_tax == 1 ? 'checked' : '' }}
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen"> Registered In Income Tax?</b>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="income_tax_div">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0" >
                                                                <label class="radio-inline">
                                                                    <input class=""  onclick="ntn_cnic('1')" type="radio" name="company_status" class="income" id="business" value="1"  {{ $supplier->company_status == 1 ? 'checked' : '' }}>Business Individual
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input onclick="ntn_cnic('2')" type="radio" name="company_status" class="income" id="company" value="2" {{ $supplier->company_status == 2 ? 'checked' : '' }}>Company
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input onclick="ntn_cnic('3')" type="radio" name="company_status" class="income" id="aop" value="3" {{ $supplier->company_status == 3 ? 'checked' : '' }}>Aop
                                                                </label>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>



                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                    <div class="row">
                                                        <div id="amir" class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox">


                                                            <input style="display: none;" placeholder="NTN" type="text" name="" class="form-control" id="ntn"/>

                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 checkbox">

                                                            <input style="display: none;margin-top: 15px" placeholder="CNIC" type="text" name="cnic" value="{{ $supplier->cnic }}" class="form-control" id="cnic"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php   //register income End ?>



                                                <?php   //register Sales Tax Start ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        id="regd_in_sales_tax"
                                                                        name="regd_in_sales_tax"
                                                                        value="1"
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen">Registered In Sales Tax?</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="sales_tax_div" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0">
                                                                <label for="strn">STRN </label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <input type="text"
                                                                       min="0"
                                                                       maxlength="15"

                                                                       placeholder="STRN"
                                                                       maxlength="18"
                                                                       min="0"
                                                                       class="form-control sf-uc-first text-right"
                                                                       id="strn"
                                                                       name=""
                                                                       value="" />
                                                                <?php  ;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php   //register SRB ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        id="regd_in_srb"
                                                                        name="regd_in_srb"
                                                                        value="1"
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen">Registered In SRB?</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="sales_tax_srb" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0">
                                                                <label for="strn"> SRB</label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <input type="text"
                                                                       min="0"
                                                                       maxlength="15"

                                                                       placeholder="SRB"
                                                                       maxlength="18"
                                                                       min="0"
                                                                       class="form-control sf-uc-first text-right"
                                                                       id="srb"
                                                                       name="srb"
                                                                       value="" />
                                                                <?php  ;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php   //register SRB End ?>


                                                <?php   //register in PRA ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 checkbox">
                                                            <label>
                                                                <input
                                                                        type="checkbox"
                                                                        id="regd_in_pra"
                                                                        name="regd_in_pra"
                                                                        value="1"
                                                                        />
                                                                <input type="hidden" value="set" name="hidden" />
                                                                <b class="smr-text-cgreen">Registered In PRA?</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="sales_tax_pra" style="display:none">
                                                            <div class="panel panel-primary panel-body well" data-collapsed="0">
                                                                <label for="strn"> PRA</label>
                                                                <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                                                                <input type="text"
                                                                       min="0"
                                                                       maxlength="15"

                                                                       placeholder="PRA"
                                                                       maxlength="18"
                                                                       min="0"
                                                                       class="form-control sf-uc-first text-right"
                                                                       id="pra"
                                                                       name="pra"
                                                                       value="" />
                                                                <?php  ;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php   //register SRB End ?>



                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">



                                                        <!--
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="o_blnc_trans">Transaction :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select name="o_blnc_trans" id="o_blnc_trans" class="form-control requiredField">
                                                                <option value="">select</option>
                                                                <option value="1"><strong>Debit</strong></option>
                                                                <option value="0"><strong>Credit</strong></option>
                                                            </select>
                                                        </div>
                                                          <!-->
                                                    </div>
                                                </div>







                                                <div>&nbsp;</div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                    <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>

                                                    <?php
                                                    //echo Form::submit('Click Me!');
                                                    ?>
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
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
  var selectedCountry = $('#country').val();
    var selectedState = "{{ $supplier->province }}";
    var selectedCity = "{{ $supplier->city }}";
    
    if (selectedCountry) {
        // First load states based on country
        $.ajax({
            url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
            type: "GET",
            data: { id: selectedCountry },
            success: function(data) {
                $('select[name="state"]').html(data);
                
                // After states are loaded, select the saved state
                if (selectedState) {
                    $('select[name="state"]').val(selectedState).trigger('change');
                    
                    // Then load cities based on selected state
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                        type: "GET",
                        data: { id: selectedState },
                        success: function(cityData) {
                            $('select[name="city"]').html(cityData);
                            
                            // Finally, select the saved city
                            if (selectedCity) {
                                $('select[name="city"]').val(selectedCity);
                            }
                        }
                    });
                }
            }
        });
    }
    
    // Your existing code for country change event
    $('select[name="country"]').on('change', function() {
        var countryID = $(this).val();
        if(countryID) {
            $.ajax({
                url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                type: "GET",
                data: { id: countryID},
                success:function(data) {
                    $('select[name="city"]').empty();
                    $('select[name="state"]').empty();
                    $('select[name="state"]').html(data);
                }
            });
        }else{
            $('select[name="state"]').empty();
            $('select[name="city"]').empty();
        }
    });

    // Your existing code for state change event
    $('select[name="state"]').on('change', function() {
        var stateID = $(this).val();
        if(stateID) {
            $.ajax({
                url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                type: "GET",
                data: { id: stateID},
                success:function(data) {
                    $('select[name="city"]').empty();
                    $('select[name="city"]').html(data);
                }
            });
        }else{
            $('select[name="city"]').empty();
        }
    });

            var con={{$con}};
            if(con<1){
                con=1;
            }
            $(".add-category").click(function() {
                con++;
                var categorySelect = '<button type="button" class="remove-category btn btn-danger" onclick="removeCategory('+con+')">-</button>'+
                '<select name="category[]" class="form-control cat">' +
                    '<option>Select Option</option>' +
                    '@foreach(DB::connection("mysql2")->table("category")->where("status", 1)->get() as $val)' +
                    '<option value="{{ $val->id }}">{{ $val->main_ic }}</option>' +
                    '@endforeach' +
                    '</select>';

                var discountInput = '<input style="margin-top:42px;" type="text" name="discount_percent[]" class="form-control dis" />';
                
                // var removeButton = '';

                var categoryContainer = $('<div class="category-container" id="cat'+con+'"></div>');
                var discountContainer = $('<div class="discount-container" id="dis'+con+'"></div>');
                
                categoryContainer.append(categorySelect);
                discountContainer.append(discountInput);
                // discountContainer.append(removeButton); // Add remove button to discount container
                
                $('.category_area').append(categoryContainer);
                $('.discount_area').append(discountContainer);
               
                
            });

            $('select[name="country"]').on('change', function() {
                var countryID = $(this).val();
                if(countryID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                        type: "GET",
                        data: { id:countryID},
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="state"]').empty();
                            $('select[name="state"]').html(data);
                        }
                    });
                }else{
                    $('select[name="state"]').empty();
                    $('select[name="city"]').empty();
                }
            });

            $('select[name="state"]').on('change', function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                        type: "GET",
                        data: { id:stateID},
                        success:function(data) {
                            $('select[name="city"]').empty();
                            $('select[name="city"]').html(data);
                        }
                    });
                }else{
                    $('select[name="city"]').empty();
                }
            });

            // Initial state for Income Tax
            if ($('#regd_in_income_tax').is(':checked')) {
                $('#income_tax_div').show();
                var selectedStatus = $('input[name="company_status"]:checked').val();
                if (selectedStatus) {
                    ntn_cnic(selectedStatus);
                }
            } else {
                $('#income_tax_div').hide();
                $("#cnic").hide();
            }

        });

        function removeCategory(con){
            $('#dis'+con).remove(); 
            $('#cat'+con).remove(); 
        }

        // function ntn_cnic(id)
        // {
        //     if(id==1)
        //     {

        //         $(this).prop('checked', false);
        //         $("#ntn").fadeIn(500);
        //         $("#cnic").fadeIn(500);
        //         $("#amir").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
        //         $("#amir").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
        //         $("#ntn").addClass("requiredField");
        //         $("#cnic").addClass("requiredField");
        //     }

        //     else
        //     {

        //         $("#ntn").fadeIn(500);
        //         $("#ntn").addClass("requiredField");
        //         $("#cnic").css("display", "none");
        //         $("#cnic").removeClass("requiredField");
        //         $("#amir").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
        //         $("#amir").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");

        //     }
        // }

        function ntn_cnic(id)
{
    if(id==1) // Business Individual
    {
        // Show both NTN and CNIC
        $("#ntn").fadeIn(500);
        $("#cnic").fadeIn(500);
        
        // Add required class to both
        $("#ntn").addClass("requiredField");
        $("#cnic").addClass("requiredField");
        
        // Adjust column layout
        $("#amir").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
        $("#amir").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
    }
    else if(id==2 || id==3) // Company or AOP
    {
        // Show only NTN
        $("#ntn").fadeIn(500);
        
        // Add required class to NTN only
        $("#ntn").addClass("requiredField");
        
        // Hide and remove required from CNIC
        $("#cnic").css("display", "none");
        $("#cnic").removeClass("requiredField");
        // $('#cnic').val(""); // Clear CNIC value (REMOVED to prevent loss of data)
        
        // Adjust column layout
        $("#amir").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
        $("#amir").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
    }
}

        // $('#regd_in_income_tax').change(function(){
        //     if ($(this).is(':checked'))
        //     {
        //         $('.income').prop('checked', false);
        //         document.getElementById("income_tax_div").style.display = "block";
        //     } else {
        //         document.getElementById("income_tax_div").style.display = "none";
        //         $("#cnic").css("display", "none");
        //         // $("#ntn").css("display", "none");
        //         // $('#ntn').val("");
        //     }
        // });

        $('#regd_in_income_tax').change(function(){
    if ($(this).is(':checked'))
    {
        // $('.income').prop('checked', false); // REMOVED: Preserve selection
        document.getElementById("income_tax_div").style.display = "block";
        
        var selectedStatus = $('input[name="company_status"]:checked').val();
        if (selectedStatus) {
            ntn_cnic(selectedStatus);
        }
    } else {
        document.getElementById("income_tax_div").style.display = "none";
        
        // Sirf required class hatao, input field ko hide mat karo
        $("#ntn").removeClass("requiredField");
        $("#cnic").removeClass("requiredField");
        
        // Required class ka red border bhi hatao
        $("#ntn").css('border-color', '#ccc');
        $("#cnic").css('border-color', '#ccc');
        
        // Radio buttons ko unchecked karo
        $('.income').prop('checked', false);
        
        // CNIC ko hide karo (kionke business individual select nahi hai)
        $("#cnic").css("display", "none");
        
        // NTN ko visible rakho lekin required na ho
        $("#ntn").css("display", "block");
        
        // Column layout reset karo
        $("#amir").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
        $("#amir").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
    }
});


        $('#regd_in_sales_tax').change(function(){
            if ($(this).is(':checked'))
            {
                document.getElementById("sales_tax_div").style.display = "block";
                $("#strn").addClass("requiredField");
            } else {
                document.getElementById("sales_tax_div").style.display = "none";
                $('#strn').val("");
                $("#strn").removeClass("requiredField");
            }
        });

        $('#regd_in_srb').change(function(){
            if ($(this).is(':checked'))
            {
                document.getElementById("sales_tax_srb").style.display = "block";
                $("#srb").addClass("requiredField");
            } else {
                document.getElementById("sales_tax_srb").style.display = "none";
                $('#srb').val("");
                $("#srb").removeClass("requiredField");
            }
        });


        $('#regd_in_pra').change(function(){
            if ($(this).is(':checked'))
            {
                document.getElementById("sales_tax_pra").style.display = "block";
                $("#pra").addClass("requiredField");
            } else {
                document.getElementById("sales_tax_pra").style.display = "none";
                $('#pra').val("");
                $("#pra").removeClass("requiredField");
            }
        });

        $('#bank_detail').change(function(){
            if ($(this).is(':checked'))
            {

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

        $(document).ready(function(){
            $("form").submit(function(){
                var input = document.getElementsByClassName('requiredField');
                var v= input.length;


                if ($('#regd_in_income_tax').is(':checked'))
                {
                    if ($('#business').is(':checked')==false && $('#company').is(':checked')==false && $('#aop').is(':checked')==false )
                    {
                        alert('Required Business Type');
                        $('#regd_in_income_tax').focus();
                        return false;
                    }
                }


                if ($('#regd_in_sales_tax').is(':checked'))
                {
                    if ($('#business').is(':checked')==false && $('#company').is(':checked')==false && $('#aop').is(':checked')==false )
                    {
                        alert('Required Business Type');
                        $('#regd_in_income_tax').focus();
                        return false;
                    }
                }

                //var select = document.getElementsByTagName('select');
                for (i = 0; i < input.length; i++){
                    var v = input[i].id;
                    if(v == '')
                    {

                    }

                    else{
                        if($('#'+v).val() == '')

                        {


                            $('#'+v).css('border-color', 'red');

                            $('#'+v).focus();
                            return false;
                        }

                        else
                        {
                            $('#'+v).css('border-color', '#ccc');
                        }
                    }
                }


            });
        });



        var count=1;
        var count_address=1;
        var count_contact_person=1;
        var count_fax=1;
        $(document).ready(function() {
            var max_fields      = 10; //maximum input boxes allowed
            var wrapper         = $(".input_fields_wrap");

            var add_button      = $(".add_field_button"); //Add button ID

            //initlal text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count++;

                $(wrapper).append('<input  type="text" name="contact_no[]" id="contact_no'+count+'" value="" class="form-control requiredField remove'+count+'"/>');

            });


            var max_fields_address      = 10; //maximum input boxes allowed
            var wrapper_address         = $(".input_fields_wrap_address");

            var add_button_address      = $(".add_field_button_address");
            $(add_button_address).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count_address++;
                $(".removes").css("display", "block");
                $(wrapper_address).append('<input  type="text" name="address[]" id="address'+count+'" value="" class="form-control requiredField remove_address'+count_address+'"/>');

            });

            var max_fields_contact_person      = 10; //maximum input boxes allowed
            var wrapper_contact_person         = $(".input_fields_wrap_contact_person");

            var add_button_contact_person      = $(".add_field_button_contact_person");
            $(add_button_contact_person).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count_contact_person++;
                $(".remove_contact_person").css("display", "block");
                $(wrapper_contact_person).append('<input  type="text" name="contact_person[]" id="contact_person'+count+'" value="" class="form-control requiredField remove_contact_person'+count_address+'"/>');

            });

            var max_fields_fax      = 10; //maximum input boxes allowed
            var wrapper_fax         = $(".input_fields_wrap_fax");

            var add_button_fax      = $(".add_field_button_fax");
            $(add_button_fax).click(function(e){ //on add input button click
                e.preventDefault();
                //max input box allowed
                //text box increment

                count_fax++;
                $(".remove_fax").css("display", "block");
                $(wrapper_fax).append('<input  type="text" name="fax[]" id="fax'+count+'" value="" class="form-control requiredField remove_fax'+count_fax+'"/>');

            });


        });
        function remove()
        {


            $('.remove'+count).remove();
            count--;


        }

        function remove_address()
        {


            $('.remove_address'+count_address).remove();
            count_address--;


        }
        function remove_contact_person()
        {


            $('.remove_contact_person'+count_contact_person).remove();
            count_contact_person--;


        }

        function remove_fax()
        {


            $('.remove_fax'+count_fax).remove();
            count_fax--;


        }
        var MCounter = 1;
        function AddMoreRows()
        {
            MCounter++;
            $('#AppendHtml').append('<div class="row" id="RemoveRows'+MCounter+'">' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Contact Person :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="contact_person[]" id="contact_person'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Contact No :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="contact_no[]" id="contact_no'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Fax :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="fax[]" id="fax'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label>Address :</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="address[]" id="address'+MCounter+'" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
                    '<label>Work Phone:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input  type="text" name="work_phone[]" id="work_phone1" value="" class="form-control" />' +
                    '<button type="button" class="btn btn-xs btn-danger pull-right" id="BtnRemove" onclick="RemoveRows('+MCounter+')" style="width: 50px; height: 25px;"> &times; </button>' +
                    '</div>' +
                    '</div>');
        }

        function RemoveRows(Rows)
        {
            $('#RemoveRows'+Rows).remove();
        }
    </script>


    <script type="text/javascript">
        $('#account_head').select2();
        $('#vendor_type').select2();
        $('#country').select2();
        $('#state').select2();
        $('#city').select2();
        $('#account_id').select2();
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
