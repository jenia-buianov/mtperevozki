<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 17:45
 */
?>
@extends('layouts.admin')

@section('content')
    <div class="col-md-4">
        <div class="cardbox">
            <div class="cardbox-body">
                <div class="clearfix mb-2">
                    <div class="float-left"><small>{{__('admin.new_today')}}</small></div>
                </div>
                <div class="h3" data-counter="{{$new_today}}">{{$new_today}}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="cardbox">
            <div class="cardbox-body">
                <div class="clearfix mb-2">
                    <div class="float-left"><small>{{__('admin.new_week')}}</small></div>
                </div>
                <div class="h3" data-counter="{{$new_week}}">{{$new_week}}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="cardbox">
            <div class="cardbox-body">
                <div class="clearfix mb-2">
                    <div class="float-left"><small>{{__('admin.new_month')}}</small></div>
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
            <a style="width: 100%" class="btn btn-lg btn-gradient wd-sm btn-primary" href="{{url('admin/objects/add')}}">{{__('admin.add')}}</a>
        </p>
    </div>

    <div class="cardbox col-12">
        <div class="col-sm-12 table-responsive">

            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th data-columt-id="order" data-sortable="false">{{__('admin.order')}}</th>
                    <th data-column-id="id" data-type="numeric">#</th>
                    <th data-column-id="photo" data-sortable="false" width="100">{{__('admin.photo')}}</th>
                    <th data-column-id="name">{{__('admin.title')}}</th>
                    <th data-column-id="description" data-sortable="false">{{__('admin.desc')}}</th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($objects as $k=>$v)
                        <tr id="row{{$v->id}}">
                            <td style="width: 120px">
                                @if($k<count($objects)-1)
                                    <form action="{{url('admin/setorder/')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                                        <input type="hidden" name="id" value="{{$v->id}}">
                                        <input type="hidden" name="mod" value="objects">
                                        <input type="hidden" name="type" value="down">
                                        {{csrf_field()}}
                                        <button data-toggle="tooltip" data-placement="top" title="{{__('admin.down')}}" class="btn btn-flat btn-info" type="submit"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                                    </form>
                                @endif
                                @if($k>0)
                                        <form action="{{url('admin/setorder/')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                                            <input type="hidden" name="id" value="{{$v->id}}">
                                            <input type="hidden" name="mod" value="objects">
                                            <input type="hidden" name="type" value="up">
                                            {{csrf_field()}}
                                            <button data-toggle="tooltip" data-placement="top" title="{{__('admin.up')}}" class="btn btn-flat btn-info" type="submit"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                                        </form>
                                @endif
                            </td>
                            <td id="order{{$v->id}}">{{$v->order}}</td>
                            <td><img src="{{url($v->photo)}}" style="max-width: 100px;max-height: 100px"></td>
                            <td>{{__('objects.'.$v->titleKey)}}</td>
                            <td>{{__('objects.'.$v->textKey)}}</td>
                            <td>
                                <a href="{{url('admin/objects/edit/'.$v->id)}}" data-toggle="tooltip" data-placement="top" title="{{__('admin.edit')}}" class="btn btn-circle btn-outline-success" style="margin-right: 5px"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                                <form action="{{url('admin/delete')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                                    <input type="hidden" name="id" value="{{$v->id}}">
                                    <input type="hidden" name="mod" value="objects">
                                    {{csrf_field()}}
                                    <button data-toggle="tooltip" data-placement="top" title="{{__('admin.delete')}}" class="btn btn-circle btn-outline-danger" type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
                "order":[1,"asc"]
            });
            console.log(dataTable);
            $('[data-toggle="tooltip"]').tooltip();
        },500);
    </script>

@endsection

