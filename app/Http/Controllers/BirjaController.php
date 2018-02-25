<?php

namespace App\Http\Controllers;

use App\RemoteCargoType;
use App\RemoteCountry;
use App\RemoteTransportType;
use Illuminate\Http\Request;
use App\RemoteCargoVolume;


class BirjaController extends Controller
{
    private $showOnPage = 50;

    public function openTransport(Request $request){

        if (isset($request->import)) $import = (int)$request->import;
        if (isset($request->export)) $export = (int)$request->export;
        if (isset($request->transport)) $transport_type = (int)$request->transport;

        $name = 'country_name_'.app()->getLocale();
        $data = [
            'content'=>['metatitle'=>'Поиск транспорта','metakey'=>'','metadesc'=>''],
            'transport_type'=>RemoteTransportType::where('transport_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_type'=>RemoteCargoType::where('cargo_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_volume'=>RemoteCargoVolume::where('cargo_volume_hidden',0)->get(),
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale()
        ];


        if (isset($export)){
            $data['content']['metatitle'].=' из '.RemoteCountry::where('id_country',$export)->first()->country_name_ru_from;
        }

        if (isset($import)){
            $data['content']['metatitle'].=' в '.RemoteCountry::where('id_country',$import)->first()->country_name_ru_to;
        }

        if (isset($transport_type)){
            $data['content']['metatitle'].='. '.RemoteTransportType::where('id',$transport_type)->first()->transport_type_ru;
        }

        $data['content']['metatitle'].='. Биржа транспорта и грузов.';
        $data['content']['metakey'] = implode(',',explode(' ',implode(explode('.',$data['content']['metatitle']))));

        return view('birja.transport',$data)->render();


    }
}
