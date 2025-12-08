 <style>
 .pagination{float:right;}
.nowrap{white-space:nowrap;}
.text-right-amount{text-align:right !important;}
.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td{width:22% !important;padding:8px 4px !important;background:#ddd;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{background-image:url(data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E);background-repeat:no-repeat;background-position:center;background-size:12px;color:#6e6b7b;width:5% !important;height:14px;content:'';right:0.3rem;top:1.3rem;}
 </style>
 <div class="table-responsive">
     <table class="userlittab table table-bordered sf-table-list" id="data-table">
        <thead>
            <tr>
                <th style="width:5% !important;"  class="text-center ">So No</th>
                <th  class="text-center ">Order No</th>
                <th  class="text-center ">Customer Name</th>
                <th  class="text-center ">Order Date</th>
                <th  class="text-center ">Amount</th>
                <th  class="text-center ">Status</th>
                <th  class="text-center ">Note</th>
                <th  class="text-center ">Action</th>
            </tr>
        </thead>
    
         <tbody id="data">
             @if (count($sale_orders) != 0)

            <?php $counter = 1; ?>
                 @foreach ($sale_orders as $sale_order)
                     <tr>
                         <td class="text-center">{{ $counter++ }}</td>
                         <td class="text-center">{{ $sale_order->so_no }}</td>
                         <td>{{ $sale_order->name }}</td>
                         <!-- <td class="text-center">{{ \Carbon\Carbon::parse($sale_order->so_date)->format('d-M-Y') }}
                         </td> -->
                        <!-- <td class="text-center">
                                {{ \Carbon\Carbon::parse($sale_order->timestamp)->format('d-M-Y H:i:s') }}
                            </td> -->
                            <!-- <td class="text-center nowrap">
                            {{ \Carbon\Carbon::parse($sale_order->timestamp)->format('d-M-Y H:i:s') }}
                        </td> -->
    
                        <td class="nowrap">
                            {{ \Carbon\Carbon::parse($sale_order->timestamp)->format('d-M-Y') }}<br>
                            {{ \Carbon\Carbon::parse($sale_order->timestamp)->format('h:i:s A') }}
    
                        </td>
    
    
                                            <td class="text-left-amount">
                            {{ number_format($sale_order->sale_taxes_amount_total, 0) }}
                        </td>
    
                         <!-- <td class="text-right">{{ number_format($sale_order->total_amount_after_sale_tax, 0) }}</td> -->
                         <td>
                             @if ($sale_order->status == 1)
                                 Approved
                             @else
                                 Not Approved
                             @endif
                         </td>
                         <!-- <td>
                             @if ($sale_order->delivery_note_status == 1 && $sale_order->dn_approve == 1)
                                 Delivery Note Approved
                             @elseif($sale_order->delivery_note_status == 1)
                                 Delivery Note Created
                             @else
                                 New Sale Order
                             @endif
                         </td> -->
                         <td>{{$sale_order->remark}}</td>
                         <td class="text-center">
                             <div class="dropdown">
    
                                 {{-- <a class="btn btn-sm btn-success" href="{{route('viewSaleOrder', $sale_order->id)}}" target="_blank">
                                <i class="fa fa-eye" aria-hidden="true"></i> View
                            </a> --}}
                                 <a class="btn btn-xs btn-success"
                                     onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{ $sale_order->id }}',{{ $sale_order->id }},'View Sale Order ')"
                                     target="_blank">
                                     <i class="fa fa-eye" aria-hidden="true"></i> View
                                 </a>
                                 {{-- <a class="btn btn-sm btn-infoo" href="{{route('saleOrderSectionA', $sale_order->id)}}" target="_blank">
                                <i class="fa fa-eye" aria-hidden="true"></i> Section A / B
                            </a> --}}
                                 <a href="{{ route('editSaleOrder', $sale_order->id) }}" class="btn btn-xs btn-warning "
                                     target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
    
                                 <!-- <a href="{{ route('deleteSaleOrder', $sale_order->id) }}" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"
                                         aria-hidden="true"></i> Delete</a> -->
    
                                 <a href="{{ route('deleteSaleOrder', $sale_order->id) }}" class="btn btn-xs btn-danger"
                                     onclick="return confirm('Are you sure you want to delete this sale order?')">
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
 </div>


 <script>
     // data-table
$("#data-table").DataTable({
    ordering: true,
    searching: true,
    paging: false,
    info: false,
    autoWidth: false, // prevent DataTables from auto-calculating width
    columnDefs: [
        { width: "5%", targets: 0 },
        { width: "30%", targets: 1 },
        { width: "15%", targets: 2 },
        { width: "10%", targets: 3 },
        { width: "10%", targets: 4 },
        { width: "15%", targets: 5 },
        { width: "15%", targets: 6 }
    ]
});

 </script>
