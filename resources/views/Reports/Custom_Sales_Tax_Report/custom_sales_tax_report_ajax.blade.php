@foreach($sales_report_data as $data)
    @php
        $gross_sales_local = $data->rate * $data->qty;
        // Divide additional tax proportionately if it's per SO? 
        // Actually the user just wants the values from the invoice detail.
        // In the detail view, sale_taxes_amount_rate is shown at the bottom.
        // For individual rows, let's keep it 0 or null if it's a global tax.
        $net_sales_local = $data->total_amount; 
    @endphp
    <tr>
        <td>{{ $data->region_name }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_city_name_by_id($data->city)->name ?? "N/A" }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_name_warehouse($data->warehouse_from) ?? "N/A" }}</td>
        <td>{{ $data->customer_name }}</td>
        <td class="text-center">{{ $data->brand_name }}</td>
        <td>{{ $data->product_name }}</td>
        <td class="text-center">{{ $data->product_barcode }}</td>
        <td class="text-center">{{ $data->main_ic ?? "N/A" }}</td>
        <td class="text-center">{{ $data->gi_no }}</td>
        <td class="text-center">{{ App\Helpers\CommonHelper::get_company_group_by($data->group_id) }}</td>
        <td class="text-center">{{ $data->hs_code }}</td>
        <td class="text-center">{{ \Carbon\Carbon::parse($data->item_date)->format("d-M-Y") }}</td>
        <td class="retail_val">{{ number_format($data->mrp_price, 2, '.', '') }}</td>
        <td class="rate_val">{{ number_format($data->rate, 2, '.', '') }}</td>
        <td class="pcs_val">{{ $data->qty }}</td>
        <td class="gross_val">{{ number_format($gross_sales_local, 2, '.', '') }}</td>
        <td class="discount_val text-center">{{ number_format($data->discount_amount, 2, '.', '') ?? 0 }}</td>
        <td class="tax_percent_val text-center">{{ number_format($data->tax, 2, '.', '') }}%</td>
        <td class="tax_val text-center">{{ number_format($data->tax_amount, 2, '.', '') ?? 0 }}</td>
        <td class="net_val text-center">{{ number_format($net_sales_local, 2, '.', '') }}</td>
    </tr>
@endforeach
