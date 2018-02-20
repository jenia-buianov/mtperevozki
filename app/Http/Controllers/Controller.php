<?php

namespace App\Http\Controllers;

use App\Pages;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    public $page = null;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function __construct()
    {
        $request = Request();
        $this->page = Pages::where('url',$request->path())->first();
        $this->middleware('logs',array());

    }
}
