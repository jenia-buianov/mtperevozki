<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Faby - Bootstrap Landing Page</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,700">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/media-queries.css')}}">
    <link rel="stylesheet" href="{{asset('aos/aos.css')}}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
    <script>
        var lang = "{{app()->getLocale()}}";
        @if(isset($page)&&$page=='home')
            var page = '{{$page}}';
        @endif
    </script>

</head>
<body>


    <nav class="navbar navbar-inverse navbar-fixed-top navbar-no-bg" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{url('/')}}">MT Perevozki</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-collapse collapse" id="top-navbar-1" aria-expanded="false" style="height: 0px;">
                <ul class="nav navbar-nav navbar-left">
                    <?php

                    foreach(App\Menu::where('parent',0)->orderBy('order')->get() as $k=>$v){
                        $link = explode('#',$v->link);
                        $count = App\Menu::where('parent',$v->id)->count();
                        echo '<li><a ';
                        if (count($link)) echo 'class="scroll-link"';
                        if ($count) echo 'class="dropdown-toggle" id="navbarDrop'.$v->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
                        echo ' href="'.url($v->link).'" title="'.trans($v->titleKey).'">'.trans($v->titleKey).'</a>';

                        if ($count) echo '<div class="dropdown-menu" aria-labelledby="navbarDrop'.$v->id.'" style="overflow: auto;padding: 10px;">';

                        foreach (App\Menu::where('parent',$v->id)->orderBy('order')->get() as $item=>$value){
                            echo '<a class="dropdown-item" title="'.trans($value->titleKey).'" href="'.url($value->link).'" style="font-weight:bold">'.trans($value->titleKey).'</a>';
                            foreach (App\Menu::where('parent',$value->id)->orderBy('order')->get() as $it=>$val){
                                echo '<a class="dropdown-item" title="'.trans($val->titleKey).'" href="'.url($val->link).'">'.trans($val->titleKey).'</a>';
                            }
                        }

                        if ($count) echo '</div>';
                        echo'</li>';
                    }

                    ?>
                    <li class="hidden-md hidden-sm"><a class="btn btn-link-3 scroll-link" data-toggle="modal" data-target="#transportFormModal" style="border-radius: 5px;
    margin: 5px 0 0 15px;
    padding: 10px;">{{translate('find_cargo')}}</a></li>
                    <li class="hidden-md hidden-sm"><a class="btn btn-link-3 scroll-link" data-toggle="modal" data-target="#formModal" style="border-radius: 5px;
    margin: 5px 0 0 15px;
    padding: 10px;">{{translate('find_cars')}}</a></li>

                        @if (!Auth::check())
                            <li><a class="btn btn-link-2" href="#" data-toggle="modal" data-target="#loginModal">{{translate('autorize')}}</a></li>
                        @else
                            <li>
                                <a class="dropdown-toggle btn btn-link-2" id="profileDrop" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">{{Auth::user()->name.' '.Auth::user()->lastname}}</a>
                                <div class="dropdown-menu" aria-labelledby="profileDrop" style="overflow: auto;">
                                    <a class="dropdown-item" href="{{url('/settings')}}"><i class="fa fa-cog"></i> {{translate('settings')}}</a>
                                    @if(Auth::user()->group->hasAccess('admin_access'))
                                        <a class="dropdown-item" href="{{url('/admin')}}"><i class="fas fa-unlock-alt"></i> {{translate('admin_panel')}}</a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{url('/logout')}}"><i class="fas fa-sign-out-alt"></i> {{translate('logout')}}</a>
                                </div>
                            </li>
                        @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/wow.min.js')}}"></script>
    <script src="{{asset('js/retina-1.1.0.min.js')}}"></script>
    <script src="{{asset('js/waypoints.min.js')}}"></script>
    <script src="{{asset('aos/aos.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
