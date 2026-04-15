@foreach($sales_report_data as $data)
    @php
        $gross_sales_local = $data->amount - $data->tax_amount + $data->discount_amount;
        $additional_st = App\Helpers\CommonHelper::get_additional_sales_tax($data->so_no) ? App\Helpers\CommonHelper::get_additional_sales_tax($data->so_no)->sale_taxes_amount_rate : 0;
        $net_sales_local = $data->net_amount + $additional_st;
    @endphp
    <tr>
        <td>{{ \App\Helpers\CommonHelper::territory_name($data->territory_id) }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_city_name_by_id($data->city)->name ?? "N/A" }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_name_warehouse($data->warehouse_from) ?? "N/A" }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_buyer_detail($data->buyers_id)->name }}</td>
        <td class="text-center">{{ $data->brand_name }}</td>
        <td class="text-center">{{ $data->main_ic ?? "N/A" }}</td>
        <td class="text-center">{{ $data->gi_no }}</td>
        <td class="text-center">{{ App\Helpers\CommonHelper::get_company_group_by($data->group_id) }}</td>
        <td class="text-center">{{ $data->hs_code }}</td>
        <td class="text-center">{{ \Carbon\Carbon::parse($data->despacth_document_date)->format("d-M-Y") }}</td>
        <td class="retail_val">{{ number_format($data->retail_value, 2, '.', '') }}</td>
        <td class="pcs_val">{{ $data->qty }}</td>
        <td class="gross_val">{{ number_format($gross_sales_local, 2, '.', '') }}</td>
        <td class="discount_val text-center">{{ number_format($data->discount_amount, 2, '.', '') ?? 0 }}</td>
        <td class="tax_val text-center">{{ number_format($data->tax_amount, 2, '.', '') ?? 0 }}</td>
        <td class="additional_tax_val text-center">{{ number_format($additional_st, 2, '.', '') }}</td>
        <td class="net_val text-center">{{ number_format($net_sales_local, 2, '.', '') }}</td>
    </tr>
@endforeach
