@foreach($payments as $data)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $data->branch ?? "N/A" }}</td>
        <td>{{ $data->territory_name }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_name_warehouse($data->warehouse_from) }}</td>
        <td>{{ $data->customer_code }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->address }}</td>
        <td>{{ 1 }}</td>
        <td>{{ $data->gi_no }}</td>
        <td>{{ $data->brand_id ? \App\Helpers\CommonHelper::get_brand_by_id($data->brand_id) : "N/A" }}</td>
        <td>{{ \Carbon\Carbon::parse($data->gd_date)->format("d-M-y") }}</td>
        <td>{{ $data->sales_person ?? "N/A" }}</td>
        <td>{{ number_format($data->invoice_amount) }}</td>
        <td>{{ $data->rv_no ? $data->rv_no : "N/A" }}</td>
        <td>{{{ number_format($data->receipt_amount) }}}</td>
        <td>
            @if($data->pay_mode == 1)
                Cheque
            @elseif($data->pay_mode == 2)
                Cash
            @elseif($data->pay_mode == 3)
                Online Transfer
            @else
                N/A
            @endif
        </td>
        <td>{{ $data->cr_no ?? "-"  }}</td>
        <td>{{ number_format($data->sale_return_amount) }}</td>
        <td>Adjustment Doc No</td>
        <td>{{ number_format($data->sale_return_amount) }}</td>
        <td>Remarks</td>
        <td>0</td>
        <td>{{ number_format($data->sale_return_amount) }}</td>
        <td>Outstanding</td>
        <td>Difference</td>
        <td>{{ number_format($data->one_to_fourty_five_days_due) }}</td>
        <td>{{ number_format($data->fourty_five_to_ninety_days_due) }}</td>
        <td>{{ number_format($data->ninety_one_to_one_seventy_nine_days_due) }}</td>
        <td>{{ number_format($data->more_than_one_eighty_days_due) }}</td>
    </tr>
@endforeach