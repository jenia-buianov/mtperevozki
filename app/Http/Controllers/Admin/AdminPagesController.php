<?php

namespace App\Http\Controllers\Admin;

use App\Forms;
use App\Language;
use App\Languages;
use App\PagesSitemap;
use App\Permisions;
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

class AdminPagesController extends Controller
{

    private function parentPages($id = 0){

        $pages = [];
        foreach (Sitemap::where('parent',0)->where('id','<>',$id)->get() as $k=>$v){
            $pages[] = [
                'title'=>$v->title,
                'id'=>$v->id,
                'children'=>$this->childrenPages($v->id,$id)
            ];
        }
        return $pages;
    }

    private function childrenPages($id, $not = 0){
        $pages = [];
        foreach (Sitemap::where('parent',$id)->where('id','<>',$not)->get() as $k=>$v){
            $pages[] = [
                'title'=>$v->title,
                'id'=>$v->id,
                'children'=>$this->childrenPages($v->id,$id)
            ];
        }
        return $pages;
    }

    private function validateForm($data){
        return Validator::make($data,[
            'url_' => 'required|string|max:255|unique:mysql.pages',
            'sitemap' => 'required|numeric',
            'title_ru' => 'required|string',
            'metatitle_ru' => 'required|string',
            'metakey_ru' => 'required|string',
            'metadesc_ru' => 'required|string',
            'text_ru' => 'required',
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
            'pages'=>Pages::orderBy('created_at','desc')->get(),
            'new_today'=>Pages::whereDate('created_at',DB::raw('CURDATE()'))->count('id'),
            'new_week'=>Pages::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->count('id'),
            'new_month'=>Pages::whereDate('created_at','>=',date('Y-m-01'))->count('id')
        ];

        return view('admin.pages')->with($data)->render();
    }

    public function add(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/pages');
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
            'langs'=>Language::where('active',1)->get(),
            'sitemap'=>$this->parentPages(),
            'forms'=>Forms::select('title','key')->get(),
            'url'=>url('admin/pages/adding'),
        ];

        return view('admin.pages_form')->with($data)->render();
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
        $input = $request->except(['_token','sitemap']);
        $input['url'] = '/ru/'.$input['url_'];
        $input['title'] = $input['title_ru'];
        $input['metatitle'] = $input['metatitle_ru'];
        $input['metadesc'] = $input['metadesc_ru'];
        $input['metakey'] = $input['metakey_ru'];
        $input['content'] = $input['text_ru'];
        unset($input['url_']);
        unset($input['metatitle_ru']);
        unset($input['metadesc_ru']);
        unset($input['metakey_ru']);
        unset($input['title_ru']);
        unset($input['text_ru']);
        $page = Pages::create($input);
        $sitemap = Sitemap::create(['url'=>$input['url'],'title'=>$input['title'],'parent'=>$request->sitemap]);
        $sitemap_pages = PagesSitemap::create(['page_id'=>$page->id,'sitemap_id'=>$sitemap->id]);
        return json_encode(['js'=>'toastr["success"]("'.translate('added').'");$("form")[0].reset()']);
    }

    public function edit(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/pages');
        }

        $page = Pages::find((int)$request->id);
        if (!$page){
            return redirect('/admin/pages');
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
            'langs'=>Language::where('active',1)->get(),
            'sitemap'=>$this->parentPages($page->sitemap()->id),
            'forms'=>Forms::select('title','key')->get(),
            'url'=>url('admin/pages/editing'),
            'page'=>$page
        ];

        return view('admin.pages_form')->with($data)->render();
    }


    public function editing(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/pages');
        }
        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $id = (int)$url[count($url)-1];
        $input = $request->input();


        $validator = Validator::make($request->except(['_token']),[
            'url_' => 'required|string',
            'sitemap' => 'required|numeric',
            'title_ru' => 'required|string',
            'metatitle_ru' => 'required|string',
            'metakey_ru' => 'required|string',
            'metadesc_ru' => 'required|string',
            'text_ru' => 'required',
        ],[
            'required'=>'Укажите поле :attribute',
            'max'=>'Максимальная длина :attribute :max',
        ]);;
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode(['js'=>'toastr["error"]("'.implode(', ',$errors).'");']);
        }
        $input = $request->except(['_token','sitemap']);
        $input['url'] = $input['url_'];
        $input['title'] = $input['title_ru'];
        $input['metatitle'] = $input['metatitle_ru'];
        $input['metadesc'] = $input['metadesc_ru'];
        $input['metakey'] = $input['metakey_ru'];
        $input['content'] = $input['text_ru'];
        unset($input['url_']);
        unset($input['metatitle_ru']);
        unset($input['metadesc_ru']);
        unset($input['metakey_ru']);
        unset($input['title_ru']);
        unset($input['text_ru']);
        Pages::where('id',$id)->update($input);
        $sitemap = PagesSitemap::where('page_id',$id)->first();
        if ($sitemap) {
            $sitemap->sitemap->title = $input['title'];
            $sitemap->sitemap->url = $input['url'];
            $sitemap->sitemap->parent = $request->sitemap;
            $sitemap->save();
        }
        return json_encode(['js'=>'toastr["success"]("'.translate('saved').'");$("form")[0].reset()']);



    }

}
