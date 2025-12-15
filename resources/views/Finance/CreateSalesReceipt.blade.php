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

    <h2 style="font-size: large;font-weight: bold; text-decoration: underline;">Bank Receipt Voucher</h2>

    <?php
    
    $WhereIn = implode(',', $val);
    $Colll = DB::Connection('mysql2')->select('select gi_no,buyers_id from sales_tax_invoice where id in(' . $WhereIn . ') group by buyers_id');
    $buyers_id = [];
    ?>

    <?php echo Form::open(['url' => 'fad/addSalesReceipt?m=' . $_GET['m'] . '', 'id' => 'createSalesOrder', 'class' => 'stop']); ?>
    <div class="well_N">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="email">Voucher Date</label>
                        <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="v_date" name="v_date">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label for="pwd">Payment Mode</label>
                            <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                                <option value="1,1">Cheque</option>
                                <option value="2,2" selected>Cash </option>
                                <option value="3,1">Online Transfer </option>
                            </select>
                    </div>

                    
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                        <label for="pwd">Cheque No:</label>
                        <input type="text" class="form-control" value="-" id="cheque1" name="cheque">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                        <label for="pwd">Cheque Date:</label>
                        <input value="{{ date('Y-m-d') }}" class="form-control" name="cheque_date" type="date">
                    </div>

                    <?php 
                    foreach ($Colll as $cc):
                        $buyers_id[] = $cc->buyers_id;
                    endforeach; ?>
                    

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="pwd">Dr Account</label>
                        <select name="acc_id" id="acc_id" class="form-control select2">
                            <option value="">Select</option>
                            @foreach (CommonHelper::get_all_account() as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>

                    </div>


                                                     <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Principal Group :</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select style="width:100% !important;" autofocus name="principal_group_id" id="principal_group"
                                                                class="form-control  select2" onchange="get_brand_by_principal_group(this)">
                                                                <option value="">Select Category</option>
                                                                @foreach(App\Helpers\CommonHelper::get_all_principal_groups() as $principal) 
                                                                    <option value="{{ $principal->id }}">{{ $principal->products_principal_group }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                            <label>Brand :</label>
                                                            <span class="rflabelsteric"></span>
                                                             <select name="brand_id" id="brand_id" class="form-control select2">
                                                                <option value="">Select Brand</option>
                                                                 @foreach (CommonHelper::get_all_brand() as $brand)
                                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                 

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="pwd">Territory</label>
                        <select name="territory_id" id="territory_id" class="form-control select2">
                            <option value="">Select Territory</option>
                            @foreach (CommonHelper::get_all_territories() as $territory)
                                <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    

                  
                    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                        <label for="comment">Remarks:</label>
                        <textarea name="desc" class="form-control" rows="1" id="comment"><?php foreach ($Colll as $cc):
                            echo CommonHelper::byers_name($cc->buyers_id)->name;
                        endforeach; ?></textarea>

                    </div>

              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Advance Payment (Use Only)</h5>
                </div>
              </div>

                      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
                        <label for="pwd">Use Advance:</label>
                        <select style="width: 100%" class="form-control" name="use_advance" id="use_advance"
                            onchange="calculateTotalAmountAdv()">
                            <option value="">Select Advance</option>

                            @foreach (CommonHelper::get_customer_advance($buyers_id) as $val_C)
                                <option value="{{ $val_C->id }}" data-amount="{{ $val_C->balance }}">
                                    {{ $val_C->payment_no . ' -- ' . number_format($val_C->balance, 2) }}
                                </option>
                            @endforeach
                        </select>

                    </div>


                   

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                        <label for="pwd">Cheque No Advance payment:</label>
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
                        <label for="comment">Amount:</label>
                        <input type="number" name="amount_received" id="amount_received" class="form-control requiredField"
                            step="any">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="comment">Advance Amount:</label>
                        <input type="text" name="advance_amount" readonly id="advance_amount"
                            class="form-control requiredField" value="0">
                    </div>

                    <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                        <label for="pwd"> Banks </label>
                        <?php $bank = DB::Connection('mysql2')->table('bank_detail')->get(); ?>
                        <select name="bank" class="form-control">
                            @foreach ($bank as $row)
                                <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                            @endforeach
                        </select>

                    </div> -->

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
                    <th class="text-center">ST No</th>

                    <th class="text-center">Invoice Amount</th>
                    <th class="text-center">Return Amount</th>
                    <th class="text-center">Previous Received Amount</th>
                    <th class="text-center">Received Amount</th>
                    <th class="text-center">Tax%</th>
                    <th class="text-center">Tax Amount</th>
                    <th class="text-center">Discount Amount</th>
                    <th class="text-center">Net Amount</th>

                </tr>
            </thead>

            <tbody>
                <?php $counter = 1;
                $gi_no = []; ?>
                @foreach ($val as $row)
                    <?php
                    
                    $invoice_detail = SalesHelper::get_sales_detail_for_receipt($row);
                    $get_freight = SalesHelper::get_freight($row);
                    $received_amount = SalesHelper::get_received_payment($row);
                    
                    if ($received_amount == null):
                        $received_amount = 0;
                    endif;
                    
                    $return_amount = SalesHelper::get_sales_return_from_sales_tax_invoice($row);
                    
                    if ($return_amount == null):
                        $return_amount = 0;
                    endif;
                    
                    if ($invoice_detail->so_type == 1):
                        $invoice_amount = $invoice_detail->old_amount;
                    else:
                        $invoice_amount = $invoice_detail->invoice_amount + $get_freight;
                    endif;
                    
                    $gi_no[] = $invoice_detail->gi_no;
                    ?>
                    <input type="hidden" name="si_id[]" value="{{ $row }}" />
                    <input type="hidden" name="so_id[]" value="{{ $invoice_detail->so_id }}" />

                    <tr title="{{ 'sales_invoice_id=' . $row }}">
                        <td class="text-center">{{ strtoupper($invoice_detail->gi_no) }}</td>
                        <td class="text-center">
                            @if ($invoice_detail->so_type == 1)
                                {{ $invoice_detail->description }}
                            @else
                                {{ strtoupper($invoice_detail->so_no) }}
                            @endif
                        </td>
                        <td>{{ $invoice_detail->sc_no }}</td>
                        <td class="text-center">{{ number_format($invoice_amount, 2) }}</td>

                        <td class="text-center">{{ number_format($return_amount, 2) }}</td>
                        <td class="text-center">{{ number_format($received_amount, 2) }}</td>


                        <td><input class="form-control receive_amount"data-invoice-amount="{{ $invoice_amount }}"
                                data-received-amount="{{ $received_amount }}" data-return-amount="{{ $return_amount }}"
                                onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                type="text" name="receive_amount[]" id="receive_amount{{ $counter }}"
                                value="{{ $invoice_amount - $received_amount - $return_amount }}"></td>

                        <td><select
                                onchange="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',0)"
                                id="percent{{ $counter }}" class="form-control tex_p" name="percent[]">
                                <option value="0">Select</option>
                                @foreach (ReuseableCode::get_invoice_tax() as $row1)
                                    <option value="{{ $row1->name }}">{{ $row1->name }}</option>
                                @endforeach
                            </select>
                        </td>


                        <td><input
                                onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                class="form-control tax" type="text" value="" name="tax_amount[]"
                                id="tax_amount{{ $counter }}"></td>

                        <td><input
                                onkeyup="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                onblur="calc('{{ $invoice_amount }}','{{ $received_amount }}','{{ $counter }}','{{ $return_amount }}',1)"
                                class="form-control discount" type="text" value="" name="discount[]"
                                id="discount_amount{{ $counter }}"></td>

                        <td><input class="form-control net_amount comma_seprated" type="text"
                                value="{{ $invoice_amount - $received_amount - $return_amount }}" name="net_amount[]"
                                id="net_amount{{ $counter }}"></td>


                    </tr>



                    <input type="hidden" id="inv_amount{{ $counter }}" value="{{ $invoice_amount }}" />
                    <input type="hidden" id="rec_amount{{ $counter }}" value="{{ $received_amount }}" />
                    <input type="hidden" id="ret_amount{{ $counter }}" value="{{ $return_amount }}" />


                    <?php $counter++;
                    $gi = implode(',', $gi_no); ?>
                @endforeach
                <input type="hidden" name="count" id="count" value="{{ $counter - 1 }}" />
                <input type="hidden" name="ref_bill_no" value="{{ $gi }}" />
                <input type="hidden" name="buyers_id" value="{{ $invoice_detail->buyers_id }}" />
                <tr class="heading" style="background-color: darkgrey">
                    <td class="text-center" colspan="8">Total</td>
                    <td><input readonly type="text" id="tax_total" class="form-control comma_seprated" /></td>

                    <td><input readonly type="text" id="discount_total" class="form-control comma_seprated" /></td>
                    <td id=""><input type="text" id="net_total" class="form-control comma_seprated" /> </td>
                </tr>

            </tbody>
        </table>
        <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
        <div class="text-center">
            <button type="submit" class="btn btn-success" onclick="SetValue(0)">Submit</button>
            <button type="submit" id="BtnSaveAndPrint" class="btn btn-info BtnSaveAndPrint" onclick="SetValue(1)">Save
                & Print</button>
        </div>
    </div>
    {{ Form::close() }}
   <script>
function SetValue(v) {
    $('#SavePrintVal').val(v);
}

$('#amount_received').on('keyup', function() {
    var totalReceived = parseFloat($(this).val());
    if (isNaN(totalReceived)) totalReceived = 0;

    var totalRequired = 0;
    $('.receive_amount').each(function() {
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
    $('.receive_amount').each(function() {
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

    var actual_amount = invoice_amount - previous_amount - return_amount;
    var receive_amount = parseFloat($('#receive_amount' + counter).val());
    if (isNaN(receive_amount)) receive_amount = 0;

    if (receive_amount > actual_amount) {
        alert('Amount cannot be greater than ' + actual_amount);
        $('#receive_amount' + counter).val(0);
        return false;
    }

    if (type == 0) {
        var tax_percent = parseFloat($('#percent' + counter).val());
        var tax_amount = ((receive_amount / 100) * tax_percent).toFixed(2);
        $('#tax_amount' + counter).val(tax_amount);
    } else {
        var tax_amount = parseFloat($('#tax_amount' + counter).val());
        if (isNaN(tax_amount)) tax_amount = 0;
    }

    var discount_amount = parseFloat($('#discount_amount' + counter).val());
    if (isNaN(discount_amount)) discount_amount = 0;

    var net_amount = receive_amount - tax_amount - discount_amount;
    $('#net_amount' + counter).val(net_amount);

    var amount = 0;
    $('.net_amount').each(function(i, obj) {
        amount += +$('#' + obj.id).val();
    });
    $('#net_total').val(parseFloat(amount));

    var tax = 0;
    $('.tax').each(function(i, obj) {
        tax += +$('#' + obj.id).val();
    });
    $('#tax_total').val(parseFloat(tax));

    var discount = 0;
    $('.discount').each(function(i, obj) {
        discount += +$('#' + obj.id).val();
    });
    $('#discount_total').val(parseFloat(discount));
}

$(document).ready(function() {
    $('.select2').select2();
    $("#cheque").select2();
    $('.comma_seprated').number(true, 2);
    hide_unhide(); // initialize on page load
});

$("form").submit(function(event) {
    var validate = validatee();
    if (!validate) return false;
});

function validatee() {
    var validate = true;
    $(".receive_amount").each(function() {
        var id = this.id;
        var amount = $('#' + id).val();
        if (amount <= 0 || amount == '') {
            $('#' + id).css('border', '3px solid red');
            validate = false;
        } else {
            $('#' + id).css('border', '');
            var pay_mode = $('#pay_mode').val();


            if (pay_mode == '1,1' || pay_mode == '3,1') {
                // cheque or online transfer
                // if ($('#cheque').val() == '' || $('#cheque').val() == null) {
                //     alert('Please select Cheque(s)');
                //     $('#cheque').css('border', '3px solid red');
                //     validate = false;
                // }
            } else if (pay_mode == '2,2') {
                // cash mode
                if ($('#use_advance').val() == '' || $('#use_advance').val() == null) {
                    // no need for alert; can be optional
                }
            }

            if ($('#acc_id').val() == '') {
                alert('Please select Debit Account');
                validate = false;
                return false;
            }
        }
    });
    return validate;
}

// ======================
// UPDATED hide_unhide()
// ======================
function hide_unhide() {
    var pay_mode = $('#pay_mode').val();

    if (pay_mode == '2,2') {
        // CASH MODE
        $(".hidee").hide(); // hide cheque-related fields
        $('#use_advance').closest('.col-lg-2').show(); // show "Use Advance"
        $('#cheque').closest('.col-lg-3').hide(); // hide multiple cheque dropdown
    } else {
        // CHEQUE / ONLINE MODE
        $(".hidee").show(); // show cheque-related fields
        $('#use_advance').closest('.col-lg-2').hide(); // hide "Use Advance"
        $('#cheque').closest('.col-lg-3').show(); // show multiple cheque dropdown
    }
}

function calculateTotalAmountAdv() {
    // let current = Number($('#amount_received').val()) || 0;
    let current = 0;
    let adv = Number($('#use_advance option:selected').data('amount')) || 0;
    $('#amount_received').val(current + adv).trigger('keyup');
}

function calculateTotalAmount() {
    console.log($("#cheque option:selected"));
    // let current = Number($('#amount_received').val()) || 0;
    let current = 0;
    let chequeTotal = 0;
    $('#cheque option:selected').each(function() {
        console.log($(this).data("amount"));
        chequeTotal += Number($(this).data('amount'));
    });

    $('#amount_received').val(current + chequeTotal).trigger('keyup');
}

$('.tax').trigger('keyup');



function get_brand_by_principal_group(element) {
    var principal_group_id = $(element).val();
    $.ajax({
        // url: '/purchase/get_brand_by_principal_group',
          url: "{{ route('get_brand_by_principal_group') }}",
        type: 'Get',
        data: { principal_group_id: principal_group_id },
        success: function(response) {
            $('#brand_id').empty().select2({
                data: response
            });
            $('#brand_id').select2('open');

        }
    });
}
</script>

@endsection
