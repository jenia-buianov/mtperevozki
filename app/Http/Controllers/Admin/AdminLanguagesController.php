<?php

namespace App\Http\Controllers\Admin;

use App\Translations;
use Illuminate\Http\Request;
use App\AdminMenu;
use Illuminate\Support\Facades\Auth;
use App\Modules;
use App\Http\Controllers\Controller;
use DB;
use App\Language;
use App\PermissionsPage;

class AdminLanguagesController extends Controller
{
    public  function  index(Request $request){

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey]
            ],
            'languages'=>Language::get(),
        ];

        return view('admin.languages')->with($data)->render();
    }

    public function check(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }
        if (empty($request->id)){
            return json_encode(['js'=>'toastr["error"]("Empty ID");']);
        }
        $lang = Language::find((int)decrypt($request->id));
        if ($lang->active==1) $lang->active = 0;
        else $lang->active = 1;
        $lang->save();
        return json_encode(['js'=>'document.location.reload(true);']);
    }



    public function add(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/languages');
        }

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey],
                ['link'=>url('admin/'.$request->module.'/'.$request->action),'title'=>PermissionsPage::where('url','/admin/'.$request->module.'/'.$request->action)->first()->permission->titleKey]
            ],
            'pagetitle'=>PermissionsPage::where('url','/admin/'.$request->module.'/'.$request->action)->first()->permission->titleKey,
            'url'=>url('admin/languages/adding')
        ];

        return view('admin.languages_add_form')->with($data)->render();
    }

    public function adding(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }

        $input = $request->input();
        unset($input['_token']);
        $input['active'] = 1;

        Language::create($input);
        return json_encode(['js'=>'toastr["success"]("'.translate('added').'");$("form")[0].reset()']);
    }

    public function edit(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/languages');
        }

        $lang = Language::find((int)$request->id);
        if (!$lang){
            return redirect('/admin/languages');
        }


        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey],
                ['link'=>url('admin/'.$request->module.'/'.$request->action),'title'=>PermissionsPage::where('url','/admin/'.$request->module.'/'.$request->action)->first()->permission->titleKey]
            ],
            'pagetitle'=>PermissionsPage::where('url','/admin/'.$request->module.'/'.$request->action)->first()->permission->titleKey,
            'lang'=>$lang,
            'url'=>url('admin/languages/editing')
        ];

        return view('admin.languages_add_form')->with($data)->render();
    }

    public function editing(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }

        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $id = (int)$url[count($url)-1];
        $lang = Language::find($id);
        if (!$lang){
            return redirect('/admin/languages');
        }

        $input = $request->input();
        unset($input['_token']);
        Language::where('id',$lang->id)->update($input);
        return json_encode(['js'=>'toastr["success"]("'.translate('saved').'");']);
    }

}
