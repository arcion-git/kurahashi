<?php

namespace App\Http\Controllers\UserAuth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest');
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
          'first_name' => ['required', 'string', 'max:255'],
          'last_name' => ['required', 'string', 'max:255'],
          'first_name_kana' => ['required', 'string', 'max:255'],
          'last_name_kana' => ['required', 'string', 'max:255'],
          'company' => ['required', 'string', 'max:255'],
          'company_kana' => ['required', 'string', 'max:255'],
          'address01' => ['required', 'string', 'max:8'],
          'address02' => ['required', 'string', 'max:255'],
          'address03' => ['required', 'string', 'max:255'],
          'address04' => ['required', 'string', 'max:255'],
          'address05' => ['required', 'string', 'max:255'],
          'tel' => ['required', 'string', 'max:255'],
          'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'first_name_kana' => $data['first_name_kana'],
          'last_name_kana' => $data['last_name_kana'],
          'company' => $data['company'],
          'company_kana' => $data['company_kana'],
          'address01' => $data['address01'],
          'address02' => $data['address02'],
          'address03' => $data['address03'],
          'address04' => $data['address04'],
          'address05' => $data['address05'],
          'tel' => $data['tel'],
          'email' => $data['email'],
          'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }
}
