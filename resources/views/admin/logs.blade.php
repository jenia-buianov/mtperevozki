<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 17:45
 */
?>
@extends('layout.admin')

@section('content')
    @foreach($statistic as $item=>$value)
        @foreach($value as $k=>$v)
            <div class="col-md-2 col-xs-12 col-sm-6">
                <div class="cardbox">
                    <div class="cardbox-body">
                        <div class="clearfix mb-2">
                            <div class="float-left"><small>{{translate($item.'_'.$k)}}</small></div>
                        </div>
                        <div class="h3" data-counter="{{$v}}">{{$v}}</div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    <div class="cardbox col-sm-12 col-md-6">
        <div class="cardbox-heading">
            <div class="cardbox-title">{{translate('users_today')}}</div>
        </div>
        <div id="users_today" style="height: 350px;"></div>
    </div>

    <div class="cardbox col-sm-12 col-md-6">
        <div class="cardbox-heading">
            <div class="cardbox-title">{{translate('visits_today')}}</div>
        </div>
        <div id="visits_today" style="height: 350px;"></div>
    </div>

    <div class="cardbox col-sm-12 col-md-12">
        <div class="cardbox-heading">
            <div class="cardbox-title">{{translate('users_month')}}</div>
        </div>
        <div id="users_month" style="height: 350px;"></div>
    </div>

    <div class="cardbox col-sm-12 col-md-12">
        <div class="cardbox-heading">
            <div class="cardbox-title">{{translate('visits_month')}}</div>
        </div>
        <div id="visits_month" style="height: 350px;"></div>
    </div>

    <div class="cardbox col-sm-12 col-md-12">
        <div class="cardbox-heading">
            <div class="cardbox-title">{{translate('users_week')}}</div>
        </div>
        <div class="mp" id="cities_users_week"></div>
    </div>

    <div class="cardbox col-sm-12 col-md-12">
        <div class="cardbox-heading">
            <div class="cardbox-title">Пользователи</div>
        </div>
        <div>
            <table class="table">
                @foreach($different_countries as $k=>$v)
                    <tr><td style="font-weight:bold;width: 50%"><a href="#" onclick="viewCountry(this)">{{$k}}</a></td><td></td></tr>
                    @foreach($v as $i=>$l)
                        <tr class="{{$k}}">
                            <td width="50%">{{$i}}</td>
                            <td>{{$l}}</td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    </div>

    <div class="cardbox col-12">
        <div class="cardbox-heading">
            <div class="cardbox-title">{{translate('logs')}}</div>
        </div>

        <div class="col-sm-12 table-responsive">

            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">#</th>
                    <th data-column-id="page">{{translate('page')}}</th>
                    <th data-column-id="lang">{{translate('lang')}}</th>
                    <th data-column-id="local">{{translate('local')}}</th>
                    <th data-column-id="os">{{translate('os_browser')}}</th>
                    <th data-column-id="date">{{translate('date')}}</th>
                    <th data-column-id="description">IP</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($logs as $k=>$v)
                        <tr id="row{{$v->id}}">
                            <td>{{$k+1}}</td>
                            <td>{{$v->page}}</td>
                            <td>{{$v->lang}}</td>
                            <td>{{$v->city.', '.$v->country}}</td>
                            <td>{{$v->browser.', '.$v->os}}</td>
                            <td>{{date('m.d.Y H:i:s',strtotime($v->created_at))}}</td>
                            <td>{{$v->ip}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://www.google.com/jsapi?key=AIzaSyB5jkZ_R0uFP5ueUB5rQ6ykV4rVM6_r_wk"></script>
    <script src="{{url('assets/admin/js/chartkick.js')}}"></script>
    <script src="{{url('assets/admin/js/Chart.bundle.js')}}"></script>

    <script>
        setTimeout(function () {
            var dataTable = $('#grid-data').DataTable({
                "pageLength":100,
                "order":[]
            });
            $('[data-toggle="tooltip"]').tooltip();
            new Chartkick.PieChart("users_today", [@foreach($users_today as $k=>$v)
            ["{{$k}}",{{$v}}],
                @endforeach],{download:true});
            new Chartkick.PieChart("visits_today", [@foreach($visits_today as $k=>$v)
            ["{{$k}}",{{$v}}],
                @endforeach],{download:true});
            new Chartkick.LineChart("users_month", [@foreach($users_month as $k=>$v)
            ["{{$k}}",{{$v}}],
                @endforeach],{download:true});
            new Chartkick.LineChart("visits_month", [@foreach($visits_month as $k=>$v)
            ["{{$k}}",{{$v}}],
                @endforeach],{download:true});
            new Chartkick.GeoChart("cities_users_week", [@foreach($cities_users_week as $k=>$v)
            ["{{$k}}",{{$v}}],
                @endforeach],{library: {displayMode: "markers", colorAxis: {colors: ["#e37b33", "#e37b33"]}},download:true});


        },1000);
    </script>

@endsection

