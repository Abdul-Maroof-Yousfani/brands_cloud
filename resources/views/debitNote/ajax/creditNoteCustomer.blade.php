
<table id="" class="table table-bordered" >


        <thead>
        <tr>
            <th  class="text-center">SI No</th>
            <th  class="text-center">SO No</th>
            <th  class="text-center">ST No</th>

            <th  class="text-center">Invoice Amount</th>
            <th  class="text-center">Return Amount</th>
            <th  class="text-center">Previous Received Amount</th>
            <th  class="text-center">Received  Amout</th>
            <th  class="text-center">Tax%</th>
            <th  class="text-center">Tax Amount</th>
            <th  class="text-center">Discount Amount</th>
            <th  class="text-center">Net Amount</th>

        </tr>
        </thead>
        
        <tbody>
            <?php $counter=1;
            $gi_no=[];?>
            @foreach($val as $row)

              <?php

              $invoice_detail= App\Helpers\SalesHelper::get_sales_detail_for_receipt($row);
              $get_freight=App\Helpers\SalesHelper::get_freight($row);
              $received_amount=App\Helpers\SalesHelper::get_received_payment($row);

              if ($received_amount==null):
              $received_amount = 0;
              endif;
            

              $return_amount=App\Helpers\SalesHelper::get_sales_return_from_sales_tax_invoice($row);

              if ($return_amount==null):
              $return_amount = 0;
              endif;

              if ($invoice_detail->so_type==1):
                  $invoice_amount=$invoice_detail->old_amount;
                  else:
                      $invoice_amount=$invoice_detail->invoice_amount+$get_freight;
                      endif;

             $gi_no[]=$invoice_detail->gi_no;
              ?>
              <input type="hidden" name="si_id[]" value="{{$row}}"/>
              <input type="hidden" name="so_id[]" value="{{$invoice_detail->so_id}}"/>

            <tr title="{{'sales_invoice_id='.$row}}">
            <td class="text-center">{{strtoupper($invoice_detail->gi_no)}}</td>
            <td class="text-center">@if ($invoice_detail->so_type==1){{$invoice_detail->description}}@else {{strtoupper($invoice_detail->so_no)}}@endif</td>
                <td>{{ $invoice_detail->sc_no }}</td>
            <td class="text-center">{{number_format($invoice_amount,2)}}</td>

              <td class="text-center">{{number_format($return_amount,2)}}</td>
            <td class="text-center">{{number_format($received_amount,2)}}</td>


            <td><input class="form-control receive_amount" onkeyup="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
             onblur="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)" type="text" name="receive_amount[]" id="receive_amount{{$counter}}"
                      value="{{$invoice_amount-$received_amount-$return_amount}}"
                        ></td>

            <td><select  onchange="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',0)" id="percent{{$counter}}" class="form-control tex_p" name="percent[]">
                    <option value="0">Select</option>
                    @foreach(App\Helpers\ReuseableCode::get_invoice_tax() as $row1)
                        <option value="{{$row1->name}}">{{$row1->name}}</option>
                        @endforeach
                </select>
            </td>


            <td><input onkeyup="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
                       onblur="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)" class="form-control tax" type="text"  value="" name="tax_amount[]" id="tax_amount{{$counter}}"></td>

            <td><input onkeyup="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
            onblur="calc('{{$invoice_amount}}','{{$received_amount}}','{{$counter}}','{{$return_amount}}',1)"
            class="form-control discount" type="text"  value="" name="discount[]" id="discount_amount{{$counter}}"></td>

            <td><input class="form-control net_amount comma_seprated" type="text"  value="{{$invoice_amount-$received_amount-$return_amount}}" name="net_amount[]" id="net_amount{{$counter}}"></td>


              </tr>



              <input type="hidden"  id="inv_amount{{$counter}}" value="{{$invoice_amount}}"/>
              <input type="hidden"  id="rec_amount{{$counter}}" value="{{$received_amount}}"/>
              <input type="hidden"  id="ret_amount{{$counter}}" value="{{$return_amount}}"/>


         <?php $counter++; $gi=implode(',',$gi_no);?>  @endforeach
            <input type="hidden" name="count" id="count" value="{{$counter-1}}"/>
            <input type="hidden" name="ref_bill_no" value="{{$gi}}" />
            <input type="hidden" name="buyers_id" value="{{$invoice_detail->buyers_id}}"/>
        <tr class="heading" style="background-color: darkgrey">
        <td class="text-center" colspan="8">Total</td>
        <td><input readonly type="text" id="tax_total" class="form-control comma_seprated"/></td>

            <td><input readonly type="text" id="discount_total" class="form-control comma_seprated"/></td>
            <td  id=""><input  type="text" id="net_total" class="form-control comma_seprated"/> </td>
        </tr>

        </tbody>
    </table>

    <script>

   
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

//         $("#percent1").change(function(){
// //          var  percent=$('#'+this.id).val();
// //           var count=$('#count').val();
// //            $('.tex_p').val(percent);
// //            for (i=2; i<=count; i++)
// //            {
// //
// //                var inv_amount=$('#inv_amount'+i).val();
// //                var rec_amount=$('#rec_amount'+i).val();
// //                var ret_amount=$('#ret_amount'+i).val();
// //                calc(inv_amount,rec_amount,i,ret_amount);
// //            }


//         });

        // function hide_unhide()
        // {
        //    var pay_mode= $('#pay_mode').val();
        //     if (pay_mode=='2,2')
        //     {
        //         $(".hidee").css("display", "none");
        //         $('#cheque').val('-');
        //     }
        //     else
        //     {
        //         $(".hidee").css("display", "block");
        //     }
        // }
    </script>
