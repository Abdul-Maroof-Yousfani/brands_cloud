@php
use App\Helpers\CommonHelper;
$i = 1;
@endphp
@foreach($material_requisitions as $material_requisition)
<tr>
    <td><input type="checkbox" name="" id=""></td>
    <td>{{$i}}</td>
    <td>{{$material_requisition->sale_order_no}}</td>
    <td>{{$material_requisition->order_no}}</td>
    <td>{{$material_requisition->mr_no}}</td>
    <td>{{ CommonHelper::get_item_by_id($material_requisition->finish_good_id)->sub_ic}}</td>

    <td>{{$material_requisition->mr_date}}</td>
    <td>
        @if($material_requisition->mr_status ==  2)
        issued 
        @elseif($material_requisition->mr_status == 3)
        Ongoing In Machine 
        @else
        Panding
        @endif
    </td>
    <td>
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"
                type="button" data-toggle="dropdown"
                aria-expanded="false">
                ...
            </button>
            <ul class="dropdown-menu">
                <li>
                <a href="#"  class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                        <a href="{{route('viewProductionPlane',$material_requisition->id)}}"   class="btn btn-sm btn-success" >
                    <i class="fa fa-eye" aria-hidden="true"></i> Issue Raw Material </a>
                </li>
            </ul>
        </div>
    </td>
</tr>



@php
$i ++;
@endphp
@endforeach