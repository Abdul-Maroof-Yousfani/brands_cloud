<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');


?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

@extends('layouts.default')
@section('content')
    <style>


        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }

        .fileContainer [type=file] {
            cursor: inherit;
            display: block;
            font-size: 999px;
            filter: alpha(opacity=0);
            min-height: 100%;
            min-width: 100%;
            opacity: 0;
            position: absolute;
            right: 0;
            text-align: right;
            top: 0;
        }

        .fileContainer [type=file] {
            cursor: pointer;
        }

        hr{border-top: 1px solid cadetblue}
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}
        .img-circle {width: 150px;
            height: 150px;
            border: 2px solid #ccc;
            padding: 4px;
            border-radius: 50%;
            margin-bottom: 32px;
            margin-top: -78px;
            z-index: 10000000;}

        .pointer {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit Employee Detail Form</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <?php echo Form::open(array('url' => 'had/editEmployeeDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data", "files" => true));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?=Input::get('m')?>">
               
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Employee Name</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="hidden" name="id" class="form-control requiredField" value="<?=$employee->id?>" />
                                
                                    <input type="text" class="form-control requiredField" placeholder="Employee Name" name="employee_name" id="employee_name_1" value="<?=$employee->name?>" />
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Employee Email</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" class="form-control requiredField" placeholder="Employee Name" name="employee_email" id="employee_name_1" value="<?=$employee->email?>" />
                                </div>
                                
                            </div>
                           

                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <span id="emp_warning" style="color:red;font-weight:bold;"></span>
                                        {{ Form::submit('Update', ['class' => 'btn btn-success btn_disable']) }}
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
    </div>

    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection

