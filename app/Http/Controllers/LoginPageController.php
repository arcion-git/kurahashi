<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Deal;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LoginPageController extends Controller
{
  public function __construct(){
		$this->middleware('user');
	}

  public function index()
  {
      $items = \DB::table('items')->get();

      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/home', ['items' => $items , 'carts' => $carts]);
  }

  public function addcart(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;

    $cart=Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_id , 'deal_id'=> null]);
    $cart->quantity = $request->quantity;
    $cart->save();

    $data = "sucsess";
      return redirect()->route('home',$data);
  }


  public function removecart(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;
    $cart=Cart::where(['user_id'=> $user_id , 'item_id'=> $item_id , 'deal_id'=> null])->delete();
    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function cart(){
    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    return view('cart', ['carts' => $carts]);
  }

  public function confirm(){
    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    return view('user/auth/confirm', ['carts' => $carts]);
  }

  public function deal(){
    $user_id = Auth::guard('user')->user()->id;
    $deals =  Deal::where('user_id',$user_id)->get();

    return view('deal', ['deals' => $deals]);
  }

  public function dealdetail($id){
    $user_id = Auth::guard('user')->user()->id;
    $deal = Deal::where('id',$id)->first();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'deal'=>$deal
    ];
    return view('dealdetail', $data);
  }

  public function adddeal(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    $data = $request->all();
    $item_ids = $data['item_id'];
    $quantitys = $data['quantity'];

    $deal=Deal::create(['user_id'=> $user_id]);
    $deal_id = $deal->id;

    foreach($item_ids as $key => $input) {
      $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> null]);
      $cart->user_id = $user_id;
      $cart->deal_id = $deal_id;
      $cart->quantity = isset($quantitys[$key]) ? $quantitys[$key] : null;
      $cart->save();
    }
    return redirect('deal');
  }

  public function addsuscess(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    $data = $request->all();
    $item_ids = $data['item_id'];
    $quantitys = $data['quantity'];

    $deal_id = $request->deal_id;

    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->success_flg = True;
    $deal->success_time = Carbon::now();
    $deal->save();

    foreach($item_ids as $key => $input) {
      $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> $deal_id]);
      $cart->user_id = $user_id;
      $cart->deal_id = $deal_id;
      $cart->quantity = isset($quantitys[$key]) ? $quantitys[$key] : null;
      $cart->save();
    }
    return redirect('deal');
  }

  public function updatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $id = $cart->deal_id;
    return redirect()->route('dealdetail',$id);
  }

}
