@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="row" style="display: flex; align-items: center;">
            <div class="col-md-3">
                <label>Month</label>
                <input class="form-control" type="month" name="month" id="month">
            </div>
            <button style="
                margin-top: 23px;
            " class="btn btn-primary waves-effect waves-float waves-light" onclick="getCustomers()" type="button">Get</button>

                </div>
                <div id="table" style="margin-top: 20px;"></div>
        </div>

        <script>
            function getCustomers() {
                $.ajax({
                    url: '{{ route("get.customers") }}',
                    type: 'GET',
                    data: {
                        "month": $("#month").val()
                    },  
                    beforeSend: function () {

                        Swal.fire({
                            title: 'Loading...',
                            html: 'Please wait while we fetch the data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading(); // show spinner
                            }
                        });
                    },
                    success: function (response) {
                        // handle success
                        Swal.close();
                        $("#table").empty();
                        $("#table").append(response);
                    },
                    error: function (xhr, status, error) {
                        // handle error
                        console.error(xhr.responseText);
                    },
                    complete: function () {
                        // runs after success/error
                        console.log('Request finished');
                    }
                });
        
            }
           
        </script>
@endsection