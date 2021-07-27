<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Deal;
use App\Item;
use App\Category;
use App\Tag;
use App\User;
use App\Holiday;
use App\Store;
use App\StoreUser;
use App\FavoriteCategory;
use App\Price;
use App\PriceGroupe;
use App\SpecialPrice;

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
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

        if ($favorite_categories === null) {
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

    $item = Item::where('id',$item_id)->first();

    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    // dd($kaiin_number);
    $store_user = StoreUser::where('user_id',$kaiin_number)->first(['store_id','tokuisaki_id']);
    // dd($store_user);
    $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
    // dd($store);
    $price_groupe = $store->price_groupe();
    // dd($price_groupe->price_groupe);
    // dd($item->sku_code);
    $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code])->first();
 // dd($price);

    $cart=Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_id , 'deal_id'=> null]);
    $cart->save();

    $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 ]);
    if(isset($price->price)){
    $order->price = $price->price;
    }
    $order->save();

    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function updateorder(Request $request){

    $prices = $request->array;

    $kekka=1;
    foreach($prices as $key => $value) {
    $order = Order::where(['id'=> $key , 'price'=> $value])->first();
    if ($order){
    }else{
    $kekka=0;
    }
    }

    return ($kekka);
  }



  public function updateordergtest(Request $request){

    // dd($request->order_id);

    $prices = array_combine($request->order_id, $request->price);
    // dd($prices);

    $kekka=1;
    foreach($prices as $key => $value) {
    $order = Order::where(['id'=> $key , 'price'=> $value])->first();
    if ($order){
    }else{
    $kekka=0;
    }
    }
    dd($kekka);

    return ($kekka);
  }






  public function removecart(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;

    // $orders=Cart::where(['user_id'=> $user_id , 'item_id'=> $item_id , 'deal_id'=> null])->first()->orders()->delete();
    // $cart=Cart::where(['user_id'=> $user_id , 'item_id'=> $item_id , 'deal_id'=> null])->delete();

    $orders=Cart::where(['id'=> $cart_id])->first()->orders()->get();
    if(empty($orders)){
    $delete_cart=Cart::find(['id'=> $cart_id])->delete();
    // dd($cart_id);
    }

    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function removeorder(Request $request){
    $order_id = $request->order_id;
    $cart_id = $request->cart_id;

    $delete_order=Order::where(['id'=> $order_id])->first()->delete();

    $orders=Order::where(['cart_id'=> $cart_id])->first();
    if(empty($orders)){
    $delete_cart=Cart::where(['id'=> $cart_id])->first()->delete();
    }

    $data = "sucsess";
    return redirect()->route('home',$data);
  }

  public function change_quantity(Request $request){
    $order_id = $request->order_id;
    $quantity = $request->quantity;

    $order=Order::where(['id'=> $order_id])->update(['quantity'=> $quantity]);

    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function change_nouhin_yoteibi(Request $request){
    $order_id = $request->order_id;
    $nouhin_yoteibi = $request->nouhin_yoteibi;

    $order=Order::where(['id'=> $order_id])->update(['nouhin_yoteibi'=> $nouhin_yoteibi]);

    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function change_store(Request $request){
    $order_id = $request->order_id;
    $store_name = $request->store_name;
    $tokuisaki_name = $request->tokuisaki_name;

    $order=Order::where(['id'=> $order_id])->update(['store_name'=> $store_name,'tokuisaki_name'=> $tokuisaki_name]);

    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function clonecart(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $cart_id = $request->cart_id;
    $order=Order::Create(['cart_id'=> $cart_id , 'tokuisaki_name'=>'------' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
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

    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date');

    return view('user/auth/confirm',
    ['carts' => $carts,
     'categories' => $categories,
     'favorite_categories' => $favorite_categories,
     // 'stores' => $stores,
     'holidays' => $holidays,
    ]);

  }


  public function order(){

    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date');

    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
    $stores = [];
    $n=1;
    foreach ($store_users as $store_user) {
    $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      array_push($stores, $store);
    $n++;
    }

    $data=
    ['carts' => $carts,
     'stores' => $stores,
     'holidays' => $holidays,
    ];
    return view('order', $data);
  }









  public function line(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    return view('user.auth.line', ['categories' => $categories, 'favorite_categories' => $favorite_categories]);
  }


  public function favorite(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    return view('user.auth.favorite', ['categories' => $categories, 'favorite_categories' => $favorite_categories]);
  }




  public function deal(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $deals =  Deal::where('user_id',$user_id)->get();

    return view('deal', ['deals' => $deals, 'categories' => $categories, 'favorite_categories' => $favorite_categories]);
  }



  public function dealdetail($id){

    $categories = Category::get()->groupBy('bu_ka_name');
    $user_id = Auth::guard('user')->user()->id;

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    $deal = Deal::where('id',$id)->first();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();

    $data=[
      // 'carts'=>$carts,
      'deal'=>$deal,
      'categories' => $categories,
      'favorite_categories' => $favorite_categories,
    ];
    return view('dealdetail', $data);
  }


  public function dealorder(Request $request){

    $deal_id = $request->deal_id;
    $user_id = Auth::guard('user')->user()->id;

    // 取引IDが一致しているものを取得
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();

    // 休日についての処理
    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date');

    // 店舗一覧取得
    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
    $stores = [];
    $n=1;
    foreach ($store_users as $store_user) {
    $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      array_push($stores, $store);
    $n++;
    }

    $data=
    ['carts' => $carts,
     'stores' => $stores,
     'holidays' => $holidays,
    ];
    return view('order', $data);
  }








  public function adddeal(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    $data = $request->all();
    $item_ids = $data['item_id'];
    // $quantitys = $data['quantity'];

    $deal=Deal::create(['user_id'=> $user_id]);
    $deal_id = $deal->id;

    foreach($item_ids as $key => $input) {
      $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> null]);
      // $cart->user_id = $user_id;
      $cart->deal_id = $deal_id;
      // $cart->quantity = isset($quantitys[$key]) ? $quantitys[$key] : null;
      $cart->save();
    }
    return redirect('deal');
  }

  public function addsuscess(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    // $data = $request->all();
    // $item_ids = $data['item_id'];
    // $quantitys = $data['quantity'];

    $deal_id = $request->deal_id;

    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->success_flg = True;
    $deal->success_time = Carbon::now();
    $deal->save();

    // foreach($item_ids as $key => $input) {
    //   $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> $deal_id]);
    //   $cart->user_id = $user_id;
    //   $cart->deal_id = $deal_id;
    //   $cart->quantity = isset($quantitys[$key]) ? $quantitys[$key] : null;
    //   $cart->save();
    // }

    return redirect('deal');
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
