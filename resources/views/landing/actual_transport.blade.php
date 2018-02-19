<div class="how-it-works-container section-container" style="padding-bottom: 30px;    ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>{{translate('actual')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                    <div class="medium-paragraph">{!! translate('actual_transport_p') !!}</div>
                </div>
            </div>
            <div class="row" style="margin-top: 1.5rem; margin-bottom: 0.8rem">
                <form class="form-inline col-xs-12" method="GET" action="{{url('/birja/transport/')}}">
                    <div class="col-md-4 col-sm-12  col-md-offset-1">
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
                                <th style="text-align: center">Объем/вес</th>
                                <th style="text-align: center">Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($auto_transport as $k=>$v)
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
                                        {{$v->volume()}}
                                    </td>
                                    <td width="15%">
                                        {{$v->date_from}}<br>
                                        {{$v->date_to}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8 col-xs-8" style="text-align: left;padding-top: 1.5rem">
                        Вы в поиске транспорта, ищете машину? Разместите ваш груз и получайте предложения
                    </div>
                    <div class="col-md-4 col-xs-12" style="text-align: right;">
                        <a class="btn btn-link-1 scroll-link" data-toggle="modal" data-target="#formModal">{{translate('order_cargo')}}</a>
                    </div>
                </div>
            </div>
    </div>
</div>