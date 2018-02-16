@extends('layout.header')
@section('content')
<header class="header " style="background-image:url('{{ asset('assets/landing/img/header_bg.jpg')}} ') ">
	<div class="container">
	  <div class="row align-items-center  text-center text-white">
		<div class="col-12 ">
		  <h1  class="text-white">Login</h1>
		  <div class="section-dialog section-dialog-sm bg-transparent p-0">
				@if ($warning = Session::get('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="opacity: 1;">
                        <?=$warning?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span>×</span>
						</button>
                    </div>
                @endif
                @if ($success = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="opacity: 1;">
                        <?=$success?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span>×</span>
						</button>
                    </div>
                @endif
                @if ($status = Session::get('status'))
                    <div class="message focus alert-dismissible fade show" role="alert" style="opacity: 1;">
                        <?=$status?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span>×</span>
						</button>
                    </div>
                @endif
				@if ($errors->has('email'))
					<div class="alert alert-danger alert-dismissible fade show " role="alert" style="opacity: 1;">
						<strong>{{ $errors->first('email') }}</strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span>×</span>
						</button>
					</div>
				@endif
		  </div>
					<form class="section-dialog section-dialog-sm bg-gray py-40" role="form" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
							
						<div class="form-group input-group input-group-lg">
						  <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
						  <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
						</div>
						<div class="form-group input-group input-group-lg">
						  <span class="input-group-addon"><i class="fa fa-key"></i></span>
						  <input type="password" class="form-control" name="password" placeholder="{{ translate('password') }}" required>
						</div>
						<a class="" href="{{ route('password.request') }}">
                                   <small>{{ translate('forgot') }} </small>
						</a>
						<button class="mt-15 btn btn-block btn-lg btn-login">{{ translate('autorize') }}</button>
				  </form>
		</div>
	  </div>
	</div>
  </header>
@endsection