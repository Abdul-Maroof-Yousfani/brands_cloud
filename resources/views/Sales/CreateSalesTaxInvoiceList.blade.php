<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

?>
@extends('layouts.default')
@section('content')
@include('select2')
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Sales Tax Invoice</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                            <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                        </div>
                    </div>
                </div>


                <div class="row" style="display:none;">



                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Customer</label>
                        <select class="form-control select2" onchange="getSobyCustomer()"  name="customer_id" id="customer_id">
                            <option value=""> Select Customer </option>
                            @foreach($Customers as $key => $value)
                                <option value="{{$value->id}}"> {{ $value->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>SO No.</label>
                        <!-- <input type="text" name="to" id="so_no" max="" value="" class="form-control" /> -->
                        <select class="form-control select2"  name="to" id="so_no">

                        </select>
                    </div>


                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                        <input type="button" value="View Filter Data" id="view-filter-data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                <span id="data"></span>

            </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const button = document.getElementById("view-filter-data");
        console.log(button.click());
    });

    function delete_record(id)
    {

        if (confirm('Are you sure you want to delete this request')) {
            $.ajax({
                url: '/pdc/deletepurchasevoucher',
                type: 'Get',
                data: {id: id},

                success: function (response) {

                    alert('Deleted');
                    $('#' + id).remove();

                }
            });
        }
        else{}
    }

    function getSobyCustomer()
    {
        $('#so_no').empty();
        let customer_id = $('#customer_id').val();
            $.ajax({
                url: "{{ route('getCustomerSaleOrders') }}", // Using route helper
                type: 'Get',
                data: {customer_id: customer_id},

                success: function (response) {
                    
                    $('#so_no').append(response);

                }
            });
        
    }

    function viewRangeWiseDataFilter()
    {

        var so_no= $('#so_no').val();
        let customer_id = $("#customer_id").val();
        var m='{{$m}}';
        $('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        $.ajax({
            url: '{{url("/sales/CreateSalesTaxInvoiceBySO")}}',
            type: 'Get',
            data: {so_no: so_no,m:m, customer_id: customer_id},

            success: function (response) {
                $('#data').html(response);


            }
        });


    }

    function sales_tax(sales_order_id,delivery_note_id,m)
    {

        var base_url='<?php echo URL::to('/'); ?>';
        window.location.href = base_url+'/sales/CreateSalesTaxInvoice?sales_order_id='+sales_order_id+'&&'+'delivery_note_id='+delivery_note_id+'&&'+'m='+m;
    }
</script>

  <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
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
                .totlas{display:flex !important;justify-content:right !important;gap:70px !important;background:#ddd !important;width:30% !important;float:right !important;padding-right:8px !important;}
                .totlas p{font-weight:bold !important;}
                .psds{display:flex !important;justify-content:right !important;gap:88px !important;}
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
        printView("PrintEmpExitInterviewList");  // Apna DIV ID yahan likho
    }
}, true);  // <-- CAPTURE MODE ENABLED (very important)
</script>
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
@endsection