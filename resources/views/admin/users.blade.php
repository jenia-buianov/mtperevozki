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

    <div class="cardbox col-12">
        <div class="col-sm-12 table-responsive">

            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">#</th>
                    <th data-column-id="name">Имя Фамилия</th>
                    <th data-column-id="url">Email</th>
                    <th data-column-id="confirmed" data-sortable="false">Поддтвержден</th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($users as $k=>$v)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$v->name.' '.$v->lastname}}</td>
                            <td>{{$v->email}}</td>
                            <td>
                                <label class="switch switch-warn switch-primary">
                                    <input type="checkbox" @if($v->confirmed) checked="checked" @endif onchange="checkUser('{{encrypt($v->id)}}')"><span></span>
                                </label>
                            </td>
                            <td>
                                <a href="{{url('admin/users/edit/'.$v->id)}}" data-toggle="tooltip" data-placement="top" title="{{translate('edit')}}" class="btn btn-circle btn-outline-success" style="margin-right: 5px"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <form action="{{url('admin/users/delete')}}" id="del_form_{{$k}}" method="post" onsubmit="submitDelete(this)" style="display: inline">
                                    <input type="hidden" name="id" value="{{$v->id}}">
                                    {{csrf_field()}}
                                    <button data-toggle="tooltip" data-info="{{$v->name.' '.$v->lastname}}" data-placement="top" title="{{translate('delete')}}" class="btn btn-circle btn-outline-danger" type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
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

