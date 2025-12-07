@extends('layouts.default')

@section('content')
    @include('select2')
    <?php
    use App\Helpers\CommonHelper;
    $so_no =CommonHelper::generateUniquePosNo('sales_return','so_no','SR');
    ?>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <style>
        .my-lab label{padding-top:0px;}
        .tabel-responsive{scrollbar-width:thin;scrollbar-color:#333 #ccc;overflow:auto;width:100%;}
        .red-text{color:red !important;}
        .red-text2{color:#000 !important;}
        .userlittab > thead > tr > td,.userlittab > tbody > tr > td,.userlittab > tfoot > tr > td{padding:10px 2px !important;vertical-align:-webkit-baseline-middle!important;}
        .table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th,.table-bordered > thead > tr > td,.table-bordered > tbody > tr > td,.table-bordered > tfoot > tr > td{padding:10px 1px !important;}
    </style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Create Sales Return</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <!-- <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>  -->
        </div>
    </div>

    <div class="well_N">
        <div class="dp_sdw2">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <form action="{{route('salesreturn.create')}}" method="post" id="create-sale-order-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class=" qout-h">
                                    <div class="bor-bo">
                                        <h1>Create Sale Return</h1>
                                    </div>
                                    <div class="row" style="padding-block:30px;">
                                        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                            <h2 class="subHeadingLabelClass">Sale Return Details</h2>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">SR No. </label>
                                                        <input name="sale_order_no" readonly
                                                               class="form-control" value="{{$so_no}}"
                                                               type="text">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">SR Date</label>
                                                        <input name="sale_order_date"
                                                               value="{{date('Y-m-d')}}" class="form-control"
                                                               type="date">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                    <label class="control-label" style="margin-bottom: 0;">Store Name</label>
                                                    <select onchange="getCustomer(this); "
                                                            name="customer_name" class="form-control"
                                                            id="customer_name" style="width: 100% !important;">
                                                        <option value="">Select</option>
                                                        @foreach(CommonHelper::get_customer_territory() as $item)
                                                            <option data-type="{{$item->CustomerType}}" value="{{$item->id}}" id="tax{{$item->id}}">
                                                                {{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">Phone No </label>
                                                        <input name="phone_no" id="phone_no" class="form-control"
                                                               type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">Address</label>
                                                        <input name="address" id="address" class="form-control"
                                                               type="text">
                                                    </div>
                                                </div>


                                                <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">Branch</label>
                                                        <input name="branch" id="branch" class="form-control"
                                                               type="text">
                                                    </div>
                                                </div>


                                                <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">Sales Person </label>
                                                        <input name="saleperson" id="saleperson" class="form-control"
                                                               type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">Special Price Mappeds
                                                        </label>
                                                        <input readonly name="special_price_mapped" value=""
                                                               class="form-control" id="special_price_mapped"
                                                               type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label" style="margin-bottom: 0;">Remark</label>
                                                        <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <h2>Other Details</h2>
                                            <div class="row">
                                                <div class="col-lg-7">
                                                    <h5>Balance Amount</h5>
                                                </div>
                                                <div class="col-lg-5">
                                                    <input name="balance_amount" class="form-control form-control2" style="background:white !important;" value="0.00" type="text" id="opening_balance" readonly tabindex="-1">
                                                </div>
                                                <div class="col-lg-7">
                                                    <h5>Amount Limit</h5>
                                                </div>
                                                <div class="col-lg-5">
                                                  <input name="credit_limit" class="form-control form-control2 " style="background:white !important;" value="0.00" type="text" id="amount_limit" readonly tabindex="-1">
                                                </div>
                                                <div class="col-lg-7">
                                                    <h5>Current Balance Due</h5>
                                                </div>
                                                <div class="col-lg-5">
                                                   <input name="current_balance_due" class="form-control form-control2" style="background:white !important;" value="0.00" type="text" id="current_balance_due" readonly tabindex="-1">
                                                </div>
                                                <div class="col-lg-7">
                                                    <h5>N.T.N No</h5>
                                                </div>
                                                <div class="col-lg-5">
                                                    <input name="n-t-n" class="form-control form-control2" style="background:white !important;" value="" type="text" id="NTN_No" readonly tabindex="-1">
                                                </div>
                                                <div class="col-lg-7">
                                                    <h5>S.T No</h5>
                                                </div>
                                                <div class="col-lg-5">
                                                    <input name="s-t-no" class="form-control form-control2" style="background:white !important;" value="" type="text" id="ST_No" readonly tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <div class="col-md-10">
                                        <h2 class="subHeadingLabelClass">Item Details</h2>
                                    </div>
                                    <div class="col-md-2 text-right">
                                       <a href="#" onclick="AddMoreDetails();getTableRowCount()" class="btn btn-primary" tabindex="-1">Add More</a>
                                    </div>
                                    <div class="tabel-responsive">
                                        <table class="userlittab table table-bordered sf-table-list" id="more_details">
                                            <thead>
                                                <tr>
                                                    
                                                    <th style="150px">Warehouse</th>
                                                    <th style="width: 150px;">Brand</th>
                                                    <th>Item & Description</th>
                                                    <th class="hideon3">S.Stock</th>
                                                    <th>Qty</th>
                                                    <th>FOC</th>
                                                   
                                                    <th>Rate</th>
                                                     <th>MRP</th>
                                                    <th class="hide">is_tax_apply</th>
                                                    <th class="hide">tax_type_id</th>
                                                    <th class="hide">tax_applied_on</th>
                                                    <th class="hide">tax_policy</th>
                                                    <th>Gross Amount</th>
                                                    <th>Disc (%)</th>
                                                    <th>Disc Amount</th>
                                                    <th class="hide">Disc 2(%)</th>
                                                    <th class="hide">Disc 2 Amount</th>
                                                    <th>Tax (%)</th>
                                                    <th>Tax Amount</th>
                                                    <th>Total Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="main">
                                                    
                                                    <td>
                                                        <select name="warehouses[]" class="form-control select2" style="width: 100%;">
                                                            <option value="">Select Warehouse</option>
                                                            @foreach($warehouses as $warehouse)
                                                                @php
                                                                    $warehouse_from = $item->warehouse_from;
                                                                @endphp
                                                                <option value="{{ $warehouse->id }}" {{ $item->warehouse_from == $warehouse->id ? "selected" :"" }}>{{ $warehouse->name }}</option>
                                                            @endforeach
                                                            <!-- Add more options dynamically if needed -->
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select style="width: 150px;" onChange="checkCustomerSelected(this);get_product_by_brand(this,1);getBrandCustomerDiscount(this,1);" name="brand_id[]" class="form-control " id="brand_id1" >
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_all_brand_territory() as $item)
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select style="width: 150px;" onChange="getCustomerAssignedWarehouse(this);item_change(this)" name="product_id[]" class="form-control itemsclass" id="product_id1"></select>
                                                    </td>
                                                    <td class="hide">
                                                        <select style="width: 70px;" name="from_warehouse[]" class="form-control from_warehouse" id="from_warehouse" tabindex="-1"></select>
                                                    </td>
                                                    <td class="hideon33 hide">
                                                        <select style="width: 70px;" name="to_warehouse[]" class="form-control to_warehouse" id="to_warehouse" tabindex="-1"></select>
                                                    </td>
                                                    <td class="hideon3">
                                                        <input style="width: 70px;" readonly name="s_stock[]" class="form-control s_stock" value="" type="text" id="s_stock" tabindex="-1">
                                                    </td>
                                                    <td>
                                                         <input style="width: 70px;"  onkeyup="calculation_amount(); checkQtyStock(this); totalQty();" name="qty[]" class="form-control qty next-total" value="" type="text" id="qty" tabindex="0">
                                                    </td>
                                                    <td>
                                                        <input style="width: 65px;" onkeyup="calculation_amount()" name="foc[]" class="form-control" value="" type="text" id="foc" tabindex="-1">
                                                    </td>
                                                      <td>
                                                        <input style="width: 80px;" name="rate[]" readonly onkeyup="calculation_amount()" class="form-control" value="" type="text" id="sale_price" tabindex="-1">
                                                    </td>
                                                    <td>
                                                        <input style="width: 80px;" readonly name="mrp_price[]" class="form-control" value="" type="text" id="mrp_price" tabindex="-1">
                                                    </td>
                                                    <td class="hide">
                                                        <input style="width: 110px;" readonly name="is_tax_apply[]" class="form-control" value="" type="text" id="is_tax_apply" tabindex="-1">
                                                    </td>
                                                    
                                                    <td class="hide">
                                                        <input style="width: 100px;" readonly name="tax_type_id[]" class="form-control" value="" type="text" id="tax_type_id" tabindex="-1">
                                                    </td>
                                                    <td class="hide">
                                                        <input style="width: 100px;" readonly name="tax_applied_on[]" class="form-control" value="" type="text" id="tax_applied_on" tabindex="-1">
                                                    </td>
                                                    <td class="hide">
                                                        <input style="width: 62px;" readonly name="tax_policy[]" class="form-control" value="" type="text" id="tax_policy" tabindex="-1">
                                                    </td>
                                                  
                                                    <td>
                                                        <input style="width: 110px;" name="gross_amount[]" class="form-control gross_amount" value="" type="text" id="gross_amount" tabindex="-1">
                                                    </td>
                                                    <td>
                                                        <input style="width: 100px;" readonly onkeyup="calculation_amount(); get_discount();" name="discount1[]" class="form-control dicountget" value="0" type="text" id="discount1" tabindex="-1">
                                                    </td>
                                                    <td>
                                                        <input style="width: 100px;" readonly name="discount_amount1[]" class="form-control discount_amount" value="" type="text" id="discount_amount1" tabindex="-1">
                                                    </td>
                                                    <td class="hide">
                                                        <input readonly style="width: 100px;" onkeyup="calculation_amount(); get_discount();" name="discount2[]" class="form-control" value="0" type="text" id="discount2" tabindex="-1">
                                                    </td>
                                                    <td class="hide">
                                                        <input readonly style="width: 100px;" name="discount_amount2[]" class="form-control" value="0" type="text" id="discount_amount2" tabindex="-1">
                                                    </td>
                                                    <td>
                                                        <input style="width: 62px;" onkeyup="calculation_amount()" name="tax[]" class="form-control sale_tax_rate " value="0" type="number" id="sale_tax_rate" tabindex="-1">
                                                    </td>
                                                    <td>
                                                        <input style="width: 90px;" readonly name="total_tax[]" class="form-control total_tax" value="" type="text" id="total_tax"  tabindex="-1">
                                                    </td>
                                                    
                                                    <td>
                                                        <input style="width: 111px;" readonly name="total_amount[]" class="form-control total" value="" type="text" id="total"  tabindex="-1">
                                                    </td>
                                                    <td>
                                                        <button type="button" onclick="AddMoreDetails(); getTableRowCount()" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <div class="col-md-10">
                                        <h2 class="subHeadingLabelClass">Sub Total</h2>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="padt">
                                            <ul class="sale-l sale-l2">
                                                <li>Total Qty</li>
                                                <li class="text-left">
                                                    <input name="total_qty"  style="background: white !important;"
                                                           class="form-control form-control2" id="total_qty"
                                                           value="" type="text"  readonly>
                                                </li>
                                                <li>Total Item</li>
                                                <li class="text-left">
                                                    <input name="total_item"  style="background: white !important;"
                                                           class="form-control form-control2" id="total_item"
                                                           value="" type="text"  readonly>
                                                </li>
                                                    <li></li><li></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="padt">
                                            <ul class="sale-l sale-l2">
                                                <li>Gross Amount</li>
                                                <li class="text-left"><input name="total_gross_amount"
                                                                             id="total_gross_amount" style="background: white !important;"
                                                                             class="form-control form-control2" value=""
                                                                             type="text" readonly></li>
                                            </ul>
                                            <ul class="sale-l sale-l2">
                                                <li>Tax Amount</li>
                                                <li class="text-left"><input name="total_sales_tax"
                                                                             id="total_sales_tax" style="background: white !important;"
                                                                             class="form-control form-control2" value=""
                                                                             type="text"  readonly></li>
                                            </ul>
                                        
                                             <ul class="sale-l sale-l2">
                                                <li>Net Amount</li>
                                                <li class="text-left"><input
                                                            name="total_amount_after_sale_tax"
                                                            id="total_amount_after_sale_tax" style="background: white !important;"
                                                            class="form-control form-control2" value=""
                                                            type="text" readonly></li>
                                            </ul>
                                            {{-- <ul class="sale-l sale-l2">
                                                <li>Sale Tax Amount</li>
                                                <li class="text-left"><input
                                                            name="sale_taxes_amount_total"
                                                            id="total_amount_after_sale_tax_apply_persentage" style="background: white !important;"
                                                            class="form-control form-control2" value=""
                                                            type="text" readonly></li>
                                            </ul>  --}}
                                                <ul class="hide sale-l sale-l2">
                                         
                                                <li> sale Tax Amount</li>
                                                <input type="text" class="form-control" name="sale_taxes_amount_rate" id="tax_amount_calculated" readonly>
                                            </ul>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-12 padtb text-right">
                                    <div class=" my-lab">
                                        <button type="submit" class="btn btn-primary mr-1"
                                                data-dismiss="modal">Submit</button>
                                        <a type="button" href="{{url('selling/listSaleOrder')}}"
                                           class="btnn btn-secondary" data-dismiss="modal">Clear
                                            Form</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
<script>
// function applySaleTax(selectElement) {
//     const selectedOption = selectElement.options[selectElement.selectedIndex];
//     const text = selectedOption.text;
//     const percentageMatch = text.match(/(\d+(?:\.\d+)?)%/);

//     if (percentageMatch) {
//         const percentage = parseFloat(percentageMatch[1]);
//         const totalAmountInput = document.getElementById("total_amount_after_sale_tax");
//         const originalAmount = parseFloat(totalAmountInput.value) || 0;
//         const discountAmount = (originalAmount * percentage) / 100;
//         const netAmount = originalAmount + discountAmount;
        
//         document.getElementById("tax_amount_calculated").value = discountAmount.toFixed(2);
//         document.getElementById("total_amount_after_sale_tax_apply_persentage").value = netAmount.toFixed(2);
//     } else {
//         document.getElementById("tax_amount_calculated").value = originalAmount ? originalAmount.toFixed(2) : "0.00";
//         document.getElementById("total_amount_after_sale_tax_apply_persentage").value = originalAmount ? originalAmount.toFixed(2) : "0.00";
//     }
// }

$(document).on('keyup change', '#qty', function() {
    calculation_amount(); // This will recalculate everything including tax
});

function applySaleTax(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const text = selectedOption.text;
    const percentageMatch = text.match(/(\d+(?:\.\d+)?)%/);

    const totalAmountInput = document.getElementById("total_amount_after_sale_tax");
    const originalAmount = parseFloat(totalAmountInput.value) || 0;

    if (percentageMatch) {
        const percentage = parseFloat(percentageMatch[1]);
        const discountAmount = (originalAmount * percentage) / 100;
        const netAmount = originalAmount + discountAmount;
        
        document.getElementById("tax_amount_calculated").value = discountAmount.toFixed(2);
        document.getElementById("total_amount_after_sale_tax_apply_persentage").value = netAmount.toFixed(2);
    } else {
        document.getElementById("tax_amount_calculated").value = "0.00";
        document.getElementById("total_amount_after_sale_tax_apply_persentage").value = originalAmount.toFixed(2);
    }
}


</script>

<script type="text/javascript">


jQuery(document).ready(function($) {
    var docBody = $(document.body);
    var shiftPressed = false;
    var clickedOutside = false;

    // Track Shift key
    docBody.on('keydown', function(e) {
        if ((e.keyCode || e.which) === 16) { shiftPressed = true; }
    });
    docBody.on('keyup', function(e) {
        if ((e.keyCode || e.which) === 16) { shiftPressed = false; }
    });

    // Detect click outside Select2
    docBody.on('mousedown', function(e){
        clickedOutside = !$(e.target).is('[class*="select2"]');
    });

    // Select2 state handlers
    docBody.on('select2:opening', function(e) {
        clickedOutside = false;
        $(e.target).attr('data-s2open', 1);
    });
    docBody.on('select2:closing', function(e) {
        $(e.target).removeAttr('data-s2open');
    });

    docBody.on('select2:close', function(e) {
        var elSelect = $(e.target);
        elSelect.removeAttr('data-s2open');

        var currentForm = elSelect.closest('form');
        var othersOpen = currentForm.find('[data-s2open]').length;

        if (othersOpen === 0 && clickedOutside === false) {
            // Filter valid inputs
            var inputs = currentForm.find(':input:enabled:not([readonly], input:hidden, button:hidden, textarea:hidden)')
                .not(function () {
                    return $(this).parent().is(':hidden');
                })
                .not('.no-tab-focus'); // EXCLUDE .no-tab-focus inputs

            var elFocus = null;
            $.each(inputs, function (index) {
                var elInput = $(this);
                if (elInput.attr('id') === elSelect.attr('id')) {
                    elFocus = shiftPressed ? inputs.eq(index - 1) : inputs.eq(index + 1);
                    return false;
                }
            });

            if (elFocus) {
                var isSelect2 = elFocus.siblings('.select2').length > 0;
                if (isSelect2) {
                    elFocus.select2('open');
                } else {
                    elFocus.focus();
                }
            }
        }
    });

    // Focus on Select2 from its container
    docBody.on('focus', '.select2', function(e) {
        var elSelect = $(this).siblings('select');
        if (!elSelect.is('[disabled]') && !elSelect.is('[data-s2open]') && $(this).has('.select2-selection--single').length > 0) {
            elSelect.attr('data-s2open', 1);
            elSelect.select2('open');
        }
    });

    // Prevent tabbing out of last input (add row)
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Tab') {
            let activeElement = document.activeElement;
            let lastRow = document.querySelector('table tr:last-child');
            let lastInput = lastRow ? lastRow.querySelector('input:last-child') : null;

            if (activeElement === lastInput) {
                event.preventDefault();
                AddMoreDetails();
                getTableRowCount();

                let newRow = document.querySelector('table tr:last-child');
                if (newRow) {
                    let firstInput = newRow.querySelector('input:not([readonly]):not(.no-tab-focus)');
                    if (firstInput) firstInput.focus();
                }
            }
        }
    });

    // Initialize row count
    getTableRowCount();
});

