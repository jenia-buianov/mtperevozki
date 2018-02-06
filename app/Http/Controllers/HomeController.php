<?php

namespace App\Http\Controllers;

use App\Category;
use App\Landing;
use App\RemoteAutoCargo;
use App\RemoteAutoTransport;
use App\RemotePassengersCargo;
use App\RemotePassengersTransport;
use App\RemotePostCargo;
use App\RemotePostTransport;
use App\TypesCargo;

class HomeController extends Controller
{
    public function index(){

        $autoTransportShowDays = 30;

        $data = [
            'cargo'=>TypesCargo::orderBY('order')->get(),
            'categories'=>Category::orderBy('order')->get(),
            'auto_transport'=>RemoteAutoTransport::where('hidden',0)->where('date_create','>=',date('Y-m-d',strtotime('-'.$autoTransportShowDays.' days')))->whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->orderBy('id','desc')->limit(15)->get(),
            'statistics'=>[
                'auto'=>[
                    'cargo'=>RemoteAutoCargo::whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->where('hidden',0)->count(),
                    'transport'=>RemoteAutoTransport::whereIn('type',[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45])->where('hidden',0)->count()
                ],
                'plain'=>[
                    'cargo'=>RemoteAutoCargo::where('type',43)->where('hidden',0)->count(),
                    'transport'=>RemoteAutoTransport::where('type',43)->where('hidden',0)->count()
                ],
                'passengers'=>[
                    'cargo'=>RemotePassengersCargo::where('hidden',0)->count(),
                    'transport'=>RemotePassengersTransport::where('hidden',0)->count()
                ],
                'sea'=>[
                    'cargo'=>RemoteAutoCargo::where('type',15)->where('hidden',0)->count(),
                    'transport'=>RemoteAutoTransport::where('type',15)->where('hidden',0)->count()
                ],
                'rails'=>[
                    'cargo'=>RemoteAutoCargo::where('type',44)->where('hidden',0)->count(),
                    'transport'=>RemoteAutoTransport::where('type',44)->where('hidden',0)->count()
                ],
                'post'=>[
                    'cargo'=>RemotePostCargo::where('hidden',0)->count(),
                    'transport'=>RemotePostTransport::where('hidden',0)->count()
                ]
            ],
            'landing'=>Landing::where('active',1)->orderBy('order','asc')->get()
        ];


        return view('home')->with($data)->render();

    }
}
