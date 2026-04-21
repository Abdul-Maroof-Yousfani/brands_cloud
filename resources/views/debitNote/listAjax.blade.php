@foreach($debits as $debit)
@php
    $rv = null;
    if($debit->rv_no)
        $rv = DB::connection("mysql2")->table("new_rvs")->where("rv_no", $debit->rv_no)->first();
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ App\Helpers\SalesHelper::get_customer_name($debit->store) }}</td>
        <td>{{ $debit->details }}</td>
        <td>{{ App\Helpers\CommonHelper::get_account_name($debit->credit) }}</td>
        <td>{{ App\Helpers\CommonHelper::get_branch_by_id($debit->branch) }}</td>
        <td class="approve">{{ $debit->is_approved == 1 ? "Approved" : "Pending" }}</td>
        <td>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle btn-xs" type="button" data-toggle="dropdown">
                    Action <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="javascript:void(0)" onclick="showDetailModelOneParamerter('/debitNote/view', '{{ $debit->id }}')"><i class="fa fa-eye"></i> View</a></li>
                    @if($debit->is_approved == 0)
                        <li><a href="javascript:void(0)" onclick="approve(this, '{{ $debit->id }}')"><i class="fa fa-check"></i> Approve</a></li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
@endforeach
