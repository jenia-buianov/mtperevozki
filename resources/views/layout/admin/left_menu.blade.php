<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 19.01.2018
 * Time: 15:29
 */
?>
<nav class="sidebar-nav">
    <ul>
        @foreach($left_menu as $k=>$v)
            <li>
                <a href="{{url('admin/'.$v->link)}}">
                    <span class="float-right nav-label"></span>
                    <span class="nav-icon">
                                    <em class="{{$v->icon}}"></em>
                                </span>
                    <span>{{$v->titleKey}}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
