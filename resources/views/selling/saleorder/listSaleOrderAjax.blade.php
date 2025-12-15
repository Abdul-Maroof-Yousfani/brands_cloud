 <style>
 .pagination{float:right;}
.nowrap{white-space:nowrap;}
.text-right-amount{text-align:right !important;}
.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td{padding:8px 4px !important;background:#ddd;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9' /%3E%3C/svg%3E") !important;background-repeat:no-repeat;background-position:center;background-size:12px;color:#6e6b7b;width:5% !important;height:14px;content:'';right:0.3rem;top:1.3rem;}
/* th.userlittab.text-center.col-sm-1.sorting_asc{width:33px !important;}
*/
 .userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{font-weight:300 !important;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{width:8px !important;height:20px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9' /%3E%3C/svg%3E") !important;}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{color:#333 !important;border:1px solid #428bca!important;background-color:white;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fff),color-stop(100%,#dcdcdc));background:-webkit-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-moz-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-ms-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-o-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:#428bca !important;width:25px !important;height:30px!important;line-height:15px;color:#fff !important;}

 </style>
 <div class="table-responsive" id="printReport">
     <table class="userlittab table table-bordered sf-table-list" id="data-table">
        <thead>
            <tr>
                 <th style="width:20px !important; text-align:center;" class="text-center sorting_asc">So No</th>
                <th  style="width: 80px !important;" class="text-center sorting_asc">Order No</th>
                <th style="width: 300px !important;"  class="text-center ">Customer Name</th>
                <th style="width: 100px !important;" class="text-center sorting_asc">Order Date</th>
                <th  style="width: 82px !important;" class="text-center ">Amount</th>
                <th   style="width: 92px !important;" class="text-center ">Status</th>
                <th style=" width:130px !important;" class="text-center ">Note</th>
                <th   style=" width:10px !important;" class="text-center ">Action</th>
            </tr>
        </thead>
    
         <tbody id="data">
             @if (count($sale_orders) != 0)

            <?php $counter = 1; ?>
                 @foreach ($sale_orders as $sale_order)
                     <tr>
                         <td style="width:50px !important; text-align:center;" class="text-center">{{ $counter++ }}</td>
                         <td class="text-center">{{ $sale_order->so_no }}</td>
                         <td><strong>{{ $sale_order->name }}</strong></td>
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
                                        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                        <ul class="dropdown-menu">
                                            <li>
    
                                                    {{-- <a class="btn btn-sm btn-success" href="{{route('viewSaleOrder', $sale_order->id)}}" target="_blank">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> View
                                                </a> --}}
                                                    <a class="btn btn-xs btn-success" onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{ $sale_order->id }}',{{ $sale_order->id }},'View Sale Order ')" target="_blank">
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
    paging: true,
    info: false,
    autoWidth: false, // prevent DataTables from auto-calculating width
});

 </script>
    <script>
    function printView(divId) {
        var element = document.getElementById(divId);
        if (!element) {
            alert("Element with ID '" + divId + "' not found!");
            return;
        }

        var content = element.innerHTML;
        var mywindow = window.open('', 'PRINT', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>Print</title>');

        // ✅ Bootstrap CSS include
        mywindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">');


        mywindow.document.write(`
            <style>
              @page{size:A4;margin:1em;}
            .table-responsive .sale_older_tab > caption + thead > tr:first-child > th,.sale_older_tab > colgroup + thead > tr:first-child > th,.sale_older_tab > thead:first-child > tr:first-child > th,.sale_older_tab > caption + thead > tr:first-child > td,.sale_older_tab > colgroup + thead > tr:first-child > td,.sale_older_tab > thead:first-child > tr:first-child > td{border-top:0;font-size:10px !important;padding:9px 5px !important;}
            .table-responsive .sale_older_tab > thead > tr > th,.sale_older_tab > tbody > tr > th,.sale_older_tab > tfoot > tr > th,.sale_older_tab > thead > tr > td,.sale_older_tab > tbody > tr > td,.table > tfoot > tr > td{padding:2px 5px !important;font-size:11px !important;border-top:1px solid #000000 !important;border-bottom:1px solid #000000 !important;border-left:1px solid #000000 !important;border-right:1px solid #000000 !important;}
            .table-responsive{height:inherit !important;}
            .sales_or{position:relative !important;height:100% !important;}
            .sgnature{position:absolute !important;bottom:0px !important;}
            p{margin:0;padding:0;font-size:13px !important;font-weight:500;}
            .mt-top{margin-top:-72px !important;}
            .sale-list.userlittab > thead > tr > td,.sale-list.userlittab > tbody > tr > td,.sale-list.userlittab > tfoot > tr > td{font-size:12px !important;text-align:left !important;}
            .sale-list.table-bordered > thead > tr > th,.sale-list.table-bordered > tbody > tr > th,.sale-list.table-bordered > tfoot > tr > th{font-size:12px !important;margin:0 !important;vertical-align:inherit !important;padding:0px 17px !important;text-align:left !important;}
            input.form-control.form-control2{margin:0 !important;}
            .totlas{display:flex;gap:70px;background:#ddd;width:37%;float:right;padding-right:8px;justify-content:space-between;}
            .totlas p{font-weight:bold !important;}
            .psds{display:flex;justify-content:right;gap:88px;font-weight:bold;}
            .psds p{font-weight:bold !important;}
            .totlass h2{font-size:13px !important;}
            .totlass{display:inline!important;background:transparent!important;margin-top:-25px!important;width:68%;float:left;}
            .col-lg-6{width:50% !important;}
            .col-lg-12{width:100% !important;}
            .col-lg-4{width:33.33333333% !important;}

            </style>
        `);
        mywindow.document.write('</head><body>');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
    }

    document.addEventListener("keydown", function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "p") {
        e.preventDefault();   // Stop default Print
        e.stopPropagation();  // Stop bubbling
        printView("printReport");  // Apna DIV ID yahan likho
    }
}, true);  // <-- CAPTURE MODE ENABLED (very important)
</script>