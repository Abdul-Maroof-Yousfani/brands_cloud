@extends('layouts.default')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <div class="well_N">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-uppercase">
                    Document No : {{$voucher_no}}
                </h3>
                <form id="filterForm">
                    <input type="hidden" id="voucher_no" name="voucher_no" value="{{$voucher_no}}">
                    <input type="hidden" id="type" name="type" value="{{$type}}">
                    <div class="mb-3">
                        <label for="customers" class="form-label">Select Items</label>
                        <select class="form-select customers" id="product" name="product" style="width: 100%;">
                            <option value="">Select Item</option>
                            @foreach($voucherItem as $row)
                                <option value="{{$row->product_id}}">{{$row->product_name}}</option>
                            @endforeach
                            <!-- Add more customers as needed -->
                        </select>
                    </div>
                </form>


            </div>
        </div>


        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <h4>
                    Add Bardcode/QR Code
                </h4>
                <form id="yourFormID">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="voucher_no" value="{{$voucher_no}}">
                            <div class="mb-3">
                                <input class="form-control" type="text" name="barcode"  id="barcodeInput">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">

                                <button class="btn btn-dark" type="submit">Enter</button>
                            </div>
                        </div>
                    </div>


                </form>

            </div>
        </div>
        <div id="filteredData">

        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        $(document).ready(function () {
            filterationCommon('{{route('getBarcodeListAgainstProduct')}}');
        });


        $('#product').select2({
            placeholder: 'Select Item',
            allowClear: true
        });


        $(document).ready(function () {
            // Function to handle form submission
            function handleFormSubmission() {
                // Get the barcode and product ID values
                var newBarcode = $('#barcodeInput').val();

                var voucherItemQty = $('#voucherItemQty').val();
                if (!newBarcode) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please Enter Barcode First',
                        text: 'You must enter a barcode before submitting the form.',
                        confirmButtonColor: '#3085d6'
                    });
                    return; // Exit the function if newBarcode is empty
                }
                var product_id = $('#product').val(); // Retrieve the product_id value

                // Check if product_id is selected
                if (!product_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please Select Item First',
                        text: 'You must select a product before submitting the form.',
                        confirmButtonColor: '#3085d6'
                    });
                    return; // Exit the function if product_id is empty
                }


                if (existingBarcodes.length >= parseInt(voucherItemQty)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'All Barcodes Scanned',
                        text: 'All items for this voucher have already been scanned. No more barcodes are needed.',
                        confirmButtonColor: '#3085d6'
                    });
                    return; // Exit the function if all barcodes are scanned
                }


                // Check if the barcode already exists
                if (existingBarcodes.includes(newBarcode)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `The barcode "${newBarcode}" already exists!`,
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    // Show SweetAlert processing message
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Serialize the form data and add additional variables
                    var formData = $('#yourFormID').serialize();
                    formData += '&product_id=' + encodeURIComponent(product_id);
                    formData += '&voucher_no=' + encodeURIComponent($('#voucher_no').val());
                    formData += '&voucher_type=' + encodeURIComponent('{{$_GET['type']}}');

                    // AJAX POST request
                    $.ajax({
                        url: '{{route('stockBarcode.store')}}',
                        type: 'POST',
                        data: formData,
                        success: function (response) {
                            Swal.close(); // Close the processing SweetAlert

                            // Display success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Barcode submitted successfully!',
                                confirmButtonColor: '#3085d6'
                            });
                            filterationCommon('{{route('getBarcodeListAgainstProduct')}}');
                            // Optionally, reset form or update UI
                            $('#yourFormID')[0].reset();
                        },
                        error: function (xhr) {
                            Swal.close(); // Close the processing SweetAlert

                            // Display validation errors
                            if (xhr.status === 422) { // Laravel validation error code
                                var errors = xhr.responseJSON.errors;
                                var errorMessage = 'Please correct the following errors:\n\n';

                                $.each(errors, function (key, errorArray) {
                                    errorMessage += `- ${errorArray[0]}\n`;
                                });

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#3085d6'
                                });
                            } else {
                                // Handle other errors (e.g., server issues)
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: `An error occurred: ${xhr.status} ${xhr.statusText}`,
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        }
                    });
                }
            }

            // Handle form submission on submit button click
            $('#yourFormID').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                handleFormSubmission(); // Call form submission function
            });

            // Handle Enter key press on the barcode input field
            $('#barcodeInput').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent default form submission
                    handleFormSubmission(); // Call form submission function
                }
            });
        });

    </script>
@endsection