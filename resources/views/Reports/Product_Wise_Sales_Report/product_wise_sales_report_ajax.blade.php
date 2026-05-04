@php
    $totalQty = 0;
    $totalAmountSP = 0;
    $totalAmountTP = 0;
    $totalAmountMRP = 0;
@endphp

@foreach($items as $data)
    @php
        $markup = 1 + ($data->tax_amount / 100);
        $tp = $data->mrp_price / $markup;
        $mrpTotal = $data->mrp_price * $data->qty; // Wait, is mrp_price unit or total?
        // Based on the view, it seems $data->amount is already total (sale_price * qty)
        // Let's check the controller query: DB::raw("SUM(subitem.sale_price * retail_sale_order_details.qty) AS amount")
        // So amount is total SP.
        // What about mrp_price? subitem.mrp_price AS mrp_price. 
        // If it's from subitem table directly, it's unit price.
        $mrpTotal = $data->mrp_price * $data->qty;
        $tpTotal = $tp * $data->qty;

        $totalQty += $data->qty;
        $totalAmountSP += $data->amount;
        $totalAmountTP += $tpTotal;
        $totalAmountMRP += $mrpTotal;
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ \Carbon\Carbon::parse($data->date)->format("d-M-Y") }}</td>
        <td>{{ $data->buyers_id ? App\Helpers\CommonHelper::get_customer_name($data->buyers_id) : "N/A" }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_ba_location($data->ba_id) }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_username($data->ba_id) }}</td>
        <td>{{ $data->sku }}</td>
        <td>{{ $data->product_barcode }}</td>
        <td>{{ $data->product_name }}</td>
        <td>{{ \App\Helpers\CommonHelper::get_brand_by_id($data->brand_id) }}</td>
        <td>{{ $data->qty }}</td>
        <td>{{ number_format($data->sale_price, 2) }}</td>
        <td>{{ number_format($data->amount, 2) }}</td>
        <td>{{ number_format($tpTotal, 2) }}</td>
        <td>{{ number_format($mrpTotal, 2) }}</td>
    </tr>
@endforeach

@if(count($items) > 0)
    <tr style="background-color: #f1f2f6; font-weight: bold; border-top: 2px solid #ccc;">
        <td colspan="9" style="text-align: right;">Total:</td>
        <td style="text-align: center;">{{ $totalQty }}</td>
        <td></td>
        <td style="text-align: center;">{{ number_format($totalAmountSP, 2) }}</td>
        <td style="text-align: center;">{{ number_format($totalAmountTP, 2) }}</td>
        <td style="text-align: center;">{{ number_format($totalAmountMRP, 2) }}</td>
    </tr>
@endif
