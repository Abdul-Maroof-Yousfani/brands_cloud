
@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no = CommonHelper::generateUniquePosNo('production_work_order', 'work_no', 'WO');
?>
<div class="row well_N align-items-center">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <ul class="cus-ul">
            <li>
                <h1>Finance</h1>
            </li>
            <li>
                <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Debit Note</h3>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <!-- LEFT SIDE FORM -->
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="well_N dp_sdw">
            <form method="POST" id="submitForm">
            <div class="panel">
                <div class="panel-body">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Create Debit Note</h2>
                    </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row qout-h">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stores</label>
                                    <select name="store" onchange="getData()" id="ClientId" class="form-control select2">
                                        <option value="">Select Store</option>
                                        @foreach(\App\Helpers\CommonHelper::get_customer() as $customer)
                                            <option value="{{ $customer->id }}" {{ old('store') == $customer->id ? "selected" : "" }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Man</label>
                                    <input type="text" class="form-control" name="delivery_man" value="{{ old('delivery_man') }}" placeholder="-">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Time</label>
                                    <input type="date" class="form-control" name="date_and_time" value="{{ old("date_and_time") }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" value="-" class="form-control" name="amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Details</label>
                                    <textarea class="form-control" name="details">{{ old("details") }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" >
                                    <label>Credit</label>
                                    <select class="form-control select2" name="credit">
                                        <option value="">Select Credit</option>
                                        @foreach($accounts as $y)
                                            <option value="{{ $y->id }}">
                                                {{ $y->code .' ---- '. $y->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Debit</label>
                                    <select class="form-control select2" name="debit">
                                        <option value="">Select Debit</option>
                                        @foreach($accounts as $y)
                                            <option {{ old('debit') == $y->code ? 'selected' : '' }} value="{{ $y->code }}">
                                                {{ $y->code .' ---- '. $y->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="form-group d-flex align-items-center">
                                    <label class="me-2">On Record</label>
                                    <input type="checkbox" name="on_record">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Voucher Type</label>
                                    <select class="form-control select2" name="voucher_type">
                                        <option value="">Select Voucher Type</option>
                                        @foreach($vouchers as $voucher)
                                            <option value="{{ $voucher->id }}">{{ $voucher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <select class="form-control select2" name="branch">
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12" style="margin-top: 30px; padding-left: 0px; width: 100%;" id="receipt-table"></div>
                <div class="col-md-12" style="margin-top: 30px; padding-left: 0px;">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Issue Voucher</button>
                </div>
            </form>
            </div>
    </div>

    <!-- RIGHT SIDE PANELS -->
    <div class="col-lg-4 col-md-4 col-sm-12" style="display: none;">
        <div class="d-flex flex-column gap-3">
            
            <!-- Pending Invoices -->
            <div class="panel panel-default shadow-sm" style="border-radius:10px; overflow:hidden;">
                <div class="panel-heading text-white" style="background-color:#007bff; padding:10px 15px;">
                    <strong>PENDING INVOICES (PARTIAL PAYMENT)</strong>
                </div>
                <div class="panel-body" style="background-color:#f8f9fa;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed mb-0" id="table">
                            
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pending Debit Note -->
            {{-- <div class="panel panel-default shadow-sm" style="border-radius:10px; overflow:hidden;">
                <div class="panel-heading text-white" style="background-color:#28a745; padding:10px 15px;">
                    <strong>PENDING DEBIT NOTE (PARTIAL PAYMENT)</strong>
                </div>
                <div class="panel-body" style="background-color:#f8f9fa;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID / Date</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Pending</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>DN-001</strong><br><small>2025-10-12</small></td>
                                    <td class="text-end">5,000.00</td>
                                    <td class="text-end text-danger">1,500.00</td>
                                </tr>
                                <tr>
                                    <td><strong>DN-002</strong><br><small>2025-10-20</small></td>
                                    <td class="text-end">7,500.00</td>
                                    <td class="text-end text-danger">2,000.00</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">12,500.00</th>
                                    <th class="text-end text-danger">3,500.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
</div>


<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>


function issue() {
    console.log($("#submitReceipt"));
    $("#submitReceipt").trigger("submit");
}

// 🔹 Event binding
// $(document).on("submit", "#submitReceipt", function(e) {
//     e.preventDefault(); // stop reload

//     issueVoucher(this); // ✅ 'this' is the actual <form> element
// });
</script>

<script>
    
    var  temp = [];
    // document.getElementById("submitForm").addEventListener("submit", function(event) {
    //     const receiptTable = $("#receipt-table");
    //     if (!receiptTable.children().length) {
    //         event.preventDefault(); 
    //         alert("Enter at least one invoice");
    //     }
    // });
    $(document).on("click", "#receiptCreate", function() {
        receiptData(temp);
    });
    
     function CheckUncheck(Counter,Id)
        {
            if($("input:checkbox:checked").length > 0)
            {

            }
            else
            {
                temp = [];
            }
            $('.AllCheckbox').each(function()
            {

                if ($(this).is(':checked'))
                {
                    $('.BtnSub'+Id).prop('disabled',false);

                }
                else
                {
                    $('.BtnSub'+Id).prop('disabled',true);
                    //temp =[];
                }

            });


            $(".AddRemoveClass"+Id).each(function() {
                if ($(this).is(':checked')) {
                    var checked = ($(this).attr('value'));
                    temp.push(checked);

                    // if(temp.indexOf(checked))
                    // {
                    //     if ($(this).is(':checked')) {
                    //         alert('Please Checked Same Client and then Create Receipt...!');
                    //         $(this).prop("checked", false);
                    //         $('.BtnSub'+Id).prop("disabled", true);
                    //     }
                    // }
                    // else
                    // {
                    //     $('.BtnSub'+Id).prop("disabled", false);
                    // }
                }
                else
                {

                }
            });



        }

    function receiptData() {
        $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        let checkedValues = $('.AllCheckbox[type="checkbox"]:checked')
                .map(function() {
                    return $(this).val();
                })
                .get();
        $.ajax({
            url: '{{ url('/debitNote/customer/showReceipt') }}',
            type: 'Get',
            data: {checkbox: temp},

            success: function (response) {
                $('#receipt-table').html(response);
            }
        });
    }
    function getData() {
        var ClientId = $('#ClientId').val();

        var m = 1;
        $('#ShowHide').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

        $.ajax({
            url: '{{ url('/sdc/getRecieptDataClientWise/create') }}',
            type: 'Get',
            data: {ClientId: ClientId,m:m},

            success: function (response) {
                $('#table').html(response);
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });
});
</script>

@endsection
