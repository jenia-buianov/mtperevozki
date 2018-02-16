<?php

namespace App\Http\Controllers;

use App\Additional\SendEmail;
use App\Emails;
use App\RemoteAutoCargo;
use App\RemoteAutoTransport;
use App\RemoteCargoVolume;
use App\RemoteCity;
use App\Subscribes;
use App\SubscribesEmails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PharIo\Manifest\Email;
use Validator;

class FormController extends Controller
{

    private function validateUser($data){
        return Validator::make($data,[
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:mysql.users',
            'phone' => 'required|numeric',
            'confirm_token' => 'required|string|max:100|unique:mysql.users',
            'password' => 'required'
        ]);
    }

    public function mainForm(Request $request){

        $validator = Validator::make($request->all(), [
            'export' => 'required|integer',
            'import' => 'required|integer',
            'import_city' =>'integer',
            'export_city' =>'integer',
            'date_export' => 'required|string',
            'cargo_type' => 'required|integer',
            'volume' => 'required|integer',
            'transport_type' => 'required|integer',
            'face' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
        ]);



        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode([
                'js'=>[
                    '$(".alert-dismissible").css("opacity",1);
                    $(".alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-danger fade show");
                    $(".alert-danger span:eq(0)").html("'.implode(', ',$errors).'");
                    '
                ]
            ]);
        }

        $input = $request->input();

        unset($input['_token']);
        $input['type'] = $input['transport_type'];
        $input['name'] = $input['cargo_type'];
        $input['date'] = $input['date_export'];
        $input['source'] = 'mtperevozki.ru';
        $input['export_city_port'] = $input['import_city_port'] = $input['icq'] = $input['count'] = $input['weight'] =
            $input['movers_site'] = $input['documents']
                =  $input['length']
                =  $input['width']
                =  $input['height']
                =  $input['by_admin_time']
                = '' ;
        $input['order_date'] = date('d-m-Y');
        $input['date_sort'] = null;
        $input['container_type'] = $input['hidden']  = $input['hide_contact'] = $input['status'] = $input['commstill'] = $input['comstil_id'] = $input['by_admin']
            = $input['id_contact']
            = $input['supplementary']
            = $input['permanent']
            = $input['car_number']
            = $input['tir']
            = $input['cmr']
            = $input['banktransfer_payment']
            = $input['adr']
            = $input['cash_payment']
            = $input['down_payment']
            = $input['packing']
            = $input['temperature']
            = $input['express_delivery']
            = $input['insurance']
            = $input['warehouse']
            = $input['seats']
            = 0;
        if (empty($input['skype']))
            $input['skype'] = '';
        if (empty($input['description']))
            $input['description'] = '';
        if (empty($input['company']))
            $input['company'] = '';
        if (empty($input['phone1']))
            $input['phone1'] = '';
        if (empty($input['phone2']))
            $input['phone2'] = '';
        if (empty($input['phone3']))
            $input['phone3'] = '';

