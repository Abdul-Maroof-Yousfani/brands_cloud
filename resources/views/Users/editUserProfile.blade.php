@extends('layouts.default')
@section('content')
<div class="panel-body well_N">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Edit User Profile</span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>


                <div class="panel">
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <!-- ============================
                                 PROFILE PICTURE UPLOAD SECTION
                                 ============================ -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h3 class="text-center">Profile Picture</h3>
                       
                                <div class="text-center">
                                    <img id="profile_preview" 
                                         src="{{ App\Helpers\CommonHelper::get_profile_pic() }}"
                                         class="img-thumbnail"
                                         style="width:150px;height:150px;border-radius:10px;">
                                </div>

                                <br>

                                <div class="text-center">
                                    <input type="file" id="profile_pic" accept="image/*" class="form-control" style="max-width:300px;margin:auto;">
                                </div>

                                <p class="text-center text-info" id="profile_msg" style="margin-top:10px;"></p>

                                <hr>
                            </div>
                            <!-- END PROFILE PICTURE SECTION -->

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::open(array('url' => 'uad/editUserPasswordDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <h3 class="text-center">Change Password</h3>
                                <label>Password</label>
                                <input class="form-control" name="password" id="password" value="" required onkeyup="checkPassword()">
                                <label>Confirm Password</label>
                                <input class="form-control" name="confirm_password" id="confirm_password" required onkeyup="checkPassword()" value="">
                                <span id="pass_message"></span>
                                <br>
                                <button type="submit" class="btn btn-success text-center">Update</button>
                                <?php echo Form::close();?>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hide">
                                <?php echo Form::open(array('url' => 'uad/editApprovalCodeDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="emr_no" value="<?=Auth::user()->emr_no?>">
                                <h3 class="text-center">Change Approval Code</h3>
                                <label>Code</label>
                                <input class="form-control" name="approval_code" id="approval_code" value="">
                                <br>
                                <button type="submit" class="btn btn-success text-center">Update</button>
                                <?php echo Form::close();?>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="lineHeight">&nbsp;</div>

            </div>
        </div>
    </div>
</div>

<script>
    <?php if(Auth::user()->password_status == 1):?>
        $(".hideforPassChange").html('');
    <?php endif;?>


    // =============================
    // PROFILE PIC AJAX UPLOAD
    // =============================
    $("#profile_pic").on("change", function(){

        let file = this.files[0];
        if(!file) return;

        // Show preview immediately
        let reader = new FileReader();
        reader.onload = function(e) {
            $("#profile_preview").attr("src", e.target.result);
        }
        reader.readAsDataURL(file);

        // Send via AJAX
        let formData = new FormData();
        formData.append("profile_pic", file);
        formData.append("_token", "{{ csrf_token() }}");

        $.ajax({
            url: "{{ route('update.profile-pic') }}", // route to create later
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            beforeSend: function(){
                $("#profile_msg").html("Uploading...");
            },

            success: function(res){
                $("#profile_msg").html("<span style='color:green;'>Profile picture updated!</span>");
            },

            error: function(){
                $("#profile_msg").html("<span style='color:red;'>Upload failed!</span>");
            }
        });
    });


    // =============================
    // PASSWORD MATCHING CHECK
    // =============================
    function checkPassword() {
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();

        if(confirm_password != '') {
            if(password != confirm_password) {
                $("#pass_message").html('<span style="color:red;">Password Don’t Match!</span>');
                $(".btn-success").attr("disabled","disabled");
            } else {
                $("#pass_message").html('<span style="color:green;">Password Matched!</span>');
                $(".btn-success").removeAttr("disabled");
            }
        }
    }
</script>
@endsection
