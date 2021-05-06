<?php

namespace App\Http\Controllers;

use App\User;
use App\Cart;
use App\Deal;
use App\Item;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminPageController extends Controller
{
  public function __construct(){
		$this->middleware('admin');
	}

  public function index()
  {
    $deals =  Deal::get();
    return view('admin/home', ['deals' => $deals]);
  }

  public function dealdetail($id){
    $deal = Deal::where('id',$id)->first();

    $carts = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'deal'=>$deal
    ];
    return view('dealdetail', $data);
  }

  public function updatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $id = $cart->user_id;
    return redirect()->route('admin.dealdetail',$id);
  }


  public function intervalupdatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $data=[
      'discount'=>$cart->discount,
      'quantity'=>$cart->quantity,
    ];

    return view('dealdetail', $data);
  }




  public function user(){

    $users = User::get();

    return view('admin.auth.user', ['users' => $users]);
  }

  public function item(){

    $items = Item::get();

    return view('admin.auth.item', ['items' => $items]);
  }

  public function import(){
    return view('admin.auth.import');
  }

  public function download(){
    return view('admin.auth.download');
  }

  public function userdeal($id){

    $deals = Deal::where('user_id',$id)->get();

    return view('admin.home', ['deals' => $deals]);
  }

}
