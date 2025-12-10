<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\SalesHelper;
$sale_order = CommonHelper::get_so_by_SONO($sales_tax_invoice->so_no);

$id = $_GET['id'];
//$m = $_GET['m']; before Code
$m = Session::get('run_company'); //After Code Change
$currentDate = date('Y-m-d');
$total_expense = 0;
$AmountInWordsMain = 0;

$count = 1;
$total_before_tax = 0;
$total_tax = 0;
$total_after_tax = 0;
$currency = '-';
$total_tax_amount = 0;
$total_qty = 0;
$total_discount_amount = 0;
$total_gross_amount = 0;
$total_amount_after_tax = 0;
if ($sales_tax_invoice->currency != 0) {
    $currency = $sales_tax_invoice->currencyRelation->curreny;
    $currency_rate = $sales_tax_invoice->currency_rate;
}

foreach ($sales_tax_invoice_data as $item) {
    $saleOrderDetail = CommonHelper::get_item_detials($item->so_data_id);
    $total_expense = 0;
    $total_before_tax += $item->rate * $item->qty;
    $total_tax += $item->tax_amount;
    $total_after_tax += $item->amount;
    $total_tax_amount += $item->tax_amount;
    $total_qty += $item->qty;
    $total_discount_amount += $saleOrderDetail->discount_amount_1 ?? 0;
    $total_gross_amount += $saleOrderDetail->sub_total ?? 0;
    $total_amount_after_tax += $saleOrderDetail->amount ?? 0;
    // $saleOrderDetail = CommonHelper::get_item_detials($item->so_data_id);
    // $total_qty += $item->qty;
    // $total_before_tax += $item->qty * $item->rate;
    // $total_foc += $item->foc;
    // $total_discount_amount += $saleOrderDetail->discount_amount_1;
    // $total_gross_amount += $saleOrderDetail->sub_total;
    // $total_tax += $item->tax_amount;
    // $total_after_tax += $item->amount;
}
?>
<style>
 .signature_bor{border-top:solid 1px #CCC;padding-top:7px;}
textarea{border-style:none;border-color:Transparent;}
.vomp{text-align:left;}
p{margin:0;padding:0;font-size:13px;font-weight:500;}
input.form-control.form-control2{margin:0 !important;}
.table-bordered>thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th{vertical-align:inherit !important;text-align:left !important;padding:7px 5px !important;}
.totlas{display:flex;justify-content:right;gap:70px;background:#ddd;width:18%;float:right;padding-right:8px;}
.totlas p{font-weight:bold;}
.psds{display:flex;justify-content:right;gap:88px;}
.psds p{font-weight:bold;}
.userlittab>thead>tr>td,.userlittab>tbody>tr>td,.userlittab>tfoot>tr>td{padding:10px 5px !important;}
.totlass{display:inline;background:transparent;margin-top:-25px;}
.totlass h2{font-size:13px !important;}
table.table-bordered>tbody>tr>td{border:none !important;border-bottom:1px solid #000 !important;}
.table-bordered>thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th{vertical-align:inherit !important;text-align:left !important;padding:7px 5px !important;font-size:13px !important;}
.table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th{font-weight:400 !important;}

</style>
<?php
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <!-- <button class="btn btn-sm btn-primary" onclick="printViewTwo('printPurchaseRequestVoucherDetail','','1')"style="">
            <span class="glyphicon glyphicon-print"> Print</span>
        </button> -->
             <!-- ✅ Normal Page -->
            <div class="no-print">
              <button class="btn btn-primary prinn pritns" onclick="printSection()">🖨️ Print</button>
            </div>

        <?php //CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail','','1');
        ?>


        @if ($sales_tax_invoice->si_status == 1)
            <button id="appro" class="btn btn-sm btn-success" onclick="approve('{{ $sales_tax_invoice->id }}')"
                style="width: 100px">Approve
            </button>
        @endif
        </button>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <a target="_blank" href="{{ url('/sales/undertaking?id=' . $sales_tax_invoice->id) }}">UnderTaking A</a>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row printPurchaseRequestVoucherDetail" id="po_detail">
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well sales_or">
            <div class="row">
                <!-- OLD CODE SALE INVOICE-->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
                    <div class="table-responsive" style="width:50%; float:left; ">
                        <table class="sale-list userlittab table table-bordered sf-table-list" style="border:1px solid #000;">

                            <tbody>
                                <?php
                                $customer_name = CommonHelper::byers_name($sales_tax_invoice->buyers_id);
                                $customer_data = CommonHelper::get_buyer_detail($sales_tax_invoice->buyers_id);
                                
                                $sales_order = SalesHelper::get_sales_tax_by_sales_order_id($sales_tax_invoice->so_id);
                                // $sales_order = App\Models\Sales_Order::find($sales_tax_invoice->so_id);
                                $sales_order = App\Models\Sales_Order::where("so_no", $sales_tax_invoice->so_no)->first();

                                $dn_detail = SalesHelper::get_dn($sales_tax_invoice->so_no);
                                ?>
                                <tr>
                                    <th style="border:1px solid black;width: 50%" class="text-left"style="border: solid 1px;">BUYER'S NAME</th>
                                    <td style="border:1px solid black; width: 50%" class="text-left">
                                        <strong><?php echo ucwords($customer_name->name); ?></strong>
                                    </td>
                                </tr>
                                <!-- <tr>
                                <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">BUYER'S ORDER NO.</th>
                                <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->order_no); ?></td>
                                </tr> -->
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:60%; border: solid 1px;">BUYER'S Order Date</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:40%;">
                                        <?php echo CommonHelper::changeDateFormat($sales_tax_invoice->order_date); ?>
                                    </td>
                                </tr>
                                <!-- @if ($sales_tax_invoice->so_id != 0): -->
                                <!-- <tr>
                                <th style="border:1px solid black;" class="text-left" style="width:50%;border: solid 1px;">BUYER'S UNIT.</th>
                                <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_order->buyers_unit); ?></td>
                                </tr> -->
                                    <!--
                                @endif -->
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">
                                        BUYER'S ADDRESS</th>
                                    <td style="border:1px solid black;font-size: xx-small" class="text-left">
                                        <?php echo ucwords($customer_data->address); ?></td>
                                </tr>

                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">
                                        BUYER'S NTN</th>
                                    <td style="border:1px solid black;font-size: xx-small" class="text-left">
                                        {{ isset($customer_data->cnic_ntn) && $customer_data->cnic_ntn != '' ? ucwords($customer_data->cnic_ntn) : '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive" style="width:40%; float:right;">
                        <table class="sale-list userlittab table table-bordered sf-table-list sales_Tax_Invoice_data" style="border: 1px solid #000;">
                            <tbody>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left"style="width:50%; border: solid 1px;">SI NO.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_tax_invoice->gi_no); ?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="border: solid 1px;">SI Date</th>
                                    <td style="border:1px solid black;" class="text-left">
                                    <?php echo CommonHelper::changeDateFormat($sales_tax_invoice->gi_date); ?></td>
                                </tr>
                                <tr>
                                    <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">SO NO.</th>
                                    <td style="border:1px solid black;" class="text-left" style="width:50%;">
                                        <!-- <?php
                                        if ($sales_tax_invoice->so_id != 0):
                                            echo strtoupper($sales_tax_invoice->so_no);
                                        else:
                                            echo $sales_tax_invoice->other_refrence;
                                        endif;
                                        ?> -->
                                        {{ strtoupper($sales_tax_invoice->so_no) }}
                                    </td>
                                </tr>
                                <!-- @if ($sales_tax_invoice->so_id != 0): -->
                                <!-- <tr>
                                <th style="border:1px solid black;" class="text-left" style="width:50%; border: solid 1px;">OTHER REFRENCE</th>
                                <td style="border:1px solid black;" class="text-left" style="width:50%;"><?php echo strtoupper($sales_order->other_refrence); ?></td>
                                </tr> -->
                                <!--@endif -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="contra">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="contr">
                                <h2 class="subHeadingLabelClass">Brands Unlimited (Pvt) Ltd</h2>
                                <p>301-305, 3rd Floor, Kavish Crown Plaza
                                    Sharah-e-Faisal, karachi.</p>
                                <p>S.t #: 3277876156235</p>
                                <br>
                                <p>Bill To:</p>
                                <p>
                                    <strong><?php echo ucwords($customer_data->name); ?></strong><br>
                                    <?php echo ucwords($customer_data->address); ?><br>
                                    <!-- bharia Twon Civic Center Islamabad<br>
                                    Pakistan<br> -->
                                    {{ $customer_data->phone_1 }}<br>
                                    N.T.N #: {{ $customer_data->cnic_ntn }}<br>
                                    S.T #: {{ $customer_data->strn }}
                                </p>
                                 <p>N.T.N #:5098058-8 </p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6  text-right">
                            <div class="contr2">
                                <h2 class="subHeadingLabelClass">Sale Invoice</h2>
                                <p>Document # {{ $sales_tax_invoice->gi_no }}</p>
                                <!-- <p>Doc #: 27903</p> -->
                                <p>Date: {{ $sales_tax_invoice->gi_date }}</p>
                                <div class="table-responsive">
                                    <table class="sale-list userlittab table table-bordered sf-table-list" style="border: 1px solid #000;width:68% !important;float:right;">
                                        <tbody>
                                            <tr>
                                                <td>Amount Limited</td>
                                                <td style="text-align: right;">
                                                    {{ $sale_order->credit_limit ?? "N/A" }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Balance Amount</td>
                                                <td style="text-align: right;">
                                                    {{ $sale_order->balance_amount ?? "N/A" }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Current Balance Due</td>
                                                <td style="text-align: right;">
                                                    {{ number_format($total_amount_after_tax, 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- @if ($customer_data->display_pending_payment_invoice == 1)
                                <div class="table-responsive">
                                    <table class="sale-list userlittab table table-bordered sf-table-list" style="border:1px solid #000; width:68% !important;float:right;">
                                        <tbody>
                                            <tr>
                                                <td>Amount Limited </td>
                                                <td style="text-align:right;">{{$sales_order->credit_limit}}</td>
                                            </tr>
                                            <tr>
                                                <td> Balance Amount</td>
                                                <td style="text-align:right;">{{$sales_order->balance_amount}}</td>
                                            </tr>
                                            <tr>
                                                <td> Current Balance Due</td>
                                                <td style="text-align:right;">{{$sales_order->current_balance_due}}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                @endif --}}

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="term">
                                            <p>SO Date: {{ $sales_tax_invoice->so_date }}</p>
                                            <!-- <p>Warehouse: </p> -->
                                            {{-- {{ $sales_order->current_balance_due }} --}}
                                            <p>Payment Terms: {{ $sales_order->model_terms_of_payment }}</p>
                                            <!-- <p>Sales Rep Mobile #: 1</p> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="term">
                                            <p>SO #: {{ $sales_order->so_no }}</p>
                                            <p>GDN #: {{ $dn_detail->gd_no }}</p>
                                            <p>Branch: {{ $sales_order->branch }}</p>
                                            <p>Sales Rep: {{ $customer_data->SaleRep }} </p>
                                            @if (strtoupper($customer_data->display_note_invoice) == 'YES')
                                                <p>Note: {{ $customer_data->strn_term }} </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <!-- <h2 class="subHeadingLabelClass">Item Details</h2> -->
                            <div id="actual">
                                <div class="table-responsive">
                                    <table class="table sale_older_tab userlittab table table-bordered sf-table-list sale-list">
                                        <thead>
                                            <tr>
                                                <th class="text-center"style="background: #000 !important; color:#fff !important;">S.NO </th>
                                                <th style="background: #000 !important; color:#fff !important;width: 20% !important;">Item</th>
                                                <!-- <th style="background: #000 !important; color:#fff !important;">Uom</th> -->
                                                <th style="text-align: center !important;; background: #000 !important; color:#fff !important;">Barcode</th>
                                                <th class="text-center"style="background: #000 !important; color:#fff !important;text-align: center !important;">QTY.</th>
                                                {{-- <th style="background: #000 !important; color:#fff !important;">FOC</th> --}}
                                                <th style="background: #000 !important; color:#fff !important;">MRP </th>
                                                <th style="background: #000 !important; color:#fff !important;">Rate</th>
                                                <th style="background: #000 !important; color:#fff !important;">GrossAmount</th>
                                                <th style="background: #000 !important; color:#fff !important;">Disc(%)</th>
                                                <th style="background: #000 !important; color:#fff !important;">DiscAmount</th>
                                                <th style="background: #000 !important; color:#fff !important;">Tax (%)</th>
                                                <th style="background: #000 !important; color:#fff !important;">TaxAmount</th>
                                                <th style="background: #000 !important; color:#fff !important;">Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // $count=1;
                                            // $total_before_tax=0;
                                            // $total_tax=0;
                                            // $total_after_tax=0;
                                            // $currency = '-';
                                            // if ($sales_tax_invoice->currency!=0):
                                            // $currency = $sales_tax_invoice->currencyRelation->curreny;
                                            // $currency_rate = $sales_tax_invoice->currency_rate;
                                            // endif;
                                            // $total_tax_amount = 0 ;
                                            // $total_qty = 0;
                                            ?>

                                             @php
                                                    $count = 1;

                                                    @endphp

                                            @foreach ($sales_tax_invoice_data as $row)
                                                @php
                                                   
                                                    $saleOrderDetail = CommonHelper::get_item_detials($row->so_data_id);
                                                    $productbarcode = CommonHelper::product_barcode($row->item_id);
                                                @endphp
                                                <tr>
                                                    {{-- $total_expense = 0;
                                            $total_before_tax += $row->rate * $row->qty;
                                            <!-- $total_tax += $row->tax_amount; -->
                                            $total_tax = number_format($items->sum('tax_amount'), 2, '.', '');

                                            
                                            $total_after_tax += $row->amount; --}}
                                                <tr>
                                                    <td style="text-align: center !important;">{{ $count++ }}</td>
                                                    <td style="width: 20%">{{ CommonHelper::get_product_sku($row->item_id) }}-{{ CommonHelper::get_product_name($row->item_id) }}</td>
                                                    <!-- <td style="text-align: center !important;" class="wsale2">{{ CommonHelper::get_uom($row->item_id) }}</td> -->
                                                    <td style="text-align: center !important;" class="wsale2"><p><strong>{{ $productbarcode ?? '--' }}</strong></p></td>
                                                    <td style="text-align: center !important;" class="wsale2"><p>{{ number_format($row->qty) }}</p></td>
                                                    <td style="text-align: center !important;">{{ CommonHelper::get_product_mrp_price($row->item_id) }}</td>
                                                    <td style="text-align: center !important;">{{ number_format($row->rate, 2) }}</td>
                                                    <td style="text-align: center !important;"> {{ number_format($saleOrderDetail->sub_total, 2) }}</td>
                                                    <td style="text-align: center !important;">{{ number_format($saleOrderDetail->discount_percent_1, 2) }}% </td>
                                                    <td style="text-align: center !important;">{{ number_format($saleOrderDetail->discount_amount_1, 2) }}</td>
                                                    <td style="text-align: center !important;">{{ number_format($row->tax, 2) }}%</td>
                                                    <td style="text-align: center !important;">{{ number_format($row->tax_amount, 2) }}</td>
                                                    <td style="text-align: center !important;">{{ number_format($saleOrderDetail->amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    {{-- <td style="text-align: center !important;"> {{ $count++ }} </td> --}}
                                                    {{-- <td style="text-align: center !important;">{{  CommonHelper::get_product_name($row->item_id) }}</td> --}}
                                                    {{-- <td style="text-align: center !important;">{{ CommonHelper::get_uom($row->item_id) }}</td> --}}
                                                    {{-- <td style="text-align: center !important;">{{ $currency }}</td> --}}
                                                    {{-- <td class="text-right" style="text-align: center !important;">{{ $row->qty }}</td> --}}
                                                    {{-- <td class="text-right" style="text-align: center !important;">{{ number_format($row->rate,2) }}</td> --}}
                                                    {{-- <td class="text-right" style="text-align: center !important;">{{ number_format($row->rate * $row->qty,2) }}</td>
                                                    <td class="text-right" style="text-align: center !important;">{{ $row->tax }}</td>
                                                    <td class="text-right" style="text-align: center !important;">{{ number_format($row->tax_amount,2) }}</td>
                                                    <td class="text-right" style="text-align: center !important;">{{ number_format($row->amount,2) }}</td> --}}
                                                    @php
                                                        // $total_tax_amount += $row->tax_amount;
                                                        // $total_qty += $row->qty;
                                                    @endphp
                                                </tr>
                                            @endforeach
                                            @if ($AddionalExpense->count() > 0)
                                                @php $ExpCounter=1; @endphp
                                                @foreach ($AddionalExpense as $Fil)
                                                    <tr class="text-center">
                                                        <td style="text-align: center !important;" colspan="8">
                                                            @php
                                                                $Accounts = CommonHelper::get_single_row('accounts', 'id', $Fil->acc_id);
                                                                $total_expense += $Fil->amount;
                                                            @endphp
                                                            <strong>{{ strtoupper($Accounts->name) }}</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;font-size:13px!important;font-weight:400!important;" colspan="3">Sub Total</th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;">{{ number_format($total_qty, 2) }}</th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"  colspan="2"></th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"><p style="text-align: center !important;">{{ number_format($total_before_tax, 2) }}</p> </th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"></th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;">{{ number_format($total_discount_amount, 2) }}</th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;"> </th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"> <p style="text-align: center !important;">{{ number_format($total_tax, 2) }}</p></th>
                                                <th style="background: transparent; border-bottom: 1px solid #000 !important; padding:0px 5px !important; margin:0 !important;text-align: center !important;"> <p style="text-align: center !important;">{{ number_format($total_amount_after_tax, 2) }}</p> </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">
                            <div class="col-md-10">
                                <h2 class="subHeadingLabelClass">Sub Total</h2>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 ">
                                <div class="padt">
                                    <!-- <ul class="sale-l sale-l2">
                                        <li>Total Product</li>
                                        <li class="text-left">
                                            <input name="total-product" class="form-control form-control2" value=""
                                                type="text">
                                        </li>
                                    </ul> -->
                                    <ul class="sale-l sale-l2">
                                        <li>Total Qty</li>
                                        <li class="text-left">
                                            <input name="total_qty" class="form-control form-control2" id="total_qty" value="{{$total_qty}}" type="text" readonly>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 ">
                                <div class="padt">
                                    <ul class="sale-l sale-l2">
                                        <li>Total FOC</li>
                                        <li class="text-left">
                                            <input name="total-fac" class="form-control form-control2" value=""
                                                type="text">
                                        </li>
                                    </ul>
                                </div>
                            </div> -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 ">
                                <div class="padt">
                                    <ul class="sale-l sale-l2">
                                        <li>Gross Amount</li>
                                        <li class="text-left">
                                            <input name="total_gross_amount" id="total_gross_amount" class="form-control form-control2" value="{{$total_before_tax}}" type="text" readonly>
                                        </li>
                                    </ul>
                                    <!-- <ul class="sale-l sale-l2">
                                        <li>Total Qty</li>
                                        <li class="text-left"><input name="total-qty" class="form-control form-control2"
                                                value="" type="text"></li>
                                    </ul> -->
                                    <!-- <ul class="sale-l sale-l2">
                                        <li>Disc</li>
                                        <li class="text-left"><input name="disc" class="form-control form-control2"
                                                value="" type="text"></li>
                                    </ul>
                                    <ul class="sale-l sale-l2">
                                        <li>Disc 2</li>
                                        <li class="text-left"><input name="disc2" class="form-control form-control2"
                                                value="" type="text"></li>
                                    </ul> -->
                                    <ul class="sale-l sale-l2">
                                        <li>Tax Amount</li>
                                        <li class="text-left">
                                            <input name="total_sales_tax" id="total_sales_tax" class="form-control form-control2" value="{{$total_tax}}" type="text" readonly>
                                        </li>
                                    </ul>
                                    <ul class="sale-l sale-l2">
                                        <li>Net Amount</li>
                                        <li class="text-left">
                                            <input name="total_amount_after_sale_tax" id="total_amount_after_sale_tax"class="form-control form-control2" value="{{$sales_tax_invoice->total}}" type="text" readonly>
                                                <!-- class="form-control form-control2" value="{{$total_after_tax}}" type="text" readonly> -->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row align-items-top">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <!-- <div class="totlas totlass">
                            <h2>Note</h2>
                            <p>{{ $so_detail->remark ?? 'N/A' }}</p>
                            
                        </div> -->
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <br>
                        <br>
                        <br>
                        <div class="totals3">
                            <div class="psds">
                                {{ CommonHelper::get_sale_tax_persentage_by_id($sale_order->sale_taxes_id) }}
                                <p id="sale_taxes_amount_rate"style="margin:0 !important;padding:0 !important;font-size:13px !important;font-weight:500 !important;">
                                    {{ $sale_order->sale_taxes_amount_rate }}</p>
                            </div>
                            <div class="totlas">
                                <p>Total</p>
                                <p>{{ number_format((float) $total_amount_after_tax + (float) $sale_order->sale_taxes_amount_rate, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fo">
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div style="text-align: left" class="printHide">
                            {{-- <label class="text-left"><input type="checkbox" onclick="show_hide()" id="formats" />Printable Format </label> --}}
                            <!-- <label class="text-left"><input type="checkbox" onclick="show_hide2()" id="formats2" />Bundle Printable Format </label> -->
                        </div>
                        <?php
                        ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" id="total" value="{{ $AmountInWordsMain }}">
                            <!--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left printHide">
                                <label for="">Show Voucher <input type="checkbox" id="ShowVoucher" onclick="ViewVoucher()"></label>
                            </div> -->
                            <?php
                            $Trans = DB::Connection('mysql2')->table('transactions')->whereIn('status',[1,100])->where('voucher_no',$sales_tax_invoice->gi_no)->orderBy('debit_credit',1);
                            if($Trans->count() > 0){
                            ?>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ShowVoucherDetail" id="ShowVoucherDetail" style="display: none">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed sale-list userlittab table table-bordered sf-table-list sales_Tax_Invoice_data">
                                        <thead>
                                            <tr>
                                                <td colspan="4"><strong><h4>Sales Invoice</h4></strong></td>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Sr No</th>
                                                <th class="text-center">Account Head<span class="rflabelsteric"></span> </th>
                                                <th class="text-center">Debit<span class="rflabelsteric"></span></th>
                                                <th class="text-center">Credit<span class="rflabelsteric"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $TransCounter = 1;
                                            $DrTot = 0;
                                            $CrTot = 0;
                                            foreach($Trans->where('voucher_type',6)->get() as $Fil): ?>
                                            <tr class="text-center">
                                                <td style="border:1px solid black;"><?php echo $TransCounter++; ?></td>
                                                <td style="border:1px solid black;">
                                                    <?php $Accounts = CommonHelper::get_single_row('accounts', 'id', $Fil->acc_id);
                                                    echo $Accounts->name ?? '';
                                                    ?>
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php if ($Fil->debit_credit == 1):
                                                        echo number_format($Fil->amount, 2);
                                                        $DrTot += $Fil->amount;
                                                    endif; ?>
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php if ($Fil->debit_credit == 0):
                                                        echo number_format($Fil->amount, 2);
                                                        $CrTot += $Fil->amount;
                                                    endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <tr class="text-center">
                                                <td colspan="2">TOTAL</td>
                                                <td><?php echo number_format($DrTot, 2); ?></td>
                                                <td><?php echo number_format($CrTot, 2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ShowVoucherDetail" id="" style="display: none">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed sale-list userlittab table table-bordered sf-table-list sales_Tax_Invoice_data">
                                        <thead>
                                            <tr>
                                                <td colspan="4"><strong><h4>COGS</h4></strong></td>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Sr No</th>
                                                <th class="text-center">Account Head<span class="rflabelsteric"></span></th>
                                                <th class="text-center">Debit<span class="rflabelsteric"></span></th>
                                                <th class="text-center">Credit<span class="rflabelsteric"></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $TransCounter = 1;
                                            $DrTot = 0;
                                            $CrTot = 0;
                                            $Trans = DB::Connection('mysql2')->table('transactions')->whereIn('status',[1,100])->where('voucher_no',$sales_tax_invoice->gi_no)->orderBy('debit_credit',1);
                                            foreach($Trans->where('voucher_type',8)->get() as $Fil): ?>
                                            <tr class="text-center">
                                                <td style="border:1px solid black;"><?php echo $TransCounter++; ?></td>
                                                <td style="border:1px solid black;">
                                                    <?php $Accounts = CommonHelper::get_single_row('accounts', 'id', $Fil->acc_id);
                                                    echo $Accounts->name ?? '';
                                                    ?>
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php if ($Fil->debit_credit == 1):
                                                        echo number_format($Fil->amount, 2);
                                                        $DrTot += $Fil->amount;
                                                    endif; ?>
                                                </td>
                                                <td style="border:1px solid black;">
                                                    <?php if ($Fil->debit_credit == 0):
                                                        echo number_format($Fil->amount, 2);
                                                        $CrTot += $Fil->amount;
                                                    endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <tr class="text-center">
                                                <td colspan="2">TOTAL</td>
                                                <td><?php echo number_format($DrTot, 2); ?></td>
                                                <td><?php echo number_format($CrTot, 2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php }?>
                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printHide">
                                <div class="row text-left">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo 'Description:' . ' ' . strtoupper($sales_tax_invoice->description); ?>
                                    </div>
                                </div>
                            </div> -->

                             <div class="row align-items-top">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="totlass">
                                        <h2>Note</h2>
                                        <p>{{ $sale_order->remark ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                            <img src="data:image/png;base64, { !! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request Voucher Detail (Office Use)'))!!} ">
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <!-- Signature -->
    <div class="row">
        <div class="sgnature">
           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center sgnature22">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <p><strong>Prepared By:</strong> </p>
                        <p><strong><?php echo strtoupper($sales_tax_invoice->username); ?></strong> </p>
                    </div>

                     <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <p><strong>Approved By:</strong> </p>
                        <p><strong><?php echo strtoupper($sales_tax_invoice->approve_user_1); ?></strong> </p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <p><strong>Approved By:</strong> </p>
                        <p><strong><?php echo strtoupper($sales_tax_invoice->approve_user_2); ?></strong> </p>
                    </div>
                </div>
                <br>
                <br>
                 <p><strong style="margin-right: 71%;">
                                            Creation Time :
                                            {{ \Carbon\Carbon::parse($sale_order->timestamp)->format('d-M-Y h:i A') }}
                                        </strong></p>
            </div>
        </div>
    </div>
</div>

<div id="#print-me"></div>
<script>
    function show_hide() {
        if ($('#formats').is(":checked")) {
            $("#actual").css("display", "none");
            $("#printable").css("display", "block");
        } else {
            $("#actual").css("display", "block");
            $("#printable").css("display", "none");
        }
    }

    function show_hide2() {
        if ($('#formats2').is(":checked")) {
            $(".ShowHideHtmlNone").fadeOut("slow");
            $(".ShowHideHtml").fadeIn("slow");

            //                $("#printable").css("display", "block");
        } else {
            $(".ShowHideHtmlNone").fadeIn("slow");
            $(".ShowHideHtml").fadeIn("slow");

            //                $("#printable").css("display", "none");
        }
    }

    $(document).ready(function() {

        toWords(1);
        var tax = '{{ $total_tax_amount }}';
        if (tax == 0) {
            $('#tax').html('Commerical Invoice');
        }

    });

    function change()
    {


        if (!$('.showw').is(':visible')) {
            $(".showw").css("display", "block");

        } else {
            $(".showw").css("display", "none");

        }

    }

    function ViewVoucher() {
        if ($('#ShowVoucher').is(':checked')) {
            $('.ShowVoucherDetail').css('display', 'block');
        } else {
            $('.ShowVoucherDetail').css('display', 'none');
        }
    }
    var th = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
    var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen',
        'Nineteen'
    ];
    var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function toWords(id) {

        s = $('#total').val();


        s = s.toString();
        s = s.replace(/[\, ]/g, '');
        if (s != parseFloat(s)) return 'not a number';
        var x = s.indexOf('.');
        if (x == -1)
            x = s.length;
        if (x > 15)
            return 'too big';
        var n = s.split('');
        var str = '';
        var sk = 0;
        for (var i = 0; i < x; i++) {
            if ((x - i) % 3 == 2) {
                if (n[i] == '1') {
                    str += tn[Number(n[i + 1])] + ' ';
                    i++;
                    sk = 1;
                } else if (n[i] != 0) {
                    str += tw[n[i] - 2] + ' ';
                    sk = 1;
                }
            } else if (n[i] != 0) { // 0235
                str += dg[n[i]] + ' ';
                if ((x - i) % 3 == 0) str += 'hundred ';
                sk = 1;
            }
            if ((x - i) % 3 == 1) {
                if (sk)
                    str += th[(x - i - 1) / 3] + ' ';
                sk = 0;
            }
        }

        if (x != s.length) {
            var y = s.length;
            str += 'point ';
            for (var i = x + 1; i < y; i++)
                str += dg[n[i]] + ' ';
        }
        result = str.replace(/\s+/g, ' ') + 'Only';

        $('#rupees').text(result);
        $('#rupees' + id).text(result);
        $('#rupees').val(result);
        $('#rupeess' + id).val(result);

        var currency = $('#curren :selected').text();
        currency = currency.split('-');
        var text = $('#rupees').text();
        text = text + ' ' + '' + currency[0] + '';

        $('#rupees').text(text);


    };

    $('.btn-info').click(function() {
        $('.printHide').css('display', 'none');
    });

    $("#print").click(function() {


        var content = $("#printPurchaseRequestVoucherDetail").html();
        document.body.innerHTML = content;
        //var content = document.getElementById('header').innerHTML;
        //var content2 = document.getElementById('content').innerHTML;

    });

    function approve(id) {
        $("#appro").attr("disabled", true);
        $.ajax({
            url: "{{ url('sales/si_approve') }}",
            type: 'Get',
            data: {
                id: id
            },

            success: function(response) {
                if (response == 0) {
                    alert('stock not avaiable');
                    return;
                }
                $('#stat' + id).html(response);
                $('#showDetailModelOneParamerter').modal('hide');

            }
        })
    }
</script>
<script>
  function printSection() {
    // ✅ Print CSS dynamically add karna
    const printStyle = `
      @media print {
        @page{size:A4;margin:10mm 10mm 10mm 10mm !important;}
        
 .signature_bor{border-top:solid 1px #CCC;padding-top:7px;}
textarea{border-style:none;border-color:Transparent;}
.vomp{text-align:left;}
p{margin:0;padding:0;font-size:13px;font-weight:500;}
input.form-control.form-control2{margin:0 !important;}
.table-bordered>thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th{vertical-align:inherit !important;text-align:left !important;padding:7px 5px !important;}
.totlas{display:flex;justify-content:right;gap:70px;background:#ddd;width:18%;float:right;padding-right:8px;}
.totlas p{font-weight:bold;}
.psds{display:flex;justify-content:right;gap:88px;}
.psds p{font-weight:bold;}
.userlittab>thead>tr>td,.userlittab>tbody>tr>td,.userlittab>tfoot>tr>td{padding:10px 5px !important;}
.totlass{display:inline;background:transparent;margin-top:-25px;}
.totlass h2{font-size:13px !important;}
table.table-bordered>tbody>tr>td{border:none !important;border-bottom:1px solid #000 !important;}
.table-bordered>thead>tr>th,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>th{vertical-align:inherit !important;text-align:left !important;padding:7px 5px !important;font-size:13px !important;}
.table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th{font-weight:400 !important;}

.table-responsive .sale_older_tab > caption + thead > tr:first-child > th,.sale_older_tab > colgroup + thead > tr:first-child > th,.sale_older_tab > thead:first-child > tr:first-child > th,.sale_older_tab > caption + thead > tr:first-child > td,.sale_older_tab > colgroup + thead > tr:first-child > td,.sale_older_tab > thead:first-child > tr:first-child > td{border-top:0;font-size:10px !important;padding:9px 5px !important;}
.table-responsive .sale_older_tab > thead > tr > th,.sale_older_tab > tbody > tr > th,.sale_older_tab > tfoot > tr > th,.sale_older_tab > thead > tr > td,.sale_older_tab > tbody > tr > td,.table > tfoot > tr > td{padding:2px 5px !important;font-size:11px !important;border-top:1px solid #000000 !important;border-bottom:1px solid #000000 !important;border-left:1px solid #000000 !important;border-right:1px solid #000000 !important;}
.table-responsive{height:inherit !important;}
// .sgnature{position:absolute !important;bottom:20px !important;width:100% !important;}
.sales_or{position:relative !important;}
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
.contr2 {
    text-align: right;
}
.hide{
 display:none; 
}

      }
    `;

    // ✅ Select element to print
    const printContent = document.getElementById('po_detail').innerHTML;
    // ✅ Open new window for print
    const printWindow = window.open('', '', 'width=900,height=700');
    // ✅ Bootstrap 5 CSS link
    const bootstrapCSS = `<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">`;
    // ✅ Write content to print window
    printWindow.document.write(`
      <html>
      <head>
        <title>Print Preview</title>
        ${bootstrapCSS}
        <style>${printStyle}</style>
      </head>
      <body>
        ${printContent}
      </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    // printWindow.close(); // optional
  }
</script>
<!-- </head>
<body> -->