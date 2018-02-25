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

    <div class="col-md-4">
        <div class="cardbox">
            <div class="cardbox-body">
                <div class="clearfix mb-2">
                    <div class="float-left"><small>{{translate('new_today')}}</small></div>
                </div>
                <div class="h3" data-counter="{{$new_today}}">{{$new_today}}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="cardbox">
            <div class="cardbox-body">
                <div class="clearfix mb-2">
                    <div class="float-left"><small>{{translate('new_week')}}</small></div>
                </div>
                <div class="h3" data-counter="{{$new_week}}">{{$new_week}}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="cardbox">
            <div class="cardbox-body">
                <div class="clearfix mb-2">
                    <div class="float-left"><small>{{translate('new_month')}}</small></div>
                </div>
                <div class="h3" data-counter="{{$new_month}}">{{$new_month}}</div>
            </div>
        </div>
    </div>

    <div class="col-xs-1 col-sm-1 col-md-4">
        &nbsp;
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
        <p style="margin-bottom: 2.5em;margin-top: 2.5em">
            <a style="width: 100%" class="btn btn-lg btn-gradient wd-sm btn-primary" href="{{url('admin/pages/add')}}">{{translate('add')}}</a>
        </p>
    </div>

    <div class="cardbox col-12">
        <div class="col-sm-12 table-responsive">

            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">#</th>
                    <th data-column-id="name">{{translate('title')}}</th>
                    <th data-column-id="url">{{translate('url')}}</th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($pages as $k=>$v)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$v->title}}</td>
                            <td><a href="{{url($v->url)}}">{{$v->url}}</a></td>
                            <td>
                                <a href="{{url('admin/pages/edit/'.$v->id)}}" data-toggle="tooltip" data-placement="top" title="{{translate('edit')}}" class="btn btn-circle btn-outline-success" style="margin-right: 5px"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <form action="{{url('admin/delete')}}" id="del_form_{{$k}}" method="post" onsubmit="submitDelete(this)" style="display: inline">
                                    <input type="hidden" name="id" value="{{$v->id}}">
                                    <input type="hidden" name="mod" value="pages">
                                    {{csrf_field()}}
                                    <button data-toggle="tooltip" data-info="{{$v->title}}" data-placement="top" title="{{translate('delete')}}" class="btn btn-circle btn-outline-danger" type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        setTimeout(function () {
            $('#grid-data').DataTable({
                "pageLength":10,
                "order":[1,"asc"]
            });
            $('[data-toggle="tooltip"]').tooltip();
        },500);
    </script>

@endsection

