@extends('layout.header')
@section('content')
	<div class="top-content" style="position: relative; z-index: 0; background: none;">
		<div class="container">
			<div class="row">
		<div class="row align-items-center  text-center text-white">
		<div class="col-md-4 col-md-offset-4">
			<div class="card"  style="padding: 3rem;background: white">
		  <h1  class="text-white">{{translate('autorize')}}</h1>
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
					<form class="" role="form" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
							
						<div class="form-group input-group input-group-lg">
						  <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
						  <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
						</div>
						<div class="form-group input-group input-group-lg">
						  <span class="input-group-addon"><i class="fa fa-key"></i></span>
						  <input type="password" class="form-control" name="password" placeholder="{{ translate('password') }}" required>
						</div>

						<a class="" href="{{ route('password.request') }}">
                                   <small>{{ translate('forgot') }} </small>
						</a>
						<button class="btn btn-link-2" style="width: 100%;display: block;">{{ translate('autorize') }}</button>
						<a class="" href="{{ route('register') }}" style="margin-top: 2rem">
							<small>{{ translate('registration') }} </small>
						</a>
				  </form>
			</div>
		</div>
		</div>
			</div>
	</div>
	</div>
@include("layout.background")
@endsection