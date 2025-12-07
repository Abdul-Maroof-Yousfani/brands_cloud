@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{$value->qc_by}}</td>
    <td>{{$value->qc_packing_date}}</td>
    <td>{{$value->packing_list_no}}</td>
    <td>{{$value->customer_name}}</td>
    <td>{{$value->so_no}}</td>
    <td>{{$value->order_no}}</td>
    <td>
        @if($value->qc_status == 2)
            Test pending
        @elseif($value->qc_status == 3)
            Test performed
        @endif

    </td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"
                        type="button" data-toggle="dropdown"
                        aria-expanded="false">
                    ...
                </button>
                <ul class="dropdown-menu">
                    <li>
                        @if($value->qc_status == 2)
                            <a href="{{route('QaPacking.testingOnReceiveItem', $value->id)}}"  class="btn btn-sm btn-warning "><i class="fa fa-eye" aria-hidden="true"></i> Perform test</a>
                        @elseif($value->qc_status == 3)
                            <a href="{{route('QaPacking.testResultOnReceiveItem', $value->id)}}"  class="btn btn-sm btn-warning "><i class="fa fa-eye" aria-hidden="true"></i> Test Result</a>
                        @endif
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
                url: '/Production/QaPacking/delete/' + id,
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
