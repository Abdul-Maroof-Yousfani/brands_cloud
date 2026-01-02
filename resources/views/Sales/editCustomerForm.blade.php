<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$Cusomter = DB::Connection('mysql2')->table('customers')->where('id',$id)->first();
$CustMore = DB::Connection('mysql2')->table('customer_info')->where('cust_id',$id)->get();
$States = DB::table('states')->where('country_id',$Cusomter->country)->get();
$Cities = DB::table('cities')->where('state_id',$Cusomter->province)->get();
$Bank = DB::Connection('mysql2')->table('bank_detail')->where('acc_id',$Cusomter->acc_id)->first();
?>
@extends('layouts.default')

@section('content')
@include('select2')
<style>
.subHeadingLabelClass {
    font-size: 23px !important;
}
</style>
<div class="well_N">
    <div class="dp_sdw">
        <div class="well">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <span class="subHeadingLabelClass">Edit Credit Customer</span>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#exampleModal" style="float: right;"> Import csv </button>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border:1px solid #f1f0f0;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <h2 class="subHeadingLabelClass">Customer Details </h2>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="panel">
                                                        <div class="panel-body">
                                                            <form method="post" action="<?= url('sad/updateCreditCustomerDetail?m='.$m.'') ?>" id="submitadv">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                            <input type="hidden" name="EditId" value="<?php echo $id?>">
                                                                            <input type="hidden" name="AccId" value="<?php echo $Cusomter->acc_id?>">
                                                                            <input type="hidden" id="url" value="{{url('sales/viewCreditCustomerList?m=1')}}">
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Name :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <input type="text" name="customer_name" id="customer_name"
                                                                                value="<?php echo $Cusomter->name?>" class="form-control " />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Code :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <input readonly type="text" name="customer_code" id="customer_code"
                                                                                value="<?php echo SalesHelper::generateCustomerCode()?>"
                                                                                class="form-control " />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Customer Code :</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <input type="text" readonly id="customer_code"
                                                                                value="<?php echo $Cusomter->customer_code?>"
                                                                                class="form-control " />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Email :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="email" name="email" id="email"
                                                                                value="<?php echo $Cusomter->email?>"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Phone No 01:</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <input type="text" name="phone_1" id="phone_1"
                                                                                value="{{$Cusomter->phone_1}}"
                                                                                class="form-control " />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Phone No 02:</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="phone_2" id="phone_2"
                                                                                value="{{$Cusomter->phone_2}}"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Country :</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <select name="country" id="country"
                                                                                class="form-control ">
                                                                                <option value="">Select Country :</option>
                                                                                @foreach($countries as $key => $y)
                                                                                <option value="{{ $y->id}}"
                                                                                    <?php if($y->id == $Cusomter->country): echo "selected"; endif;?>>
                                                                                    {{ $y->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>State :</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <select name="state" id="state" class="form-control ">
                                                                                @foreach($States as $key => $y)
                                                                                <option value="{{ $y->id}}"
                                                                                    <?php if($y->id == $Cusomter->province): echo "selected"; endif;?>>
                                                                                    {{ $y->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>City :</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <select name="city" id="city" class="form-control ">
                                                                                @foreach($Cities as $key => $y)
                                                                                <option value="{{ $y->id}}"
                                                                                    <?php if($y->id == $Cusomter->city): echo "selected"; endif;?>>
                                                                                    {{ $y->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <?php
                                                                        $MCounter = 1;
                                                                        ?>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Address :</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <input type="text" name="address" id="address"
                                                                                value="<?php echo $Cusomter->address?>"
                                                                                class="form-control " />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Zip :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="number" name="zip" id="zip"
                                                                                value="{{isset($Cusomter->zip) ? $Cusomter->zip : ''}}"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Title :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="title" id="title"
                                                                                value="{{isset($Cusomter->title) ? $Cusomter->title : ''}}"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Contact Person :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="contact_person"
                                                                                id="contact_person"
                                                                                value="{{isset($Cusomter->contact_person) ? $Cusomter->contact_person : ''}}"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Contact Person Email:</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="contact_person_email"
                                                                                id="contact_person_email"
                                                                                value="{{isset($Cusomter->contact_person_email) ? $Cusomter->contact_person_email : ''}}"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Company Shipping Address:</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            </br>
                                                                            <input type="radio" id="same_as_company_address"
                                                                                name="company_shipping_type"
                                                                                value="same as company address"
                                                                                {{$Cusomter->company_shipping_type == "same as company address" ? 'checked' : ''}} />
                                                                            <label for="contactChoice1">Same As Company
                                                                                Address</label>
                                                                            </br>
                                                                            <input type="radio"
                                                                                {{$Cusomter->company_shipping_type == "others" ? 'checked' : ''}}
                                                                                id="others" name="company_shipping_type"
                                                                                value="others" />
                                                                            <label for="contactChoice2">Other</label>
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                            <label>Shipping City :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="shipping_city"
                                                                                id="shipping_city" class="form-control"
                                                                                value="{{$Cusomter->shipping_city}}" />
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                            <label>Shipping State :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="shipping_state"
                                                                                id="shipping_state" class="form-control"
                                                                                value="{{$Cusomter->shipping_state}}" />
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                            <label>Shipping Country :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="shipping_country"
                                                                                id="shipping_country" class="form-control"
                                                                                value="{{$Cusomter->shipping_country}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Opening Balance :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="number" name="opening_balance"
                                                                                id="opening_balance" class="form-control"
                                                                                value="{{$Cusomter->opening_balance}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Opening Balance Date :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="date" name="opening_balance_date"
                                                                                id="opening_balance_date"
                                                                                value="{{$Cusomter->opening_balance_date}}"
                                                                                class="form-control" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label> Tax Filer Registered :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <select onChange="taxFiler(this)" name="tax_filer"
                                                                                class="form-control ">
                                                                                <option value="">Select Option</option>
                                                                                <option value="yes"
                                                                                    {{$Cusomter->regd_in_income_tax == "Yes" ? 'selected' : ''}}>
                                                                                    Yes</option>
                                                                                <option value="no"
                                                                                    {{$Cusomter->regd_in_income_tax == "no" ? 'selected' : ''}}>
                                                                                    No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                            <label>NTN :</label>
                                                                            <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                            <input type="text" name="ntn" id="strn"
                                                                                value="<?php echo $Cusomter->cnic_ntn?>"
                                                                                class="form-control " />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                            <label>STRN :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <input type="text" name="strn" id="strn"
                                                                                value="<?php echo $Cusomter->strn?>"
                                                                                class="form-control " />
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                            <label>STRN Note/Terms :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="strn_term" id="strn_term"
                                                                                class="form-control"
                                                                                value="{{$Cusomter->strn_term}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                            <label>Display Notes in Invoice:</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="display_note_invoice"
                                                                                id="display_note_invoice"
                                                                                class="form-control ">
                                                                                <option value="yes"
                                                                                    {{$Cusomter->display_note_invoice == "yes" ? 'selected' : ''}}>
                                                                                    yes</option>
                                                                                <option value="no"
                                                                                    {{$Cusomter->display_note_invoice == "no" ? 'selected' : ''}}>
                                                                                    no</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                            <label>W.H Tax :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="number" name="wh_tax" id="wh_tax"
                                                                                class="form-control"
                                                                                value="{{$Cusomter->wh_tax}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                            <label>Adv Tax :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="number" name="adv_tax" id="adv_tax"
                                                                                class="form-control"
                                                                                value="{{$Cusomter->adv_tax ?? 0}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                            <label>Credit Days :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <input type="text" name="credit_days" id="credit_days"
                                                                                value="<?php echo $Cusomter->creditDaysLimit?>"
                                                                                class="form-control " />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Credit Amount Limit :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <input type="text" name="credit_amount"
                                                                                id="credit_amount"
                                                                                class="form-control "
                                                                                value="{{$Cusomter->creditLimit}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Locality :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="locality" id="locality"
                                                                                class="form-control">
                                                                                <option value="1"
                                                                                    {{$Cusomter->locality == 1 ? 'selected' :''}}>
                                                                                    local</option>
                                                                                <option value="2"
                                                                                    {{$Cusomter->locality == 2 ? 'selected' :''}}>
                                                                                    international</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Stores category :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="store_category" id="store_category"
                                                                                class="form-control">
                                                                                <option selected value="">Select Option</option>
                                                                                @foreach($StoresCategory as $category)
                                                                                <option value="{{$category->id}}" {{$Cusomter->store_category == $category->id ? 'selected' :''}}>{{$category->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Territory ID :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="territory_id" id="territory_id"
                                                                                class="form-control">
                                                                                <option selected value="">Select Option</option>
                                                                                @foreach($Territory as $territory)
                                                                                <option value="{{$territory->id}}" {{$Cusomter->territory_id == $territory->id ? 'selected' :''}}>{{$territory->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Sales Person :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="sale_person" id="sale_person"
                                                                                class="form-control"
                                                                                value="{{$Cusomter->SaleRep}}" />
                                                                        </div> -->
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                <label>Sales Person :</label>
                                                                                <span class="rflabelsteric"></span>
                                                                                <select name="sale_person" id="sale_person" class="form-control">
                                                                                    <option value="">-- Select Sales Person --</option>
                                                                                    @foreach($salesPersons as $person)
                                                                                        <option value="{{ $person->id }}" 
                                                                                            {{ $Cusomter->SaleRep == $person->id ? 'selected' : '' }}>
                                                                                            {{ $person->sub_department_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Accept Cheque :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select onChange="checkCheque(this)"
                                                                                name="accept_cheque" id="accept_cheque"
                                                                                class="form-control">
                                                                                <option value="yes">Yes</option>
                                                                                <option value="no">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Display Pending paymen in Invoice :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="display_pending_payment_invoice"
                                                                                id="display_pending_payment_invoice"
                                                                                class="form-control">
                                                                                <option value="yes">Yes</option>
                                                                                <option value="no">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                            <label>Bank Account No :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <input type="text" name="account_no" id="account_no"
                                                                                class="form-control"
                                                                                value="{{isset($Bank->account_no) ? $Bank->account_no : ''}}" />
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                            <label>Bank Account Title :</label>
                                                                            {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                            <input type="text" name="account_title"
                                                                                id="account_title" class="form-control"
                                                                                value="{{isset($Bank->account_title) ? $Bank->account_title : ''}}" />
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                            <label>Bank :</label>
                                                                            {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                            <input type="text" name="bank"
                                                                                value="{{isset($Bank->bank_name) ? $Bank->bank_name : ''}}"
                                                                                id="bank" class="form-control" />
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                            <label>Branch Code :</label>
                                                                            {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                            <input type="text" name="branch_code" id="branch_code"
                                                                                class="form-control"
                                                                                value="{{isset($Bank->swift_code) ? $Bank->swift_code : ''}}" />
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Customer Type :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <select onChange="getCustomerType(this)"
                                                                                name="customer_type" id="customer_Type"
                                                                                class="form-control " > 
                                                                                {{-- @foreach($CustomerType as $data)
                                                                                <option data-type="{{strtolower($data->name)}}" value="{{$data->id}}" {{$Cusomter->CustomerType == $data->id ? 'selected' :''}}>{{$data->name}}</option>
                                                                                @endforeach --}}
                                                                                 <option selected value="">Select Option</option> 
                                                                            <option data-type="general" value="1" {{$Cusomter->CustomerType == 1 ? 'selected' :''}}>General</option>
                                                                            <option data-type="employee" value="2" {{$Cusomter->CustomerType == 2 ? 'selected' :''}}>Employee</option>
                                                                            <option data-type="reseller" value="3" {{$Cusomter->CustomerType == 3 ? 'selected' :''}}>Reseller/ Distributor
                                                                            </option>
                                                                            </select>
                                                                        </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 customerTypeField hide">
                                                                            <label>BA Mapping :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <select name="ba_mapping" id="ba_mapping"
                                                                                class="form-control ">
                                                                                <option value="">Select Option</option>
                                                                                <option value="1"
                                                                                    {{$Cusomter->ba_mapping == 1? 'selected' :''}}>
                                                                                    Yes</option>
                                                                                <option value="0"
                                                                                    {{$Cusomter->ba_mapping == 0? 'selected' :''}}>
                                                                                    No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Employee :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="employee_id" id="employee_id"
                                                                                class="form-control">
                                                                                <option value="">Select Option</option>
                                                                                @foreach(\App\Helpers\SalesHelper::get_all_unregistered_employees() as $employee)
                                                                                <option value="{{ $employee->id }}"
                                                                                        {{ $Cusomter->employee_id == $employee->id ? 'selected' :'' }}>
                                                                                        {{ $employee->name }} -- {{ $employee->id }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <label>Special Price Mapped :</label>
                                                                            <span class="rflabelsteric"></span>
                                                                            <select name="special_price_mapped"
                                                                                id="special_price_mapped" class="form-control">
                                                                                <option value="">Select Option</option>
                                                                                <option value="1"
                                                                                    {{$Cusomter->special_price_mapped == 1? 'selected' :''}}>
                                                                                    Yes</option>
                                                                                <option value="0"
                                                                                    {{$Cusomter->special_price_mapped == 0? 'selected' :''}}>
                                                                                    No</option>
                                                                            </select>
                                                                        </div>
                                                                        @php
                                                                            $warehouse_from = explode(",", $Cusomter->warehouse_from);
                                                                        @endphp
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Warehouse From :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="warehouse_from[]" id="warehouse_from"
                                                                            class="form-control" multiple>
                                                                            <option value="all" {{ in_array("all", $warehouse_from) ? 'selected' : '' }}>All</option>
                                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                            <option value="{{$row->id}}" {{ in_array($row->id, $warehouse_from) ? 'selected' : '' }}>{{$row->name}}</option>
                                                                            @endforeach
                                                                            
                                                                        </select>
                                                                    </div>
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide customerTypeField">
                                                                            <label>Warehouse To :</label>
                                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                                            <select name="warehouse_to" id="warehouse_to"
                                                                                class="form-control " >
                                                                                <option value="">Select Option</option>
                                                                                @foreach(CommonHelper::get_all_virtual_warehouse() as $row)
                                                                                <option value="{{$row->id}}"
                                                                                    {{$Cusomter->warehouse_to == $row->id ? 'selected' : ''}}>
                                                                                    {{$row->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                        <div class="lineHeight">&nbsp;</div>
                                                                        <div class="lineHeight">&nbsp;</div>
                                                                        <div class="row">  
                                                                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                                            <button type="button" class="btn btn-danger" onclick="history.back()">Back</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
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
        </div>
    </div>
</div>

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
                <form action="{{ url('sales/uploadCreditCustomer') }}" method="post"
                    enctype="multipart/form-data">
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
            </div>
        </div>
    </div>
</div>

<script>
const radioButtons = document.querySelectorAll('input[name="company_shipping_type"]');

$("#customer_Type").trigger("change");

// Add change event listener to each radio button
radioButtons.forEach(radio => {
    radio.addEventListener('change', () => {
        var selectedValue = document.querySelector('input[name="company_shipping_type"]:checked')
            .value;
        if (selectedValue == "same as company address") {
            $('.shippingfield').addClass('hide');
        }
        if (selectedValue == "others") {
            $('.shippingfield').removeClass('hide');
        }
    });
});

function download_csv_file() {
    //define the heading for each row of the data
    var csv = 
    'Name,Customer Code,Email,Phone no 1,Phone no 2,Country,State,City,Address,Zip,Title,Contact Person,Contact Person Email,Company Shipping Address,Shipping City,Shipping State,Shipping Country,Opening Balance,Opening Balance Date,Tax Filer Registered,NTN,STRN,STRN Note,Display Notes in Invoice,W.H Tax,Credit Days,Credit Amount Limit,Locality,Stores Category,Territory Id,Sales Person,Accept Cheque,Display Pending Payment in Invoice,Bank Account No,Bank Account Title,Bank,Branch Code,Customer Type,Status,Employee,Special Price Mapped,Warehouse From,Warehouse To,BA Mapping,Adv Tax\n';

    var csvFileData = [
    ['Ahmed', 'CUST001', 'ahmed@example.com', '0333-1234567', '0334-7654321', 'Pakistan', 'Sindh', 'Karachi', 'Street 123', '75500', 'Mr.', 'Ali', 'ali@example.com', 'same as company address', '', '', '', '50000', '2024-01-01', 'Yes', '123456', '654321', 'STRN Note 1', 'No', '5', '23','100000', 'Local', 'GTTTZ', 'territory 1', 'John Doe', 'Yes', 'No', '12345678901234', 'Ali Ahmed', 'Bank Name', '001', 'General', 'Active', '', 'Yes', 'karachi warehouse', '','yes','0'],
    ['Sara', 'CUST002', 'sara@example.com', '0331-2233445', '', 'Pakistan', 'Punjab', 'Lahore', 'Street ABC', '54000', 'Ms.', 'Hina', 'hina@example.com', 'others', 'Lahore', 'Punjab', 'Pakistan', '30000', '2024-02-01', 'No', '789012', '210987', 'STRN Note 2', 'Yes', '10','25', '150000', 'International', 'GTTTZ', 'territory 2', 'Jane Doe', 'No', 'Yes', '', '', '', '', 'General', 'Inactive', '', 'No', 'karachi warehouse', '','No','12'],
    ];
    //merge the data with CSV
    csvFileData.forEach(function(row) {
        csv += row.join(',');
        csv += "\n";
    });

    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';

    //provide the name for the CSV file to be downloaded
    hiddenElement.download = 'Customer file.csv';
    hiddenElement.click();
}

var MCounter = '<?php echo $MCounter?>';

function AddMoreRows() {
    MCounter++;
    $('#AppendHtml').append('<div class="row" id="RemoveRows' + MCounter + '">' +
        '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
        '<label>Contact Person :</label>' +
        '<span class="rflabelsteric"><strong>*</strong></span>' +
        '<input  type="text" name="contact_person_more[]" id="contact_person_more' + MCounter +
        '" value="" class="form-control" />' +
        '</div>' +
        '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
        '<label>Contact No :</label>' +
        '<span class="rflabelsteric"><strong>*</strong></span>' +
        '<input  type="text" name="contact_no_more[]" id="contact_no_more' + MCounter +
        '" value="" class="form-control" />' +
        '</div>' +
        '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
        '<label>Fax :</label>' +
        '<span class="rflabelsteric"><strong>*</strong></span>' +
        '<input  type="text" name="fax_more[]" id="fax_more' + MCounter + '" value="" class="form-control" />' +
        '</div>' +
        '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">' +
        '<label>Address :</label>' +
        '<span class="rflabelsteric"><strong>*</strong></span>' +
        '<input  type="text" name="address_more[]" id="address_more' + MCounter +
        '" value="" class="form-control" />' +
        '</div>' +
        '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">' +
        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
        '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove" onclick="RemoveRows(' + MCounter +
        ')" style="margin: 35px 0px 0px 0px; width: 50px; height: 25px;"> &times; </button>' +
        '</div>' +
        '</div>' +
        '</div>');
}

function RemoveRows(Rows) {
    $('#RemoveRows' + Rows).remove();
}

function taxFiler(element) {
    if (element.value == "no") {
        $('.taxFillerField').addClass('hide');
    }
    if (element.value == "yes") {
        $('.taxFillerField').removeClass('hide');
    }
}

function checkCheque(element) {
    if (element.value == "no") {
        $('.bankDetailField').addClass('hide');
    }
    if (element.value == "yes") {
        $('.bankDetailField').removeClass('hide');
    }
}

function getCustomerType(element) {
    const selectedOption = element.selectedOptions[0];
    const dataType = selectedOption.getAttribute('data-type');
    if (dataType == "reseller") {
        $('.customerTypeField').removeClass('hide');
    } else {
        $('.customerTypeField').addClass('hide');
    }
    if (dataType == "employee") {
        $('#employee_id').prop('disabled', false);
    }
    else {
        $('#employee_id').prop('disabled', true);
    }
}

$(document).ready(function() {
    $('.select2').select2();
    $('#country').select2();
    $('#state').select2();
    $('#city').select2();
    
    $(".btn-success").click(function(e) {
        var cashCustomer = new Array();
        var val;
        cashCustomer.push($(this).val());
        var _token = $("input[name='_token']").val();
        for (val of cashCustomer) {
            jqueryValidationCustom();
            if (validate == 0) {
                // return true;
            } else {
                return true;
            }
        }
    });

    $('select[name="country"]').on('change', function() {
        var countryID = $(this).val();
        if (countryID) {
            $.ajax({
                url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                type: "GET",
                data: {
                    id: countryID
                },
                success: function(data) {
                    $('select[name="city"]').empty();
                    $('select[name="state"]').empty();
                    $('select[name="state"]').html(data);
                }
            });
        } else {
            $('select[name="state"]').empty();
            $('select[name="city"]').empty();
        }
    });

    $('select[name="state"]').on('change', function() {
        var stateID = $(this).val();
        if (stateID) {
            $.ajax({
                url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
                type: "GET",
                data: {
                    id: stateID
                },
                success: function(data) {
                    $('select[name="city"]').empty();
                    $('select[name="city"]').html(data);
                }
            });
        } else {
            $('select[name="city"]').empty();
        }
    });
});

$(document).ready(function() {
    $('#warehouse_from').select2({
        dropdownParent: $('body'),
        width: '100%'
    });
});
</script>
{{-- <script src="{{URL::asset('assets/js/select2/js_tabindex.js') }}"></script> --}}
@endsection
