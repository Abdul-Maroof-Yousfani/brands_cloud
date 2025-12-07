<?php

use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
?>

@include('select2')
<style>
.subHeadingLabelClass {
    font-size: 23px !important;
}
</style>


 
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <span class="subHeadingLabelClass">View Customer/Store</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <!-- <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#exampleModal" style="float: right;"> Import csv </button>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                            <hr style="border:1px solid #f1f0f0;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
 
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      
                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                       
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                            <label>Name :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                            <p id="name">{{ $customer->name ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Code :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                            <p id="name">{{ $customer->code ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                         <label>Customer Code :</label>
                                                                         </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                         <p id="Customer Code">{{ $customer->customer_code ?? '-' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Email :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Email">{{ $customer->email ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Phone No 01:</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Phone1">{{ $customer->phone_1 ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Phone No 02:</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Phone2">{{ $customer->phone_2 ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Country :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Country">{{ CommonHelper::get_country_name_by_id($customer->country) ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>State :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="State">{{ $customer->state ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>City :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="City">{{ CommonHelper::get_city_name_by_id($customer->city) ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Address :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Address">{{ $customer->address ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Zip :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="zip">{{ $customer->zip ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Title :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Title">{{ $customer->title==null ? '-' : $customer->title }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Contact Person :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="ContactPerson ">{{ $customer->contact_person==null ? '-' : $customer->contact_person }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Contact Person Email:</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="ContactPersonEmail ">{{ $customer->contact_person_email==null ? '-' : $customer->contact_person_email }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Company Shipping Address:</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="CompanyShippingAddress">{{ $customer->company_shipping_type ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Shipping City :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Shipping">{{ $customer->shipping_city ?? '-' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Shipping State :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Shippingstate">{{ $customer->shipping_state ?? '-' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shippingfield">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Shipping Country :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Shippingcountry">{{ $customer->shipping_country ?? '-' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Opening Balance :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Opening">{{ $customer->opening_balance==0 ? '-' : $customer->opening_balance }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Opening Balance Date :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="OpeningBalanc">{{ $customer->opening_balance_date==0 ? '-' : $customer->opening_balance_date }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label> Tax Filer Registered :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="TaxFiler">{{ $customer->regd_in_income_tax ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>NTN :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="ntn">{{ $customer->cnic_ntn ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                                          
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>STRN :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="strn">{{ $customer->strn==null ? '-' : $customer->strn }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 taxFillerField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>STRN Note/Terms :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="strn_note">{{ $customer->strn_term==null ? '-' : $customer->strn_term }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Display Notes in Invoice:</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Display_note">{{ $customer->display_note_invoice ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>W.H Tax :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="whtax">{{ $customer->wh_tax==null ? '-' : $customer->wh_tax }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Credit Days :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="creaditdays">{{ $customer->creditDaysLimit ?? '' }}</p>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Credit Amount Limit :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="creaditAmount">{{ $customer->creditLimit ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Locality :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Locality">{{ $customer->locality==1 ? 'Local' : 'International' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Stores category :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Storescategory">{{ $customer->store_category==0 ? '-' : $customer->store_categoryname }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Territory ID :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="territoryID">{{ $customer->tname ?? '' }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Sales Person :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                     <p id="SalesPerson">{{ CommonHelper::get_sub_department($customer->SaleRep) ?? '' }}</p>

                                                                       
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Accept Cheque :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="AcceptCheque ">{{ $customer->accept_cheque == 0 ? '-' : $customer->accept_cheque }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Display Pending paymen in Invoice :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="DisplayPendingpaymen ">{{$customer->display_pending_payment_invoice==0 ? '-' : $customer->display_pending_payment_invoice }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Bank Account No :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Bank">{{  $bankDetail ? $bankDetail->account_no : "-"  }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Bank Account Title :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Bank">{{  $bankDetail ? $bankDetail->account_title : "-"  }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Bank :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Bank">{{  $bankDetail ? $bankDetail->bank_name : "-"  }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bankDetailField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Branch Code :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Bank">{{  $bankDetail ? $bankDetail->swift_code : "-"  }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Customer Type :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="CustomerType">
                                                                            @if ($customer->CustomerType == 0)
                                                                                -
                                                                            @elseif ($customer->CustomerType == 1)
                                                                                General
                                                                            @elseif ($customer->CustomerType == 2)
                                                                                Employee
                                                                            @else
                                                                                Reseller/ Distributor
                                                                            @endif
                                                                        </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Status :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Status">{{$customer->status == 0 ? 'Inactive' : 'Active'}}</p>
                                                                        </div>
    
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 customerTypeField hide">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>BA Mapping :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="BAMapping">{{$customer->ba_mapping ?? "-"}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Employee :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Employee">{{$customer->employee_id ?? "-"}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Special Price Mapped :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="SpecialPriceMapped">{{$customer->special_price_mapped ?? "-"}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Warehouse From :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="WarehouseFrom">{{$customer->warehouse_from==0 ? "All" : $customer->warehouse_name }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide customerTypeField">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <label>Warehouse To :</label>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                        <p id="Warehouseto">{{$customer->warehouse_to ?? "-"}}</p>
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
                                            
                                            <input type="file" name='file' label="Choose File" required>
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
                                        <td>City</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>Contact Person</td>
                                        <th><span class="badge badge-outline-info">This Field is required</span></th>
                                    </tr>
                                    <tr>
                                        <td>Contact No</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>NTN</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>STRN</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Terms Of Payment</td>
                                        <th><span class="badge badge-outline-success">This Field is required</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Discount (%)</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>
                                    <tr>
                                        <td>Credit Days</td>
                                        <th><span class="badge badge-outline-success">Field is optional</span>
                                        </th>


                                    </tr>



                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>

  
    <script>
    const radioButtons = document.querySelectorAll('input[name="company_shipping_type"]');

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
    var csvFileData = [

        ['Ahmed', 'Karachi', 'Ali', '0332-1234567', 'xyz', 'xyz@gmail.com', '1', '1', '1', '15', '30'],
    ];

    //create a user-defined function to download CSV file
    function download_csv_file() {

        //define the heading for each row of the data
        var csv =
            'Name,City,Contact Person,Contact No,Address,Email,NTN,STRN,Terms of Payment,Discount (%),Credit Days \n';

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
                '<option>Select Option</option>' +
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
            $('#display_note_invoice').val('no');
            // Make the select element readonly
            $('#display_note_invoice').prop('disabled', true);
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
        if(dataType != "employee"){
            $('#employee_id').prop('disabled', true);

        }
        // console.log(dataType); 
    }
    </script>
    <script src="{{URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
  