@extends('layouts.default')

@section('content')
@include('select2')

<?php
use App\Helpers\CommonHelper;
?>
<style>
.my-lab label {
    padding-top: 0px;
}

.tabel-responsive {
    scrollbar-width: thin;
    scrollbar-color: #333 #ccc;
    overflow: auto;
    width: 100%;
}
</style>

<div class="container">
    <form method="POST" action="{{ route('updateSaleOrder',$sale_orders->id)}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <div class="col-md-10">
                    <h2 class="">Customer Information</h2>
                </div>
                <div class="col-md-2 text-right">
                    <!-- <a href="" class="btn btn-secondary">Go Back</a> -->
                </div>
                <div class="tabel-responsive">
                    <table class="table table-bordered sf-table-list">
                        <tbody>
                            <tr>
                                <td>
                                    <label for="customer">Customer</label>
                                    <select name="customer_name" id="customer" class="form-control">
                                        <option value="">Select Customer</option>
                                        @foreach(CommonHelper::get_customer() as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ $sale_orders->buyers_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <label for="order_date">Order Date</label>
                                    <input type="date" name="so_date" id="so_date" class="form-control"
                                        value="{{ $sale_orders->so_date }}">
                                </td>
                                <!-- Add more fields as per your original form -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <div class="col-md-10">
                <h2 class="">Item Details</h2>
            </div>
            <div class="col-md-2 text-right">
                <a href="#" onclick="AddMoreDetails()" class="btn btn-primary">Add More</a>
            </div>
            <div class="tabel-responsive">
                <table class="userlittab table table-bordered sf-table-list" id="more_details">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Item & Description</th>
                            <th>Qty</th>
                            <th>FOC</th>
                            <th>MRP</th>
                            <th>Rate</th>
                            <th>Gross Amount</th>
                            <th>Disc (%)</th>
                            <th>Disc Amount</th>
                            <th>Disc 2(%)</th>
                            <th>Disc 2 Amount</th>
                            <th>Tax (%)</th>
                            <th>Tax Amount</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales_order_data as $key => $item)
                        <tr class="main">
                            <td>
                                <select style="width: 150px;" onChange="get_product_by_brand(this, {{ $key }})"
                                    name="brand_id[]" class="form-control" id="brand_id{{ $key }}">
                                    <option value="">Select</option>
                                    @foreach(CommonHelper::get_all_brand() as $brand)
                                    <option value="{{ $brand->id }}"
                                    {{ $item->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select style="width: 150px;" onChange="item_change(this)" name="product_id[]"
                                    class="form-control itemsclass" id="product_id{{ $key }}">
                                    @foreach(CommonHelper::get_all_subitem() as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $item->item_id == $product->id ? 'selected' : '' }}>{{ $product->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input style="width: 150px;" onkeyup="calculation_amount(); totalQty();" name="qty[]"
                                    class="form-control qty" value="{{ $item->qty }}" type="text" id="qty"></td>
                            <td><input style="width: 150px;" onkeyup="calculation_amount()" name="foc[]"
                                    class="form-control" value="{{ $item->foc }}" type="text" id="foc"></td>
                            <td><input style="width: 150px;" name="mrp_price[]" readonly class="form-control"
                                    value="{{ $item->mrp_price }}" type="text" id="mrp_price"></td>
                            <td><input style="width: 150px;" name="rate[]" onkeyup="calculation_amount()"
                                    class="form-control" value="{{ $item->rate }}" type="text" id="sale_price"></td>
                            <td><input style="width: 150px;" name="gross_amount[]" class="form-control gross_amount"
                                    value="{{ $item->sub_total }}" type="text" id="gross_amount"></td>
                            <td><input style="width: 150px;" onkeyup="calculation_amount(); get_discount();"
                                    name="discount1[]" class="form-control" value="{{ $item->discount_percent_1 }}" type="text"
                                    id="discount1"></td>
                            <td><input style="width: 150px;" readonly name="discount_amount1[]" class="form-control"
                                    value="{{ $item->discount_amount_1 }}" type="text" id="discount_amount1"></td>
                            <td><input style="width: 150px;" onkeyup="calculation_amount(); get_discount();"
                                    name="discount2[]" class="form-control" value="{{ $item->discount_percent_2 }}" type="text"
                                    id="discount2"></td>
                            <td><input style="width: 150px;" readonly name="discount_amount2[]" class="form-control"
                                    value="{{ $item->discount_amount_2 }}" type="text" id="discount_amount2"></td>
                            <td><input style="width: 150px;" onkeyup="calculation_amount();" name="tax[]"
                                    class="form-control" value="{{ $item->tax }}" type="number" id="sale_tax_rate"></td>
                            <td><input style="width: 150px;" readonly name="total_tax[]" class="form-control total_tax"
                                    value="{{ $item->tax_amount }}" type="text" id="total_tax"></td>
                            <td><input style="width: 150px;" readonly name="total_amount[]" class="form-control total"
                                    value="{{ $item->amount }}" type="text" id="total"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <div class="col-md-10">
                <h2 class="">Sub Total</h2>
            </div>
            <div class="col-md-3">
                <div class="padt">
                    <ul class="sale-l sale-l2">
                        <li>Total Qty</li>
                        <li class="text-left">
                            <input name="total_qty" class="form-control form-control2" id="total_qty"
                                value="{{ $sale_orders->total_qty }}" type="text">
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="padt">
                    <ul class="sale-l sale-l2">
                        <li>Gross Amount</li>
                        <li class="text-left"><input name="total_gross_amount" id="total_gross_amount"
                                class="form-control form-control2" value="{{ $sale_orders->total_amount }}"
                                type="text"></li>
                    </ul>
                    <ul class="sale-l sale-l2">
                        <li>Tax Amount</li>
                        <li class="text-left"><input name="total_sales_tax" id="total_sales_tax"
                                class="form-control form-control2" value="{{ $sale_orders->sales_tax_rate }}" type="text">
                        </li>
                    </ul>
                    <ul class="sale-l sale-l2">
                        <li>Net Amount</li>
                        <li class="text-left"><input name="total_amount_after_sale_tax" id="total_amount_after_sale_tax"
                                class="form-control form-control2" value="{{ $sale_orders->total_amount_after_sale_tax }}"
                                type="text"></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12 padtb text-right">
            <div class="col-md-9"></div>
            <div class="col-md-3 my-lab">
                <button type="submit" class="btn btn-primary mr-1">Submit</button>
                <a type="button" href="" class="btn btn-secondary">Clear Form</a>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
function AddMoreDetails() {
    mainCount = $('.main').length;
    Counter = mainCount + 1;

    $('#more_details').append(`
        <tbody id="RemoveRows${Counter}">
        <tr class="main">
            <td>
                <select style="width: 150px;" onChange="get_product_by_brand(this,1)"
                    name="brand_id[]" class="form-control"
                    id="brand_id1">
                    <option value="">Select</option>

                    @foreach(CommonHelper::get_all_brand() as
                    $item)
                    <option value="{{$item->id}}">
                        {{$item->name}}
                    </option>
                    @endforeach
                </select>
            </td>
            <td>
                <select  style="width: 150px;"  onChange="item_change(this)"
                    name="product_id[]"
                    class="form-control itemsclass"
                    id="product_id1">

                </select>
            </td>
            <td>
                <input style="width: 150px;" onkeyup="calculation_amount(); totalQty();"
                    name="qty[]" class="form-control qty" value=""
                    type="text" id="qty">
            </td>
            <td>
                <input style="width: 150px;" onkeyup="calculation_amount()" name="foc[]"
                    class="form-control" value="" type="text"
                    id="foc">
            </td>
            <td>
                <input style="width: 150px;" name="mrp_price[]" readonly
                    class="form-control " value="" type="text"
                    id="mrp_price">
            </td>
            <td>
                <input style="width: 150px;" name="rate[]" onkeyup="calculation_amount()"
                    class="form-control " value="" type="text"
                    id="sale_price">
            </td>
            <td>
                <input style="width: 150px;" name="gross_amount[]"
                    class="form-control gross_amount" value=""
                    type="text" id="gross_amount">
            </td>
            <td>
                <input style="width: 150px;"
                    onkeyup="calculation_amount(); get_discount();"
                    name="discount1[]" class="form-control"
                    value="0" type="text" id="discount1">
            </td>
            <td>
                <input style="width: 150px;" readonly name="discount_amount1[]"
                    class="form-control" value="" type="text"
                    id="discount_amount1">
            </td>
            <td>
                <input style="width: 150px;"
                    onkeyup="calculation_amount(); get_discount();"
                    name="discount2[]" class="form-control"
                    value="0" type="text" id="discount2">
            </td>
            <td>
                <input style="width: 150px;" readonly name="discount_amount2[]"
                    class="form-control" value="" type="text"
                    id="discount_amount2">
            </td>
            <td>
                <input style="width: 150px;" onkeyup="calculation_amount()" name="tax[]"
                    class="form-control" value="0" type="number"
                    id="sale_tax_rate">
            </td>
            <td>
                <input style="width: 150px;" readonly name="total_tax[]"
                    class="form-control total_tax" value=""
                    type="text" id="total_tax">
            </td>
            <td>
                <input style="width: 150px;" readonly name="total_amount[]"
                    class="form-control total" value="" type="text"
                    id="total">
            </td>

        </tr>
        </tbody> `);
    $('#category' + Counter).select2();
    $('#item_id' + Counter).select2();
    $('#category' + Counter).select2({
        width: '100%' // Set the desired width
    });

    $('#item_id' + Counter).select2({
        width: '100%' // Set the desired width
    });

    Counter++;
    calculation_amount();

}

function get_discount() {
    $('.itemsclass').each(function() {
        var customer_id = $('#customer').val();
        var item_id = $(this).closest('.main').find('.item_id').val();
        var discountPercent = $(this).closest('.main').find('.discount_percent');
        var gross_amount = $(this).closest('.main').find('#gross_amount').val();
        var discount1 = $(this).closest('.main').find('#discount1').val();
        var discount2 = $(this).closest('.main').find('#discount2').val();

        var discount_amount1 = gross_amount / 100 * discount1;
        $(this).closest('.main').find('#discount_amount1').val(discount_amount1);

        var amountAfterDiscount1 = gross_amount - discount_amount1;
        var discount_amount2 = amountAfterDiscount1 / 100 * discount2;
        $(this).closest('.main').find('#discount_amount2').val(discount_amount2);

        calculation_amount();
    });
}

function getCustomer(element) {
    var id = element.value;
    $.ajax({
        url: '{{ url("stad/getCustomerById") }}',
        type: 'GET',
        data: {
            id: id,
        },
        success: function(response) {
            if (response.special_price_mapped == 1) {
                $('#special_price_mapped').val("yes");
            } else {
                $('#special_price_mapped').val("no");
            }
        }
    });
}

function setSelectedOptionById(id) {
    var selectElement = document.getElementById('customer');
    var optionElement = document.getElementById(id);
    if (optionElement && selectElement) {
        optionElement.selected = true;
    } else {
        console.error('Option or select element not found.');
    }
}

function item_change(datas) {
    var id = datas.value;
    var customerId = $('#customer_name').val();
    $.ajax({
        url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
        type: 'Get',
        data: {
            id: id,
            customerId: customerId
        },
        success: function(data) {
            $(datas).closest('.main').find('#mrp_price').val(data.mrp_price);
            $(datas).closest('.main').find('#sale_price').val(data.sale_price);
        }
    });
}

function get_customer_details(id) {
    var id = id;
    $.ajax({
        url: '<?php echo url('/')?>/customer/get_customer',
        type: 'Get',
        processData: false,
        contentType: false,
        data: {
            id: id
        },
        success: function(data) {
            console.log(data);
        }
    });
}

function get_quotation_data(id) {
    var id = id;
    $('#more_details').empty();
    $('#sale_taxt_group').val([]);
    $.ajax({
        url: '<?php echo url('/')?>/saleQuotation/get_quotation_data',
        type: 'Get',
        data: {
            id: id
        },
        success: function(data) {
            $('#more_details').append(data);
            var customer_id = $('#customer_id').val();
            var sale_tax_group = $('#sale_tax_group').val();
            var sale_tax_rate1 = $('#sale_tax_rate').val();
            setSelectedOptionById('op' + customer_id);
            setSelectedOptionById('tax' + sale_tax_group);
            $('#sale_tax_rate').val(sale_tax_group);
            $('#customer').trigger('change');
            calculation_amount();
        }
    });
    calculation_amount();
}

function calculation_amount() {
    console.log(":function call");
    var grad_total = 0;
    var sale_tax = 0;
    var befor_tax = 0;
    var all_tax = 0;
    $('.itemsclass').each(function() {
        sale_tax = $(this).closest('.main').find('#sale_tax_rate').val();
        console.log(sale_tax);
        var actual_rate = $(this).closest('.main').find('#sale_price').val();
        var actual_qty = $(this).closest('.main').find('#qty').val();
        var foc_qty = $(this).closest('.main').find('#foc').val();
        var discount_amount1 = $(this).closest('.main').find('#discount_amount1').val() || 0;
        var discount_amount2 = $(this).closest('.main').find('#discount_amount2').val() || 0;
        var rate = actual_rate ? actual_rate : 0;
        var qty = actual_qty ? actual_qty : 0;
        var foc = foc_qty ? foc_qty : 0;
        var finalQty = qty - foc;
        var total = parseFloat(finalQty) * parseFloat(rate);
        sale_tax = Number(sale_tax);
        var totalDiscount = parseFloat(discount_amount1) + parseFloat(discount_amount2);
        $(this).closest('.main').find('#gross_amount').val(total);
        var totalAmount = parseFloat(total - totalDiscount) || 0;
        var sale_tax_amount = parseFloat(totalAmount / 100 * sale_tax) || 0;
        totalAmount = parseFloat(totalAmount.toFixed(3));
        sale_tax_amount = parseFloat(sale_tax_amount.toFixed(3));
        grad_total += totalAmount + sale_tax_amount;
        befor_tax += totalAmount;
        all_tax += sale_tax_amount;
        $(this).closest('.main').find('#total').val(grad_total);
        $(this).closest('.main').find('#total_tax').val(all_tax);
    })
    totalGrossAmount();
    totalTaxAmount();
    totalAmount();
    $('#grand_total').val(befor_tax);
    $('#grand_total_with_tax').val(grad_total);
}

function totalQty() {
    var totalQty = 0;
    $('.qty').each(function() {
        var qty = parseFloat($(this).val()) || 0;
        totalQty += qty;
    });
    $('#total_qty').val(totalQty);
}

function totalGrossAmount() {
    var totalGrossAmount = 0;
    $('.gross_amount').each(function() {
        var grossAmount = parseFloat($(this).val()) || 0;
        totalGrossAmount += grossAmount;
    });
    $('#total_gross_amount').val(totalGrossAmount);
}

function totalTaxAmount() {
    var totalTaxAmount = 0;
    $('.total_tax').each(function() {
        var taxAmount = parseFloat($(this).val()) || 0;
        totalTaxAmount += taxAmount;
    });
    $('#total_sales_tax').val(totalTaxAmount);
}

function totalAmount() {
    var totalAmount = 0;
    $('.total').each(function() {
        var amount = parseFloat($(this).val()) || 0;
        totalAmount += amount;
    });
    $('#total_amount_after_sale_tax').val(totalAmount);
}
</script>
@endsection