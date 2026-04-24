<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    @include('number_formate')
    <style>
        .heading {
            font-size: large;
            font-weight: bold;
        }
    </style>

    <div class="row">
       
    </div>

    @php
        $id = request()->id;
        $hide_style = $NewRvs->pay_mode == 2 ? 'display: none;' : '';
    @endphp

    <?php echo Form::open(['url' => 'fad/updateSalesReceipt/' . $id . '?m=' . $_GET['m'] . '', 'id' => 'createSalesOrder', 'class' => 'stop']); ?>
    <div class="panel-body well_N">
        <div class="row">
             <div class="col-lg-12">
            <h2 class="heading" style="text-decoration: underline; margin-bottom: 20px;">Edit Receipt Voucher</h2>
        </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="v_date">Voucher Date</label>
                                <input type="date" value="{{ $NewRvs->rv_date }}" class="form-control" id="v_date"
                                    name="v_date">
                            </div>

                            @php
                                $selected_principal_groups = explode(',', $NewRvs->principal_group_id);
                            @endphp
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Principal Group : <span class="rflabelsteric"><strong>*</strong></span></label>
                                <select style="width:100% !important;" name="principal_group_id[]" id="principal_group"
                                    class="form-control select2" multiple>
                                    @foreach(App\Helpers\CommonHelper::get_all_principal_groups() as $principal)
                                        <option {{ in_array($principal->id, $selected_principal_groups) ? 'selected' : '' }}
                                            value="{{ $principal->id }}">{{ $principal->products_principal_group }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="territory_id">Territory</label>
                                <select name="territory_id" id="territory_id" class="form-control select2">
                                    <option value="">Select Territory</option>
                                    @foreach (CommonHelper::get_all_territories() as $territory)
                                        <option {{ $territory->id == $NewRvs->territory_id ? 'selected' : '' }}
                                            value="{{ $territory->id }}">{{ $territory->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="pay_mode">Payment Mode</label>
                                <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                                    <option value="1,1" {{ $NewRvs->pay_mode == 1 ? 'selected' : '' }}>Cheque</option>
                                    <option value="2,2" {{ $NewRvs->pay_mode == 2 ? 'selected' : '' }}>Cash</option>
                                    <option value="3,1" {{ $NewRvs->pay_mode == 3 ? 'selected' : '' }}>Online Transfer
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            @php
                                $first_bridge = $brige_table->first();
                                $invoice_detail_first = SalesHelper::get_sales_detail_for_receipt($first_bridge->si_id ?? 0);
                                $customer = CommonHelper::byers_name($invoice_detail_first->buyers_id ?? 0);
                                $cust_acc = DB::connection('mysql2')->table('accounts')->where('id', $customer->acc_id ?? 0)->select('name', 'code')->first();
                            @endphp
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <label>Customer Name & Cr Account</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ ($customer->name ?? 'N/A') . ' (' . ($cust_acc->name ?? 'N/A') . ' - ' . ($cust_acc->code ?? 'N/A') . ')' }}">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee" style="{{ $hide_style }}">
                                <label for="bank">Banks (Company Bank)</label>
                                <?php $bank = DB::Connection('mysql2')->table('bank_detail')->get(); ?>
                                <select name="bank" class="form-control select2">
                                    <option value="">Select Bank</option>
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}" {{ $NewRvs->bank == $row->id ? 'selected' : '' }}>
                                            {{ $row->bank_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee" style="{{ $hide_style }}">
                                <label for="bank_detail_customer">Banks (Customer)</label>
                                <?php $bank = DB::Connection('mysql2')->table('bank_detail_customer')->get(); ?>
                                <select name="bank_detail_customer" class="form-control select2">
                                    <option value="">Select Bank</option>
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}" {{ $NewRvs->bank_customer_id == $row->id ? 'selected' : '' }}>
                                            {{ $row->bank_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee" style="{{ $hide_style }}">
                                <label for="cheque">Cheque No:</label>
                                <input type="text" class="form-control" id="cheque" name="cheque"
                                    value="{{ $NewRvs->cheque_no }}">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee" style="{{ $hide_style }}">
                                <label for="cheque_date">Cheque Date:</label>
                                <input value="{{ $NewRvs->cheque_date }}" class="form-control" name="cheque_date"
                                    type="date">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="acc_id">Dr Account</label>
                                @php
                                    $dr_acc_selected = $NewRvs->acc_id;
                                    if (!$dr_acc_selected) {
                                        $dr_acc_selected = $NewRvsData->where('debit_credit', 1)->first()->acc_id ?? null;
                                    }
                                @endphp
                                <select name="acc_id" id="acc_id" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach (CommonHelper::get_all_account() as $row)
                                        <option value="{{ $row->id }}" {{ $dr_acc_selected == $row->id ? 'selected' : '' }}>
                                            {{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label for="comment">Remarks:</label>
                                <textarea name="desc" class="form-control" rows="2"
                                    id="comment">{{ $NewRvs->description }}</textarea>
                            </div>
                        </div>
                    </div>


                    @php
                        $first_bridge = $brige_table->first();
                        $invoice_detail_first = SalesHelper::get_sales_detail_for_receipt($first_bridge->si_id ?? 0);
                        $buyers_id_arr = [$invoice_detail_first->buyers_id ?? 0];
                        $chequed = db::connection('mysql2')->table('cheque')->where([
                            ['status', 1],
                            ['approved', 1],
                        ])
                            ->whereIn('issued', [0, 2])
                            ->get();
                    @endphp
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="bg-primary text-white p-2">Advance Payment (Use Only)</h5>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="use_advance">Use Advance:</label>
                                <select style="width: 100%" class="form-control select2" name="use_advance" id="use_advance"
                                    onchange="calculateTotalAmountAdv()">
                                    <option value="">Select Advance</option>
                                    @foreach (CommonHelper::get_customer_advance($buyers_id_arr) as $val_C)
                                        <option value="{{ $val_C->id }}" data-amount="{{ $val_C->balance }}" {{ ($NewRvs->use_advance ?? '') == $val_C->id ? 'selected' : '' }}>
                                            {{ $val_C->payment_no . ' -- ' . number_format($val_C->balance, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee" style="{{ $hide_style }}">
                                <label for="cheque">Cheque No Advance payment:</label>
                                @php
                                    $selected_cheques = explode(',', $NewRvs->cheque_list ?? '');
                                @endphp
                                <select style="width: 100%" class="form-control select2" name="cheque_list[]" id="cheque_list"
                                    onchange="calculateTotalAmount()" multiple>
                                    @foreach($chequed as $key_C => $val_C)
                                        <option value="{{ $val_C->id }}" data-amount="{{ $val_C->amount }}" {{ in_array($val_C->id, $selected_cheques) ? 'selected' : '' }}>
                                            {{ $val_C->cheque_no . '--' . $val_C->amount}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="amount_received">Amount:</label>
                                <input type="number" name="amount_received" id="amount_received" class="form-control"
                                    step="any" value="{{ $NewRvs->amount_received ?? 0 }}">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="advance_amount">Advance Amount:</label>
                                <input type="number" readonly name="advance_amount" id="advance_amount" class="form-control"
                                    value="{{ $NewRvs->advance_amount ?? 0 }}">
                            </div>
                        </div>
                        <div class="row" id="supplier_section"
                            style="{{ ($NewRvs->advance_amount ?? 0) > 0 ? '' : 'display: none;' }}">
                            <div class="col-lg-3">
                                <label>Supplier Account</label>
                                <select name="supplier_acc_id" class="form-control select2">
                                    <option value="">Select Supplier</option>
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}" {{ ($NewRvs->supplier_acc_id ?? '') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="ref_bill_no" value="" />
                    <div>&nbsp;</div>

                    <table id="" class="table table-bordered">


                        <thead>
                            <tr>
                                <th class="text-center">SI No</th>
                                <th class="text-center">SO No</th>
                                <th class="text-center">Invoice Amount</th>
                                <th class="text-center">Return Amount</th>
                                <th class="text-center">Previous Received Amount</th>
                                <th class="text-center">Received Amount</th>
                                <th class="text-center">Receiving Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

    $counter = 1;
    $TotalTaxAmount = 0;
    $TotalDiscountAmount = 0;
    $TotalNetAmount = 0;
    $gi_no = []; ?>
                            @foreach ($brige_table as $fil)
                                                    <?php
                                $TotalTaxAmount += $fil->tax_amount;
                                $TotalDiscountAmount += $fil->discount_amount;
                                // $TotalNetAmount += $fil->net_amount;

                                $invoice_detail = SalesHelper::get_sales_detail_for_receipt($fil->si_id);

                                $get_freight = SalesHelper::get_freight($fil->si_id);
                                $received_amount = $fil->received_amount; //SalesHelper::get_received_payment($fil->si_id);
                                $return_amount = SalesHelper::get_sales_return_from_sales_tax_invoice($fil->si_id);

                                if ($invoice_detail->so_type == 1):
                                    $invoice_amount = $invoice_detail->old_amount;
                                else:
                                    $invoice_amount = $invoice_detail->invoice_amount + $get_freight;
                                endif;

                                $gi_no[] = $invoice_detail->gi_no;


                                $TotalNetAmount += $invoice_amount - $received_amount - $return_amount;
                                                                                    ?>
                                                    <input type="hidden" name="si_id[]" value="{{ $fil->si_id }}" />
                                                    <input type="hidden" name="so_id[]" value="{{ $invoice_detail->so_id }}" />

                                                    <tr title="{{ 'sales_invoice_id=' . $fil->si_id }}">
                                                        <td class="text-center">{{ strtoupper($invoice_detail->gi_no) }}</td>
                                                        <td class="text-center">
                                                            @if ($invoice_detail->so_type == 1)
                                                                {{ $invoice_detail->description }}
                                                            @else
                                                                {{ strtoupper($invoice_detail->so_no) }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">{{ number_format($invoice_amount, 2) }}</td>

                                                        <td class="text-center">{{ number_format($return_amount, 2) }}</td>
                                                        <td class="text-center">{{ number_format($received_amount, 2) }}</td>


                                                        <td><input class="form-control receive_amount"
                                                                onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                                onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                                                type="text" name="receive_amount[]" id="receive_amount{{ $counter }}"
                                                                value=""></td>

                                                        <td><input class="form-control net_amount comma_seprated" type="text" readonly
                                                                value="" name="net_amount[]"
                                                                id="net_amount{{ $counter }}"></td>
                                                    </tr>



                                                    <input type="hidden" id="inv_amount{{ $counter }}" value="{{ $invoice_amount }}" />
                                                    <input type="hidden" id="rec_amount{{ $counter }}" value="{{ $received_amount }}" />
                                                    <input type="hidden" id="ret_amount{{ $counter }}" value="{{ $return_amount }}" />


                                                    <?php    $counter++;
                                $gi = implode(',', $gi_no); ?>
                            @endforeach
                            <input type="hidden" name="count" id="count" value="{{ $counter - 1 }}" />
                            <input type="hidden" name="ref_bill_no" value="{{ $gi }}" />
                            <input type="hidden" name="buyers_id" value="{{ $invoice_detail->buyers_id }}" />
                            <tr class="heading" style="background-color: darkgrey">
                                @php
                                    $total_inv = 0;
                                    $total_ret = 0;
                                    $total_prev = 0;
                                    $total_receive = 0;
                                    foreach ($brige_table as $fil) {
                                        $invoice_detail = SalesHelper::get_sales_detail_for_receipt($fil->si_id);
                                        $get_freight = SalesHelper::get_freight($fil->si_id);
                                        if ($invoice_detail->so_type == 1) {
                                            $total_inv += $invoice_detail->old_amount;
                                        } else {
                                            $total_inv += $invoice_detail->invoice_amount + $get_freight;
                                        }
                                        $total_ret += SalesHelper::get_sales_return_from_sales_tax_invoice($fil->si_id);
                                        $total_prev += $fil->received_amount;
                                        $total_receive += $fil->received_amount;
                                    }
                                @endphp
                                <td class="text-center">TOTAL</td>
                                <td class="text-center"></td>
                                <td class="text-center">{{ number_format($total_inv, 2) }}</td>
                                <td class="text-center">{{ number_format($total_ret, 2) }}</td>
                                <td class="text-center">{{ number_format($total_prev, 2) }}</td>
                                <td><input readonly type="text" id="receive_total" class="form-control comma_seprated"
                                        value="" /></td>
                                <td><input readonly type="text" id="net_total" class="form-control comma_seprated"
                                        value="" /> </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        {{ Form::close() }}
        <script>
            $(document).ready(function () {
                $('.select2').select2();
                $("#cheque").select2();
                $('.comma_seprated').number(true, 2);
                hide_unhide();
            });

            $('#amount_received').on('keyup', function () {
                var totalReceived = parseFloat($(this).val());
                if (isNaN(totalReceived)) totalReceived = 0;

                var totalRequired = 0;
                $('.receive_amount').each(function () {
                    var invoice_amount = parseFloat($(this).attr('data-invoice-amount'));
                    var previous_amount = parseFloat($(this).attr('data-received-amount'));
                    var return_amount = parseFloat($(this).attr('data-return-amount'));
                    if (isNaN(invoice_amount)) invoice_amount = 0;
                    if (isNaN(previous_amount)) previous_amount = 0;
                    if (isNaN(return_amount)) return_amount = 0;
                    totalRequired += invoice_amount - previous_amount - return_amount;
                });

                if (totalReceived > totalRequired) {
                    let advance = parseFloat(totalReceived) - parseFloat(totalRequired);
                    $('#advance_amount').val(parseFloat(advance));
                    if (advance > 0) {
                        $('#supplier_section').show();
                    } else {
                        $('#supplier_section').hide();
                    }
                    totalReceived = totalRequired;
                } else {
                    $('#advance_amount').val(0);
                    $('#supplier_section').hide();
                }

                var remainingAmount = totalReceived;
                $('.receive_amount').each(function () {
                    var counter = $(this).attr('id').replace('receive_amount', '');
                    var invoice_amount = parseFloat($(this).attr('data-invoice-amount'));
                    var previous_amount = parseFloat($(this).attr('data-received-amount'));
                    var return_amount = parseFloat($(this).attr('data-return-amount'));
                    if (isNaN(invoice_amount)) invoice_amount = 0;
                    if (isNaN(previous_amount)) previous_amount = 0;
                    if (isNaN(return_amount)) return_amount = 0;
                    var maxReceivable = invoice_amount - previous_amount - return_amount;

                    if (remainingAmount > 0) {
                        var assignAmount = Math.min(remainingAmount, maxReceivable);
                        $(this).val(assignAmount);
                        remainingAmount -= assignAmount;
                    } else {
                        $(this).val(0);
                    }
                    calc(invoice_amount, previous_amount, counter, return_amount, 1);
                });
            });

            function calc(invoice_amount, previous_amount, counter, return_amount, type) {
                var invoice_amount = parseFloat(invoice_amount);
                var previous_amount = parseFloat(previous_amount);
                var return_amount = parseFloat(return_amount);

                if (isNaN(return_amount)) return_amount = 0;
                if (isNaN(previous_amount)) previous_amount = 0;

                var actual_amount = (invoice_amount - previous_amount - return_amount).toFixed(2);
                var receive_amount = parseFloat($('#receive_amount' + counter).val());

                if (isNaN(receive_amount)) receive_amount = 0;

                if (parseFloat(receive_amount.toFixed(2)) > parseFloat(actual_amount)) {
                    alert('Amount cannot be greater than ' + actual_amount);
                    $('#receive_amount' + counter).val(0);
                    receive_amount = 0;
                }

                $('#net_amount' + counter).val(receive_amount);

                var total_receive = 0;
                $('.receive_amount').each(function () {
                    var val = parseFloat($(this).val());
                    if (!isNaN(val)) total_receive += val;
                });
                $('#receive_total').val(total_receive);
                $('#net_total').val(total_receive);
            }

            function hide_unhide() {
                var pay_mode = $('#pay_mode').val();
                if (pay_mode == '2,2') {
                    $(".hidee").hide();
                    $('#use_advance').closest('.col-lg-3').show();
                    $('#cheque_list').closest('.col-lg-3').hide();
                } else {
                    $(".hidee").show();
                    $('#use_advance').closest('.col-lg-3').hide();
                    $('#cheque_list').closest('.col-lg-3').show();
                }
            }

            function calculateTotalAmountAdv() {
                let current = 0;
                let adv = Number($('#use_advance option:selected').data('amount')) || 0;
                $('#amount_received').val(current + adv).trigger('keyup');
            }

            function calculateTotalAmount() {
                let current = 0;
                let chequeTotal = 0;
                $('#cheque_list option:selected').each(function () {
                    chequeTotal += Number($(this).data('amount'));
                });
                $('#amount_received').val(current + chequeTotal).trigger('keyup');
            }

            $("form").submit(function (event) {
                var validate = validatee();
                if (!validate) return false;
            });

            function validatee() {
                var validate = true;
                $(".receive_amount").each(function () {
                    var id = this.id;
                    var amount = $('#' + id).val();
                    if (amount <= 0 || amount == '') {
                        $('#' + id).css('border', '3px solid red');
                        validate = false;
                    } else {
                        $('#' + id).css('border', '');
                        if ($('#acc_id').val() == '') {
                            alert('Please select Debit Account');
                            validate = false;
                            return false;
                        }
                    }
                });
                return validate;
            }
        </script>
@endsection