@php
    $total_ctn = 0;
    $total_pcs = 0;
    $total_total_pcs = 0;
    $total_gross = 0;
    $total_disc = 0;
    $total_disc2 = 0;
    $total_tax = 0;
    $total_net = 0;
@endphp

@foreach($sales_report_data as $data)
    @php
        $gross_amount = $data->qty * $data->unit_price;
        $disc_amount = $data->discount_amount_1 ?? 0;
        $disc2_amount = $data->discount_amount_2 ?? 0;
        $tax_amount = $data->tax_amount ?? 0;
        $net_amount = $data->net_amount;

        $total_ctn += floor($data->ctn);
        $total_pcs += $data->pcs;
        $total_total_pcs += $data->qty;
        $total_gross += $gross_amount;
        $total_disc += $disc_amount;
        $total_disc2 += $disc2_amount;
        $total_tax += $tax_amount;
        $total_net += $net_amount;
    @endphp
    <tr>
        <td>{{ str_replace('cr', 'SR', $data->bill_no) }}</td>
        <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
        <td>{{ strtoupper($data->ref_no) }}</td>
        <td>{{ $data->customer_name }}</td>
        <td>{{ $data->notes }}</td>
        <td>{{ $data->item_name }}</td>
        <td>{{ $data->brand_name }}</td>
        <td>{{ $data->hs_code }}</td>
        <td class="text-right">{{ number_format($data->ctn, 2) }}</td>
        <td class="text-right">{{ number_format($data->pcs, 0) }}</td>
        <td class="text-right">{{ $data->packing }}</td>
        <td class="text-right">{{ number_format($data->qty, 0) }}</td>
        <td class="text-right">{{ number_format($data->unit_price, 2) }}</td>
        <td class="text-right">{{ number_format($gross_amount, 2) }}</td>
        <td class="text-right">{{ $data->discount_percent_1 }}%</td>
        <td class="text-right">{{ number_format($disc_amount, 2) }}</td>
        <td class="text-right">{{ $data->discount_percent_2 }}%</td>
        <td class="text-right">{{ number_format($disc2_amount, 2) }}</td>
        <td class="text-right">{{ round(($tax_amount / ($gross_amount ?: 1)) * 100) }}%</td>
        <td class="text-right">{{ number_format($tax_amount, 2) }}</td>
        <td class="text-right">{{ number_format($net_amount, 2) }}</td>
    </tr>
@endforeach

@if(count($sales_report_data) > 0)
    <tr style="background: #eee; font-weight: bold;">
        <td colspan="8" class="text-right">TOTAL</td>
        <td class="text-right">{{ number_format($total_ctn, 2) }}</td>
        <td class="text-right">{{ number_format($total_pcs, 0) }}</td>
        <td></td>
        <td class="text-right">{{ number_format($total_total_pcs, 0) }}</td>
        <td></td>
        <td class="text-right">{{ number_format($total_gross, 2) }}</td>
        <td></td>
        <td class="text-right">{{ number_format($total_disc, 2) }}</td>
        <td></td>
        <td class="text-right">{{ number_format($total_disc2, 2) }}</td>
        <td></td>
        <td class="text-right">{{ number_format($total_tax, 2) }}</td>
        <td class="text-right">{{ number_format($total_net, 2) }}</td>
    </tr>
@endif
