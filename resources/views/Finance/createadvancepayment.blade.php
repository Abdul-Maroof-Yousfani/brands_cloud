<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($accType == 'client') {
    $m = Session::get('run_company');
} else {
    $m = Auth::user()->company_id;
}

use App\Helpers\CommonHelper;

?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Create Advance Payment Customer</h2>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <?php echo Form::open(array('url' => 'finance/insertadvancepayment?m=' . $m . '', 'id' => 'chartofaccountForm'));?>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="chartofaccountSection[]" class="form-control"
                                                id="chartofaccountSection" value="1" />
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label>Customer:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control select2 requiredField" name="customer_id"
                                                    id="customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach(CommonHelper::get_customer() as $key => $y)
                                                        <option value="{{ $y->id}}">{{ $y->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="acc_name">Amount </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text"
                                                    autofocus="autofocus" placeholder="amount"
                                                    class="form-control requiredField" name="amount" id="amount" value=""
                                                    autocomplete="off">
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="pwd">Payment Mode</label>
                                                <select id="pay_mode" name="pay_mode" onchange="hide_unhide()"
                                                    class="form-control">
                                                    <option value="1">Cheque</option>
                                                    <option value="2">Cash </option>
                                                    {{-- <option value="3,1">Online Transfer </option> --}}
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                                                <label> Banks </label>
                                                @php
                                                    $bank = CommonHelper::get_bank_accounts();
                                                @endphp
                                                <select style="width: 100%" name="bank" id="bank_id"
                                                    class="form-control select2">
                                                    @foreach ($bank as $row)
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                                                <label for="pwd">Cheque No:</label>
                                                <input type="text" name="cheque" id="" class="form-control">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidee">
                                                <label for="pwd">Cheque Date:</label>
                                                <input value="{{ date('Y-m-d') }}" class="form-control" name="cheque_date"
                                                    type="date">
                                            </div>


                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hideee">
                                                <label for="acc_name">Received Account </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField select2" name="account_recieve_id"
                                                    id="account_recieve_id">
                                                    <option value="">Select Account</option>
                                                    @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                                                        <option {{$y->id == 1235 ? 'selected' : ''}} value="{{$y->id}}">
                                                            {{ $y->code . ' ---- ' . $y->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label"> Date.</label>
                                                <span
                                                    style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                                                <input autofocus onblur="" onchange="" type="date"
                                                    class="form-control requiredField" max="<?php echo date('Y-m-d') ?>"
                                                    name="adv_date" id="adv_date" value="<?php echo date('Y-m-d') ?>" />
                                            </div>

                                        </div>
                                        <div style="line-height:5px;">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="acc_name">Description </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="description" id="description" rows="8" cols="50"
                                                    style="resize:none;font-size: 11px;"
                                                    class="form-control requiredField"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            {{ Form::submit('Submit', ['class' => 'btnn btn-success']) }}
                                            <button type="reset" id="reset" class="btnn btn-danger">Clear Form</button>
                                        </div>

                                        <?php
    echo Form::close();
                                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#customer_id').select2();
        $('#account_recieve_id').select2();
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
    <script>

    $( document ).ready(function() {
hide_unhide()
    });
        $(".btn-success").click(function (e) {
            var chartofAccount = new Array();
            var val;
            $("input[name='chartofaccountSection[]']").each(function () {
                chartofAccount.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of chartofAccount) {

                jqueryValidationCustom();
                if (validate == 0) {
                    //return false;
                } else {
                    return false;
                }
            }
        });

         function hide_unhide() {
            var pay_mode = $('#pay_mode').val();
            if (pay_mode == '2') {
                $(".hidee").css("display", "none");
                $(".hideee").css("display", "block");
                // $('#cheque').val('-');
                $(".for_cash").css("display", "block");
                $('#acc_id').select2();
                $(".rc_amount").show();

            } else {
                $(".hidee").css("display", "block");
                $(".hideee").css("display", "none");
                $(".for_cash").css("display", "none");
                $(".rc_amount").hide();

            }
        }
    </script>
@endsection