        unset($input['date_export']);
        unset($input['transport_type']);
        unset($input['cargo_type']);
        if (in_array($input['type'],[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45,15,43,44])){
            $cargo = RemoteAutoCargo::create($input);
            if (isset($input['phone1'])&&!empty($input['phone1']))
                $cargo->phone1 = htmlspecialchars($input['phone1']);
            if (isset($input['phone2'])&&!empty($input['phone2']))
                $cargo->phone2 = htmlspecialchars($input['phone2']);
            $cargo->save();

            if (Auth::check()){
                $user = User::where('id',Auth::user()->id)->first();
            }
            if (User::where('email',$input['email'])->count()){
                $user = User::where('email',$input['email'])->first();
            }
            if (Auth::check()||User::where('email',$input['email'])->count()){
                $subscribe = Subscribes::create([
                                'user_id'=>$user->id,
                                'url'=>url(app()->getLocale().'/birja/transport/?import='.$input['import'].'&export='.$input['export']),
                                'model'=>'RemoteAutoTransport',
                                'new_count'=>RemoteAutoTransport::where('import',$input['import'])->count()
                            ]);
                foreach (Emails::where('type','subscribes')->get() as $k=>$v){
                    SubscribesEmails::create([
                       'subscribe_id'=>$subscribe->id,
                       'email_id'=>$v->id
                    ]);
                }

                foreach (Emails::where('type','registration')->get() as $k=>$v){
                    $array = [
                        'email_from'=>$v->email_from->login,
                        'template'=>$v->template->path,
                        'message'=>[
                            'subject'=>translate('added_with_success'),
                            'email'=>$input['email']
                        ]
                    ];
                    foreach (json_decode($v->template->params) as $i=>$l){
                        if (isset($user->$l)&&$l!=='password') $array['message'][$l] = $user->$l;
                        if ($l=='to')  $array['message'][$l] = strip_tags($cargo->import());
                        if ($l=='from')  $array['message'][$l] = strip_tags($cargo->export());
                        if ($l=='volume')  $array['message'][$l] = $cargo->name();
                        if ($l=='transport')  $array['message'][$l] = $cargo->transport_type();
                        if ($l=='count')  $array['message'][$l] = RemoteAutoTransport::where('import',$input['import'])->count();


                    }
                    $array['message']['subscribe_link'] = url(app()->getLocale().'/birja/transport/?import='.$input['import'].'&export='.$input['export']);
                    new SendEmail($array);
                }

            return json_encode([
                'js'=>[
                    '$(".alert-dismissible").css("opacity",1);
                    $(".alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-success fade show");
                    $(".alert-success span:eq(0)").html("'.translate('added_with_success').'");
                    setTimeout(function(){
                        window.location.replace("'.$subscribe->url.'");
                    },2000);
                    '
                ]
            ]);
            }
            else {
                $password = str_shuffle('A#d^FS025761');
                $userRegData = $request->only(['face','phone','email']);
                $userName = explode(' ',$userRegData['face']);
                unset($userRegData['face']);
                $userRegData['name'] = $userName[0];
                $userRegData['lastname'] = $userName[1];
                $userRegData['confirm_token'] = str_shuffle('ABCDEFJQDLKLSDKFLKSNVPMBOIOT131354640889730213456789_zxcvbnmasdfghjklqwertyuiop');
                $userRegData['password'] = bcrypt($password);
                $userRegData['group_id'] = 3;
                $validateUser = $this->validateUser($userRegData);
                if ($validateUser->fails()){
                    $errors = [];
                    foreach ($validateUser->errors()->all() as $k=>$v){
                        $errors[] = $v;
                    }
                    return json_encode([
                        'js'=>[
                            '$(".alert-dismissible").css("opacity",1);
                    $(".alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-danger fade show");
                    $(".alert-danger span:eq(0)").html("'.implode(', ',$errors).'");
                    '
                        ]
                    ]);
                }

                $user = User::create($userRegData);
                if (!empty($input['phone1']))
                    $user->phone2 = $input['phone1'];
                if (!empty($input['phone2']))
                    $user->phone3 = $input['phone2'];
                if (!empty($input['skype']))
                    $user->skype = $input['skype'];
                $user->save();

                foreach (Emails::where('type','registration')->get() as $k=>$v){
                    $array = [
                        'email_from'=>$v->email_from->login,
                        'template'=>$v->template->path,
                        'message'=>[
                            'subject'=>translate('registration'),
                            'email'=>$input['email']
                        ]
                    ];
                    foreach (json_decode($v->template->params) as $i=>$l){
                        if (isset($user->$l)) $array['message'][$l] = $user->$l;
                        if ($l=='password')  $array['message'][$l] = $$l;
                        if ($l=='to')  $array['message'][$l] = strip_tags($cargo->import());
                        if ($l=='from')  $array['message'][$l] = strip_tags($cargo->export());
                        if ($l=='volume')  $array['message'][$l] = $cargo->name();
                        if ($l=='transport')  $array['message'][$l] = $cargo->transport_type();
                        if ($l=='count')  $array['message'][$l] = RemoteAutoTransport::where('import',$input['import'])->count();


                    }
                    $array['message']['subscribe_link'] = url('confirm/'.$user->confirm_token.'?r='.app()->getLocale().'/birja/transport/['.$input['import'].','.$input['export'].']');
                    new SendEmail($array);
                }

                $subscribe = Subscribes::create([
                    'user_id'=>$user->id,
                    'url'=>url(app()->getLocale().'/birja/transport/?import='.$input['import'].'&export='.$input['export']),
                    'model'=>'RemoteAutoTransport',
                    'new_count'=>RemoteAutoTransport::where('import',$input['import'])->count()
                ]);
                foreach (Emails::where('type','subscribes')->get() as $k=>$v){
                    SubscribesEmails::create([
                        'subscribe_id'=>$subscribe->id,
                        'email_id'=>$v->id
                    ]);
                }

                return json_encode([
                    'js'=>[
                        '$(".alert-dismissible").css("opacity",1);
                    $(".alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-success fade show");
                    $(".alert-success span:eq(0)").html("'.translate('added_with_success').'.<br>'.translate('send_to_email').'");
                    '
                    ]
                ]);
            }
        }
    }

    public function setCity(Request $request){
        $cities = [];
        $name = 'city_name_'.app()->getLocale();
        foreach (RemoteCity::where('id_country',(int)$request->country)->orderBy($name,'asc')->get() as $k=>$v){
            $cities[] = '<option value="'.$v->id_city.'">'.$v->$name.'</option>';
        }

        return json_encode(['cities'=>implode("\n",$cities)]);
    }
}
