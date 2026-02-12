<?php
$currentDate = date('Y-m-d');
$id = $_GET['id'];
$m 	= $_GET['m'];
$d 	= DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
$subDepartmentDetail = DB::selectOne('select * from `sub_department` where `id` = '.$id.'');
?>
	<div class="">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<?php echo Form::open(array('url' => 'had/editSubDepartmentDetail?m='.$m.'&&d='.$d.'','id'=>'subDepartmentForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" name="subDepartmentSection[]" class="form-control" id="subDepartmentSection" value="1" />
											</div>
										</div>
										<input type="hidden" name="sub_department_id_1" id="sub_department_id_1" value="<?php echo $subDepartmentDetail->id?>" class="form-control requiredField" />
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
												<label>Select Department:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<select class="form-control requiredField" name="department_id_1" id="department_id_1">
		                                    		<option value="">Select Department</option>
		                                    		@foreach($departments as $key => $y)
		                                    			<option value="{{ $y->id}}" {{ $subDepartmentDetail->department_id == $y->id ? 'selected="selected"' : '' }}>{{ $y->department_name}}</option>
		                                    		@endforeach
		                                    	</select>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Select Region:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<select class="form-control requiredField select2" name="territory_id_1" id="territory_id_1">
													<option value="">Select Region</option>
													@foreach($territories as $territory)
														<option value="{{ $territory->id }}" {{ $subDepartmentDetail->territory_id == $territory->id ? 'selected="selected"' : '' }}>{{ $territory->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="lineHeight">&nbsp;</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Salesman Name:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="sub_department_name_1" id="sub_department_name_1" value="{{$subDepartmentDetail->sub_department_name}}" class="form-control requiredField" />
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Designation:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="designation_1" id="designation_1" value="{{$subDepartmentDetail->designation}}" class="form-control requiredField" />
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Phone Number:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="phone_number_1" id="phone_number_1" value="{{$subDepartmentDetail->phone_number}}" class="form-control requiredField" />
											</div>
										</div>
						
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="subDepartmentSection"></div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
										{{ Form::submit('Update', ['class' => 'btn btn-success']) }}
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
									</div>
								</div>
							<?php echo Form::close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.select2').select2({
				placeholder: "Select",
				allowClear: true,
				width: '100%'
			});
		});

		$(".btn-success").click(function(e){
			var subDepartmentSection = new Array();
			var val;
			$("input[name='subDepartmentSection[]']").each(function(){
    			subDepartmentSection.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of subDepartmentSection) {

				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}

		});
	</script>
