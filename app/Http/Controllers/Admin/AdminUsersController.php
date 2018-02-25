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

class AdminUsersController extends Controller
{
    public  function  index(Request $request){

        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey]
            ],
            'new_today'=>User::whereDate('created_at',DB::raw('CURDATE()'))->count('id'),
            'new_week'=>User::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->count('id'),
            'new_month'=>User::whereDate('created_at','>=',date('Y-m-01'))->count('id'),
            'users'=>User::get()
        ];

        return view('admin.users')->with($data)->render();
    }

    public function edit(Request $request){
        $id = (int)$request->id;
        $user = User::find($id);
        if (!$user){
            return redirect()->route('admin.home');
        }
        $data = [
            'left_menu'=>AdminMenu::orderBy('order','asc')->get(),
            'user'=>Auth::user(),
            'module' =>[
                ['link'=>url('admin'),'title'=>'Админ-панель'],
                ['link'=>url('admin/'.$request->module),'title'=>PermissionsPage::where('url','/admin/'.$request->module)->first()->permission->titleKey]
            ],
            'new_today'=>User::whereDate('created_at',DB::raw('CURDATE()'))->count('id'),
            'new_week'=>User::whereDate('created_at','>=',date('Y-m-d',strtotime('monday this week')))->count('id'),
            'new_month'=>User::whereDate('created_at','>=',date('Y-m-01'))->count('id'),
            'user_'=>$user,
            'url'=>url('admin/users/editing'),
            'groups'=>Groups::get()
        ];

        return view('admin.users_form')->with($data)->render();
    }

    public function editing(Request $request){
        $url = explode('/',$_SERVER['HTTP_REFERER']);
        $id = (int)$url[count($url)-1];
        $user = User::find($id);
        if (!$user){
            return redirect()->route('admin.home');
        }
        $validator =  Validator::make($request->except(['_token']), [
            'name' => 'required|string|max:64',
            'lastname' => 'string|max:64',
            'email' => 'required|string|email|max:255',
            'phone'=>'required|numeric',
            'group_id'=>'required|numeric',
        ],[
            'name.required'=>translate('should_be_name'),
            'name.string'=>translate('should_be_name_string'),
            'name.max'=>translate('should_be_name_max').' :max',
            'phone.required'=>translate('should_be_phone'),
            'phone.numeric' => translate('phone_should_be_numeric'),
            'email.required'=>translate('should_be_email'),
            'email.max'=>translate('should_be_max'),
            'email.unique'=>translate('should_be_unique'),
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode(['js'=>'toastr["error"]("'.implode(', ',$errors).'");']);
        }
        $input = $request->except(['_token']);
        DB::table('users')->where('id',$user->id)->update($input);
        return json_encode(['js'=>'toastr["success"]("'.translate('saved').'");']);
    }


    public function check(Request $request){
        $id = (int)decrypt($request->id);
        $user = User::find($id);
        if ($user){
            if ($user->confirmed==1){
                $user->confirmed = 0;
                $user->confirm_token = str_shuffle('ABCDEFJQDLKLSDKFLKSNVPMBOIOT131354640889730213456789_zxcvbnmasdfghjklqwertyuiop');
                foreach (Emails::where('type','registration_user')->get() as $k=>$v){
                    $array = [
                        'email_from'=>$v->email_from->login,
                        'template'=>$v->template->path,
                        'message'=>[
                            'subject'=>translate('registration'),
                            'email'=>$user->email
                        ]
                    ];
                    foreach (json_decode($v->template->params) as $i=>$l){
                        if (isset($user->$l)) $array['message'][$l] = $user->$l;

                    }
                    new SendEmail($array);
                }
            }
            else{
                $user->confirmed = 1;
                $user->confirm_token = null;
            }
            $user->save();
            return json_encode(['js'=>'$("#row'.$id.' button:eq(0)").tooltip("hide"); toastr["success"]("'.translate('saved').'");']);
        }else {
            return json_encode(['js'=>'toastr["error"]("Пользователь не найден")']);
        }
    }
}
