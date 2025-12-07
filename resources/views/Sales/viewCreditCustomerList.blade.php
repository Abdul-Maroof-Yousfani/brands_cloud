<?php
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')
@include('select2')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.js"></script>

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Customers</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Customer List</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <!-- <ul class="cus-ul2">
                 <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                 </li>
                 {{-- 
         <li>
            <input type="text" class="fomn1" placeholder="Search Anything" >
         </li>
         <li>
            <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
         </li>
         <li>
            <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
         </li>
         --}}
              </ul> -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row borderBtmMnd ">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">Customer List</h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="filterForm">
                                                <div class="row ">
                                                    <input type="hidden" name="m" value="<?php echo $_GET['m']; ?>">
                                                    <div class="col-md-1 mb-3">
                                                        <label for="customers" class="form-label">Show Entries</label>
                                                        <select name="per_page" class="form-control">
                                                            <option value="25">25</option>
                                                            <option value="50">50</option>
                                                            <option value="100">100</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-11 mb-3 ">
                                                        <div class="row justify-content-end text-right">
                                                            <div class="col-md-2 mb-3">
                                                                <label for="territory_id" class="form-label">Territory</label>
                                                                <select name="territory_id[]" id="territory_id" multiple class="form-control select2">
                                                                    <option disabled>Select Territory</option>
                                                                    @if($territories)
                                                                    @foreach($territories as $territory)
                                                                        <option value="{{ $territory->id }}">
                                                                            {{ $territory->name }}
                                                                        </option>
                                                                    @endforeach
                                                                    @endif

                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label for="customer_type" class="form-label">Customer Type</label>
                                                                <select name="customer_type[]" id="customer_type" multiple class="form-control select2">
                                                                    <option disabled>Select type</option>
                                                                    <option data-type="general" value="1">General</option>
                                                                    <option data-type="employee" value="2">Employee</option>
                                                                    <option data-type="reseller" value="3">Reseller/ Distributor</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label for="customers" class="form-label">Search</label>
                                                                <input type="text" class="form-control" id="search"
                                                                    placeholder="Search by Doc No or Customer"
                                                                    name="search" value="">
                                                            </div>
                                                            
                                                        </div>


    


                                                    </div>
                                                </div>
                                            </form>
                                            <form id="exportForm" method="GET" action="{{ route('exportCustomers') }}">
    <input type="hidden" name="m" value="{{ $m }}">
</form>

<button type="button" onclick="submitCustomerExport()" class="btn btn-success">
    <i class="fa fa-file-excel-o"></i> Export Excel
</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div id="filteredData">
                                                <div class="text-center spinnerparent">
                                                    <div class="loader" role="status"></div>
                                                </div>
                                            </div>
                                        </div>
                                     <div>

                                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <script>
                $(document).ready(function() {
                    filterationCommonGlobal('{{ route('viewCreditCustomerList') }}');
                    $('.select2').select2();
                });
            </script>
            <script>
                function CreateAccount(AccId, CustomerName, CustomerId) {
                    var acc_code = '';
                    var headOne = '<?php echo CommonHelper::get_account_name_by_code('1'); ?>';
                    var headTwo = '<?php echo CommonHelper::get_account_name_by_code('1'); ?>';


                    swal({
                        title: 'Select Account To Create Supplier',
                        input: 'select',
                        inputOptions: {
                            '1-2-1': headOne,
                            '1-2-2': headTwo,

                        },
                        inputPlaceholder: 'Select Account Head',
                        showCancelButton: true,
                        inputValidator: function(value) {
                            return new Promise(function(resolve, reject) {
                                if (value !== '') {
                                    acc_code = value;
                                    resolve()

                                } else {
                                    reject('You need to select account head :)')
                                }
                            })
                        }
                    }).then(function(result) {
                        $.ajax({
                            url: '<?php echo url('/'); ?>/sdc/createCustomerAccount',
                            type: "GET",
                            data: {
                                AccId: AccId,
                                CustomerName: CustomerName,
                                CustomerId: CustomerId,
                                value: acc_code
                            },
                            success: function(data) {
                                if (data == 'yes') {
                                    if (result == '1-2-1') {
                                        result = headOne;
                                    } else if (result == '1-2-2') {
                                        result = headThree;
                                    } else {
                                        result = headTwo;
                                    }
                                    swal({
                                        type: 'success',
                                        html: '<b>' + CustomerName + '</b>' + '<br>' +
                                            ' Account Create againts this ' + '<br>' + '<b>' + result +
                                            '</b>'
                                    });
                                    $('#Btn' + SupplierId).prop('disabled', true);
                                    $('#ShowHide' + SupplierId).html('Account Created');
                                }

                            }
                        });

                    });


                }

                /*   $(document).ready(function() {
                            function viewCreditCustomerList(){
                                $('#viewCreditCustomerList').html('<tr><td colspan="100"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
                                var m = '<?php echo $_GET['m']; ?>';
                                $.ajax({
                                    url: '<?php echo url('/'); ?>/sdc/viewCreditCustomerList',
                                    type: "GET",
                                    data:{m:m},
                                    success:function(data) {
                                        setTimeout(function(){
                                            $('#viewCreditCustomerList').html(data);
                                        },1000);
                                    }
                                });
                            }
                            viewCreditCustomerList();
                        });
                */
                function CustomerDelete(id) {

                    if (confirm('Are you sure you want to delete this request')) {
                        $.ajax({
                            url: '/sdc/customer_delete',
                            type: 'Get',
                            data: {
                                id: id
                            },

                            success: function(response) {
                                $('#' + response).remove();

                            }
                        });
                    } else {}
                }



         
            </script>
            </script>

<script>
    function submitCustomerExport() {
        const exportForm = document.getElementById('exportForm');
        const filterForm = document.getElementById('filterForm');

        if (!exportForm || !filterForm) {
            alert('Form not found in the page!');
            return;
        }

        // Remove old inputs
        Array.from(exportForm.querySelectorAll('.dynamic-field')).forEach(el => el.remove());

        // Add inputs from filterForm
        const formData = new FormData(filterForm);
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            input.classList.add('dynamic-field');
            exportForm.appendChild(input);
        }

        exportForm.submit();
    }
</script>



        @endsection
