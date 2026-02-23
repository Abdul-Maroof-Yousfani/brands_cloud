<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    @include('number_formate')

    <style>
        * {
            font-size: 12px !important;
            font-family: Arial;
        }

        .select2 {
            width: 100%;
        }
        tbody#AppnedHtml input {
            margin-bottom: 0;
            width: 120px;
        }
    </style>


    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Quotation Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(array('url' => url('quotation/insert_quotation').'?m='.$m.'','id'=>'cashPaymentVoucherForm','class'=>'stop'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="demandsSection[]"
                                                       class="form-control requiredField" id="demandsSection" value="1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">PR NO. <span
                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" class="form-control requiredField"
                                                               placeholder="" name="" id=""
                                                               value="{{strtoupper($request_data[0]->demand_no)}}" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Quotation NO. <span
                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" class="form-control requiredField"
                                                               placeholder="" name="pr_no" id="pr_no"
                                                               value="{{strtoupper($voucher_no)}}" />
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Quotation Date.</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="date" class="form-control requiredField"
                                                               max="<?php echo date('Y-m-d') ?>" name="demand_date_1"
                                                               id="demand_date_1" value="<?php echo date('Y-m-d') ?>" />
                                                    </div>
                                                    <input type="hidden" name="pr_id" value="{{ $id }}" />

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Ref No. <span
                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input autofocus type="text" class="form-control requiredField"
                                                               placeholder="Ref  No" name="ref_no" id="slip_no_1" value="" />
                                                    </div>

                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Supplier <span
                                                                    class="rflabelsteric"><strong>*</strong></span></label>
                                                        <select class="form-control select2 requiredField" name="supplier"
                                                                id="supplier">
                                                            <option value="">Select</option>
                                                            @foreach(CommonHelper::get_all_supplier() as $row)
                                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                </div>
                                                <input type="hidden" name="demand_type" id="demand_type">
                                                <div class="row">


                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="sf-label">Description</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <textarea name="description_1" id="description_1" rows="4" cols="50"
                                                                  style="resize:none;"
                                                                  class="form-control requiredField"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive" id="">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr class="text-center">
                                                            <th colspan="5" class="text-center">Purchase Request Detail
                                                            </th>
                                                            <th colspan="2" class="text-center">

                                                            </th>
                                                            <th class="text-center">
{{--                                                                <span class="badge badge-success" id="span">1</span>--}}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">SR NO</th>
                                                            <th class="text-center">SKU</th>
                                                            <th class="text-center">Product</th>
                                                            <th class="text-center">Barcode</th>
                                                            <th style="width: 100px" class="text-center">UOM<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center">Rate<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center">Tax (%)<span
                                                                        class="rflabelsteric"><strong></strong></span></th>
                                                            <th style="" class="text-center">Rate after tax<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center">QTY<span
                                                                        class="rflabelsteric"><strong>*</strong></span></th>
                                                            <th style="" class="text-center">Amount</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody id="AppnedHtml">
                                                        <?php $count=0;

//dd($request_data);



                                                        ?>


                                                        @foreach($request_data as $row)

                                                                <?php
                                                                $count++;
//                                                                    dd($row->qty);
                                                                if($row->is_tax_apply_on_product == 0){
                                                                    $tax_on_product_in_percentage = $row->tax_in_percent_on_product;
                                                                }else{
                                                                    $tax_on_product_in_percentage =0;
                                                                }
                                                                ?>
                                                            <tr class="text-center">
                                                                <td>{{ $count }}</td>
                                                                <td>{{ CommonHelper::get_product_sku($row->sub_item_id) }}</td>
                                                                <td>{{ CommonHelper::get_product_name($row->sub_item_id) }}
                                                                </td>
                                                                <td>{{ CommonHelper::product_barcode($row->sub_item_id) }}</td>

                                                                <td>{{ CommonHelper::get_uom($row->sub_item_id) }}</td>

                                                                <td><input onkeyup="calc_tax_per_product({{$count}})"
                                                                           {{--                                                                           calcu('{{ $count }}','{{ $row->qty }}');--}}
                                                                           {{--                                                                           onblur="calcu('{{ $count }}','{{ $row->qty }}')"--}}
                                                                           class="form-control zerovalidate requiredField"
                                                                           step="0.01" type="number" name="rate[]"
                                                                           id="rate{{ $count }}"
                                                                           {{--                                                                           value="{{ CommonHelper::get_product_mrp_price($row->sub_item_id) }}"--}}
                                                                           value=""

                                                                    />
                                                                </td>

                                                                <td>
                                                                    <input type="text"
                                                                           name="tax_in_percent_on_each_row[]"
                                                                           value="{{ $tax_on_product_in_percentage }}"
                                                                           onkeyup="calc_tax_per_product({{$count}})"
                                                                           id="tax_in_percent_on_each_row{{$count}}"
                                                                           class="form-control ">
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                           readonly
                                                                           name="tax_in_amount_on_each_row[]"
                                                                           {{--                                                                           value="{{ $tax_on_product_in_percentage }}"--}}
                                                                           id="tax_in_amount_on_each_row{{$count}}"
                                                                           class="form-control zerovalidate requiredField">
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" value="{{$row->qty}}" name="qty_each_row[]" id="qty_each_row{{$count}}">

                                                                    {{ $row->qty }}</td>
                                                                <td class="hide"><input readonly
                                                                           class="form-control  total_tax_on_each_row"
                                                                           step="0.01" type="number" name="total_tax_on_each_row[]"
                                                                           id="total_tax_on_each_row{{ $count }}" /> </td>
                                                                <td><input readonly
                                                                           class="form-control zerovalidate requiredField amount"
                                                                           step="0.01" type="number" name="amount[]"
                                                                           id="amount{{ $count }}" /> </td>
                                                                <input type="hidden" name="pr_data_id[]"
                                                                       value="{{ $row->id }}" />
                                                            </tr>
                                                        @endforeach

                                                        </tbody>

                                                        <tbody>
                                                        {{--                                                        <tr style="font-size:large;font-weight: bold">--}}
                                                        {{--                                                            <td class="text-center" colspan="5">Total</td>--}}
                                                        {{--                                                            <td id="" class="text-right" colspan="1"><input readonly--}}
                                                        {{--                                                                                                            class="form-control" type="text" id="net" /> </td>--}}
                                                        {{--                                                            <td></td>--}}
                                                        {{--                                                        </tr>--}}
                                                        </tbody>


                                                    </table>


                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
                                                             style="float: right;">
                                                            <table class="table table-bordered sf-table-list">
                                                                <thead>
                                                                <th class="text-center" colspan="3">Sales Tax Account
                                                                    Head</th>
                                                                <th class="text-center" colspan="3">Sales Tax Amount
                                                                </th>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        Total Tax
                                                                        {{--                                                                        <select onchange="sales_tax(this.id)"--}}
                                                                        {{--                                                                                class="form-control select2" id="sales_taxx"--}}
                                                                        {{--                                                                                name="sales_taxx">--}}
                                                                        {{--                                                                            <option value="0">Select Sales Tax</option>--}}

                                                                        {{--                                                                            @foreach(ReuseableCode::get_all_sales_tax()--}}
                                                                        {{--                                                                            as $row)--}}

                                                                        {{--                                                                                <option value="{{$row->rate}}">--}}
                                                                        {{--                                                                                    {{$row->rate}} %</option>--}}
                                                                        {{--                                                                            @endforeach--}}
                                                                        {{--                                                                        </select>--}}
                                                                    </td>
                                                                    <td class="text-right" colspan="3">
                                                                        <div style="text-align: right" id="sales_amount_td" ></div>
                                                                                <input type="hidden"  name="sales_tax_amount"
                                                                                       id="sales_tax_amount" />
                                                                    </td>

                                                                </tr>


                                                                </tbody>

                                                                <tbody>
                                                                <tr style="font-size:large;font-weight: bold">
                                                                    <td class="text-center" colspan="3">Total Amount
                                                                        After Tax</td>
                                                                    <td id="" style="text-align: right" class="text-right" colspan="3"><div

                                                                                id="net_after_tax"></div>
                                                                        <input type="hidden" name="net_after_tax_value_hidden" id="net_after_tax_value_hidden">

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
                            <div class="demandsSection"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                                </div>
                            </div>
                            <?php echo Form::close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script type="text/javascript">
        $('.select2').select2();
        $('#net').number(true, 2);
        $('#net_after_tax').number(true, 2);
        $('#sales_amount_td').number(true, 2);

        $(document).ready(function() {
            var count = 1;
            @foreach($request_data as $index => $row)
                count += 1;
            calcu(count, '{{ $row->qty }}');
            @endforeach
        });
    </script>

    <script>
        function calcu(count, qty) {

            var qty = parseFloat(qty);
            var rate = parseFloat($('#rate' + count).val());
            var total = (qty * rate).toFixed(2);
            $('#amount' + count).val(total);
            sales_tax();
            total_amount();
        }


        // function calc_tax_per_product(count, valu) {
        //
        //     var rate = parseFloat(valu);
        //     var tax_in_percent_on_each_row = parseFloat($('#tax_in_percent_on_each_row' + count).val());
        //     var rate = parseFloat($('#rate' + count).val());
        //     var total = (tax_in_percent_on_each_row * rate).toFixed(2);
        //     $('#tax_in_amount_on_each_row' + count).val(total);
        //
        // }
        function calc_tax_per_product(count) {

            // Parse the values as floats
            var rate = parseFloat($('#rate' + count).val());
            var tax_in_percent_on_each_row = parseFloat($('#tax_in_percent_on_each_row' + count).val());
            var qty_each_row = parseFloat($('#qty_each_row' + count).val());

            // Calculate the tax amount by multiplying the rate by the percentage (divided by 100)
            var total = ((tax_in_percent_on_each_row / 100) * rate + rate).toFixed(2);
            // Set the tax amount in the respective input field
            $('#tax_in_amount_on_each_row' + count).val(total);
            $('#amount' + count).val(total*qty_each_row);
            $('#total_tax_on_each_row' + count).val((tax_in_percent_on_each_row / 100) * rate * qty_each_row);
            total_amount();
            calc_total_tax_only()
        }
        // function calc_total_tax_only(count) {
        //
        //     // Parse the values as floats
        //     var rate = parseFloat($('#rate' + count).val());
        //     var tax_in_percent_on_each_row = parseFloat($('#tax_in_percent_on_each_row' + count).val());
        //     var qty_each_row = parseFloat($('#qty_each_row' + count).val());
        //
        //     // Calculate the tax amount by multiplying the rate by the percentage (divided by 100)
        //     var total = ((tax_in_percent_on_each_row / 100) * rate + rate).toFixed(2);
        //
        //     // Set the tax amount in the respective input field
        //     $('#tax_in_amount_on_each_row' + count).val(total);
        //     $('#amount' + count).val(total*qty_each_row);
        //     total_amount();
        // }


        function calc_total_tax_only() {
            var amount = 0;
            $('.total_tax_on_each_row').each(function() {

                amount += +$(this).val();

            });


            $('#sales_amount_td').text(amount);
            $('#sales_tax_amount').val(amount);

            // var sales_tax = parseFloat($('#sales_amount_td').val());
            // $('#net_after_tax').val(amount + sales_tax);

        }


        $("form").submit(function(e) {

            var validate = form_validate();


            if (validate == false) {

                e.preventDefault();
                return false;
            }
            if (validate == 1) {


                $('form').submit();

            }
        });


        function sales_tax(id) {
            var sales_tax = 0;
            var sales_tax_per_value = $('#sales_taxx').val();


            if (sales_tax_per_value != '0') {
                var net = $('#net').val();
                var sales_tax = (net / 100) * sales_tax_per_value;

            }

            $('#sales_amount_td').val(sales_tax);
            $('#sales_tax_amount').val(sales_tax);

            total_amount();
        }

        // function total_amount() {
        //     var amount = 0;
        //     $('.amount').each(function() {

        //         amount += +$(this).val();

        //     });
        //     $('#net').val(amount);
        //     // var sales_tax = parseFloat($('#sales_amount_td').val());
        //     $('#net_after_tax').text(amount);
        //     $('#net_after_tax_value_hidden').val(amount);

        // }

        function total_amount() {
    var amount = 0;
    $('.amount').each(function() {
        amount += parseFloat($(this).val()) || 0;
    });

    // 2 decimal formatting
    amount = parseFloat(amount).toFixed(2);

    $('#net').val(amount);
    $('#net_after_tax').text(amount);
    $('#net_after_tax_value_hidden').val(amount);
}

    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection