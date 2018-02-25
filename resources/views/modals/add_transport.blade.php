<div class="modal fade modal-primary" id="transportFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="border:12px solid #deaa5c">
        <div class="modal-content" style="    box-shadow: none;    border: transparent;">
            <div class="card card-plain">
                <form class="form" id="fmodalTransport" method="POST" action="{{url(app()->getLocale().'/sendTransportForm')}}"  onsubmit="sendForm(this,event)" style="margin-top: 1rem;">
                    <div class="modal-header justify-content-center">
                        <div style="text-align: right;float: right;padding-top: 1rem">
                            <i class="fa fa-times" data-dismiss="modal" aria-hidden="true"></i>
                        </div>
                        <div class="text-center" style="margin-top: 0rem;margin-bottom: 1.5rem;font-size: 3rem;float:left">
                            {{translate('adding_transport')}}
                        </div>
                    </div>
                    <div class="modal-body col-xs-12">
                        <div class="card-body">

                            <!-- First block -->

                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('country_from')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="export" class="form-control" required onchange="setCity(this,event)">
                                            <option value="" selected disabled>{{translate('select_country_from')}}</option>
                                            <option>{{translate('all_countries')}}</option>
                                            @foreach($countries as $country=>$value)
                                                <option value="{{$value->id_country}}">{{$value->$country_name}} [{{$value->alpha3}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('country_to')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="import" class="form-control" required  onchange="setCity(this,event)">
                                            <option value="" selected disabled>{{translate('select_country_to')}}</option>
                                            <option>{{translate('all_countries')}}</option>
                                            @foreach($countries as $country=>$value)
                                                <option value="{{$value->id_country}}">{{$value->$country_name}} [{{$value->alpha3}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Second block -->

                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        {{translate('city_from')}}
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="export_city" class="form-control" disabled="disabled">
                                            <option value="" selected disabled>{{translate('select_city_from')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        {{translate('city_to')}}
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="import_city" class="form-control"  disabled="disabled">
                                            <option value="" selected disabled>{{translate('select_city_to')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Thierd block -->
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('free_from')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <input type="text" class="datepick form-control" id="free_from" name="free_from" value="{{translate('enter_date_export')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('free_to')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <input type="text" class="datepick form-control" id="free_to" name="free_to" value="{{translate('enter_date_export')}}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Fouth block -->

                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('transport_type')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="transport_type" class="form-control" required>
                                            <option selected disabled>{{translate('select_transport_type')}}</option>
                                            @foreach($transport_type as $country=>$value)
                                                <option value="{{$value->id}}">{{$value->$transport_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('volume_transport')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="volume" class="form-control" required>
                                            <option selected disabled>{{translate('select_cargo_volume')}}</option>
                                            @foreach($cargo_volume as $country=>$value)
                                                <option value="{{$value->id}}">{{$value->$cargo_volume_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="col-xs-12  col-md-11">

                            <!-- Fith block -->
                            <div class="col-xs-12 col-md-6">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-5">
                                                <label>{{translate('face')}}</label>
                                            </div>
                                            <div class="col-xs-12 col-md-7">
                                                <input type="text" name="face" class="form-control" value="@if(Auth::check()){{Auth::user()->name.' '.Auth::user()->lastname}}@endif" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-5">
                                                {{translate('company')}}
                                            </div>
                                            <div class="col-xs-12 col-md-7">
                                                <input type="text" name="company" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-5">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-xs-12 col-md-7">
                                                <input data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{translate('will_be_send_on_this_email')}}" type="email" name="email" value="@if(Auth::check()){{Auth::user()->email}}@endif" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sixth block -->


                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('phone')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input type="text" name="phone" class="form-control" value="@if(Auth::check()){{Auth::user()->phone}}@endif" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="add_phone" style="@if(Auth::check()&&!empty(Auth::user()->phone2)) display: block; @else display:none @endif">

                                    @if(Auth::check()&&!empty(Auth::user()->phone2))
                                        <div class="row">
                                            <div class="col-xs-12 col-md-5">
                                                <label>{{translate('phone')}}</label>
                                            </div>
                                            <div class="col-xs-12 col-md-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon">+</span>
                                                    <input type="text" name="phone1" class="form-control" value="{{Auth::user()->phone2}}" required>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(Auth::check()&&!empty(Auth::user()->phone3))
                                        <div class="row">
                                            <div class="col-xs-12 col-md-5">
                                                <label>{{translate('phone')}}</label>
                                            </div>
                                            <div class="col-xs-12 col-md-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon">+</span>
                                                    <input type="text" name="phone2" class="form-control" value="{{Auth::user()->phone3}}" required>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <a href="#" class="add_phone_link" onclick="addPhone(this)">{{translate('add_phone')}}</a>
                                        <a href="#" class="dell_phone_link" onclick="dellPhone(this)" style="display: none">{{translate('dell_phone')}}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Seventh block -->

                        </div>
                        {{csrf_field()}}

                    </div>
                    <div class="modal-footer text-center">
                        <div class="alert col-sm-12 alert-dismissible" role="alert" style="display: none">
                            <span style="text-align: center"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-md-6 col-xs-12" style="text-align: center">
                            {!! NoCaptcha::display() !!}
                            {{translate('set_captcha')}}
                        </div>
                        <div class="col-md-6 col-xs-12" style="text-align: right">
                            <button class="btn btn-link-1" type="submit">{{translate('send_request')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>