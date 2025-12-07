@foreach($credits as $credit)
@php
    $rv = null;
    if($credit->rv_no)
        $rv = DB::connection("mysql2")->table("credits_data")->where("rv_no", $credit->rv_no)->first();
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ App\Helpers\SalesHelper::get_customer_name($credit->store) }}</td>
        <td>{{ $credit->delivery_man }}</td>
        <td>{{ $credit->details }}</td>
        <td>{{ App\Helpers\CommonHelper::get_account_name_by_codename($credit->debit)->name ?? "N/A" }}</td>
        <td>{{ $credit->on_record == 1 ? "Yes" : "No" }}</td>
        <td>{{ App\Helpers\CommonHelper::get_vouchers($credit->voucher_type)[0]->name }}</td>
        <td>{{ App\Helpers\CommonHelper::get_branch_by_id($credit->branch) }}</td>
        <td class="approve status{{ $credit->rv_no }}">{{ $rv && $rv->rv_status == 2 ? "Approved" : "Pending" }}</td>
        <td>
            <div class="dropdown">
                <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucherForDebit','<?php echo $credit->rv_no;?>','View Bank Reciept Voucher Detail','1','')" class="btn btn-xs btn-success">View</a>
            </div>
        </td>
    </tr>
@endforeach
