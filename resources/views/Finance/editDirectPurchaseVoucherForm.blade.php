<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
use Carbon\Carbon;

$MenuPermission = true;
$accType = Auth::user()->acc_type;
$m = Session::get('run_company');
$current_date = date('Y-m-d');

$startDate = Carbon::parse($NewPurchaseVoucher->bill_date);
$endDate = Carbon::parse($NewPurchaseVoucher->due_date);
$model_terms_of_payment = $endDate->diffInDays($startDate);

$totalItemRows = count($NewPurchaseVoucherData);

if ($accType == 'user'):
    $user_rights = DB::table('menu_privileges')->where([['emp_code', '=', Auth::user()->emp_code], ['compnay_id', '=', Session::get('run_company')]]);
    $submenu_ids = explode(',', $user_rights->value('submenu_id'));
    if (in_array(81, $submenu_ids)) {
        $MenuPermission = true;
    } else {
        $MenuPermission = false;
    }
endif;
?>

@extends('layouts.default')

@section('content')
@include('select2')
@include('modal')
@include('number_formate')

<style>
.select2-container {
    font-size: 11px;
}
.subHeadingLabelClass {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}
.headquid {
    background: #f9f9f9;
    padding: 10px;
    border-bottom: 2px solid #00a65a;
    margin-bottom: 20px;
}
.well_N {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.dp_sdw {
    padding: 10px;
}
.panel-heading-custom {
    background: #f5f5f5;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Edit Direct Purchase Invoice (Finance)</h2>
                            </div>
                            @if(!$MenuPermission)
                                <span class="subHeadingLabelClass text-danger" style="float: right">Permission Denied <span style='font-size:45px !important;'>&#128546;</span></span>
                            @endif
                        </div>
                    </div>

                    @if($MenuPermission)
                    {{ Form::open(['url' => 'pad/updateDirectPurchaseInvoice?m=' . $m, 'id' => 'insertDirectPurchaseInvoice', 'class' => 'stop']) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="panel panel-default" style="border: 1px solid #ddd; box-shadow: 0 1px 5px rgba(0,0,0,0.05);">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <label class="sf-label">PV NO.</label>
                                    <input readonly type="text" class="form-control requiredField" name="pv_no" id="pv_no" value="{{ $NewPurchaseVoucher->pv_no }}" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <label class="sf-label">PV DATE. <strong>*</strong></label>
                                    <input type="date" class="form-control requiredField" max="{{ date('Y-m-d') }}" name="pv_date" id="pv_date" value="{{ $NewPurchaseVoucher->pv_date }}" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <label class="sf-label">Ref / Bill No. <strong>*</strong></label>
                                    <input type="text" class="form-control" placeholder="Ref / Bill No" name="slip_no" id="slip_no" value="{{ $NewPurchaseVoucher->slip_no }}"/>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <label class="sf-label">Bill Date. <strong>*</strong></label>
                                    <input type="date" class="form-control" name="bill_date" id="bill_date" value="{{ $NewPurchaseVoucher->bill_date }}" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <label class="sf-label">Due Date <strong>*</strong></label>
                                    <input type="date" class="form-control" name="due_date" id="due_date" value="{{ $NewPurchaseVoucher->due_date }}" readonly />
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>

                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label class="sf-label">Vendor <strong>*</strong></label>
                                    <select onchange="get_address()" name="supplier_id" id="supplier_id" class="form-control requiredField select2">
                                        <option value="">Select Vendor</option>
                                        @foreach ($supplierList as $row1)
                                            @php
                                                $address = CommonHelper::get_supplier_address($row1->id);
                                            @endphp
                                            <option {{ $NewPurchaseVoucher->supplier == $row1->id ? 'selected' : '' }} value="<?php echo $row1->id . '@#' . $address . '@#' . $row1->ntn . '@#' . $row1->terms_of_payment; ?>"><?php echo ucwords($row1->name); ?></option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label class="sf-label">Mode/ Terms (Days) <strong>*</strong></label>
                                    <input onkeyup="calculate_due_date()" type="number" class="form-control" name="model_terms_of_payment" id="model_terms_of_payment" value="{{ $model_terms_of_payment }}" />
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                    <label class="sf-label">Warehouse / Region</label>
                                    <select onchange="get_address()" name="warehouse_id" id="warehouse_id" class="form-control select2">
                                        <option value="">Select</option>
                                        @foreach (CommonHelper::get_all_warehouse() as $row1)
                                            <option {{ $NewPurchaseVoucher->warehouse == $row1->id ? 'selected' : '' }} value="{{ $row1->id }}">{{ $row1->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                    <label class="sf-label">Cost Center</label>
                                    <select class="form-control select2" name="sub_department_id" id="sub_department_id">
                                        <option value="">Select Department</option>
                                        @foreach($departmentsTwo as $key => $y)
                                            <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                @php
                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` ='.$y->id.'');
                                                @endphp
                                                @foreach($subdepartments as $key2 => $y2)
                                                    <option {{ $NewPurchaseVoucher->sub_department_id == $y2->id ? 'selected' : '' }} value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="sf-label">Remarks <strong>*</strong></label>
                                    <textarea name="main_description" id="main_description" rows="2" class="form-control requiredField" style="resize:none;">{{ $NewPurchaseVoucher->description }}</textarea>
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background: #00a65a; color: #fff;">
                                            <th style="width: 40%;">Account Head / Item</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">Discount%</th>
                                            <th class="text-right">Discount Amt</th>
                                            <th class="text-right">Net Amount</th>
                                            <th class="text-center" style="width: 100px;">
                                                <button type="button" class="btn btn-xs btn-primary" onclick="AddMoreDetails()">Add Row</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="AppnedHtml">
                                        @foreach ($NewPurchaseVoucherData as $key => $DFil)
                                        <tr id="RemoveRows{{ $key+1 }}" title="{{ $key+1 }}" class="AutoNo">
                                            <td>
                                                <select name="item_id[]" id="sub_{{ $key+1 }}" onchange="itemChange({{ $key+1 }})" class="form-control select2">
                                                    @foreach(CommonHelper::get_all_account_operat() as $key1 => $y)
                                                        <option @if($DFil->sub_item == $y->id) selected @endif value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                    @endforeach
                                                </select>
                                                <!-- Hidden fields maintained for backward compatibility -->
                                                <input readonly type="hidden" name="uom_id[]" id="uom_id{{ $key+1 }}" value="{{ $DFil->uom }}">
                                                <input type="hidden" name="actual_qty[]" id="actual_qty{{ $key+1 }}" value="{{ $DFil->qty }}">
                                                <input type="hidden" name="rate[]" id="rate{{ $key+1 }}" value="{{ $DFil->rate }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control text-right requiredField" name="amount[]" id="amount{{ $key+1 }}" value="{{ $DFil->amount }}" onkeyup="amount_calculation({{ $key+1 }})" >
                                            </td>
                                            <td>
                                                @php
                                                    $discount_percent = 0;
                                                    if($DFil->discount_amount != 0){
                                                        $total_val = (float)$DFil->discount_amount + (float)$DFil->net_amount;
                                                        $discount_percent = ($total_val > 0) ? ($DFil->discount_amount / $total_val * 100) : 0;
                                                    }
                                                @endphp
                                                <input type="number" step="0.01" onkeyup="discount_percent(this.id)" class="form-control text-right" name="discount_percent[]" id="discount_percent{{ $key+1 }}" value="{{ number_format($discount_percent, 2, '.', '') }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" onkeyup="discount_amount(this.id)" class="form-control text-right" name="discount_amount[]" id="discount_amount{{ $key+1 }}" value="{{ $DFil->discount_amount }}">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control text-right requiredField net_amount_dis" name="after_dis_amount[]" id="after_dis_amount{{ $key+1 }}" value="{{ $DFil->net_amount }}" readonly>
                                            </td>
                                            <td class="text-center">
                                                @if($key > 0)
                                                    <button type="button" class="btn btn-xs btn-danger" onclick="RemoveSection({{ $key+1 }})"><i class="fa fa-minus"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background: #f9f9f9; font-weight: bold;">
                                            <td class="text-right" style="vertical-align: middle;">TOTAL</td>
                                            <td colspan="3"></td>
                                            <td>
                                                <input readonly class="form-control text-right" type="text" id="net" />
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row" style="margin-top: 10px;">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="text-center" style="vertical-align: middle; font-weight: bold; width: 50%;">Sales Tax Head</td>
                                                <td>
                                                    <select onchange="sales_tax(this.id)" class="form-control select2" id="sales_taxx" name="sales_taxx">
                                                        <option value="0">Select</option>
                                                        @foreach (ReuseableCode::get_all_sales_tax() as $row)
                                                            <option value="{{ $row->percent.'@'.$row->acc_id }}" {{($row->percent == "17.000")? 'selected' : ''}}>{{ $row->percent }} %</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" style="vertical-align: middle; font-weight: bold;">Sales Tax Amount</td>
                                                <td>
                                                    <input type="text" class="form-control text-right" name="sales_amount_td" id="sales_amount_td" value="{{ $NewPurchaseVoucher->sales_tax_amount }}" readonly />
                                                    <input type="hidden" name="sales_amount" id="sales_tax_amount" value="{{ $NewPurchaseVoucher->sales_tax_amount }}" />
                                                </td>
                                            </tr>
                                            <tr style="background: #f5f5f5; font-size: 16px; font-weight: bold;">
                                                <td class="text-center" style="vertical-align: middle;">Net Total</td>
                                                <td>
                                                    <input readonly class="form-control text-right" type="text" id="net_after_tax" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <p id="rupeeText" class="text-info" style="font-weight: bold; text-transform: capitalize; margin: 10px 0;"></p>
                                    <input type="hidden" name="rupeess" id="rupeess1" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 text-right" style="margin-top: 20px;">
                                    <a href="{{ url()->previous() }}" class="btn btn-default btn-lg">Cancel</a>
                                    {{ Form::submit('Update Voucher', ['class' => 'btn btn-success btn-lg', 'style' => 'min-width: 150px;']) }}
                                </div>
                            </div>

                        </div>
                    </div>
                    {{ Form::close() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var Counter = {{ $totalItemRows }};

    function itemChange(id) {
        // Compatibility with legacy code expecting sub_item data
        var uom = $('#sub_' + id).find(':selected').data("uom");
        if(uom) $('#uom_id' + id).val(uom);
    }

    function AddMoreDetails() {
        Counter++;
        var options = `@foreach(CommonHelper::get_all_account_operat() as $y)
                        <option value="{{ $y->id}}">{{ $y->code .' ---- '. $y->name}}</option>
                       @endforeach`;
        
        $('#AppnedHtml').append(`
            <tr id="RemoveRows${Counter}" class="AutoNo">
                <td>
                    <select name="item_id[]" id="sub_${Counter}" onchange="itemChange(${Counter})" class="form-control select2">
                        <option value="">Select Account</option>
                        ${options}
                    </select>
                    <input type="hidden" name="uom_id[]" id="uom_id${Counter}">
                    <input type="hidden" name="actual_qty[]" id="actual_qty${Counter}" value="1">
                    <input type="hidden" name="rate[]" id="rate${Counter}" value="0">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control text-right requiredField" name="amount[]" id="amount${Counter}" onkeyup="amount_calculation(${Counter})">
                </td>
                <td>
                    <input type="number" step="0.01" onkeyup="discount_percent(this.id)" class="form-control text-right" name="discount_percent[]" id="discount_percent${Counter}" value="0.00">
                </td>
                <td>
                    <input type="number" step="0.01" onkeyup="discount_amount(this.id)" class="form-control text-right" name="discount_amount[]" id="discount_amount${Counter}" value="0.00">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control text-right requiredField net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" value="0.00" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-danger" onclick="RemoveSection(${Counter})"><i class="fa fa-minus"></i></button>
                </td>
            </tr>
        `);
        $('.select2').select2({ width: '100%' });
    }

    function RemoveSection(Row) {
        $('#RemoveRows' + Row).remove();
        net_amount();
    }

    function amount_calculation(number) {
        var amount = parseFloat($('#amount' + number).val()) || 0;
        $('#after_dis_amount' + number).val(amount.toFixed(2));
        discount_percent('discount_percent' + number);
        net_amount();
        sales_tax('sales_taxx');
    }

    function discount_percent(id) {
        var number = id.replace("discount_percent", "");
        var amount = parseFloat($('#amount' + number).val()) || 0;
        var percent = parseFloat($('#' + id).val()) || 0;
        
        if (percent > 100) {
            alert('Percentage Cannot Exceed 100');
            $('#' + id).val(0);
            percent = 0;
        }
        
        var discount_amount = (percent * amount) / 100;
        $('#discount_amount' + number).val(discount_amount.toFixed(2));
        
        var net = amount - discount_amount;
        $('#after_dis_amount' + number).val(net.toFixed(2));
        
        net_amount();
    }

    function discount_amount(id) {
        var number = id.replace("discount_amount", "");
        var amount = parseFloat($('#amount' + number).val()) || 0;
        var discount_amount = parseFloat($('#' + id).val()) || 0;
        
        if (discount_amount > amount) {
            alert('Discount Cannot Exceed Amount');
            $('#' + id).val(0);
            discount_amount = 0;
        }
        
        var percent = (amount > 0) ? (discount_amount / amount * 100) : 0;
        $('#discount_percent' + number).val(percent.toFixed(2));
        
        var net = amount - discount_amount;
        $('#after_dis_amount' + number).val(net.toFixed(2));
        
        net_amount();
    }

    function sales_tax(id) {
        var val = $('#sales_taxx').val();
        if(!val) return;
        
        var rate = parseFloat(val.split('@')[0]) || 0;
        var net = parseFloat($('#net').val()) || 0;
        var tax_amount = (net * rate) / 100;
        
        $('#sales_amount_td').val(tax_amount.toFixed(2));
        $('#sales_tax_amount').val(tax_amount.toFixed(2));
        
        var final_net = net + tax_amount;
        $('#net_after_tax').val(final_net.toFixed(2));
        updateAmountInWords(final_net.toFixed(2));
    }

    function net_amount() {
        var total = 0;
        $('.net_amount_dis').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#net').val(total.toFixed(2));
        sales_tax('sales_taxx');
    }

    function calculate_due_date() {
        var dateVal = $("#pv_date").val();
        if (!dateVal) return;
        var date = new Date(dateVal);
        var days = parseFloat($('#model_terms_of_payment').val()) || 0;
        if (!isNaN(date.getTime())) {
            date.setDate(date.getDate() + days);
            var yyyy = date.getFullYear();
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var dd = String(date.getDate()).padStart(2, '0');
            $("#due_date").val(yyyy + "-" + mm + "-" + dd);
        }
    }

    function get_address() {
        var supplier = $('#supplier_id').val();
        if (!supplier) return;
        var parts = supplier.split('@#');
        $('#model_terms_of_payment').val(parts[parts.length - 1]);
        calculate_due_date();
    }

    // Amount to Words Logic
    var th = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
    var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function updateAmountInWords(s) {
        s = s.toString().replace(/[\, ]/g, '');
        if (s != parseFloat(s)) return;
        var x = s.indexOf('.');
        if (x == -1) x = s.length;
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
            } else if (n[i] != 0) {
                str += dg[n[i]] + ' ';
                if ((x - i) % 3 == 0) str += 'hundred ';
                sk = 1;
            }
            if ((x - i) % 3 == 1) {
                if (sk) str += th[(x - i - 1) / 3] + ' ';
                sk = 0;
            }
        }
        if (x != s.length) {
            var y = s.length;
            str += 'point ';
            for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ';
        }
        var result = str.replace(/\s+/g, ' ') + 'Only';
        $('#rupeeText').text('Amount In Words: ' + result);
    }

    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        net_amount();
    });
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
