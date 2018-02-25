<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 20.01.18
 * Time: 2:40
 */
?>
@extends('layouts.admin')

@section('content')

    <div class="cardbox col-12">
        <div class="col-sm-12 table-responsive">
            @include("admin.order_table")
        </div>
    </div>
    <script>
        setTimeout(function () {
            $('#grid-data').DataTable({
                "pageLength":50
            });
            $('[data-toggle="tooltip"]').tooltip();
        },500);
    </script>

@endsection
