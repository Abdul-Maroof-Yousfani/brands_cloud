@extends('layouts.default')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <div class="well_N">
        <div class="row align-items-center ">
            <div class="col-md-6">
                <h1>BA Targets</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Create Target
                </button>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Make modal larger for table -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create BA Targets</h5>
                    </div>
                    <div class="modal-body">
                        <form id="submitadv" action="{{route('baTargets.store')}}" method="POST">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                            <input type="hidden" id="listRefresh" value="{{route('list.baTargets')}}">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select select2" name="status" id="status" style="width: 100%;" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Targets (Customer-wise)</label>
                                <div style="overflow-x: auto;"> <!-- For horizontal scroll if many brands -->
                                    <table class="table table-bordered" id="targetsTable">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Store</th>
                                                <th>Zone</th>
                                                @foreach($brands as $brand)
                                                    <th>{{ $brand->name }}</th>
                                                @endforeach
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="targetRows">
                                            <!-- Initial row -->
                                            <tr class="target-row">
                                                <td><input type="text" name="targets[0][code]" class="form-control" readonly></td>
                                                <td>
                                                    <select name="targets[0][customer_id]" class="form-select select2 customer-select" style="width: 100%;">
                                                        <option value="">Select Store</option>
                                                        @foreach($customers as $customer)
                                                            <option value="{{ $customer->id }}" data-zone="{{ $customer->zone ?? 'N/A' }}">{{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" name="targets[0][zone]" class="form-control zone-input" readonly></td>
                                                @foreach($brands as $brand)
                                                    <td><input type="number" name="targets[0][brands][{{ $brand->id }}]" class="form-control" min="0"></td>
                                                @endforeach
                                                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" id="addMore" class="btn btn-secondary">Add More</button>
                                </div>
                            </div>
                            <button style="margin-top: 10px" type="submit" class="btn btn-primary my-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="filteredData">
            <div class="text-center spinnerparent">
                <div class="spinner-border" role="status">
                    <img style="width: 100px" src="{{asset('/public/loading-gif.gif')}}" alt="">
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')

    <script>
        $(document).ready(function () {
            filterationCommonGlobal('{{route('list.baTargets')}}');

            // Initialize select2 on initial row
            $('.select2').select2();

            let rowIndex = 1;

            $('#addMore').click(function () {
                let newRow = $('.target-row:first').clone();
                newRow.find('input').val('');
                newRow.find('select').val('').trigger('change'); // Reset select2
                newRow.find('input[name^="targets"]').each(function () {
                    let name = $(this).attr('name').replace(/\[\d+\]/, '[' + rowIndex + ']');
                    $(this).attr('name', name);
                });
                newRow.find('select[name^="targets"]').each(function () {
                    let name = $(this).attr('name').replace(/\[\d+\]/, '[' + rowIndex + ']');
                    $(this).attr('name', name);
                });
                newRow.appendTo('#targetRows');
                newRow.find('.select2').select2(); // Reinitialize select2 on new row
                rowIndex++;
            });

            // Remove row
            $(document).on('click', '.remove-row', function () {
                if ($('.target-row').length > 1) {
                    $(this).closest('tr').remove();
                }
            });

            // Update zone and code on customer select
            $(document).on('change', '.customer-select', function () {
                let selectedOption = $(this).find('option:selected');
                let zone = selectedOption.data('zone');
                let code = selectedOption.val(); // Using ID as code for simplicity
                $(this).closest('tr').find('.zone-input').val(zone);
                $(this).closest('tr').find('input[name$="[code]"]').val(code);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#status').select2();
    </script>
@endsection