@foreach($net_sales_reports as $index => $data)
    <tr>
        <td>{{ ++$index }}</td>
        <td>{{ $data->product_name }}</td>
        <td>{{ $data->brand_name }}</td>
        <td>{{ $data->customer_name }}</td>
        <td>{{ $data->territory_name }}</td>
        <td>{{ $data->customer_code }}</td>
        <td>{{ $data->sku }}</td>
        <td>{{ $data->product_type }}</td>
        <td>0</td>
        <td>{{ $data->barcode }}</td>
        <td>{{ number_format($data->qty, 0) }}</td>
        <td>{{ number_format($data->qty, 0) }}</td>
        <td>{{ $data->cog }}</td>
        <td>{{ number_format($data->amount, 2) }}</td>
        <td>{{ number_format($data->discount_amount, 2) }}</td>
        <td>{{ number_format($data->tax_amount, 2) }}</td>
        <td>{{ number_format($data->net_amount, 2) }}</td>
    </tr>
@endforeach