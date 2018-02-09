<div class="how-it-works-container section-container section-container-image-bg" style="padding-bottom: 0px;">
    <div class="black-holder-3">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>{{translate('search')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row" style="margin-top: 1.5rem; margin-bottom: 0.8rem">
                <form class="form-inline col-xs-12" method="GET" action="{{url('/birja/search')}}">
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
                    <div class="col-md-1 col-sm-12 col-md-offset-1" style="padding-top: 30px;">
                        <button class="btn btn-link-1" type="submit" style="margin: 0px;line-height: 40px;height: auto;">{{translate('search')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>