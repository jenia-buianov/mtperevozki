@extends('layout.header')
@section('content')

    <div class="top-content" style="position: relative; z-index: 0;background: none;padding-top: 100px;">
        <div class="container-fluid">
                <div class="col-xs-12 banner col-md-6 col-lg-3">
                    <div class="bnr">
                        Место для<br>
                        Вашей рекламы<br>
                        300 x 100
                    </div>
                </div>
                <div class="col-xs-12 banner col-md-6 col-lg-3">
                    <div class="bnr">
                        Место для<br>
                        Вашей рекламы<br>
                        300 x 100
                    </div>
                </div>
                <div class="col-xs-12 banner col-md-6 col-lg-3">
                    <div class="bnr">
                        Место для<br>
                        Вашей рекламы<br>
                        300 x 100
                    </div>
                </div>
                <div class="col-xs-12 banner col-md-6 col-lg-3">
                    <div class="bnr">
                        Место для<br>
                        Вашей рекламы<br>
                        300 x 100
                    </div>
                </div>
                <div class="birja-left pl-0" style="padding-top: 30px;    padding-left: 0px;">
                    <div class="card align-items-center  text-center row"  style="background: white;padding-bottom: 5px">
                        <div style="  background-repeat: no-repeat;
    background-image: url(https://www.desktopbackground.org/download/2560x1440/2010/04/17/3405_cool-simple-white-backgrounds-picture-gallery_5120x2880_h.jpg);
    background-position: center;
    background-size: cover;color:#333;font-weight:bold;    line-height: 18px;
    padding: 8px;
    min-height: 65px;
    border-bottom: 1px solid #ccc;
    vertical-align: middle;">
                            <img src="data:image/png;base64,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" style="float:left">
                            {{mb_strtoupper(mb_substr($menus[$prefix],0,1)).mb_substr($menus[$prefix],1)}}
                        </div>
                        <ul style="padding-left: 10px;    text-align: left;
    margin-top: 10px;">
                            <li style="list-style-type:none;"><a href="{{route('birja.cargo',['lang'=>app()->getLocale(),'tr'=>$prefix])}}">Поиск грузов</a></li>
                            <li style="list-style-type:none;"><a href="{{route('birja.transport',['lang'=>app()->getLocale(),'tr'=>$prefix])}}">Поиск транспорта</a></li>
                            <li style="list-style-type:none;"><a href="#specificTransportModal" class="scroll-link" data-toggle="modal" data-target="#specificTransportModal">Добавить груз</a></li>
                            <li style="list-style-type:none;"><a href="#specificTransportModal" class="scroll-link" data-toggle="modal" data-target="#specificTransportModal">Добавить транспорт</a></li>
                        </ul>
                    </div>

                    <div class="banners" style="margin-top: 15px">
                        <img src="data:image/png;base64,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" class="img-fluid">
                        <img src="data:image/png;base64,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" class="img-fluid" style="margin-top: 10px;">
                    </div>

                </div>
                <div class="birja-right" style="padding-top: 30px;">
                    <div class="row align-items-center  text-center">
                        <div class="card"  style="background: white">
                            <ul class="birja-menu" style="margin-bottom: 0px;">
                            @foreach($menus as $k=>$v)
                                <li class="fnp">
                                    <ul>
                                        <li class="current">
                                            <span>{{mb_strtoupper(mb_substr($v,0,1)).mb_substr($v,1)}}</span>
                                            <div>
                                                <a href="{{route('birja.transport',['lang'=>app()->getLocale(),'tr'=>$k])}}">Транспорт</a>
                                                <a href="{{route('birja.cargo',['lang'=>app()->getLocale(),'tr'=>$k])}}">Грузы</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            @endforeach
                            </ul>
                            <div class="col-12  find_form-container" style="padding: 3rem;    background-repeat: no-repeat;
    background-image: url(https://www.desktopbackground.org/download/2560x1440/2010/04/17/3405_cool-simple-white-backgrounds-picture-gallery_5120x2880_h.jpg);
    background-position: center;
    background-size: cover;">
                                <div class="container-fluid" style="max-width: 1140px">
                                    <form class="form" action="{{route('birja.transport',['tr'=>$prefix,'lang'=>$lang])}}" method="GET">
                                        <div class="col-sm-12 col-md-4 col-md-offset-1">
                                            <select name="export" class="form-control">
                                                <option selected value="" disabled="disabled">{{translate('country_from')}}</option>
                                                <option>{{translate('all_countries')}}</option>
                                                @foreach($countries as $country=>$value)
                                                    <option value="{{$value->id_country}}" @if(isset($_GET['export'])&&$value->id_country==(int)$_GET['export']) selected @endif>{{$value->$country_name}} [{{$value->alpha3}}]</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-md-offset-2">
                                            <select name="import" class="form-control">
                                                <option selected value="" disabled="disabled">{{translate('country_to')}}</option>
                                                <option>{{translate('all_countries')}}</option>
                                                @foreach($countries as $country=>$value)
                                                    <option value="{{$value->id_country}}" @if(isset($_GET['import'])&&$value->id_country==(int)$_GET['import']) selected @endif>{{$value->$country_name}} [{{$value->alpha3}}]</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-md-offset-1">
                                            <select name="transport" class="form-control">
                                                <option selected value="" disabled>Транспорт</option>
                                                <?php $group = $transport_type[0]->transport_type_group; ?>
                                                @foreach($transport_type as $country=>$value)
                                                    <?php if ($group!==$value->transport_type_group) {
                                                        $group = $value->transport_type_group;
                                                        echo '<option value="" disabled>-------------------</option>';
                                                    } ?>
                                                    <option value="{{$value->id}}" @if(isset($_GET['transport'])&&$value->id==(int)$_GET['transport']) selected @endif>{{$value->$transport_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-md-offset-2">
                                            <select name="volume" class="form-control">
                                                <option selected value="" disabled>Вес</option>
                                                @foreach($cargo_volume as $country=>$value)
                                                    <option value="{{$value->id}}" @if(isset($_GET['volume'])&&$value->id==(int)$_GET['volume']) selected @endif>{{$value->$cargo_volume_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-md-offset-1">
                                            <input placeholder="Свободен с" name="date_from" class="datepick form-control"  @if(isset($_GET['date_from'])) value="{{$_GET['date_from']}}" @endif>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-md-offset-2">
                                            <input placeholder="Свободен по" name="date_to" class="datepick form-control" @if(isset($_GET['date_to'])) value="{{$_GET['date_to']}}" @endif>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button class="btn btn-link-2">{{translate('search')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <p class="top_titles" style="text-align: left;font-weight: bold;padding: 3rem">
                                По запросу {{mb_lcfirst(explode('.',$content['metatitle'])[0])}} найдено {{$search_count}} заявок.
                            </p>
                            <div class="col-sm-12 table-responsive" style="padding-top: 0px;">
                                <table id="grid-data" class="table table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th height="50">Откуда</th>
                                        <th height="50">Куда</th>
                                        <th height="50" style="text-align: center">Тип транспорта</th>
                                        <th height="50" style="text-align: center">Объем/вес</th>
                                        <th height="50" style="text-align: center">Дата</th>
                                        <th height="50">Контакты</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($search as $k=>$v)
                                        <tr id="row{{$v->id}}">
                                            <td align="left" width="20%">
                                                <img src="{{url('images/flags/flat/24/'.$v->export_flag().'.png')}}" width="24" height="24">
                                                <font class="hidden-lg hidden-md">{{$v->export_flag()}}</font>
                                                <font class="hidden-xs hidden-sm"><?php echo $v->export()?></font>
                                            </td>
                                            <td align="left" width="20%">
                                                <img src="{{url('images/flags/flat/24/'.$v->import_flag().'.png')}}" width="24" height="24">
                                                <font class="hidden-lg hidden-md">{{$v->import_flag()}}</font>
                                                <font class="hidden-xs hidden-sm"><?php echo $v->import()?></font>
                                            </td>
                                            <td width="20%" align="center">
                                                {{$v->transport_type()}}
                                            </td>
                                            <td width="15%" align="center">
                                                {{$v->volume()}}
                                            </td>
                                            <td width="10%" align="center">
                                                {{$v->date_from}}<br>
                                                {{$v->date_to}}
                                            </td>
                                            <td>
                                                @if(\Illuminate\Support\Facades\Auth::check())
                                                    {{$v->face}}
                                                @else
                                                    <a href="#" data-toggle="modal" data-target="#loginModal">Авторизируйтесь</a>
                                                    или <a href="#" data-toggle="modal" data-target="#registerModal">зарегистрируйтесь</a> и смотрите контакты
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <?php $assets = $_GET; unset($assets['page']); ?>
                            {{$search->appends($assets)->links()}}
                        </div>
                    </div>
                </div>
        </div>
    </div>


@if (!Auth::check())
    <div class="modal fade modal-primary" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-register">
            <div class="modal-content">
                <div class="card card-login card-plain">
                    <div class="modal-header justify-content-center">
                        <div style="text-align: right">
                            <i class="fa fa-times" data-dismiss="modal" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <h3 style="color:white;text-align: center">Регистрация</h3>
                        <form class="form" method="POST" action="{{url('/register')}}" style="margin-top: 2rem;">
                            <div class="card-body">
                                <div class="form-group input-group input-group-lg fix">
                                    <span class="input-group-addon"><i class="fas fa-envelope"></i></span>
                                    <input  data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{translate('will_be_send_reg_message')}}" type="email" class="form-control" name="email" placeholder="Укажите ваш Email" value="{{ old('email') }}" required autofocus>
                                </div>
                                <div class="form-group input-group input-group-lg fix">
                                    <span class="input-group-addon"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="name" placeholder="Укажите ваше имя" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group input-group input-group-lg fix">
                                    <span class="input-group-addon">+</span>
                                    <input type="number" class="form-control" name="phone" placeholder="{{translate('enter_field').' '.mb_strtolower(translate('phone'))}}" value="@if(empty(old('phone'))){{'+'}}@else{{ old('phone') }}@endif" required>
                                </div>
                                <div class="form-group input-group input-group-lg fix">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="Придумайте пароль" required>
                                </div>
                                <div class="form-group input-group input-group-lg fix">
                                    <span class="input-group-addon"><i class="fab fa-creative-commons"></i></span>
                                    <select class="form-control" name="type" required>
                                        <option selected>-Укажите вашу деятельность-</option>
                                        <option value="Грузовладелец">Грузовладелец </option>
                                        <option value="Автотранспортная компания">Автотранспортная компания</option>
                                        <option value="Железнодорожные перевозки">Железнодорожные перевозки</option>
                                        <option value="Авиа перевозки">Авиа перевозки</option>
                                        <option value="Морские перевозки">Морские перевозки</option>
                                        <option value="Экспресс доставка">Экспресс доставка </option>
                                        <option value="Пассажирские перевозки">Пассажирские перевозки</option>
                                        <option value="Экспедиторская компания">Экспедиторская компания</option>
                                    </select>
                                </div>
                                <div class="form-group input-group input-group-lg fix" style="margin-top: 1rem;">
                                    <span class="input-group-addon"><i class="fa fa-copyright"></i></span>
                                    <input type="text" class="form-control" name="company" placeholder="Укажите название компании" value="{{ old('company') }}">
                                </div>
                                <div class="col-sm-12" style="    padding: 0px;text-align: center;margin-bottom: 2rem;">
                                    {!! NoCaptcha::display() !!}
                                    {{translate('set_captcha')}}
                                </div>
                                <button class="btn btn-link-1" style="margin: 0px 5px 5px 0px;width: 100%;display: block">{{translate('registration')}}</button>
                            </div>
                            {{csrf_field()}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    setTimeout(function () {
        $(function () {

            pos = $('#grid-data thead').offset().top;
            left = $('#grid-data thead').offset().left;
            getTd = $('#grid-data tbody tr:eq(0)').html().split('<td');
            countTR = $('#grid-data tbody').html().split('<tr').length-2;



            for(k=0;k<getTd.length;k++)
                $('#grid-data thead th:eq('+k+')').css('width',($('#grid-data tbody tr:eq(0) td:eq('+k+')').width()+11)+'px');

            for(k=0;k<getTd.length;k++)
                $('#grid-data thead th:eq('+k+')').css('width',($('#grid-data tbody tr:eq(0) td:eq('+k+')').width()+11)+'px');

            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('.birja-left').css('position','fixed');
                    $('.birja-left').css('top','45px');
                    $('.birja-left').css('left','15px');
                    $('.birja-right').css('margin-left','235px');
                }
                else{
                    $('.birja-left').css('top','auto');
                    $('.birja-left').css('position','relative');
                    $('.birja-left').css('left','0px');
                    $('.birja-right').css('margin-left','auto');
                }

                if ($(this).scrollTop() > pos&&$(this).scrollTop()<$('#grid-data tbody tr:eq('+countTR+')').offset().top) {

                    $('.top_titles').css('position','fixed');
                    $('.top_titles').css('z-index','1000');
                    $('.top_titles').css('background','white');
                    $('.top_titles').css('width','100%');
                    $('.top_titles').css('padding','10px');
                    $('.top_titles').css('padding-left','3rem');
                    $('.top_titles').css('top','60px');
                    $('#grid-data thead').css('background-color','white');
//                    $('#grid-data thead').css('color','white');
                    $('#grid-data thead').css('position','fixed');
                    $('#grid-data thead ').css('left',left+'px');
                    $('#grid-data thead ').css('top','90px');
                    $('#grid-data thead th').css('border-right','2px solid #ddd');


                } else {

                    $('.top_titles').css('position','relative');
                    $('.top_titles').css('top','0');
                    $('#grid-data thead th').css('border-right','none');
                    $('#grid-data thead').css('background-color','transparent');
//                    $('#grid-data thead').css('color','inherit');
                    $('#grid-data thead').css('position','relative');
                    $('#grid-data thead ').css('left','0px');
                    $('#grid-data thead ').css('top','0px');

                }

            });



        });

    },1500);
</script>
@endsection