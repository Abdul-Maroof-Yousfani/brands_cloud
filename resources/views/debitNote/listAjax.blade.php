@foreach($debits as $debit)
@php
    $rv = null;
    if($debit->rv_no)
        $rv = DB::connection("mysql2")->table("new_rvs")->where("rv_no", $debit->rv_no)->first();
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ App\Helpers\SalesHelper::get_customer_name($debit->store) }}</td>
        <td>{{ $debit->delivery_man }}</td>
        <td>{{ $debit->details }}</td>
        <td>{{ App\Helpers\CommonHelper::get_account_name_by_codename($debit->credit)->name ?? "N/A" }}</td>
        <td>{{ $debit->on_record == 1 ? "Yes" : "No" }}</td>
<td>{{ App\Helpers\CommonHelper::get_vouchers($debit->voucher_type)[0]->name ?? 'N/A' }}</td>
        <td>{{ App\Helpers\CommonHelper::get_branch_by_id($debit->branch) }}</td>
        <td class="approve">{{ $debit->is_approved == 1 ? "Approved" : "Pending" }}</td>
        <td>
            <div class="dropdown">
                <a {{ $debit->is_approved == 1 ? 'disabled' : '' }} onclick="approve(this, '{{ $debit->id }}')" class="btn btn-success">Approve</a>
                  <a onclick="showDetailModelOneParamerter('/debitNote/view', '{{ $debit->id }}')" class="btn btn-primary">View</a>

            </div>
     
        </td>
    </tr>
@endforeach
