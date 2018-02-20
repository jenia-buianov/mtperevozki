<?php

namespace App\Http\Controllers;

use App\Additional\SendEmail;
use App\Companies;
use App\Emails;
use App\RemoteAutoCargo;
use App\RemoteAutoTransport;
use App\RemoteCargoVolume;
use App\RemoteCity;
use App\RemoteCountry;
use App\RemoteTransportType;
use App\Subscribes;
use App\SubscribesEmails;
use App\User;
use App\UsersCompanies;
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
        ],[
//            'required' => 'Вы не указали поле :attribute',
            'name.required'=>translate('should_be_name'),
            'name.string'=>translate('should_be_name_string'),
            'name.max'=>translate('should_be_name_max').' :max',
            'lastname.required'=>translate('should_be_lastname'),
            'lastname.string'=>translate('should_be_lastname_string'),
            'lastname.max'=>translate('should_be_lastname_max'),
            'phone.required'=>translate('should_be_phone'),
            'phone.numeric' => translate('phone_should_be_numeric'),
            'email.required'=>translate('should_be_email'),
            'g-recaptcha-response.required'=>translate('captcha_required'),
        ]);
    }


    // Adding cargo only for Auto, Sea, Rails, Air.
    // Adding post cargo in the method addPostCargo
    // Adding passengers cargo in the method addPassengersCargo

    public function addCargo(Request $request){

        $validator = Validator::make($request->all(), [
            'export' => 'required|integer',
            'import' => 'required|integer',
            'import_city' =>'integer',
            'export_city' =>'integer',
            'date_export' => 'required|string',
            'cargo_type' => 'required',
            'volume' => 'required|integer',
            'transport_type' => 'required|integer',
            'face' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha'
        ],[
            'export.required' => translate('export_required'),
            'g-recaptcha-response.required'=>translate('captcha_required'),
            'phone.numeric' => translate('phone_should_be_numeric'),
            'email' => translate('email_wrong'),
            'import.required'=>translate('import_required'),
            'import_city.integer'=>translate('wrong_import_city'),
            'export_city.integer'=>translate('wrong_export_ciy'),
            'date_export.required'=>translate('should_be_date_export'),
            'date_export.string'=>translate('date_export_string'),
            'cargo_type.required'=>translate('should_be_cargo_type'),
            'volume.required'=>translate('should_be_volume'),
            'volume.integer'=>translate('should_be_volume_integer'),
            'transport_type.required'=>translate('should_be_transport_type'),
            'transport_type.integer'=>translate('should_be_transport_type_integer'),
            'face.required'=>translate('should_be_face'),
            'phone.required'=>translate('should_be_phone'),
        ]);



        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode([
                'js'=>[
                    '$("#formModal .alert-dismissible").css("opacity",1);
                    $("#formModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-danger fade show");
                    $("#formModal .alert-dismissible").css("display","block");
                    $("#formModal .alert-danger span:eq(0)").html("'.implode(', ',$errors).'");
                    '
                ]
            ]);
        }

        $input = $request->input();

        unset($input['_token']);
        if (!empty($input['own'])&&$input['cargo_type']=='own')
            $input['cargo_type'] = htmlspecialchars(trim($input['own']),3);
        unset($input['own']);
        $input['company'] = trim($input['company']);
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
                =  $input['description']
                =  $input['skype']
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

        $user = User::where('email',$input['email'])->first();

        if(!Auth::check()&&!$user) $input['hidden'] = 1;

        if (empty($input['company']))
            $input['company'] = '';
        if (empty($input['phone1']))
            $input['phone1'] = '';
        if (empty($input['phone2']))
            $input['phone2'] = '';
        if (empty($input['phone3']))
            $input['phone3'] = '';

        if (empty($input['export_city']) or $input['export_city']==0)
            $input['export_city'] = '';
        if (empty($input['import_city']) or $input['import_city']==0)
            $input['import_city'] = '';

        unset($input['date_export']);
        unset($input['transport_type']);
        unset($input['cargo_type']);
        unset($input['g-recaptcha-response']);
        if (in_array($input['type'],[2,3,4,5,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,26,27,28,29,32,35,37,45,15,43,44])){
            $cargo = RemoteAutoCargo::create($input);
            if (isset($input['phone1'])&&!empty($input['phone1']))
                $cargo->phone1 = htmlspecialchars($input['phone1']);
            if (isset($input['phone2'])&&!empty($input['phone2']))
                $cargo->phone2 = htmlspecialchars($input['phone2']);
            $cargo->save();

            if ($user){
                $subscribe = Subscribes::create([
                                'user_id'=>$user->id,
                                'url'=>url(app()->getLocale().'/birja/transport/?import='.$input['import'].'&export='.$input['export'].'&transport='.$input['type']),
                                'model'=>'RemoteAutoTransport',
                                'new_count'=>RemoteAutoTransport::where('import',$input['import'])->count(),
                                'transport_type'=>$input['type'],
                                'title'=>translate('transport_subscribes').': '.RemoteTransportType::find($input['type'])->transport_type_ru.' '.RemoteCountry::find($input['import'])->country_name_ru,
                                'import'=>$input['import'],
                                'export'=>$input['export'],
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
                    $array['message']['subscribe_link'] = url(app()->getLocale().'/birja/transport/?import='.$input['import'].'&export='.$input['export'].'&transport='.$input['type']);
                    $array['message']['subscribe_id'] = $subscribe->id;
                    new SendEmail($array);
                }


            return json_encode([
                'js'=>[
                    '$("#formModal .alert-dismissible").css("display","block");
                    $("#formModal .alert-dismissible").css("opacity",1);
                    $("#formModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-success fade show");
                    $("#formModal .alert-success span:eq(0)").html("'.translate('added_with_success').'");
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
                            '$("#formModal .alert-dismissible").css("opacity",1);
                    $("#formModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-danger fade show");
                    $("#formModal .alert-dismissible").css("display","block");
                    $("#formModal .alert-danger span:eq(0)").html("'.implode(', ',$errors).'");
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

                $subscribe = Subscribes::create([
                    'user_id'=>$user->id,
                    'url'=>url(app()->getLocale().'/birja/transport/?import='.$input['import'].'&export='.$input['export'].'&transport='.$input['type']),
                    'model'=>'RemoteAutoTransport',
                    'new_count'=>RemoteAutoTransport::where('import',$input['import'])->count(),
                    'transport_type'=>$input['type'],
                    'import'=>$input['import'],
                    'export'=>$input['export'],
                    'title'=>translate('transport_subscribes').': '.RemoteTransportType::find($input['type'])->transport_type_ru.' '.RemoteCountry::find($input['import'])->country_name_ru
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
                    $array['message']['subscribe_id'] = $subscribe->id;
                    new SendEmail($array);
                }


                return json_encode([
                    'js'=>[
                        '$("#formModal .alert-dismissible").css("display","block");
                        $("#formModal .alert-dismissible").css("opacity",1);
                    $("#formModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-success fade show");
                    $("#formModal .alert-success span:eq(0)").html("'.translate('added_with_success').'.<br>'.translate('send_to_email').'");
                    '
                    ]
                ]);
            }
        }
    }

    public function addPostCargo(Request $request){

    }

    public function addPassengersCargo(Request $request){

    }

    // Adding transport only for Auto, Sea, Rails, Air.
    // Adding post transport in the method addPostTransport
    // Adding passengers transport in the method addPassengersTransport

    public function addTransport(Request $request){

        $validator = Validator::make($request->all(), [
            'export' => 'required|integer',
            'import' => 'required|integer',
            'import_city' =>'integer',
            'export_city' =>'integer',
            'free_from' => 'required|string',
            'free_to' => 'required|string',
            'volume' => 'required|integer',
            'transport_type' => 'required|integer',
            'face' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
//            'g-recaptcha-response' => 'required|captcha'
        ],[
            'export.required' => translate('export_required'),
            'g-recaptcha-response.required'=>translate('captcha_required'),
            'phone.numeric' => translate('phone_should_be_numeric'),
            'email' => translate('email_wrong'),
            'import.required'=>translate('import_required'),
            'import_city.integer'=>translate('wrong_import_city'),
            'export_city.integer'=>translate('wrong_export_ciy'),
            'free_from.required'=>translate('should_be_free_from'),
            'free_from.string'=>translate('free_from_string'),
            'free_to.required'=>translate('should_be_free_to'),
            'free_to.string'=>translate('free_to_string'),
            'volume.required'=>translate('should_be_volume'),
            'volume.integer'=>translate('should_be_volume_integer'),
            'transport_type.required'=>translate('should_be_transport_type'),
            'transport_type.integer'=>translate('should_be_transport_type_integer'),
            'face.required'=>translate('should_be_face'),
            'phone.required'=>translate('should_be_phone'),
        ]);



        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $k=>$v){
                $errors[] = $v;
            }
            return json_encode([
                'js'=>[

                    '$("#transportFormModal .alert-dismissible").css("opacity",1);
                    $("#transportFormModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-danger fade show");
                    $("#transportFormModal .alert-dismissible").css("display","block");
                    $("#transportFormModal .alert-danger span:eq(0)").html("'.implode(', ',$errors).'");
                    '
                ]
            ]);
        }

        $input = $request->input();

        unset($input['_token']);
        $input['type'] = $input['transport_type'];
        $input['date_from'] = $input['free_from'];
        $input['date_to'] = $input['free_to'];
        $input['order_date'] = date('d-m-Y');

        $input['company'] = trim($input['company']);

        if (empty($input['company']))
            $input['company'] = '';
        if (empty($input['phone1']))
            $input['phone1'] = '';
        if (empty($input['phone2']))
            $input['phone2'] = '';
        if (empty($input['phone3']))
            $input['phone3'] = '';

        $input = array_merge($input,array(
            'container_type' => '0',
            'weight' => '',
            'export_city_port' => '',
            'import_city_port' => '',
            'free' => '',
            'hidden' => '0',
            'hide_contact' => '0',
            'status' => '0',
            'by_admin' => '0',
            'by_admin_time' => '',
            'source' => 'mtperevozki.ru',
            'name' => '',
            'associate' => '0',
            'supplementary' => '0',
            'car_number' => '1',
            'tir' => '0',
            'cmr' => '0',
            'adr' => '0',
            'cemt' => '0',
            'date_sort' => NULL,
            'documents' => '0',
            'packing' => '0',
            'door_to_door_delivery' => '0',
            'airport_to_door_delivery' => '0',
            'warehouse' => '0',
            'insurance' => '0',
            'comstil' => '0',
            'comstil_id' => '0',
            'description' => '',
            'skype' => ''
        ));
        if (isset($input['phone1'])&&!empty($input['phone1']))
            $input['phone1'] = htmlspecialchars($input['phone1']);
        if (isset($input['phone2'])&&!empty($input['phone2']))
            $input['phone2'] = htmlspecialchars($input['phone2']);

        if (empty($input['export_city']) or $input['export_city']==0)
            $input['export_city'] = '';
        if (empty($input['import_city']) or $input['import_city']==0)
            $input['import_city'] = '';

        unset($input['free_from']);
        unset($input['free_to']);
        unset($input['transport_type']);
        unset($input['g-recaptcha-response']);



//        print_r($input);
//        exit;

        $user = User::where('email',$input['email'])->first();

        if(!Auth::check()&&!$user) $input['hidden'] = 1;

        $cargo = RemoteAutoTransport::create($input);

        if ($user){
            $subscribe = Subscribes::create([
                'user_id'=>$user->id,
                'url'=>url(app()->getLocale().'/birja/cargo/?import='.$input['import'].'&export='.$input['export'].'&transport='.$input['type']),
                'model'=>'RemoteAutoCargo',
                'new_count'=>RemoteAutoCargo::where('import',$input['import'])->count(),
                'transport_type'=>$input['type'],
                'import'=>$input['import'],
                'export'=>$input['export'],
                'title'=>translate('cargo_subscribes').': '.RemoteTransportType::find($input['type'])->transport_type_ru.' '.RemoteCountry::find($input['import'])->country_name_ru
            ]);
            foreach (Emails::where('type','subscribes')->get() as $k=>$v){
                SubscribesEmails::create([
                    'subscribe_id'=>$subscribe->id,
                    'email_id'=>$v->id
                ]);
            }

            foreach (Emails::where('type','registration_transport')->get() as $k=>$v){
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
                    if ($l=='volume')  $array['message'][$l] = $cargo->volume();
                    if ($l=='transport')  $array['message'][$l] = $cargo->transport_type();
                    if ($l=='count')  $array['message'][$l] = RemoteAutoCargo::where('import',$input['import'])->count();


                }
                $array['message']['subscribe_link'] = url(app()->getLocale().'/birja/cargo/?import='.$input['import'].'&export='.$input['export'].'&transport='.$input['type']);
                $array['message']['subscribe_id'] = $subscribe->id;
                new SendEmail($array);
            }

//            return view('templates.registation_transport',$array['message'])->render();

            if (!empty(trim($input['company']))){
                $company = Companies::where('title',$input['company'])->first();
                if (!$company){
                    $company = Companies::create(['title'=>htmlspecialchars($input['company'])]);
                    UsersCompanies::create(['user_id'=>$user->id,'company_id'=>$company->id]);
                }elseif(!UsersCompanies::where([['user_id','=',$user->id],['company_id','=',$company->id]])->count())
                    UsersCompanies::create(['user_id'=>$user->id,'company_id'=>$company->id]);
            }

            return json_encode([
                'js'=>[
                    '
                     $("#transportFormModal .alert-dismissible").css("display","block");
                    $("#transportFormModal .alert-dismissible").css("opacity",1);
                    $("#transportFormModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-success fade show");
                    $("#transportFormModal .alert-success span:eq(0)").html("'.translate('added_with_success').'");
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
                        '
                         $("#transportFormModal .alert-dismissible").css("display","block");
                        $("#transportFormModal .alert-dismissible").css("opacity",1);
                    $("#transportFormModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-danger fade show");
                    $("#transportFormModal .alert-danger span:eq(0)").html("'.implode(', ',$errors).'");
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

            $subscribe = Subscribes::create([
                'user_id'=>$user->id,
                'url'=>url(app()->getLocale().'/birja/cargo/?import='.$input['import'].'&export='.$input['export'].'&transport='.$input['type']),
                'model'=>'RemoteAutoCargo',
                'new_count'=>RemoteAutoCargo::where('import',$input['import'])->count(),
                'transport_type'=>$input['type'],
                'import'=>$input['import'],
                'export'=>$input['export'],
                'title'=>translate('cargo_subscribes').': '.RemoteTransportType::find($input['type'])->transport_type_ru.' '.RemoteCountry::find($input['import'])->country_name_ru
            ]);
            foreach (Emails::where('type','subscribes')->get() as $k=>$v){
                SubscribesEmails::create([
                    'subscribe_id'=>$subscribe->id,
                    'email_id'=>$v->id
                ]);
            }

            foreach (Emails::where('type','registration_transport')->get() as $k=>$v){
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
                    if ($l=='volume')  $array['message'][$l] = $cargo->volume();
                    if ($l=='transport')  $array['message'][$l] = $cargo->transport_type();
                    if ($l=='count')  $array['message'][$l] = RemoteAutoCargo::where('import',$input['import'])->count();


                }
                $array['message']['subscribe_link'] = url('confirm/'.$user->confirm_token.'?r='.app()->getLocale().'/birja/cargo/['.$input['import'].','.$input['export'].']');
                $array['message']['subscribe_id'] = $subscribe->id;
                new SendEmail($array);
            }


            if (!empty($input['company'])){
                $company = Companies::where('title',$input['company'])->first();
                if (!$company){
                    $company = Companies::create(['title'=>htmlspecialchars($input['company'])]);
                }
                UsersCompanies::create(['user_id'=>$user->id,'company_id'=>$company->id]);
            }
            return json_encode([
                'js'=>[
                    ' $("#transportFormModal .alert-dismissible").css("display","block");
                    $("#transportFormModal .alert-dismissible").css("opacity",1);
                    $("#transportFormModal .alert-dismissible").removeClass("alert-danger fade show").removeClass("alert-success fade show").addClass("alert-success fade show");
                    $("#transportFormModal .alert-success span:eq(0)").html("'.translate('added_with_success').'.<br>'.translate('send_to_email').'");
                    '
                ]
            ]);
        }


    }

    public function addPostTransport(Request $request){

    }

    public function addPassengersTransport(Request $request){

    }

    public function setCity(Request $request){
        $cities = ['<option selected disabled>'.translate('select_city').'</option>'];
        $name = 'city_name_'.app()->getLocale();
        foreach (RemoteCity::where('id_country',(int)$request->country)->orderBy($name,'asc')->get() as $k=>$v){
            $cities[] = '<option value="'.$v->id_city.'">'.$v->$name.'</option>';
        }

        return json_encode(['cities'=>implode("\n",$cities)]);
    }
}
