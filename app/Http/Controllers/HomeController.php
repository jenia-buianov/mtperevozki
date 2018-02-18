<?php

namespace App\Http\Controllers;

use App\Category;
use App\Landing;
use App\Language;
use App\RemoteAutoCargo;
use App\RemoteAutoTransport;
use App\RemoteCargoType;
use App\RemoteCargoVolume;
use App\RemoteCountry;
use App\RemotePassengersCargo;
use App\RemotePassengersTransport;
use App\RemotePostCargo;
use App\RemotePostTransport;
use App\RemoteTransportType;
use App\TypesCargo;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(Request $request){

        $autoTransportShowDays = 30;
        $name = 'country_name_'.app()->getLocale();

        $data = [
            'cargo'=>TypesCargo::orderBY('order')->get(),
            'categories'=>Category::orderBy('order')->get(),
            'auto_cargo'=>RemoteAutoCargo::where('hidden',0)->where('date_create','>=',date('Y-m-d',strtotime('-'.$autoTransportShowDays.' days')))->whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->orderBy('id','desc')->limit(10)->get(),
            'auto_transport'=>RemoteAutoTransport::where('hidden',0)->where('date_create','>=',date('Y-m-d',strtotime('-'.$autoTransportShowDays.' days')))->whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->orderBy('id','desc')->limit(10)->get(),
            'transport_type'=>RemoteTransportType::where('transport_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_type'=>RemoteCargoType::where('cargo_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_volume'=>RemoteCargoVolume::where('cargo_volume_hidden',0)->get(),
            'statistics'=>[
                'cargo'=>[
                    'auto'=>[url('images/types/avto.jpg'),RemoteAutoCargo::whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->where('hidden',0)->count()],
                    'sea'=>[url('images/types/sea.jpg'),RemoteAutoCargo::where('type',15)->where('hidden',0)->count()],
                    'rails'=>[url('images/types/rail.jpg'),RemoteAutoCargo::where('type',44)->where('hidden',0)->count()],
                    'plain'=>[url('images/types/avia.jpg'),RemoteAutoCargo::where('type',43)->where('hidden',0)->count()],
                    'post'=>[url('images/types/posil.jpg'),RemotePostCargo::where('hidden',0)->count()],
                    'passengers'=>[url('images/types/auto.jpg'),RemotePassengersCargo::where('hidden',0)->count()],

                ],
                'transport'=>[
                    'auto'=>['http://img.webme.com/pic/t/truck-driver-worldwide/peterbilt_truck.png',RemoteAutoTransport::whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->where('hidden',0)->count()],
                    'sea'=>['http://ntrans-container.ru/assets/template/images/slide2.png',RemoteAutoTransport::where('type',15)->where('hidden',0)->count()],
                    'rails'=>['https://bestpngimg.com/wp-content/uploads/2017/09/Diesel-Train.png',RemoteAutoTransport::where('type',44)->where('hidden',0)->count()],
                    'plain'=>['https://img-fotki.yandex.ru/get/176508/493212545.3/0_1bbd11_4a9d975a_L',RemoteAutoTransport::where('type',43)->where('hidden',0)->count()],
                    'post'=>['http://www.hazalambalaj.com/Images/Sayfa/Kurumsal/Basit/5311caeb8cb76.png',RemotePostTransport::where('hidden',0)->count()],
                    'passengers'=>['http://st30.stblizko.ru/images/product/188/451/132_original.png',RemotePassengersTransport::where('hidden',0)->count()],

                ]
            ],
            'landing'=>Landing::where('active',1)->orderBy('order','asc')->get(),
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale(),
            'page'=>'home'
        ];


        return view('home')->with($data)->render();

    }
}
