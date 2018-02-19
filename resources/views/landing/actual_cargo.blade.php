<div class="how-it-works-container section-container section-container-image-bg" style="padding-bottom: 30px;background: #f8f8f8;">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2 style="color:#555;">{{translate('actual_cargo')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                    <p class="medium-paragraph" style="color:#555;"> {!! translate('actual_p') !!}</p>
                </div>
            </div>
            <div class="row" style="color:#888;margin-top: 1.5rem; margin-bottom: 0.8rem">
                <form class="form-inline col-xs-12" method="GET" action="{{url('/birja/cargo/')}}">
                    <div class="col-md-4 col-sm-12 col-md-offset-1">
                        <label class="col-sm-12" for="inlineFormInput">
                            <div class="row">
                                {{translate('country_export')}}
                            </div>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" name="country_export"  style="width: 100%">
                                <option selected>{{translate('all_countries')}}</option>
                                @foreach($countries as $country=>$value)
                                    <option value="{{$value->id_country}}">{{$value->alpha3}} - {{$value->$country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label class="col-sm-12" for="inlineFormInput">
                            <div class="row">
                                {{translate('country_import')}}
                            </div>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" name="country_export"  style="width: 100%">
                                <option selected>{{translate('all_countries')}}</option>
                                @foreach($countries as $country=>$value)
                                    <option value="{{$value->id_country}}">{{$value->alpha3}} - {{$value->$country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-12" style="padding-top: 30px;">
                        <button class="btn btn-link-1" type="submit" style="margin: 0px;line-height: 40px;height: auto;">{{translate('search')}}</button>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-12 more-features-box" data-aos="fade-up">
                    <div class="table-responsive white-holder-10">
                        <table class="table table-hover" align="left">
                            <thead>
                            <tr>
                                <th>Откуда</th>
                                <th>Куда</th>
                                <th style="text-align: center">Тип транспорта</th>
                                <th style="text-align: center">Наименование груза</th>
                                <th style="text-align: center">Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($auto_cargo as $k=>$v)
                                <tr>
                                    <td align="left" width="20%">
                                        <img src="{{url('images/flags/flat/24/'.$v->export_flag().'.png')}}" width="24" height="24">
                                        <font class="hidden-lg hidden-md">{{$v->export_flag()}}</font>
                                        <font class="hidden-xs hidden-sm"><?=$v->export()?></font>
                                    </td>
                                    <td align="left" width="20%">
                                        <img src="{{url('images/flags/flat/24/'.$v->import_flag().'.png')}}" width="24" height="24">
                                        <font class="hidden-lg hidden-md">{{$v->import_flag()}}</font>
                                        <font class="hidden-xs hidden-sm"><?=$v->import()?></font>
                                    </td>
                                    <td width="30%">
                                        {{$v->transport_type()}}
                                    </td>
                                    <td width="15%">
                                        {{$v->name()}}
                                    </td>
                                    <td width="15%">
                                        {{date('d.m.Y',strtotime($v->date))}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{url($lang.'/birja/cargo/')}}" style="margin-top: 1.2rem;font-size:1.5rem;font-weight: 600;float: right">{{translate('all_cargo')}}</a>
                </div>
            </div>
    </div>
</div>