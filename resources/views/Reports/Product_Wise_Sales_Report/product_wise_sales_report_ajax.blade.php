@foreach($items as $data)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ \Carbon\Carbon::parse($data->date)->format("d-M-Y") }}</td>
        <td>{{ $data->buyers_id ? App\Helpers\CommonHelper::get_customer_name($data->buyers_id) : "N/A" }}</td>
        <td>{{ $data->buyers_id ? App\Helpers\CommonHelper::get_customer_address($data->buyers_id) : "N/A" }}</td>
        <td>BA</td>
        <td>{{ $data->sku }}</td>
        <td>{{ $data->product_barcode }}</td>
        <td>{{ $data->product_name }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_brand_by_id($data->brand_id) }}</td>
        <td>{{ $data->qty }}</td>
        <td>{{ $data->purchase_price }}</td>
        <td>{{ $data->amount }}</td>
        <td>1</td>
        <td>{{ $data->mrp_price}}</td>
    </tr>
@endforeach