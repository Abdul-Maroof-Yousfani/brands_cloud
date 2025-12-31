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

use App\Helpers\ReuseableCode;

$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$performaInvoice=ReuseableCode::check_rights(111);
$view=ReuseableCode::check_rights(111);
$edit=ReuseableCode::check_rights(112);
$delete=ReuseableCode::check_rights(113);
$export=ReuseableCode::check_rights(256);

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',1)->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;
?>
@extends('layouts.default')
@section('content')
    @include('select2')

<style>
/* 
.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td{padding:8px 8px !important;background:#ddd;text-align:center;}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after{background-image:url(data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235e5873' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E);background-repeat:no-repeat;background-position:center;background-size:12px;color:#6e6b7b;width:inherit;height:0;content:'';right:0.3rem;top:1.3rem;}
table.dataTable tbody th,table.dataTable tbody td{padding:8px 10px;text-align:center;} */



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
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="headquid ">
                                    <span class="subHeadingLabelClass">View Delivery Note List</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <div class="headquid ">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                        <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border-color: #ccc">

                    <!-- <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="gdn_no" class="form-label">GDN NO.</label>
                            <input type="text" class="form-control" id="gdn_no" placeholder="Type Here GDN NO." name="gdn_no">
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="so_no" class="form-label">SO NO.</label>
                            <input type="text" class="form-control" id="so_no" placeholder="Type Here SO NO." name="so_no">
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Type here Product Name, Item Code, SKU" name="search" value="">
                        </div>
                        
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo $currentMonthStartDate;?>" class="form-control" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" readonly class="form-control text-center" value="Between" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>To Date</label>
                            <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>" />
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>User </label>
                            <select name="username" id="username" class="form-control select2">
                                <option value="">Select User</option>
                                @foreach ($username as $item)
                                <option value="{{ $item->username }}">{{ $item->username }}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-md-2 col-sm-2 col-xs-12">
                            <label for="dnStatus" class="form-label">DN Status</label>
                            <select name="dnStatus" id="dnStatus" class="form-control">
                                <option value="">Select Status</option>
                                <option value="All">All</option>
                                <option value="1">Approved</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                        
                    

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                        </div>
                    </div> -->


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <!-- <table class="userlittab table table-bordered sf-table-list dataTable no-footer" id="EmpExitInterviewList"> -->
                                        <table class="userlittab table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th style="text-align:center;width: 50px;" class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">SO Nos</th>
                                            <th class="text-center col-sm-1">DN No</th>
                                            <th class="text-center col-sm-1">DN Date</th>
                                            <!-- <th class="text-center col-sm-1">Order No</th> -->
                                            <th class="text-center col-sm-1">Order Date</th>
                                            <th style="text-align:left; width: 250px;" class="text-center">Customer</th>
                                            <th style="text-align: center !important;width: 60px;"class="text-center">Qty.</th>
                                            <th style="text-align: center !important;width: 80px;"class="text-center">Amount</th>
                                            <!-- <th class="text-center">Document <br>Status</th> -->
                                            <th class="text-center">Status</th>
                                            <!-- <th class="text-center">Username</th> -->
                                            <th class="text-center">Note</th>
                                            <th  style="text-align:left;width: 60px;" class="text-left">Action</th>
                                            {{--<th class="text-center">Delete</th>--}}
                                            </thead>
                                            <tbody id="data">
                                            <?php $counter = 1;$total=0;
                                            $open=0;
                                            $parttial=0;
                                            $complete=0;
                                            ?>

                                            @foreach($delivery_note as $row)
                                            
                                                <?php

                                                    $checkifbarcodescanningproduct = DB::connection('mysql2')->table('delivery_note_data')
                                                        ->join('subitem', 'delivery_note_data.item_id', '=', 'subitem.id')
                                                        ->where('subitem.is_barcode_scanning', 1)
                                                        ->where('delivery_note_data.master_id', $row->id)
                                                        ->select('grn_data.net_amount', 'grn_data.rate') // Add required columns to select
                                                        ->count();
                                                    $stockBarcodeurl = route('stockBarcode.show',$row->grn_no).'?type=grn';

                                                    $approvalStatus = $row->status == 1 ? 'Approved' : "Pending";

                                              //  $data1=SalesHelper::get_total_amount_for_dn_by_id($row->id);
                                                $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id);
                                             
                                                $status='';
                                                if ($row->sales_tax_invoice==0):
                                                $status='Open';
                                                $return_qty_data=SalesHelper::get_return_by_dn($row->gd_no);

                                                $return_qty=0;
                                                if (!empty($return_qty_data->qty)):
                                                $return_qty=$return_qty_data->qty;
                                                endif;

                                                $remaining_qty=$data->qty-$return_qty;
                                                if ($remaining_qty==0):
                                                $status='Returned';
                                                endif;
                                                $open++;
                                                elseif($row->sales_tax_invoice==1):
                                                $status='Complete';
                                                //   $parttial++;

                                                $complete++;
                                                endif;
                                                 ?>
                                                <?php
                                                    $customer=CommonHelper::byers_name($row->buyers_id); 
                                                    $saleOrderDetail = CommonHelper::get_so_by_SONO($row->so_no);
                                                    $sale_taxes_amount_rate = 0;
                                                    if($saleOrderDetail){
                                                        $sale_taxes_amount_rate = $saleOrderDetail->sale_taxes_amount_rate ?? 0;
                                                    }
                                                ?>

                                                <tr @if($status=='Open') style="background-color: #fdc8c8" @elseif($status=='partial') style="background-color: #c9d6ec"  @endif title="{{$row->id}}" id="{{$row->id}}">
                                                    <td style="text-align:center;" class="text-center">{{$counter++}}</td>
                                                    <td class="text-center"><?php echo  strtoupper($row->so_no) ?></td>
                                                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>
                                                    <td class="text-center"> 
                                                        <?php echo  $row->gd_date ? \Carbon\Carbon::parse($row->gd_date)->format("d-M-Y") : '' ?>
                                                        <br>
                                                        <?php echo $row->timestamp ? \Carbon\Carbon::parse($row->timestamp)->format("h:i:s A") : "";?> 
                                                    </td>

                                                    @php
                                                        $sales_order = App\Models\Sales_Order::where("so_no", $row->so_no)->first();
                                                    @endphp
                                                    <!-- <td class="text-center">{{$row->so_no}}</td> -->
                                                  
                                                    <td class="text-center">
                                                        
                                                    <?php echo $sales_order && $sales_order->timestamp ? \Carbon\Carbon::parse($sales_order->timestamp)->format("d-M-Y") : "";?> <br>
                                                <?php echo $sales_order && $sales_order->timestamp ? \Carbon\Carbon::parse($sales_order->timestamp)->format("h:i:s A") : "";?> 
                                                
                                                </td>



                                                   
                                                    <td style="text-align:left;" class="text-center"><strong>{{$customer->name}}</strong></td>
                                                    <td style="text-align: center !important;" class="text-center">{{number_format($data->qty,0)}}</td>
                                                  
                                                    <td   style="text-align: center !important;" class="text-center">{{number_format($data->amount + $row->sales_tax_amount + $sale_taxes_amount_rate, 0)}}</td>
                                                    <!-- <td>{{$status}}</td> -->
                                                    <td>{{$approvalStatus}}</td>
                                                    <!-- <td class="text-center"><?php echo $row->username?></td> -->
                                                     <td  style="text-align:left;"  class="text-center">
                                                        {{ !empty($sales_order->remark) ? $sales_order->remark : '-' }}
                                                    </td>


                                                    <td class="text-center">


                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <?php if($view == true):?>
                                                                    <button style="width: 100%;"onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id ?>','','View Delivery Note')"
                                                                            type="button" class="btn btn-success btn-xs">View</button>

                                                                    <?php endif;?>

                                                        
                                                                    <button  style="width: 100%;" onclick="showDetailModelOneParamerter('sales/viewPerformaInvoice/<?php echo $row->id ?>','<?php echo $row->id ?>','View Performa Invoice Note')"
                                                                            type="button" class="btn btn-success btn-xs">Performa Invoice</button>

                                                            
                                                                    <?php if($edit == true && $status!='Complete' && $row->status == 0):?>
                                                                    <button  style="width: 100%;" onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')"
                                                                            type="button" class="btn btn-primary btn-xs">Edit </button>
                                                                    <?php endif;?>

                                                            


                                                                    @if($checkifbarcodescanningproduct > 0)
                                                                    <a style="display: block" href="{{route('stockBarcode.show',$row->gd_no).'?type=gdn'}}" type="button" class="btn btn-warning btn-xs" target="_blank">Stock Barcodes</a>
                                                                    @endif


                                                                    <li>
                                                                        <?php if($delete == true && $status!='Complete'):?>
                                                                    <button  style="width: 100%;" onclick="delivery_note_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                                                                            type="button" class="btn btn-danger btn-xs">Delete</button>
                                                                    <?php endif;?>

                                                                </li>

                                                             </ul>
                                                        </div>

                                                    </td>
                                                    {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
                                                    {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
                                                </tr>


                                            @endforeach



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
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Delivery Note <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('#BuyerId').select2();
            $('.select2-container--default').css('width','100%');
        });
        function delivery_note_delete(id,m)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sad/delivery_not_delete',
                    type: 'GET',
                    data: {id: id, m:m},
                    success: function (response) {
                        //alert(response); return false;
                        if (response=='0')
                        {
                            alert('Can not Deleted')
                        }

                        else {
                            alert('Deleted');
                            // alert(response);
                            $('#' + id).remove();
                        }



                    }
                });
            }
            else{}
        }


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


        function RadioChange()
        {
            var radioValue = $("input[name='FilterType']:checked").val();

            if(radioValue == 1)
            {
                $('#SearchText').prop('disabled',false);
                $('#ChangeType').html('SO NO');
                $('#SearchText').prop('placeholder','Enter SO NO');
            }
            else if(radioValue == 2)
            {
                $('#SearchText').prop('disabled',false);
                $('#ChangeType').html('DN NO');
                $('#SearchText').prop('placeholder','Enter DN NO');
            }
            else
            {
                $('#ChangeType').html('');
                $('#SearchText').prop('placeholder','');
                $('#SearchText').prop('disabled',true);
            }
        }

        function ResetFields()
        {
            $('input[name="FilterType"]').attr('checked', false);
            $('#ChangeType').html('');
            $('#SearchText').prop('placeholder','');
            $('#SearchText').val('');
            $('#SearchText').prop('disabled',true);
        }

        function viewRangeWiseDataFilterOld() // This function is Old not using anywhere.
        {
            var radioValue = $("input[name='FilterType']:checked").val();
            var FilterType = $('#filters').val();
            var SearchText = $('#SearchText').val();
            var BuyerId = $('#BuyerId').val();
            var from= $('#from').val();
            var to= $('#to').val();
            var radio= $('input[name="optradio"]:checked').val();
            var m = '<?php echo $m;?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '{{url('/sdc/getDeliveryNoteFilterWise')}}',
                type: 'Get',
                data: {from: from,to:to,m:m,radioValue:radioValue,SearchText:SearchText,FilterType:FilterType,BuyerId:BuyerId,radio:radio},

                success: function (response) {

                    $('#data').html(response);


                }
            });

        }

        function viewRangeWiseDataFilter() // This function is not using anywhere.
        {
            var radioValue = $("input[name='FilterType']:checked").val();
            var FilterType = $('#filters').val();
            var SearchText = $('#SearchText').val();
            var search = $('#search').val();
            var BuyerId = $('#BuyerId').val();
            var from= $('#from').val();
            var to= $('#to').val();
            var username= $('#username').val();
            var gdn_no= $('#gdn_no').val();
            var so_no= $('#so_no').val();
            var dnStatus= $('#dnStatus').val();
            var radio= $('input[name="optradio"]:checked').val();
            var m = '<?php echo $m;?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            $.ajax({
                url: '{{url('/sdc/getDeliveryNoteFilterWiseAjax')}}',
                type: 'Get',
                data: {
                    username:username, 
                    from: from,
                    to:to,
                    m:m, 
                    dnStatus:dnStatus, 
                    radioValue:radioValue,
                    SearchText:SearchText,
                    FilterType:FilterType,
                    BuyerId:BuyerId,
                    radio:radio,
                    search:search,
                    gdn_no:gdn_no,
                    so_no:so_no,
                    territory_id: "{{ auth()->user()->territory_id }}"
                },

                success: function (response) {
                    $('#data').html(response);

                }
            });

        }

        function delivery_note(id,m)
        {
            var base_url='<?php echo URL::to('/'); ?>';
            window.location.href = base_url+'/sales/EditDeliveryNote?id='+id+'&&'+'m='+m;
        }

        function FilterSelection()
        {
//            var radioValue = $("input[name='SelectType']:checked").val();
            var   radioValue=$('#filters').val();
            if(radioValue == 1)
            {
                $('#ShowHideDate').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideBuyer').css('display','none');
                $("input:radio").removeAttr("checked");
            }
            else if(radioValue == 2)
            {
                $('#ShowHideSoNo').fadeIn('slow');
                $('#ShowHideDate').css('display','none');
                $('#ShowHideBuyer').css('display','none');
                $("input:radio").removeAttr("checked");
            }
            else if(radioValue == 3)
            {
                $('#ShowHideBuyer').fadeIn('slow');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideDate').css('display','none');
                $("input:radio").removeAttr("checked");
            }
            else
            {
                $('#ShowHideBuyer').css('display','none');
                $('#ShowHideSoNo').css('display','none');
                $('#ShowHideBuyer').css('display','none');
                $("input:radio").removeAttr("checked");
            }

        }

</script>
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
