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
    .heading
    {
        font-size: large;
        font-weight: bold;
    }
</style>

<h2 style="font-size: large;font-weight: bold; text-decoration: underline;">Bank Receipt Voucher</h2>

<?php

$WhereIn = implode(',',$val);
$Colll = DB::Connection('mysql2')->select('select * from debits where id in ('.$WhereIn.')');

?>

 <?php echo Form::open(array('url' => 'fad/addDebitReceipt?m='.$_GET['m'].'','id'=>'createSalesOrder','class'=>'stop'));?>
<div class="well_N">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label for="email">Voucher Date</label>
                       <input type="date" value="{{date('Y-m-d')}}" class="form-control" id="v_date" name="v_date">
                       </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="pwd">Payment Mode</label>
                        <select id="pay_mode" name="pay_mode" onchange="hide_unhide()" class="form-control">
                            <option value="1,1">Cheque</option>
                            <option value="2,2">Cash </option>
                            <option value="3,1">Online Transfer </option>
                        </select>
                    </div>


                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                        <label for="pwd">Cheque No:</label>
                         <input type="text" class="form-control" id="cheque" name="cheque">
                                 </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
        <label for="pwd">Cheque Date:</label>
      <input value="{{date('Y-m-d')}}" class="form-control" name="cheque_date" type="date" >
    </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <label for="pwd">Account</label>
            <select name="acc_id" id="acc_id" class="form-control select2 cheque">
                <option value="">Select</option>
                    @foreach(CommonHelper::get_bank_accounts() as $row)
                    <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
            </select>

            

        </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="comment">Remarks:</label>
                        <textarea name="desc" class="form-control" rows="3" id="comment"></textarea>

                    </div>
    </div>
        </div>
            </div>


        <input type="hidden" name="ref_bill_no" value="" />
    <div>&nbsp;</div>

    <table class="table table-bordered w-100" style="width: 100%; table-layout: fixed;">
    <thead class="text-center">
        <tr>
            <th>Debit No</th>
            <th>Amount</th>
            <th>Previous Received Amount</th>
            <th>Received Amount</th>
            <th>Net Amount</th>
        </tr>
    </thead>

    <tbody>
        @php
            $counter = 1;
            $gi_no = [];
            $debits = DB::connection('mysql2')->select('select * from debits where id in ('.$WhereIn.')');
        @endphp

        @foreach($debits as $row)
        @php
              $received_amount=SalesHelper::get_received_payment_for_debit($row->rv_no);
        @endphp
            <input type="hidden" name="si_id[]" value="{{ $row->rv_no }}"/>

            <tr title="debit_id={{ $row->id }}">
                <td class="text-center">{{ $row->rv_no }}</td>
                <td class="text-center">{{ number_format($row->amount - \App\Helpers\SalesHelper::get_received_payment_for_debit($row->rv_no), 2) }}</td>
                <td class="text-center">{{ $received_amount }}</td>
                <td>
                    <input class="form-control receive_amount"
                           onkeyup="calc('{{ $row->amount }}', '0', '{{ $counter }}', '0', 1)"
                           onblur="calc('{{ $row->amount }}', '0', '{{ $counter }}', '0', 1)"
                           type="text"
                           name="receive_amount[]"
                           id="receive_amount{{ $counter }}">
                </td>
                <td>
                    <input class="form-control net_amount comma_seprated"
                           type="text"
                           readonly
                           name="net_amount[]"
                           id="net_amount{{ $counter }}">
                </td>
            </tr>

            @php $counter++; $gi = implode(',', $gi_no); @endphp
        @endforeach

        <input type="hidden" name="count" id="count" value="{{ $counter - 1 }}"/>
        <input type="hidden" name="ref_bill_no" value="{{ $gi }}"/>
        <input type="hidden" name="buyers_id" value="{{ $row->store }}"/>

        <tr class="heading bg-secondary text-white">
            <td class="text-center" colspan="3">Total</td>
            <td><input readonly type="text" id="tax_total" class="form-control comma_seprated"/></td>
            <td><input readonly type="text" id="net_total" class="form-control comma_seprated"/></td>
        </tr>
    </tbody>
</table>

    <input type="hidden" id="SavePrintVal" name="SavePrintVal" value="0">
    <div class="text-center">
    <button   type="submit" class="btn btn-success" onclick="SetValue(0)">Submit</button>
    <button type="submit" id="BtnSaveAndPrint" class="btn btn-info BtnSaveAndPrint" onclick="SetValue(1)">Save & Print</button>
    </div>
