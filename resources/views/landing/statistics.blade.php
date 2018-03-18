<div class="statistics-container section-container" style="padding-bottom: 0px;">
    <div class="white-holder-7">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>{{translate('statistic')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                    <p class="medium-paragraph">{!! translate('statistic_p') !!}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 more-features-box" data-aos="fade-up">
                    <div class="row">
                    @foreach($statistics['cargo'] as $v=>$i)
                            <a href="{{route('birja.cargo',['lang'=>$lang,'tr'=>$v])}}" class="col-sm-2 col-xs-12" style="color:#333">
                                <div class="img_class">
                                @if(count($i)>1)
                                    <img src="{{$i[0]}}">
                                    <div class="number animated fadeInUpBig">{{$i[1]}}</div>
                                @else
                                    <div class="how-it-works-number">{{$i[0]}}</div>
                                @endif
                                </div>
                                <h3>{{translate($v.'_cargo')}}</h3>
                            </a>
                    @endforeach
                    </div>

                    <div class="row" style="padding-bottom: 30px;">
                        @foreach($statistics['transport'] as $v=>$i)
                            <a href="{{route('birja.transport',['lang'=>$lang,'tr'=>$v])}}" class="col-sm-2 col-xs-12" style="color:#333">
                                <div class="img_class">
                                @if(count($i)>1)
                                    <img src="{{$i[0]}}">
                                    <div class="number animated fadeInUpBig">{{$i[1]}}</div>
                                @else
                                    <div class="how-it-works-number">{{$i[0]}}</div>
                                @endif
                                </div>
                                <h3>{{translate($v.'_transport')}}</h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>