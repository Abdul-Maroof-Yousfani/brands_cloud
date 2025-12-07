<?php $count=1; ?>
@foreach($branches as $row)

<?php $array = explode('-',$row->code);
$level = count($array);
$nature = $array[0]; ?>
<tr>
    <td class="text-center">{{$count++}}</td>
    <td class="text-center">{{$row->branch_name}}</td>
</tr>


@endforeach