<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 18:39
 */
?>
@extends('layouts.admin')

@section('content')
<div class="cardbox col-12">
    <div class="cardbox-heading">
        <div class="cardbox-title">
            {{__('admin.'.$permission->titleKey)}}
        </div>
    </div>
    <div class="col-sm-12 table-responsive">
        <form action="{{$url}}" onsubmit="submitForm(this)" method="post" enctype="multipart/form-data">
            @if(isset($vac))
                <div class="text-center">
                    <img src="{{url($vac->photo)}}" style="max-width: 250px; max-height: 250px">
                </div>
            @endif
            <div class="form-group">
                <label>{{__('admin.photo')}}</label>
                <input type="file" @if(!isset($vac)) required @endif class="form-control" name="photo" id="photo">
            </div>
            <ul class="nav nav-pills">
            @foreach($langs as $k=>$v)
                    <li class="nav-item">
                        <a class="nav-link @if($k==0) active @endif" data-toggle="tab" href="#lang{{$v->code}}" role="tab">{{$v->title}}</a>
                    </li>
            @endforeach
            </ul>
                <div class="tab-content clearfix">
                @foreach($langs as $k=>$v)
                    <div class="tab-pane @if($k==0) active @endif" id="lang{{$v->code}}">
                        <div class="form-group">
                            <label>{{__('admin.title').' '.$v->title}}</label>
                            <input required type="text" class="form-control" name="title_{{$v->code}}" id="title" value="@if(isset($vac)){{__('objects.'.$vac->titleKey,[],$v->code)}}@endif">
                        </div>
                        <div class="form-group">
                            <label>{{__('admin.desc').' '.$v->title}}</label>
                            <textarea required name="text_{{$v->code}}" id="text_{{$v->code}}" rows="10" cols="80">@if(isset($vac)){{__('objects.'.$vac->textKey,[],$v->code)}}@endif</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center" style="margin-top: 3em;margin-bottom: 3em">
                <button class="btn btn-gradient btn-success btn-lg" type="submit">@if(!isset($vac)){{__('admin.add')}} @else {{__('admin.edit')}} @endif</button>
            </div>


            {{csrf_field()}}
        </form>
    </div>
</div>
<script src="{{asset('assets/admin/ckeditor/ckeditor.js')}}"></script>
    <script>setTimeout(function() {
                @foreach($langs as $k=>$v)
                CKEDITOR.replace('text_{{$v->code}}');
                @endforeach
            },1000);
    </script>
@endsection