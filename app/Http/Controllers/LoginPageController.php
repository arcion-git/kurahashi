<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Deal;
use App\Item;
use App\Category;
use App\Tag;
use App\User;


use App\Store;
use App\StoreUser;

use App\FavoriteCategory;

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
      $categories = Category::get();
      $categories = $categories->groupBy('bu_ka_name');
      // dd($categories);



      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/auth/questionnaire', ['categories' => $categories]);
  }



  public function index()
  {

      $first_login = Auth::guard('user')->user()->first_login;
        if ($first_login === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }


      $items = Item::get();
      $categories = Category::get()->groupBy('bu_ka_name');
      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/home',
      ['items' => $items ,
       'carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories]);
  }


  public function PostFavoriteCategory(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = $request->input('favorite_category');
    // dd($favorite_categories);


    foreach ($favorite_categories as $favorite_category) {
      $favorite_category=FavoriteCategory::firstOrNew(['user_id'=> $user_id , 'category_id'=> $favorite_category]);
      $favorite_category->save();
    }

    $user = User::where('id', $user_id)->first();
    $user->first_login = 1;
    $user->save();

    $data = "sucsess";
    return redirect()->route('home',$data);
  }



  public function category($id)
  {

      $category = Category::find($id);


      // $category_items = CategoryItem::where('category_id',$id)->get();
      // dd($category_items);
      $items = Item::get();

      // $items = Tag::first()->items()->get();

      $items = Category::find($id)->items()->get();


      $categories = Category::get()->groupBy('bu_ka_name');
      $category_name = Category::where('category_id',$id)->first()->category_name;
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/home', ['items' => $items , 'carts' => $carts , 'categories' => $categories ,  'category_name' => $category_name ,'favorite_categories' => $favorite_categories]);
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

    $categories = Category::get()->groupBy('bu_ka_name');
    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    // dd($kaiin_number);
    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get("tokuisaki_id","store_id");
    $store_ids = StoreUser::where('user_id',$kaiin_number)->get("store_id");



    $stores = Store::get();


    foreach($stores as $store) {
    $store = Store::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();
    }
    dd($stores);

    return view('user/auth/confirm', ['carts' => $carts, 'categories' => $categories, 'favorite_categories' => $favorite_categories]);

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
