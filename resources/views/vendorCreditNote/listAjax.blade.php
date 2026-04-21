@foreach($credits as $credit)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ App\Helpers\SalesHelper::get_supplier_name($credit->vendor_id) }}</td>
        <td>{{ $credit->details }}</td>
        <td>{{ App\Helpers\CommonHelper::get_account_name($credit->debit) }}</td>
        <td>{{ App\Helpers\CommonHelper::get_branch_by_id($credit->branch) }}</td>
        <td class="approve">{{ $credit->is_approved == 1 ? "Approved" : "Pending" }}</td>
        <td>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle btn-xs" type="button" data-toggle="dropdown">
                    Action <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="javascript:void(0)" onclick="showDetailModelOneParamerter('/vendorCreditNote/view', '{{ $credit->id }}')"><i class="fa fa-eye"></i> View</a></li>
                    @if($credit->is_approved == 0)
                        <li><a href="javascript:void(0)" onclick="approve(this, '{{ $credit->id }}')"><i class="fa fa-check"></i> Approve</a></li>
                    @endif
                    <li><a href="javascript:void(0)" onclick="deleteCredit(this, '{{ $credit->id }}')"><i class="fa fa-trash"></i> Delete</a></li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
