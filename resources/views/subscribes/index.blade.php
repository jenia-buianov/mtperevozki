@extends('layout.header')
@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">
                <div class="row align-items-center  text-center text-white">
                    <div class="col-md-10 col-md-offset-1 col-xs-12 col-xs-offset-0">
                        <div class="card"  style="padding: 3rem;background: white">
                            <h1  class="text-white">{{translate('subscribes')}}</h1>
                            @if(count($subscribes)>0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <th width="5%">#</th>
                                        <th>{{translate('title')}}</th>
                                        <th>{{translate('link')}}</th>
                                        <th>{{translate('results')}}</th>
                                        <th>{{translate('last_update')}}</th>
                                        <th width="5%">{{translate('actions')}}</th>
                                    </thead>
                                    <tbody>
                                    @foreach($subscribes as $k=>$v)
                                        <tr>
                                            <td width="5%">{{$k+1}}</td>
                                            <td  align="left">{{$v->title}}</td>
                                            <td  align="left"><a href="{{$v->url}}">{{translate('go_to_birja')}}</a></td>
                                            <td align="left">{{$v->new_count().' '.translate('new')}}<br>{{$v->total_count().' '.translate('total')}}</td>
                                            <td>{{date('m.d.Y',strtotime($v->updated_at))}}</td>
                                            <td width="5%">
                                                @if($v->active)
                                                    <a title="Отписаться от рассылки" class="btn btn-danger" style="    padding: 6px 12px;    line-height: 1.42857143;" href="{{route('subscribes.dismiss',['lang'=>app()->getLocale(),'id'=>encrypt($v->id)])}}">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </a>
                                                @else
                                                    <a title="Подписаться вновь" class="btn btn-info" style="    padding: 6px 12px;    line-height: 1.42857143;" href="{{route('subscribes.enable',['lang'=>app()->getLocale(),'id'=>encrypt($v->id)])}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="text-center">
                                    {{translate('no_subscribes')}}
                                </div>
                            @endif
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