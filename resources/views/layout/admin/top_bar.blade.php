<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 19.01.2018
 * Time: 15:29
 */
?>
<header class="header-container">
    <nav>
        <ul class="d-lg-none">
            <li><a class="sidebar-toggler menu-link menu-link-close" href="#"><span><em></em></span></a></li>
        </ul>
        <ul class="d-none d-sm-block">
            <li><a class="covermode-toggler menu-link menu-link-close" href="#"><span><em></em></span></a></li>
        </ul>
        <h2 class="header-title">
            @if (!is_array($module))
                {{$module->titleKey}}
            @else
                @for($i=0;$i<count($module);$i++)
                    <a href="{{$module[$i]['link']}}" style="color:rgb(89, 103, 107);">{{$module[$i]['title']}}</a>
                    @if ($i<count($module)-1) > @endif
                @endfor
            @endif
        </h2>
        <ul class="float-right">
            <li class="dropdown"><a class="dropdown-toggle has-badge" href="#" data-toggle="dropdown"><span class="user_top_avatar bg-gradient-danger header-user-image">{{mb_strtoupper(mb_substr($user->name,0,1)).mb_strtoupper(mb_substr($user->lastname,0,1))}}</span></a>
                <div class="dropdown-menu dropdown-menu-right dropdown-scale">
                    <div class="dropdown-divider" role="presentation"></div><a class="dropdown-item" href="{{url('logout')}}"><em class="ion-log-out icon-lg text-primary"></em>{{translate('logout')}}</a>
                </div>
            </li>
            <li><a id="header-search" href="#"><em class="ion-ios-search-strong"></em></a></li>
        </ul>
    </nav>
</header>
