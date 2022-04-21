<?php

namespace App\Http\Controllers\UserAuth;

use App\User;
use App\Setonagi;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
          // 'company_kana' => ['required', 'string', 'max:255'],
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
      $create_user = User::create([
          'email' => $data['email'],
          'password' => Hash::make($data['password']),
          'tel' => $data['tel'],
          'setonagi' => 1,
      ]);
      $user_id = $create_user->id;
      $setonagi = Setonagi::create([
          'user_id' => $user_id,
          'company' => $data['company'],
          // 'company_kana' => $data['company_kana'],
          'last_name' => $data['last_name'],
          'first_name' => $data['first_name'],
          'last_name_kana' => $data['last_name_kana'],
          'first_name_kana' => $data['first_name_kana'],
          'address01' => $data['address01'],
          'address02' => $data['address02'],
          'address03' => $data['address03'],
          'address04' => $data['address04'],
          'address05' => $data['address05'],
          // 'unei_company' => $data['unei_company'],
          // 'pay' => $data['pay'],
      ]);
      // if(isset($data['unei_company'])){
      // 'unei_company' => $data['unei_company'],
      // }

      // かけ払いAPIに送信
      // $client = new Client();
      // $url = 'https://demo.yamato-credit-finance.jp/kuroneko-anshin/AN060APIAction.action';
      // $option = [
      //   'headers' => [
      //     'Accept' => '*/*',
      //     'Content-Type' => 'application/x-www-form-urlencoded',
      //     'charset' => 'UTF-8',
      //   ],
      //   'form_params' => [
      //     'traderCode' => '330000051',
      //     'cId' => $user_id,
      //     'hjkjKbn' => '1',
      //     'houjinKaku' => '1',
      //     'houjinZengo' => '1',
      //     'sMei' => '黒猫商店',
      //     'shitenMei' => '高田馬場支店',
      //     'sMeikana' => 'クロネコショウテン',
      //     'shitenMeikana' => 'タカ',
      //     'ybnNo' => '7200824',
      //     'Adress' => '広島県福山市多治米町',
      //     'telNo' => '080-2888-5281',
      //     'keitaiNo' => '080-2888-5281',
      //     'gyscod1' => '07',
      //     'gyscod2' => '082',
      //     'setsurituNgt' => '200104',
      //     'shk' => '12345',
      //     'nsyo' => '12345',
      //     'kmssyainsu' => '12345',
      //     'daikjmeiSei' => '黒猫',
      //     'daikjmeiMei' => '花子',
      //     'daiknameiSei' => 'クロネコ',
      //     'daiknameiMei' => 'ハナコ',
      //     'daiYbnno' => '7200824',
      //     'daiAddress' => '広島県福山市多治米町',
      //     'szUmu' => '0',
      //     'szHjkjKbn' => '1',
      //     'szHoujinKaku' => '12',
      //     'szHoujinZengo' => '1',
      //     'szHonknjmei' => '黒猫商店',
      //     'szHonknamei' => 'クロネコショウテン',
      //     'szYbnno' => '7200824',
      //     'szAddress' => '広島県福山市多治米町',
      //     'szTelno' => '0849525627',
      //     'szDaikjmei_sei' => '黒猫',
      //     'szDaikjmei_mei' => '花子',
      //     'szDaiknamei_sei' => 'クロネコ',
      //     'szDaiknamei_mei' => 'ハナコ',
      //     'sqssfKbn' => '1',
      //     'sqYbnno' => '7200824',
      //     'sqAddress' => '広島県福山市多治米町',
      //     'sofuKnjnam' => '黒猫商店',
      //     'sofuTntnam' => '黒猫花子',
      //     'syz' => '購買部',
      //     'kmsTelno' => '084-952-5627',
      //     'shrhohKbn' => '2',
      //     'passWord' => 'UzhJlu8E'
      //   ]
      // ];
      // $response = $client->request('POST', $url, $option);
      // $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result);

      return $create_user;
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
