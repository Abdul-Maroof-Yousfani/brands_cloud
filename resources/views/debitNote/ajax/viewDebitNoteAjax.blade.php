<?php
use App\Helpers\CommonHelper;
$total_amount = 0;

$buyer_detail = CommonHelper::get_buyer_detail($debit->store);
$m = Input::get('m');
?>

<style>
 p{margin:0;padding:0;font-size:13px;font-weight:500;}
input.form-control.form-control2{margin:0!important;}
.table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th{vertical-align:inherit !important;text-align:left !important;padding:7px 5px !important;}
.totlas{display:flex;justify-content:right;gap:70px;background:#ddd;width:18%;float:right;padding-right:8px;}
.totlas p{font-weight:bold;}
.psds{display:flex;justify-content:right;gap:88px;}
.psds p{font-weight:bold;}
.userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{padding:10px 5px !important;}
.totlass{display:inline;background:transparent;margin-top:-25px;width:68%;float:left;}
.totlass h2{font-size:13px !important;}
.vomp{text-align:left;}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row" >
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                           
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                <?php CommonHelper::newdisplayPrintButtonInView('printReport', '', 1);?>
                            </div>
                        </div>
                        <div class="mt-top" id="printReport">
                            <div class="sales_or2">
                                <div class="contra">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="contr">
                                                <h2 class="subHeadingLabelClass">Brands Unlimited (Pvt) Ltd</h2>
                                                <p>301-305, 3rd Floor, Kavish Crown Plaza
                                                    Sharah-e-Faisal, karachi.</p>
                                                    <br>
                                                <p>N.T.N #:5098058-8 </p>
                                                <p>S.t #: 3277876156235</p>

                                                <br>
                                                <p>Bill To:</p>
                                                <p>
                                                    <strong>{{$buyer_detail->name}}</strong>
                                                    <br>
                                                    <br>
                                                    {{$buyer_detail->address}}<br>
                                                    {{ CommonHelper::get_all_country_by_id($buyer_detail->country)->name ?? '-'}}<br>
                                                    {{$buyer_detail->phone_1}}<br>
                                                    N.T.N #:
                                                    {{isset($buyer_detail->cnic_ntn) ? $buyer_detail->cnic_ntn : "-" }}
                                                    S.T #: {{isset($buyer_detail->strn) ? $buyer_detail->strn : "-"}}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6  text-right">
                                            <div class="contr2">
                                                <h2 class="subHeadingLabelClass">Debit Note</h2>
                                                <br>
                                                <p>Document # {{$debit->id}}</p>
                                                <p>Date: {{ \Carbon\Carbon::parse($debit->created_at)->format("d-M-Y") }}</p>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="sale-list userlittab table table-bordered sf-table-list" style="border: 1px solid #000;">
                                                        <tbody>
                                                            <tr>
                                                                <td>Amount Limited</td>
                                                                <td style="text-align: right;">
                                                                    {{ number_format($debit->amount, 2) }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Store</td>
                                                                <td style="text-align: right;">
                                                                    {{ \App\Helpers\SalesHelper::get_customer_name($debit->store) }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Delivery Man</td>
                                                                <td style="text-align: right;">
                                                                    {{ $debit->delivery_man }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date</td>
                                                                <td style="text-align: right;">
                                                                    {{ \Carbon\Carbon::parse($debit->date)->format("d-M-y") }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>On Record</td>
                                                                <td style="text-align: right;">
                                                                    {{ $debit->on_record == 1 ? "Yes" : "No" }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Voucher Type</td>
                                                                <td style="text-align: right;">
                                                                    {{ json_decode(\App\Helpers\CommonHelper::get_vouchers($debit->voucher_type))[0]->name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Branch</td>
                                                                <td style="text-align: right;">
                                                                    {{ \App\Helpers\CommonHelper::get_branch_by_id($debit->branch) }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Debit</td>
                                                                <td style="text-align: right;">
                                                                    {{ $debit->debit != "-" ? \App\Helpers\CommonHelper::get_account_name_by_id($debit->debit) : "N/A" }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Details</td>
                                                                <td style="text-align: right;">
                                                                    {{ $debit->details }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                           
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <div class="term">
                                                            <p>Terms:</p>
                                                            <p>Payment Terms: 30 Days</p>
                                                            <p>Salesperson Mobile #</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                        <div class="term">
                                                            <p>GDN #:</p>
                                                            <p>Branch: {{ \App\Helpers\CommonHelper::get_branch_by_id($debit->branch)  }}</p>
                                                            <p>Salesperson: {{$buyer_detail->SaleRep}}</p>
                                                            <p><strong></strong></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                           
                                </div>

                                <div class="row align-items-top">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="totlas totlass">
                                            <h2>Note</h2>
                                            <p>{{ $debit->details ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <br>
                            
                            <div class="sgnature2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">
                                            <p><strong>{{ Auth::user()->name }}</strong> </p>
                                            <p><strong>Prepared By</strong> </p>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">
    
                                            <p><strong>Approved By</strong> </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm4 col-xs-4">
                                            <p><strong>Received By</strong> </p>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="vomp">
                                        <p><strong>Creation Time :{{ \Carbon\Carbon::parse($debit->created_at)->format('d-M-Y') }}</strong> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="contra">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="contraA">
                                            <h2>Date: </h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="con_rewB">

                                        </div>    
                                    </div>
                                </div>
                            </div> -->

                    <!-- {{--    <div class="bro_src">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="secc">
                                            <hr style="border:1px solid #000">
                                                <h2>SECTION-B</h2>
                                            <hr style="border:1px solid #000">
                                        </div>
                                        <div class="vomp">
                                            <h2>To be completed by (1) Managing Director</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="tasetb">
                                        <table class="userlittab3 table table-bordered sf-table-list3">
                                            <tbody id="data">
                                                <tr>
                                                    <td class="text-left"></td>
                                                    <td class="text-center">Yes</td>
                                                    <td class="text-center">NO</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Capable of meeting specified requirements?</td>
                                                    <td class="text-center">Y</td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">Any special problem(s)?</td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">N</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">Date</td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">2/3/2023</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="vomp">
                                <h2>Report Execution :2/3/2023 6:41:08 PM</h2>
                            </div>

                            <div class="vomp">
                                <h2>Premier Cables</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="tasetb">
                                        <table class="userlittab3 table table-bordered sf-table-list4">
                                            <tbody id="data">
                                                <tr class="text-center">
                                                    <td colspan="6" class="secec text-center">SECTION-C</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        To be completed by the concerned Director Marketing<br>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    
                                                </tr>

                                                <tr>
                                                    <td class="text-left">
                                                        1) Decision: On the basis of answer given in Section-B, it is decided to:<br>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    
                                                </tr>

                                                <tr>
                                                    <td class="text-left">
                                                        Accept the order
                                                    </td>
                                                    <td>
                                                        Y
                                                    </td>
                                                    <td>
                                                        Reject the order
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        Negotiate Amendment
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        2) Confirmation sent:
                                                    </td>
                                                    <td>
                                                        Yes Y
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        No
                                                    </td>
                                                    <td></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        3) Obtain Customer's agrrement:
                                                    </td>
                                                    <td>
                                                        Yes Y
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        No
                                                    </td>
                                                    <td></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                    4) Amendment required after acceptance of an order**:
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        Yes Y
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        No N
                                                    </td>
                                                    
                                                    
                                                </tr>
                                                <tr>
                                                    <td>**Please fill new contract review form and send it to Managing Director</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>This is a computer generated document and does not require any signature.</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                    </div> --}} -->
                    <!-- <div class="col-md-12 padtb text-right printHide">
                        <div class="col-md-9"></div>
                        <div class="col-md-3 my-lab">
                            <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Approve</button>
                            <a type="button" href="{{url('selling/listSaleOrder')}}"
                                class="btnn btn-secondary" data-dismiss="modal">Close</a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- printView sale order -->
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
                .totlass{display:inline!important;background:transparent!important;margin-top:-25px!important;}
                .totlass h2{font-size:13px !important;}
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
</script>