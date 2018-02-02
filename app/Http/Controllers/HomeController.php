<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\TypesCargo;
use Illuminate\Http\Request;
use Alexusmai\Ruslug\Slug;

class HomeController extends Controller
{
    public function index(){


        $data = [
            'cargo'=>TypesCargo::orderBY('order')->get(),
            'categories'=>Category::orderBy('order')->get()
        ];

        return view('home')->with($data)->render();

    }
}
