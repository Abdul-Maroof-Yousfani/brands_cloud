@foreach($stores as $store)
    <tr>
        <td>1</td>
        <td>{{ \App\Helpers\CommonHelper::get_territory_name($store->territory_id) }}</td>
        <td>{{ $store->customer_code }}</td>
        <td>{{ $store->name }}</td>
        @foreach($brands as $brand)
            <td>
                {{ 
                    isset($stocks[$store->customer_id][$brand->id]) 
                        ? $stocks[$store->customer_id][$brand->id] 
                        : 0 
                }}
            </td>
        @endforeach
    </tr>
@endforeach