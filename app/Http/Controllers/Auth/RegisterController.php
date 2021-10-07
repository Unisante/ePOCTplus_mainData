<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use DB;

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

    use RegistersUsers {
        // change the name of the name of the trait's method in this class
        // so it does not clash with our own register method
           register as registration;
       }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Register method to use 2FA
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');
        $registration_data = $request->all();
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();
        $request->session()->flash('registration_data', $registration_data);

        // generate the QR image.
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        // pass the QR barcode image to our view
        return view('google2fa.register', [
            'QR_Image' => $QR_Image, 
            'secret' => $registration_data['google2fa_secret'],
            'reauthenticating' => false
        ]);
    }

    public function completeRegistration(Request $request)
    {        
        // add the session data back to the request input
        $request->merge(session('registration_data'));

        // call the default laravel authentication
        return $this->registration($request);
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Adds roles to specific accounts when creating a new user.
     */
    protected function addRoleToSpecificAccounts(&$user){
        if($user === null){
            throw new \InvalidArgumentException('User should not be null.');
        }

        switch($user->email){
            case 'admin@dynamic.com':
                $role = 'Administrator';
                break;
            case 'datamanager@dynamic.com':
                $role = 'Data Manager';
                break;
            case 'statistician@dynamic.com':
                $role = 'Statistician';
                break;
            case 'logistician@dynamic.com':
                $role = 'Logistician';
                break;
            default:
                $role = null;
        }

        // no role to add
        if($role === null){
            return;
        }

        $role_entry = DB::table('roles')->where('name', $role)->first();
        if($role_entry !== null){
            $user->roles()->sync([$role_entry->id]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $name = $data['name'];
        $email = $data['email'];
        $password = Hash::make($data['password']);
        $google2fa_secret = $data['google2fa_secret'];
        $user = User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => $password,
            'google2fa_secret'  => $google2fa_secret,
        ]);

        $this->addRoleToSpecificAccounts($user);

        return $user;
    }
}