@if(isset($page)&&$page=='home')
@include("layout.background")
@endif

    <div class="how-it-works-container section-container section-container-image-bg cargo-container">
        <div class="black-holder-0">
            <div class="container">
                <?php
                    $menu = [];

                foreach(App\Menu::where('parent',0)->orderBy('order')->get() as $k=>$v){
                    if (!empty($v->link)){
                        if (count(explode('#',$v->link)))
                            $menu[] = ['link'=>url($v->link),'title'=>trans($v->titleKey)];
                        else $menu[] = ['link'=>url(app()->getLocale().'/'.$v->link),'title'=>trans($v->titleKey)];

                    }
                }

                foreach(App\Menu::where('parent',0)->orderBy('order')->get() as $k=>$v){
                    foreach (App\Menu::where('parent',$v->id)->orderBy('order')->get() as $item=>$value){
                        $menu[] = ['link'=>url($val->link),'title'=>trans($value->titleKey)];
                        foreach (App\Menu::where('parent',$value->id)->orderBy('order')->get() as $it=>$val){
                            $menu[] = ['link'=>url($val->link),'title'=>trans($val->titleKey)];
                        }
                    }
                }
                echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><div class="row">';
                foreach ($menu as $k=>$v){
                   echo '<a href="'.$v['link'].'" style="display: block">'.$v['title'].'</a>';
                    if ($k>0&&$k%10==0){
                        echo '</div></div><div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><div class="row">';
                    }
                }
                echo'</div></div>';
                ?>
            </div>
        </div>
    </div>

    @if (!Auth::check())
    <div class="modal fade modal-primary" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="card card-login card-plain">
                    <div class="modal-header justify-content-center">
                        <div style="text-align: right">
                            <i class="fa fa-times" data-dismiss="modal" aria-hidden="true"></i>
                        </div>
                        <div class="text-center" style="margin-top: 1.5rem;margin-bottom: 1.5rem;font-size: 7rem;">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form class="form" method="POST" action="{{url('/login')}}" style="margin-top: 2rem;">
                            <div class="card-body">
                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" placeholder="E-mail" value="" required="" autofocus>
                                </div>

                                <div class="form-group input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="{{translate('password')}}" required="">
                                </div>

                                <div class="text-center" style="margin-top: 2rem; margin-bottom: 2rem">
                                    <a href="{{route('password.request')}}">{{translate('forgot')}}</a>
                                </div>
                                <button class="btn btn-link-1" style="margin: 0px 5px 5px 0px;width: 100%;display: block">{{translate('autorize')}}</button>
                            </div>
                            {{csrf_field()}}
                        </form>
                    </div>
                    <div class="modal-footer text-center">
                        <a href="{{url('/register')}}" class="btn btn-lg btn-block" style="color: #deaa5c!important;">{{translate('registration')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    @include('forms.add_cargo')
    @include('forms.add_transport')
    <i class="fas fa-arrow-circle-up fa-2x" id="to-top" aria-hidden="true" style="display: inline;"></i>
    <script src='https://www.google.com/recaptcha/api.js'></script>

<script>
    $(document).ready(function () {
        $("#to-top").hide();
        $('[data-toggle="popover"]').popover({
            trigger:'focus'
        });

        if (window.screen.width>=1024) {
            if(typeof page!=='undefined') {
                top_padding = (window.screen.height - 875) / 2;
                $('.backround_').height(window.screen.height - 150);
                $('.top-content').height(window.screen.height - 390);
                $('.backround_').css('padding-top', top_padding + 'px');
            }
            else{
                top_padding = (window.screen.height - 675) / 2;
                $('.backround_').height(window.screen.height);
                $('.top-content').height(window.screen.height - 190);
                $('.backround_').css('padding-top', top_padding + 'px');
            }
        }

        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#to-top').fadeIn(500);
            } else {
                $('#to-top').fadeOut(700);
            }
        });

        $('#to-top').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

    AOS.init({duration: 1200,easing: 'ease-out-back',disable: "mobile"});
        if ($('.datepick').length){
            $('.datepick').datepicker({ format: 'dd-mm-yyyy',autoclose:true });
        }
        if (window.screen.width>=768) {
            $('.dropdown-menu').css('height',window.screen.height*0.3+'px');
            $('.dropdown-menu').css('width',window.screen.width*0.6+'px');
            $('.dropdown-menu').css('left',(window.screen.width*-0.3)+'px');
            $('[aria-labelledby="profileDrop"].dropdown-menu').css('height','auto');
            $('[aria-labelledby="profileDrop"].dropdown-menu').css('width','auto');
            $('[aria-labelledby="profileDrop"].dropdown-menu').css('left','auto');
        }
    });

</script>

</body>
</html>