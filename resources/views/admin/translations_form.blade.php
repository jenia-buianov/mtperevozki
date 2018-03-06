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
            @if(!isset($translation))
                {{$pagetitle}}
            @else
                {{$translation->key}}
            @endif
        </div>
    </div>
    <div class="col-sm-12 table-responsive">
        <form action="{{$url}}" onsubmit="submitForm(this)" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Ключ</label>
                <input required name="key" value="@if(isset($translation)){{$translation->key}}@endif" class="form-control">
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
                            <label>{{'Текст '.$v->title}}</label>
                            <textarea required name="text_{{$v->code}}" id="text_{{$v->code}}" rows="10" cols="80">@if(isset($translation))
                                @foreach($all_translations as $i=>$t)
                                    @if($t->lang==$v->code){{htmlspecialchars_decode($t->text)}}@endif
                                @endforeach
                                @endif</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center" style="margin-top: 3em;margin-bottom: 3em">
                <button class="btn btn-gradient btn-success btn-lg" type="submit">@if(isset($translation)) {{translate('edit')}} @else {{translate('add')}} @endif</button>
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