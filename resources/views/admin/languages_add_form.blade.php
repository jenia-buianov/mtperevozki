<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 21.01.18
 * Time: 21:10
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
                    <div class="form-group">
                        <label>{{__('admin.title')}}</label>
                        <input required type="text" class="form-control" name="title" id="title">
                    </div>
                    <div class="form-group">
                        <label>{{__('admin.code')}}</label>
                        <input required type="text" class="form-control" name="code" id="title">
                    </div>
                <div class="text-center" style="margin-top: 3em;margin-bottom: 3em">
                    <button class="btn btn-gradient btn-success btn-lg" type="submit">@if(!isset($vac)){{__('admin.add')}} @else {{__('admin.edit')}} @endif</button>
                </div>


                {{csrf_field()}}
            </form>
        </div>
    </div>
@endsection

