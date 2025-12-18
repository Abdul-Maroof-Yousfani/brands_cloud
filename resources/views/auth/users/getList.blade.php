<table class="table table-bordered sf-table-list" id="TableExportToCsv">
    <thead>
        <tr class="text-center">
            <th class="text-center">S.No</th>
            <th class="text-center">Name</th>
            <th class="text-center">Roles</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $key=>$user)

      
        <tr class="text-center">
            <td class="text-center">{{ $key+1 }}</td>
            <td class="text-center">{{ $user->name }}
                <br>

                <small>
                    {{ $user->username }}
                </small>
            </td>
            <td>

  <button class=" badge badge-success">{{$user->role_name}}</button>

            </td>
            <td class="text-center">{{$user->status == 0 ? 'Inactive' : 'Active'}} </td>
            <td class="text-center">
                <a class="btn btn-warning" style="margin-right: 3px" href="{{route('users.edit',$user->id)}}">
                    <span class="glyphicon glyphicon-pencil"></span>

                </a>
                <!-- <a class="btn btn-danger" href="javascript:;"
                    onclick="deletemodal('{{ route('roles.destroy', $user->id) }}', `{{ route('list.roles') }}`)">
                    <span class="glyphicon glyphicon-trash"></span>
                </a> -->

            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<script>
    $(document).ready(function() {
        $('.select2').select2();
        // Attach select2 to elements when the modal is shown
        $('body').on('shown.bs.modal', '.modal', function() {
            $(this).find('.select2').select2({
                width: '100%',
                allowClear: true
            });
        });
    });
</script>


   <script>
$("#TableExportToCsv").DataTable({
    ordering: true,
    searching: true,
    paging: true,
    info: false,
    autoWidth: false, // prevent DataTables from auto-calculating width
});

</script>