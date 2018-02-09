<div class="how-it-works-container section-container section-container-image-bg" style="padding-bottom: 30px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 more-features section-description">
                    <h2 style="color:#555;">{{translate('actual_cargo')}}</h2>
                    <div class="divider-1"><div class="line"></div></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 more-features-box" data-aos="fade-up">
                    <div class="table-responsive white-holder-10">
                        <table class="table table-hover" align="left">
                            <thead>
                            <tr>
                                <th>Откуда</th>
                                <th>Куда</th>
                                <th style="text-align: center">Тип транспорта</th>
                                <th style="text-align: center">Наименование груза</th>
                                <th style="text-align: center">Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($auto_cargo as $k=>$v)
                                <tr>
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
                                        {{$v->name()}}
                                    </td>
                                    <td>
                                        {{date('d.m.Y',strtotime($v->date))}}
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