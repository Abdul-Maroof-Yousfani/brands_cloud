@foreach($net_sales_reports as $index => $data)
    <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $data->customer_code }}</td>
        <td>{{ $data->customer_name }}</td>
        <td>{{ $data->territory_name }}</td>
        <td>{{ $data->hs_code }}</td>
        <td>{{ $data->barcode }}</td>
        <td>{{ $data->sku }}</td>
        <td>{{ $data->product_name }}</td>
        <td>{{ $data->brand_name }}</td>
        <td>{{ $data->cog }}</td>
        <td>{{ number_format($data->qty, 0) }}</td>
        <td>{{ number_format($data->amount, 2) }}</td>
        <td>{{ number_format($data->discount_amount, 2) }}</td>
        <td>{{ number_format($data->amount - $data->discount_amount, 2) }}</td>
        <td>{{ $data->sales_return_qty ?? "N/A" }}</td>
        <td>{{ $data->gross_return_amount ?? "N/A" }}</td>
        <td>{{ $data->gross_return_amount ?? "N/A" }}</td>
        <td>{{$data->qty - ($data->sales_return_qty ?? 0) }}</td>
        <td>{{ number_format(($data->amount - $data->discount_amount) - ($data->gross_return_amount)) }}</td>
       
    </tr>
@endforeach