// Track total row count
function getTableRowCount() {
    var rowCount = $("tr.main").length;
    $('#total_item').val(rowCount);
}

    // Aliiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
// Add new row dynamically
// function AddMoreDetails() {
//     var Counter = $('.main').length + 1;
//     var previousBrandId = $('#brand_id' + (Counter - 1)).val();
//     var selectedOption = $('#customer_name').find('option:selected');
//     var stockValue = selectedOption.data('type');
//     var hideshow = stockValue == 3 ? 'table-cell' : 'none';

//     $('#more_details').append(`
//         <tbody id="RemoveRows${Counter}">
//             <tr class="main">
//                 <td> 
//                     <select style="width:150px;"onchange="checkCustomerSelected(this);get_product_by_brand(this,${Counter});getBrandCustomerDiscount(this,${Counter});" name="brand_id[]"  class="form-control no-tab-focus" id="brand_id${Counter}"  tabindex="-1" >
//                         <option value="">Select</option>
//                         @foreach(CommonHelper::get_all_brand() as $item)
//                             <option value="{{$item->id}}">{{$item->name}}</option>
//                         @endforeach
//                     </select>
//                 </td>
//                 <td>
//                     <select name="product_id[]" class="form-control itemsclass" id="product_id${Counter}" style="width:150px;" onchange="getCustomerAssignedWarehouse(this);item_change(this);"></select>
//                 </td>
//                 <td class="hide">
//                     <select name="from_warehouse[]" class="form-control from_warehouse" id="from_warehouse" style="width:70px;" tabindex="-1"></select>
//                 </td>
//                 <td>
                    
