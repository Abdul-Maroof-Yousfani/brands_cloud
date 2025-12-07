<?php 
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
?>
@foreach ($payments as $key => $payment)
    <tr>
        <td class="text-center">{{++$key}}</td>
        <td class="text-center">{{$payment->payment_no}}</td>
        <td class="text-center">{{CommonHelper::get_supplier_name($payment->supplier_id)}}</td>
        <td class="text-center">{{CommonHelper::get_account_name($payment->account_recieve_id) ?? '----'}}</td>
        <td class="text-center">{{$payment->amount ?? '--'}}</td>
        <td class="text-center">{{$payment->adv_date ?? '--'}}</td>
        <td class="text-center">{{$payment->amount_recieved_no ?? '--'}}</td>
        <td class="text-center">{{$payment->amount_issued_no ?? '--'}}</td>
        <td class="text-center">{{$payment->description ?? '--'}}</td>
        <td class="text-center">
            {!! 
            $payment->parent_id != null
            ? '-'
            : ($payment->amount_issued_status == 1
                ? "<span style='color:green'>Issued</span>"
                : "<span style='color:red'>Not Issued</span>"
            )
        !!}
        </td>

    </tr>
@endforeach