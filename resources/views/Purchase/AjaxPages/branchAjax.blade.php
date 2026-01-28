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
							<?php echo Form::open(array('url' => "/sales/branch/{$branch->id}/edit", 'id' => $branch->id));?>
                                {{ csrf_field() }}
                                {{ method_field("PUT") }}
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										
										<div class="lineHeight">&nbsp;</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Branch:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="branch_name" id="branch" value="{{ $branch->branch_name }}" class="form-control requiredField" />
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