//                     <input type="text" name="c_stock[]" class="form-control c_stock " id="c_stock" style="width:70px;" readonly tabindex="-1">              
//                 </td>
//                 <td class="hideon33 hide" style="display:${hideshow}">
//                     <select name="to_warehouse[]" class="form-control to_warehouse" id="to_warehouse" style="width:70px;" tabindex="-1"></select>
//                 </td>
//                 <td class="hideon3" style="display:${hideshow}">
//                     <input type="text" name="s_stock[]" class="form-control s_stock " id="s_stock" style="width:70px;" readonly tabindex="-1">
//                 </td>
//                 <td>
 
                
//                  <input type="text" name="qty[]" class="form-control qty next-total" id="qty" style="width:70px;" onkeyup="checkQtyStock(this) calculation_amount(); totalQty();"
//                   </td>
//                 <td>
//                     <input type="text" name="foc[]" class="form-control" id="foc" style="width:65px;" onkeyup="calculation_amount();" tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="text" name="mrp_price[]" class="form-control " id="mrp_price" style="width:80px;" readonly tabindex="-1">
//                 </td>
//                 <!-- More hidden fields and discount/tax inputs omitted for brevity -->
//                 <td>
//                     <input type="text" name="rate[]" class="form-control" id="sale_price" style="width:80px;" onkeyup="calculation_amount();"tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="text" name="gross_amount[]" class="form-control gross_amount  id="gross_amount" style="width:110px;" readonly tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="text" name="discount1[]" class="form-control dicountget " id="discount${Counter}" style="width:100px;" value="0" readonly tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="text" name="discount_amount1[]" class="form-control discount_amount " id="discount_amount${Counter}" style="width:100px;" readonly tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="number" name="tax[]" class="form-control sale_tax_rate " id="sale_tax_rate" style="width:62px;" value="0" onkeyup="calculation_amount();" tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="text" name="total_tax[]" class="form-control total_tax " id="total_tax" style="width:90px;" readonly tabindex="-1">
//                 </td>
//                 <td>
//                     <input type="text" name="total_amount[]" class="form-control total no-tab-focus" id="total" style="width:111px;" readonly tabindex="-1">
//                 </td>
//                 <td>
//                     <button type="button" class="btn btn-danger removerow"><span class="glyphicon glyphicon-trash"></span></button>
//                 </td>
//             </tr>
//         </tbody>`);

