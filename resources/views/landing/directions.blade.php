<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 18.03.18
 * Time: 18:21
 */
?>
<div class="how-it-works-container section-container section-container-image-bg cargo-container"  style="padding-bottom: 30px;background: #e3ddc1;">
    <div class="black-holder-0">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2 style="color: #333">{{translate('directions_title')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row" style="color:black;margin-top: 1.5rem; margin-bottom: 0.8rem">
                @foreach($directions as $k)
                    <div class="col-lg-3 col-xs-6 col-md-4">
                        @for($i=0;$i<count($k);$i++)
                            <a href="{{$k[$i]['link']}}"  style="display: block;" title="{{$k[$i]['title']}}">
                                <h3 style="color:#333;font-size: 1.6rem">{{$k[$i]['title']}}</h3>
                            </a>
                        @endfor
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>