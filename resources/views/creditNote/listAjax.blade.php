@foreach($credits as $credit)
@php
    $rv = null;
    if($credit->rv_no)
        $rv = DB::connection("mysql2")->table("credits_data")->where("rv_no", $credit->rv_no)->first();
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ App\Helpers\SalesHelper::get_customer_name($credit->store) }}</td>
        <td>{{ $credit->details }}</td>
        <td>{{ App\Helpers\CommonHelper::get_account_name($credit->debit) ?? "N/A" }}</td>
        <td>{{ App\Helpers\CommonHelper::get_branch_by_id($credit->branch) }}</td>
        <td class="approve status{{ $credit->rv_no }}">{{ $rv && $rv->rv_status == 2 ? "Approved" : "Pending" }}</td>
        <td>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle btn-xs" type="button" data-toggle="dropdown">
                    Action <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="javascript:void(0)" onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucherForDebit','<?php echo $credit->rv_no;?>','View Credit Note Detail','1','')"><i class="fa fa-eye"></i> View Detail</a></li>
                    @if(!$rv || $rv->rv_status == 1)
                        <li><a href="{{ route('creditNote.update', $credit->id) }}"><i class="fa fa-edit"></i> Edit</a></li>
                        <li><a href="javascript:void(0)" onclick="approve_debit_note('credits_data','credits_item_data','rv_status','rv_date','rv_no','3','{{ $credit->rv_no }}')"><i class="fa fa-check"></i> Approve</a></li>
                    @endif

                </ul>
            </div>
        </td>
    </tr>
@endforeach
