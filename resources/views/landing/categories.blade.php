<div class="features-container categories section-container">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 features section-description wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
                <h2>{{translate('categories')}}</h2>
                <div class="divider-1"><div class="line"></div></div>
            </div>
        </div>
        @foreach($categories as $k=>$v)
            @if($k%3==0) <div class="row"> @endif
                <?php
                if ($k%3==0) $effect = 'fade-left';
                if ($k%3==1) $effect = 'fade-up';
                if ($k%3==2) $effect = 'fade-right';
                $offset = $k*50;
                ?>
                <div class="col-sm-4 features-box" data-aos="{{$effect}}" data-aos-delay="{{$offset}}">
                    <a class="features-box-icon" style="display: block;color:#666;font-weight: 600;cursor: pointer">
                        <img src="{{url('images/categories/'.$v->image)}}">
                        <div class="holder animated fadeInUpBig" style="font-size:2rem;">
                            Заказать
                        </div>
                    </a>
                    <h3><a href="{{url($v->link)}}">{{trans($v->titleKey)}}</a></h3>
                </div>
                @if($k%3==2) </div> @endif
        @endforeach
    </div>
</div>