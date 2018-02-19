<div class="features-container section-container" style="background: white;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 features section-description wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
                <h2>{{translate('order_cars')}}</h2>
                <div class="divider-1"><div class="line"></div></div>
                <p class="medium-paragraph">{!! translate('cargo_p') !!}</p>
            </div>
        </div>
        @foreach($cargo as $k=>$v)
            @if($k%3==0) <div class="row"> @endif
                <?php
                if ($k%3==0) $effect = 'fade-left';
                if ($k%3==1) $effect = 'fade-up';
                if ($k%3==2) $effect = 'fade-right';
                $offset = $k*50;
                ?>
                <div class="col-sm-4 features-box">
                    <div class="features-box-icon">
                        <img src="{{url('images/types/'.$v->image)}}">
                    </div>
                    <h3><a href="{{url($v->link)}}">{{trans($v->titleKey)}}</a></h3>
                </div>
                @if($k%3==2) </div> @endif
        @endforeach
    </div>
    <div class="container">
        <div class="row">
                <a class="btn btn-link-1 scroll-link" data-toggle="modal" data-target="#formModal">{{translate('order_cargo')}}</a>
        </div>
    </div>
</div>