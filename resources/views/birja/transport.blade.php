@extends('layout.header')
@section('content')

    <div class="top-content" style="position: relative; z-index: 0;background: none;padding-top: 100px;">
        <div class="container-fluid">
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <img src="https://mechanicinfo.ru/wp-content/uploads/2018/03/crop-22-768x261.jpg" class="img-fluid">
                </div>
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <img src="https://mechanicinfo.ru/wp-content/uploads/2018/03/crop-22-768x261.jpg" class="img-fluid">
                </div>
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <img src="https://mechanicinfo.ru/wp-content/uploads/2018/03/crop-22-768x261.jpg" class="img-fluid">
                </div>
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <img src="https://mechanicinfo.ru/wp-content/uploads/2018/03/crop-22-768x261.jpg" class="img-fluid">
                </div>
                <div class="col-lg-2 pl-0" style="padding-top: 30px;    padding-left: 0px;">
                    <div class="card align-items-center  text-center row"  style="background: white;padding-bottom: 5px">
                        <div style="  background-repeat: no-repeat;
    background-image: url(https://www.desktopbackground.org/download/2560x1440/2010/04/17/3405_cool-simple-white-backgrounds-picture-gallery_5120x2880_h.jpg);
    background-position: center;
    background-size: cover;color:#333;font-weight:bold;padding: 8px">
                            {{$content['h1']}}
                        </div>
                        <div style="margin-top: 10px;padding-left: 5px;text-align: left">
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{route('birja.transport',['lang'=>app()->getLocale(),'tr'=>$prefix])}}">Транспорт все заявки</a>
                        </div>

                        <div style="padding-left: 5px;text-align: left;">
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{route('birja.cargo',['lang'=>app()->getLocale(),'tr'=>$prefix])}}">Грузы все заявки</a>
                        </div>

                        <div style="text-align: left;padding-left: 5px;">
                            <i class="fas fa-chevron-right"></i>
                            <a href="#specificTransportModal" class="scroll-link" data-toggle="modal" data-target="#specificTransportModal">Добавить транспорт</a>
                        </div>

                    </div>

                    <div class="banners" style="margin-top: 15px">
                        <img src="https://s-media-cache-ak0.pinimg.com/originals/9a/ee/a0/9aeea0701553042f56c56251c33e6a5d.png" class="img-fluid">
                        <img src="https://s-media-cache-ak0.pinimg.com/originals/9a/ee/a0/9aeea0701553042f56c56251c33e6a5d.png" class="img-fluid" style="margin-top: 10px;">
                    </div>

                </div>
                <div class="col-lg-10" style="padding-top: 30px;">
                    <div class="row align-items-center  text-center">
                        <div class="card"  style="background: white">
                            @foreach($menus as $k=>$v)
                                @if($k==$prefix)
                                    <h1 style="font-size: 2rem; display: inline-block; padding-left: 1rem; padding-right: 1rem">{{$content['h1']}}</h1>
                                @else
                                    <h3 style="font-size: 1.5rem; display: inline-block; padding-left: 1rem; padding-right: 1rem">
                                        <a href="{{route('birja.transport',['lang'=>$lang,'tr'=>$k])}}">{{mb_strtoupper(mb_substr($v,0,1)).mb_substr($v,1)}}</a>
                                    </h3>
                                @endif
                            @endforeach

                            <div class="col-12  find_form-container" style="padding: 3rem;    background-repeat: no-repeat;
    background-image: url(https://www.desktopbackground.org/download/2560x1440/2010/04/17/3405_cool-simple-white-backgrounds-picture-gallery_5120x2880_h.jpg);
    background-position: center;
    background-size: cover;margin-top: 2rem">
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
                            <div class="col-sm-12 table-responsive" style="padding: 3rem;padding-top: 0px;">
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
                                                    или <a href="{{route('register')}}">зарегистрируйтесь</a> и смотрите контакты
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
@endsection

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
                    $('.col-lg-2').css('position','fixed');
                    $('.col-lg-2').css('top','45px');
                    $('.col-lg-2').css('left','10px');
                    $('.col-lg-10').css('margin-left','16.66666667%');
                }
                else{
                    $('.col-lg-2').css('top','auto');
                    $('.col-lg-2').css('position','relative');
                    $('.col-lg-2').css('left','auto');
                    $('.col-lg-10').css('margin-left','auto');
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