<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\RemoteCountry;
use App\RemoteCargoType;
use App\RemoteCargoVolume;
use App\RemoteTransportType;
use App\Emails;
use App\Additional\SendEmail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('logs');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:64',
            'email' => 'required|string|email|max:255|unique:mysql.users',
            'password' => 'required|string|min:6|',
            'phone'=>'required|numeric',
            'type' => 'required'
//            'g-recaptcha-response' => 'required|captcha'
        ],[
            'name.required'=>translate('should_be_name'),
            'name.string'=>translate('should_be_name_string'),
            'name.max'=>translate('should_be_name_max').' :max',
            'phone.required'=>translate('should_be_phone'),
            'phone.numeric' => translate('phone_should_be_numeric'),
            'email.required'=>translate('should_be_email'),
            'email.max'=>translate('should_be_max'),
            'email.unique'=>translate('should_be_unique'),
            'g-recaptcha-response.required'=>translate('captcha_required'),
            'type.required'=>'Укажите тип',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'group_id'=>3,
            'phone'=>$data['phone'],
            'confirm_token'=>$data['confirm_token'],
            'type'=>$data['type']
        ]);
    }

    public function register(Request $request){

        $validator = $this->validator($request->input());
        if ($validator->fails()) {
            return redirect('register')->withInput($request->except(['password','g-recaptcha-response']))
                ->withErrors($validator->errors());
        }
        $input = $request->input();
        $input['confirm_token'] = str_shuffle('ABCDEFJQDLKLSDKFLKSNVPMBOIOT131354640889730213456789_zxcvbnmasdfghjklqwertyuiop');
        $user = $this->create($input);
        foreach (Emails::where('type','registration_user')->get() as $k=>$v){
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

            }
            new SendEmail($array);
        }
        return redirect('/register')->with('success', translate('confirm_email'));
    }

    public function showRegistrationForm(){
        $name = 'country_name_'.app()->getLocale();
        $data = [
            'transport_type'=>RemoteTransportType::where('transport_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_type'=>RemoteCargoType::where('cargo_type_hidden',0)->orderBy('order','asc')->get(),
            'cargo_volume'=>RemoteCargoVolume::where('cargo_volume_hidden',0)->get(),
            'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
            'country_name'=>$name,
            'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
            'transport_name'=>'transport_type_'.app()->getLocale(),
            'cargo_type_name'=>'cargo_type_'.app()->getLocale(),
            'lang'=>app()->getLocale()
        ];
        return view('auth.register')->with($data)->render();
    }
}
