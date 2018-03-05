@extends('layout.header')
@section('content')

    <div class="top-content" style="position: relative; z-index: 0;background: none">
        <div class="container">
            <div class="row">
                <div class="row align-items-center  text-center">
                    <div class="col-12">
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
                            <div class="col-12" style="margin-top: 15px;margin-bottom: 15px;">
                                <div class="row">
                                    <div class="col-md-3 col-md-offset-3">
                                        <button class="btn btn-gray" style="height: 50px;
    margin: 5px;
    padding: 0 20px;" data-toggle="modal" data-target="#transportFormModal">Добавить транспорт</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-gray" style="height: 50px;
    margin: 5px;
    padding: 0 20px;" data-toggle="modal" data-target="#formModal">Добавить груз</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12" style="padding: 3rem;    background-repeat: no-repeat;
    background-image: url(https://www.desktopbackground.org/download/2560x1440/2010/04/17/3405_cool-simple-white-backgrounds-picture-gallery_5120x2880_h.jpg);
    background-position: center;
    background-size: cover;margin-top: 2rem">
                                <h2>Фильтр</h2>
                                <form class="form" action="{{route('birja.transport',['tr'=>$prefix,'lang'=>$lang])}}" method="GET">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-md-offset-1">
                                        <select name="export" class="form-control">
                                            <option selected value="" disabled="disabled">Откуда</option>
                                            <option>{{translate('all_countries')}}</option>
                                            @foreach($countries as $country=>$value)
                                                <option value="{{$value->id_country}}" @if(isset($_GET['export'])&&$value->id_country==(int)$_GET['export']) selected @endif>{{$value->$country_name}} [{{$value->alpha3}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-md-offset-2">
                                        <select name="import" class="form-control">
                                            <option selected value="" disabled="disabled">Куда</option>
                                            <option>{{translate('all_countries')}}</option>
                                            @foreach($countries as $country=>$value)
                                                <option value="{{$value->id_country}}" @if(isset($_GET['import'])&&$value->id_country==(int)$_GET['import']) selected @endif>{{$value->$country_name}} [{{$value->alpha3}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-md-offset-1">
                                        <select name="transport" class="form-control">
                                            <option selected value="" disabled>Транспорт</option>
                                            @foreach($transport_type as $country=>$value)
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
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-md-offset-1">
                                        <input placeholder="Свободен с" name="date_from" class="datepick form-control"  @if(isset($_GET['date_from'])) value="{{$_GET['date_from']}}" @endif>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-md-offset-2">
                                        <input placeholder="Свободен по" name="date_to" class="datepick form-control" @if(isset($_GET['date_to'])) value="{{$_GET['date_to']}}" @endif>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button class="btn btn-link-2">Применить</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <p style="text-align: left;font-weight: bold;padding: 3rem">
                                По запросу {{mb_strtolower($content['h1'])}} найдено {{$search_count}} заявок.
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
                                            <td width="20%">
                                                {{$v->transport_type()}}
                                            </td>
                                            <td width="15%">
                                                {{$v->volume()}}
                                            </td>
                                            <td width="10%">
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
                if ($(this).scrollTop() > pos&&$(this).scrollTop()<$('#grid-data tbody tr:eq('+countTR+')').offset().top) {
                    $('#grid-data thead').css('background-color','white');
//                    $('#grid-data thead').css('color','white');
                    $('#grid-data thead').css('position','fixed');
                    $('#grid-data thead ').css('left',left+'px');
                    $('#grid-data thead ').css('top','60px');
                    $('#grid-data thead th').css('border-right','2px solid #ddd');

                } else {

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