//     // Reinitialize Select2
//     $('#brand_id' + Counter).select2();
//     $('#product_id' + Counter).select2();

//     if (previousBrandId) {
//         $('#brand_id' + Counter).val(previousBrandId).trigger('change');
//     }

//     calculation_amount();
// }

function AddMoreDetails() {
    var Counter = $('.main').length + 1;
    var previousBrandId = $('#brand_id' + (Counter - 1)).val();
    var selectedOption = $('#customer_name').find('option:selected');
    var stockValue = selectedOption.data('type');
    var hideshow = stockValue == 3 ? 'table-cell' : 'none';
    
    $('#more_details').append(`
        <tbody id="RemoveRows${Counter}">
            <tr class="main">
                <td>
                    <select name="warehouses[]" id="warehouse${Counter}" class="form-control select2" style="width: 100%;">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ $warehouse_from == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                        <!-- Add more options dynamically if needed -->
                    </select>
                </td>
                <td>
                    <select style="width:150px;" onchange="checkCustomerSelected(this);get_product_by_brand(this,${Counter});getBrandCustomerDiscount(this,${Counter});" name="brand_id[]" class="form-control brand-select no-tab-focus" id="brand_id${Counter}" tabindex="-1">
                        <option value="">Select</option>
                        @foreach(CommonHelper::get_all_brand() as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="product_id[]" class="form-control itemsclass" id="product_id${Counter}" style="width:150px;" onchange="getCustomerAssignedWarehouse(this);item_change(this);"></select>
                </td>
                <td class="hide">
                    <select name="from_warehouse[]" class="form-control from_warehouse" id="from_warehouse" style="width:70px;" tabindex="-1"></select>
                </td>
              
                <td class="hideon33 hide" style="display:${hideshow}">
                    <select name="to_warehouse[]" class="form-control to_warehouse" id="to_warehouse" style="width:70px;" tabindex="-1"></select>
                </td>
                <td class="hideon3" style="display:${hideshow}">
                    <input type="text" name="s_stock[]" class="form-control s_stock" id="s_stock" style="width:70px;" readonly tabindex="-1">
                </td>
                <td>
                    <input type="text" name="qty[]" class="form-control qty next-total" id="qty" style="width:70px;" onkeyup="checkQtyStock(this);calculation_amount();totalQty();">
                </td>
                <td>
                    <input type="text" name="foc[]" class="form-control" id="foc" style="width:65px;" onkeyup="calculation_amount();" tabindex="-1">
                </td>
                <td>
                    <input type="text" name="rate[]" readonly class="form-control" id="sale_price" style="width:70px;" onkeyup="calculation_amount();" tabindex="-1">
                </td>
                <td>
                    <input type="text" name="mrp_price[]" class="form-control" id="mrp_price" style="width:70px;" readonly tabindex="-1">
                </td>
                <td class="hide">
                    <input type="text" name="is_tax_apply[]" class="form-control" id="is_tax_apply" style="width:70px;" readonly tabindex="-1">
                </td>
                <td class="hide">
                    <input type="text" name="tax_type_id[]" class="form-control" id="tax_type_id" style="width:70px;" readonly tabindex="-1">
                </td>
                <td class="hide">
                    <input type="text" name="tax_applied_on[]" class="form-control" id="tax_applied_on" style="width:70px;" readonly tabindex="-1">
                </td>
                <td class="hide">
                    <input type="text" name="tax_policy[]" class="form-control" id="tax_policy" style="width:70px;" readonly tabindex="-1">
                </td>
                <td>
                    <input type="text" name="gross_amount[]" class="form-control gross_amount" id="gross_amount" style="width:110px;" readonly tabindex="-1">
                </td>
                <td>
                    <input type="text" name="discount1[]" class="form-control dicountget" id="discount1${Counter}" style="width:100px;" value="0" readonly tabindex="-1">
                </td>
                <td>
                    <input type="text" name="discount_amount1[]" class="form-control discount_amount" id="discount_amount1${Counter}" style="width:100px;" readonly tabindex="-1">
                </td>
                <td class="hide">
                    <input type="text" name="discount2[]" class="form-control" id="discount2" style="width:100px;" value="0" readonly tabindex="-1">
                </td>
                <td class="hide">
                    <input type="text" name="discount_amount2[]" class="form-control" id="discount_amount2" style="width:100px;" readonly tabindex="-1">
                </td>
                <td>
                    <input type="number" name="tax[]" class="form-control sale_tax_rate" id="sale_tax_rate" style="width:62px;" value="0" onkeyup="calculation_amount();" tabindex="-1">
                </td>
                <td>
                    <input type="text" name="total_tax[]" class="form-control total_tax" id="total_tax" style="width:90px;" readonly tabindex="-1">
                </td>
           
                <td>
                    <input type="text" name="total_amount[]" class="form-control total no-tab-focus" id="total" style="width:111px;" readonly tabindex="-1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger removerow"><span class="glyphicon glyphicon-trash"></span></button>
                </td>
            </tr>
        </tbody>`);

    // Initialize Select2 for new row
    $('#brand_id' + Counter).select2();
    $('#warehouse' + Counter).select2();
    $('#product_id' + Counter).select2();

    // Set previous brand ID and trigger change to fetch discount
    if (previousBrandId) {
        $('#brand_id' + Counter).val(previousBrandId).trigger('change');
    }

    // Update row count and calculations
    getTableRowCount();
    calculation_amount();
}
    // $('#create-sale-order-form').on('submit', function(event) {
    //     event.preventDefault(); // Prevent default form submission

    //     var form = $(this);
    //     var url = form.attr('action');
    //     var formData = new FormData(form[0]);

    //     // Show loading state
    //     Swal.fire({
    //         title: 'Processing...',
    //         text: 'Please wait while the sale order is being created.',
    //         allowOutsideClick: false,
    //         didOpen: () => {
    //             Swal.showLoading();
    //         }
    //     });

    //     $.ajax({
    //         url: url,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         headers: {
    //             'X-CSRF-TOKEN': $('input[name="_token"]').val()
    //         },
    //         success: function(response) {
    //             Swal.close();
    //             if (response.success) {
    //                 let saleOrderId = response.saleOrderId;
    //                 let route = `selling/viewSaleOrderPrint/${saleOrderId}`;
    //                 Swal.fire({
    //                     icon: 'success',
    //                     title: 'Success',
    //                     text: response.message || 'Sale order created successfully!',
    //                     confirmButtonText: 'OK',
    //                     timer: 1500, // Auto-close after 1.5 seconds
    //                     showConfirmButton: false
    //                 }).then(() => {
    //                     showDetailModelOneParamerter(route,saleOrderId,'View Sale Order ');
    //                     // window.location.href = '{{ url("selling/listSaleOrder") }}';
    //                 });
    //             } else {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Error',
    //                     text: response.message || 'Failed to create sale order.',
    //                     confirmButtonText: 'OK'
    //                 });
    //             }
    //         },
    //         error: function(xhr) {
    //             Swal.close();
    //             var errorMessage = 'An error occurred while creating the sale order.';
    //             if (xhr.responseJSON && xhr.responseJSON.message) {
    //                 errorMessage = xhr.responseJSON.message;
    //             } else if (xhr.responseJSON && xhr.responseJSON.errors) {
    //                 errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
    //             }

    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Error',
    //                 html: errorMessage,
    //                 confirmButtonText: 'OK'
    //             });
    //         }
    //     });
    // });

    $('#create-sale-order-form').on('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    var form = $(this);
    var url = form.attr('action');
    var formData = new FormData(form[0]);

    // Show loading state
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while the sale order is being created.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                let saleOrderId = response.saleOrderId;
                let route = `selling/viewSaleReturnPrint/${saleOrderId}`;

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Sale return created successfully!',
                    timer: 1500, // Auto-close after 1.5 seconds
                    showConfirmButton: false
                }).then(() => {
                    // Save data to localStorage for next page
                    localStorage.setItem("showSaleReturnId", saleOrderId);
                    localStorage.setItem("showSaleOrderRoute", route);

                    // Redirect to listSaleOrder page
                    window.location.href = '{{ route("listSaleReturn") }}';
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to create sale order.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            var errorMessage = 'An error occurred while creating the sale order.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
});

    // Aliiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
