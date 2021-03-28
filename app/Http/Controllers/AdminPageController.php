<?php

namespace App\Http\Controllers;

use App\User;
use App\Cart;
use App\Deal;

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



}
