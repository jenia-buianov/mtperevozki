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
                {{$user_->name.' '.$user_->lastname}}
        </div>
    </div>
    <div class="col-sm-12 table-responsive">
        <form action="{{$url}}" onsubmit="submitForm(this)" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Имя</label>
                <input required name="name" value="{{$user_->name}}" class="form-control">
            </div>
            <div class="form-group">
                <label>Фамилия</label>
                <input name="lastname" value="{{$user_->lastname}}" class="form-control">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input required name="email" type="email" value="{{$user_->email}}" class="form-control">
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input required name="phone" value="{{$user_->phone}}" class="form-control">
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input name="phone2" value="{{$user_->phone2}}" class="form-control">
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input name="phone3" value="{{$user_->phone3}}" class="form-control">
            </div>

            <div class="form-group">
                <label>Группа</label>
                <select name="group_id" class="form-control">
                    @foreach($groups as $k=>$v)
                        <option value="{{$v->id}}" @if($v->id==$user_->group_id) selected="selected" @endif>{{translate($v->titleKey)}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Подтвержден</label>
                <label class="switch switch-warn switch-primary">
                    <input type="checkbox" @if($user_->confirmed) checked="checked" @endif onchange="checkUser('{{encrypt($user_->id)}}')"><span></span>
                </label>
            </div>

            <div class="text-center" style="margin-top: 3em;margin-bottom: 3em">
                <button class="btn btn-gradient btn-success btn-lg" type="submit"> {{translate('edit')}}</button>
            </div>

            {{csrf_field()}}
        </form>
    </div>
</div>
@endsection