// Handle Tab on last total field to add a row
$(document).on('keydown', '.next-total', function (event) {
    if (event.key === "Tab" && $(this).is('.next-total:last')) {
        event.preventDefault();
        AddMoreDetails();

        setTimeout(() => {
            const newField = $('#more_details tr.main:last')
                .find('input:not([readonly]):enabled:not(.no-tab-focus), select:not([readonly]):enabled:not(.no-tab-focus)')
                .first();
            newField.focus();
        }, 200);
    }
});


    function get_sub_item_by_id(instance) {
        var category = instance.value;
        $(instance).closest('.main').find('.itemsclass').empty();
        $.ajax({
            url: '{{ url("/getSubItemByCategory") }}',
            type: 'Get',
            data: {
                category: category
            },
            success: function(response) {
                $(instance).closest('.main').find('.itemsclass').append(response);
            }
        });
    }

    function get_price(counter, instance) {
        // Retrieve values based on the counter
        var brand_id = $('#brand_id' + counter).val();
        var product_id = $('#product_id' + counter).val();
        var customer_id = $('#customer_name').val();
        var category = $(instance).val(); // Assuming 'instance' is an input element

        // Clear the targeted element before appending new data
        $(instance).closest('.main').find('.itemsclass').empty();

        // Perform an AJAX GET request
        $.ajax({
            url: '/getPriceForSaleOrder', // Removed Blade comment syntax
            type: 'GET',
            data: {
                product_id: product_id,
                customer_id: customer_id
            },
            success: function (response) {
                // Append response data to the target element
                $(instance).closest('.main').find('.itemsclass').append(response);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error); // Error handling for debugging
            }
        });
    }

    function RemoveSection(row) {
        var element = document.getElementById("RemoveRows" + row);

        if (element) {
            element.parentNode.removeChild(element);
        }

        Counter--;
        calculation_amount();
    }

    // function get_discount() {

    //     $('.main').each(function() {
    //         var customer_id = $('#customer').val();
    //         var item_id = $(this).find('.item_id').val();

    //         var discountPercent = $(this).find('.discount_percent');
    //         var gross_amount = $(this).find('#gross_amount').val();
    //         var discount1 = $(this).find('.dicountget').val();
    //         var discount2 = $(this).find('#discount2').val();

    //         var discount_amount1 = gross_amount / 100 * discount1;
    //         console.log(discount_amount1);
    //         $(this).find('.discount_amount').val(discount_amount1);

    //         var amountAfterDiscount1 = gross_amount - discount_amount1;

    //         var discount_amount2 = amountAfterDiscount1 / 100 * discount2;

    //         $(this).find('#discount_amount2').val(discount_amount2);


    //         calculation_amount();

    //         // var category_id =  $(this).closest('.main').find('.category').val();

    //         // var category_id = $(this).val();
    //         // var currentElement = $(this); // Store $(this) in a variable
    //         // var index = currentElement.attr('id').split('_');

    //         // $.ajax({
    //         //     url: '{{ url("stad/getCustomerDiscounts") }}',
    //         //     type: 'GET',
    //         //     data: {
    //         //         item_id: item_id,
    //         //         customer_id: customer_id
    //         //     }, // Use supplier[0] instead of supplier_id
    //         //     success: function(response) {
    //         //         if (isNaN(response)) {
    //         //             response = 0;
    //         //         }

    //         //         discountPercent.val(response);

    //         //         discountPercent.trigger('keyup');
    //         //     }
    //         // });
    //     });
    // }


    function get_discount() {
    $('.main').each(function() {
        var gross_amount = parseFloat($(this).find('#gross_amount').val()) || 0;

        var brand_discount = parseFloat($(this).find('.dicountget').val()) || 0;
        var product_discount = parseFloat($(this).find('#discount2').val()) || 0;

        var applied_discount = 0;

        // ✅ Priority check
        if (brand_discount > 0) {
            applied_discount = brand_discount;
        } else if (product_discount > 0) {
            applied_discount = product_discount;
        }

        // ✅ Apply final discount
        var discount_amount = (gross_amount * applied_discount) / 100;
        $(this).find('.discount_amount').val(discount_amount.toFixed(2));

        calculation_amount();
    });
}

    $(document).ready(function(){
        $('.hideon3').hide();
    });

    function getCustomer(element) {
        var id = element.value;
        var selectedOption = $(element).find('option:selected');
        var stockValue = selectedOption.data('type');


        if(stockValue == 3){
            $('.hideon3').show();
        }else{
            $('.hideon3').hide();
        }
        $.ajax({
            url: '{{ url("stad/getCustomerById") }}',
            type: 'GET',
            data: {
                id: id,
            },
            success: function(response) {
                $('#opening_balance').val(response.balance_amount);
                $('#amount_limit').val(response.creditLimit);
                $('#current_balance_due').val(response.balance_amount);
                $('#address').val(response.address);
                $('#saleperson').val(response.SaleRep);
                $('#phone_no').val(response.phone_1);
                // $('#salepersonmobile').val(response.salepersonmobile);
                if(response.cnic_ntn != ""){
                    $('#NTN_No').val(response.cnic_ntn);
                }else{
                    $('#NTN_No').val("-");
                }
                if(response.strn != ""){
                    $('#ST_No').val(response.strn);
                }else{
                    $('#ST_No').val("-");
                }
                if (response.special_price_mapped == 1) {
                    $('#special_price_mapped').val("yes");
                } else {
                    $('#special_price_mapped').val("no");
                }
            }
        });
    }

    function checkCustomerSelected(element) {
        // Get the selected customers
        let selectedCustomers = $('#customer_name').val();
        // Check if the selectedCustomers array is empty or null
        if (selectedCustomers === null || selectedCustomers.length === 0) {
            // $(element).val(null).trigger('change'); // Reset and clear the selected values
            $(element).prop('selectedIndex', 0);
            // Show SweetAlert error
            Swal.fire({
                icon: 'error',
                title: 'No Customers Selected',
                text: 'Please select customer before proceeding.',
            });
            return false; // Prevent form submission or further actions
        }
        return true; // Continue if customers are selected
    }

