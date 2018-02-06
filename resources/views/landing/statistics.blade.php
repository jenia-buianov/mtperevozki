<div class="statistics-container section-container" style="padding-bottom: 0px;">
    <div class="white-holder-7">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>Статистика</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 more-features-box" data-aos="fade-up">
                    @foreach($statistics as $k=>$v)
                        <div class="row" style="padding-bottom: 30px;">
                            <div class="col-sm-6 col-xs-12">
                                <div class="how-it-works-number">{{$v['cargo']}}</div>
                                <h3>{{translate($k.'_cargo')}}</h3>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="how-it-works-number">{{$v['transport']}}</div>
                                <h3>{{translate($k.'_transport')}}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>