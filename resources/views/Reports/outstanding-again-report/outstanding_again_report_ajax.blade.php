@foreach($outstandings as $data)
    <tr>
        <td>{{ $data->rv_no }}</td>
        <td>{{ $data->rv_date }}</td>
        <td>{{ explode("-", $data->description)[0] }}</td>
        <td>{{ $data->description }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_all_principal_groups_name($data->principal_group_id) }}</td>
        <td>{{ ($data->territory_id ?? $data->customer_territory) ? \App\Helpers\CommonHelper::territory_name($data->territory_id ?? $data->customer_territory) : "N/A" }}</td>
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
    
        <td>{{ $data->internal_bank_name ?? 'N/A' }}</td>
        <td>{{ $data->cheque_no }}</td>
        <td>{{ $data->customer_bank_id ? \App\Helpers\CommonHelper::get_bank_account_by_id($data->customer_bank_id)->bank_name : "N/A" }}</td>
        <td>{{ $data->cheque_date }}</td>
        <td class="total_amount">{{ number_format($data->amount) }}</td>
    </tr>
@endforeach