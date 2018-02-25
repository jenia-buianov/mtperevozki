<?php

namespace App\Http\Controllers\Admin;

use App\Modules;
use App\Permisions;
use App\PermissionsPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use DB;

class AdminModuleController extends Controller
{

    public function start(Request $request){
        $module = htmlspecialchars($request->module,3);
        $access = Auth::user()->group->hasAccessByURL('/admin/'.$module);
        if (!$access) {
            return redirect()->route('admin.home');
        }
        return app('App\Http\Controllers\Admin\Admin'.ucfirst($module).'Controller')->index($request);
    }

    public function action(Request $request){
        DB::connection()->enableQueryLog();
        $module = htmlspecialchars($request->module,3);
        $action = htmlspecialchars($request->action,3);
        $access = Auth::user()->group->hasAccessByURL('/admin/'.$module.'/'.$action);
        if (!$access) {
            if ($request->isMethod('get'))
                return redirect()->route('admin.home');
            return json_encode(['js'=>'toastr["error"]("'.translate('no_access').'")']);
        }
        return app('App\Http\Controllers\Admin\Admin'.ucfirst($module).'Controller')->$action($request);
    }
}
