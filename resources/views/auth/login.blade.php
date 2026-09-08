
	@include('includes._normalUserNavigation')
	<div class = "container">
		<div class="wrapper">
			<form action="{{ url('/login') }}" method="POST" class="form-signin">
				{{ csrf_field() }}
				@php
					$daysLeft = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse('2026-09-10')->startOfDay(), false);
					$daysLeft = $daysLeft < 0 ? 0 : $daysLeft;
				@endphp
				<div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
					<i class="fa fa-exclamation-circle"></i> Reminder: Your subscription expires in {{ $daysLeft }} days on Sep 10, 2026 at 11:59 PM. The system will be suspended automatically.
				</div>
				<h3 class="form-signin-heading">Welcome Back! Please Sign In</h3>
				<hr class="colorgraph"><br>
				<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
				@if ($errors->has('email'))
                	<span class="help-block">
                    	<strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
				<br />
                <input id="password" type="password" class="form-control" name="password">
				@if ($errors->has('password'))
					<span class="help-block">
						<strong>{{ $errors->first('password') }}</strong>
					</span>
				@endif
				<div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>

				<div id="company" class="company">
					<label>
						<select class="form-control">
							<?php $data=DB::Connection('mysql2')->table('company')->select('id','name')->get(); ?>

							@foreach($data as $row)

								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach

						</select> Remember Me
					</label>
				</div>



				<button type="submit" class="btn btn-primary">
                	<i class="fa fa-btn fa-sign-in"></i> Login
                </button>
				<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
            </form>		
		</div>
	</div>
</body>
</html>