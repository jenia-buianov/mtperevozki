<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RemoteAutoCargo;
use App\RemoteAutoTransport;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\RemoteCountry;
use App\RemoteCargoType;
use App\RemoteCargoVolume;
use App\RemoteTransportType;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->middleware('logs');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect($this->redirectTo);
    }

    public function showLoginForm()
    {
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


        return view('auth.login')->with($data)->render();
    }

    public function confirm(Request $request){
        $user = User::where('confirm_token',htmlspecialchars($request->token,3))->first();
        if (!$user){
            abort(404);
        }
        $user->confirmed = 1;
        $user->confirm_token = null;
        $user->save();
        $redirect = explode('[',$request->r);
        $countries = explode(',',mb_substr($redirect[1],0,mb_strlen($redirect[1])-1));
        RemoteAutoCargo::where('email',$user->email)->where('hidden',0)->update(['hidden'=>1]);
        RemoteAutoTransport::where('email',$user->email)->where('hidden',0)->update(['hidden'=>1]);
        Auth::login($user);
        return redirect($redirect[0].'?import='.$countries[0].'&export='.$countries[1]);
    }

    public function login(Request $request)
    {
        $validator = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $name = 'country_name_'.app()->getLocale();

        if (auth()->attempt(array('email' => $request->input('email'), 'password' => $request->input('password')),true)){
            if(!auth()->user()->confirmed){
                auth()->logout();
                $errors = [$this->username() => trans('auth.failed')];
                return redirect('/login')->with([
                    'lang'=>app()->getLocale(),
                    'warning'=>translate('not_confirmed'),
                    'countries'=>RemoteCountry::select($name,'id_country','alpha3')->where('country_hidden',0)->orderBy($name)->get(),
                    'country_name'=>$name,
                    'cargo_volume_name'=>'cargo_volume_'.app()->getLocale(),
                    'transport_name'=>'transport_type_'.app()->getLocale(),
                    'cargo_type_name'=>'cargo_type_'.app()->getLocale(),

                ]);
            }
            return redirect('/');
        }else{
            $errors = [$this->username() => translate('not_found_user')];
            return redirect('/login')->withInput($request->only($this->username(), 'remember'))
                ->withErrors($errors);
        }
    }
}
