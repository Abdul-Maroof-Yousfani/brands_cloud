@extends('layouts.default')
@section('content')
<style>
    a.d-block {
        display: block;
        padding: 5px 10px;
    }
</style>
    <div class="well_N">
        <div class="row align-items-center ">
            <div class="col-md-6">
                <h1>List of Customer Discount</h1>
            </div>
            <div class="col-md-6 text-right">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Create Discount
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a   href="javascript:void(0);"  class="d-block mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Create Customer Discount
                        </a>
                        <a   href="javascript:void(0);"  class="d-block mb-4"  data-bs-toggle="modal" data-bs-target="#importDiscount">
                            Import  Brand Wise  Discount
                        </a>
                        <a  href="javascript:void(0);"  class="d-block mb-4"  data-bs-toggle="modal" data-bs-target="#customerDiscountBrandItemWiseImport">
                            Import  Brand Item Wise  Discount
                        </a>
                    </div>
                </div>
{{--                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">--}}
{{--                    Create Customer Discount--}}
{{--                </button>--}}
{{--                <div class="col-md-6 text-right">--}}
{{--                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#importDiscount">--}}
{{--                        Import  Brand Wise  Discount--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="col-md-6 text-right">--}}
{{--                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#customerDiscountBrandItemWiseImport">--}}
{{--                        Import  Brand Item Wise  Discount--}}
{{--                    </button>--}}
{{--                </div>--}}
            </div>
        </div>
        <!-- Button trigger modal -->

        <div class="modal fade" id="customerDiscountBrandItemWiseImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Brand's Item Wise Discount</h5>
                        {{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                    </div>
                    <div class="modal-body">
                        <form id="customerDiscountBrandItemWiseImport" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <!-- Customer Selection -->
                            <div class="mb-3">
                                <label for="customers" class="form-label">Customers</label>
                                <select class="form-select customers" id="customers" name="customers[]" multiple="multiple" style="width: 100%;">
                                    <option value="all">All Customers</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_customer() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
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
                                    <a href="{{asset('/public/brand_item_wise_discount_import.csv')}}" target="_blank" class="btn btn-primary" >Download Sample File</a>
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
        <div class="modal fade" id="importDiscount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Brand Wise Discount</h5>
                        {{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                    </div>
                    <div class="modal-body">
                        <form id="importForm" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <!-- Customer Selection -->
                            <div class="mb-3">
                                <label for="customers" class="form-label">Customers</label>
                                <select class="form-select customers" id="customers2" name="customers[]" multiple="multiple" style="width: 100%;">
                                    <option value="all">All Customers</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_customer() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
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
                                    <a href="{{asset('/public/brand_wise_discount_import.csv')}}" target="_blank" class="btn btn-primary" >Download Sample File</a>
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



        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Customer Discount</h5>
                        {{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                    </div>
                    <div class="modal-body">
                        <form id="discountForm">
                            <!-- Brand Selection -->
                            <div class="mb-3">
                                <label for="brands" class="form-label">Brands</label>
                                <select
                                        onChange="get_product_by_brand(this,1)"
                                        class="form-select" id="brands" name="brands"  style="width: 100%;">
                                    @foreach(App\Helpers\CommonHelper::get_all_brand() as $item)
                                        <option value="{{$item->id}}">
                                            {{$item->name}}
                                        </option>
                                    @endforeach
                                    <!-- Add more brands as needed -->
                                </select>
                            </div>

                            <!-- Product Selection -->
                            <div class="mb-3">
                                <label for="products" class="form-label">Products</label>
                                <select class="form-select" id="products" name="products" style="width: 100%;">
{{--                                    <option value="">Select Product</option>--}}
                                </select>
                            </div>

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

                            <!-- Discount Percentage Input -->
                            <div class="mb-3">
                                <label for="discount" class="form-label">Discount Percentage</label>
                                <input type="number" class="form-control" id="discount" name="discount" placeholder="Enter discount percentage">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Apply Discount</button>
                        </form>
                    </div>
                    {{--                    <div class="modal-footer">--}}
                    {{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
                    {{--                        <button type="button" class="btn btn-primary">Save changes</button>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>
        <table class="table table-bordered sf-table-list">
            <thead >
            <tr class="text-center">
                <th class="text-center">Customer</th>
                <th class="text-center">Product Name</th>
                <th class="text-center">Discount (%)</th>
                <th class="text-center">Creation Date</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($CustomerSpecialPrice as $key => $csp)
                <tr class="text-center">
                    {{--                    <td>{{ ++$key }}</td>--}}
                    <td>{{ $csp->customer_name }}</td>
                    <td>{{ App\Helpers\CommonHelper::get_product_name($csp->product_id) }}</td>
                    <td>{{ $csp->discount_percentage }}%</td>
                    <td>{{ \Carbon\Carbon::parse($csp->created_at)->format('m/d/Y') }}</td>
                    <td><a href="{{ route('specialPrice.edit', $csp->id) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>


        $(document).ready(function () {
            $('#customerDiscountBrandItemWiseImport, #importForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let url = $(this).attr('id') === 'customerDiscountBrandItemWiseImport'
                    ? "{{ route('customerDiscountBrandItemWiseImport') }}"
                    : "{{ route('customerDiscountBrandWiseImport') }}";

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
                    url: url,
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
                            errorMessage = Object.values(errors).map(error => error.join(' ')).join(' ');
                        } else if (xhr.responseJSON?.message) {
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



        $(document).ready(function () {
            $('#importFordddm').submit(function (e) {
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
                    url: "{{ route('import.customerSpecialPrices') }}", // Create this route in your web.php
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
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = 'An error occurred.';

                        if (errors) {
                            errorMessage = Object.values(errors).join(' ');
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

        function get_product_by_brand(element, number) {
            var value = element.value;
            $('#products').empty();
            $.ajax({
                url: '{{ url("/getSubItemByBrand") }}',
                type: 'Get',
                data: {
                    id: value
                },
                success: function(data) {
                    $('#products').append(data);
                }
            });
        }



        $(document).ready(function () {
            $('#discountForm').submit(function (e) {
                e.preventDefault();

                let formData = $(this).serialize();

                // Show processing Swal loader
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we apply the discount',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{route('customerDiscount.store')}}', // Adjust the URL to your route
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        // Close the loader and show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            // Refresh the page after the alert is closed
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        // Handle validation errors (422 Unprocessable Entity)
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = 'Please fix the following errors:';
                            $.each(errors, function (key, value) {
                                errorMessage += '\n' + value;
                            });

                            // Show validation error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMessage,
                            });

                            // Handle 404 Not Found errors
                        } else if (xhr.status === 404) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error 404',
                                text: 'The requested resource was not found.',
                            });

                            // Handle 500 Internal Server Error
                        } else if (xhr.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'An internal server error occurred. Please try again later.',
                            });

                            // Handle other errors (fallback for other status codes)
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: `An unexpected error occurred (Error code: ${xhr.status}). Please try again later.`,
                            });
                        }
                    },
                    complete: function () {
                        // Always close the loader if there's success or error
                        Swal.close();
                    }
                });
            });
        });



    </script>

    <script>
        // Initialize Select2 on all dropdowns
        $('#brands').select2({
            placeholder: 'Select Brands',
            allowClear: true
        });
        $('#products').({
            placeholder: 'Select Products',
            allowClear: true
        });
        $('#customers').select2({
            placeholder: 'Select Customers',
            allowClear: true
        });
        $('#customers2').select2({
            placeholder: 'Select Customers',
            allowClear: true
        });
    </script>
@endsection