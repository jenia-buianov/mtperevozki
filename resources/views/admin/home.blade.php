<?php
/**
 * Created by PhpStorm.
 * User: jenia
 * Date: 18.01.2018
 * Time: 20:50
 */
?>
@extends('layout.admin')

@section('content')


    <div class="cardbox col-12">
        <div class="cardbox-heading">
            <div class="cardbox-title">text</div>
        </div>

        <div class="col-sm-12 table-responsive">

        </div>
    </div>
    <script>
        setTimeout(function () {
            $('#grid-data').DataTable({
                buttons: [
                    'copy', 'excel', 'pdf'
                ]
            });
            $('[data-toggle="tooltip"]').tooltip();
        },500);
    </script>
@endsection
