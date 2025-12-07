@foreach($sales_order_datas as $data)
    <tr>
        <td class="text-center">{{ $data->product_name }}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->main_ic }}</td>
        <td>{{ $data->main_ic }}</td>
        <td class="text-center">{{ $data->sku_code }}</td>
        <td class="text-center">{{ $data->product_barcode }}</td>
        <td class="text-center"></td>
        <td class="text-center">{{ $data->qty }}</td>
        <td>{{ $data->sub_total }}</td>
        <td>{{ $data->discount_amount_2 + $data->discount_amount_1 }}</td>
        <td>{{ $data->tax_amount }}</td>
        <td class="text-center">{{ $data->amount }}</td>
        <td class="text-center"></td>
        <td class="text-center"></td>
    </tr>
@endforeach