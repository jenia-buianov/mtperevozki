<?php

namespace App\Http\Controllers;

use App\Category;
use App\Companies;
use App\Landing;
use App\Language;
use App\Pages;
use App\PagesSitemap;
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
use App\Sitemap;
use App\TypesCargo;
use Illuminate\Http\Request;


class HomeController extends Controller
{

    private function sendCurl($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

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
                    'auto'=>[url('images/types/avto.jpg'),RemoteAutoTransport::whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->where('hidden',0)->count()],
                    'sea'=>[url('images/types/sea.jpg'),RemoteAutoTransport::where('type',15)->where('hidden',0)->count()],
                    'rails'=>[url('images/types/rail.jpg'),RemoteAutoTransport::where('type',44)->where('hidden',0)->count()],
                    'plain'=>[url('images/types/avia.jpg'),RemoteAutoTransport::where('type',43)->where('hidden',0)->count()],
                    'post'=>[url('images/types/posil.jpg'),RemotePostTransport::where('hidden',0)->count()],
                    'passengers'=>[url('images/types/auto.jpg'),RemotePassengersTransport::where('hidden',0)->count()],

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
