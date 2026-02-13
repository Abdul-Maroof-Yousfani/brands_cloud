<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$m=Session::get('run_company');
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
                                        <span class="subHeadingLabelClass">Create Customer/Store</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#exampleModal" style="float: right;"> Import csv </button>
                                    </div>
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
                                                        <form method="POST" action="<?= url('sad/addCreditCustomerDetail?m='.$m.'') ?>" id="submitadv">
                                                            {{ csrf_field() }}
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <?php
                                                                        //  echo Form::open(array('url' => 'sad/addCreditCustomerDetail?m='.$m.'','id'=>'addCreditCustomerForm'));
                                                                        //  echo Form::open(array('url' => 'sad/addCreditCustomerDetail?m='.$m.'','id'=>'ddd'));
                                                                        ?>
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <input type="hidden" name="pageType"
                                                                            value="<?php echo $_GET['pageType']?>">
                                                                        <input type="hidden" name="parentCode"
                                                                            value="<?php echo $_GET['parentCode']?>">
                                                                        <input type="hidden" id="url" value="{{url('sales/viewCreditCustomerList?m=1')}}">
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Name :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="text" name="customer_name" id="customer_name"
                                                                            value="" class="form-control " />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Code :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input readonly type="text" name="customer_code" 
                                                                            id="customer_code"
                                                                            value="<?php echo SalesHelper::generateCustomerCode()?>"
                                                                            class="form-control " />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Customer Code :</label>
                                                                        <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                                        <input type="text" name="code" id="code" value=""
                                                                            class="form-control " />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Email :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="email" name="email" id="email" value=""
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Phone No 01:</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="text" name="phone_1" id="phone_1" value=""
                                                                            class="form-control "  />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Phone No 02:</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="phone_2" id="phone_2" value=""
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Country :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <select name="country" id="country" class="form-control ">
                                                                            <option value="">Select Country :</option>
                                                                            @foreach($countries as $key => $y)
                                                                            <option value="{{ $y->id}}">{{ $y->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>State :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <select name="state" id="state" class="form-control ">

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>City :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <select name="city" id="city" class="form-control ">

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Address :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="text" name="address" id="address" value=""
                                                                            class="form-control "  />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Zip :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="zip" id="zip" value=""
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Title :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="title" id="title" value=""
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Contact Person :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="contact_person" id="contact_person"
                                                                            value="" class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Contact Person Email:</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="contact_person_email"
                                                                            id="contact_person_email" value="" class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Company Shipping Address:</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        </br>
                                                                        <input type="radio" id="same_as_company_address"
                                                                            name="company_shipping_type"
                                                                            value="same as company address" />
                                                                        <label for="contactChoice1">Same As Company Address</label>
                                                                        </br>
                                                                        <input type="radio" checked id="others"
                                                                            name="company_shipping_type" value="others" />
                                                                        <label for="contactChoice2">Other</label>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <label>Shipping City :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="shipping_city" id="shipping_city"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <label>Shipping State :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="shipping_state" id="shipping_state"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <label>Shipping Country :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="shipping_country" id="shipping_country"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Opening Balance :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="opening_balance" id="opening_balance"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Opening Balance Date :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="date" name="opening_balance_date"
                                                                            id="opening_balance_date" class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label> Tax Filer Registered :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <select onChange="taxFiler(this)" name="tax_filer"
                                                                            class="form-control ">
                                                                            <option value="">Select Option</option>
                                                                            <option value="Yes">Yes</option>
                                                                            <option value="no">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <label>NTN :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="text" name="ntn" value="-" id="strn" class="form-control " />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                                        
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  taxFillerField">
                                                                        <label>STRN :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="text" name="strn" id="strn" value="-" class="form-control " />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  taxFillerField">
                                                                        <label>STRN Note/Terms :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="strn_term" value="-" id="strn_term"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                        <label>Display Notes in Invoice:</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="display_note_invoice" id="display_note_invoice"
                                                                            class="form-control ">
                                                                            <option value="yes">yes</option>
                                                                            <option value="no">no</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                        <label>W.H Tax :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="wh_tax" id="wh_tax"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                        <label>Adv Tax :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="adv_tax" id="adv_tax"
                                                                            class="form-control"step="0.01" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                        <label>Credit Days :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="number" name="credit_days" id="credit_days"
                                                                            class="form-control " />
                                                                    </div>
                                                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <label>Shipping City :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="shipping_city" id="shipping_city"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <label>Shipping State :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="shipping_state" id="shipping_state"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <label>Shipping Country :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="shipping_country" id="shipping_country"
                                                                            class="form-control" />
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Opening Balance :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="opening_balance" id="opening_balance"
                                                                            class="form-control" />
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Opening Balance Date :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="date" name="opening_balance_date"
                                                                            id="opening_balance_date" class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label> Tax Filer Registered :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select onChange="taxFiler(this)" name="tax_filer"
                                                                            class="form-control">
                                                                            <option value="yes">yes</option>
                                                                            <option value="no">no</option>
                                                                        </select>
                                                                    </div>


                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <label>NTN :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="ntn" id="strn" class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <label>STRN :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="strn" id="strn" class="form-control" />
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <label>STRN Note/Terms :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="strn_term" id="strn_term"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Display Notes in Invoice:</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="display_note_invoice" id="display_note_invoice"
                                                                            class="form-control">
                                                                            <option value="yes">yes</option>
                                                                            <option value="no">no</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>W.H Tax :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="wh_tax" id="wh_tax"
                                                                            class="form-control" />
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Credit Days :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="number" name="credit_days" id="credit_days"
                                                                            class="form-control" />
                                                                    </div> -->
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Credit Amount Limit :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <input type="text" name="credit_amount" id="credit_amount"
                                                                            class="form-control " />
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Locality :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="locality" id="locality" class="form-control">
                                                                            <option value="1">local</option>
                                                                            <option value="2">international</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Stores category :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="store_category" id="store_category"
                                                                            class="form-control">
                                                                            <option selected value="">Select Option</option>

                                                                            @foreach($StoresCategory as $category)
                                                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Territory ID :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="territory_id" id="territory_id"
                                                                            class="form-control">
                                                                            <option selected value="">Select Option</option>

                                                                            @foreach($Territory as $data)
                                                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Sales Person :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="sale_person" id="sale_person"
                                                                            class="form-control" />
                                                                    </div> -->

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Sales Person :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="sale_person" id="sale_person" class="form-control">
                                                                            <option selected value="">Select Option</option>
                                                                            @foreach($SubDepartments as $person)
                                                                                <option value="{{ $person->id }}">{{ $person->sub_department_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Customer Group :</label>
                                            <span class="rflabelsteric"></span>
                                            <select name="customer_group_id" id="customer_group_id" class="form-control select2">
                                                <option value="">-- Select Customer Group --</option>
                                                @foreach($customerGroups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->customer_group }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Accept Cheque :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select onChange="checkCheque(this)" name="accept_cheque"
                                                                            id="accept_cheque" class="form-control">
                                                                            <option value="yes">Yes</option>
                                                                            <option value="no">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Display Pending paymen in Invoice :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="display_pending_payment_invoice"
                                                                            id="display_pending_payment_invoice" class="form-control">
                                                                            <option value="yes">Yes</option>
                                                                            <option value="no">No</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Bank Account No :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="account_no" id="account_no"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Bank Account Title :</label>
                                                                        {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                        <input type="text" name="account_title" id="account_title"
                                                                            class="form-control"  />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Bank :</label>
                                                                        {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                        <input type="text" name="bank" id="bank" class="form-control"
                                                                             />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Branch Code :</label>
                                                                        {{-- <span class="rflabelsteric"><strong>*</strong></span> --}}
                                                                        <input type="text" name="branch_code" id="branch_code"
                                                                            class="form-control"  />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Customer Type :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <select onChange="getCustomerType(this)" name="customer_type"
                                                                            id="customer_Type" class="form-control " >
                                                                            <option selected value="">Select Option</option>
                                                                            <!-- @foreach($CustomerType as $data)
                                                                            <option data-type="{{strtolower($data->name)}}" value="{{$data->id}}">{{$data->name}}</option>
                                                                            @endforeach -->
                                                                            <option data-type="general" value="1">General</option>
                                                                            <option data-type="employee" value="2">Employee</option>
                                                                            <option data-type="reseller" value="3">Reseller/ Distributor
                                                                            </option>
                                                                        </select>

                                                                    </div>
                                                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Status :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="status" id="status" class="form-control">
                                                                            <option value="1">Yes</option>
                                                                            <option value="0">No</option>
                                                                        </select>

                                                                    </div> -->
                                                                    <div
                                                                        class="col-lg-12 col-md-12 col-sm-12 col-xs-12 customerTypeField hide">
                                                                        <label>BA Mapping :</label>
                                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                                        <select name="ba_mapping" id="ba_mapping" class="form-control">
                                                                            <option value="">Select Option</option>
                                                                            <option value="1">Yes</option>
                                                                            <option value="0">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Employee :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="employee_id" id="employee_id" class="form-control select2">
                                                                            <option value="">Select Option</option>
                                                                            @foreach(App\Helpers\SalesHelper::get_all_unregistered_employees() as $row)
                                                                                <option value="{{ $row->id }}" data-email="{{ $row->email }}" data-name="{{ $row->name }}">
                                                                                    {{ $row->name }} -- {{ $row->id }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Special Price Mapped :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="special_price_mapped" id="special_price_mapped"
                                                                            class="form-control">
                                                                            <option value="">Select Option</option>
                                                                            <option value="1">Yes</option>
                                                                            <option value="0">No</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Warehouse From :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="warehouse_from[]" id="warehouse_from"
                                                                            class="form-control" multiple>
                                                                            <option value="all">All</option>
                                                                            @foreach(CommonHelper::get_all_warehouse() as $row)
                                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                                            @endforeach
                                                                            
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Region :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <br>
                                                                        <select name="region_id" class="select2" style="width: 100%;" id="region_id"
                                                                            class="form-control">
                                                                            <option value="">All</option>
                                                                            @foreach($regions as $region)
                                                                                <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                                                            @endforeach
                                                                            
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Status :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <br>
                                                                        <select name="status" class="select2" style="width: 100%;" id="status"
                                                                            class="form-control">
                                                                            <option value="1">Active</option>
                                                                            <option value="0">In-active</option>
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
                                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>



                                                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Credit Amount Limit :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="credit_amount" id="credit_amount"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Locality :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="locality" id="locality" class="form-control">
                                                                            <option value="1">local</option>
                                                                            <option value="2">international</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Stores category :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="store_category" id="store_category"
                                                                            class="form-control">
                                                                            <option value="1">Gt</option>
                                                                            <option value="2">Gtxyz</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Territory ID :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="territory_id" id="territory_id"
                                                                            class="form-control">
                                                                            <option value="1">Territory 1</option>
                                                                            <option value="2">Territory 2</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Sales Person :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="sale_person" id="sale_person"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Accept Cheque :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select onChange="checkCheque(this)" name="accept_cheque"
                                                                            id="accept_cheque" class="form-control">
                                                                            <option value="yes">Yes</option>
                                                                            <option value="no">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Display Pending paymen in Invoice :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="display_pending_payment_invoice"
                                                                            id="display_pending_payment_invoice" class="form-control">
                                                                            <option value="yes">Yes</option>
                                                                            <option value="no">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Bank Account No :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="account_no" id="account_no"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Bank Account Title :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="account_title" id="account_title"
                                                                            class="form-control"  />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Bank :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="bank" id="bank" class="form-control"
                                                                             />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <label>Branch Code :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <input type="text" name="branch_code" id="branch_code"
                                                                            class="form-control"  />
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Customer Type :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select onChange="getCustomerType(this)" name="customer_type"
                                                                            id="customer_Type" class="form-control" >
                                                                            <option selected value="">Select Option</option>
                                                                            <option data-type="general" value="1">General</option>
                                                                            <option data-type="employee" value="2">Employee</option>
                                                                            <option data-type="reseller" value="3">Reseller/ Distributor
                                                                            </option>
                                                                        </select>

                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Status :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="status" id="status" class="form-control">
                                                                            <option value="1">Yes</option>
                                                                            <option value="0S">No</option>
                                                                        </select>

                                                                    </div> -->
                                                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 customerTypeField hide">
                                                                        <label>BA Mapping :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="ba_mapping" id="ba_mapping" class="form-control">
                                                                            <option value="1">Yes</option>
                                                                            <option value="0">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Employee :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="employee_id" id="employee_id" class="form-control"
                                                                            disabled>

                                                                            <option value="">Select Option</option>
                                                                            <option value="1">Employee 01</option>
                                                                            <option value="2">Employee 02</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Special Price Mapped :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="special_price_mapped" id="special_price_mapped"
                                                                            class="form-control">
                                                                            <option value="1">Yes</option>
                                                                            <option value="0">No</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <label>Warehouse From :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="warehouse_from" id="warehouse_from"
                                                                            class="form-control">
                                                                            <option value="1">warehouse1</option>
                                                                            <option value="2">warehouse2</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide customerTypeField">
                                                                        <label>Warehouse To :</label>
                                                                        <span class="rflabelsteric"></span>
                                                                        <select name="warehouse_to" id="warehouse_to"
                                                                            class="form-control">
                                                                            <option value="1">warehousevirtual1</option>
                                                                            <option value="2">warehousevirtual2</option>
                                                                        </select>
                                                                    </div> -->

                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                                    <div class="lineHeight">&nbsp;</div>
                                                                    <div class="lineHeight">&nbsp;</div>
                                                                    <div class="row">  
                                                                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                                            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                // echo Form::close();
                                                                ?>
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

                        <div class="col-sm-12 col-md-12">
                            <table class="table table-bordered table-sm mt-4">
                                <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <th><span class="badge badge-outline-success">This Field is required and name
                                                must be unique</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Customer Code</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Phone no 1</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>Phone no 2</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>Zip</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Title</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Contact Person</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Contact Person Email</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Company Shipping Address</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Shipping City</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Shipping State</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Shipping Country</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Opening Balance</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Opening Balance Date</td>
                                        <th><span class="badge badge-outline-info">This Field is optional</span></th>
                                    </tr>
                                    <tr>
                                        <td>Tax Filer Registered</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>NTN</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>STRN</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>STRN Note</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Display Notes in Invoice</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>W.H Tax</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Credit Days</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Credit Amount Limit</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Locality</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Stores Category</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Territory Id</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Sales Person</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Customer Group</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Accept Cheque</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Display Pending Payment in Invoice</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Bank Account No</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Bank Account Title</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Bank</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Branch Code</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Customer Type</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Employee</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Special Price Mapped</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Warehouse From</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Warehouse To</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Adv Tax</td>
                                        <th><span class="badge badge-outline-success">This Field is optional</span>
                                        </th>
                                    </tr>
                                     <tr>
                                        <td>Region</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                  <tr>
                                        <td>Customer Group</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                  

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <script>
    const radioButtons = document.querySelectorAll('input[name="company_shipping_type"]');
    $(".select2").select2();

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


    //create CSV file data in an array
    // var csvFileData = [

    //     ['Ahmed', 'Karachi', 'Ali', '0332-1234567', 'xyz', 'xyz@gmail.com', '1', '1', '1', '15', '30'],
    // ];

    //create a user-defined function to download CSV file
    function download_csv_file() {

        //define the heading for each row of the data
        var csv = 
        'Name,Customer Code,Email,Phone no 1,Phone no 2,Country,State,City,Address,Zip,Title,Contact Person,Contact Person Email,Company Shipping Address,Shipping City,Shipping State,Shipping Country,Opening Balance,Opening Balance Date,Tax Filer Registered,NTN,STRN,STRN Note,Display Notes in Invoice,W.H Tax,Credit Days,Credit Amount Limit,Locality,Stores Category,Territory Id,Sales Person,Accept Cheque,Display Pending Payment in Invoice,Bank Account No,Bank Account Title,Bank,Branch Code,Customer Type,Status,Employee,Special Price Mapped,Warehouse From,Warehouse To,BA Mapping,Adv Tax, Region\n';

        var csvFileData = [
        ['Ahmed', 'CUST001', 'ahmed@example.com', '0333-1234567', '0334-7654321', 'Pakistan', 'Sindh', 'Karachi', 'Street 123', '75500', 'Mr.', 'Ali', 'ali@example.com', 'same as company address', '', '', '', '50000', '2024-01-01', 'Yes', '123456', '654321', 'STRN Note 1', 'No', '5', '23','100000', 'Local', 'GTTTZ', 'territory 1', 'John Doe', 'Yes', 'No', '12345678901234', 'Ali Ahmed', 'Bank Name', '001', 'General', 'Active', '', 'Yes', 'karachi warehouse', '','yes','0', 'Lahore'],
        ['Sara', 'CUST002', 'sara@example.com', '0331-2233445', '', 'Pakistan', 'Punjab', 'Lahore', 'Street ABC', '54000', 'Ms.', 'Hina', 'hina@example.com', 'others', 'Lahore', 'Punjab', 'Pakistan', '30000', '2024-02-01', 'No', '789012', '210987', 'STRN Note 2', 'Yes', '10','25', '150000', 'International', 'GTTTZ', 'territory 2', 'Jane Doe', 'No', 'Yes', '', '', '', '', 'General', 'Inactive', '', 'No', 'karachi warehouse', '','No','12', 'Lahore'],
    ];
        //merge the data with CSV
        csvFileData.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
        });

        //display the created CSV data on the web browser
        //document.write(csv);


        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';

        //provide the name for the CSV file to be downloaded
        hiddenElement.download = 'Cstomer file.csv';
        hiddenElement.click();
    }





    var MCounter = 0;

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
    $(document).ready(function() {
        var con = 1;

        $(".add-category").click(function() {
            var categorySelect =
                '<button type="button" class="remove-category btn btn-danger" onclick="removeCategory(' +
                con + ')">-</button>' +
                '<select name="category[]" class="form-control cat">' +
                '                                                                            <option value="">Select Option</option>' +
                '@foreach(DB::connection("mysql2")->table("category")->where("status", 1)->get() as $val)' +
                '<option value="{{ $val->id }}">{{ $val->main_ic }}</option>' +
                '@endforeach' +
                '</select>';

            var discountInput =
                '<input style="margin-top:42px;" type="text" name="discount_percent[]" class="form-control dis" />';

            // var removeButton = '';

            var categoryContainer = $('<div class="category-container" id="cat' + con + '"></div>');
            var discountContainer = $('<div class="discount-container" id="dis' + con + '"></div>');

            categoryContainer.append(categorySelect);
            discountContainer.append(discountInput);
            // discountContainer.append(removeButton); // Add remove button to discount container

            $('.category_area').append(categoryContainer);
            $('.discount_area').append(discountContainer);
            con++;
            console.log($('select[name="category[]"]').val());
        });


        // $('.discount_area').on('click', '.remove-category', function() {
        //     $(this).closest('.discount-container').remove(); // Remove the parent discount container
        //     $(this).closest('.category-container').remove();
        // });



        $(".btn-success").click(function(e) {
            var cashCustomer = new Array();
            var val;
            //$("input[name='chartofaccountSection[]']").each(function(){
            cashCustomer.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of cashCustomer) {

                jqueryValidationCustom();
                if (validate == 0) {
                    //return false;
                } else {
                    return false;
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


    function removeCategory(con) {
        $('#dis' + con).remove();
        $('#cat' + con).remove();
    }

    function get_nature_type() {
        var nature = $("#account_id option:selected").text();
        nature = nature.split('-');
        nature = nature[0];
        if (nature == 1 || nature == 4) {
            $('#o_blnc_trans').val(1);
        } else {
            $('#o_blnc_trans').val(0);
        }
    }

    function taxFiler(element) {
        if (element.value == "no") {
            $('.taxFillerField').addClass('hide');
            // $('#display_note_invoice').val('no');
            // Make the select element readonly
            // $('#display_note_invoice').prop('disabled', true);
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
        else{
            $('#employee_id').prop('disabled', true);

        }
        // console.log(dataType); 
    }
    $(document).ready(function() {
        $('#warehouse_from').select2({
            width: '100%'
        });
    });

    </script>
    {{-- <script src="{{URL::asset('assets/js/select2/js_tabindex.js') }}"></script> --}}
    @endsection
