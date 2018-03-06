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
                <label>Деяьельность</label>
                <select class="form-control" name="type" required>
                    <option selected>-Укажите деятельность пользователя-</option>
                    <option value="Грузовладелец" @if($user_->type=='Грузовладелец') selected="selected" @endif>Грузовладелец </option>
                    <option value="Автотранспортная компания" @if($user_->type=='Автотранспортная компания') selected="selected" @endif>Автотранспортная компания</option>
                    <option value="Железнодорожные перевозки" @if($user_->type=='Железнодорожные перевозки') selected="selected" @endif>Железнодорожные перевозки</option>
                    <option value="Авиа перевозки" @if($user_->type=='Авиа перевозки') selected="selected" @endif>Авиа перевозки</option>
                    <option value="Морские перевозки" @if($user_->type=='Морские перевозки') selected="selected" @endif>Морские перевозки</option>
                    <option value="Экспресс доставка" @if($user_->type=='Экспресс доставка') selected="selected" @endif>Экспресс доставка </option>
                    <option value="Пассажирские перевозки" @if($user_->type=='Пассажирские перевозки') selected="selected" @endif>Пассажирские перевозки</option>
                    <option value="Экспедиторская компания" @if($user_->type=='Экспедиторская компания') selected="selected" @endif>Экспедиторская компания</option>
                </select>
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