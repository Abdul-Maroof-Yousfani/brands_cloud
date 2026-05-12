<body class="mainb" style="background-image:url({{ url('assets/img/banner1.png') }});">
@include('includes._normalUserNavigation')
<style>
    .mainb {
        background-repeat: no-repeat;
        background-size: cover;
    }
    .marquee {
        width: 100%;
        position: absolute;
        text-align: center;
        animation: marquee 2000ms linear infinite;
    }

    @keyframes marquee {
        0% { top: 100%; }
        100% { top: -30px; }
    }
    .navbar-login {
        display: none;
    }
    .padb {padding-bottom: 37px;}
</style>
<section class="login-sec">
    <img class="circle" src="{{ url('assets/img/animation/circledot.png') }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center padb">
                <img src="{{ url('assets/img/white-logo.png') }}">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="login-fwrp p1">
                    <form action="{{ route('reseller.login.submit') }}" method="POST" class="form-signin" style="opacity: 0.9;">
                        {{ csrf_field() }}
                        <div class="text-center">
                            <h1>Hello! <br> Welcome Back</h1>
                            <p style="color: #666;">Reseller Portal</p>
                        </div>
                        
                        @if($errors->any())
                            <div class="alert alert-danger" style="margin-top: 10px;">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <label for="">Email</label>
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-user"></i>
                            <input id="email" type="email" class="form-control singInput" name="email" value="{{ old('email') }}" placeholder="Reseller email" required />
                        </div>
                        
                        <label for="">Password</label>
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                            <input id="password" type="password" class="form-control singInput" name="password" placeholder="Password" required />
                        </div>
                        
                        <div id="remember" class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me                            
                            </label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn login-btn" onclick="loader()">LOGIN <i class="fa fa-btn fa-sign-in"></i></button>
                            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function loader()
    {
        var div = document.getElementById('colorgraph');
        if (div) {
            div.innerHTML = '';
            div.innerHTML += '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>';
        }
    }
</script>
</body>