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

    <div class="col-xs-1 col-sm-1 col-md-4">
        &nbsp;
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
        <p style="margin-bottom: 2.5em;margin-top: 2.5em">
            <a style="width: 100%" class="btn btn-lg btn-gradient wd-sm btn-primary" href="{{url('admin/groups/add')}}">{{'Добавить'}}</a>
        </p>
    </div>

    <div class="cardbox col-12">
        <div class="col-sm-12 table-responsive">
            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">#</th>
                    <th data-column-id="name">Группа</th>
                    <th data-column-id="users">Количество пользователей</th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($groups as $k=>$v)
                        <tr>
                            <td>@if($k<9)0{{$k+1}}@else{{$k+1}}@endif</td>
                            <td>{{translate($v->titleKey)}}</td>
                            <td>{{count($v->user)}} пользователей</td>
                            <td>
                                <a href="{{url('admin/groups/edit/'.$v->id)}}" data-toggle="tooltip" data-placement="top" title="{{translate('edit')}}" class="btn btn-circle btn-outline-success" style="margin-right: 5px"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
                "pageLength":100,
                "order":[1,"asc"],
            });
            $('[data-toggle="tooltip"]').tooltip();
        },500);
    </script>

@endsection

