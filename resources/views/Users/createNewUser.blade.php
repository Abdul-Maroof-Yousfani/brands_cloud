@extends('layouts.default')

@section('content')
<style>
    .report-card { border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: none; }
    .filter-section { background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; }
    .btn-generate { background: linear-gradient(135deg, #6c5ce7, #a29bfe); border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; color: white; transition: all 0.3s; }
    .btn-generate:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4); }
    
    .select2-container--default .select2-selection--multiple { 
        border-radius: 8px; 
        border: 1px solid #ddd; 
        padding: 5px 8px 1px 8px; 
        min-height: 42px; 
        height: auto !important; 
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #6c5ce7;
        border: none;
        color: white;
        border-radius: 4px;
        padding: 4px 10px;
        margin: 0px 6px 4px 0; 
        display: flex;
        align-items: center;
        font-size: 13px;
        line-height: 1.2;
    }
    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
        margin: 0 0 4px 0;
        height: 24px;
        line-height: 24px;
        font-size: 14px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 6px;
        font-weight: bold;
        text-decoration: none;
        font-size: 16px;
        line-height: 1;
        position: relative;
        top: -1px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ff7675;
        background: none;
    }
</style>

    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    	@if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                            
                            </div>
                        @endif
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create New User</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                echo Form::open(['url' => 'users/storeNewUser', 'id' => 'addMainMenuTitleForm']);
                                ?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Name</label>
                                    <input type="text" name="name" id="name" value=""
                                        class="form-control" />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Email</label>
                                    <input type="text" name="email" id="email" value=""
                                        class="form-control" />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Password</label>
                                    <input type="password" name="password" id="password" value=""
                                        class="form-control" />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        value="" class="form-control" />
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Acount Type</label>
                                    <select onchange="checkUserForCategory(this.value)" type="text" name="acc_type"
                                        id="acc_type" class="form-control" />
                                    <option value="client">Client</option>
                                    <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Role</label>
                                    <select type="text" name="role_id" id="role_id" class="form-control">
                                        <option value="">Select Role</option>

                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Territory</label>
                                <br>
                              <select name="territory_id[]" id="territory_id" multiple class="form-control select2" size="8">
                                    <option value="all">-- Select All --</option>
                                    @foreach ($territories as $territory)
                                        <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                                    @endforeach
                                </select>




                            </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 category">
                                    <label>Categories</label>
                                    <br>
                                    @foreach ($category as $key => $value)
                                        <div class="form-check">
                                            <input id="checkbox{{ $value->id }}" type="checkbox" checked
                                                name="category[]" value="{{ $value->id }}">
                                            <label for="checkbox{{ $value->id }}">{{ $value->main_ic }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 dashboard">
                                    <label>dashboard Access</label>
                                    <br>

                                    <div class="form-check">
                                        <input id="checkboxDash1" type="checkbox" name="dashboard_access[]"
                                            value="dashboard">
                                        <label for="checkboxDash1">DashBoard</label>
                                    </div>

                                    <div class="form-check">
                                        <input id="checkboxDash2" type="checkbox" name="dashboard_access[]"
                                            value="dashboard_production">
                                        <label for="checkboxDash2">Production Dashboard </label>
                                    </div>

                                    <div class="form-check">
                                        <input id="checkboxDash3" type="checkbox" name="dashboard_access[]"
                                            value="dashboard_management">
                                        <label for="checkboxDash3">Management Dashboard</label>
                                    </div>
                                </div>
                                <div>&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                </div>
                                <?php
                                echo Form::close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#territory_id').select2({
         
        });
    });
</script>

<script>
    function checkUserForCategory(value) {
        let checkboxes = document.querySelectorAll('input[type="checkbox"]');
        if (value == 'client') {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
        } else {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }
    }
</script>

@endsection
