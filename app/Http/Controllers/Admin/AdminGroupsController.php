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

class AdminGroupsController extends Controller
{

    private function validateForm($data){
        return Validator::make($data,[
            'titleKey' => 'required|string|max:255',
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
            'groups'=>Groups::get()
        ];

        return view('admin.groups')->with($data)->render();
    }

    public function add(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/groups');
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
            'url'=>url('admin/groups/adding'),
            'permissions'=>Permissions::get()
        ];

        return view('admin.groups_form')->with($data)->render();
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
        $group = Groups::create(['titleKey'=>$input['titleKey']]);
        Translations::create(['key'=>$input['titleKey'],'text'=>$input['titleKey'],'lang'=>app()->getLocale()]);
        if(isset($input['permissions'])&&!empty($input['permissions'])) {
            foreach ($input['permissions'] as $k => $v)
                PermissionsGroups::create(['group_id' => $group->id, 'permission_id' => $v]);
        }
        return json_encode(['js'=>'toastr["success"]("'.translate('added').'");$("form")[0].reset()']);
    }

    public function edit(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/groups');
        }

        $page = Groups::find((int)$request->id);
        if (!$page){
            return redirect('/admin/groups');
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
            'url'=>url('admin/groups/editing'),
            'group'=>$page,
            'permissions'=>Permissions::get()
        ];

        return view('admin.groups_form')->with($data)->render();
    }


    public function editing(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/groups');
        }
        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $id = (int)$url[count($url)-1];
        $group = Groups::find($id);
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

        Translations::where('key',$group->titleKey)->where('lang',app()->getLocale())->update(['text'=>htmlspecialchars($input['titleKey'],3)]);
        PermissionsGroups::where('group_id',$group->id)->delete();
        if(isset($input['permissions'])&&!empty($input['permissions'])) {
           foreach ($input['permissions'] as $k => $v)
               PermissionsGroups::create(['group_id' => $group->id, 'permission_id' => $v]);
        }
        return json_encode(['js'=>'toastr["success"]("'.translate('saved').'");']);



    }

}
