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
            @if(!isset($page))
                {{$pagetitle}}
            @else
                {{$page->title}}
            @endif
        </div>
    </div>
    <div class="col-sm-12 table-responsive">
        <form action="{{$url}}" onsubmit="submitForm(this)" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>{{translate('url')}}</label>
                <input required name="url_" value="@if(isset($page)){{$page->url}}@endif" class="form-control">
            </div>
            <div class="form-group">
                <label>{{translate('parent')}}</label>
                <select class="form-control" name="sitemap" id="sitemap">
                    @foreach($sitemap as $k=>$v)
                        <option value="{{$v['id']}}" @if (isset($page)&&$v['id']==$page->sitemap[0]->id) selected="selected" @endif>{{$v['title']}}</option>
                        @if (!empty($v['children']))
                            @foreach($v['children'] as $child=>$child1)
                                <option value="{{$child1['id']}}" @if (isset($page)&&$v['id']==$page->sitemap[0]->id) selected="selected" @endif>----- {{$child1['title']}}</option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
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
                            <label>{{translate('title').' '.$v->title}}</label>
                            <input required type="text" class="form-control" name="title_{{$v->code}}" id="title_{{$v->code}}" value="@if(isset($page)){{$page->title}}@endif">
                        </div>

                        <div class="form-group">
                            <label>{{'Metatitle '.$v->title}}</label>
                            <input required type="text" class="form-control" name="metatitle_{{$v->code}}" id="metatitle_{{$v->code}}" value="@if(isset($page)){{$page->metatitle}}@endif">
                        </div>
                        <div class="form-group">
                            <label>{{'Metadesc '.$v->title}}</label>
                            <input required type="text" class="form-control" name="metadesc_{{$v->code}}" id="metadesc_{{$v->code}}" value="@if(isset($page)){{$page->metadesc}}@endif">
                        </div>

                        <div class="form-group">
                            <label>{{'Metakeys '.$v->title}}</label>
                            <input required type="text" class="form-control" name="metakey_{{$v->code}}" id="metakey_{{$v->code}}" value="@if(isset($page)){{$page->metakey}}@endif">
                        </div>

                        <div class="form-group">
                            <label>{{translate('text').' '.$v->title}}</label>
                            <textarea required name="text_{{$v->code}}" id="text_{{$v->code}}" rows="10" cols="80">@if(isset($page)){{$page->content}}@endif</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-12 col-xs-12">
            <div class="row">
                @foreach($forms as $k=>$v)
                <div class="col-md-4 col-xs-12">
                    <code>
                        $$_FORM('{{$v->key}}')
                    </code>
                    {{$v->title}}
                </div>
            @endforeach
            </div>
            </div>

            <div class="text-center" style="margin-top: 3em;margin-bottom: 3em">
                <button class="btn btn-gradient btn-success btn-lg" type="submit">@if(isset($page)) {{translate('edit')}} @else {{translate('add')}} @endif</button>
            </div>
            @if(isset($page))
            <div class="text-center" style="margin-bottom: 3em;">
                <a href="{{url($page->url)}}" class="btn btn-lg btn-info btn-gradient" target="_blank" style="width:100%;">Просмотртеть странцу</a>
            </div>
            @endif

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