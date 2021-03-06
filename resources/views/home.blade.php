@extends('layout.header')

@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">

                <div class="col-sm-5 c-form-1-box">
                    &nbsp
                </div>
                <div class="col-sm-12 text" data-aos="fade-up" data-aos-delay="300">
                    <h1 style="    color: #333;">{{translate('main_logo')}}</h1>
                    <div class="description">
                        <p class="medium-paragraph" style="color:black">{{translate('main_logo_small')}}</p>
                        <h3 style="color:black">{{translate('main_logo_h3')}}</h3>
                    </div>
                    <div style="margin-bottom: 250px;">
                        <button class="btn btn-link-2"  data-toggle="modal" data-target="#formModal">{{translate('find_price_cargo')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="padding-bottom: 30px;">
        <div class="container">
            <h3 style="text-align: center">
                {{translate('main_h3')}}
            </h3>
        </div>
    </div>

    @foreach($landing as $k=>$v)
        @include("landing.$v->view")
    @endforeach

@endsection