</div>
{{Form::close()}}
    <script>
        const banks = @json(CommonHelper::get_bank_accounts());
        const cashes = @json(CommonHelper::get_cash_accounts());
        function SetValue(v)
        {
            $('#SavePrintVal').val(v);
        }
        function calc(invoice_amount,previous_amount,counter,return_amount,type)
        {

          //  alert(invoice_amount+' '+previous_amount+' '+counter+' '+return_amount);
            var invoice_amount=parseFloat(invoice_amount);
            var previous_amount=parseFloat(previous_amount);
            var return_amount=parseFloat(return_amount);

            if (isNaN(return_amount))
            {
                return_amount=0;
            }

            if (isNaN(previous_amount))
            {
                previous_amount=0;
            }
            var actual_amount=invoice_amount-previous_amount-return_amount;


            var receive_amount=parseFloat($('#receive_amount'+counter).val());

            if (isNaN(receive_amount))
            {
                receive_amount=0;
            }

            if (receive_amount>actual_amount)
            {
                alert('Amount Can not greater them '+actual_amount);
                $('#receive_amount'+counter).val(0);
                return false;
            }

            if (type==0)
            {
                var tax_percent=parseFloat($('#percent'+counter).val());
                var tax_amount=((receive_amount/100)*tax_percent).toFixed(2);
                $('#tax_amount'+counter).val(tax_amount);
            }

            else
            {
                var tax_amount=parseFloat($('#tax_amount'+counter).val());
                if (isNaN(tax_amount))
                {
                    tax_amount=0;
                }
            }



            var discount_amount=parseFloat($('#discount_amount'+counter).val());
            if (isNaN(discount_amount))
            {
                discount_amount=0;
            }

            var net_amount=receive_amount-tax_amount-discount_amount;
            $('#net_amount'+counter).val(net_amount);


            var amount=0;

            $('.net_amount').each(function (i, obj) {

                amount += +$('#'+obj.id).val();
            });
            amount=parseFloat(amount);
            $('#net_total').val(amount);


            var tax=0;

            $('.tax').each(function (i, obj) {

                tax += +$('#'+obj.id).val();
            });
            tax=parseFloat(tax);
            $('#tax_total').val(tax);



            var discount=0;

            $('.discount').each(function (i, obj) {

                discount += +$('#'+obj.id).val();
            });
            discount=parseFloat(discount);
            $('#discount_total').val(discount);

        }

        $(document).ready(function() {
            $('.select2').select2();
            
            $('.cash').next('.select2-container').hide().prop("disabled", true);
            $('.comma_seprated').number(true,2);
        });




        $( "form" ).submit(function( event )
        {
            var validate=validatee();

            if (validate==true)
            {

            }
            else
            {
                return false;
            }

        });
        function validatee()
        {
            var validate=true;
            $( ".receive_amount" ).each(function() {
                var id=this.id;



                    var amount=$('#'+id).val();

                    if (amount <= 0 || amount=='')
                    {
                        $('#'+id).css('border', '3px solid red');

                        validate=false;
                    }
                    else
                    {
                        $('#'+id).css('border', '');

                        if ($('#cheque').val()=='')
                        {
                            $('#cheque').css('border', '3px solid red');

                            validate=false;
                        }

                        if ($('#acc_id').val()=='')
                        {
                           alert('pls select Debit Account');
                            validate=false;
                            return false;


                        }
                    }

            });
            return validate;
        }

        $("#percent1").change(function(){
//          var  percent=$('#'+this.id).val();
//           var count=$('#count').val();
//            $('.tex_p').val(percent);
//            for (i=2; i<=count; i++)
//            {
//
//                var inv_amount=$('#inv_amount'+i).val();
//                var rec_amount=$('#rec_amount'+i).val();
//                var ret_amount=$('#ret_amount'+i).val();
//                calc(inv_amount,rec_amount,i,ret_amount);
//            }


        });

        function hide_unhide()
        {


           var pay_mode= $('#pay_mode').val();
            if (pay_mode=='2,2')
            {
                // acc_id
                // cash
                $(".hidee").css("display", "none");
                $('#cheque').val('-');

                let $select = $('#acc_id');

                // Clear existing options
                $select.empty().append('<option value="">Select</option>');

                // Add new options
                cashes.forEach(acc => {
                    $select.append(`<option value="${acc.id}">${acc.name}</option>`);
                });

                
                // Refresh Select2
                $select.val('').trigger('change');

            }
            else
            {
                // cheque, online
                $(".hidee").css("display", "block");// Hide cash select2

                 
                let $select = $('#acc_id');

                // Clear existing options
                $select.empty().append('<option value="">Select</option>');

                // Add new options
                banks.forEach(acc => {
                    $select.append(`<option value="${acc.id}">${acc.name}</option>`);
                });

                
                // Refresh Select2
                $select.val('').trigger('change');


            }
        }
    </script>
    @endsection