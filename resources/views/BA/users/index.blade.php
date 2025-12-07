@extends('layouts.default')
@section('content')
    <style>
        .custom-modal {
            display: none; 
            position: fixed; 
            z-index: 1050; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background: rgba(0,0,0,0.6);
        }
        
        .custom-modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            box-shadow: 0 5px 15px rgba(0,0,0,.3);
            animation: fadeIn .3s ease;
        }
        
        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        
        .close-modal {
            font-size: 24px;
            cursor: pointer;
        }
        
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
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
                <button type="button" class="btn btn-primary mb-4" id="openModalBtn">
                   Create
                </button>
            </div>
        </div>
        <div id="customModal" class="custom-modal">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h5>Create BA Formation</h5>
                    <span class="close-modal">&times;</span>
                </div>
                <div class="custom-modal-body">
                    <form id="submitadv" action="{{route('baFormation.store')}}" method="POST">
                        <input type="hidden" value="{{csrf_token()}}" name="_token">
                        <input type="hidden" id="listRefresh" value="{{route('list.baFormation')}}">
        
                        <div class="mb-3">
                            <label for="customers" class="form-label">Customers</label>
                            <select class="form-select select2" id="customers69" name="customer" style="width: 100%;">
                                <option value="">Select Customers</option>
                                @foreach(App\Helpers\SalesHelper::get_all_customer_only_distributors() as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="mb-3">
                            <label for="employee" class="form-label">Employee</label>
                            <select class="form-select select2" id="employee69" name="employee" style="width: 100%;">
                                <option value="">Select Employee</option>
                                @foreach(App\Helpers\SalesHelper::get_all_employees() as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="mb-3">
                            <label for="brands" class="form-label">Brands</label>
                            <select multiple class="form-select select2" id="brands" name="brands[]" style="width: 100%;">
                                @foreach(App\Helpers\CommonHelper::get_all_brand() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select select2" name="status" id="status69" style="width: 100%;">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
        
                        <button style="margin-top: 10px" type="submit" class="btn btn-primary my-2">Create</button>
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

    <script>
        $(document).ready(function () {
            filterationCommonGlobal('{{route('list.baFormation')}}');
        });

        $("#openModalBtn").click(function () {
            $("#customModal").fadeIn();
        });
    
        $(".close-modal").click(function () {
            $("#customModal").fadeOut();
        });
    
        $(window).click(function (e) {
            if ($(e.target).is("#customModal")) {
                $("#customModal").fadeOut();
            }
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
        function syncEmployee() {
            $('#syncIcon').hide();
            $('#syncLoader').show();

            $.ajax({
                url: "{{ route('syncEmployee') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Employee synced successfully!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error syncing employee:', error);
                    alert('Failed to sync employee.');
                },
                complete: function() {
                    $('#syncIcon').show();
                    $('#syncLoader').hide();
                }
            });
        }
    </script>
@endsection