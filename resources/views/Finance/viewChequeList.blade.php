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
										
										<span class="subHeadingLabelClass">Cheque List</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
										<?php echo CommonHelper::displayPrintButtonInBlade('PrintPanel','','1');?>
											<a id="dlink" style="display:none;"></a>
											<button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

									</div>
								</div>
							</div>
						
							</div>

							<div class="row">

								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<label>Customer</label>
									<select id="customer_id" class="form-control select2">
										<option value="">Select Customer</option>
										<?php foreach($customers as $key => $val):?>
											<option value="<?php echo $val->id?>">
												<?php echo $val->name; ?>
											</option>
										<?php endforeach;?>
									</select>
								</div>
								
								<!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<label>supplier</label>
									<select id="supplier_id" class="form-control select2">
										<option value="">Select supplier</option>
										<?php foreach($supplier as $key => $val):?>
											<option value="<?php echo $val->id?>">
												<?php echo $val->name; ?>
											</option>
										<?php endforeach;?>
									</select>
								</div> -->

								<!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<label>Voucher Status</label>
									<select id="issued" class="form-control select2">
										<option value="">Select issued Status</option>
										<option value="0">Cheque In Hand</option>
										<option value="1">Issued</option>
										<option value="2">Return</option>
									</select>
								</div> -->
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
									<input type="button" value="Get Data" class="btn btn-primary" onclick="viewChequeListAjax();" style="margin-top: 32px;" />
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
															<table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv1">
																<thead>

																<th class="text-center">S.No</th>
																<th class="text-center">Customer Name</th>
																<th class="text-center">Received Code</th>
																<th class="text-center">Received Date</th>
																<th class="text-center">Cheque No</th>
																<th class="text-center">Cheque Date</th>


																<!-- <th class="text-center">Supplier Name</th> -->
																<th class="text-center">Issue Code</th>
																<th class="text-center">Issue Date</th>
																<th class="text-center">Amount</th>
																<th class="text-center">Issue Status</th>
																<th class="text-center hidden-print">Action</th>
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
		$(document).ready(function(){
			$('.select2').select2();
			viewChequeListAjax();
		});

		function viewChequeListAjax()
		{
			$('#data').empty();
			let customer_id = $('#customer_id').val();
			let supplier_id = $('#supplier_id').val();
			let issued = $('#issued').val();
			
			$.ajax({
				url: 'viewChequeList',
				type: "GET",
				data: {
						customer_id,
						supplier_id,
						issued
				},
				success:function(data) {
					$('#data').empty();
					$('#data').append(data)
				}
			});


		}
	</script>
@endsection
