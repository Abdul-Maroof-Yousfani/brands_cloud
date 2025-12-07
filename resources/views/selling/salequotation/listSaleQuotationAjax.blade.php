<?php
use App\Helpers\CommonHelper;
?>
@foreach ($sale_quotations as $sale_quotation)
    <tr>
        <td class="text-center">{{ $sale_quotation->quotation_no }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($sale_quotation->quotation_date) }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($sale_quotation->q_valid_up_to) }}</td>
        <td class="text-center">{{ $sale_quotation->revision_no }}</td>
        <td class="text-center">
            @if($sale_quotation->customer_type == 'customer')
            {{ (!empty(CommonHelper::byers_name($sale_quotation->customer_id))) ? strtoupper(CommonHelper::byers_name($sale_quotation->customer_id)->name) : '' }}
            @endif
            @if($sale_quotation->customer_type == 'prospect')
            {{ (!empty(CommonHelper::get_prospect($sale_quotation->prospect_id))) ?  strtoupper(CommonHelper::get_prospect($sale_quotation->prospect_id)->company_name) : ''}}

          @endif
            
          </td>
        <td>
            @if($sale_quotation->so_status == 0)
                Pending
            @elseif($sale_quotation->so_status ==  2)
                Draft
            @else
              Sale Order Created	
            @endif

        </td>
        <td>
       
           @if($sale_quotation->approved_status == '0')
            Pending For Approve
           @elseif($sale_quotation->approved_status == '1')
            Approved
           @elseif($sale_quotation->approved_status == '3')
            rejected
           @endif
    
        </td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"
                        type="button" data-toggle="dropdown"
                        aria-expanded="false">
                    ...
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="btn btn-sm btn-success" onclick="showDetailModelOneParamerter('saleQuotation/viewSaleQuotation/{{$sale_quotation->id}}',{{$sale_quotation->id}},'View Sale Quotation')">
                            <i class="fa fa-eye" aria-hidden="true"></i> View
                        </a>
                        <a class="btn btn-sm btn-infoo" onclick="showDetailModelOneParamerter('saleQuotation/viewSaleQuotationPrint/{{$sale_quotation->id}}',{{$sale_quotation->id}},'View Sale Quotation ')">
                            <i class="fa fa-eye" aria-hidden="true"></i> Print
                        </a>
                        @if($sale_quotation->approved_status == '0')
                        <a href="{{route('editSaleQuotation',$sale_quotation->id)}}"   
                            class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                        @endif
                        @if($sale_quotation->so_status == 0)
                                @if($sale_quotation->approved_status == '0')
                                <a href="{{route('approveSaleQuoatation',$sale_quotation->id)}}" class="btn btn-sm btn-primary"><i class="fa fa-trash-o" aria-hidden="true"></i> Approve</a>
                                <a href="{{route('rejectSaleQuoatation',$sale_quotation->id)}}" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Reject </a>
                                @endif
                        @else
                                <a onclick="removeDraft('{{$sale_quotation->id}}')" class="btn btn-sm btn-primary"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove Draft </a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
    </tr>

@endforeach