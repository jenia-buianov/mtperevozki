<?php

namespace App\Http\Controllers;

use App\RemoteAutoTransport;
use App\RemoteCargoType;
use App\RemoteCountry;
use App\RemoteTransportType;
use Illuminate\Http\Request;
use App\RemoteCargoVolume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BirjaController extends Controller
{
    private $showOnPage = 50;

    public function openTransport(Request $request){
//        DB::enableQueryLog();
        $prefixes = ['auto','sea','rails','passengers','post','plain'];
        $prefixes_class = ['auto'=>'Auto','sea'=>'Auto','rails'=>'Auto','plain'=>'Auto','passengers'=>'Passengers','post'=>'Post'];
        $prefix_model_class = ['auto'=>'','sea'=>'','rails'=>'','plain'=>'','passengers'=>'Passengers','post'=>'Post'];
        $prefix_categories = [
            'auto'=>  [2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45],
            'sea' => [15],
            'rails' => [44],
            'plain' => [43],
            'passengers' => '',
            'post' => ''
        ];
        $prefix_h1 = [
            'auto'=>'авто транспорта',
            'sea'=>'морского транспорта',
            'rails'=>'железнодорожного транспорта',
            'plain'=>'авиа транспорта',
            'passengers'=>'пассажирского транспорта',
            'post'=>'транспорта для посылок'
        ];

        $menus = [
            'auto'=>'авто перевозки',
            'sea'=>'морские перевозки',
            'plain'=>'авиа перевозки',
            'rails'=>'ж.д. перевозки',
            'passengers'=>'пассажирские перевозки',
            'post'=>'Доставка посылок'
        ];

        if (!isset($request->tr)||empty($request->tr)){
            $query = RemoteAutoTransport::where('hidden',0);
        }
//        dd($request->tr);
        if (isset($request->tr)&&!empty($request->tr)){
            $prefix = explode('_',htmlspecialchars($request->tr,3));
            if (!in_array($prefix[0],$prefixes)){
                abort(404);
            }
            $class = "App\Remote".$prefixes_class[$prefix[0]]."Transport";
            $query = $class::where('hidden',0);
            if (!empty($prefix_categories[$prefix[0]])){
                $query->whereIn('type',$prefix_categories[$prefix[0]]);
            }
            $prefix = $prefix[0];
        }else $prefix = 'auto';

        if (isset($request->import)&&!empty($request->import)) {
            $import = (int)$request->import;
            $query->where('import',$import);
        }
        if (isset($request->export)&&!empty($request->export)) {
            $export = (int)$request->export;
            $query->where('export',$export);
        }
        if (isset($request->transport)&&!empty($request->transport)) {
            $transport_type = (int)$request->transport;
            $query->where('type',$transport_type);
        }
        if (isset($request->volume)&&!empty($request->volume)) {
            $volume = (int)$request->volume;
            $query->where('volume',$volume);
        }

            if (isset($request->date_from) && !empty($request->date_from)) {
                $date_from = htmlspecialchars($request->date_from, 3);
//                $query->where('date_from', '>=', $date_from);
                $query->whereBetween('date_from', [date('d-m-Y',strtotime($date_from.' -1 year')),date('d-m-Y',strtotime($date_from.' +1 year'))]);
            }
            if (isset($request->date_to) && !empty($request->date_to)) {
                $date_to = htmlspecialchars($request->date_to, 3);
                $query->whereBetween('date_to', [$date_to,date('d-m-Y',strtotime($date_to.' +1 year'))]);
//                $query->where(DB::raw('UNIX_TIMESTAMP(DATE_FORMAT(date_to, "%Y-%m-%d"))'), '>=', strtotime($date_to));
            }

        $class = "App\Remote".$prefix_model_class[$prefix]."TransportType";
        $transport = $class::where('transport_type_hidden',0);
        if (in_array($prefix,['auto','plain','rails','sea'])){
            $transport->where('split',1);
        }

        $transport = $transport->orderBy('transport_type_group','asc')->orderBy('order','asc')->get();
        $class = "App\Remote".$prefix_model_class[$prefix]."CargoType";
        $cargo_type = $class::where('cargo_type_hidden',0)->orderBy('order','asc')->get();
        $class = "App\Remote".$prefix_model_class[$prefix]."CargoVolume";
        $cargo_volume = $class::where('cargo_volume_hidden',0);
        if (in_array($prefix,['auto','plain','rails','sea'])){
            $cargo_volume->where('split',1);
        }
        $cargo_volume = $cargo_volume->get();
        $name = 'country_name_'.app()->getLocale();
//        dd($query->count());
        $data = [
            'content'=>['metatitle'=>'Поиск '.$prefix_h1[$prefix],'metakey'=>'','metadesc'=>'','h1'=>'Поиск '.$prefix_h1[$prefix]],
            'transport_type'=>$transport,
            'cargo_type'=>$cargo_type,
            'cargo_volume'=>$cargo_volume,
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale(),
            'prefix'=>$prefix,
            'search_count'=>$query->count(),
            'search'=>$query->orderBy('id','desc')->paginate($this->showOnPage),
            'menus'=>$menus
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

    public function openCargo(Request $request){
//        DB::enableQueryLog();


        $prefixes = ['auto','sea','rails','passengers','post','plain'];
        $prefixes_class = ['auto'=>'Auto','sea'=>'Auto','rails'=>'Auto','plain'=>'Auto','passengers'=>'Passengers','post'=>'Post'];
        $prefix_model_class = ['auto'=>'','sea'=>'','rails'=>'','plain'=>'','passengers'=>'Passengers','post'=>'Post'];
        $prefix_categories = [
            'auto'=>  [2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45],
            'sea' => [15],
            'rails' => [44],
            'plain' => [43],
            'passengers' => '',
            'post' => ''
        ];
        $prefix_h1 = [
            'auto'=>'авто грузов',
            'sea'=>'морских грузов',
            'rails'=>'железнодорожных грузов',
            'plain'=>'авиа грузов',
            'passengers'=>'пассажирских грузов',
            'post'=>'посылок'
        ];

        $menus = [
            'auto'=>'авто перевозки',
            'sea'=>'морские перевозки',
            'plain'=>'авиа перевозки',
            'rails'=>'ж.д. перевозки',
            'passengers'=>'пассажирские перевозки',
            'post'=>'доставка посылок'
        ];

        if (!isset($request->tr)||empty($request->tr)){
            $query = RemoteAutoTransport::where('hidden',0);
        }
//        dd($request->tr);
        if (isset($request->tr)&&!empty($request->tr)){
            $prefix = explode('_',htmlspecialchars($request->tr,3));
            if (!in_array($prefix[0],$prefixes)){
                abort(404);
            }
            $class = "App\Remote".$prefixes_class[$prefix[0]]."Cargo";
            $query = $class::where('hidden',0);
            if (!empty($prefix_categories[$prefix[0]])){
                $query->whereIn('type',$prefix_categories[$prefix[0]]);
            }
            $prefix = $prefix[0];
        }else $prefix = 'auto';

        if (isset($request->import)&&!empty($request->import)) {
            $import = (int)$request->import;
            $query->where('import',$import);
        }
        if (isset($request->export)&&!empty($request->export)) {
            $export = (int)$request->export;
            $query->where('export',$export);
        }
        if (isset($request->transport)&&!empty($request->transport)) {
            $transport_type = (int)$request->transport;
            $query->where('type',$transport_type);
        }
        if (isset($request->volume)&&!empty($request->volume)) {
            $volume = (int)$request->volume;
            $query->where('volume',$volume);
        }

        if (isset($request->date_from) && !empty($request->date_from)) {
            $date_from = htmlspecialchars($request->date_from, 3);
//                $query->where('date_from', '>=', $date_from);
            $query->whereBetween('date_from', [date('d-m-Y',strtotime($date_from.' -1 year')),date('d-m-Y',strtotime($date_from.' +1 year'))]);
        }
        if (isset($request->date_to) && !empty($request->date_to)) {
            $date_to = htmlspecialchars($request->date_to, 3);
            $query->whereBetween('date_to', [$date_to,date('d-m-Y',strtotime($date_to.' +1 year'))]);
//                $query->where(DB::raw('UNIX_TIMESTAMP(DATE_FORMAT(date_to, "%Y-%m-%d"))'), '>=', strtotime($date_to));
        }

        $class = "App\Remote".$prefix_model_class[$prefix]."TransportType";
        $transport = $class::where('transport_type_hidden',0);
        if (in_array($prefix,['auto','plain','rails','sea'])){
            $transport->where('split',1);
        }

        $transport = $transport->orderBy('transport_type_group','asc')->orderBy('order','asc')->get();
        $class = "App\Remote".$prefix_model_class[$prefix]."CargoType";
        $cargo_type = $class::where('cargo_type_hidden',0)->orderBy('order','asc')->get();
        $class = "App\Remote".$prefix_model_class[$prefix]."CargoVolume";
        $cargo_volume = $class::where('cargo_volume_hidden',0);
        if (in_array($prefix,['auto','plain','rails','sea'])){
            $cargo_volume->where('split',1);
        }
        $cargo_volume = $cargo_volume->get();
        $name = 'country_name_'.app()->getLocale();
//        dd($query->count());
        $data = [
            'content'=>['metatitle'=>'Поиск '.$prefix_h1[$prefix],'metakey'=>'','metadesc'=>'','h1'=>'Поиск '.$prefix_h1[$prefix]],
            'transport_type'=>$transport,
            'cargo_type'=>$cargo_type,
            'cargo_volume'=>$cargo_volume,
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale(),
            'prefix'=>$prefix,
            'search_count'=>$query->count(),
            'search'=>$query->orderBy('id','desc')->paginate($this->showOnPage),
            'menus'=>$menus
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
        return view('birja.cargo',$data)->render();












        $prefixes = ['auto','sea','rails','passengers','post','plain'];
        $prefixes_class = ['auto'=>'Auto','sea'=>'Auto','rails'=>'Auto','plain'=>'Auto','passengers'=>'Passengers','post'=>'Post'];
        $prefix_model_class = ['auto'=>'','sea'=>'','rails'=>'','plain'=>'','passengers'=>'Passengers','post'=>'Post'];
        $prefix_categories = [
            'auto'=>  [2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45],
            'sea' => [15],
            'rails' => [44],
            'plain' => [43],
            'passengers' => '',
            'post' => ''
        ];
        $prefix_h1 = [
            'auto'=>'авто грузов',
            'sea'=>'морских грузов',
            'rails'=>'железнодорожных грузов',
            'plain'=>'авиа грузов',
            'passengers'=>'пассажирских грузов',
            'post'=>'посылок'
        ];

        $menus = [
            'auto'=>'авто грузы',
            'sea'=>'морские грузы',
            'rails'=>'ж.д. грузы',
            'passengers'=>'пассажирские грузы',
            'post'=>'посылки'
        ];

        if (!isset($request->tr)||empty($request->tr)){
            $query = RemoteAutoTransport::where('hidden',0);
        }
//        dd($request->tr);
        if (isset($request->tr)&&!empty($request->tr)){
            $prefix = explode('_',htmlspecialchars($request->tr,3));
            if (!in_array($prefix[0],$prefixes)){
                abort(404);
            }
            $class = "App\Remote".$prefixes_class[$prefix[0]]."Cargo";
            $query = $class::where('hidden',0);
            if (!empty($prefix_categories[$prefix[0]])){
                $query->whereIn('type',$prefix_categories[$prefix[0]]);
            }
            $prefix = $prefix[0];
        }else $prefix = 'auto';

        if (isset($request->import)&&!empty($request->import)) {
            $import = (int)$request->import;
            $query->where('import',$import);
        }
        if (isset($request->export)&&!empty($request->export)) {
            $export = (int)$request->export;
            $query->where('export',$export);
        }
        if (isset($request->transport)&&!empty($request->transport)) {
            $transport_type = (int)$request->transport;
            $query->where('type',$transport_type);
        }
        if (isset($request->volume)&&!empty($request->volume)) {
            $volume = (int)$request->volume;
            $query->where('volume',$volume);
        }

        if (isset($request->date_from) && !empty($request->date_from)) {
            $date_from = htmlspecialchars($request->date_from, 3);
//                $query->where('date_from', '>=', $date_from);
            $query->whereBetween('date_from', [date('d-m-Y',strtotime($date_from.' -1 year')),date('d-m-Y',strtotime($date_from.' +1 year'))]);
        }
        if (isset($request->date_to) && !empty($request->date_to)) {
            $date_to = htmlspecialchars($request->date_to, 3);
            $query->whereBetween('date_to', [$date_to,date('d-m-Y',strtotime($date_to.' +1 year'))]);
//                $query->where(DB::raw('UNIX_TIMESTAMP(DATE_FORMAT(date_to, "%Y-%m-%d"))'), '>=', strtotime($date_to));
        }

        $i=0;
        foreach ($menus as $k=>$v){
            if ($k!==$prefix&&$i<2) $menus_order[$k] = $v;
            if ($k!==$prefix&&$i>1) $menus_order[$k] = $v;
            if ($i==2) $menus_order[$prefix] = $menus[$prefix];
            $i++;
        }
        $class = "App\Remote".$prefix_model_class[$prefix]."TransportType";
        $transport = $class::where('transport_type_hidden',0)->orderBy('order','asc')->get();
        $class = "App\Remote".$prefix_model_class[$prefix]."CargoType";
        $cargo_type = $class::where('cargo_type_hidden',0)->orderBy('order','asc')->get();
        $class = "App\Remote".$prefix_model_class[$prefix]."CargoVolume";
        $cargo_volume = $class::where('cargo_volume_hidden',0)->where('split',1)->get();
        $name = 'country_name_'.app()->getLocale();
        $data = [
            'content'=>['metatitle'=>'Поиск '.$prefix_h1[$prefix],'metakey'=>'','metadesc'=>'','h1'=>'Поиск '.$prefix_h1[$prefix]],
            'transport_type'=>$transport,
            'cargo_type'=>$cargo_type,
            'cargo_volume'=>$cargo_volume,
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale(),
            'prefix'=>$prefix,
            'search_count'=>$query->count(),
            'search'=>$query->orderBy('id','desc')->paginate($this->showOnPage),
            'menus'=>$menus_order
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
        return view('birja.cargo',$data)->render();
    }

    public function search(Request $request){
        $type = htmlspecialchars($request->type,3);
        $import = htmlspecialchars($request->country_import,3);
        $export = htmlspecialchars($request->country_export,3);
        $kind = htmlspecialchars($request->kind,3);
//        dd($_GET);
        return redirect(url(app()->getLocale().'/birja/'.$kind.$type.'?import='.$import.'&export='.$export));
    }
}
