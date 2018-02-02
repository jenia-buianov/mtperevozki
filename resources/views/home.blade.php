@extends('layout.header')

@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">

                <div class="col-sm-5 c-form-1-box">
                    &nbsp
                </div>
                <div class="col-sm-12 text" data-aos="fade-up" data-aos-delay="300">
                    <h1 style="    color: #333;">{{translate('main_logo')}}</h1>
                    <div class="description">
                        <p class="medium-paragraph" style="    color: #333;">Найдём перевозчика
                            для доставки любого груза</p>
                    </div>
                    <div style="margin-bottom: 250px;">
                        <button class="btn btn-link-2">Узнать стоимость перевозки</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="features-container section-container" style="background: white;">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 features section-description wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
                        <h2>{{translate('order_cars')}}</h2>
                        <div class="divider-1"><div class="line"></div></div>
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
                        <div class="col-sm-4 features-box"  data-aos="{{$effect}}" data-aos-delay="{{$offset}}">
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
                        <div data-aos="fade-up">
                            <a class="btn btn-link-1 scroll-link" href="#top-content">Contact Us <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>


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
                        <div class="features-box-icon">
                            <img src="{{url('images/categories/'.$v->image)}}">
                            <div class="holder">
                                <a href="{{url('/'.$v->link)}}" class="btn btn-link-1" style="color:white">Заказать</a>
                            </div>
                        </div>
                        <h3><a href="{{url($v->link)}}">{{trans($v->titleKey)}}</a></h3>
                    </div>
                    @if($k%3==2) </div> @endif
            @endforeach
        </div>
    </div>

    <div class="more-features-container section-container" style="background: white">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>{{translate('why_we')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-7 more-features-box" data-aos="fade-left">
                    <div class="more-features-box-text">
                        <div class="more-features-box-text-icon"><i class="fa fa-clock-o"></i></div>
                        <h3>Экономия времени</h3>
                        <div class="more-features-box-text-description">
                            Не надо звонить и вести долгие переговоры
                        </div>
                    </div>
                    <div class="more-features-box-text">
                        <div class="more-features-box-text-icon"><i class="fa fa-registered"></i></div>
                        <h3>Надежные перевозчики</h3>
                        <div class="more-features-box-text-description">
                            Мы проверяем документы и выбираем лучших для сотрудничества
                        </div>
                    </div>
                    <div class="more-features-box-text">
                        <div class="more-features-box-text-icon"><i class="fa fa-check-circle"></i></div>
                        <h3>Страхование грузов</h3>
                        <div class="more-features-box-text-description">
                            Застрахуйте грузоперевозку с помощью одного из партнёров «Везёт Всем»
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 more-features-box" data-aos="fade-right">
                    <iframe width="100%" height="300px" src="https://www.youtube.com/embed/G9ZbswiGUrQ" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

        <!-- MODAL: Terms and Conditions -->
        <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <h2 class="modal-title" id="modal-terms-label">Terms and Conditions</h2>
                    </div>
                    <div class="modal-body">
                        <p>Please read carefully the terms and conditions for using our product below:</p>
                        <h3>1. Dolor sit amet</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                        </p>
                        <ul>
                            <li>Easy To Use</li>
                            <li>Awesome Design</li>
                            <li>Cloud Based</li>
                        </ul>
                        <p>
                            Ut wisi enim ad minim veniam, <a href="http://azmind.com/premium/faby/v1-2/layout-3/index.html#">quis nostrud exerci tation</a> ullamcorper suscipit lobortis nisl ut aliquip ex ea
                            commodo consequat nostrud tation.
                        </p>
                        <h3>2. Sed do eiusmod</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                        </p>
                        <h3>3. Nostrud exerci tation</h3>
                        <p>
                            Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea
                            commodo consequat nostrud tation.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">I Read it &amp; I Agree</button>
                    </div>
                </div>
            </div>
        </div>
@endsection