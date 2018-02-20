@extends('layout.header')
@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;height:auto!important;">
        <div class="container">
            <div class="row">
                <div class="row align-items-center  text-center text-white">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="card"  style="padding: 3rem;background: white">
                            <h1  class="text-white">{{translate('settings')}}</h1>
                            @if ($warning = Session::get('warning'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$warning?>
                                </div>
                            @endif
                            @if ($success = Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$success?>
                                </div>
                            @endif
                            @if ($status = Session::get('status'))
                                <div class="message focus alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$status?>
                                </div>
                            @endif
                            @if ($danger = Session::get('danger'))
                                <div class="alert alert-danger focus alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$danger?>
                                </div>
                            @endif
                            @if ($errors->has('email'))
                                <div class="alert alert-danger alert-dismissible fade show " role="alert" style="opacity: 1;">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </div>
                            @endif
                            <form id="settings_form" role="form" method="POST" action="{{ route('settings.save',['lang'=>app()->getLocale()]) }}">
                                {{ csrf_field() }}

                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" placeholder="{{translate('enter_field').' Email'}}" value="{{$user->email}}" required autofocus>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="name" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('name'))}}" value="{{ $user->name}}" required>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="lastname" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('lastname'))}}" value="{{ $user->lastname }}" required>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon">+</span>
                                    <input type="number" class="form-control" name="phone" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('phone'))}}" value="{{ $user->phone }}" required>
                                </div>
                                <div class="add_phone">
                                        <div class="form-group input-group input-group-lg" @if (!$user->phone2) style="display: none" @endif>
                                            <span class="input-group-addon">+</span>
                                            <input type="number" class="form-control" name="phone2" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('phone'))}}" value="{{ $user->phone2 }}" required>
                                        </div>
                                        <div class="form-group input-group input-group-lg" @if (!$user->phone3) style="display: none" @endif>
                                            <span class="input-group-addon">+</span>
                                            <input type="number" class="form-control" name="phone3" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('phone'))}}" value="{{ $user->phone3 }}" required>
                                        </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <a href="#" class="add_phone_link" onclick="addPhoneSettings(this)" @if ($user->phone3 and mb_strlen($user->phone3)) style="display:none;" @endif>{{translate('add_phone')}}</a>
                                        <a href="#" class="dell_phone_link" onclick="dellPhoneSettings(this)" @if (!$user->phone2 or !mb_strlen($user->phone2)) style="display:none;" @endif>{{translate('dell_phone')}}</a>
                                    </div>
                                </div>
                                <button class="btn btn-link-2" style="width: 100%;display: block">{{ translate('save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center  text-center text-white">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="card"  style="padding: 3rem;background: white">
                            <h1  class="text-white">{{translate('change_password')}}</h1>
                            @if ($warning_pass = Session::get('warning_pass'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$warning_pass?>
                                </div>
                            @endif
                            @if ($success = Session::get('success_pass'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$success?>
                                </div>
                            @endif
                            @if ($status = Session::get('status_pass'))
                                <div class="message focus alert-dismissible fade show" role="alert" style="opacity: 1;">
                                    <?=$status?>
                                </div>
                            @endif
                            <form class="" role="form" method="POST" action="{{ route('settings.password',['lang'=>app()->getLocale()]) }}">
                                {{ csrf_field() }}


                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="{{translate('new_password')}}" required>
                                </div>

                                <button class="btn btn-link-2" style="width: 100%;display: block">{{ translate('save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        setTimeout(function () {
            $('.top-content').css('height','auto');
        },700);
    </script>
    @include("layout.background")
@endsection