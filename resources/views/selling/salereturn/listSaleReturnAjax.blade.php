 <table class="userlittab table table-bordered sf-table-list">
     <thead>
         <tr>
             <th class="text-center col-md-1">So No.</th>
             <th class="text-center col-md-4">Customer Name</th>
             <th class="text-center col-md-1">Order Date</th>
             <th class="text-center col-md-1">Amount</th>
             <th class="text-center col-md-1">Approval Status</th>
             <th class="text-center col-md-2">Status</th>
             <th class="text-center col-md-2">Action</th>
         </tr>
     </thead> 
     <tbody id="data">
     
         @if (count($sale_orders) != 0)
             @foreach ($sale_orders as $sale_order)
                 <tr>
                     <td class="text-center">{{ $sale_order->so_no }}</td>
                     <td>{{ $sale_order->name }}</td>
                     <td class="text-center">{{ \App\Helpers\CommonHelper::changeDateFormat($sale_order->so_date) }}
                     </td>
                     <td class="text-right">{{ number_format($sale_order->sale_taxes_amount_total, 2) }}</td>
                     <!-- <td class="text-right">{{ number_format($sale_order->total_amount_after_sale_tax, 2) }}</td> -->
                     <td>
                         @if ($sale_order->status == 1)
                             Approved
                         @else
                             Not Approved
                         @endif
                     </td>
                     <td>
                         @if ($sale_order->delivery_note_status == 1 && $sale_order->dn_approve == 1)
                             Delivery Note Approved
                         @elseif($sale_order->delivery_note_status == 1)
                             Delivery Note Created
                         @else
                             New Sale Order
                         @endif
                     </td>
                     <td class="text-center">
                         <div class="dropdown">

                             {{-- <a class="btn btn-sm btn-success" href="{{route('viewSaleOrder', $sale_order->id)}}" target="_blank">
                            <i class="fa fa-eye" aria-hidden="true"></i> View
                        </a> --}}
                             <a class="btn btn-xs btn-success"
                                 onclick="showDetailModelOneParamerter('selling/viewSaleReturnPrint/{{ $sale_order->id }}',{{ $sale_order->id }},'View Sale Return ')"
                                 target="_blank">
                                 <i class="fa fa-eye" aria-hidden="true"></i> View
                             </a>
                             {{-- <a class="btn btn-sm btn-infoo" href="{{route('saleOrderSectionA', $sale_order->id)}}" target="_blank">
                            <i class="fa fa-eye" aria-hidden="true"></i> Section A / B
                        </a> --}}
                             <a href="{{ route('editSaleReturn', $sale_order->id) }}" class="btn btn-xs btn-warning "
                                 target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                           
                                 <!-- <a href="{{ route('deleteSaleOrder', $sale_order->id) }}" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"
                                     aria-hidden="true"></i> Delete</a> -->

                                     <a href="{{ route('deleteSaleReturn', $sale_order->id) }}"
                                            class="btn btn-xs btn-danger"
                                            onclick="return confirm('Are you sure you want to deete this sale order?')">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                            </a>

                             </li>
                             </ul>
                         </div>
                     </td>
                 </tr>
             @endforeach
         @else
             <tr>
                 <td colspan="10">
                    <div class="alert alert-warning py-5">No record found</div>
                 </td>
             </tr>
         @endif
     </tbody>
 </table>
 <div class="row d-flex" id="paginationLinks">
     <div class="col-md-6">
         <p> Showing {{ $sale_orders->firstItem() }} to {{ $sale_orders->lastItem() }} of {{ $sale_orders->total() }}
             entries</p>
     </div>
     <div class="col-md-6 text-right">
         <div id="">
             {{ $sale_orders->links() }}
         </div>
     </div>
 </div>
