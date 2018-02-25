<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\AdminMenu;
use Illuminate\Support\Facades\Auth;
use App\Modules;
use App\Http\Controllers\Controller;
use DB;
use App\Logs;
use App\PermissionsPage;

class AdminChartController extends Controller
{
    public  function  index(Request $request){

        $hour = explode(':',DB::table('logs')->select(DB::raw('CURTIME() as `hour`'))->first()->hour);
        $hour = $hour[0];

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey]
            ],
            'logs'=>Logs::orderBy('id','desc')->get(),
            'statistic'=>[
                'users'=>[
                    'hour'=>Logs::where('created_at','like','%'.date('Y-m-d '.$hour).'%')->count(DB::raw('DISTINCT `ip`')),
                    'today'=>Logs::whereDate('created_at',DB::raw('CURDATE()'))->count(DB::raw('DISTINCT `ip`')),
                    'yesterday'=>Logs::whereDate('created_at',date('Y-m-d',strtotime('-1 day')))->count(DB::raw('DISTINCT `ip`')),
                    'week'=>Logs::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->count(DB::raw('DISTINCT `ip`')),
                    'month'=>Logs::whereDate('created_at','>=',date('Y-m-01'))->count(DB::raw('DISTINCT `ip`')),
                    'total'=>Logs::count(DB::raw('DISTINCT `ip`')),
                ],
                'visits'=>[
                    'hour'=>Logs::where('created_at','like','%'.date('Y-m-d '.$hour).'%')->count('id'),
                    'today'=>Logs::whereDate('created_at',DB::raw('CURDATE()'))->count('id'),
                    'yesterday'=>Logs::whereDate('created_at',date('Y-m-d',strtotime('-1 day')))->count('id'),
                    'week'=>Logs::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->count('id'),
                    'month'=>Logs::whereDate('created_at','>=',date('Y-m-01'))->count('id'),
                    'total'=>Logs::count('id'),
                ]
            ]
        ];
        for ($i=0;$i<24;$i++){
            if ($i<10) $i='0'.$i;
            $next = $i+1;
            if ($next<10) $next = '0'.$next;
            $count = Logs::where('created_at','like','%'.date('Y-m-d '.$i).'%')->count(DB::raw('DISTINCT `ip`'));
            if ($count>0)
                $data['users_today'][$i.':00 - '.$next.':00'] = $count;
        }
        for ($i=0;$i<24;$i++){
            if ($i<10) $i='0'.$i;
            $next = $i+1;
            if ($next<10) $next = '0'.$next;
            $count = Logs::where('created_at','like','%'.date('Y-m-d '.$i).'%')->count('id');
            if ($count>0)
                $data['visits_today'][$i.':00 - '.$next.':00'] = $count;
        }

        $data['users_month'] = [];
        for ($i=0;$i<date('d');$i++){
            if ($i<10) $i = '0'.$i;
            $data['users_month'][date('Y-m-'.$i)] = Logs::where('created_at','like','%'.date('Y-m-'.$i).'%')->count(DB::raw('DISTINCT `ip`'));
        }
        $data['visits_month'] = [];
        for ($i=0;$i<date('d');$i++){
            if ($i<10) $i = '0'.$i;
            $data['visits_month'][date('Y-m-'.$i)] = Logs::where('created_at','like','%'.date('Y-m-'.$i).'%')->count('id');
        }
        $countriesThisWeek = Logs::select(DB::raw('DISTINCT city'))->whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->get();
        $data['cities_users_week'] = [];
        foreach ($countriesThisWeek as $k=>$v){
            $data['cities_users_week'][$v->city] = Logs::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->where('city',$v->city)->count(DB::raw('DISTINCT `ip`'));
        }
        $diffCountriesThisWeek = Logs::select(DB::raw('DISTINCT country_code'))->whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->get();
        $data['different_countries'] = [];
        foreach ($diffCountriesThisWeek as $k=>$v){
            $data['different_countries'][$v->country_code] = [];
            foreach (Logs::select(DB::raw('DISTINCT city'))->whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->where('country_code',$v->country_code)->get() as $i=>$value){
                $data['different_countries'][$v->country_code][$value->city] = Logs::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->where('city',$value->city)->count(DB::raw('DISTINCT `ip`'));
            }
        }


        return view('admin.logs')->with($data)->render();
    }
}
