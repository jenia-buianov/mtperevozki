<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 16:38
 */
?>
<table id="grid-data" class="table table-condensed table-hover table-striped">
    <thead>
    <tr>
        <th data-column-id="id" data-type="numeric">#</th>
        <th data-column-id="name">{{__('admin.name')}}</th>
        <th data-column-id="phone" data-sortable="false">{{__('admin.phone')}}</th>
        <th data-column-id="date">{{__('admin.date')}}</th>
        <th data-column-id="locality">{{__('admin.local')}}</th>
        <th data-column-id="commands" data-formatter="commands" data-sortable="false">{{__('admin.acp_actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $k=>$v)
        <tr id="row{{$v->id}}">
            <td>@if($k<9){{'0'.($k+1)}}@else{{$k+1}}@endif</td>
            <td>{{$v->name}}</td>
            <td align="center">{{$v->phone}}</td>
            <td>{{date('d.m.Y H:i',strtotime($v->created_at))}}</td>
            <td>{{$v->local}}</td>
            <td style="text-align: right" width="120px;">
                @if(!$v->checked)
                <form action="{{url('admin/orders/checked')}}" method="post" onsubmit="submitForm(this)" style="display: inline;margin-right: 5px;">
                    <input type="hidden" name="id" value="{{$v->id}}">
                    {{csrf_field()}}
                    <button data-toggle="tooltip" data-placement="top" title="{{__('admin.checked')}}" class="btn btn-circle btn-outline-success" type="submit"><i class="fa fa-check-circle-o" aria-hidden="true"></i></button>
                </form>
                @else
                    <button data-toggle="tooltip" data-placement="top" title="{{__('admin.dones')}}" class="btn btn-circle btn-gradient btn-success" type="button" style="margin-right: 5px;"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                @endif
                <form action="{{url('admin/delete')}}" method="post" onsubmit="submitForm(this)" style="display: inline">
                    <input type="hidden" name="id" value="{{$v->id}}">
                    <input type="hidden" name="mod" value="orders">
                    {{csrf_field()}}
                    <button data-toggle="tooltip" data-placement="top" title="{{__('admin.delete')}}" class="btn btn-circle btn-outline-danger" type="submit"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </form>
        </tr>
    @endforeach
    </tbody>
</table>
