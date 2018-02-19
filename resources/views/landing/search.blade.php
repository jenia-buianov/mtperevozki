<div class="how-it-works-container section-container section-container-image-bg cargo-container" id="cargo" style="padding-bottom: 30px;background: #e3ddc1;color: #666666 !important;">
    <div class="black-holder-0">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2 style="color: #333">{{translate('search_title')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                    <p class="medium-paragraph">
                        {!! translate('search_p') !!}
                    </p>
                </div>
            </div>
            <div class="row" style="margin-top: 1.5rem; margin-bottom: 0.8rem">
                <form class="form-inline col-xs-12" method="GET" action="{{url($lang.'/birja/search')}}">
                    <div class="col-md-3 col-sm-12">
                        <label class="col-sm-12" for="inlineFormInput">
                            <div class="row">
                                {{translate('what_find')}}
                            </div>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" name="type" style="width: 100%">
                                <option value="cargo" selected>{{translate('cargo')}}</option>
                                <option value="transport">{{translate('transport')}}</option>
                            </select>
                        </div>
                    </div>
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
                                    <option value="{{$value->id_country}}">{{$value->alpha3}} - {{$value->$country_name}}</option>
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
                                    <option value="{{$value->id_country}}">{{$value->alpha3}} - {{$value->$country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-12 col-md-offset-1" style="padding-top: 30px;">
                        <button class="btn btn-link-1" type="submit" style="margin: 0px;line-height: 40px;height: auto;">{{translate('search')}}</button>
                    </div>
                </form>
            </div>
            <div class="row" style="margin-top: 5rem">
                <div class="col-sm-4 col-xs-12">
                    <button class="btn btn-golden"  data-toggle="modal" data-target="#transportFormModal">{{translate('add_transport')}}</button>
                    <div style="margin-bottom: 3rem" class="visible-xs"></div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <button class="btn btn-golden"  data-toggle="modal" data-target="#formModal">{{translate('add_cargo')}}</button>
                    <div style="margin-bottom: 3rem" class="visible-xs"></div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <a class="btn btn-golden" href="{{url(app()->getLocale().'/birja')}}">{{translate('go_to_birja')}}</a>
                </div>

            </div>
        </div>
    </div>
</div>