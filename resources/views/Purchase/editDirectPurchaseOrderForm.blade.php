<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$MenuPermission = true;
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}

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

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

    <script>
        var counter = {{ count($purchaseDetails) > 0 ? count($purchaseDetails) : 1 }};
        var Counter = counter;
    </script>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="headquid">
                            <h2 class="subHeadingLabelClass">Edit Direct Purchase Order</h2>
                        </div>

                        @if ($MenuPermission == true)
                            <?php echo Form::open(['url' => 'stad/updateDirectPurchaseOrder', 'id' => 'editPurchaseRequestDetail', 'class' => 'stop']); ?>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{ $purchaseRequest->id }}">
                            <input type="hidden" name="pageType" value="{{ request()->get('pageType') }}">
                            <input type="hidden" name="parentCode" value="{{ request()->get('parentCode') }}">

                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PO NO.</label>
                                            <input readonly type="text" class="form-control" name="po_no"
                                                id="po_no"
                                                value="{{ strtoupper($purchaseRequest->purchase_request_no) }}" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PO DATE.<strong>*</strong></label>
                                            <input type="date" class="form-control requiredField" name="po_date"
                                                id="po_date" value="{{ $purchaseRequest->purchase_request_date }}"
                                                max="{{ date('Y-m-d') }}" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Mode of delivery</label>
                                            <input type="text" class="form-control" name="term_of_del" id="term_of_del"
                                                value="{{ $purchaseRequest->term_of_del }}" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">PO Type</label>
                                            <select onchange="get_po(this.id); po_type_change(this);" name="po_type"
                                                id="po_type" class="form-control">
                                                <option value="">Select Option</option>
                                                @foreach (CommonHelper::get_international_to_types_direct() as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $purchaseRequest->po_type == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Destination</label>
                                            <input type="text" class="form-control" name="destination" id="destination"
                                                value="{{ $purchaseRequest->destination }}" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Vendor<strong>*</strong></label>
                                            <select onchange="get_address(); get_discount();" name="supplier_id"
                                                id="supplier_id" class="form-control requiredField select2">
                                                <option value="">Select Vendor</option>
                                                <!-- Will be populated via AJAX on po_type_change -->
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Currency</label>
                                            <select onchange="claculation(1);get_rate($purhaseRequest->rate)" name="curren" id="curren"
                                                class="form-control select2 requiredField">
                                                <!-- Will be populated via AJAX -->
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Currency Rate</label>
                                            <input class="form-control" type="text" name="currency_rate"
                                                id="currency_rate" value="{{ $purchaseRequest->currency_rate }}" />
                                        </div>
                                        <input type="hidden" name="curren_rate" id="curren_rate"
                                            value="{{ $purchaseRequest->currency_rate }}" />
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Mode/ Terms Of Payment</label>
                                            <input onkeyup="calculate_due_date()" type="text" class="form-control"
                                                name="model_terms_of_payment" id="model_terms_of_payment"
                                                value="{{ $purchaseRequest->terms_of_paym }}" />
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Payment Due Date</label>
                                            <input type="date" class="form-control" name="due_date" id="due_date"
                                                value="{{ $purchaseRequest->due_date }}" readonly />
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="sf-label">Supplier's Address</label>
                                            <input readonly type="text" class="form-control" name="address"
                                                id="addresss" value="123" />
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label class="sf-label">Supplier's NTN</label>
                                            <input readonly type="text" class="form-control" name="ntn"
                                                id="ntn_id" value="" />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Remarks</label>
                                            <textarea name="Remarks" class="form-control">{{ $purchaseRequest->remarks }}</textarea>
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="sf-label">Terms & Condition<strong>*</strong></label>
                                            <textarea name="main_description" id="main_description" rows="4" class="form-control requiredField"
                                                style="resize:none;font-size: 11px;">{{ $purchaseRequest->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th colspan="7" class="text-center">Purchase Order Detail</th>
                                                    <th class="text-center"><span class="badge badge-success"
                                                            id="span">{{ count($purchaseDetails) }}</span></th>
                                                </tr>
                                                <tr>
                                                    <th>Brand</th>
                                                    <th>Product</th>
                                                    <th>Product Type</th>
                                                    <th>Product Barcode</th>
                                                    <th>Product Classification</th>
                                                    <th>Product Trend</th>
                                                    <th>Uom<strong>*</strong></th>
                                                    <th>Actual Qty<strong>*</strong></th>
                                                    <th>Rate<strong>*</strong></th>
                                                    <th>Amount(PKR)</th>
                                                    <th>Amount</th>
                                                    <th class="hide">Discount %</th>
                                                    <th class="hide">Discount Amount</th>
                                                    <th>Net Amount<strong>*</strong></th>
                                                    <th>History</th>
                                                    <th>Add / Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody id="AppnedHtml">
                                                @forelse($purchaseDetails as $index => $detail)
                                                    <tr id="RemoveRows{{ $index + 1 }}" class="AutoNo main">
                                                        <td>
                                                            <select style="width: 150px;"
                                                                onChange="get_product_by_brand(this,{{ $index + 1 }}, {{ $detail->sub_item_id }})"
                                                                name="brand_id[]" class="form-control select2"
                                                                id="brand_id{{ $index + 1 }}">
                                                                <option value="">Select</option>
                                                                @foreach (CommonHelper::get_all_brand() as $brand)
                                                                    <option value="{{ $brand->id }}"
                                                                        {{ $detail->brand_id == $brand->id ? 'selected' : '' }}>
                                                                        {{ $brand->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select
                                                                onchange="get_type_barcode_by_product('productName{{ $index + 1 }}')"
                                                                name="item_id[]" id="productName{{ $index + 1 }}"
                                                                class="form-control requiredField select2 itemsclass"
                                                                style="width:200px !important;">
                                                                <option value="">Select Products</option>
                                                                <!-- Filled via AJAX -->
                                                            </select>
                                                        </td>
                                                        <td><input readonly type="text" class="form-control"
                                                                name="product_type[]"
                                                                id="product_type{{ $index + 1 }}"
                                                                value="{{ $detail->product_type ?? '' }}"></td>
                                                        <td><input readonly type="text" class="form-control"
                                                                name="product_barcode[]"
                                                                id="product_barcode{{ $index + 1 }}"
                                                                value="{{ $detail->barcode ?? '' }}"></td>
                                                        <td><input readonly type="text" class="form-control"
                                                                name="classification_name[]"
                                                                id="product_classification{{ $index + 1 }}"
                                                                value="{{ $detail->classification ?? '' }}"></td>
                                                        <td><input readonly type="text" class="form-control"
                                                                name="product_trend[]"
                                                                id="product_trend{{ $index + 1 }}"
                                                                value="{{ $detail->trend ?? '' }}"></td>
                                                        <td>
                                                            <input readonly type="text" class="form-control"
                                                                name="uom_id[]" id="uom_id{{ $index + 1 }}"
                                                                value="{{ $detail->uom ?? '' }}">
                                                            <input type="hidden" name="cat_id[]"
                                                                id="mainIcId_{{ $index + 1 }}"
                                                                value="{{ $detail->cat_id ?? '' }}">
                                                        </td>
                                                        <td><input type="text"
                                                                onkeyup="claculation({{ $index + 1 }})"
                                                                class="form-control requiredField ActualQty"
                                                                name="actual_qty[]" id="actual_qty{{ $index + 1 }}"
                                                                value="{{ $detail->purchase_request_qty }}"></td>
                                                        <td><input type="text"
                                                                onkeyup="claculation({{ $index + 1 }})"
                                                                class="form-control requiredField ActualRate"
                                                                name="rate[]" id="rate{{ $index + 1 }}"
                                                                value="{{ $detail->rate }}"></td>
                                                        <td><input readonly type="text" class="form-control"
                                                                name="amount[]" id="amount{{ $index + 1 }}"
                                                                value="{{ $detail->amount }}"></td>
                                                        <td><input readonly type="text"
                                                                class="form-control actual_amount" name="actual_amount[]"
                                                                id="actual_amount{{ $index + 1 }}"
                                                                value="{{ $detail->actual_amount ?? $detail->amount }}">
                                                        </td>
                                                        <td class="hide"><input type="text"
                                                                onkeyup="discount_percent(this.id)" class="form-control"
                                                                name="discount_percent[]"
                                                                id="discount_percent{{ $index + 1 }}"
                                                                value="{{ $detail->discount_percent }}"></td>
                                                        <td class="hide"><input type="text"
                                                                onkeyup="discount_amount(this.id)" class="form-control"
                                                                name="discount_amount[]"
                                                                id="discount_amount{{ $index + 1 }}"
                                                                value="{{ $detail->discount_amount }}"></td>
                                                        <td><input readonly type="text"
                                                                class="form-control net_amount_dis"
                                                                name="after_dis_amount[]"
                                                                id="after_dis_amount{{ $index + 1 }}"
                                                                value="{{ $detail->net_amount }}"></td>
                                                        <td><input type="checkbox"
                                                                onclick="view_history({{ $index + 1 }})"
                                                                id="view_history{{ $index + 1 }}"></td>
                                                        <td class="text-center">
                                                            @if ($index == 0)
                                                                <input type="button" class="btn btn-sm btn-primary"
                                                                    onclick="AddMoreDetails()" value="+" />
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                    onclick="RemoveSection({{ $index + 1 }})"> -
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <!-- Same first row as create -->
                                                @endforelse
                                            </tbody>
                                            <!-- Totals and Tax section same as create -->
                                            <!-- ... (copy from your create form) -->
                                        </table>
                                    </div>

                                    
                                    <div class="row" style="margin-top: 10px;">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: right;">
        <table class="table table-bordered sf-table-list">
            <thead>
                <tr>
                    <th class="text-center" colspan="3">Description</th>
                    <th class="text-center" colspan="3">Amount</th>
                </tr>
            </thead>
            <tbody>
           
                <tr>
                    <td colspan="3">
                        <select onchange="sales_tax(this.id)" class="form-control select2" id="sales_taxx" name="sales_taxx">
                            <option value="0">Select Tax</option>
                            @foreach (ReuseableCode::get_all_sales_tax() as $row)
                                <option value="{{ $row->percent . '@' . $row->acc_id }}"
                                    {{ $purchaseRequest->sales_tax_acc_id == $row->acc_id ? 'selected' : '' }}>
                                    {{ $row->percent }}%
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td colspan="3" class="text-right">
                        <input type="text" onkeyup="tax_by_amount(this.id)" class="form-control text-right"
                               name="sales_amount_td" id="sales_amount_td"
                               value="{{ $purchaseRequest->sales_tax_amount }}" />
                        <input type="hidden" name="sales_amount" id="sales_tax_amount"
                               value="{{ $purchaseRequest->sales_tax_amount }}" />
                    </td>
                </tr>
                <tr style="font-size:large; font-weight: bold; background-color: #f5f5f5;">
                    <td colspan="3" class="text-center">Total Amount After Tax</td>
                    <td colspan="3" class="text-right">
                        <input readonly class="form-control" type="text" id="net_after_tax"
                               value="{{ $purchaseRequest->sales_tax_amount + ($purchaseDetails->sum('net_amount') ?? 0) }}" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

                                    <!-- Sales Tax Table (copy from create form) -->
                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                                            <a href="{{ url()->previous() }}" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>
                        @else
                            <span class="subHeadingLabelClass text-danger text-center" style="float: right">Permission
                                Denied 😥</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All JavaScript from create form goes here (AddMoreDetails, calculations, etc.) -->
    <!-- Just add one extra call on document ready to load products for existing rows -->


    <script>
        $('[name="brand_id[]"]').trigger("change");
        var Counter = {{ count($purchaseDetails) ?: 1 }};
        // TAB key to add new row from last editable field
        $(document).on('keydown', 'input, select', function(e) {
            if (e.key === "Tab") {
                let $inputs = $('input:enabled:not([readonly]), select:enabled:not([readonly])').filter(':visible');
                let idx = $inputs.index(this);
                if (idx === $inputs.length - 1) {
                    e.preventDefault();
                    AddMoreDetails();
                    setTimeout(() => {
                        const $newField = $(`#AppnedHtml tr.main:last`)
                            .find('input:enabled:not([readonly]), select:enabled:not([readonly])')
                            .first();
                        if ($newField.length) $newField.focus();
                    }, 200);
                }
            }
        });

        function AddMoreDetails() {
            Counter++;
            var previousBrandId = $('#brand_id' + (Counter - 1)).val();

            $('#AppnedHtml').append(`
            <tr id="RemoveRows${Counter}" class="AutoNo main">
                <td>
                    <select style="width: 150px;" onChange="get_product_by_brand(this, ${Counter})" name="brand_id[]" class="form-control select2 brand-select" id="brand_id${Counter}">
                        <option value="">Select</option>
                        @foreach (CommonHelper::get_all_brand() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="item_id[]" id="productName${Counter}" onchange="get_type_barcode_by_product('productName${Counter}')"
                            class="form-control select2 product-select itemsclass" style="width:200px !important;">
                        <option value="">Select Products</option>
                    </select>
                </td>
                <td><input readonly type="text" class="form-control" name="product_type[]" id="product_type${Counter}"></td>
                <td><input readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode${Counter}"></td>
                <td><input readonly type="text" class="form-control" name="classification_name[]" id="product_classification${Counter}"></td>
                <td><input readonly type="text" class="form-control" name="product_trend[]" id="product_trend${Counter}"></td>
                <td>
                    <input readonly type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}">
                    <input readonly type="hidden" class="form-control mainIcId" name="cat_id[]" id="mainIcId_${Counter}">
                </td>
                <td><input type="text" onkeyup="claculation(${Counter})" class="form-control ActualQty" name="actual_qty[]" id="actual_qty${Counter}" placeholder="ACTUAL QTY"></td>
                <td><input type="text" onkeyup="claculation(${Counter})" class="form-control ActualRate" name="rate[]" id="rate${Counter}" placeholder="RATE"></td>
                <td><input readonly type="text" class="form-control" name="amount[]" id="amount${Counter}" placeholder="AMOUNT"></td>
                <td><input readonly type="text" class="form-control actual_amount" name="actual_amount[]" id="actual_amount${Counter}" placeholder="AMOUNT"></td>
                <td class="hide"><input type="text" onkeyup="discount_percent(this.id)" class="form-control" value="0" name="discount_percent[]" id="discount_percent${Counter}"></td>
                <td class="hide"><input type="text" onkeyup="discount_amount(this.id)" class="form-control" value="0" name="discount_amount[]" id="discount_amount${Counter}"></td>
                <td><input readonly type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}" value="0.00"></td>
                <td><input onclick="view_history(${Counter})" type="checkbox" id="view_history${Counter}"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})"> - </button>
                </td>
            </tr>
        `);

            $('#brand_id' + Counter).select2();
            if (previousBrandId) {
                $('#brand_id' + Counter).val(previousBrandId).trigger('change');
            }

            $('#productName' + Counter).select2();

            $('#span').text($('.AutoNo').length);
        }

        function RemoveSection(Row) {
            $('#RemoveRows' + Row).remove();
            $('#span').text($('.AutoNo').length);
            net_amount();
            sales_tax('sales_taxx');
        }

        function get_po(id) {
            var number = $('#' + id).val();
            var po = $('#po_no').val();
            if (number == 1) $('#po_no').val('PL' + po.slice(2));
            if (number == 2) $('#po_no').val('PS' + po.slice(2));
            if (number == 3) $('#po_no').val('PI' + po.slice(2));
        }

        function po_type_change(selectedElement) {
            var selectedValue = selectedElement.value;
            if (selectedValue) {
                $.ajax({
                    url: '{{ url('/pdc/get_currency_vendor_by_to_type_direct_simple') }}',
                    type: 'GET',
                    data: {
                        id: selectedValue
                    },
                    success: function(response) {
                        console.log(response.vendorOptions);
                        $('#curren').empty().append(response.currencyOptions);
                        $('#supplier_id').empty().append(response.vendorOptions);
                        console.log(response.vendorOptions);

                        $("#supplier_id").val("{{ $purchaseRequest->supplier_id }}").trigger("change");
                        $("#curren").val("{{ $purchaseRequest->currency_id }}").trigger("change");

                        // Re-select saved values after reload
                        // $('#curren').val("{{ $purchaseRequest->currency_id }},{{ $purchaseRequest->currency_rate }}").trigger('change');
                        // $('#supplier_id').val("{{ $purchaseRequest->supplier_id }}@#{{ $purchaseRequest->supplier_address ?? '' }}@#{{ $purchaseRequest->supplier_ntn ?? '' }}@#{{ $purchaseRequest->terms_of_paym }}").trigger('change');
                        get_address("{{ $purchaseRequest->terms_of_paym }}");
                    }
                });
            }
        }

        function get_product_by_brand(element, number, selected_brand_id = null) {
            var value = element.value;
            var $productSelect = $('#productName' + number);
            $productSelect.empty().append('<option value="">Select Products</option>');

            $.ajax({
                url: '{{ url('/getSubItemByBrand') }}',
                type: 'GET',
                data: {
                    id: value
                },
                success: function(data) {
                    $productSelect.append(data);

                    if (selected_brand_id)
                        $productSelect.val(selected_brand_id).trigger("change");

                    // If this row had a saved item, re-select it
                    // var savedItemId = "{{ $purchaseDetails->pluck('sub_item_id', 'brand_id')->toJson() }}";
                    // You may need to store item_id per row in data-attribute if needed
                    $productSelect.select2();
                }
            });
        }

        function get_type_barcode_by_product(id) {
            var productName = $('#' + id).val();
            var index_val = id.replace("productName", "");

            if (productName > 0) {
                $.ajax({
                    url: '/pdc/get_type_barcode_by_product',
                    type: 'GET',
                    data: {
                        productName: productName
                    },
                    success: function(response) {
                        $('#product_type' + index_val).val(response.product_type_id);
                        $('#product_barcode' + index_val).val(response.product_barcode);
                        $('#product_classification' + index_val).val(response.product_classification_id);
                        $('#product_trend' + index_val).val(response.product_trend_id);
                        $('#uom_id' + index_val).val(response.uom);
                        $("#rate" + index_val).val(response.purchase_price);
                        claculation(index_val);
                    }
                });
            }
        }

        function claculation(number) {
            var qty = parseFloat($('#actual_qty' + number).val()) || 0;
            var rate = parseFloat($('#rate' + number).val()) || 0;
            var currency = parseFloat($('#currency_rate').val()) || 1;

            var actual = (qty * rate).toFixed(2);
            var total = (qty * rate * currency).toFixed(2);

            $('#amount' + number).val(total);
            $('#actual_amount' + number).val(actual);

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

            var discount_amount = (amount * percent / 100).toFixed(2);
            $('#discount_amount' + number).val(discount_amount);

            var net = (amount - discount_amount).toFixed(2);
            $('#after_dis_amount' + number).val(net);

            net_amount();
            sales_tax('sales_taxx');
        }

        function discount_amount(id) {
            var number = id.replace("discount_amount", "");
            var amount = parseFloat($('#amount' + number).val()) || 0;
            var disc = parseFloat($('#' + id).val()) || 0;

            if (disc > amount) {
                alert('Discount cannot exceed amount');
                $('#' + id).val(0);
                disc = 0;
            }

            var percent = ((disc / amount) * 100).toFixed(2);
            $('#discount_percent' + number).val(percent);

            $('#after_dis_amount' + number).val((amount - disc).toFixed(2));

            net_amount();
            sales_tax('sales_taxx');
        }

        function net_amount() {
            var amount = 0;
            var actual_amount = 0;

            $('.net_amount_dis').each(function() {
                amount += parseFloat($(this).val()) || 0;
            });
            $('.actual_amount').each(function() {
                actual_amount += parseFloat($(this).val()) || 0;
            });

            $('#net').val(amount.toFixed(2));
            $('#actual_net').val(actual_amount.toFixed(2));

            var sales_tax = parseFloat($('#sales_amount_td').val()) || 0;
            $('#net_after_tax').val((amount + sales_tax).toFixed(2));
            $('#d_t_amount_1').val((amount + sales_tax).toFixed(2));
        }

        function sales_tax(id) {
            var per = $('#sales_taxx').val().split("@")[0];
            if (per != '0') {
                var net = parseFloat($('#net').val()) || 0;
                var tax = (net * per / 100).toFixed(2);
                // $('#sales_amount_td').val(tax);
            } else {
                // $('#sales_amount_td').val(0);
            }
            net_amount();
        }

        function get_address(terms_of_paym) {
            var data_items = $("#supplier_id option:selected").data("items");
            console.log(data_items);
            var supplier = $('#supplier_id').val();
            if (supplier) {
                var parts = data_items.split('@#');
                $('#addresss').val(parts[1] || '');
                $('#ntn_id').val(parts[2] || '');
                if(terms_of_paym) {
                    $('#model_terms_of_payment').val(terms_of_paym || '');
                } else {
                    $('#model_terms_of_payment').val(parts[3] || '');
                }
                calculate_due_date();
            }
        }

        function get_rate(rt) {
            var currency = $('#curren').val();
            if (currency) {
                var rate = currency.split(',')[1];
                $('#curren_rate').val(rate);
                $('#currency_rate').val(rt);
            }
        }

        function calculate_due_date() {
            var days = parseFloat($('#model_terms_of_payment').val()) || 0;
            var poDate = $('#po_date').val();

            if (poDate && days > 0) {
                var date = new Date(poDate);
                date.setDate(date.getDate() + days);
                var yyyy = date.getFullYear();
                var mm = ('0' + (date.getMonth() + 1)).slice(-2);
                var dd = ('0' + date.getDate()).slice(-2);
                $('#due_date').val(yyyy + '-' + mm + '-' + dd);
            }
        }

        function view_history(id) {
            var item = $('#productName' + id).val();
            if ($('#view_history' + id).is(":checked") && item) {
                showDetailModelOneParamerter('pdc/viewHistoryOfItem_directPo?id=' + item);
            } else if ($('#view_history' + id).is(":checked")) {
                alert('Select Item');
            }
        }

        $(document).ready(function() {
            $('.select2').select2();

            // Load products for existing rows
            $('.brand-select').each(function() {
                var rowNum = $(this).attr('id').replace('brand_id', '');
                if ($(this).val()) {
                    get_product_by_brand(this, rowNum);
                }
            });

            // Load supplier and currency based on PO type
            po_type_change($('#po_type')[0]);

            // Trigger calculations
            net_amount();
            sales_tax('sales_taxx');
            calculate_due_date();
            get_address("{{ $purchaseRequest->terms_of_paym }}");
        });

        function get_discount() {
            var m = '{{ $m }}';
            $('.mainIcId').each(function() {
                var supplier = $('#supplier_id').val().split('@#');
                var category_id = $(this).val();
                var currentElement = $(this); // Store $(this) in a variable
                var index = currentElement.attr('id').split('_');

                $.ajax({
                    url: '{{ url('stad/getSupplierDiscounts') }}',
                    type: 'GET',
                    data: {
                        category_id: category_id,
                        supplier_id: supplier[0],
                        m: m
                    }, // Use supplier[0] instead of supplier_id
                    success: function(response) {
                        if (isNaN(response)) {
                            response = 0;
                        }
                        $('#discount_percent' + index[1]).val(response);
                        $('#discount_percent' + index[1]).trigger('keyup');
                    }
                });
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                return false;
            }
        });
    </script>

    <!-- Paste all your existing <script> blocks here (AddMoreDetails, claculation, etc.) -->

@endsection
