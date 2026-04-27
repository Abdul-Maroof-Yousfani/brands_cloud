<?php 
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m = Session::get('run_company');
}
?>


@foreach ($payments as $key => $payment)
    <tr>
        <td class="text-center">{{++$key}}</td>
        <td class="text-center">{{$payment->payment_no}}</td>
        <td class="text-center">{{$payment->cheque_no ?? "N/A"}}</td>
        <td class="text-center">{{CommonHelper::get_customer_name($payment->customer_id)}}</td>
        <td class="text-center">
            @if($payment->bank_id)
                {{CommonHelper::get_account_name($payment->bank_id)}}
            @elseif($payment->account_recieve_id)
                {{CommonHelper::get_account_name($payment->account_recieve_id)}}
            @else
                ----
            @endif
        </td>
        <td class="text-right">{{number_format($payment->amount, 2)}}</td>
        <td class="text-right" style="font-weight: bold; color: green;">{{number_format($payment->remaining_amount, 2)}}</td>
        <td class="text-center">{{$payment->adv_date ? FinanceHelper::changeDateFormat($payment->adv_date) : '--'}}</td>
        <td class="text-center">{{$payment->amount_recieved_no ?? '--'}}</td>
        <td class="text-center">{{$payment->description ?? '--'}}</td>
        <td class="text-center">
            @if($payment->remaining_amount <= 0)
                <span class="label label-success">Completed</span>
            @elseif($payment->remaining_amount == $payment->amount)
                <span class="label label-info">Not Issued</span>
            @else
                <span class="label label-warning">Partial</span>
            @endif
        </td>
        <td class="text-center">
               <a onclick="showDetailModelOneParamerter('finance/showadvancepayment','{{$payment->id}}','View Advance Payment Detail','{{Session::get('run_company')}}','')" 
               class="btn btn-xs btn-success">View</a>                                                                          
        </td>
    </tr>
@endforeach