<?php

namespace App\Http\Controllers\Admin;

use App\Forms;
use App\Groups;
use App\Language;
use App\Languages;
use App\PagesSitemap;
use App\Permisions;
use App\Permissions;
use App\PermissionsGroups;
use App\PermissionsPage;
use App\Sitemap;
use App\Translations;
use Illuminate\Http\Request;
use App\AdminMenu;
use Illuminate\Support\Facades\Auth;
use App\Pages;
use App\Modules;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\App;
use Validator;

class AdminTranslationsController extends Controller
{

    private function validateForm($data){
        return Validator::make($data,[
            'key' => 'required|string|max:255'
        ],[
            'required'=>'Укажите поле :attribute',
            'max'=>'Максимальная длина :attribute :max',
        ]);
    }

    public  function  index(Request $request){

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey]
            ],
            'translations'=>Translations::get()
        ];

        return view('admin.translations')->with($data)->render();
    }

    public function add(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/translations');
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
            'url'=>url('admin/translations/adding'),
            'langs'=>Language::where('active',1)->get(),
        ];

        return view('admin.translations_form')->with($data)->render();
    }

    public function adding(Request $request){


        $validator = $this->validateForm($request->except(['_token']));
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode(['js'=>'toastr["error"]("'.implode(', ',$errors).'");']);
        }
        $input = $request->except(['_token']);
        $lang = Language::where('active',1)->get();
        foreach ($lang as $k=>$v){
            Translations::create(['key'=>htmlspecialchars($input['key'],3),'text'=>htmlspecialchars($input['text_'.$v->code],3),'lang'=>$v->code]);
        }
        return json_encode(['js'=>'toastr["success"]("'.translate('added').'");$("form")[0].reset()']);
    }

    public function edit(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/translations');
        }

        $page = Translations::find((int)$request->id);
        if (!$page){
            return redirect('/admin/translations');
        }

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey],
                ['link'=>url('admin/'.$request->module.'/'.$request->action.'/'.$request->id),'title'=>PermissionsPage::where('url','/admin/'.$request->module.'/'.$request->action)->first()->permission->titleKey]
            ],
            'pagetitle'=>PermissionsPage::where('url','/admin/'.$request->module.'/'.$request->action)->first()->permission->titleKey,
            'url'=>url('admin/translations/editing'),
            'translation'=>$page,
            'langs'=>Language::where('active',1)->get(),
            'all_translations'=>Translations::where('key',$page->key)->get()
        ];

        return view('admin.translations_form')->with($data)->render();
    }


    public function editing(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/translations');
        }
        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $id = (int)$url[count($url)-1];
        $group = Translations::find($id);
        if (!$group){
            return json_encode(['js'=>'toastr["error"]("Not found group");']);
        }
        $validator = $this->validateForm($request->except(['_token']));
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode(['js'=>'toastr["error"]("'.implode(', ',$errors).'");']);
        }
        $input = $request->except(['_token']);

        $lang = Language::where('active',1)->get();
        foreach ($lang as $k=>$v){
            $tr = Translations::where('lang',$v->code)->where('key',$group->key)->first();
            if (!$tr)
                Translations::create(['key'=>htmlspecialchars($input['key'],3),'text'=>htmlspecialchars($input['text_'.$v->code],3),'lang'=>$v->code]);
            else{
                $tr->key = htmlspecialchars($input['key'],3);
                $tr->text = htmlspecialchars($input['text_'.$v->code],3);
                $tr->save();
            }
        }

        return json_encode(['js'=>'toastr["success"]("'.translate('saved').'");']);



    }

}
