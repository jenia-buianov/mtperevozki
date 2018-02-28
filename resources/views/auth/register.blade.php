@extends('layout.header')
@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">
                <div class="row align-items-center  text-center text-white">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="card"  style="padding: 3rem;background: white">
                            <h1  class="text-white">{{translate('registration')}}</h1>
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
                            @if ($errors->has('email'))
                                <div class="alert alert-danger alert-dismissible fade show " role="alert" style="opacity: 1;">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </div>
                            @endif
                            <form class="" role="form" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}

                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
                                    <input  data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{translate('will_be_send_reg_message')}}" type="email" class="form-control" name="email" placeholder="{{translate('enter_field').' Email'}}" value="{{ old('email') }}" required autofocus>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="name" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('name'))}}" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon">+</span>
                                    <input type="number" class="form-control" name="phone" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('phone'))}}" value="@if(empty(old('phone'))){{'+'}}@else{{ old('phone') }}@endif" required>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('password'))}}" required>
                                </div>
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fab fa-creative-commons"></i></span>
                                    <select class="form-control" name="type" required>
                                        <option selected>----Укажите тип-----</option>
                                        <option value="Грузовладелец">Грузовладелец </option>
                                        <option value="Автотранспортная компания">Автотранспортная компания</option>
                                        <option value="Железнодорожные перевозки">Железнодорожные перевозки</option>
                                        <option value="Авиа перевозки">Авиа перевозки</option>
                                        <option value="Морские перевозки">Морские перевозки</option>
                                        <option value="Экспресс доставка">Экспресс доставка </option>
                                        <option value="Пассажирские перевозки">Пассажирские перевозки</option>
                                        <option value="Экспедиторская компания">Экспедиторская компания</option>
                                    </select>
                                </div>
                                <div class="col-sm-12" style="text-align: center">
                                    {!! NoCaptcha::display() !!}
                                    {{translate('set_captcha')}}
                                </div>
                                <button class="btn btn-link-2" style="width: 100%;display: block">{{ translate('registration') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("layout.background")
@endsection