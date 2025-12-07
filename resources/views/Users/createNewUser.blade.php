@extends('layouts.default')

@section('content')
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

