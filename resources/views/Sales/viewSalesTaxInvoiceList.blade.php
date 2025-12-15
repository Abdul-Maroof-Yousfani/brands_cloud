<?php
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

$view = ReuseableCode::check_rights(118);
$edit = ReuseableCode::check_rights(119);
$delete = ReuseableCode::check_rights(120);
$export = ReuseableCode::check_rights(257);

?>
@extends('layouts.default')
@section('content')
@include('select2')
<style>
 .pagination{float:right;}
.nowrap{white-space:nowrap;}
.text-right-amount{text-align:right !important;}
.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td{padding:8px 4px !important;background:#ddd;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9' /%3E%3C/svg%3E") !important;background-repeat:no-repeat;background-position:center;background-size:12px;color:#6e6b7b;width:5% !important;height:14px;content:'';right:0.3rem;top:1.3rem;}
table.dataTable tbody th,table.dataTable tbody td{padding:8px 4px !important;}
.userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{font-weight:300 !important;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{width:8px !important;height:20px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9' /%3E%3C/svg%3E") !important;}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{color:#333 !important;border:1px solid #428bca!important;background-color:white;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fff),color-stop(100%,#dcdcdc));background:-webkit-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-moz-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-ms-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:-o-linear-gradient(top,#fff 0%,#dcdcdc 100%);background:#428bca !important;width:25px !important;height:30px!important;line-height:15px;color:#fff !important;}
</style>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">View Sales Invoice List</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList', '', '1'); ?>
                                    <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export
                                        <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>

                        <hr style="border-color: #ccc">
                
                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body" id="PrintEmpExitInterviewList">
                                <?php echo CommonHelper::headerPrintSectionInPrintView($m); ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                         <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                                <thead>
                                                    <th style="width: 45px;" class="so-width text-center col-sm-1">S.No</th>
                                                    <th style="width: 62px;"class="text-center col-sm-1">SO No</th>
                                                    <th  style="width: 80px;"class="text-center col-sm-1">SI No</th>
                                                    <!-- <th class="text-center col-sm-1">ST No</th> -->
                                                    <!-- <th style="width: 100px;"class="text-center col-sm-1">Buyer's Unit</th> -->
                                                    <!-- <th class="text-center col-sm-1">Order No</th> -->
                                                    <th style="width: 90px;" class="text-center col-sm-1">SI Date</th>
                                                    <th style="width: 135px;" class="text-center col-sm-1">Payment Terms</th>
                                                    <th class="text-center col-sm-1">Order Date</th>
                                                    <th class="text-center">Customer</th>
                                                    <th style="width: 80px;" class="text-center">Amount</th>
                                                    <th  style="width: 75px;"class="text-center">SI Status</th>
                                                    <th class="text-center">Status</th>

                                                    <th style="width: 60px;" class="text-center">Action</th>

                                                    {{-- <th class="text-center">Delete</th> --}}
                                                </thead>
                                                <tbody id="data">
                                                    <?php $counter = 1;
                                                    $total = 0;
                                                    $open = 0;
                                                    $parttial = 0;
                                                    $complete = 0;
                                                    
                                                    ?>

                                                    @foreach ($sales_tax_invoice as $row)
                                                        <?php
                                                        $data = SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row->id);
                                                        $fright = SalesHelper::get_freight($row->id);
                                                        
                                                        $amount = $data->amount + $row->sales_tax + $fright;
                                                        
                                                        $received_amount = SalesHelper::get_received_amount($row->id);
                                                        $main_amount = $amount;
                                                        $diffrence = $main_amount - $received_amount;
                                                        
                                                        if ($diffrence < 0):
                                                            $diffrence = 0;
                                                        endif;
                                                        
                                                        if ($diffrence == $main_amount):
                                                            $status = 'Open';
                                                            $open++;
                                                        elseif ($main_amount != '' && $diffrence != 0):
                                                            $status = 'partial';
                                                            $parttial++;
                                                        elseif ($diffrence == 0):
                                                            $status = 'Complete';
                                                            $complete++;
                                                        endif;
                                                        
                                                        $customer = CommonHelper::byers_name($row->buyers_id);
                                                        
                                                        $BuyersUnit = '';
                                                        $BuyerOrderNo = '';
                                                        if ($row->so_id != 0):
                                                            $SoData = DB::Connection('mysql2')->table('sales_order')->where('id', $row->so_id)->select('so_no', 'buyers_unit')->first();
                                                            $BuyersUnit = $SoData->buyers_unit ?? 0;
                                                            $BuyerOrderNo = $SoData->so_no ?? 0;
                                                        endif;
                                                        ?>
                                                        <tr @if ($status == 'Open')
                                                    @elseif($status == 'partial') style="background-color: #c9d6ec" @endif
                                                            title="{{ $row->id }}" id="{{ $row->id }}">
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td title="{{ $row->id }}" class="text-center">
                                                                @if (!empty($row->so_no))
                                                                    {{ strtoupper($row->so_no) }}
                                                                @else
                                                                    Direct
                                                                    Sale
                                                                @endif
                                                            </td>
                                                            <td title="{{ $row->id }}" class="text-center">
                                                                {{ strtoupper($row->gi_no) }}</td>
                                                            <!-- <td>
                                                            <input type="text" id="ScNo<?php echo $row->id; ?>" class="form-control" value="<?php echo $row->sc_no; ?>">
                                                            <button type="button" class=" btn btn-xs btn-success" id="BtnUpdate<?php echo $row->id; ?>" onclick="UpdateValue('<?php echo $row->id; ?>')">Update</button>
                                                            <span id="ScNoError<?php echo $row->id; ?>"></span>
                                                        </td> -->
                                                            <!-- <td class="text-center"><?php echo $BuyersUnit; ?></td> -->
                                                            <!-- <td class="text-center"><?php echo $BuyerOrderNo; ?></td> -->
                                                            <td class="text-center">
                                                                <?php echo \Carbon\Carbon::parse($row->gi_date)->format("d-M-Y"); ?>
                                                            </td>
                                                            <td class="text-center">{{ $row['model_terms_of_payment'] }}
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo \Carbon\Carbon::parse($row->order_date)->format("d-M-Y"); ?>
                                                            </td>
                                                            <td class="text-center"><strong>{{ $customer->name }}</strong></td>
                                                            <td class="text-right">
                                                                <!-- {{ number_format($data->amount + $row->sales_tax + $row->sales_tax_further + $fright, 2) }} -->
                                                                {{ $row->total == '0.000' ? number_format($data->amount + $row->sales_tax + $row->sales_tax_further + $fright, 0) : number_format($row->total, 0) }}
                                                            </td>
                                                            <td>{{ $status }}</td>
                                                            <td id="stat{{ $row->id }}" class="text-center">
                                                                <?php echo SalesHelper::si_status($row->si_status); ?></td>
                                                            <?php $total += $data->amount + $row->sales_tax + $fright; ?>

                                                            <td class="text-center">
                                                                <div class="dropdown">
                                                                    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>

                                                                                <?php if($view == true):?>
                                                                                <button
                                                                                    onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id; ?>','View Sales Tax Invoice')"
                                                                                    type="button"
                                                                                    class="btn btn-success btn-xs" style="width:100% !important; text-align:left;">View</button>
                                                                                <?php endif;?>
                                                                                <!-- <?php if($edit == true ):?>
                                                                                <button
                                                                                    onclick="sales_tax('< ?php echo $row->id?>','< ?php echo $m ?>')"
                                                                                    type="button" class="btn btn-primery btn-xs">Edit</button>
                                                                                <?php endif;?> -->
                                                                                
                                                                                <!-- <a target="_blank" class="btn btn-xs btn-info"
                                                                                href="<?php echo url('/'); ?>/sales/PrintSalesTaxInvoiceDirect?id=<?php echo $row->id; ?>">Print</a> -->

                                                                                @if ($row->si_status != 3 && (!empty($row->so_no) || empty($row->approve_user_1)))

                                                                                <?php if($delete == true):?>
                                                                                <button
                                                                                    onclick="sales_tax_delete('<?php echo $row->id; ?>','<?php echo $m; ?>')"
                                                                                    type="button"
                                                                                    class="btn btn-danger btn-xs" style="width:100% !important; text-align:left;">Delete</button>
                                                                                <?php endif;?>
                                                                                    <!-- <button
                                                                                onclick="sales_tax('< ?php echo $row->id?>','< ?php echo $m ?>')"
                                                                                type="button" class="btn btn-primery btn-xs">Edit</button> -->
                                                                                    <!-- <a target="_blank" class="btn btn-xs btn-primary"
                                                                                href="<?php echo url('/'); ?>/sales/EditSalesTaxInvoice?id=<?php echo $row->id; ?>?m=<?php echo $m; ?>">Edit</a> -->
                                                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                                                        href="{{ route('edit.sales.tax.invoice', ['id' => $row->id, 'm' => $row->id]) }}">Edit</a>
                                                                                @endif



                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                            </td>
                                                            {{-- <td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}"
                                                    class="btn btn-success btn-xs">Edit </a></td> --}}
                                                            {{-- <td class="text-center"><button onclick="delete_record('{{$row->id}}')"
                                                    type="button" class="btn btn-danger btn-xs">DELETE</button></td> --}}
                                                        </tr>
                                                    @endforeach

                                                    

                                                </tbody>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center" colspan="7" style="font-size: 13px;">
                                                            Total
                                                        </td>
                                                        <td class="text-right" colspan="1" style="font-size:13px;color:#333;">
                                                            {{ number_format($total, 2) }}
                                                        </td>
                                                        <td class="text-center" colspan="2" style="font-size: 13px;">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td colspan="1" style="font-size: 13px;"><strong>Open</strong>
                                                        </td>
                                                        <td style="font-size: 13px;"><strong><?php echo $open; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td colspan="1" style="font-size: 13px;">
                                                            <strong>Partial</strong>
                                                        </td>
                                                        <td style="font-size: 13px;"><strong><?php echo $parttial; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td colspan="1" style="font-size: 13px;">
                                                            <strong>Complete</strong>
                                                        </td>
                                                        <td style="font-size: 13px;"><strong><?php echo $complete; ?></strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('EmpExitInterviewList');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('Sales Tax Invoice <?php echo date('d-m-Y'); ?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#BuyerId').select2();
            $('.select2-container--default').css('width', '100%');
        });

        function UpdateValue(Id) {
            var base_url = '<?php echo URL::to('/'); ?>';
            var ScNo = $('#ScNo' + Id).val();
            if (ScNo != "") {
                $('#ScNoError' + Id).html('');
                $.ajax({
                    url: base_url + '/sdc/updateScNo',
                    type: 'GET',
                    data: {
                        Id: Id,
                        ScNo: ScNo
                    },
                    success: function(response) {
                        alert(response);
                    }
                });
            } else {
                $('#ScNoError' + Id).html('<p class="text-danger">Enter Sc No</p>');
            }

        }

        function FilterSelection() {
            var radioValue = $('#filters').val();

            if (radioValue == 1) {
                $('#ShowHideDate').fadeIn('slow');
                $('#ShowHideSoNo').css('display', 'none');
                $('#ShowHideBuyer').css('display', 'none');
            } else if (radioValue == 2) {
                $('#ShowHideSoNo').fadeIn('slow');
                $('#ShowHideDate').css('display', 'none');
                $('#ShowHideBuyer').css('display', 'none');
            } else if (radioValue == 3) {
                $('#ShowHideBuyer').fadeIn('slow');
                $('#ShowHideSoNo').css('display', 'none');
                $('#ShowHideDate').css('display', 'none');
            } else {
                $('#ShowHideBuyer').css('display', 'none');
                $('#ShowHideSoNo').css('display', 'none');
                $('#ShowHideBuyer').css('display', 'none');
            }
        }

        function sales_tax_delete(id, m) {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url = '<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url + '/sad/sales_tax_delete',
                    type: 'GET',
                    data: {
                        id: id,
                        m: m
                    },
                    success: function(response) {
                        alert('Deleted');
                        // alert(response);
                        $('#' + id).remove();

                    }
                });
            } else {}
        }


        function RadioChange() {
            var radioValue = $("input[name='FilterType']:checked").val();

            if (radioValue == 1) {
                $('#SearchText').prop('disabled', false);
                $('#ChangeType').html('SO NO');
                $('#SearchText').prop('placeholder', 'Enter SO NO');
            } else if (radioValue == 2) {
                $('#SearchText').prop('disabled', false);
                $('#ChangeType').html('SI NO');
                $('#SearchText').prop('placeholder', 'Enter SI NO');
            } else {
                $('#ChangeType').html('');
                $('#SearchText').prop('placeholder', '');
                $('#SearchText').prop('disabled', true);
            }
        }

        function ResetFields() {
            $('input[name="FilterType"]').attr('checked', false);
            $('#ChangeType').html('');
            $('#SearchText').prop('placeholder', '');
            $('#SearchText').val('');
            $('#SearchText').prop('disabled', true);
        }

        function viewRangeWiseDataFilterOld() // This function is Old not using anywhere.
        {
            var radioValue = $("input[name='FilterType']:checked").val();
            var FilterType = $('#filters').val();
            var SearchText = $('#SearchText').val();
            var BuyerId = $('#BuyerId').val();
            var from = $('#from').val();
            var to = $('#to').val();
            var m = '<?php echo $m; ?>';
            var radio = $('input[name="optradio"]:checked').val();
            $('#data').html(
                '<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>'
            );

            $.ajax({
                url: '/sdc/getSalesTaxInvoiceeFilterWise',
                type: 'Get',
                data: {
                    from: from,
                    to: to,
                    m: m,
                    radioValue: radioValue,
                    SearchText: SearchText,
                    FilterType: FilterType,
                    BuyerId: BuyerId,
                    radio: radio
                },

                success: function(response) {

                    $('#data').html(response);


                }
            });

        }

        function viewRangeWiseDataFilter() {
            // var radioValue = $("input[name='FilterType']:checked").val();
            // var FilterType = $('#filters').val();
            // var SearchText = $('#SearchText').val();
            // var BuyerId = $('#BuyerId').val();
            var radio = $('input[name="optradio"]:checked').val();
            var search = $('#search').val();
            var username = $('#username').val();
            var si_no = $('#si_no').val();
            var so_no = $('#so_no').val();
            var gdn_no = $('#gdn_no').val();
            var si_status = $('#si_status').val();
            var from = $('#from').val();
            var to = $('#to').val();
            var m = '<?php echo $m; ?>';
            $('#data').html(
                '<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>'
            );

            $.ajax({
                url: '/sdc/getSalesTaxInvoiceeFilterWiseAjax',
                type: 'Get',
                data: {
                    from: from,
                    to: to,
                    m: m,
                    search: search,
                    radio: radio,
                    si_no: si_no,
                    gdn_no: gdn_no,
                    so_no: so_no,
                    username: username,
                    si_status: si_status
                },
                success: function(response) {
                    $('#data').html(response);
                }
            });

        }

        function sales_tax(sales_order_id, m) {
            var base_url = '<?php echo URL::to('/'); ?>';
            window.location.href = base_url + '/sales/EditSalesTaxInvoice?sales_order_id=' + sales_order_id + '&&' + 'm=' +
                m;
        }
    </script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $("#EmpExitInterviewList").DataTable({
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
@endsection
