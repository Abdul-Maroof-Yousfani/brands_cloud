@foreach($outstandings as $data)
    <tr>
        <td>{{ $data->rv_no }}</td>
        <td>{{ $data->rv_date }}</td>
        <td>{{ explode("-", $data->description)[0] }}</td>
        <td>{{ $data->description }}</td>
        <td>{{ $data->brand_id ? \App\Helpers\CommonHelper::get_brand_by_id($data->brand_id) : 'N/A' }}</td>
        <td>{{ $data->territory_id ? \App\Helpers\CommonHelper::territory_name($data->territory_id) : "N/A" }}</td>
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
    
        <td>Deposit To</td>
        <td>{{ $data->cheque_no }}</td>
        <td>{{ $data->bank ? \App\Helpers\CommonHelper::get_bank_account_by_id($data->bank)->bank_name : "N/A" }}</td>
        <td>{{ $data->cheque_date }}</td>
        <td>{{ number_format($data->amount) }}</td>
    </tr>
@endforeach