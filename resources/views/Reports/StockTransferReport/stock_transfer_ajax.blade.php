@php
    $total_qty = 0;
@endphp
@if(count($items) > 0)
    @foreach($items as $val)
        @php
            $total_qty += $val->qty;
            if ($val->received_qty >= $val->qty) {
                $status = '<span class="label label-success">Received</span>';
            } elseif ($val->received_qty > 0) {
                $status = '<span class="label label-info">Partial</span>';
            } else {
                $status = '<span class="label label-warning">In Transit</span>';
            }
        @endphp
        <tr>
            <td class="text-center">{{ $val->tr_no }}</td>
            <td class="text-center">{{ date('d-m-Y', strtotime($val->date)) }}</td>
            <td class="text-center">{{ $val->sku_code }}</td>
            <td class="text-center">{{ $val->product_name }}</td>
            <td class="text-center">{{ $val->product_barcode }}</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($val->qty, 2) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($val->received_qty, 2) }}</td>
            <td class="text-center">{{ $val->warehouse_from }}</td>
            <td class="text-center">{{ $val->warehouse_to }}</td>
            <td class="text-center">{!! $status !!}</td>
            <td class="text-center">{{ $val->description }}</td>
            <td class="text-center">{{ $val->created_by }}</td>
        </tr>
    @endforeach
    @php
        $total_received_qty = $items->sum('received_qty');
    @endphp
    <script>
        $("#tfoot").html(`
            <tr>
                <th colspan="5" class="text-right">Total:</th>
                <th class="text-center">{{ number_format($total_qty, 2) }}</th>
                <th class="text-center">{{ number_format($total_received_qty, 2) }}</th>
                <th colspan="5"></th>
            </tr>
        `);
    </script>
@else
    <tr>
        <td colspan="10" class="text-center">No Data Found</td>
    </tr>
    <script>
        $("#tfoot").html("");
    </script>
@endif
