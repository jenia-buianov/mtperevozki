<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 17:45
 */
?>
@extends('layout.admin')

@section('content')

    <div class="col-12 alert alert-warning">
        Показано всего 100 компаний и поиск производится только среди этой сотни. Чтобы просмотреть другие компании используйте страницы
    </div>
    <div class="cardbox col-12">
        <div class="col-sm-12 table-responsive">
            <table id="grid-data" class="table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th data-column-id="id" data-type="numeric">#</th>
                    <th data-column-id="name">Название компании</th>
                    <th data-column-id="users">Пользователи</th>
                    <th data-column-id="type" data-type="date">Дата создания</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($companies as $k=>$v)
                        <tr>
                            <td>@if($k<9)0{{$k+1}}@else{{$k+1}}@endif</td>
                            <td>{{$v->title}}</td>
                            <td>
                                @if(count($v->users))
                                    @foreach($v->users as $c=>$user)
                                        <b>{{$user->name.' '.$user->lastname}}</b><br>
                                    @endforeach
                                @else
                                    Нет пользователей
                                @endif
                            </td>
                            <td>{{date('d.m.Y', strtotime($v->created_at))}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$companies->links()}}
        </div>
    </div>
    <script>
        setTimeout(function () {
            $('#grid-data').DataTable({
                "pageLength":100,
                "order":[0,"asc"],
                "info":false,
                "lengthMenu":[100],
                "paging": false
            });
            $('[data-toggle="tooltip"]').tooltip();
        },500);
    </script>

@endsection

