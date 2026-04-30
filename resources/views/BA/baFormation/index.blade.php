@extends('layouts.default')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Premium Styling for Standard Selects */
        .custom-filter-input {
            border: 1px solid #d1dcec !important;
            border-radius: 8px !important;
            height: 45px !important;
            padding: 8px 12px !important;
            background: #f8fbff !important;
            color: #2d3436 !important;
            font-size: 14px !important;
        }

        .custom-filter-input:focus {
            border-color: #6c5ce7 !important;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1) !important;
            outline: none !important;
        }

        .form-label {
            font-weight: 600 !important;
            color: #2d3436 !important;
            margin-bottom: 8px !important;
            display: inline-block !important;
        }

        .modal-content {
            border-radius: 15px !important;
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .modal-header {
            background: #fdfdff !important;
            border-bottom: 1px solid #f1f4f8 !important;
            border-radius: 15px 15px 0 0 !important;
        }

        .modal-title {
            color: #2d3436 !important;
            font-weight: 700 !important;
        }

        .btn-primary {
            background: #6c5ce7 !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 25px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            background: #5b4bc4 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4) !important;
        }

        /* Select2 Multi-select tag styling */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #6c5ce7 !important;
            border: none !important;
            color: #fff !important;
            border-radius: 6px !important;
            padding: 4px 12px !important;
            font-size: 13px !important;
            margin-top: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
        }

        /* Hide any extra pseudo-elements that might cause double icons */
        .select2-container--default .select2-selection--multiple .select2-selection__choice::before,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove::before {
            content: "" !important;
            display: none !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
            border: none !important;
            margin-right: 8px !important;
            padding: 0 !important;
            font-weight: bold !important;
            font-size: 16px !important;
            background: transparent !important;
            position: static !important;
            order: -1; /* Ensure x is on the left */
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: transparent !important;
            color: #ff7675 !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1dcec !important;
            border-radius: 8px !important;
            min-height: 45px !important;
            padding-bottom: 6px !important;
        }
    </style>
    <div class="well_N">
        <div class="row align-items-center ">
            <div class="col-md-6">
                <h1>BA Formation</h1>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary mb-4" id="syncEmployeeBtn" onclick="syncEmployee()">
                    <span id="syncIcon" class="d-inline">
                        <i class="fas fa-sync-alt"></i> Sync Employee
                    </span>
                    <span id="syncLoader" style="display:none">
                        <i class="fas fa-spinner fa-spin"></i> Syncing...
                    </span>
                </button>
                <button type="button" class="btn btn-success mb-4" id="exportExcelBtn" onclick="exportExcel()"
                    style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button type="button" class="btn btn-danger mb-4" id="exportPdfBtn" onclick="exportPdf()"
                    style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import"></i> Import BA
                </button>
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Create
                </button>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="card mb-4 shadow-sm border-0"
            style="border-radius: 12px; border: 1px solid #e0e0e0 !important; background: #fff;">
            <div class="card-body p-4">
                <form id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Customers</label>
                                <select class="form-select custom-filter-input" name="filter_customer" id="filter_customer">
                                    <option value="">All Customers</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_customer_only_distributors() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Employee</label>
                                <select class="form-select custom-filter-input" name="filter_employee" id="filter_employee">
                                    <option value="">All Employees</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_employees() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Status</label>
                                <select class="form-select custom-filter-input" name="filter_status" id="filter_status">
                                    <option value="">All Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" onclick="applyFilters()"
                                style="height: 45px; border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-filter me-2"></i> Filter Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <style>
            .filter-label {
                font-weight: 600 !important;
                font-size: 14px !important;
                color: #2d3436 !important;
                margin-bottom: 8px !important;
                display: block !important;
                text-align: left !important;
            }

            .custom-filter-input {
                border-radius: 8px !important;
                height: 45px !important;
                border: 1px solid #d1dcec !important;
                width: 100% !important;
                display: block !important;
                background-color: #f8fbff !important;
            }

            .custom-filter-input:focus {
                border-color: #6c5ce7 !important;
                box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1) !important;
                outline: none;
            }

            .filter-group {
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
            }
        </style>

        <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create BA Formation</h5>
                    </div>
                    <div class="modal-body">
                        <form id="submitadv" action="{{route('baFormation.store')}}" method="POST"
                            class="baFormationForm underfieldvalidation">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                            <input type="hidden" id="listRefresh" value="{{route('list.baFormation')}}">
                            <div class="mb-3">
                                <label for="customers" class="form-label">Customers</label>
                                <select class="form-select select2 requiredv" id="customers" name="customer"
                                    style="width: 100%;" data-message="Customer">
                                    <option value="">Select Customers</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_customer_only_distributors() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="employee" class="form-label">Employee</label>
                                <select class="form-select select2 requiredv" id="employee" name="employee"
                                    style="width: 100%;" data-message="Employee">
                                    <option value="">Select Employee</option>
                                    @foreach(App\Helpers\SalesHelper::get_all_employees() as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="brands" class="form-label">Brands</label>
                                <select multiple class="form-select select2 requiredv" id="brands" name="brands[]"
                                    style="width: 100%;" data-message="Brands">
                                    @foreach(App\Helpers\CommonHelper::get_all_brand() as $item)
                                        <option value="{{$item->id}}">
                                            {{$item->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select select2 requiredv" name="status" id="status" style="width: 100%;"
                                    data-message="Status">
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


        <!-- Import Modal -->
        <div class="modal fade" id="importModal" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import BA Formations</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="importBAForm" action="{{ route('baFormation.import') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                            <div class="mb-3">
                                <label for="import_file" class="form-label">Select Excel/CSV File</label>
                                <input type="file" name="import_file" id="import_file" class="form-control" required>
                            </div>
                            <div class="alert alert-info py-2">
                                <small><strong>Note:</strong> Columns: Employee, Customer, Brands, <b>Status (Active/Inactive)</b>.</small>
                                <br>
                                <a href="{{ asset('public/samples/ba_formation_sample.csv') }}"
                                    class="text-primary font-weight-bold" download>
                                    <i class="fas fa-download mt-2"></i> Download Sample CSV
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="importSubmitBtn">
                                <span id="importIcon"><i class="fas fa-upload"></i> Import Data</span>
                                <span id="importLoader" style="display:none"><i class="fas fa-spinner fa-spin"></i>
                                    Processing...</span>
                            </button>
                        </div>
                    </form>
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
    <!-- DataTables Buttons for Export -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initial load with filterForm
            filterationCommonGlobal('{{route('list.baFormation')}}', false, 'filteredData', '#filterForm');
        });

        function applyFilters() {
            let filterData = $('#filterForm').serialize();
            filterationCommonGlobal('{{route('list.baFormation')}}?' + filterData, false, 'filteredData', '#filterForm');
        }

        function exportExcel() {
            $('#TableExportToCsv').DataTable().button('.buttons-excel').trigger();
        }

        function exportPdf() {
            $('#TableExportToCsv').DataTable().button('.buttons-pdf').trigger();
        }

        $(document).ready(function () {
            $('#importBAForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#importSubmitBtn').attr('disabled', true);
                $('#importIcon').hide();
                $('#importLoader').show();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#importSubmitBtn').attr('disabled', false);
                        $('#importIcon').show();
                        $('#importLoader').hide();

                        if (response.success) {
                            $('#importModal').modal('hide');
                            $('#importBAForm')[0].reset();
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                html: response.message,
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                applyFilters(); // Refresh the list only after user clicks OK
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: response.message,
                            });
                        }
                    },
                    error: function (xhr) {
                        $('#importSubmitBtn').attr('disabled', false);
                        $('#importIcon').show();
                        $('#importLoader').hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.',
                        });
                    }
                });
            });

            $('#submitadv').submit(function (e) {
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
                            $('.modal').modal('hide');
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                // Refresh the list with filterForm only after user clicks OK
                                filterationCommonGlobal($('#listRefresh').val(), false, 'filteredData', '#filterForm');
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

    <script>

        // $(document).ready(function() {
        //     $('#customers').select2({
        //         placeholder: 'Select Customers',
        //         allowClear: true
        //     });

        //     $('#brands').select2({
        //         placeholder: 'Select Brands',
        //         allowClear: true
        //     });

        //     $('#employee').select2({
        //         placeholder: 'Select Employee',
        //         allowClear: true
        //     });

        //     $('#status').select2({
        //         placeholder: 'Select Status',
        //         allowClear: true
        //     });
        // });
        function syncEmployee() {
            // Show loader and hide sync icon
            $('#syncIcon').hide();
            $('#syncLoader').show();

            // Make an AJAX request to the Laravel controller
            $.ajax({
                url: "{{ route('syncEmployee') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Handle success response
                    alert('Employee synced successfully!');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error('Error syncing employee:', error);
                    alert('Failed to sync employee.');
                },
                complete: function () {
                    // Hide loader and show sync icon
                    $('#syncIcon').show();
                    $('#syncLoader').hide();
                }
            });
        }
    </script>


    <script>
        $(document).ready(function () {

            // Initialize Select2 only when modal is shown
            $('#exampleModal').on('shown.bs.modal', function () {
                var $modal = $(this);

                // Customers
                $modal.find('#customers').select2({
                    width: '100%',
                    placeholder: 'Select Customers',
                    allowClear: true,
                    dropdownParent: $modal
                });

                // Employee
                $modal.find('#employee').select2({
                    width: '100%',
                    placeholder: 'Select Employee',
                    allowClear: true,
                    dropdownParent: $modal
                });

                // Brands (multi-select)
                $modal.find('#brands').select2({
                    width: '100%',
                    placeholder: 'Select Brands',
                    allowClear: true,
                    dropdownParent: $modal
                });

                // Status
                $modal.find('#status').select2({
                    width: '100%',
                    placeholder: 'Select Status',
                    allowClear: true,
                    dropdownParent: $modal
                });
            });

        });
    </script>

@endsection