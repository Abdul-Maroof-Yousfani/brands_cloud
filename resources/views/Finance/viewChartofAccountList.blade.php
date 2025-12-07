<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Helpers\ReuseableCode;


$edit=ReuseableCode::check_rights(146);
$delete=ReuseableCode::check_rights(147);
$export=ReuseableCode::check_rights(223);


$company=ReuseableCode::get_account_year_from_to(Session::get('run_company'));

$from_date = $company[0];
$to_date = $company[1];


?>
@extends('layouts.default')

@section('content')
@include('select2')
	<style>
		#myInput {

			background-position: 10px 10px;
			background-repeat: no-repeat;
			width: 100%;
			font-size: 16px;
			padding: 12px 20px 12px 40px;
			border: 1px solid #ddd;
			margin-bottom: 12px;
		}
	</style>

	<style>
        .modal-dialog.modal-dialog-centered {
            height: 100%;
            display: flex;
        }
        .modal-dialog.modal-dialog-centered .modal-content {
            margin: auto !important;
        }
        .modal-header {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
        }
    </style>

	<?php  $_SERVER['REMOTE_ADDR']; ?>
	<div class="well_N">
	<div class="dp_sdw">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
						@include('Finance.'.$accType.'financeMenu')
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well ">
						<div class='headquid'>
						<div class="row">
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<span class="subHeadingLabelClass">View Chart of Account</span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right">
								<?php echo CommonHelper::displayPrintButtonInBlade('printAccountList','','1');?>
								<?php if($export == true):?>
									<button type="button" class="btn btn-warning" onclick="tableToExcel('myTable', 'Chart of Account List')">Export to CSV</button>
								<?php endif;?>
							</div>
						</div>
						</div>

						
   <div class="row">
                        <div class="col-12">

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content w-100">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Import Chart of Account</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="importForm" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="file">Choose CSV File</label>
                                                    <input type="file" name="file" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer row">
                                                <div class="col-md-6 text-left">
                                                    <a target="_blank" href="{{asset('/public/chart_of_account.csv')}}" class="btn btn-dark">Download Sample File</a>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="button" id="importButton" class="btn btn-primary">Import</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>



    						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin-left: 76%;">
                                Import Chart of Account (.csv)
                            </button>
						
							<div class="panel">
								<div class="panel-body">
									<div class="row" id="printAccountList">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<form id="filterForm">
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
													<input type="hidden" name="get_m" value="{{ $m }}">
													<label>Search</label>
													<input type="text" name="search" placeholder="Search for names.." title="Type in a name" class="form-control">
													{{-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" class="hidden-print"> --}}
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
													<label>From Date</label>
													<input type="Date" name="fromDate" id="fromDate" max="" value="" class="form-control"/>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
													<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
													<input type="text" readonly value="Between" class="form-control text-center"/>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
													<label>To Date</label>
													<input type="Date" name="toDate" id="toDate" max="" value="" class="form-control"/>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
													<label>User </label>
													<select name="username[]" multiple class="form-control select2">
														<option disabled>Select User</option>
														@foreach($username as $item)
															<option value="{{ $item->username}}">{{ $item->username}}</option>
														@endforeach
													</select>
												</div>
											</form>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive" id="filteredData">
												<div class="text-center spinnerparent">
													<div class="loader" role="status"></div>
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
	</div>
	</div>


	<script>
		function delete_record(id)
		{

			if (confirm('Are you sure you want to delete this request')) {
				$.ajax({
					url: '/fd/deletechartofaccount',
					type: 'Get',
					data: {id: id},

					success: function (response) {

						
						$('#' + id).remove();

					}
				});
			}
			else{}
		}
	</script>
   <script>
		$(document).ready(function() {
			$('.select2').select2();
			filterationCommonGlobal('{{ route('getviewChartofAccountList') }}');
		});
	</script>
	<script>
		// function myFunction() {
		// 	var input, filter, table, tr, td, i, txtValue;
		// 	input = document.getElementById("myInput");
		// 	filter = input.value.toUpperCase();
		// 	table = document.getElementById("myTable");
		// 	tr = table.getElementsByTagName("tr");
		// 	for (i = 0; i < tr.length; i++) {
		// 		td = tr[i].getElementsByTagName("td")[2];
		// 		if (td) {
		// 			txtValue = td.textContent || td.innerText;
		// 			if (txtValue.toUpperCase().indexOf(filter) > -1) {
		// 				tr[i].style.display = "";
		// 			} else {
		// 				tr[i].style.display = "none";
		// 			}
		// 		}
		// 	}
		// }

		var tableToExcel = (function() {
			var uri = 'data:application/vnd.ms-excel;base64,'
					, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
					, base64 = function(s) {
						return window.btoa(unescape(encodeURIComponent(s))) }
					, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
			return function(table, name) {

				if (!table.nodeType)
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
					console.log(table);
				window.location.href = uri + base64(format(template, ctx))
			}
		})()

        function newTabOpen(FromDate,ToDate,AccCode)
        {

            var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
            window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode, '_blank');
        }
	</script>


<script>
        $(document).ready(function() {
            $('#importButton').on('click', function() {
                // Validate file input
                let fileInput = $('input[name="file"]')[0];
                if (fileInput.files.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please select a file to upload.'
                    });
                    return;
                }

                // Create FormData object
                let formData = new FormData($('#importForm')[0]);

                // Show loader
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while the file is being uploaded.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                $.ajax({
                    url: "{{ route('import.data') }}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Close the loader
                        Swal.close();

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data imported successfully.'
                        }).then(() => {
                            // Reload the page after the success message is confirmed
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Close the loader
                        Swal.close();

                        // Prepare error message
                        let errorMessage = 'An error occurred while importing the data.';
                        if (xhr.responseJSON) {
                            // If there are specific validation errors
                            if (xhr.responseJSON.errors) {
                                errorMessage = '';
                                $.each(xhr.responseJSON.errors, function(key, messages) {
                                    // Combine all error messages into a single string
                                    errorMessage += messages.join(' ') + ' ';
                                });
                            } else if (xhr.responseJSON.message) {
                                // Generic error message
                                errorMessage = xhr.responseJSON.message;
                            }
                        }

                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage.trim()
                        });
                    }
                });
            });
        });
    </script>


@endsection
