<div class="how-it-works-container section-container" style="padding-bottom: 30px;    background: #f8f8f8;">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>{{translate('actual')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row" style="margin-top: 1.5rem; margin-bottom: 0.8rem">
                <form class="form-inline col-xs-12" method="GET" action="{{url('/birja/transport/')}}">
                    <div class="col-md-3 col-sm-12">
                        <label class="col-sm-12" for="inlineFormInput">
                            <div class="row">
                                {{translate('country_export')}}
                            </div>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" name="country_export"  style="width: 100%">
                                <option selected>{{translate('all_countries')}}</option>
                                @foreach($countries as $country=>$value)
                                    <? $title = 'country_name_'.app()->getLocale(); ?>
                                    <option value="{{$value->id_country}}">{{$value->alpha3}} - {{$value->$title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="col-sm-12" for="inlineFormInput">
                            <div class="row">
                                {{translate('country_import')}}
                            </div>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" name="country_export"  style="width: 100%">
                                <option selected>{{translate('all_countries')}}</option>
                                @foreach($countries as $country=>$value)
                                    <? $title = 'country_name_'.app()->getLocale(); ?>
                                    <option value="{{$value->id_country}}">{{$value->alpha3}} - {{$value->$title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="col-sm-12" for="inlineFormInput">
                            <div class="row">
                                {{translate('what_transport')}}
                            </div>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" name="type" style="width: 100%">
                                <option selected>-------</option>
                                @foreach($transport_type as $k=>$v)
                                    <? $title = 'transport_type_'.app()->getLocale(); ?>
                                    <option value="{{$v->id}}">{{$v->$title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-12 col-md-offset-1" style="padding-top: 30px;">
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
                                    <td align="left">
                                        <img src="{{url('images/flags/flat/24/'.$v->export_flag().'.png')}}" width="24" height="24">
                                        <?=$v->export()?>
                                    </td>
                                    <td align="left">
                                        <img src="{{url('images/flags/flat/24/'.$v->import_flag().'.png')}}" width="24" height="24">
                                        <?=$v->import()?>
                                    </td>
                                    <td>
                                        {{$v->transport_type()}}
                                    </td>
                                    <td>
                                        {{$v->volume()}}
                                    </td>
                                    <td>
                                        {{$v->date_from}}<br>
                                        {{$v->date_to}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{url('birja/transport/')}}" style="margin-top: 1.2rem;font-size:1.5rem;font-weight: 600;float: right">{{translate('all_transport')}}</a>
                </div>
            </div>
    </div>
</div>