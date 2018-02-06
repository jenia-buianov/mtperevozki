<div class="how-it-works-container section-container section-container-image-bg" style="padding-bottom: 0px;">
    <div class="black-holder-3">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2>Актуальные предложения транспорта</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 more-features-box" data-aos="fade-up">
                    <div class="table-responsive white-holder-8">
                        <div id="myHeader" class="table_header">
                            <table class="table">
                                <tr>
                                    <th style="text-align: center">#</th>
                                    <th>Откуда</th>
                                    <th>Куда</th>
                                    <th style="text-align: center">Тип транспорта</th>
                                    <th style="text-align: center">Объем/вес</th>
                                    <th style="text-align: center">Дата</th>
                                </tr>
                            </table>
                        </div>
                        <table class="table table-hover" align="left">
                            <thead>
                            <tr>
                                <th style="text-align: center">#</th>
                                <th>Откуда</th>
                                <th>Куда</th>
                                <th style="text-align: center">Тип транспорта</th>
                                <th style="text-align: center">Объем/вес</th>
                                <th style="text-align: center">Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($auto_transport as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td align="left">
                                        <img src="{{url('images/flags/flat/24/'.$v->export_flag().'.png')}}" width="24" height="24">
                                        <?=$v->export()?>
                                    </td>
                                    <td align="left">
                                        <img src="{{url('images/flags/flat/24/'.$v->import_flag().'.png')}}" width="24" height="24">
                                        <?=$v->import()?>
                                    </td>
                                    <td>
                                        {{$v->transport_type()}}
                                    </td>
                                    <td>
                                        {{$v->volume()}}
                                    </td>
                                    <td>
                                        {{$v->date_from}}<br>
                                        {{$v->date_to}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>