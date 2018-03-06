<?php

namespace App\Http\Controllers\Admin;

use App\Groups;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminMenu;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\PermissionsPage;
use DB;
use App\Emails;
use App\Additional\SendEmail;
use Validator;
use App\Companies;

class AdminCompaniesController extends Controller
{
    public  function  index(Request $request){

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey]
            ],
            'companies'=>Companies::orderBy('title')->paginate(100)
        ];


        return view('admin.companies')->with($data)->render();
    }
}
