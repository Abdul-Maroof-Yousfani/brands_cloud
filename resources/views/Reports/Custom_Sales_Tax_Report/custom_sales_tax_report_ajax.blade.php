@foreach($sales_report_data as $data)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ \App\Helpers\CommonHelper::territory_name($data->territory_id) }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_city_name_by_id($data->city)->name ?? "N/A" }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_name_warehouse($data->warehouse_from) ?? "N/A" }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_buyer_detail($data->buyers_id)->name }}</td>
        <td class="text-center">{{ $data->brand_name }}</td>
        <td class="text-center">{{ $data->main_ic ?? "N/A" }}</td>
        <td class="text-center">{{ $data->gi_no }}</td>
        <td class="text-center">{{ App\Helpers\CommonHelper::get_group_by($data->group_id) }}</td>
        <td class="text-center">{{ $data->hs_code }}</td>
        <td class="text-center">{{ \Carbon\Carbon::parse($data->despacth_document_date)->format("d-M-Y") }}</td>
        <td>{{ number_format($data->retail_value) }}</td>
        <td>{{ $data->qty }}</td>
        <td>{{ number_format($data->amount - $data->tax_amount + $data->discount_amount, 2) }}</td>
        <td class="text-center">{{ number_format($data->discount_amount, 2) ?? 0 }}</td>
        <td class="text-center">{{ number_format($data->tax_amount, 2) ?? 0 }}</td>
        @php
            $additional_st = App\Helpers\CommonHelper::get_additional_sales_tax($data->so_no) ? App\Helpers\CommonHelper::get_additional_sales_tax($data->so_no)->sale_taxes_amount_rate : 0;
        @endphp
        <td class="text-center">{{ $additional_st }}</td>
        <td class="text-center">{{ number_format($data->net_amount + $additional_st, 2) }}</td>
        <td class="text-center">{{ $data->percentid ?? "N/A" }}</td>
    </tr>
@endforeach
