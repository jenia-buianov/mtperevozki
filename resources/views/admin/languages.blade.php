<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 21.01.18
 * Time: 15:34
 */
?>
@extends('layout.admin')

@section('content')
    <div class="col-xs-1 col-sm-1 col-md-4">
        &nbsp;
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
        <p style="margin-bottom: 2.5em;margin-top: 2.5em">
            <a style="width: 100%" class="btn btn-lg btn-gradient wd-sm btn-primary" href="{{url('admin/languages/add')}}">{{translate('add')}}</a>
        </p>
    </div>

<div class="cardbox col-12">
    <div class="col-sm-12 table-responsive">
        <table id="grid-data" class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th data-column-id="id" data-type="numeric">#</th>
                <th data-column-id="name">Название языка</th>
                <th data-column-id="code">Код</th>
                <th data-column-id="active">Используется</th>
                <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($languages as $k=>$v)
                <tr id="lang{{$v->id}}">
                    <td>{{$k+1}}</td>
                    <td>{{$v->title}}</td>
                    <td>{{$v->code}}</td>
                    <td width="80">
                        <label class="switch switch-warn switch-primary">
                            <input type="checkbox" @if($v->active==1) checked="checked" @endif onchange="checkLang('{{encrypt($v->id)}}')"><span></span>
                        </label>
                    </td>
                    <td>
                        <a href="{{url('admin/languages/edit/'.$v->id)}}" data-toggle="tooltip" data-placement="top" title="{{translate('edit')}}" class="btn btn-circle btn-outline-success" style="margin-right: 5px"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <form action="{{url('admin/delete')}}" id="del_form_{{$k}}" method="post" onsubmit="submitDelete(this)" style="display: inline">
                            <input type="hidden" name="id" value="{{$v->id}}">
                            <input type="hidden" name="mod" value="languages">
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
        var dataTable = $('#grid-data').DataTable({
            "pageLength":10,
            "order":[]
        });
        $('[data-toggle="tooltip"]').tooltip();
    },500);
</script>
@endsection
