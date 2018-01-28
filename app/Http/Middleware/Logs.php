<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request as R;
use Jenssegers\Agent\Agent;
use App;
use Illuminate\Support\Facades\Cookie;


class Logs
{
    public function handle($request, Closure $next)
    {
        $this->logs($request);
        return $next($request);
    }

    private function logs(R $request){


        $agent = new Agent();
        $reqeust = Request();


        $array = ['city'=>'','country'=>'','latitue'=>'','long'=>'','country_code'=>'','ip'=>$_SERVER['REMOTE_ADDR'],'page'=>$reqeust->fullurl(),'method'=>$reqeust->getMethod(),'post'=>'','os'=>'','browser'=>''];

        $logsInfo = DB::table('logs')->select('city','country','latitue','long','country_code')->where('ip',$_SERVER['REMOTE_ADDR'])->orderBy('id','desc')->first();
        $array['os'] = $agent->platform();
        $array['browser'] = $agent->device().' '.$agent->browser();

        if (!empty($_POST)) $array['post'] = json_encode($_POST);
        else $array['post'] = '';
        if(!count($logsInfo)){

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
            $resp = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($resp->status=='success'){
                $array['city'] = $resp->city;
                $array['country'] = $resp->country;
                $array['latitue'] = $resp->lat;
                $array['long'] = $resp->lon;
                $array['country_code'] = $resp->countryCode;
            }

        }
        else{
            $array['city'] = $logsInfo->city;
            $array['country'] = $logsInfo->country;
            $array['latitue'] = $logsInfo->latitue;
            $array['long'] = $logsInfo->long;
            $array['country_code'] = $logsInfo->country_code;

        }
        $lang = '';
        if(!is_null($request->cookie('lang'))){
            $array['lang'] = $request->cookie('lang');
        }else{
            $langs = DB::table('languages')->select('code')->where('active',1)->get();
            $i = 0;
            while(empty($lang)&&$i<count($langs)){
                if (in_array($langs[$i]->code,$agent->languages()))
                    $lang = $langs[$i]->code;
                $i++;
            }

            if (empty($lang)){
                $lang = DB::table('languages')->select('code')->where('active',1)->where('default',1)->first()->code;
            }
            $array['lang'] = $lang;
            Cookie::queue('lang', $lang, 365*60*24);
        }

        App::setLocale($array['lang']);

        DB::table('logs')->insert($array);

    }
}
