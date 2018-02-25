<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 19.01.2018
 * Time: 15:29
 */
?>
<aside class="sidebar-container">
    <div class="brand-header">
        <div class="float-left pt-4 text-muted sidebar-close"><em class="ion-arrow-left-c icon-lg"></em></div><a class="brand-header-logo" href="#">
            <!-- Logo Imageimg(src="img/logo.png", alt="logo")
            --><span class="brand-header-logo-text">{{translate('admin_panel')}}</span></a>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-toolbar">
            <div class="sidebar-toolbar-content text-center">
                <div class="sidebar-toolbar-background"></div>
                <div class="mt-3">
                    <div class="lead">{{$user->name.' '.$user->lastname}}</div>
                    <div class="text-thin">{{translate($user->group->titleKey)}}</div>
                </div>
            </div>
        </div>
        @include('layout.admin.left_menu')
    </div>
</aside>
<div class="sidebar-layout-obfuscator"></div>