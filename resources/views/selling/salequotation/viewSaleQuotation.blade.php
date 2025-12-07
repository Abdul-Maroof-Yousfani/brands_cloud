
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\SalesHelper;
    $counter = 1;
    $total_amount = 0;
    ?>

    <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="">
                <div class="dp_sdw" id="printReport">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="headquid">
                                                <h1 class="subHeadingLabelClass"> Sale Quotation</h1>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right hidden-print">
                                            <h1>
                                               
                                            
                                            <a type="button" class="btn btn-primary" onclick="showDetailModelOneParamerter('saleQuotation/viewSaleQuotationPrint/{{ $sale_quotations[0]->sale_quotations_id }}', {{ $sale_quotations[0]->sale_quotations_id }}, 'View Sale Quotation')"
                                           >Print</a>
                                            </h1>

                                        </div>
                                    </div>

                                    <div class='slist'>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="headquid">
                                                    <h2 class="subHeadingLabelClass">Quotation Details</h2>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Quotation Number</h5>
                                                            <p>{{ $sale_quotations[0]->quotation_no }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Quotation Date</h5>
                                                            <p>{{ CommonHelper::changeDateFormat($sale_quotations[0]->quotation_date) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Quotation Valid Up To</h5>
                                                            <p>{{ CommonHelper::changeDateFormat($sale_quotations[0]->q_valid_up_to) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Revision Number</h5>
                                                            <p>{{ $sale_quotations[0]->revision_no }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($sale_quotations[0]->type_customer == 'customer')
                                        <div class='slist'>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="headquid">
                                                        <h2 class="subHeadingLabelClass">Customer Details</h2>
                                                    </div>
                                                </div>

                                                <div class="col-md-10">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>Customer Name</h5>
                                                                <p>{{ $sale_quotations[0]->customer_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>Customer Code</h5>
                                                                <p>{{ $sale_quotations[0]->customer_code }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>Customer Address</h5>
                                                                <p>{{ $sale_quotations[0]->address }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>City</h5>
                                                                @if(!empty($sale_quotations[0]->city))
                                                                <p>{{ CommonHelper::get_all_cities_by_id($sale_quotations[0]->city)->name }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>Country</h5>
                                                                @if(!empty($sale_quotations[0]->country))
                                                                <p>{{CommonHelper::get_all_country_by_id($sale_quotations[0]->country)->name }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>STN No</h5>
                                                                <p>{{ $sale_quotations[0]->strn }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>NTN No</h5>
                                                                <p>{{ $sale_quotations[0]->NTNNumber }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($sale_quotations[0]->type_customer == 'prospect')
                                        <div class='slist'>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="headquid">
                                                        <h2 class="subHeadingLabelClass">Prospect Details</h2>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>Company Name</h5>
                                                                <p>{{ $sale_quotations[0]->prospect_company_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="slis-ctn">
                                                                <h5>Company Name</h5>
                                                                <p>{{ $sale_quotations[0]->prospect_company_address }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class='slist'>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="headquid">
                                                    <h2 class="subHeadingLabelClass">Date and Currency</h2>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Currency</h5>
                                                            <p>{{ $sale_quotations[0]->curreny ?? 'PKR' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Inquiry Reference Date</h5>
                                                            <p>{{ CommonHelper::changeDateFormat($sale_quotations[0]->inquiry_reference_date) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Exchange Rate</h5>
                                                            <p>{{ $sale_quotations[0]->exchange_rate }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='slist'>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="headquid">
                                                    <h2 class="subHeadingLabelClass">Sales Details</h2>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Sales Pool</h5>
                                                            <p>{{ $sale_quotations[0]->sales_pool_name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Type</h5>
                                                            <p>{{ $sale_quotations[0]->sales_type_name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Subject Line</h5>
                                                            <p>{{ $sale_quotations[0]->subject_line }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Storage Dimension</h5>
                                                            <p>{{ $sale_quotations[0]->storage_dimention_name }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tems_sec">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="slis-ctn">
                                                                <h5>Sales Tax Group</h5>
                                                                <p>{{ $sale_quotations[0]->sales_tax_group_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="slis-ctn">
                                                                <h5>Made Of Delivery</h5>
                                                                <p>{{ SalesHelper::deliverMode($sale_quotations[0]->mode_of_delivery) }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="slis-ctn">
                                                                <h5>Terms & Conditions</h5>
                                                                
                                                                {!! $sale_quotations[0]->terms_condition !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='slist'>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="headquid">
                                                    <h2 class="subHeadingLabelClass">Quotation Created By</h2>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Name</h5>
                                                            <p>{{ $sale_quotations[0]->created_by }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Designation</h5>
                                                            <p>{{ $sale_quotations[0]->designation }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="slis-ctn">
                                                            <h5>Company Name</h5>
                                                            <p>{{ $sale_quotations[0]->company_name }}</p>
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
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">Quotation Chart</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">
                                                <table class="table table-bordered sf-table-list">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">S No</th>
                                                        <th class="text-center">Item Code</th>
                                                        <th class="text-center">Item Description</th>
                                                        <th class="text-center">QTY</th>
                                                        <th class="text-center">UOM</th>
                                                        <th class="text-center">Unit Price (without GST)</th>
                                                        <th class="text-center">Total Value (without GST)</th>
                                                        <th class="text-center"> GST % </th>
                                                        <th class="text-center">Unit Price (with GST)</th>
                                                        <th class="text-center">Total Value (with GST)</th>
                                                    </tr>
                                                    </thead>
                                                    @php 
                                                    $rat_sale_tax =  CommonHelper::get_sale_tax_by_id($sale_quotations[0]->sale_tax_group);
                                                    $total_with_gst= 0;
                                                    @endphp

                                                    <tbody id="data">
                                                    @foreach($sale_quotations As $key => $value)
                                                            <?php
                                                            $total_amount += $value->total_amount;
                                                            ?>
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td class="text-left">{{ $value->item_code }}</td>
                                                            <td class="text-left">{{ $value->description }}</td>
                                                            <td class="text-right">{{ number_format($value->qty,2) }}</td>
                                                            <td class="text-center">{{ $value->uom_id }}</td>
                                                            <td class="text-right">{{ number_format($value->unit_price, 2) }}</td>
                                                            <td class="text-right">{{ number_format($value->total_amount, 2) }}</td>
                                                            @php
                                                           $tax_rat_unit_price =  $value->unit_price/100*$rat_sale_tax;
                                                               $tax_amount = $value->total_amount/100*$rat_sale_tax;
                                                               $total_with_gst +=$value->total_amount+$tax_amount;
                                                            @endphp
                                                            <td  class="text-right">
                                                                {{$rat_sale_tax}} %
                                                            </td>
                                                            <td>
                                                                {{number_format($value->unit_price+$tax_rat_unit_price,2)}}
                                                            </td>
                                                            <td class="text-right">{{ number_format( $value->total_amount+$tax_amount, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <ul class="grand">
                                                    <li>
                                                        <div class="grndctn">
                                                            <h5>Grand Total</h5>
                                                            <p>(without GST)</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="grndctn">
                                                            <h6>{{ number_format($total_amount, 2) }}</h6>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="grndctn">
                                                            <h5>Grand Total</h5>
                                                            <p>(with GST)</p>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="grndctn">
                                                            <h6>{{ number_format($total_with_gst, 2) }}</h6>
                                                        </div>
                                                    </li>
                                                </ul>
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
