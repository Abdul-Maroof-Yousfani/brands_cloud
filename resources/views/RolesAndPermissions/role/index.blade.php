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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create BA Targets</h5>
                        {{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                    </div>
                    <div class="modal-body">
                        <form id="submitadv" action="{{route('baTargets.store')}}" method="POST">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                            <input type="hidden" id="listRefresh" value="{{route('list.baTargets')}}">
                            <div class="mb-3">
                                <label for="customers" class="form-label">BA Name</label>
                                <select class="form-select select2" id="employee" name="employee"  style="width: 100%;">
                                    <option value="">Select Employee</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_employees() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="customers" class="form-label">Store Name</label>
                                <select class="form-select select2" id="customers" name="customer"  style="width: 100%;">
                                    <option value="">Select Store</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_customer_only_distributors() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="brands" class="form-label">Brands</label>
                                <select multiple
                                        class="form-select select2" id="brands" name="brands[]"  style="width: 100%;">
                                    @foreach(App\Helpers\CommonHelper::get_all_subitems() as $item)
                                        <option value="{{$item->id}}">
                                            {{$item->product_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="brands" class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="brands" class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="brands" class="form-label">Targeted Qty</label>
                                <input type="number" name="target_qty" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="customers" class="form-label">status</label>
                                <select class="form-select select2"  name="status"   id="status"  style="width: 100%;">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
        });



        $(document).ready(function () {
            $('#SubmitForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while the file is being uploaded.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('baFormation.store') }}", // Ensure this route is defined in web.php
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Import Successful!',
                                text: response.message
                            }).then(() => {
                                // Refresh the page after the alert is closed
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Import Failed',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.close();

                        let errors = xhr.responseJSON?.errors;
                        let errorMessage = 'An error occurred.';

                        if (errors) {
                            // Join all error messages into a single string
                            errorMessage = Object.values(errors).map(error => error.join(' ')).join(' ');
                        } else if (xhr.responseJSON?.message) {
                            // Fallback to general error message if specific validation errors are not available
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });
        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#customers').select2({
            placeholder: 'Select Customers',
            allowClear: true
        });
        $('#brands').select2({
            placeholder: 'Select Brands',
            allowClear: true
        });
        $('#employee').select2({
            placeholder: 'Select Employee',
            allowClear: true
        });
        $('#status').select2();
    </script>
@endsection