@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Quotation</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <ul class="cus-ul2">
                <li>
                    <a href="{{route('createSaleQuotation')}}" class="btn btn-primary" >Add Quotation</a>
                </li>
                <!--   {{-- <li>
                    <input type="text" class="fomn1" id="search" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>  -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">View Quotation List</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Quotation No</th>
                                                        <th class="text-center">Quotation Date</th>
                                                        <th class="text-center">Valid Up To</th>
                                                        <th class="text-center">Revision</th>
                                                        <th class="text-center">Customer/Prospect</th>
                                                        <th class="text-center">Sale Order Status</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="data"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            viewRangeWiseDataFilter();
        });
        function viewRangeWiseDataFilter()
        {
            var Filter=$('#search').val();
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/listSaleQuotation',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }
        function removeDraft(id)
        {
    
         $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/removeDraft',
                type: 'Get',
                data: {id:id},
                success: function (response) {
                  
            if (response.catchError) {
                $(".alert-danger").removeClass("hide");
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display', 'block');
                $(".print-error-msg").find("ul").append('<li>' + response.catchError + '</li>');

            }
            if ($.isEmptyObject(response.error)) {

                var successMessage = $('.alert-success');
                successMessage.removeClass('hide');
                successMessage.html(response.success);
                viewRangeWiseDataFilter();
             

            } else {

                printErrorMsg(response.error);
            }
        },
                error: function(xhr, status, error) {
                    // Handle errors here
                    $('.loader-container').hide();
                    console.log(error); // Log the error message for debugging

                    
                }
            });
        }
    </script>

@endsection