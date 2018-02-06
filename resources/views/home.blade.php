@extends('layout.header')

@section('content')
    <div class="top-content" style="position: relative; z-index: 0; background: none;">
        <div class="container">
            <div class="row">

                <div class="col-sm-5 c-form-1-box">
                    &nbsp
                </div>
                <div class="col-sm-12 text" data-aos="fade-up" data-aos-delay="300">
                    <h1 style="    color: #333;">{{translate('main_logo')}}</h1>
                    <div class="description">
                        <p class="medium-paragraph" style="    color: #333;">Найдём перевозчика
                            для доставки любого груза</p>
                    </div>
                    <div style="margin-bottom: 250px;">
                        <button class="btn btn-link-2">Узнать стоимость перевозки</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($landing as $k=>$v)
        @include("landing.$v->view")
    @endforeach

        <!-- MODAL: Terms and Conditions -->
        <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <h2 class="modal-title" id="modal-terms-label">Terms and Conditions</h2>
                    </div>
                    <div class="modal-body">
                        <p>Please read carefully the terms and conditions for using our product below:</p>
                        <h3>1. Dolor sit amet</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                        </p>
                        <ul>
                            <li>Easy To Use</li>
                            <li>Awesome Design</li>
                            <li>Cloud Based</li>
                        </ul>
                        <p>
                            Ut wisi enim ad minim veniam, <a href="http://azmind.com/premium/faby/v1-2/layout-3/index.html#">quis nostrud exerci tation</a> ullamcorper suscipit lobortis nisl ut aliquip ex ea
                            commodo consequat nostrud tation.
                        </p>
                        <h3>2. Sed do eiusmod</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                        </p>
                        <h3>3. Nostrud exerci tation</h3>
                        <p>
                            Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea
                            commodo consequat nostrud tation.
                        </p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">I Read it &amp; I Agree</button>
                    </div>
                </div>
            </div>
        </div>
@endsection