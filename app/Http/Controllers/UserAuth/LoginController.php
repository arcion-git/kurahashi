<?php

namespace App\Http\Controllers\UserAuth;

use App\User;
use App\Cart;
use App\Order;

// 時間に関する処理
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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



    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // public $redirectTo = '/user/home';

    public $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    public function showWelcome(){
        return view('user.auth.welcome');
    }

    public function showWelcomeguide(){
        return view('user.auth.welcomeguide');
    }

    public function showWelcomelow(){
        return view('user.auth.welcomelow');
    }

    public function showWelcomeprivacypolicy(){
        return view('user.auth.welcomeprivacypolicy');
    }

    public function showWelcomecontact(){
        return view('user.auth.welcomecontact');
    }

    public function welcomepostcontact(Request $request){
        $name = $request->name;
        $address = $request->address;
        $email = $request->email;
        $tel = $request->tel;
        $shubetu = $request->shubetu;
        $naiyou = $request->naiyou;
        $contact = Contact::create(['name'=> $name, 'address'=> $address ,'email'=> $email, 'tel'=> $tel, 'shubetu'=> $shubetu, 'naiyou'=> $naiyou ]);
        $admin_mail = config('app.admin_mail');
        $text = 'この度はお問い合わせいただきありがとうございます。<br />下記の内容でお問い合わせを受け付けました。<br />
        <br />
        お名前：'.$name.'<br />
        ご住所：'.$address.'<br />
        メールアドレス：'.$email.'<br />
        電話番号：'.$tel.'<br />
        お問い合わせ種別：'.$shubetu.'<br />
        お問い合わせ内容：<br />
        '.$naiyou.'<br />
        <br />内容を確認し、担当より1〜3営業日以内にご返信させていただきます。
        <br />今しばらくお待ちください。';
        Mail::send('emails.register', [
            'name' => $name,
            'text' => $text,
            'admin_mail' => $admin_mail,
        ], function ($message) use ($email , $admin_mail) {
            $message->to($email)->bcc($admin_mail)->subject('SETOnagiオーダーブックお問い合わせ完了のお知らせ');
        });
        $message = 'お問い合わせ内容が送信されました';
        $data=[
          'message'=>$message,
        ];
        // return view()->route('welcomecontact',$data);
        return view('user/auth/welcomecontact', ['data' => $data]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }

    // ログイン後のリダイレクト先を変更
    public function redirectPath()
    {
        return 'setonagi';
    }

    protected function authenticated(\Illuminate\Http\Request $request)
    {
        // 先日以降にカートに追加された商品を削除
        // dd($request->email);
        $user = User::where(['email'=> $request->email])->first();
        $yesterday = Carbon::yesterday();
        $carts = Cart::where(['user_id'=> $user->id,'deal_id'=> null])
        ->whereDate('created_at', '<=' , $yesterday)->get();
        if($carts){
          foreach ($carts as $key => $value) {

            $orders=Order::where(['cart_id'=> $value->id])->delete();
          }
        }
        $carts = Cart::where(['user_id'=> $user->id,'deal_id'=> null])
        ->whereDate('created_at', '<=' , $yesterday)->delete();
        return redirect()->route('setonagi');
    }


}
