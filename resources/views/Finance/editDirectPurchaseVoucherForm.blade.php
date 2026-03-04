<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
use Carbon\Carbon;
$MenuPermission = true;

$accType = Auth::user()->acc_type;
$m=Session::get('run_company');
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

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

    <script>
        var counter = 1;
    </script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Edit Direct Purchase Invoice</span>
                                <?php
                        if($MenuPermission == true):?>
                                <?php else:?>
                                <span class="subHeadingLabelClass text-danger text-center" style="float: right">Permission
                                    Denied <span style='font-size:45px !important;'>&#128546;</span></span>
                                <?php endif;

                        ?>
                            </div>
                        </div>
                        <?php if($MenuPermission == true):?>
                        <div class="lineHeight">&nbsp;</div>


                        {{ Form::open(['url' => 'pad/updateDirectPurchaseInvoice?m=' . $m . '', 'id' => 'insertDirectPurchaseInvoice', 'class' => 'stop']) }}
                        @php
                            $pv_no = CommonHelper::uniqe_no_for_purcahseVoucher(date('y'), date('m'));
                        @endphp
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">PV NO.</label>
                                                <input readonly type="text" class="form-control requiredField"
                                                    placeholder="" name="pv_no" id="pv_no"
                                                    value="{{ $NewPurchaseVoucher->pv_no ?? strtoupper($pv_no) }}" />
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">PV DATE.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField"
                                                    max="{{ date('Y-m-d') }}" name="pv_date" id="pv_date"
                                                    value="{{ $NewPurchaseVoucher->pv_date ?? '' }}" />
                                            </div> 
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Ref / Bill No. <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input type="text" class="form-control" placeholder="Ref / Bill No" name="slip_no" id="slip_no"
                                                       value="{{ $NewPurchaseVoucher->slip_no ?? '' }}"/>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Bill Date.</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control"  name="bill_date" id="bill_date" value="{{ $NewPurchaseVoucher->bill_date ?? '' }}" />
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Due Date <span class="rflabelsteric"><strong>*</strong></span></label>
                                                <input  type="date" class="form-control" placeholder="" name="due_date" id="due_date" value="{{ $NewPurchaseVoucher->due_date ?? '' }}" readonly />
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label"> <a href="#"
                                                        onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');"
                                                        class="">Vendor</a></label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select onchange="get_address()" name="supplier_id" id="supplier_id"
                                                    class="form-control requiredField select2">
                                                    <option value="">Select Vendor</option>
                                                    @foreach ($supplierList as $row1)
                                                        @php
                                                            $address = CommonHelper::get_supplier_address($row1->id);
                                                        @endphp
                                                        <option {{ $NewPurchaseVoucher->supplier == $row1->id ? 'selected' : '' }} value="<?php echo $row1->id . '@#' . $address . '@#' . $row1->ntn . '@#' . $row1->terms_of_payment; ?>"><?php echo ucwords($row1->name); ?></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Mode/ Terms Of Payment <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                <input onkeyup="calculate_due_date()" type="number"
                                                    class="form-control" placeholder=""
                                                    name="model_terms_of_payment" id="model_terms_of_payment"
                                                    value="{{ $model_terms_of_payment }}" />
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Warehouse / Region <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                        <select onchange="get_address()" name="warehouse_id" id="warehouse_id"
                                                        class="form-control select2">
                                                        <option value="">Select</option>
                                                        @foreach (CommonHelper::get_all_warehouse() as $row1)
                                                       
                                                       <option {{ $NewPurchaseVoucher->warehouse == $row1->id ? 'selected' : '' }} value="{{ $row1->id }}">{{ $row1->name }}</option>     
                                                        @endforeach
                                                    </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                <label class="sf-label">Cr Account <span
                                                        class="rflabelsteric"><strong>*</strong></span></label>
                                                <select class="form-control select2" name="sub_department_id" id="sub_department_id">
                                                    <option value="">Select Department</option>
                                                    @foreach(CommonHelper::get_accounts_by_parent_code('1-6') as $key => $y)
                                                        <option {{ $NewPurchaseVoucher->sub_department_id == $y->id ? 'selected' : '' }} value="{{ $y->id}}">{{ $y->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>
                                    </div>
                                    <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                                        <label class="sf-label">Remarks</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <textarea name="main_description" id="main_description" rows="4" cols="50"
                                            style="resize:none;font-size: 11px;" class="form-control requiredField">{{ $NewPurchaseVoucher->description }}</textarea>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th colspan="12" class="text-center">Purchase Order Detail</th>
                                                        <th colspan="3" class="text-center">
                                                            <input type="button" class="btn btn-sm btn-primary"
                                                                onclick="AddMoreDetails()" value="Add More Rows" />
                                                        </th>
                                                        <th class="text-center">
                                                            <span class="badge badge-success" id="span">{{ count($NewPurchaseVoucherData) }}</span>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 15rem" class="text-center">Brand</th>
                                                        <th class="text-center" style="width: 25rem;">Product</th>
                                                        <th class="text-center">Product Type</th>
                                                        <th class="text-center">Product Barcode</th>
                                                        <th class="text-center">Product Classification</th>
                                                        <th class="text-center">Product Trend</th>
                                                        <th class="text-center">Uom<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center"> QTY<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Rate<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Amount(PKR)<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Tax %<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Tax Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Discount %<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Discount Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Net Amount<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th class="text-center">Add / Delete<span
                                                                class="rflabelsteric"><strong>*</strong></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                    @foreach ($NewPurchaseVoucherData as $key => $DFil)
                                                    <tr id="RemoveRows{{ $key+1 }}" title="{{ $key+1 }}" class="AutoNo">
                                                        <td>
                                                            <select style="width: 15rem;" onChange="get_product_by_brand(this,{{ $key+1 }})" name="brand_id[]" class="form-control select2" id="brand_id{{ $key+1 }}">
                                                                <option value="">Select</option>
                                                                @foreach(CommonHelper::get_all_brand() as $item)
                                                                <option @if($DFil->brand_id == $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select onChange="get_type_barcode_by_product('productName{{ $key+1 }}')" name="item_id[]" id="productName{{ $key+1 }}" class="form-control requiredField select2 itemsclass" style="width:25rem !important;">
                                                                <option value="">Select Products</option>
                                                                @foreach(CommonHelper::get_all_subitem_by_brand($DFil->brand_id) as $item)
                                                                <option @if($DFil->sub_item == $item->id) selected @endif value="{{$item->id}}">{{ $item->product_name != '' ? $item->product_name : $item->sub_ic }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" class="form-control" name="product_type[]" id="product_type{{ $key+1 }}" value="{{ $DFil->product_type ?? '' }}">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode{{ $key+1 }}" value="{{ $DFil->product_barcode ?? '' }}">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" class="form-control" name="classification_name[]" id="product_classification{{ $key+1 }}" value="{{ $DFil->classification_name ?? '' }}">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" class="form-control" name="product_trend[]" id="product_trend{{ $key+1 }}" value="{{ $DFil->product_trend ?? '' }}">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id{{ $key+1 }}" value="{{ $DFil->uom }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="claculation('{{ $key+1 }}')" onblur="claculation('{{ $key+1 }}')" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty{{ $key+1 }}" placeholder="ACTUAL QTY" min="1" value="{{ $DFil->qty }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="claculation('{{ $key+1 }}')" onblur="claculation('{{ $key+1 }}')" class="form-control requiredField ActualRate" name="rate[]" id="rate{{ $key+1 }}" placeholder="RATE" min="1" value="{{ $DFil->rate }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="amount[]" id="amount{{ $key+1 }}" placeholder="AMOUNT" min="1" value="{{ $DFil->amount }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control actual_amount" name="actual_amount[]" id="actual_amount{{ $key+1 }}" placeholder="AMOUNT" min="1" value="{{ $DFil->actual_amount ?? $DFil->amount }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="claculation('{{ $key+1 }}')" class="form-control" name="tax_per[]" id="tax_per{{ $key+1 }}" placeholder="TAX %" value="{{ $DFil->tax_rate ?? 0 }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="claculation('{{ $key+1 }}')" class="form-control" name="tax_amount[]" id="tax_amount{{ $key+1 }}" placeholder="TAX AMOUNT" value="{{ $DFil->tax_amount ?? 0 }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="discount_percent(this.id)" class="form-control requiredField" name="discount_percent[]" id="discount_percent{{ $key+1 }}" placeholder="DISCOUNT" min="1" value="{{ $DFil->discount_percent ?? (($DFil->amount > 0) ? ($DFil->discount_amount / $DFil->amount * 100) : 0) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeyup="discount_amount(this.id)" class="form-control requiredField" name="discount_amount[]" id="discount_amount{{ $key+1 }}" placeholder="DISCOUNT" min="1" value="{{ $DFil->discount_amount }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount{{ $key+1 }}" placeholder="NET AMOUNT" min="1" value="{{ $DFil->net_amount }}" readonly>
                                                        </td>
                                                        <td class="text-center" style="display: flex; gap: 10px;">
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="RemoveSection({{ $key+1 }})"> - </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                                <tbody>
                                                    <tr style="background-color: darkgrey;font-size:large;font-weight: bold">
                                                        <td class="text-center" colspan="15">Total</td>
                                                        <td id="" class="text-right" colspan="1"><input readonly class="form-control" type="text" id="net" /> </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>



                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
                                        <table class="table table-bordered sf-table-list">
                                            <thead>
                                                <th class="text-center" colspan="3">Sales Tax Account Head</th>
                                                <th class="text-center" colspan="3">Sales Tax Amount</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select onchange="sales_tax(this.id)"
                                                            class="form-control select2" id="sales_taxx"
                                                            name="sales_taxx">
                                                            <option value="0">Select</option> SF
                                                            @foreach (ReuseableCode::get_all_sales_tax() as $row)
                                                                <option  value="{{ $row->percent.'@'.$row->acc_id }}" {{($row->acc_id == $NewPurchaseVoucher->sales_tax_acc_id)? 'selected' : ''}}>
                                                                    {{ $row->percent }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-right" colspan="3">
                                                        <input onkeyup="" type="text"
                                                            class="form-control" name="sales_amount_td"
                                                            id="sales_amount_td" value="{{ $NewPurchaseVoucher->sales_tax_amount }}" />
                                                    </td>
                                                    <input type="hidden" name="sales_amount" id="sales_tax_amount" value="{{ $NewPurchaseVoucher->sales_tax_amount }}" />
                                                </tr>


                                            </tbody>

                                            <tbody>
                                                <tr style="font-size:large;font-weight: bold">
                                                    <td class="text-center" colspan="3">Total Amount After Tax</td>
                                                    <td id="" class="text-right" colspan="3"><input readonly
                                                            class="form-control" type="text" id="net_after_tax" />
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="subHeadingLabelClass">Additional Expenses</span>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list">
                                                <thead>
                                                    <th class="text-center">Account Head</th>
                                                    <th class="text-center">Expense Amount</th>
                                                    <th class="text-center">
                                                        <button type="button" class="btn btn-xs btn-primary"
                                                            id="BtnAddMoreExpense" onclick="AddMoreExpense()">More
                                                            Expense</button>
                                                    </th>
                                                </thead>
                                                <tbody id="AppendExpense">
                                                    @foreach($ExpensesData as $keyE => $expense)
                                                    <script>var CounterExpense = {{ $keyE + 1 }};</script>
                                                    <tr id="RemoveExpenseRow{{ $keyE + 1 }}">
                                                        <td>
                                                            <select class="form-control requiredField select2" name="account_id[]" id="account_id{{ $keyE + 1 }}">
                                                                <option value="">Select Account</option>
                                                                @foreach(CommonHelper::get_accounts_by_parent_code('1-6') as $Fil)
                                                                <option @if($expense->category_id == $Fil->id) selected @endif value="{{$Fil->id}}">{{$Fil->code.'--'.$Fil->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="expense_amount[]" id="expense_amount{{ $keyE + 1 }}" class="form-control requiredField" value="{{ $expense->net_amount }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" id="BtnRemoveExpense{{ $keyE + 1 }}" class="btn btn-sm btn-danger" onclick="RemoveExpense({{ $keyE + 1 }})"> - </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <table>
                                    <tr>
                                        <td style="text-transform: capitalize;" id="rupees"></td>
                                        <input type="hidden" value="" name="rupeess" id="rupeess1" />
                                    </tr>
                                </table>
                                <input type="hidden" id="d_t_amount_1" />

                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo Form::close(); ?>
                    <?php endif;?>
                </div>

            </div>
        </div>
    </div>
    <script>
        function itemChange(id) {
            $('#uom_id' + id).val($('#sub_' + id).find(':selected').data("uom"))
        }

        function get_product_by_brand(element, number, selected_brand_id = null) {
            var brand_id = $(element).val();
            if (brand_id) {
                $.ajax({
                    url: "{{ url('pdc/get_product_by_brand') }}",
                    type: "GET",
                    data: { brand_id: brand_id },
                    success: function(data) {
                        $('#productName' + number).empty();
                        $('#productName' + number).append('<option value="">Select Products</option>');
                        $.each(data, function(key, value) {
                            $('#productName' + number).append('<option value="' + value.id + '">' + (value.product_name ? value.product_name : value.sub_ic) + '</option>');
                        });
                        if (selected_brand_id) {
                            $('#productName' + number).val(selected_brand_id).trigger('change');
                        }
                    }
                });
            }
        }

        function get_type_barcode_by_product(id) {
            var product_id = $('#' + id).val();
            var number = id.replace("productName", "");
            if (product_id) {
                $.ajax({
                    url: "{{ url('pdc/get_type_barcode_by_product') }}",
                    type: "GET",
                    data: { product_id: product_id },
                    success: function(data) {
                        $('#product_type' + number).val(data.type_name);
                        $('#product_barcode' + number).val(data.barcode);
                        $('#product_classification' + number).val(data.classification_name);
                        $('#product_trend' + number).val(data.trend_name);
                        $('#uom_id' + number).val(data.uom_name);
                    }
                });
            }
        }

        var Counter = {{ count($NewPurchaseVoucherData) }};
        var CounterExpense = {{ count($ExpensesData) }};

        function AddMoreExpense() {
            CounterExpense++;
            $('#AppendExpense').append(`
                <tr id="RemoveExpenseRow${CounterExpense}">
                    <td>
                        <select class="form-control requiredField select2" name="account_id[]" id="account_id${CounterExpense}">
                            <option value="">Select Account</option>
                            @foreach(CommonHelper::get_accounts_by_parent_code('1-6') as $Fil)
                            <option value="{{$Fil->id}}">{{$Fil->code.'--'.$Fil->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="expense_amount[]" id="expense_amount${CounterExpense}" class="form-control requiredField">
                    </td>
                    <td class="text-center">
                        <button type="button" id="BtnRemoveExpense${CounterExpense}" class="btn btn-sm btn-danger" onclick="RemoveExpense(${CounterExpense})"> - </button>
                    </td>
                </tr>
            `);
            $('.select2').select2();
        }

        function RemoveExpense(Row) {
            $('#RemoveExpenseRow' + Row).remove();
        }

        function AddMoreDetails() {
            Counter++;
            $('#AppnedHtml').append(`
                <tr id="RemoveRows${Counter}" class="AutoNo">
                    <td>
                        <select style="width: 15rem;" onChange="get_product_by_brand(this,${Counter})" name="brand_id[]" class="form-control select2" id="brand_id${Counter}">
                            <option value="">Select</option>
                            @foreach(CommonHelper::get_all_brand() as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select onChange="get_type_barcode_by_product('productName${Counter}')" name="item_id[]" id="productName${Counter}" class="form-control requiredField select2 itemsclass" style="width:25rem !important;">
                            <option value="">Select Products</option>
                        </select>
                    </td>
                    <td><input readonly type="text" class="form-control" name="product_type[]" id="product_type${Counter}"></td>
                    <td><input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode${Counter}"></td>
                    <td><input readonly type="text" class="form-control" name="classification_name[]" id="product_classification${Counter}"></td>
                    <td><input readonly type="text" class="form-control" name="product_trend[]" id="product_trend${Counter}"></td>
                    <td><input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}"></td>
                    <td><input type="text" onkeyup="claculation('${Counter}')" onblur="claculation('${Counter}')" class="form-control requiredField ActualQty" name="actual_qty[]" id="actual_qty${Counter}" placeholder="ACTUAL QTY"></td>
                    <td><input type="text" onkeyup="claculation('${Counter}')" onblur="claculation('${Counter}')" class="form-control requiredField ActualRate" name="rate[]" id="rate${Counter}" placeholder="RATE"></td>
                    <td><input readonly type="text" class="form-control" name="amount[]" id="amount${Counter}" placeholder="AMOUNT"></td>
                    <td><input readonly type="text" class="form-control actual_amount" name="actual_amount[]" id="actual_amount${Counter}" placeholder="AMOUNT"></td>
                    <td><input type="text" onkeyup="claculation('${Counter}')" class="form-control" name="tax_per[]" id="tax_per${Counter}" value="0"></td>
                    <td><input readonly type="text" class="form-control" name="tax_amount[]" id="tax_amount${Counter}" value="0"></td>
                    <td><input type="text" onkeyup="discount_percent(this.id)" class="form-control" name="discount_percent[]" id="discount_percent${Counter}" value="0"></td>
                    <td><input type="text" onkeyup="discount_amount(this.id)" class="form-control" name="discount_amount[]" id="discount_amount${Counter}" value="0"></td>
                    <td><input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" value="0.00"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})"> - </button>
                    </td>
                </tr>
            `);
            $('.select2').select2();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }

        function RemoveSection(Row) {
            $('#RemoveRows' + Row).remove();
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            net_amount();
            sales_tax('sales_taxx');
        }

        function get_po(id) {
            var number = $('#' + id).val();
            var po = $('#po_no').val();
            if (number == 1) {
                var res = po.slice(2, 9);
                var pl_no = 'PL' + res;
                $('#po_no').val(pl_no);
            } else if (number == 2) {
                var res = po.slice(2, 9);
                var pl_no = 'PS' + res;
                $('#po_no').val(pl_no);
            } else if (number == 3) {
                var res = po.slice(2, 9);
                var pl_no = 'PI' + res;
                $('#po_no').val(pl_no);
            }
        }
    </script>
    <script>
        var x = 0;


        $('.sam_jass').bind("enterKey", function(e) {


            $('#items').modal('show');


        });
        $('.sam_jass').keyup(function(e) {
            if (e.keyCode == 13) {
                selected_id = this.id;
                $(this).trigger("enterKey");


            }

        });


        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        function tax_by_amount(id) {


            var tax_percentage = $('#sales_taxx').val();



            if (tax_percentage == 0) {

                $('#' + id).val(0);
            } else {
                var tax_amount = parseFloat($('#' + id).val());

                // highlight end

                if (isNaN(tax_amount) == true) {
                    tax_amount = 0;
                }
                var count = 1;
                var amount = 0;
                $('.net_amount_dis').each(function() {


                    amount += +$('#after_dis_amountt_' + count).val();
                    count++;
                });
                var total = parseFloat(tax_amount + amount).toFixed(2);
                $('#d_t_amount_1').val(total);


            }
            //            toWords(1);



        }

        function net_amount() {
            var amount = 0;
            $('.net_amount_dis').each(function(i, obj) {
                amount += +$('#' + obj.id).val();
            });
            amount = parseFloat(amount);
            $('#net').val(amount.toFixed(2));

            var expense_amount = 0;
            $('input[name="expense_amount[]"]').each(function() {
                expense_amount += +$(this).val();
            });

            var sales_tax = parseFloat($('#sales_amount_td').val()) || 0;
            var net = (amount + sales_tax + expense_amount).toFixed(2);
            $('#net_after_tax').val(net);
            $('#d_t_amount_1').val(net);
            // toWords(1);
        }



        function view_history(id) {

            var v = $('#sub_' + id).val();


            if ($('#view_history' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem_directPo?id=' + v);
                } else {
                    alert('Select Item');
                }

            }





        }





        $(document).ready(function() {
            net_amount();
            amount_calculation(1);
            for (i = 1; i <= counter; i++) {
                $('#amount_' + i).number(true, 2);
                //   $('#rate_'+i).number(true,2);
                $('#purchase_approve_qty_' + i).number(true, 2);


                $('#after_dis_amountt' + i).number(true, 2);
                $('#rate_' + i).number(true, 2);
            }

            $('#d_t_amount_1').number(true, 2);
            $('#sales_amount_td').number(true, 2);

            $(".btn-success").click(function(e) {
                //alert();
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        //alert(response);
                        vala = 0;
                        var flag = false;
                        $('.ActualQty').each(function() {
                            vala = parseFloat($(this).val());
                            if (vala == 0) {
                                alert('Please Enter Correct Actual Qty....!');
                                $(this).css('border-color', 'red');
                                flag = true;
                                return false;
                            } else {
                                $(this).css('border-color', '#ccc');
                            }
                        });

                        $('.ActualRate').each(function() {
                            vala = parseFloat($(this).val());
                            if (vala == 0) {
                                alert('Please Enter Correct Rate Qty....!');
                                $(this).css('border-color', 'red');
                                flag = true;
                                return false;
                            } else {
                                $(this).css('border-color', '#ccc');
                            }
                        });
                        if (flag == true) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }

            });


            $(document).keypress("m", function(e) {
                if (e.ctrlKey)
                    AddMoreDetails();
            });

        });

        function removeSeletedPurchaseRequestRows(id, counter) {
            var totalCounter = $('#totalCounter').val();
            if (totalCounter == 1) {
                alert('Last Row Not Deleted');
            } else {
                var lessCounter = totalCounter - 1;
                var totalCounter = $('#totalCounter').val(lessCounter);
                var elem = document.getElementById('removeSelectedPurchaseRequestRow_' + counter + '');
                elem.parentNode.removeChild(elem);
            }

        }

        $(document).ready(function() {
            //            toWords(1);
        });


        function claculation(number) {
            var qty = $('#actual_qty' + number).val();
            var rate = $('#rate' + number).val();

            var total = parseFloat(qty * rate).toFixed(2);

            $('#amount' + number).val(total);

            var amount = 0;
            count = 1;
            $('.net_amount_dis').each(function(i, obj) {

                amount += +$('#' + obj.id).val();

                count++;
            });
            amount = parseFloat(amount);


            sales_tax('sales_taxx');
            discount_percent('discount_percent' + number);
            net_amount();
            //  toWords(1);
        }
        function sales_tax(id) {
            var sales_tax = 0;
            var sales_tax_per_value = $('#sales_taxx').val();
            sales_tax_per_value = sales_tax_per_value.split("@")[0];
             
            if (sales_tax_per_value != '0') {
                var net = $('#net').val();
               
                var sales_tax = (net / 100) * sales_tax_per_value;
            
            }
            console.log(sales_tax)
            $('#sales_amount_td').val(sales_tax);
          
            total_amount();
         
        }
        function total_amount() {
            var amount = 0;
          
            $('.net_amount_dis').each(function() {

                amount += +$(this).val();

            });
            $('#net').val(amount);
        
            var sales_tax = parseFloat($('#sales_amount_td').val());
            var net = (amount + sales_tax).toFixed(2);
          
            $('#net_after_tax').val(net);
            console.log(net);
        

        }
        // function sales_tax(id) {

        //     var sales_tax_per_value = $('#' + id).val();

        //     if (sales_tax_per_value != 0) {
        //         var sales_tax_per = $('#' + id + ' :selected').text();
        //         sales_tax_per = sales_tax_per.split('(');
        //         sales_tax_per = sales_tax_per[1];
        //         sales_tax_per = sales_tax_per.replace('%)', '');

        //     } else {
        //         sales_tax_per = 0;
        //     }

        //     count = 1;
        //     var amount = 0;
        //     $('.net_amount_dis').each(function() {


        //         amount += +$(this).val();
        //         count++;
        //     });


        //     var x = parseFloat(sales_tax_per * amount);
        //     var s_tax_amount = parseFloat(x / 100).toFixed(2);

        //     $('#sales_tax_amount').val(s_tax_amount);
        //     $('#sales_amount_td').val(s_tax_amount);

        //     var amount = 0;
        //     count = 1;
        //     $('.net_amount_dis').each(function() {


        //         amount += +$('#after_dis_amountt_' + count).val();
        //         count++;
        //     });
        //     amount = parseFloat(amount);
        //     s_tax_amount = parseFloat(s_tax_amount);
        //     var total_amount = (amount + s_tax_amount).toFixed(2);
        //     $('.td_amount').text(total_amount);
        //     $('#d_t_amount_1').val(total_amount);
        //     net_amount();
        //     //   toWords(1);



        // }


        function get_address() {
            var supplier = $('#supplier_id').val();

            supplier = supplier.split('@#');
            $('#addresss').val(supplier[1]);

            $('#ntn_id').val(supplier[2]);
            $('#model_terms_of_payment').val(supplier[3]);
            calculate_due_date();
        }


        function get_rate() {
            var currency_id = $('#curren').val();
            currency_id = currency_id.split(',');
            $('#curren_rate').val(currency_id[1]);
        }
    </script>
    <script>
        function open_sales_tax(id) {
            var dept_name = $('#' + id + ' :selected').text();
            if (dept_name == 'Add New') {
                showDetailModelOneParamerter('fdc/createAccountFormAjax/sales_taxx')
            }
        }

        function discount_percent(id) {
            var number = id.replace("discount_percent", "");
            var amount = parseFloat($('#amount' + number).val()) || 0;
            var tax_amount = parseFloat($('#tax_amount' + number).val()) || 0;
            var total_with_tax = amount + tax_amount;
            var x = parseFloat($('#' + id).val()) || 0;
            if (x > 100) {
                alert('Percentage Cannot Exceed by 100');
                $('#' + id).val(0);
                x = 0;
            }
            var discount_amount = (total_with_tax * x / 100).toFixed(2);
            $('#discount_amount' + number).val(discount_amount);
            var amount_after_discount = (total_with_tax - discount_amount).toFixed(2);
            $('#after_dis_amount' + number).val(amount_after_discount);
            sales_tax('sales_taxx');
            net_amount();
        }

        function discount_amount(id) {
            var number = id.replace("discount_amount", "");
            var amount = parseFloat($('#amount' + number).val()) || 0;
            var tax_amount = parseFloat($('#tax_amount' + number).val()) || 0;
            var total_with_tax = amount + tax_amount;
            var discount_amount = parseFloat($('#' + id).val()) || 0;
            if (discount_amount > total_with_tax) {
                alert('Amount Cannot Exceed by ' + total_with_tax);
                $('#discount_amount' + number).val(0);
                discount_amount = 0;
            }
            var percent = (total_with_tax > 0) ? (discount_amount / total_with_tax * 100).toFixed(2) : 0;
            $('#discount_percent' + number).val(percent);
            var amount_after_discount = (total_with_tax - discount_amount).toFixed(2);
            $('#after_dis_amount' + number).val(amount_after_discount);
            sales_tax('sales_taxx');
            net_amount();
        }

        function get_detail(id, number) {
            var item = $('#' + id).val();
            $.ajax({
                url: '{{ url('/pdc/get_data') }}',
                data: {
                    item: item
                },
                type: 'GET',
                success: function(response) {
                    var data = response.split(',');
                    $('#uom_id' + number).val(data[0]);
                }
            })
        }
        $(".remove").each(function() {
            $(this).html($(this).html().replace(/,/g, ''));
        });

        function calculate_due_date() {
            var date = new Date($("#pv_date").val());
            var days = parseFloat($('#model_terms_of_payment').val());
            days = days;
            if (!isNaN(date.getTime())) {
                date.setDate(date.getDate() + days);
                var yyyy = date.getFullYear().toString();
                var mm = (date.getMonth() + 1).toString(); // getMonth() is zero-based
                var dd = date.getDate().toString();
                var new_d = yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]);
                $("#due_date").val(new_d);
            } else {
                alert("Invalid Date");
            }
        }
    </script>
    <script type="text/javascript">
        $('.select2').select2();

        function amount_calculation(number) {
            var amount = $('#amount' + number).val();
               // var rate = $('#rate' + number).val();

               
            // $('#amount' + number).val(total);
            var total = parseFloat(amount).toFixed(2);

            var amount = 0;
            count = 1;
            $('.net_amount_dis').each(function(i, obj) {

                amount += +$('#' + obj.id).val();

                count++;
            });
            amount = parseFloat(amount);



            discount_percent('discount_percent' + number);
            net_amount();
            sales_tax('sales_taxx');
            //  toWords(1);
        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection```
