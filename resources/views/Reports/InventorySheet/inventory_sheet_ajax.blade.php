@foreach($items as $item)
    <tr>
        <td>{{ $item->sku }}</td>
        <td>{{ $item->barcode }}</td>
        <td>{{ $item->subitem_id }} {{  $item->name }}</td>
        @foreach($brands as $brand)
            <td>
                {{ 
                    isset($stocks[$item->subitem_id][$brand->id]) 
                        ? $stocks[$item->subitem_id][$brand->id] 
                        : 0 
                }}
            </td>
        @endforeach
    </tr>
@endforeach