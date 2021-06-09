<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Deal;
use App\Item;
use App\Category;

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




  public function questionnaire()
  {
      $items = Item::get();

      $categories = Category::get();
      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/auth/questionnaire', ['items' => $items , 'carts' => $carts , 'categories' => $categories]);
  }



  public function index()
  {
      $items = Item::get();

      $categories = Category::get();
      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      // foreach($items as $item){
      //   dd($item->category()->id);
      // }

      return view('user/home', ['items' => $items , 'carts' => $carts , 'categories' => $categories]);
  }

  public function category($id)
  {

      $items = Item::where('category_id',$id)->get();

      $categories = Category::get();
      $category_name = Category::where('id',$id)->first()->category_name;
      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/home', ['items' => $items , 'carts' => $carts , 'categories' => $categories , 'category_name' => $category_name]);
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
    $data=[
      'carts'=>$carts,
    ];
    return view('cart', $data);
  }

  public function dealcart(Request $request){
    $id = $request->deal_id;
    $user_id = Auth::guard('user')->user()->id;
    $deal = Deal::where('id',$id)->first();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'deal'=>$deal
    ];
    return view('dealcart', $data);
  }



  public function confirm(){
    $categories = Category::get();
    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    return view('user/auth/confirm', ['carts' => $carts, 'categories' => $categories]);
  }

  public function deal(){
      $categories = Category::get();
    $user_id = Auth::guard('user')->user()->id;
    $deals =  Deal::where('user_id',$user_id)->get();

    return view('deal', ['deals' => $deals, 'categories' => $categories]);
  }

  public function dealdetail($id){
      $categories = Category::get();
    $user_id = Auth::guard('user')->user()->id;
    $deal = Deal::where('id',$id)->first();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'deal'=>$deal,
      'categories' => $categories,
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

    return; redirect('deal');
  }

  public function updatecart(Request $request){

    $cart_id = $request->cart_id;

    $cart=Cart::where('id',$cart_id)->first();
    $cart->discount = $request->discount;
    $cart->quantity = $request->quantity;
    $cart->save();

    $id = $cart->deal_id;
    $cart=Cart::where('id',$cart_id)->first();

    $data=[
      'discount'=> $cart->discount,
      'quantity'=> $cart->quantity,
      'cart_id' => $cart_id,
    ];
    echo json_encode($data);
    return ;
  }




}
