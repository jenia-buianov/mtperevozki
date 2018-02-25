<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 21.01.18
 * Time: 15:34
 */
?>
@extends('layouts.admin')

@section('content')
<div class="cardbox col-12">
    <div class="col-sm-12 table-responsive">
        <table id="grid-data1" class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th data-column-id="id" data-type="numeric">#</th>
                <th data-column-id="name">{{__('admin.title')}}</th>
                <th data-column-id="code">{{__('admin.code')}}</th>
                <th data-column-id="date">{{__('admin.date')}}</th>
                <th data-column-id="active">{{__('admin.active')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($languages as $k=>$v)
                <tr id="lang{{$v->id}}">
                    <td>{{$k+1}}</td>
                    <td>{{$v->title}}</td>
                    <td>{{$v->code}}</td>
                    <td>{{date('m.d.Y',strtotime($v->created_at))}}</td>
                    <td width="80">
                        @if($v->active==1)
                            <form action="{{url('admin/language/disable')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                                <input type="hidden" name="id" value="{{$v->id}}">
                                {{csrf_field()}}
                                <button data-toggle="tooltip" data-placement="top" title="{{__('admin.disable')}}" class="btn btn-flat btn-danger" type="submit"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                            </form>
                        @else
                            <form action="{{url('admin/language/enable')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                                <input type="hidden" name="id" value="{{$v->id}}">
                                {{csrf_field()}}
                                <button data-toggle="tooltip" data-placement="top" title="{{__('admin.enable')}}" class="btn btn-flat btn-success" type="submit"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-12" style="margin-top: 25px;margin-bottom: 35px">
    <div class="row">
        <a href="{{url('admin/language/add')}}" class="btn btn-gradient btn-primary col-xs-12 col-sm-6 text-center" style="width: 100%">{{__('admin.add_language')}}</a>
        <a href="{{url('admin/language/addtranslation')}}" class="btn btn-gradient btn-info col-xs-12 col-sm-6 text-center" style="width: 100%">{{__('admin.add_translation')}}</a>
    </div>
</div>
<div class="cardbox col-12">
    <div class="cardbox-heading">
        <div class="cardbox-title">{{__('admin.translations')}}</div>
    </div>
    <div class="col-sm-12 table-responsive">

        <table id="grid-data" class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th data-column-id="id" data-type="numeric">#</th>
                @foreach($languages as $k=>$v)
                    @if($v->active==1)
                        <th data-column-id="lang_{{$v->code}}">{{__('admin.title').' '.$v->title}}</th>
                    @endif
                @endforeach
                <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @for($i=0;$i<count($translations);$i++)
                <tr id="row{{$v->id}}">
                    <td>{{$i+1}}</td>
                        @foreach($languages as $k=>$v)
                            @if($v->active==1)
                                <?php  $file = explode('.',$translations[$i]); ?>
                                <td>@if(is_file(__DIR__.'/../../../resources/lang/'.$v->code.'/'.$file[0].'.php')) {{__($translations[$i],[],$v->code)}} @endif</td>
                            @endif
                        @endforeach
                    <td>
                        <a href="{{url('admin/language/editTranslation/'.$translations[$i])}}" data-toggle="tooltip" data-placement="top" title="{{__('admin.edit')}}" class="btn btn-circle btn-outline-success" style="margin-right: 5px"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                        <form action="{{url('admin/language/delete')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                            <input type="hidden" name="id" value="{{$v->id}}">
                            {{csrf_field()}}
                            <button data-toggle="tooltip" data-placement="top" title="{{__('admin.delete')}}" class="btn btn-circle btn-outline-danger" type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>
            @endfor
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
        var dataTable1 = $('#grid-data1').DataTable({
            "pageLength":10,
            "order":[0,"asc"]
        });
        $('[data-toggle="tooltip"]').tooltip();
    },500);
</script>
@endsection
