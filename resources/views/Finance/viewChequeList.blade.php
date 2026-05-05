<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;


?>

@extends('layouts.default')

@section('content')
	@include('select2')
	<div class="well_N">
		<div class="dp_sdw">
			<div class="panel">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="well">
								<div class='headquid'>

									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

												<span class="subHeadingLabelClass">Advance Cheque List</span>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
												<?php echo CommonHelper::displayPrintButtonInBlade('PrintPanel', '', '1');?>
												<a id="dlink" style="display:none;"></a>
												<button type="button" class="btn btn-warning"
													onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

											</div>
										</div>
									</div>

								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="form-group" style="margin-left: 15px;">
											<label style="margin-right: 20px;">
												<input type="radio" name="list_type" value="advance" checked onchange="viewChequeListAjax()"> Advance Cheque List
											</label>
											<label>
												<input type="radio" name="list_type" value="rv" onchange="viewChequeListAjax()"> Voucher Cheque List
											</label>
										</div>
									</div>
								</div>

								<div class="row">

									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
										<label>Customer</label>
										<select id="customer_id" class="form-control select2">
											<option value="">Select Customer</option>
											<?php foreach ($customers as $key => $val):?>
											<option value="<?php echo $val->id?>">
												<?php echo $val->name; ?>
											</option>
											<?php endforeach;?>
										</select>
									</div>

									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
										<input type="button" value="Get Data" class="btn btn-primary"
											onclick="viewChequeListAjax();" style="margin-top: 32px;" />
									</div>
								</div>

								<div class="lineHeight">&nbsp;</div>
								<div id="printBankPaymentVoucherList">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<?php // Form::open(array('url' => '/approvedPaymentVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
											<div class="panel">
												<div class="panel-body" id="PrintPanel">

													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="table-responsive">
																<h5 style="text-align: center" id="h3"></h5>
																<table class="userlittab table table-bordered sf-table-list"
																	id="TableExportToCsv1">
																	<thead>

																		<th class="text-center">S.No</th>
																		<th class="text-center">Customer Name</th>
																		<th class="text-center">Received Code</th>
																		<th class="text-center">Received Date</th>
																		<th class="text-center">Cheque No</th>
																		<th class="text-center">Cheque Date</th>

																		<th class="text-center issue_cols">Issue Code</th>
																		<th class="text-center issue_cols">Issue Date</th>
																		<th class="text-center">Amount</th>
																		<th class="text-center issue_cols">Remaining</th>
																		<th class="text-center issue_cols">Consumption Status</th>
																		<th class="text-center">Cheques Status</th>
																		<!-- <th class="text-center hidden-print">Action</th> -->
																	</thead>
																	<tbody id="data">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
													<div class="row">

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
	<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
	<script !src="">
		function ExportToExcel(type, fn, dl) {
			var elt = document.getElementById('TableExportToCsv1');
			var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
			return dl ?
				XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
				XLSX.writeFile(wb, fn || ('C.P.V <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.select2').select2();
			viewChequeListAjax();
		});

		function viewChequeListAjax() {
			$('#data').empty();
			let customer_id = $('#customer_id').val();
			let supplier_id = $('#supplier_id').val();
			let list_type = $('input[name="list_type"]:checked').val();

			$.ajax({
				url: 'viewChequeList',
				type: "GET",
				data: {
					customer_id,
					supplier_id,
					list_type
				},
				success: function (data) {
					$('#data').empty();
					$('#data').append(data);
					
					if (list_type == 'rv') {
						$('.issue_cols').hide();
					} else {
						$('.issue_cols').show();
					}
				}
			});
		}
		var previous_status = "";
		function setPrevStatus(obj) {
			previous_status = $(obj).val();
		}

		function changeStatus(obj, id, v_no = '', cheque_no = '') {
			let status = $(obj).val();
			let statusText = $(obj).find('option:selected').text();
			
			if (confirm('Are you sure you want to change status to "' + statusText + '"?')) {
				$.ajax({
					url: '{{ url("finance/updateChequeStatus") }}',
					type: "POST",
					data: {
						_token: '{{ csrf_token() }}',
						id: id,
						status: status,
						v_no: v_no,
						cheque_no: cheque_no
					},
					success: function (data) {
						if (data.success) {
							toastr.success(data.message);
							if (data.new_id) {
								// Update the row to use the newly created ID for future changes
								$(obj).attr('onchange', `changeStatus(this, ${data.new_id})`);
							}
						} else {
							toastr.error(data.message);
							$(obj).val(previous_status); // Revert on server error
						}
					}
				});
			} else {
				$(obj).val(previous_status); // Revert on cancel
			}
		}
	</script>
@endsection