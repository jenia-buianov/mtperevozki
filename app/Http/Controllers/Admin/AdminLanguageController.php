<?php

namespace App\Http\Controllers\Admin;

use App\Additional\WriteFile;
use App\Languages;
use App\Permisions;
use App\Translations;
use Illuminate\Http\Request;
use App\AdminMenu;
use Illuminate\Support\Facades\Auth;
use App\Modules;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\App;

class AdminLanguageController extends Controller
{
    public  function  index(Request $request){

        $data = [
            'left_Dmenu'=>AdminMenu::where('group_id',Auth::user()->group_id)->orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>Modules::where('link',$request->module)->first(),
            'languages'=>Languages::get(),
        ];
        $data['translations'] = [];
        foreach ($data['languages'] as $k=>$v){
            if ($v->active&&$v->active==1){
                $dir = opendir(__DIR__."/../../../../resources/lang/".$v->code."/");
                while($name = readdir($dir)) {
                    $translation = new WriteFile($v->code,$name);
                    $data['translations'] = array_merge($data['translations'],$translation->getKeysWithFilename());
                    $data['translations'] = array_unique($data['translations']);
                }
            }
        }

        return view('admin.languages')->with($data)->render();
    }

    public function enable(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }
        if (empty($request->id)){
            return json_encode(['js'=>'toastr["error"]("Empty ID");']);
        }
        $lang = Languages::find((int)$request->id);
        $lang->active = 1;
        $lang->save();
        return json_encode(['js'=>'document.location.reload(true);']);
    }

    public function disable(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }
        if (empty($request->id)){
            return json_encode(['js'=>'toastr["error"]("Empty ID");']);
        }
        $lang = Languages::find((int)$request->id);
        $lang->active = 0;
        $lang->save();
        return json_encode(['js'=>'document.location.reload(true);']);
    }

    public function addtranslation(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/languages');
        }

        $data = [
            'left_menu'=>AdminMenu::where('group_id',Auth::user()->group_id)->orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>Modules::where('link',$request->module)->first(),
            'langs'=>Languages::where('active',1)->get(),
            'url'=>url('admin/language/addingTranslation'),
            'permission'=>Permisions::where('url',$request->module.'/'.$request->action)->where('group_id',Auth::user()->group_id)->first()
        ];

        return view('admin.languages_form')->with($data)->render();
    }

    public function addingTranslation(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }

        $input = $request->input();
        unset($input['_token']);
        $t = new \Alexusmai\Ruslug\Slug;
        $title = $t->make(mb_substr(strip_tags($input['text_ru']),0,95));
        $textKey = $title.'_text';

        foreach (Languages::where('active',1)->get() as $k=>$v){
            $createText = ['key'=>$textKey,'text'=>htmlspecialchars($input['text_'.$v->code],3),'lang'=>$v->code,'file'=>'main'];
            Translations::create($createText);
            $writeToFile = new \App\Additional\WriteFile($v->code,'main.php');
            $writeToFile->add(["\t'$textKey'=>'".htmlspecialchars($input['text_'.$v->code],3)."',\n"]);
        }
        return json_encode(['js'=>'toastr["success"]("'.__('admin.added').'");$("form")[0].reset()']);
    }

    public function editTranslation(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/languages');
        }

        $data = [
            'left_menu'=>AdminMenu::where('group_id',Auth::user()->group_id)->orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>Modules::where('link',$request->module)->first(),
            'langs'=>Languages::where('active',1)->get(),
            'url'=>url('admin/language/editingTranslation'),
            'translation'=>htmlspecialchars($request->id,3),
            'permission'=>Permisions::where('url',$request->module.'/'.$request->action)->where('group_id',Auth::user()->group_id)->first()
        ];

        return view('admin.languages_form')->with($data)->render();
    }

    public function editingTranslation(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/languages');
        }
        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $id = $url[count($url)-1];
        $file = explode('.',$id);

        $input = $request->input();
        unset($input['_token']);


        foreach (Languages::where('active',1)->get() as $k=>$v){
            $writeToFile = new \App\Additional\WriteFile($v->code,$file[0].'.php');
            $writeToFile->changeValue($file[1],htmlspecialchars($input['text_'.$v->code],3));
        }
        return json_encode(['js'=>'toastr["success"]("'.__('admin.saved').'");$("form")[0].reset()']);
    }


    public function add(Request $request){
        if ($request->isMethod('post')){
            return redirect('admin/language');
        }

        $data = [
            'left_menu'=>AdminMenu::where('group_id',Auth::user()->group_id)->orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>Modules::where('link',$request->module)->first(),
            'langs'=>Languages::where('active',1)->get(),
            'url'=>url('admin/language/adding'),
            'permission'=>Permisions::where('url',$request->module.'/'.$request->action)->where('group_id',Auth::user()->group_id)->first()
        ];

        return view('admin.languages_add_form')->with($data)->render();
    }

    public function adding(Request $request){
        if ($request->isMethod('get')){
            return redirect('admin/language');
        }

        $input = $request->input();
        unset($input['_token']);
        $input['active'] = 1;

        Languages::create($input);
        mkdir(__DIR__."/../../../../resources/lang/".$input['code'],777);

        return json_encode(['js'=>'toastr["success"]("'.__('admin.added').'");$("form")[0].reset()']);
    }

}
