<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 18.01.2018
 * Time: 20:00
 */
?>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin Template">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>{{__('admin.acp_title')}}</title>
    <link rel="stylesheet" href="{{ asset('assets/admin.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="layout-container">
    <div class="page-container bg-blue-grey-900">
        <div class="d-flex align-items-center align-items-center-ie bg-pic7 bg-cover">
            <div class="fw">
                <div class="container container-xs">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/jquery.js')}}"></script>
<script src="{{asset('assets/admin.min.js')}}"></script>
</body>
</html>
