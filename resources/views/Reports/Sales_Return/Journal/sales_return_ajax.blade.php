@foreach($sales_report_data as $data)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $data->cr_no }}</td>
        <td>{{ $data->customer_name }}</td>
        <td>{{ $data->product_name }}</td>
        <td>{{ $data->brand_name }}</td>
        <td>{{ App\Helpers\CommonHelper::get_company_group_by($data->group_id) }}</td>
        <td>{{ $data->hs_code }}</td>
        <td>{{ $data->qty }}</td>
        <td>{{ $data->qty }}</td>
        <td>{{ $data->packing }}</td>
        <td>{{ $data->qty }};</td>
        <td>{{ $data->sale_price }}</td>
        @php
            $gross_amount = $data->amount - $data->tax_amount + $data->discount_amount + $data->second_discount_amount;
       @endphp
        <td>{{ $gross_amount }}</td>
        <td>{{ ($data->discount_amount / $gross_amount) * 100 }}</td>
        <td>{{ $data->discount_amount }}</td>
        <td>{{ ($data->second_discount_amount / $gross_amount) * 100 }}</td>
        <td>{{ $data->second_discount_amount ?? 0 }}</td>
        <td>{{ round(($data->tax_amount / $gross_amount) * 100) }}</td>
        <td>{{ $data->tax_amount }}</td>
        <td>{{ $data->amount }}</td>
    </tr>
@endforeach