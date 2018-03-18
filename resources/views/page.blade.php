@extends('layout.header')
@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">
                <div class="row align-items-center  text-center text-white">
                    <div class="col-xs-12">
                        <div class="card"  style="padding: 3rem;background: white;text-align: left">
                            <div style="font-size: 13px;margin-bottom: 10px;">
                                @for($i=0;$i<count($sitemap);$i++)
                                    <a href="{{$sitemap[$i]['url']}}">{{$sitemap[$i]['title']}}</a>
                                    @if($i!==count($sitemap)-1)
                                        &#8594;
                                    @endif
                                @endfor
                            </div>
                            <h1  class="text-white">{{$content->metatitle}}</h1>
                            {!! $content->content!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function () {
            $('.top-content').css('height','auto');
        },700);
    </script>
    @include("layout.background")
@endsection

@if(isset($yield))
    @foreach($yield as $k=>$v)
        @section($k)
            {{$v}}
        @endsection
    @endforeach
@endif