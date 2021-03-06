                <form class="form col-12" id="acargo_form" method="POST" action="{{url(app()->getLocale().'/sendForm')}}"  onsubmit="sendForm(this,event)" style="margin-top: 1rem;">
<div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('country_from')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="export" class="form-control"  onchange="setCity(this,event)">
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
                                        <select name="import" class="form-control"   onchange="setCity(this,event)">
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
                                        <label>{{translate('date')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <input type="text" class="datepick form-control" id="date" name="date_export" value="{{translate('enter_date_export')}}" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('cargo_name')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="cargo_type" class="form-control" onchange="cargoType(this)" >
                                            <option selected disabled>{{translate('select_cargo_name')}}</option>
                                            @foreach($cargo_type as $k=>$value)
                                                <option value="{{$value->id}}">{{$value->$cargo_type_name}}</option>
                                            @endforeach
                                            <option disabled>---------------</option>
                                            <option value="own">{{translate('own_cargo')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-12" id="cargo_name" style="display: none">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-5">
                                                <label>{{translate('enter_own_cargo')}}</label>
                                            </div>
                                            <div class="col-xs-12 col-md-7">
                                                <input type="text" class="form-control" name="own">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fouth block -->

                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('volume')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="volume" class="form-control" >
                                            <option selected disabled>{{translate('select_cargo_volume')}}</option>
                                            @foreach($cargo_volume as $country=>$value)
                                                <option value="{{$value->id}}">{{$value->$cargo_volume_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <label>{{translate('transport_type')}}</label>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <select name="transport_type" class="form-control" >
                                            <option selected disabled>{{translate('select_transport_type')}}</option>
                                            @foreach($transport_type as $country=>$value)
                                                <?php if ($group!==$value->transport_type_group) {
                                                    $group = $value->transport_type_group;
                                                    echo '<option value="" disabled>-------------------</option>';
                                                } ?>
                                                <option value="{{$value->id}}" @if(isset($_GET['transport'])&&$value->id==(int)$_GET['transport']) selected @endif>{{$value->$transport_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="col-xs-12 col-md-11">

                            <!-- Fith block -->
                            <div class="col-xs-12 col-md-6">
                                <div class="row">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-5">
                                            <label>{{translate('face')}}</label>
                                        </div>
                                        <div class="col-xs-12 col-md-7">
                                            <input type="text" name="face" class="form-control" value="@if(Auth::check()){{Auth::user()->name.' '.Auth::user()->lastname}}@endif" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-5">
                                            {{translate('company')}}
                                        </div>
                                        <div class="col-xs-12 col-md-7">
                                            <input type="text" name="company" class="form-control" placeholder="Компания повышает уровень доверия" value="@if(Auth::check()&&Auth::user()->companies&&count(Auth::user()->companies)==1){{Auth::user()->companies[0]->title}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-5">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-xs-12 col-md-7">
                                            <input data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{translate('will_be_send_on_this_email')}}" type="email" name="email" value="@if(Auth::check()){{Auth::user()->email}}@endif" class="form-control" >
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
                                            <input type="text" name="phone" class="form-control" value="@if(Auth::check()){{Auth::user()->phone}}@endif" >
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
                                                    <input type="text" name="phone1" class="form-control" value="{{Auth::user()->phone2}}" >
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
                                                    <input type="text" name="phone2" class="form-control" value="{{Auth::user()->phone3}}" >
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



                        {{csrf_field()}}

                        <div class="alert col-sm-12 alert-dismissible" role="alert" style="display: none">
                            <span style="text-align: center"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <div class="col-xs-12" style="margin-top: 10px;">
                        <div class="row">
                            <div class="col-md-6 col-xs-12" style="text-align: center">
                                {!! NoCaptcha::display() !!}
                                {{translate('set_captcha')}}<br>
                                <span style="font-size:1.1rem">Отправляя запрос, вы соглашаетесь с обработкой ваших личных данных</span>
                            </div>
                            <div class="col-md-6 col-xs-12" style="text-align: right">
                                <button class="btn btn-link-1" type="submit">{{translate('send_request')}}</button>
                            </div>
                        </div>
                    </div>
</div>
                </form>