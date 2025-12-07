<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;


//$view=ReuseableCode::check_rights(174);


?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">    
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="headquid">
                                            <span class="subHeadingLabelClass">View Advance Payment List</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  <div class="row">

                                    {{-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>From Date</label>
                                        <input type="Date" name="FromDate" id="FromDate" min="{{$AccYearFrom}}" max="{{$AccYearTo;}}" value="{{$currentMonthStartDate;}}" class="form-control" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>To Date</label>
                                        <input type="Date" name="ToDate" id="ToDate" min="{{$AccYearFrom}}" max="{{$AccYearTo;}}" value="{{$currentMonthEndDate;}}" class="form-control" />
                                    </div> --}}
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>Customer</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2">
                                            <option value="">Select Customer</option>
                                            @foreach(CommonHelper::get_customer() as $key => $y)
                                                <option value="{{ $y->id}}">{{ $y->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>Issued Status</label>
                                        <select name="amount_issued_status" id="amount_issued_status" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">Issued</option>
                                            <option value="0">Not Issued</option>
                                        </select>
                                    </div>
                                   
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
                                        <input type="button" value="View Range Wise Data Filter" class="btn btn-primary" onclick="filter_advance_list();" style="margin-top: 33px;" />
                                    </div>
                                </div>


                                <div class="lineHeight">&nbsp;</div>
                                <div id="printBankPaymentVoucherList">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel">
                                                <div class="panel-body" id="PrintPanel">
                                                    <div class="row" id="ShowHide">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <h5 style="text-align: center" id="h3"></h5>
                                                                <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv">
                                                                    <thead>
                                                                    <th class="text-center">S.No</th>
                                                                    <th class="text-center">Payment No</th>
                                                                    <th class="text-center">Cheque No</th>
                                                                    <th class="text-center">Customer Name</th>
                                                                    <th class="text-center">Account Recieved Name</th>
                                                                    <th class="text-center">Amount </th>
                                                                    <th class="text-center">Advance Date </th>
                                                                    <th class="text-center">Amount Recieved No</th>
                                                                    <th class="text-center">Amount Issued No</th>
                                                                    <th class="text-center">Description</th>
                                                                    <th class="text-center">Issued Status</th>
                                                                     <th class="text-center">Action</th> 
                                                                    </thead>
                                                                    <tbody id="data">

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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function(){
             $('#customer_id').select2();
            filter_advance_list();
        });

        function EyeSlash(Id)
        {
            $('.toggle-password'+Id).toggleClass("fa-eye fa-eye-slash");

            var input = $("#Password"+Id);
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        }

        function FieldOpen(Id)
        {
            $('#Password'+Id).attr('readonly',false);
            $('#BtnUpdate'+Id).css('display','block');
            $('#BtnEdit'+Id).css('display','none');
        }
        function UpdatePassword(Id)
        {
            var Password = $('#Password'+Id).val();
            if(Password !="")
            {
                $('#Password'+Id).css('border-color','#ccc');
                $('#Loading'+Id).html('<img src="<?php echo url('/');?>/assets/img/loading.gif">');
                $.ajax({
                    url: '/finance/update_user_password',
                    type: 'Get',
                    data: {Password: Password,Id:Id},

                    success: function (response)
                    {
                        if(response == 'yes')
                        {
                            $('#Loading'+Id).html('');
                            $('#Password'+Id).attr('readonly',true);
                            $('#BtnUpdate'+Id).css('display','none');
                            $('#BtnEdit'+Id).css('display','block');
                        }
                    }
                });
            }
            else
            {
                $('#Password'+Id).css('border-color','#c76969');
            }

        }

        function filter_advance_list()
        {
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            let = customer_id = $('#customer_id').val();
            let = amount_issued_status = $('#amount_issued_status').val();
            $.ajax({
                url: '<?php echo url('/')?>/finance/viewadvancepayment',
                type: 'Get',
                data: {customer_id:customer_id,amount_issued_status:amount_issued_status},

                success: function (response)
                {
                    $('#data').html(response);
                }
            });
        }

        function ActiveInActiveUser(Id,Val)
        {

            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/finance/activeInActiveUser',
                type: 'Get',
                data: {UserId: Id,statusVal:Val},

                success: function (response)
                {
                    filter_user_list();
                }
            });

        }
    </script>

@endsection
