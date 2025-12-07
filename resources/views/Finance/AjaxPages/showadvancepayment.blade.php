@php
use App\Models\AdvancePayment;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$m = request()->m;
$paymentId = request()->id;

$payment = AdvancePayment::where('status', 1)
            ->where('id', $paymentId)
            ->first();
@endphp

    <section  id="printCashPaymentVoucherDetail">

        {{-- ================= HEADER (PRINT LEFT • HEADING CENTER • LOGO RIGHT) ================= --}}
        <div class="row" style="margin-bottom:20px; align-items:center;">

            {{-- LEFT: PRINT BUTTON --}}
            <div class="col-lg-4 col-md-4 col-sm-4 text-left" style="margin:0;margin-top: 35px;">
                {!! CommonHelper::displayPrintButtonInView('printCashPaymentVoucherDetail','','1') !!}
            </div>

            {{-- CENTER: HEADING --}}
            <div class="col-lg-4 col-md-4 col-sm-4 text-center" style="margin:0;margin-top: 35px;">
                <h3 style="margin:0;">View Advance Payment Detail</h3>
            </div>

            {{-- RIGHT: LOGO --}}
            <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                {!! CommonHelper::get_company_logo(Session::get('run_company')) !!}
            </div>

        </div>



        {{-- ================= MAIN CONTENT ================= --}}
        <div class="row headquid" >
            <div class="col-lg-12">
                <div class="well">
                    <div class="row">

                        <div class="col-lg-12">
                            <br>

                            <div class="row">

                                {{-- Payment Basic Info --}}
                                <div class="col-lg-6">
                                    <table class="table table-bordered table-striped table-condensed tableMargin">
                                        <tbody>
                                            <tr>
                                                <td style="width:40%;">Payment No</td>
                                                <td>{{ $payment->payment_no ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Cheque No</td>
                                                <td>{{ $payment->cheque_no ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Payment Mode</td>
                                                <td>
                                                    @if(!empty($payment->cheque_no))
                                                        Cheque
                                                    @else
                                                        Cash
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Amount</td>
                                                <td>{{ number_format($payment->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Payment Date</td>
                                                <td>{{ $payment->adv_date ? FinanceHelper::changeDateFormat($payment->adv_date) : '--' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Customer Information --}}
                                <div class="col-lg-6">
                                    <table class="table table-bordered table-striped table-condensed tableMargin">
                                        <tbody>
                                            <tr>
                                                <td style="width:40%;">Customer</td>
                                                <td>{{ CommonHelper::get_customer_name($payment->customer_id) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Account</td>
                                                <td>{{ CommonHelper::get_account_name($payment->account_recieve_id) ?? '----' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Amount Received No</td>
                                                <td>{{ $payment->amount_recieved_no ?? '--' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Amount Issued No</td>
                                                <td>{{ $payment->amount_issued_no ?? '--' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                {{-- Description --}}
                                <div class="col-lg-12">
                                    <h4>Description: </h4>
                                    <p style="border:1px solid #ddd;padding:13px 9px;border-radius:4px;">
                                        {{ $payment->description ?? 'No description available' }}
                                    </p>
                                </div>


                                {{-- Status --}}
                                <div class="col-lg-6">
                                    <table class="table table-bordered table-striped table-condensed tableMargin">
                                        <tbody>
                                            <tr>
                                                <td style="width:40%;">Status</td>
                                                <td>
                                                    @if($payment->parent_id != null)
                                                        <span class="label label-info">Child Payment</span>
                                                    @elseif($payment->amount_issued_status == 1)
                                                        <span class="label label-success">Issued</span>
                                                    @else
                                                        <span class="label label-warning">Not Issued</span>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Created By</td>
                                                <td>{{ $payment->user_name ?? '--' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>


                            {{-- Printed On --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <label style="border-bottom:1px solid #000 !important;">
                                        Printed On: {{ date('Y-m-d') }} ({{ date('D') }})
                                    </label>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


    </section>

{{-- ================= PRINT SCRIPT ================= --}}
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on("click", ".printBtn", function () {

    let printContents = document.getElementById("printCashPaymentVoucherDetail").innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;

});
</script>
@endsection
