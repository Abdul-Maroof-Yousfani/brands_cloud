@extends('layouts.default')

@section('content')
@include('select2')
<?php
use App\Helpers\CommonHelper;
$quotation_no =CommonHelper::generateUniquePosNo('sale_quotations','revision_no','REV');
?>
<style>
    /* input#item_description {
    width: 180px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    font-size: 16px;
}
.my-lab label {
    padding-top:0px; 
} */
td.diswrp span {
    width: 233px !important;
}
td.diswrap span.select2.select2-container.select2-container--default.select2-container--focus {
    width: 200px !important;
}
td.diswrp span b 
{
    right: 10px;
}
.m-tab tr td span b {
    right: 10px !important;
    left: inherit !important;
}
.m-tab tr td label{
display:block;
}
.m-tab tr td span {
    font-size: 8px;
    width: 200px !important;
}

element.style {
  
}
@media (min-width: 992px)
{
.col-md-2 {
 
}
}

</style>

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sale Quotation</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
             <!-- <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>  -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <form action="{{route('saleQuotaionStore')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" value="1" name="revison_status" >
                    <input type="hidden" name="quoatation_id" value="{{$salequoatation->id}}" >
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid bor-bo">
                           <h2 class="subHeadingLabelClass">Edit Sale Quotation</h2>
                        </div>
                                <div class="">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            
                                           
                                            <div class="col-md-12 bor-bo">

                                            <div class="col-md-2">
                                                    <h2>Quotation Details</h2>
                                                </div>
                                                <div class="col-md-10">
                                                    {{-- <div class="col-md-3">
                                                        <label for="">Prospect Company </label>
                                                        <select class="form-control" name="prospect_id" id="">
                                                            <option value="">Select Prospect</option>
                                                            @foreach(CommonHelper::get_all_prospect() as $porspect)

                                                            <option value="{{$porspect->id}}">{{$porspect->company_name}}</option>
                                                            @endforeach
                                                        </select>
                                                     
                                                    </div> --}}
                                                    <div class="col-md-3">
                                                        <label for="">Quotation Number</label>
                                                        <input type="text" name="quotation_no" value="{{$salequoatation->quotation_no}}" readonly class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Quotation Date</label>
                                                        <input type="date" name="quotation_date" value="{{$salequoatation->quotation_date}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Quotation Valid Up To</label>
                                                        <input type="date" name="valid_up_to" value="{{$salequoatation->q_valid_up_to}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Revision No</label>
                                                        <input readonly type="text" value="REV-00{{$salequoatation->revision_count+1}}" name="revision" class="form-control">
                                                   <input type="hidden" name="revision_count" value="{{$salequoatation->revision_count+1}}" id="">
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 bor-bo">
                                                <div class="col-md-2">
                                                    
                                                   <input type="checkbox" id="customer" @if($salequoatation->customer_type == 'customer' ) checked @endif  name="customer_type" value="customer">
                                                   <label for="customer">Customer</label>
                                                   <input type="checkbox" id="prospect"  @if($salequoatation->customer_type == 'prospect' ) checked @endif name="customer_type" value="prospect">
                                                   <label for="prospect">Prospect</label>
                                                </div>

                                                <div class="col-md-10" id="prospect_details" @if($salequoatation->customer_type == 'customer' ) hidden @endif  > 
                                                    <div class="col-md-3">
                                                        <label for="">Prospect Company </label>
                                                        <select class="form-control" onchange="getContactByprospect(this)" name="prospect_id" id="prospect_id">
                                                            <option value="">Select Prospect</option>
                                                            @foreach(CommonHelper::get_all_prospect() as $porspect)
                                                            <option @if($salequoatation->prospect_id == $porspect->id) selected  @endif value="{{$porspect->id}}">{{$porspect->company_name}}</option>
                                                            @endforeach
                                                        </select>
                                                     
                                                    </div>
                                                 
                                                    <div class="col-md-3">
                                                        <label for=""> Contact Name</label>
                                                        <input type="text" class="form-control" id="contact_name">
                                                    </div>
                                                    <div class="col-md-3">
                                                     <label for="">Contact Number </label>  
                                                     <input type="text" class="form-control" id="contact_number">

                                                     </div>
                                                     <div class="col-md-3">
                                                        <label for="">Contact Email</label>
                                                        <input type="text" class="form-control" id="contact_email">
                                                     </div>
                                                    

                                                </div>
                                
                                                <div class="col-md-10" id="customer_details" @if($salequoatation->customer_type == 'prospect' ) hidden @endif>
                                                    <div class="col-md-3">
                                                        <label for="">Customer</label>
                                                       <select name="customer_id" onchange="get_customer_details(this)" class="form-control" id="customer_id">
                                                           <option value="">Select Customer</option>
                                                        @foreach (CommonHelper::get_customer() as $customer)
                                                            <option @if($salequoatation->customer_id == $customer->id) selected @endif  value="{{$customer->id}}">{{$customer->name}}</option>
                                                        @endforeach
                                                       </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Customer Representative Name</label>
                                                        <input type="text" name="representative_name" id="representative_name" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Customer Code</label>
                                                        <input type="text" name="customer_code" id="customer_code" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Customer Name</label>
                                                        <input type="text" name="customer_name" id="customer_name" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Customer Address</label>
                                                        <input type="text" name="customer_address" id="customer_address" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">City</label>
                                                        <input type="text" name="customer_city" id="customer_city" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Country</label>
                                                        <input type="text" name="customer_country" id="customer_country" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">STN No</label>
                                                        <input type="text" name="customer_stn" id="customer_stn" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">NTN No</label>
                                                        <input type="text" name="customer_ntn"  id="customer_ntn" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 bor-bo">
                                                <div class="col-md-2">
                                                    <h2>Date and Currency</h2>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="col-md-3">
                                                        <label for="">Currency</label>
                                                        <select name="currency_id" class="form-control" id="currency_id">
                                                        <option value="">Select Currency </option>
                                                        @foreach(CommonHelper::get_curreny() as $currency)
                                                            <option @if($salequoatation->currency_id == $currency->id) selected @endif value="{{$currency->id}}">{{ $currency->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Inquiry Reference Date</label>
                                                        <input type="date" value="{{$salequoatation->inquiry_reference_date}}"  name="inquiry_refer_date" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Exchange Rate</label>
                                                        <input type="text" value="{{$salequoatation->exchange_rate}}" name="exchange_rate" value="1" id="exchange_rate" class="form-control">
                                                        {{-- <a href="#" class="btn-add" onclick="createContact('contact/createContact','','Add Contact','')">+</a> --}}
                                                    </div>
                                              
                                                </div>
                                            </div>

                                            <div class="col-md-12 bor-bo">
                                                <div class="col-md-2">
                                                    <h2>Sales Details</h2>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="col-md-3">
                                                        <label for="">Sales Pool</label>
                                                        <select name="sale_pool" class="form-control" id="">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_table_data('sales_pools') as $item)
                                                            <option  @if($salequoatation->sales_poal == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                           
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Type</label>
                                                        <select name="type" class="form-control" id="">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_table_data('sales_types') as $item)
                                                            <option @if($salequoatation->type == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Subject Line</label>
                                                        <input type="text"  value="{{$salequoatation->subject_line}}" name="subject_line" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Storage Dimension</label>
                                                        <select name="storgae_dimension" class="form-control" id="">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_table_data('storage_dimentions') as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Sales Tax Group</label>
                                                        <select onchange="saletax(this)" name="sale_taxt_group" class="form-control" id="sale_taxt_group">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_table_data('sales_tax_groups') as $item)
                                                            <option @if($salequoatation->sale_tax_group == $item->id) selected @endif value="{{$item->id}},{{$item->rate}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <label for="">Sales Tax rate</label>
                                                      <input type="text" value="{{$salequoatation->sales_tax_rate}}" class="form-control" readonly name="sale_tax_rate" id="sale_tax_rate">
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <label for="">Mode of Delivery</label>
                                                        <select name="mode_of_delivry" class="form-control" id="">
                                                            <option value="">Select </option>
                                                            @foreach(CommonHelper::get_table_data('mode_deliveries') as $item)
                                                            <option @if($salequoatation->mode_of_delivery == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                         
                                                        </select>
                                                    </div>
                                                    {{-- <div class="col-md-3">
                                                        <label for="">Delivery Terms</label>
                                                        <select name="delivery_term" class="form-control" id="">
                                                            <option value="1">01</option>
                                                            <option value="2">02</option>
                                                            <option value="3">03</option>
                                                        </select>
                                                    </div> --}}
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-12 bor-bo">
                                                <div class="col-md-2">
                                                  &nbsp;
                                                </div>
                                                <div class="col-md-10">
                                                            <div class="col-md-12">
                                                                <label for="">Terms & Condition:</label>
                                                                <textarea rows="6" type="text" name="term_condition" id="terms_condition" class="form-control">
                                                                    {{$salequoatation->terms_condition}}
                                                                </textarea>
                                                            </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 bor-bo">
                                                <div class="col-md-2">
                                                    <h2>Quotation Created By</h2>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="col-md-3">
                                                        <label for="">Name</label>
                                                        <input type="text" name="created_by"  value="{{$salequoatation->created_by}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Designation</label>
                                                        <input type="text" name="designation" value="{{$salequoatation->designation}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Company Name</label>
                                                        <input type="text" name="company_name"  value="{{$salequoatation->company_name}}" class="form-control">
                                                    </div> 
                                                </div>
                                            </div>


                                            <div class="col-md-12 bor-bo">
                                                <div class="col-md-12">
                                                    <h2>Quotation Chart</h2>
                                                </div>
                                                <div class="col-md-12" id="AppnedHtml">
                                                    <table class="m-tab main" style="width: 100%" >
                                                        @php
                                                        $grand_total =0;
                                                        @endphp
                                                        @foreach($salequoatationdata as $qouate)
                                                        <tr>
                                                            <td class="diswrp">
                                                                <label for="">Category</label>
                                                                <select onchange="get_sub_item_by_id(this)"  required name="category[]" class="form-control select2 requiredField category">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_sub_category()->get() as $category)
                                                                        <option  @if(CommonHelper::get_category_by_itemid($qouate->item_id) == $category->id) selected @endif
                                                                            value="{{ $category->id }}">
                                                                            {{ $category->sub_category_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>  
                                                            </td>
                                                            <td class="diswrp">
                                                                <label class="dis" for="">Product</label>
                                                                <select onchange="item_change(this)" required class="form-control select2  requiredField item_id" name="item_id[]" id="item_id">
                                                                    <option value="">Select Product</option>
                                                                    @foreach(CommonHelper::get_all_subitem() as $item)
                                                                    <option @if($qouate->item_id == $item->id) selected @endif value="{{$item->id}}">{{$item->sub_ic}}</option> 
                                                                    @endforeach
                                                                </select>     
                                                            </td>
                                                         
                                                           
                                                            <td>
                                                                <label for="">UOM</label>
                                                                <input readonly type="text" required  value="{{$qouate->uom_id}}" id="uom" name="uom[]" class="form-control requiredField">        
                                                            </td>
                                                            <td>
                                                                <label for="" title="delivery ">Deliv: Type</label>
                                                                <select onchange="deliverytype(this)" class="form-control" name="delivery_type[]" id="">
    
                                                                    <option value="">Select</option>
                                                                    <option @if($qouate->delivery_type == 1) selected @endif value="1">Bundle</option>
                                                                    <option @if($qouate->delivery_type == 2) selected @endif value="2">Straight</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <label for="">Len:Bundle</label>
                                                                <input type="text" value="{{$qouate->bundle_length}}" class="form-control bundle_length" readonly name="bundle_length[]" id="">
                                                            </td>
                                                            <td>
                                                                <label for="">length</label>
                                                                <input type="text" value="{{$qouate->qty}}" onkeyup="calculation_amount()" name="qty[]" id="qty" class="form-control">        
                                                            </td>
                                                           
                                                            <td>
                                                                <label for="">Unit Price 
                                                                    {{-- <span>(with & without GST)</span> --}}
                                                                </label>
                                                                <input type="text" value="{{$qouate->unit_price}}" onkeyup="calculation_amount()" name="unit_price[]" id="unit_price" class="form-control">        
                                                            </td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <label for="">Item Description</label>
                                                                <textarea id="item_description" name="item_description[]" class="form-control item_description " >   
                                                                    {!!$qouate->item_description!!}    
                                                                </textarea> 
                                                            </td>
                                                            <td>
                                                                <label for="">Total Value 
                                                                    {{-- <span>(with & without GST)</span> --}}
                                                                </label>
                                                                <input type="text" readonly name="total[]" value="{{$qouate->total_amount}}" id="total" class="form-control">        
                                                            </td>
                                                            @php
                                                            $grand_total += $qouate->total_amount;   
                                                            @endphp
                                                            <td>
                                                                <label for="">Action</label>
                                                                <a href="#"  class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                                               
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    <div class="m-tab main"  id="RemoveRows2">
                                                   
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 padtb">

                                                <div class="col-md-8"></div>    
                                                <div class="col-md-4 my-lab">
                                                    <label for="">
                                                     Total Amount 
                                                    </label>
                                                    <input type="text" readonly value="{{$grand_total }}" name="grand_total" id="grand_total" class="form-control">
                                                    <label for="">
                                                      Total Tax 
                                                    </label>
                                                    @php 
                                                    $tax_amount = $grand_total/100*$salequoatation->sales_tax_rate;
                                                    @endphp
                                                    
                                                       <input type="text" readonly value="{{ $tax_amount}}" name="total_tax" id="total_tax" class="form-control">
                                                   
                                                    <label for="">
                                                      Total Amount With Tax
                                                    </label>
                                                    <input type="text" readonly value="{{$salequoatation->total_amount_after_sale_tax}}" name="grand_total_with_tax" id="grand_total_with_tax" class="form-control">
                                                
                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Send</button>
                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                    <label for="">
                                                        Save As Draft
                                                        <input type="checkbox" name="save_as_draft" class="form-control" value="1" id="">
                                                    </label>

                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function(){
            CKEDITOR.replace('terms_condition');
            $('.item_description').each(function () {
    CKEDITOR.replace(this.id, {
        toolbar: [],
        allowedContent: 'p h1 h2 strong'
    });
});

           let instance = document.getElementById('prospect_id') ;
           getContactByprospect(instance);
           let instance2 = document.getElementById('customer_id') ;
           get_customer_details(instance2);


            // $("#prospect_details").hide();
                $('.item_id').each(function(){
                $('.item_id').select2();
                get_all_currenecy();
            });
                $('.category').each(function(){
                $('.category').select2();
                get_all_currenecy();
            });
        });

function deliverytype(instance)
{
    id  = instance.value;
    console.log(id);
    if(id == 1)
    {
        $(instance).closest('.main').find('.bundle_length').prop('readonly', false);

    }else{
        $(instance).closest('.main').find('.bundle_length').prop('readonly', true);
    }
}


        $("input[name=customer_type]").on("click", function() {
  $("input[name=customer_type]").prop("checked", false);
  $(this).prop("checked", true);
});
$('#sale_taxt_group').on('change',function(){
    calculation_amount();
});
$("input[name=customer_type]:checkbox").click(function() {

    if($(this).attr("value")=="customer") {
    
        $("#prospect_details").hide();
        $("#customer_details").show();
        $("#customer_details :input").prop("disabled", false);
        $("#prospect_details :input").prop("disabled", true);
    }
    if($(this).attr("value")=="prospect") {
        $("#prospect_details").show();
        $("#customer_details").hide();
        $("#prospect_details :input").prop("disabled", false);
        $("#customer_details :input").prop("disabled", true);
    }
});
 function saletax(instance)
 {

    var value = $(instance).val();
    let excet_value =  value.split(',');
    $('#sale_tax_rate').val(excet_value[1])

 }
    </script>
    <script type="text/javascript">
      
        var Counter = 3;
       function AddMoreDetails()
       {
        $('#AppnedHtml').append(`<table class="m-tab main" id="RemoveRows${Counter}">
                                                        <tr>
                                                            <td class="diswrp">
                                                                <label for="">Category</label>
                                                                <select onchange="get_sub_item_by_id(this)"  required name="category[]" class="form-control select2 category">
                                                                    <option value="">Select</option>
                                                                    @foreach(CommonHelper::get_sub_category()->get() as $category )

                                                                        <option value="{{$category->id}}">{{$category->sub_category_name}} </option>
                                                                    @endforeach                                                                </select>                                                            
                                                            </td>

                                                            <td class="diswrap">
                                                                <label for="">S No</label>
                                                                <select required onchange="item_change(this)" class="form-control item_id" name="item_id[]" id="item_id">
                                                                    @foreach(CommonHelper::get_all_subitem() as $item)
                                                                    <option value="{{$item->id}}">{{$item->sub_ic}}</option> 
                                                                    @endforeach
                                                                </select>            
                                                            </td>
                                                           
                                                            
                                                            <td>
                                                                <label for="">UOM</label>
                                                                <input readonly id="uom" type="text" name="uom[]" class="form-control">        
                                                            </td>
                                                            <td>
                                                               <label for="" title="delivery ">Deliv: Type</label>
                                                                <select onchange="deliverytype(this)" class="form-control" name="delivery_type[]" id="">
                                                                    <option value="">Select</option>
                                                                    <option value="1">Bundle</option>
                                                                    <option value="2">Straight</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                    <label for="">Len:Bundle</label>
                                                                    <input type="text" readonly class="form-control bundle_length" name="bundle_length[]" id="">
                                                                </td>
                                                            <td>
                                                                <label for="">length</label>
                                                                <input type="text" name="qty[]" onkeyup="calculation_amount()" id="qty" class="form-control">        
                                                            </td>
                                                       
                                                            <td>
                                                                <label for="">Unit Price</span></label>
                                                                <input type="text" name="unit_price[]" onkeyup="calculation_amount()" id="unit_price" class="form-control">        
                                                            </td>
                                                           
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <label for="">Item Description</label>
                                                                <textarea id="item_description" name="item_description[]" class="form-control">   
                                                                    
                                                                </textarea>      
                                                            </td>
                                                            <td>
                                                                <label for="">Total Value</label>
                                                                <input type="text" readonly  name="total[]" id="total" class="form-control">        
                                                            </td>
                                                            <td>
                                                                <label for="">Action</label>
                                                                <a href="#"  class="btn  btn-danger" onclick="RemoveSection(${Counter})"> <i class="fa fa-trash-o" aria-hidden="true"></i> </a>
                                                              
                                                            </td>
                                                        </tr>
                                                    </table>`);
                                                    Counter++;
                                                    $('.item_id').each(function(){
                            $('.item_id').select2();
                            get_all_currenecy();
                        });
                                                                calculation_amount();
       }
       function RemoveSection(row) {
        var element = document.getElementById("RemoveRows" + row);
        if (element) {
            element.parentNode.removeChild(element);
        }
        Counter--;
        calculation_amount();
       }
       </script>
<script>
    function get_customer_details(instance)
    {
        var id = instance.value;
        console.log(id)
        $.ajax({
                url: '<?php echo url('/')?>/customer/get_customer',
                type: 'Get',
                data: {id:id},
             success: function (data) {
               $('#customer_ntn').val(data.cnic_ntn);
               $('#customer_stn').val(data.strn);
               $('#customer_country').val(data.country);
               $('#customer_city').val(data.city);
               $('#customer_address').val(data.address);
               $('#customer_name').val(data.name);
               $('#customer_code').val(data.customer_code);
               $('#representative_name').val(data.name);
             
             }
            });

    }

    function item_change(datas)
    {
        var id = datas.value;
        $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
                type: 'Get',
                data: {id:id},
             success: function (data) {
                $(datas).closest('.main').find('#item_code').val(data.item_code);
                $(datas).closest('.main').find('#item_description').val(data.description);
                $(datas).closest('.main').find('#uom').val(data.uom_name);
             }
            });

    }
    function calculation_amount()
    {
        var grad_total = 0;
        var tax = $('#sale_tax_rate').val();
        var rat_ex = $('#exchange_rate').val();
        var sale_tax =  tax? tax : 0;
        var exchange_rate = rat_ex? rat_ex:1;
        var befor_tax = 0;
        var all_tax= 0;
        var actual_qty = 0;
        var actual_rate = 0;

        $('.item_id').each(function(){
          var   total = 0;
           var actual_rate =  $(this).closest('.main').find('#unit_price').val();
           var actual_qty =  $(this).closest('.main').find('#qty').val();
           var rate =  actual_rate? actual_rate : 0;
           var qty =  actual_qty? actual_qty : 0;
            total = parseFloat(qty) * parseFloat(rate);

          var sale_tax_amount =  total/100*sale_tax;

           grad_total +=  total + sale_tax_amount;
           befor_tax += total;
           all_tax += sale_tax_amount;
            $(this).closest('.main').find('#total').val(total);
        });

        $('#total_tax').val(all_tax);
        $('#grand_total').val(befor_tax);
        $('#grand_total_with_tax').val(grad_total);
    }


    function get_all_currenecy()
    {
        var id =1;
        $.ajax({
                url: "{{route('get_all_currenecy')}}",
                type: 'Get',
                data: {id:id},
             success: function (data) {
              $('#currency_id').empty();
              $('#currency_id').append(data);

             }
            });

    }
    function  get_sub_item_by_id(instance)
	{


		var category= instance.value;
	
        $(instance).closest('.main').find('#item_id').empty();
		$.ajax({
			url: '{{ url("/getSubItemByCategory") }}',
			type: 'Get',
			data: {category: category},
			success: function (response) {
                $(instance).closest('.main').find('#item_id').append(response);
			
			}
		});
	}
    function  getContactByprospect(instance)
	{


		var prospect_id = instance.value;
		$.ajax({
			url: '{{ url("/getContactByprospect") }}',
			type: 'Get',
			data: {prospect_id: prospect_id},
			success: function (response) {
             $('#contact_name').val(response.first_name);
             $('#contact_number').val(response.cell);
             $('#contact_email').val(response.email);
			
			}
		});
	}
</script>
    

@endsection