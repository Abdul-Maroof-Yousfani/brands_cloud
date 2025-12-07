@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{$value->packing_list_no}}</td>
    <td>{{$value->packing_date}}</td>
    <td>{{$value->deliver_to}}</td>
    <td>{{$value->item_name}}</td>
    <td>{{$value->customer_name}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"
                        type="button" data-toggle="dropdown"
                        aria-expanded="false">
                    ...
                </button>
                <ul class="dropdown-menu">
                    <li>
                      
                        <a href="{{route('Packing.show', $value->id)}}"  class="btn btn-sm btn-warning " ><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                    
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @php 
    $count ++;
    @endphp
@endforeach


<script>
    function delete_row(tr, id)
    {
        $.ajax({
                url: '/InventoryMaster/Packing/delete/' + id,
                type: 'Get',
                data: {
                        id:id,
                    },
                success: function (response) {
                    $(tr).remove();
                }   
            });
    }

</script>
