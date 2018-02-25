<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Bootstrap Admin Template">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>{{translate('admin_panel')}}</title>
    <link rel="stylesheet" href="{{asset('css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/admin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/ckeditor/.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="theme-default">
<div class="layout-container">
    <!-- top navbar-->
    @include('layout.admin.top_bar')
    <!-- sidebar-->
    @include('layout.admin.left_bar')
    <!-- Main section-->
    <main class="main-container">
        <section class="section-container">
            <div class="container container-lg">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </section>
    </main>
</div>
<!-- Search template-->
<div class="modal modal-top fade modal-search" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-search-form">
                    <form action="#">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button class="btn btn-flat" type="button" data-dismiss="modal"><em class="ion-arrow-left-c icon-lg text-muted"></em></button>
                            </div>
                            <input class="form-control header-input-search" type="text" placeholder="{{translate('search')}}..">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{csrf_field()}}
<!-- Modernizr-->
<script src="{{asset('assets/jquery.js')}}"></script>
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/admin/js/colors.js')}}"></script>
<script src="{{asset('assets/admin/js/gmaps.js')}}"></script>
<script src="{{asset('assets/admin/js/index.js')}}"></script>
<script src="{{asset('assets/admin/js/index_.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.categories.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.orderBars.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.resize.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.spline.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.time.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.flot.tooltip.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.knob.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.localize.js')}}"></script>
<script src="{{asset('assets/admin/js/modernizr.custom.js')}}"></script>
<script src="{{asset('assets/admin/js/pace.min.js')}}"></script>
<script src="{{asset('assets/admin/js/popper.min.js')}}"></script>
<script src="{{asset('assets/admin/js/screenfull.js')}}"></script>
<script src="{{asset('assets/admin/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/admin/js/dataTables_'.app()->getLocale().'.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('assets/admin/js/app.js')}}"></script>
<script src="{{asset('assets/admin/js/simple.js')}}"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
</body>
</html>