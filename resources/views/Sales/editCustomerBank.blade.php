<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">    
            <div class="panel">
                <div class="panel-body">
                    <div class="headquid">
						<h2 class="subHeadingLabelClass">Edit Customer Bank</h2>
					</div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'sales/updateCustomerBank/'.$bank->id.'?m='.$m.''));?>
                            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?? '' ?>">
                            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?? '' ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="meinti">
                                    <ul>
                                        <li>
                                            <div class="sellab">
                                                <lable>Bank Name</lable>
                                                <input type="text" name="bank_name" value="{{ $bank->bank_name }}" required id="bank_name" class="form-control requiredField" />
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <div class="btus addblatebut">
                                    {{ Form::submit('Update', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) }}
                                </div>
                            </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
