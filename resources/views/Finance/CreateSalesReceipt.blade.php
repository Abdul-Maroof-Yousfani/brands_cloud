<?php
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$first_invoice_id = reset($val);
$so_data_raw = DB::connection('mysql2')->table('sales_tax_invoice')
    ->join('sales_order', 'sales_tax_invoice.so_id', '=', 'sales_order.id')
    ->where('sales_tax_invoice.id', $first_invoice_id)
    ->select('sales_order.principal_group_id', 'sales_order.principal_group_ids', 'sales_order.id as so_id')
    ->first();



$selected_principal_groups = [];
$selected_brands = [];
if ($so_data_raw) {
    // principal_group_ids null ho tw kuch select nahi hoga
    if (!empty($so_data_raw->principal_group_ids)) {
        $selected_principal_groups = explode(',', $so_data_raw->principal_group_ids);
    } elseif (!empty($so_data_raw->principal_group_id)) {
        $selected_principal_groups = [$so_data_raw->principal_group_id];
    }

    $selected_brands = DB::connection('mysql2')->table('sales_order_data')
        ->where('master_id', $so_data_raw->so_id)
        ->whereNotNull('brand_id')
        ->distinct()
        ->pluck('brand_id')
        ->toArray();
}
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    @include('number_formate')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="heading" style="text-decoration: underline; margin-bottom: 20px;">Receipt Voucher</h2>
        </div>
    </div>

    @php
        $invoice_detail_first = SalesHelper::get_sales_detail_for_receipt($first_invoice_id);
        $customer = CommonHelper::byers_name($invoice_detail_first->buyers_id ?? 0);
        $cust_acc = DB::connection('mysql2')->table('accounts')->where('id', $customer->acc_id ?? 0)->select('name', 'code')->first();
        $buyers_id_arr = [$invoice_detail_first->buyers_id ?? 0];
        $WhereIn = implode(',', $val);
        $Colll = DB::Connection('mysql2')->select('select gi_no,buyers_id from sales_tax_invoice where id in(' . $WhereIn . ') group by buyers_id');
    @endphp

    <?php echo Form::open(['url' => 'fad/addSalesReceipt?m=' . $_GET['m'] . '', 'id' => 'createSalesOrder', 'class' => 'stop']); ?>
    <div class="panel-body well_N">
        <div class="well">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="v_date">Voucher Date</label>
                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="v_date" name="v_date">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label>Principal Group : <span class="rflabelsteric"><strong>*</strong></span></label>
                    <select style="width:100% !important;" name="principal_group_id[]" id="principal_group"
                        class="form-control select2" multiple>
                        @foreach(App\Helpers\CommonHelper::get_all_principal_groups() as $principal)
                            <option value="{{ $principal->id }}" {{ in_array($principal->id, $selected_principal_groups) ? 'selected' : '' }}>{{ $principal->products_principal_group }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="territory_id">Territory</label>
                    <select name="territory_id" id="territory_id" class="form-control select2">
                        <option value="">Select Territory</option>
                        @foreach (CommonHelper::get_all_territories() as $territory)
                            <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="pay_mode">Payment Mode</label>
                    <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                        <option value="1,1">Cheque</option>
                        <option value="2,2" selected>Cash</option>
                        <option value="3,1">Online Transfer</option>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Customer Name & Cr Account</label>
                    <input type="text" class="form-control" readonly
                        value="{{ ($customer->name ?? 'N/A') . ' (' . ($cust_acc->name ?? 'N/A') . ' - ' . ($cust_acc->code ?? 'N/A') . ')' }}">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="bank">Banks (Customer Bank)</label>
                    <?php $bank = DB::Connection('mysql2')->table('bank_detail')->get(); ?>
                    <select name="bank" class="form-control select2">
                        <option value="">Select Bank</option>
                        @foreach ($bank as $row)
                            <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="cheque">Cheque No:</label>
                    <input type="text" class="form-control" id="cheque1" name="cheque" value="-">
                </div>
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="cheque_date">Cheque Date:</label>
                    <input value="{{ date('Y-m-d') }}" class="form-control" name="cheque_date" type="date">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="acc_id">Dr Account (Deposit To)</label>
                    <select name="acc_id" id="acc_id" class="form-control select2">
                        <option value="">Select</option>
                        @foreach (CommonHelper::get_all_account() as $row11)
                            <option value="{{ $row11->id }}">{{ $row11->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label for="comment">Remarks:</label>
                    <textarea name="desc" class="form-control" rows="1"
                        id="comment"><?php foreach ($Colll as $cc): echo CommonHelper::byers_name($cc->buyers_id)->name; endforeach; ?></textarea>
                </div>
            </div>
        </div>

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
                            <option value="{{ $val_C->id }}" data-amount="{{ $val_C->balance }}">
                                {{ $val_C->payment_no . ' -- ' . number_format($val_C->balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                    <label for="cheque">Cheque No Advance payment:</label>
                    <select style="width: 100%" class="form-control select2" name="cheque_list[]" id="cheque"
                        onchange="calculateTotalAmount()" multiple>
                        @foreach($chequed as $key_C => $val_C)
                            <option value="{{ $val_C->id }}" data-amount="{{ $val_C->amount }}">
                                {{ $val_C->cheque_no . '--' . $val_C->amount}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="amount_received">Amount:</label>
                    <input type="number" name="amount_received" id="amount_received" class="form-control"
                        step="any" value="0">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="advance_amount">Advance Amount:</label>
                    <input type="number" readonly name="advance_amount" id="advance_amount" class="form-control"
                        value="0">
                </div>
            </div>
        </div>


        <input type="hidden" name="ref_bill_no" value="" />
        <div>&nbsp;</div>
        
        <div class="table-responsive">
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
                        @php
                            $counter = 1; 
                            $gi_no = [];
                            $total_inv = 0;
                            $total_ret = 0;
                            $total_prev = 0;
                        @endphp
                        @foreach ($val as $row)
                            @php
                                $invoice_detail = SalesHelper::get_sales_detail_for_receipt($row);
                                $get_freight = SalesHelper::get_freight($row);
                                $received_amount = round(SalesHelper::get_received_payment($row), 0);
                                $return_amount = round(SalesHelper::get_sales_return_from_sales_tax_invoice($row), 0);
                                $invoice_amount = round($invoice_detail->invoice_amount + $get_freight, 0);
                                $balance_due = $invoice_amount - $return_amount - $received_amount;

                                $total_inv += $invoice_amount;
                                $total_ret += $return_amount;
                                $total_prev += $received_amount;
                            @endphp

                            <input type="hidden" name="si_id[]" value="{{ $row }}" />
                            <input type="hidden" name="so_id[]" value="{{ $invoice_detail->so_id }}" />
                            <input type="hidden" id="inv_amount{{ $counter }}" value="{{ $invoice_amount }}" />
                            <input type="hidden" id="rec_amount{{ $counter }}" value="{{ $received_amount }}" />
                            <input type="hidden" id="ret_amount{{ $counter }}" value="{{ $return_amount }}" />

                            <tr>
                                <td class="text-center">{{ strtoupper($invoice_detail->gi_no) }}</td>
                                <td class="text-center">{{ strtoupper($invoice_detail->so_no) }}</td>
                                <td class="text-center">{{ number_format($invoice_amount, 2) }}</td>
                                <td class="text-center">{{ number_format($return_amount, 2) }}</td>
                                <td class="text-center">{{ number_format($received_amount, 2) }}</td>
                                <td>
                                    <input class="form-control receive_amount" 
                                           data-invoice-amount="{{ $invoice_amount }}"
                                           data-received-amount="{{ $received_amount }}" 
                                           data-return-amount="{{ $return_amount }}"
                                           type="text" name="receive_amount[]" 
                                           id="receive_amount{{ $counter }}" 
                                           value="{{ $balance_due }}"
                                           onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}')"
                                           onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}')">
                                </td>
                                <td>
                                    <input class="form-control net_amount comma_seprated" type="text" readonly 
                                           name="net_amount[]" id="net_amount{{ $counter }}" 
                                           value="{{ $balance_due }}">
                                </td>
                            </tr>
                            <?php 
                                $counter++; 
                                $gi_no[] = $invoice_detail->gi_no;
                            ?>
                        @endforeach
                        
                        <input type="hidden" name="count" id="count" value="{{ $counter - 1 }}" />
                        <input type="hidden" name="ref_bill_no" value="{{ implode(',', $gi_no) }}" />
                        <input type="hidden" name="buyers_id" value="{{ $invoice_detail->buyers_id ?? 0 }}" />

                        <tr class="heading" style="background-color: darkgrey">
                            <td class="text-center">TOTAL</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ number_format($total_inv, 2) }}</td>
                            <td class="text-center">{{ number_format($total_ret, 2) }}</td>
                            <td class="text-center">{{ number_format($total_prev, 2) }}</td>
                            <td><input readonly type="text" id="receive_total" class="form-control comma_seprated" value="" /></td>
                            <td><input readonly type="text" id="net_total" class="form-control comma_seprated" value="" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
            <div class="text-center">
                <button type="submit" class="btn btn-success" onclick="SetValue(0)">Submit</button>
                <button type="submit" id="BtnSaveAndPrint" class="btn btn-info BtnSaveAndPrint" onclick="SetValue(1)">Save & Print</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <script>
        function SetValue(v) {
            $('#SavePrintVal').val(v);
        }

        function calc(invoice_amount, previous_amount, counter, return_amount) {
            var invoice_amount = parseFloat(invoice_amount) || 0;
            var previous_amount = parseFloat(previous_amount) || 0;
            var return_amount = parseFloat(return_amount) || 0;

            var actual_amount = invoice_amount - previous_amount - return_amount;
            
            var receive_amount_input = $('#receive_amount' + counter).val();
            // Handle comma separated inputs smoothly
            if (receive_amount_input !== undefined && receive_amount_input !== '') {
                receive_amount_input = receive_amount_input.toString().replace(/,/g, '');
            }
            var receive_amount = Math.round(parseFloat(receive_amount_input)) || 0;

            if (receive_amount > actual_amount) {
                alert('Amount cannot be greater than ' + Math.round(actual_amount));
                $('#receive_amount' + counter).val(Math.round(actual_amount));
                receive_amount = Math.round(actual_amount);
            }

            // Sync receiving (net_amount) with receive_amount
            $('#net_amount' + counter).val(receive_amount);

            var total_receive = 0;
            var net_total = 0;

            $('.receive_amount').each(function () {
                var rawVal = $(this).val();
                if (rawVal !== undefined && rawVal !== '') {
                    rawVal = rawVal.toString().replace(/,/g, '');
                }
                total_receive += Math.round(parseFloat(rawVal)) || 0;
            });
            $('#receive_total').val(total_receive);

            $('.net_amount').each(function () {
                var rawVal = $(this).val();
                if (rawVal !== undefined && rawVal !== '') {
                    rawVal = rawVal.toString().replace(/,/g, '');
                }
                net_total += Math.round(parseFloat(rawVal)) || 0;
            });
            $('#net_total').val(net_total);
        }

        $(document).ready(function () {
            $('.select2').select2();
            $("#cheque").select2();
            $('.comma_seprated').number(true, 0); // Using 0 decimals per user request for int rounding

            // Initial calculation
            $('.receive_amount').each(function() {
                var counter = $(this).attr('id').replace('receive_amount', '');
                var invoice_amount = $(this).attr('data-invoice-amount');
                var received_amount = $(this).attr('data-received-amount');
                var return_amount = $(this).attr('data-return-amount');
                calc(invoice_amount, received_amount, counter, return_amount);
            });
            
            hide_unhide(); 
        });

        $('#amount_received').on('keyup', function () {
            var totalReceived = parseFloat($(this).val());
            if (isNaN(totalReceived)) totalReceived = 0;

            var totalRequired = 0;
            $('.receive_amount').each(function () {
                var invoice_amount = parseFloat($(this).attr('data-invoice-amount')) || 0;
                var previous_amount = parseFloat($(this).attr('data-received-amount')) || 0;
                var return_amount = parseFloat($(this).attr('data-return-amount')) || 0;
                totalRequired += invoice_amount - previous_amount - return_amount;
            });

            if (totalReceived > totalRequired) {
                let advance = totalReceived - totalRequired;
                $('#advance_amount').val(advance);
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
                var invoice_amount = parseFloat($(this).attr('data-invoice-amount')) || 0;
                var previous_amount = parseFloat($(this).attr('data-received-amount')) || 0;
                var return_amount = parseFloat($(this).attr('data-return-amount')) || 0;
                var maxReceivable = invoice_amount - previous_amount - return_amount;

                if (remainingAmount > 0) {
                    var assignAmount = Math.min(remainingAmount, maxReceivable);
                    $(this).val(Math.round(assignAmount));
                    remainingAmount -= assignAmount;
                } else {
                    $(this).val(0);
                }
                calc(invoice_amount, previous_amount, counter, return_amount);
            });
        });


        $("form").submit(function (event) {
            var validate = validatee();
            if (!validate) return false;
        });

        function validatee() {
            var validate = true;
            $(".receive_amount").each(function () {
                var id = this.id;
                var amount = $('#' + id).val();
                if (amount === '' || isNaN(amount.toString().replace(/,/g, ''))) {
                    $('#' + id).css('border', '3px solid red');
                    validate = false;
                } else {
                    $('#' + id).css('border', '');
                }
            });

            if ($('#acc_id').val() == '') {
                alert('Please select Debit Account');
                validate = false;
            }
            return validate;
        }

        function hide_unhide() {
            var pay_mode = $('#pay_mode').val();
            if (pay_mode == '2,2') {
                $(".hidee").hide();
                $('#use_advance').closest('.col-lg-3').show();
            } else {
                $(".hidee").show();
            }
        }

        function calculateTotalAmountAdv() {
            let current = 0;
            let adv = Number($('#use_advance option:selected').data('amount')) || 0;
            $('#amount_received').val(Math.round(current + adv)).trigger('keyup');
        }

        function calculateTotalAmount() {
            let current = 0;
            let chequeTotal = 0;
            $('#cheque option:selected').each(function () {
                chequeTotal += Number($(this).data('amount'));
            });
            $('#amount_received').val(Math.round(current + chequeTotal)).trigger('keyup');
        }

    </script>

@endsection