</script>

<script>
    function setSelectedOptionById(id) {
        // Get the select element
        console.log(id);
        var selectElement = document.getElementById('customer');

        // Get the option element by ID
        var optionElement = document.getElementById(id);

        // Check if the option element and select element exist
        if (optionElement && selectElement) {
            // Set the selected attribute of the option
            optionElement.selected = true;
        } else {
            console.error('Option or select element not found.');
        }
    }

    // function item_change(datas) {
    //     var id = datas.value;
    //     var customerId = $('#customer_name').val();
    //     $.ajax({
    //         url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
    //         type: 'Get',
    //         data: {
    //             id: id,
    //             customerId: customerId
    //         },
    //         success: function(data) {

    //             // console.log("dasdsad",data);
    //             $(datas).closest('.main').find('#mrp_price').val(data.mrp_price);
    //             $(datas).closest('.main').find('#sale_price').val(data.sale_price);
    //             $(datas).closest('.main').find('.dicountget').val(data.discount);
    //             $(datas).closest('.main').find('.sale_tax_rate').val(data.tax);
    //             $(datas).closest('.main').find('#is_tax_apply').val(data.is_tax_apply);
    //             $(datas).closest('.main').find('#tax_type_id').val(data.tax_type_id);
    //             $(datas).closest('.main').find('#tax_applied_on').val(data.tax_applied_on);
    //             $(datas).closest('.main').find('#tax_policy').val(data.tax_policy);
    //             calculation_amount();
    //             get_discount();
    //         }
    //     });
    // }


    function item_change(datas) {
    var id = datas.value;
    var customerId = $('#customer_name').val();
    $.ajax({
        url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
        type: 'GET',
        data: {
            id: id,
            customerId: customerId
        },
        success: function(data) {
            var row = $(datas).closest('.main');

            row.find('#mrp_price').val(data.mrp_price);
            row.find('#sale_price').val(data.sale_price);
            row.find('.sale_tax_rate').val(data.tax);
            row.find('#is_tax_apply').val(data.is_tax_apply);
            row.find('#tax_type_id').val(data.tax_type_id);
            row.find('#tax_applied_on').val(data.tax_applied_on);
            row.find('#tax_policy').val(data.tax_policy);

            // ✅ Only apply product discount if brand discount is NOT set
            let brandDiscount = parseFloat(row.find('.dicountget').val()) || 0;
            if (brandDiscount === 0) {
                row.find('.dicountget').val(data.discount); // this is product discount
            }

            calculation_amount();
            get_discount();
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
</script>

<script>





    function get_quotation_data(id) {
        var id = id;
        $('#more_details').empty();
        // $('#customer').val([]);
        $('#sale_taxt_group').val([]);
        // $('#sale_tax_rate').val('');
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
        // console.log(":function call");
        var grad_total = 0;

        // var tax = ($('#sale_tax_rate').val()) ? Number($('#sale_tax_rate').val()) : 0;
        // var sales_tax_further = ($('#sales_tax_further').val()) ? Number($('#sales_tax_further').val()) : 0;


        // var sale_tax = tax + sales_tax_further;
        var sale_tax = 0;

        var befor_tax = 0;
        var all_tax = 0;

        
//    $('.main').each(function () {
//         var sale_tax = parseFloat($(this).find('.sale_tax_rate').val()) || 0;
//         var tax = parseFloat($(this).find('.tax').val()) || 0;
//         var mrp_price = $(this).find('#mrp_price').val();
//         var is_tax_apply = parseInt($(this).find('#is_tax_apply').val()) || 0;
//         var tax_type_id = parseInt($(this).find('#tax_type_id').val()) || 0;
//         var tax_applied_on = $(this).find('#tax_applied_on').val();
//         var tax_policy = $(this).find('#tax_policy').val();

//         var actual_rate = parseFloat($(this).find('#sale_price').val()) || 0;
//         var actual_qty = parseFloat($(this).find('#qty').val()) || 0;
//         var foc_qty = parseFloat($(this).find('#foc').val()) || 0;
//         var discount_amount2 = parseFloat($(this).find('#discount_amount2').val()) || 0;
//         var discount1 = parseFloat($(this).find('.dicountget').val()) || 0;

//         var finalQty = actual_qty - foc_qty;
//         var total = finalQty * actual_rate;

//         var discount_amount1 = (total / 100) * discount1;
//         var totalDiscount = discount_amount1 + discount_amount2;
//         var totalAmount = total - totalDiscount;
//         var totalmrp_price = mrp_price;
//         var totalrate_price = actual_rate;

//         $(this).find('.discount_amount').val(discount_amount1.toFixed(2));
//         $(this).find('#gross_amount').val(total.toFixed(2));

//         var sale_tax_amount = 0;
//         var grand_total = 0;
//         var taxper =  100 + sale_tax;





//         if (is_tax_apply = 1) {
//             console.log("tax_applied_on",tax_applied_on);
//             if (tax_applied_on == "MRP" || tax_applied_on == "SALE") {


//                  console.log("MRP check");

//                 if (tax_type_id === 1) {
//                        console.log("tax_type_id check");

//                     if (tax_applied_on == "MRP") {

//                           console.log("tax_applied_on check");
//                         sale_tax_amount1 = (totalmrp_price / taxper) ;
//                         sale_tax_amounttotal =   totalmrp_price - sale_tax_amount1;
//                         console.log("tax_applied_on sale_tax_amount",sale_tax_amount1,totalmrp_price,sale_tax_amounttotal);

//                     } else if (tax_applied_on == "SALE") {
//                         sale_tax_amount = totalrate_price * (tax / 100);
//                     }

//                     sale_tax_amount = parseFloat(sale_tax_amount.toFixed(3));
//                     totalAmount = parseFloat(totalAmount.toFixed(3));
//                     grand_total = totalAmount + sale_tax_amount;

//                     $(this).find('#total').val(grand_total.toFixed(2));
//                     $(this).find('.total_tax').val(sale_tax_amount.toFixed(2));
//                 }


//                 //   if (tax_type_id === 2) {
//                 //        console.log("tax_type_id check");

//                 //     if (tax_applied_on == "MRP") {

                       
//                 //        sale_tax_amount1 = (totalmrp_price / taxper) - totalmrp_price ;
//                 //         sale_tax_amount =   totalmrp_price - sale_tax_amount1;

//                 //     } else if (tax_applied_on == "SALE") {
//                 //         sale_tax_amount = totalAmount * (tax / 100);
//                 //     }

//                 //     sale_tax_amount = parseFloat(sale_tax_amount.toFixed(3));
//                 //     totalAmount = parseFloat(totalAmount.toFixed(3));
//                 //     grand_total = totalAmount + sale_tax_amount;

//                 //     $(this).find('#total').val(grand_total.toFixed(2));
//                 //     $(this).find('.total_tax').val(sale_tax_amount.toFixed(2));
//                 // }

              






//             }
//         } else {
//             // No tax apply
//             $(this).find('#total').val(totalAmount.toFixed(2));
//             $(this).find('.total_tax').val("0.00");
//         }
//     })


$('.main').each(function () {
    var sale_tax = parseFloat($(this).find('.sale_tax_rate').val()) || 0;
    var tax = parseFloat($(this).find('.tax').val()) || 0;
    var mrp_price = parseFloat($(this).find('#mrp_price').val()) || 0;
    var is_tax_apply = parseInt($(this).find('#is_tax_apply').val()) || 0;
    var tax_type_id = parseInt($(this).find('#tax_type_id').val()) || 0;
    var tax_applied_on = $(this).find('#tax_applied_on').val();
    var tax_policy = $(this).find('#tax_policy').val();

    var actual_rate = parseFloat($(this).find('#sale_price').val()) || 0;
    var actual_qty = parseFloat($(this).find('#qty').val()) || 0;
    var foc_qty = parseFloat($(this).find('#foc').val()) || 0;
    var discount_amount2 = parseFloat($(this).find('#discount_amount2').val()) || 0;
    var discount1 = parseFloat($(this).find('.dicountget').val()) || 0;

    var finalQty = actual_qty - foc_qty;
    var total = finalQty * actual_rate;

    var discount_amount1 = (total / 100) * discount1;
    var totalDiscount = discount_amount1 + discount_amount2;
    var totalAmount = total - totalDiscount;

    $(this).find('.discount_amount').val(discount_amount1.toFixed(2));
    $(this).find('#gross_amount').val(total.toFixed(2));

    var sale_tax_amount = 0;
    var grand_total = 0;
    var taxper = 100 + sale_tax;

    if (is_tax_apply === 1) {
        if (tax_applied_on === "MRP" || tax_applied_on === "SALE") {
            if (tax_type_id === 1) {
            
                if (tax_applied_on === "MRP") {
                    sale_tax_amount = finalQty * mrp_price * (sale_tax / taxper);
                } else if (tax_applied_on === "SALE") {
                   
                      sale_tax_amount = finalQty * actual_rate * (sale_tax / taxper);
                }

                sale_tax_amount = parseFloat(sale_tax_amount.toFixed(3));
                totalAmount = parseFloat(totalAmount.toFixed(3));
                grand_total = totalAmount + sale_tax_amount;

                $(this).find('#total').val(grand_total.toFixed(2));
                $(this).find('.total_tax').val(sale_tax_amount.toFixed(2));
            }
              if (tax_type_id === 2) {
            
                if (tax_applied_on === "MRP") {
                  sale_tax_amount = (finalQty * mrp_price) * (sale_tax / 100);

            
                

                } else if (tax_applied_on === "SALE") {
                   
                      sale_tax_amount = finalQty * actual_rate * (sale_tax / 100);
                }

                sale_tax_amount = parseFloat(sale_tax_amount.toFixed(3));
                totalAmount = parseFloat(totalAmount.toFixed(3));
                grand_total = totalAmount + sale_tax_amount;

                $(this).find('#total').val(grand_total.toFixed(2));
                $(this).find('.total_tax').val(sale_tax_amount.toFixed(2));
            }
          
        }
    } else {
        $(this).find('#total').val(totalAmount.toFixed(2));
        $(this).find('.total_tax').val("0.00");
    }
});


   const taxDropdown = document.querySelector('select[name="sale_taxes_id"]');
    if (taxDropdown && taxDropdown.value) {
        applySaleTax(taxDropdown);
    }

        totalGrossAmount();
        totalTaxAmount();
        totalAmount();
        totalQty();
        
        // get_discount();
        // $('#total_tax').val(all_tax);
        $('#grand_total').val(befor_tax);
        $('#grand_total_with_tax').val(grad_total);


        // var grad_total = 0;
        // $('.items_class').each(function(){
        //    var actual_rate =  $(this).closest('.main').find('#rate').val();
        //    var actual_qty =  $(this).closest('.main').find('#qty').val();
        //    var rate =  actual_rate? actual_rate : 0;
        //    var qty =  actual_qty? actual_qty : 0;
        //    var total = parseFloat(qty) * parseFloat(rate);
        //    grad_total +=total;
        //     $(this).closest('.main').find('#total').val(total);
        // })
        // document.getElementById('grand_total').innerHTML = grad_total;
    }

    // function saletax(instance) {
    //     var value = instance.value;
    //     let excet_value = value.split(',');
    //     $('#sale_tax_rate').val(excet_value[1]);
    //     calculation_amount();
    // }

    function get_product_by_brand(element, number) {
        var value = element.value;
        $(element).closest('.main').find('.itemsclass').empty();
        $.ajax({
            url: '{{ url("/getSubItemByBrand") }}',
            type: 'Get',
            data: {
                id: value
            },
            success: function(data) {
                $(element).closest('.main').find('.itemsclass').append(data);
            }
        });
    }
    // Aliiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii

    // function getBrandCustomerDiscount(element, number) {

       
    //     var value = element.value;
    //     let customerId = $('#customer_name').val();
    //     console.log(customerId,number,value);
    //     $.ajax({
    //         url: '{{ url("/getDiscountByCustomerAndBrand") }}',
    //         type: 'Get',
    //         data: {
    //             id: value,
    //             cusId : customerId
    //         },
    //         success: function(response) {
    //             console.log(response.data);
    //             $('#discount'+number).val(response.data);

    //         }
    //     });
    // }
    function getBrandCustomerDiscount(element, number) {
        var value = element.value;
        let customerId = $('#customer_name').val();
        if (!customerId) {
            Swal.fire({
                icon: 'error',
                title: 'No Customer Selected',
                text: 'Please select a customer before selecting a brand.',
            });
            $(element).val('').trigger('change');
            return;
        }
        $.ajax({
            url: '{{ url("/getDiscountByCustomerAndBrand") }}',
            type: 'Get',
            data: {
                id: value,
                cusId: customerId
            },
            success: function(response) {
                // Use correct discount input ID: 'discount1' for first row, 'discount1${number}' for others
                const discountInputId = number === 1 ? '#discount1' : `#discount1${number}`;
                $(element).closest('.main').find(discountInputId).val(response.data || 0);
                calculation_amount();
                get_discount();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching discount:', error);
                const discountInputId = number === 1 ? '#discount1' : `#discount1${number}`;
                $(element).closest('.main').find(discountInputId).val(0);
                calculation_amount();
                get_discount();
            }
        });
    }
    // Aliiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
    function getCustomerAssignedWarehouse(element) {
        var customer = $('#customer_name').val();
        $.ajax({
            url: '{{ url("/getCustomerAssignedWarehouse") }}',
            type: 'GET',
            data: {
                cusId: customer,
                item: element.value
            },
            success: function(response) {
                console.log(response.data);
                $(element).closest('.main').find('.c_stock').val(response.company_total_quantity);
                $(element).closest('.main').find('.s_stock').val(response.store_total_quantity);
                $(element).closest('.main').find('.from_warehouse').empty();
                $(element).closest('.main').find('.to_warehouse').empty();


                // Append a default option
                $(element).closest('.main').find('.from_warehouse').append('<option value="">Select Warehouse</option>');
                $(element).closest('.main').find('.to_warehouse').append('<option value="">Select Virtual Warehouse</option>');

                // Loop through the response and append each warehouse as an option
                $.each(response.company_warehouse, function(index, warehouse) {
                    $(element).closest('.main').find('.from_warehouse').append('<option data-stock="' + warehouse.total_qty + '" value="' + warehouse.id + '">' + warehouse.name + ' ' + '('+warehouse.total_qty+')' + '</option>');
                    $(element).closest('.main').find('.from_warehouse').select2();
                });
                $.each(response.store_warehouse, function(index, storewarehouse) {
                    $(element).closest('.main').find('.to_warehouse').append('<option data-stock="' + storewarehouse.total_qty + '" value="' + storewarehouse.id + '">' + storewarehouse.name + ' ' + '('+storewarehouse.total_qty+')' + '</option>');
                    $(element).closest('.main').find('.to_warehouse').select2();
                });
            }
        });
    }

    $('body').on('change','.from_warehouse', function() {
        // Retrieve the selected option
        var selectedOption = $(this).find('option:selected');
        // Get the 'data-stock' value from the selected option
        var stockValue = selectedOption.data('stock');
        console.log(stockValue); // Log the stock value

        // Set the value of the closest '.main' element's '.c_stock' input to the stock value
        $(this).closest('.main').find('.c_stock').val(stockValue);
    });


    $('body').on('change','.to_warehouse', function() {
        // Retrieve the selected option
        var selectedOption = $(this).find('option:selected');
        // Get the 'data-stock' value from the selected option
        var stockValue = selectedOption.data('stock');
        console.log(stockValue); // Log the stock value

        // Set the value of the closest '.main' element's '.c_stock' input to the stock value
        $(this).closest('.main').find('.s_stock').val(stockValue);
    });

    function further_taxes(instance) {
        // sales_tax_further further_taxes

        var value = instance.value;
        let excet_value = value.split(',');

        $('#sales_tax_further').val(excet_value[1]);
        calculation_amount();

    }

  

    function totalQty() {
        var totalQty = 0;
        $('.qty').each(function() {
            var qty = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
            totalQty += qty;
        });
        $('#total_qty').val(totalQty);
    }

    function totalGrossAmount() {
        var totalGrossAmount = 0;
        $('.gross_amount').each(function() {
            var grossAmount = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
            totalGrossAmount += grossAmount;
        });
        $('#total_gross_amount').val(totalGrossAmount);
    }

    function totalTaxAmount() {
        var totalTaxAmount = 0;
        $('.total_tax').each(function() {
            var taxAmount = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
            totalTaxAmount += taxAmount;
        });
        $('#total_sales_tax').val(totalTaxAmount);
    }

    function totalAmount() {
        var totalAmount = 0;
        $('.total').each(function() {
            var amount = parseFloat($(this).val()) || 0; // Convert value to float, default to 0 if NaN
            totalAmount += amount;
        });
        $('#total_amount_after_sale_tax').val(totalAmount);
    }

    $(document).ready(function() {

        $('#customer').select2();
        $('#customer_name').select2();
    })

    $('body').on('click', '.removerow', function() {
        // Show the confirmation dialog
        Swal.fire({
            icon: 'warning', // Change icon to warning for confirmation
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            showCancelButton: true, // Show cancel button
            confirmButtonColor: '#3085d6', // Set the confirm button color
            cancelButtonColor: '#d33', // Set the cancel button color
            confirmButtonText: 'Yes, remove it!' // Set the confirm button text
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove the row if the user confirmed
                $(this).closest('tr').remove();
                getTableRowCount();
                 calculation_amount();
                

         const taxDropdown = document.querySelector('select[name="sale_taxes_id"]');
            if (taxDropdown && taxDropdown.value) {
                applySaleTax(taxDropdown);
            }
                // Swal.fire(
                //     'Removed!',
                //     'The row has been removed.',
                //     'success' // Show a success message
                // );
            }
        });
    });
    
    $('#brand_id1').select2();
    $('#product_id1').select2();



</script>



<script>
    function checkQtyStock(qtyInput) {
        const qty = parseFloat(qtyInput.value) || 0;
        const row = qtyInput.closest('tr');
        const cStockInput = row.querySelector('.c_stock');
        const cStock = parseFloat(cStockInput.value) || 0;

        // If qty > c_stock
        if (qty > cStock) {
            qtyInput.classList.add('red-text');

            // Reset the value to max allowed (c_stock)
            qtyInput.value = cStock;

            // Optional: show alert or error message
            // alert("Quantity cannot be greater than stock!");
        } else {
            qtyInput.classList.remove('red-text');
        }

        // If stock is 0 and qty is entered
        if (cStock === 0 && qty > 0) {
            qtyInput.classList.add('red-text');
        }
    }
</script>



<script>



$(document).ready(function() {
    if (window.location.href.indexOf("selling/createSaleOrder?pageType=add") > -1) {
        function forceCloseSidebar() {
            $(".sidenavnr").addClass("Navactive");
            $("body").addClass("full_with");
        }

        // First run
        forceCloseSidebar();

        // Run again after a delay (for late scripts)
        setTimeout(forceCloseSidebar, 400);
        setTimeout(forceCloseSidebar, 1500);
    }
});





</script>

@endsection