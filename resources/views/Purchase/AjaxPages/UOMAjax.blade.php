@foreach($uoms as $index => $uom)
    <tr>
        <td class="text-center">{{ $index++ }}</td>
        <td>{{ $uom->uom_name }}</td>
        <td>
            <button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editUOM/{{ $uom->id }}','1','UOM Edit Form','1')">
                <span class="glyphicon glyphicon-edit"></span>
            </button>
            <form style="display: inline-block;" method="post" action="{{ route('uom.delete', ['id' => $uom->id]) }}">
                {{ method_field("DELETE") }}
                {{ csrf_field() }}
                <button class="delete-modal btn btn-xs btn-danger" type="submit">
                    <span class="glyphicon glyphicon-trash"></span>
                </button>
            </form>
        </td>
    </tr>
@endforeach