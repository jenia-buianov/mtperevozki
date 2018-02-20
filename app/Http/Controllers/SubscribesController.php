<?php

namespace App\Http\Controllers;

use App\Subscribes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\RemoteCountry;
use App\RemoteCargoType;
use App\RemoteTransportType;
use App\RemoteCargoVolume;

class SubscribesController extends Controller
{
    public function dismiss(Request $request){
        $id = (int)decrypt($request->id);
        $subscribe = Subscribes::find($id);
        if (!$subscribe){
            return abort(404);
        }
        $user = User::find($subscribe->user_id);
        if (!$user)
            return abort(404);
        $name = 'country_name_'.app()->getLocale();
        if (!$user->confirmed){
            return redirect(route('login'))->with([
                'warning'=>translate('not_confirmed'),
                'transport_type'=>RemoteTransportType::where('transport_type_hidden',0)->orderBy('order','asc')->get(),
                'cargo_type'=>RemoteCargoType::where('cargo_type_hidden',0)->orderBy('order','asc')->get(),
                'cargo_volume'=>RemoteCargoVolume::where('cargo_volume_hidden',0)->get(),
                'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
                'country_name'=>$name,
                'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
                'transport_name'=>'transport_type_'.app()->getLocale(),
                'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
                'lang'=>app()->getLocale()

            ]);
        }
        $subscribe->active=0;
        $subscribe->save();

        Auth::logout();
        Auth::loginUsingId($subscribe->user_id);
        return redirect(route('subscribes',['lang'=>app()->getLocale()]));
    }

    public function index(Request $request){
        $name = 'country_name_'.app()->getLocale();
        return view('subscribes.index')->with([
            'transport_type'=>RemoteTransportType::where('transport_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_type'=>RemoteCargoType::where('cargo_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_volume'=>RemoteCargoVolume::where('cargo_volume_hidden',0)->get(),
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale(),
            'subscribes'=>Subscribes::where('user_id',Auth::user()->id)->orderBy('active','desc')->orderBy('created_at','desc')->get()
        ]);
    }

    public function enable(Request $request){
        $id = (int)decrypt($request->id);
        $subscribe = Subscribes::find($id);
        if (!$subscribe){
            return abort(404);
        }
        $user = User::find($subscribe->user_id);
        if (!$user)
            return abort(404);
        $subscribe->active = 1;
        $subscribe->save();
        return redirect(route('subscribes',['lang'=>app()->getLocale()]));
    }
}
