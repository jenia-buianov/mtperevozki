<?php

namespace App\Http\Controllers\Admin;

use App\AdminMenu;
use App\Logs;
use App\Modules;
use App\Order;
use App\Permissions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class AdminHomeController extends Controller
{

    public function index(Request $request){
        $page = explode('admin/',$request->path());
        if (count($page)>1)
            $page = $page[1];
        else $page = '/';
        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>Permissions::where('key','admin_access')->first(),
        ];
        return view('admin.home')->with($data)->render();
    }

    public function deleteMethod(Request $request){
        if (!(int)$request->id){
            echo json_encode(['js'=>'toastr["error"]("Empty ID")']);
        }
        if (empty(htmlspecialchars($request->mod,3))){
            echo json_encode(['js'=>'toastr["error"]("Empty Mode")']);
        }
        if (!empty(htmlspecialchars($request->mod,3))){
            DB::table(htmlspecialchars($request->mod,3))->where('id',(int)$request->id)->delete();
            echo json_encode(['js'=>'$("#row'.(int)$request->id.'").remove();toastr["success"]("Удалено")']);
        }
    }

    public function setOrder(Request $request){
        if (!(int)$request->id){
            echo json_encode(['js'=>'toastr["error"]("Empty ID")']);
        }
        if (empty(htmlspecialchars($request->mod,3))){
            echo json_encode(['js'=>'toastr["error"]("Empty Mode")']);
        }
        if (empty(htmlspecialchars($request->type,3))){
            echo json_encode(['js'=>'toastr["error"]("Empty Type")']);
        }
        $currentObject = DB::table(htmlspecialchars($request->mod,3))->where('id',(int)$request->id)->first();
        if (htmlspecialchars($request->type,3)=='up')
            $changeObject = DB::table(htmlspecialchars($request->mod,3))->where('order','<',$currentObject->order)->orderBy('order','desc')->limit(1)->first();
        else
            $changeObject = DB::table(htmlspecialchars($request->mod,3))->where('order','>',$currentObject->order)->orderBy('order','asc')->limit(1)->first();
        $tempOrder =  $currentObject->order;
        $currentObject->order = $changeObject->order;
        $changeObject->order = $tempOrder;
        DB::table(htmlspecialchars($request->mod,3))->where('id',(int)$currentObject->id)->update(['order'=>$currentObject->order]);
        DB::table(htmlspecialchars($request->mod,3))->where('id',(int)$changeObject->id)->update(['order'=>$changeObject->order]);
        echo json_encode(['js'=>'$("#order'.$currentObject->id.'").html('.$currentObject->order.');$("#order'.$changeObject->id.'").html('.$changeObject->order.');toastr["success"]("'.__('admin.saved').'")']);


    }

}
