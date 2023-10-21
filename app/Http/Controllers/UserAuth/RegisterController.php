<?php

namespace App\Http\Controllers\UserAuth;

use App\User;
use App\Setonagi;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Mail;

// BtoC向け
use App\ShippingCalender;
use App\ShippingCompanyCode;
use App\ShippingInfo;
use App\ShippingSetting;

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
        // dd($data);
        // URLからtypeパラメータを取得
        if(isset($data['type'])){
          // typeパラメータが存在する場合の処理
          // dd($data['type']);
          $rules = [
              'first_name' => ['required', 'string', 'max:255'],
              'last_name' => ['required', 'string', 'max:255'],
              'first_name_kana' => ['required', 'string', 'max:255'],
              'last_name_kana' => ['required', 'string', 'max:255'],
              'address01' => ['required', 'string', 'max:8'],
              'address02' => ['required', 'string', 'max:255'],
              'address03' => ['required', 'string', 'max:255'],
              'address04' => ['required', 'string', 'max:255', 'regex:/^[^\x01-\x7E]+$/u'],
              'tel' => ['required', 'string', 'max:20', 'regex:/^\d{2,5}-\d{1,4}-\d{4}$/'],
              // 'company_name' => ['required'],
              'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
              'password' => ['required', 'string', 'min:8', 'confirmed'],
          ];

          $validator = Validator::make($data, $rules);

          // カスタムエラーメッセージを設定
          $customMessages = [
              // 'company_name.required' => '会社名は必須です。',
          ];

          $validator->setCustomMessages($customMessages);

          return $validator;

        } else {
          // typeパラメータが存在しない場合の処理
          return Validator::make($data, [
            'hjkjKbn' => ['required'],
            'pay' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name_kana' => ['required', 'string', 'max:255'],
            'last_name_kana' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'company_kana' => ['required', 'string', 'max:255'],
            'address01' => ['required', 'string', 'max:8'],
            'address02' => ['required', 'string', 'max:255'],
            'address03' => ['required', 'string', 'max:255'],
            'address04' => ['required', 'string', 'max:255', 'regex:/^[^\x01-\x7E]+$/u'],
            'tel' => ['required', 'string', 'max:20', 'regex:/^\d{2,5}-\d{1,4}-\d{4}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
          ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

      // dd($data);

      // バリデーションからスタート
      // dd($result);
      // if(isset($data['type'])){
      //   dd('BtoC');
      // }else{
      //   dd('BtoB');
      // }

      $create_user = User::create([
          'email' => $data['email'],
          'password' => Hash::make($data['password']),
          'name' => $data['last_name'].$data['first_name'],
          'name_kana' => $data['last_name_kana'].$data['first_name_kana'],
          'tel' => $data['tel'],
      ]);
      $create_user->setonagi = 1;
      $create_user->save();

      if(isset($data['type'])){
        $company = '';
      }else{
        $company = $data['company'];
      }

      $setonagi = Setonagi::create([
          // 'user_id' => $user_id,
          'company' => $company,
          // 'company_kana' => $data['company_kana'],
          'last_name' => $data['last_name'],
          'first_name' => $data['first_name'],
          'last_name_kana' => $data['last_name_kana'],
          'first_name_kana' => $data['first_name_kana'],
          'address01' => $data['address01'],
          'address02' => $data['address02'],
          'address03' => $data['address03'],
          'address04' => $data['address04'],
          // 'unei_company' => $data['unei_company'],
          // 'pay' => $data['pay'],
      ]);

      $user_id = $create_user->id;
      $setonagi->user_id = $user_id;
      $setonagi->save();

      if(isset($data['type'])){
        if(isset($data['company_name'])){
          $company = $data['company_name'];
        }else{
          $company = null;
        }
      }else{
        // BtoSB
        $company = $data['company'];
      }



      if(isset($data['type'])){
        // BtoCのみsetonagi_okを1に変更
        $setonagi->setonagi_ok = 1;
        $setonagi->shipping_code = $data['type'];
        $setonagi->company_name = $company;
        $setonagi->save();
      }

      // if(isset($data['unei_company'])){
      // 'unei_company' => $data['unei_company'],
      // }

      // BtoSBユーザーのみを登録
      if(!isset($data['type'])){
      // かけ払いAPIに送信
      $client = new Client();
      $url = config('app.kakebarai_user_touroku');
      $kakebarai_traderCode = config('app.kakebarai_traderCode');
      // dd($kakebarai_traderCode);
      $kakebarai_passWord = config('app.kakebarai_passWord');
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => $kakebarai_traderCode,
          'cId' => '00'.$user_id,
          'hjkjKbn' => $data['hjkjKbn'],
          'sMei' => $data['company'],
          'shitenMei' => '',
          'sMeikana' => $data['company_kana'],
          'shitenMeikana' => '',
          'ybnNo' => $data['address01'],
          'Adress' => $data['address02'].$data['address03'].$data['address04'],
          'telNo' => $data['tel'],
          // 'keitaiNo' => '080-2888-5281',
          // 'gyscod1' => '',
          // 'gyscod2' => '',
          // 'setsurituNgt' => '',
          // 'shk' => '',
          // 'nsyo' => '',
          // 'kmssyainsu' => '',
          'daikjmeiSei' => $data['last_name'],
          'daikjmeiMei' => $data['first_name'],
          'daiknameiSei' => $data['last_name_kana'],
          'daiknameiMei' => $data['first_name_kana'],

          // 代表者情報エリア(No.3 「法人・個人事業主」が「個人事業主:2」の場合に、指定可能です。)
          // 'daiYbnno' => '',
          // 'daiAddress' => '',

          // 運営会社有無
          'szUmu' => 0,

          // 運営会社情報エリア(No.26「運営会社有無」が「運営会社有り:1」の場合に、指定可能です。)
          // 'szHjkjKbn' => '',
          // 'szHoujinKaku' => '',
          // 'szHoujinZengo' => '1',
          // 'szHonknjmei' => '黒猫商店',
          // 'szHonknamei' => 'クロネコショウテン',
          // 'szYbnno' => '7200824',
          // 'szAddress' => '広島県福山市多治米町',
          // 'szTelno' => '0849525627',
          // 'szDaikjmei_sei' => '黒猫',
          // 'szDaikjmei_mei' => '花子',
          // 'szDaiknamei_sei' => 'クロネコ',
          // 'szDaiknamei_mei' => 'ハナコ',

          // 送付先選択
          'sqssfKbn' => '1',

          // 請求書送付先情報エリア(No.39「送付先選択」が「その他:9」の場合に、指定可能です。)
          // 'sqYbnno' => '7200824',
          // 'sqAddress' => '広島県福山市多治米町',
          // 'sofuKnjnam' => '黒猫商店',
          // 'sofuTntnam' => '黒猫花子',
          // 'syz' => '購買部',
          // 'kmsTelno' => '084-952-5627',

          'shrhohKbn' => $data['pay'],
          'passWord' => $kakebarai_passWord,
        ]
      ];
      if($data['hjkjKbn'] == '1'){
        $option_add = [
          'form_params' => [
            'houjinKaku' => $data['houjinKaku'],
            'houjinZengo' => $data['houjinZengo'],
            // 代表者情報エリア(No.3 「法人・個人事業主」が「個人事業主:2」の場合に、指定可能です。)
            // 'daiYbnno' => $data['daiYbnno'],
            // 'daiAddress' => $data['daiAddress']
          ]
        ];
        $option = array_replace_recursive($option, $option_add);
      }
      // 運営会社情報エリア(No.26「運営会社有無」が「運営会社有り:1」の場合に、指定可能です。)
      if(isset($data['unei_company'])){
        if($data['unei_company'] == '1'){
          $option_add = [
          'form_params' => [
            // 運営会社有無
            'szUmu' => 1,
            // 法人個人
            'szHjkjKbn' => $data['unei_company_hjkjKbn'],
            // 運営会社情報エリア
            'szHonknjmei' => $data['szHonknjmei'],
            'szHonknamei' => $data['szHonknamei'],
            'szYbnno' => $data['szaddress01'],
            'szAddress' => $data['szaddress02'].$data['szaddress03'].$data['szaddress04'],
            'szTelno' => $data['szTelno'],
            'szDaikjmei_sei' => $data['szDaikjmei_sei'],
            // 'szDaikjmei_mei' => $data['szDaikjmei_mei'],
            // 'szDaiknamei_sei' => $data['szDaiknamei_sei'],
            // 'szDaiknamei_mei' => $data['szDaiknamei_mei'],
            ]
          ];
          $option = array_replace_recursive($option, $option_add);
          // dd($option);
        }
        if($data['szHjkjKbn'] == '1'){
          $option_add = [
          'form_params' => [
            'szhoujinKaku' => $data['szhoujinKaku'],
            'szHoujinZengo' => $data['szHoujinZengo'],
            ]
          ];
          $option = array_replace_recursive($option, $option_add);
          // dd($option);
        }
      }
      // 請求書送付先情報エリア(No.39「送付先選択」が「その他:9」の場合に、指定可能です。
      if(isset($data['sqssfKbn'])){
        if($data['sqssfKbn'] == '9'){
          $option_add = [
            'form_params' => [
              // 送付先選択
              'sqssfKbn' => '9',
              // 請求書送付先情報エリア
              'sqYbnno' => $data['sqaddress01'],
              'sqAddress' => $data['sqaddress02'].$data['sqaddress03'].$data['sqaddress04'],
              'sofuKnjnam' => $data['sofuKnjnam'],
              'sofuTntnam' => $data['sofuTntnam'],
              'syz' => $data['syz'],
              'kmsTelno' => $data['kmsTelno'],
            ]
          ];
          $option = array_replace_recursive($option, $option_add);
          // dd($option);
        }
      }
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result);
      }

      // 登録メール送信
      $name = $data['last_name'].$data['first_name'];
      $email = $data['email'];
      $admin_mail = config('app.admin_mail');
      $url = url('');
      if(isset($data['type'])){
        // BtoC
        $text = 'この度はSETOnagiオーダーブックにご登録くださいまして、誠にありがとうございます。<br />
        <br />
        SETOnagiオーダーブックのご利用には、ご登録頂いたメールアドレスとパスワードが必要となりますので、大切に保管くださいますようお願いいたします。<br />
        <br />
        ユーザー登録時にご登録いただいたメールアドレスとパスワードにて、下記URLよりご利用いただけます。<br />
        URL：<a href="'.$url.'">'.$url.'</a>';
      }else{
        // BtoSB
        $text = 'この度はSETOnagiオーダーブックにご登録くださいまして、誠にありがとうございます。<br />
        <br />
        SETOnagiオーダーブックのご利用には、ご登録頂いたメールアドレスとパスワードが必要となりますので、大切に保管くださいますようお願いいたします。<br />
        <br />
        ご登録くださいました情報に基づき、ヤマトクレジットファイナンス株式会社の審査に進ませていただきます。<br />
        審査完了までに、最大で2営業日いただいております。<br />
        審査が完了し次第、改めて、メールにてご案内いたします。<br />
        オーダーブックのご利用開始まで、今しばらくお待ちください。';
      }
      Mail::send('emails.register', [
          'name' => $name,
          'text' => $text,
          'admin_mail' => $admin_mail,
      ], function ($message) use ($email , $admin_mail) {
          $message->to($email)->subject('SETOnagiオーダーブックにご登録いただきありがとうございます。');
      });

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm_c()
    {
      // URLからtypeパラメータを取得する
        $type = $_GET['type'] ?? null;
        $shipping_info = ShippingInfo::where('shipping_code',$type)->first();

        $redirectUrl = url(''); // リダイレクト先URLを指定
        if(!isset($shipping_info)){
          $queryParameters=[
            'type' => $type,
            'message' => 'QRコードが正くありません',
          ];
          $redirectUrlWithQuery = $redirectUrl . '?' . http_build_query($queryParameters);
          return redirect()->to($redirectUrlWithQuery);
        }
        $company_names = ShippingCompanyCode::where('shipping_code',$type)->get();
        // dd($company_names);
        $data=[
          'company_names'=>$company_names,
          'shipping_info'=>$shipping_info,
        ];
        return view('user.auth.register_c', $data);
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
