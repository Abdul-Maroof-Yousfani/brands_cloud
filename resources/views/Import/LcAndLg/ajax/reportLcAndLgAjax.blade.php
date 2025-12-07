@php 
    $count = 1;
@endphp    

@foreach ($data as $value)
<tr id="tr{{ $count }}">
    <td>{{ str_replace('_', ' ', $value->type) }}</td>
    <td>{{$value->name}}</td>
    <td>{{$value->limit}}</td>
    <td>{{$value->limit_utilized}}</td>
    <td>{{$value->un_utilized}}</td>
    <td>{{$value->remaining_percentage}}</td>
     
    </tr>
    @php 
    $count ++;
    @endphp
@endforeach

