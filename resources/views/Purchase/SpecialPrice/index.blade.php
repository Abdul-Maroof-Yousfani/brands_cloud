@extends('layouts.default')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <div class="well_N">
        <div class="row align-items-center ">
            <div class="col-md-6">
                <h1>List of Special Price</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Import Special Price
                </button>
            </div>
        </div>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Special Price</h5>
                        {{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                    </div>
                    <div class="modal-body">
                        <form id="importForm" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <!-- Customer Selection -->
                            <div class="mb-3">
                                <label for="customers" class="form-label">Customers</label>
                                <select class="form-select" id="customers" name="customers[]" multiple="multiple" style="width: 100%;">
                                    <option value="all">All Customers</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_customer() as $row)
                                        <option value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn.'*'.$row->terms_of_payment}}">{{$row->name}}</option>
                                    @endforeach
                                    <!-- Add more customers as needed -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload CSV File</label>
                                <input class="form-control" type="file" id="file" name="file" accept=".csv">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{asset('/public/customer_special_prices.csv')}}" target="_blank" class="btn btn-primary" >Download Sample File</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-success">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered sf-table-list">
            <thead >
            <tr class="text-center">
                <th class="text-center">S.no</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Product Name</th>
                <th class="text-center">Product COde</th>
                <th class="text-center">MRP</th>
                <th class="text-center">Sale Price</th>
                <th class="text-center">Creation Date</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($CustomerSpecialPrice as $key => $csp)
                <tr class="text-center">
                    <td>{{ ++$key }}</td>
                    <td>{{ $csp->customer_name }}</td>
                    <td>{{ App\Helpers\CommonHelper::get_product_name($csp->product_id) }}</td>
                    <td>{{ $csp->product_code }}</td>
                    <td>{{ $csp->mrp_price }}</td>
                    <td>{{ $csp->sale_price }}</td>
                    <td>{{ \Carbon\Carbon::parse($csp->created_at)->format('m/d/Y') }}</td>
                    <td><a href="{{ route('specialPrice.edit', $csp->id) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')

    <script>

        {{--$(document).ready(function () {--}}
        {{--    $('#importForm').submit(function (e) {--}}
        {{--        e.preventDefault();--}}

        {{--        let formData = new FormData(this);--}}

        {{--        Swal.fire({--}}
        {{--            title: 'Processing...',--}}
        {{--            text: 'Please wait while the file is being uploaded.',--}}
        {{--            allowOutsideClick: false,--}}
        {{--            showConfirmButton: false,--}}
        {{--            didOpen: () => {--}}
        {{--                Swal.showLoading();--}}
        {{--            }--}}
        {{--        });--}}

        {{--        $.ajax({--}}
        {{--            url: "{{ route('import.customerSpecialPrices') }}", // Create this route in your web.php--}}
        {{--            method: "POST",--}}
        {{--            data: formData,--}}
        {{--            contentType: false,--}}
        {{--            processData: false,--}}
        {{--            success: function (response) {--}}
        {{--                Swal.close();--}}
        {{--                if (response.success) {--}}
        {{--                    Swal.fire({--}}
        {{--                        icon: 'success',--}}
        {{--                        title: 'Import Successful!',--}}
        {{--                        text: response.message--}}
        {{--                    }).then(() => {--}}
        {{--                        // Refresh the page after the alert is closed--}}
        {{--                        location.reload();--}}
        {{--                    });--}}
        {{--                } else {--}}
        {{--                    Swal.fire({--}}
        {{--                        icon: 'error',--}}
        {{--                        title: 'Import Failed',--}}
        {{--                        text: response.message--}}
        {{--                    });--}}
        {{--                }--}}
        {{--            },--}}

        {{--            error: function (xhr) {--}}
        {{--                Swal.close();--}}
        {{--                let errors = xhr.responseJSON.errors;--}}
        {{--                let errorMessage = 'An error occurred.';--}}

        {{--                if (errors) {--}}
        {{--                    errorMessage = Object.values(errors).join(' ');--}}
        {{--                }--}}

        {{--                Swal.fire({--}}
        {{--                    icon: 'error',--}}
        {{--                    title: 'Error',--}}
        {{--                    text: errorMessage--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}





        $(document).ready(function () {
            $('#importForm').submit(function (e) {
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
                    url: "{{ route('import.customerSpecialPrices') }}", // Ensure this route is defined in web.php
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
    </script>
@endsection