@foreach($sales_report_data as $data)
    <tr>
        <td class="text-center">{{ $data->product_name }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->main_ic ?? "N/A" }}</td>
        <td>{{ $data->main_ic ?? "N/A"}}</td>
        <td class="text-center">{{ $data->voucher_no }}</td>
        <td class="text-center">{{ $data->product_barcode }}</td>
        <td class="text-center">{{ $data->qty }}</td>
        <td class="text-center">{{ $data->qty }}</td>
        <td>{{ $data->net_amount }}</td>
        <td>{{ $data->discount_amount }}</td>
        <td>{{ (int)$data->sales_tax + (int)$data->sales_tax_further }}</td>
        <td class="text-center">{{ $data->amount }}</td>
        <td class="text-center">{{ $data->cogs }}</td>
        <td class="text-center"></td>
    </tr>
@endforeach