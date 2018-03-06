<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 18:39
 */
?>
@extends('layout.admin')

@section('content')
<div class="cardbox col-12">
    <div class="cardbox-heading">
        <div class="cardbox-title">
            @if(!isset($group))
                {{$pagetitle}}
            @else
                {{translate($group->titleKey)}}
            @endif
        </div>
    </div>
    <div class="col-sm-12 table-responsive">
        <form action="{{$url}}" onsubmit="submitForm(this)" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Название группы</label>
                <input required name="titleKey" value="{{translate($group->titleKey)}}" class="form-control">
            </div>

            <h3>Разрешения</h3>
            @foreach($permissions as $k=>$permission)
                <div class="col-12">
                    <div class="row">
                        <div class="col-8">
                            <label>{{__($permission->titleKey,[],'ru')}}</label>
                        </div>
                        <div class="col-4">
                            <input type="checkbox" name="permissions[]" value="{{$permission->id}}" @if($group->hasAccess($permission->key)) checked @endif>
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="text-center" style="margin-top: 3em;margin-bottom: 3em">
                <button class="btn btn-gradient btn-success btn-lg" type="submit">@if(isset($group)) {{translate('edit')}} @else {{translate('add')}} @endif</button>
            </div>

            {{csrf_field()}}
        </form>
    </div>
</div>
@endsection