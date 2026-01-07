<table class="table table-bordered sf-table-list" id="TableExportToCsv">
    <thead>
    <tr class="text-center">
        <th class="text-center">Ba No</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Employee</th>
        <th class="text-center">Status</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($BAFormations as $BAFormation)
        <tr class="text-center">
            <td class="text-center">{{ $BAFormation->ba_no }}</td>
            <td class="text-center">{{ $BAFormation->customer_name }}</td>
            <td class="text-center">{{ $BAFormation->employee_name }}</td>
            <td class="text-center">{{$BAFormation->status == 0 ? 'Inactive' : 'Active'}} </td>
            <td class="text-center">
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#editmodal{{ $BAFormation->id }}">
                    Edit
                </button>
                <div class="modal fade" id="editmodal{{ $BAFormation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Create BA Formation</h5>
                            </div>
                            <div class="modal-body">
                                <form id="submitadv" action="{{ route('baFormation.update',$BAFormation->id) }}" method="POST">
                                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                                    <input type="hidden" value="PUT" name="_method">
                                    <input type="hidden" id="listRefresh" value="{{ route('list.baFormation') }}">
                                    <div class="mb-3">
                                        <label for="customers" class="form-label">Customers</label>
                                        <select  multiple class="form-select select2" id="customers" name="customer" style="width: 100%;">
                                            <option value="">Select Customers</option>
                                            @foreach(App\Helpers\SalesHelper::get_all_customer_only_distributors() as $row)
                                                <option {{$BAFormation->customer_id == $row->id ? 'selected' : ''}} value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="employee" class="form-label">Employee</label>
                                        <select  multiple class="form-select select2" id="employee" name="employee" style="width: 100%;">
                                            <option value="">Select Employee</option>
                                            @foreach(App\Helpers\SalesHelper::get_all_employees() as $row)
                                                <option {{$BAFormation->employee_id == $row->id ? 'selected' : ''}} value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="brands" class="form-label">Brands</label>
                                        <select multiple class="form-select select2" id="brands{{ $BAFormation->id }}" name="brands[]" style="width: 100%;">
                                            @foreach(App\Helpers\CommonHelper::get_all_brand() as $item)
                                                <option {{ in_array($item->id, json_decode($BAFormation->brands_ids, true) ?? []) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select  multiple class="form-select select2" name="status" id="status" style="width: 100%;">
                                            <option  {{$BAFormation->status == 1 ? 'selected' : ''}} value="1">Active</option>
                                            <option  {{$BAFormation->status == 0 ? 'selected' : ''}}  value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button style="margin-top: 10px" type="submit" class="btn btn-primary my-2">Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


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

   <script>
$("#TableExportToCsv").DataTable({
    ordering: true,
    searching: true,
    paging: true,
    info: false,
    autoWidth: false,
    columnDefs: [
        { targets: 4, searchable: false } // Action column
    ]
});

</script>
