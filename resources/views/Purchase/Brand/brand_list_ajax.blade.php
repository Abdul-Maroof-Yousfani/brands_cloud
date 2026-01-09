     @foreach($brands as $key => $brand)
             <tr class="text-center">
                 <td>{{ ++$key }}</td>
                 <td>{{ $brand->name }}</td>
                 <td>{{ $brand->description }}</td>
                   <td>{{ $brand->principalGroup->products_principal_group ?? '-' }}</td>
                 <td><a href="{{ route('brands.edit', $brand->id) }}">Edit</a></td>
        </tr>
         @endforeach