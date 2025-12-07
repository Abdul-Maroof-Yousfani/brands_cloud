<table class="table table-bordered sf-table-list">
    <thead>
    <tr class="text-center">
        <th class="text-center">S.No</th>
        <th class="text-center">Name</th>
       <th class="text-center">Status</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($roles as $key=>$role)
        <tr class="text-center">
            <td class="text-center">{{ $key+1 }}</td>
            <td class="text-center">{{ $role->name }}</td>
            <td class="text-center">{{$role->status == 0 ? 'Inactive' : 'Active'}} </td>
            <td class="text-center">
                <a class="btn btn-warning" style="margin-right: 3px" href="{{route('roles.edit',$role->id)}}">
                    <span class="glyphicon glyphicon-pencil"></span>

                </a>
                <a  class="btn btn-danger" href="javascript:;"
                   onclick="deletemodal('{{ route('roles.destroy', $role->id) }}', `{{ route('list.roles') }}`)">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div id="paginationLinks">
    {{ $roles->links() }}
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        // Attach select2 to elements when the modal is shown
        $('body').on('shown.bs.modal', '.modal', function () {
            $(this).find('.select2').select2({
                width: '100%',
                allowClear: true
            });
        });
    });
</script>
