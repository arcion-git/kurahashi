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
use App\Recommend;
use App\RecommendCategory;
use App\CartNini;
use App\OrderNini;
use App\Repeatcart;
use App\Repeatorder;


// デバッグを出力
use Log;
use Response;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// API通信
use GuzzleHttp\Client;


class LoginPageController extends Controller
{
  public function __construct(){
		$this->middleware('user');
	}




  public function questionnaire()
  {
    // if ( Auth::guard('admin')->check() ){
    //     Auth::guard('admin')->logout();
    // }

      $categories = Category::get();
      $categories = $categories->groupBy('bu_ka_name');
      // dd($categories);

      $user_id = Auth::guard('user')->user()->id;
      $carts =  Cart::where('user_id',$user_id)->get();

      return view('user/auth/questionnaire', ['categories' => $categories]);
  }



  public function index()
  {
    if ( Auth::guard('admin')->check() ){
        Auth::guard('admin')->logout();
    }

      // $search = $request->search;
      // dd($search);

      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }


      $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);

      $categories = Category::get()->groupBy('bu_ka_name');

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $carts =  Cart::where('user_id',$user_id)->get();

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;

      $now = Carbon::now()->addDay(3)->format('Y-m-d');

      $recommends = Recommend::where('user_id', $kaiin_number)->whereDate('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();

      $special_prices = SpecialPrice::get();

      return view('user/home',
      ['items' => $items ,
       'carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }




    public function search(Request $request)
    {

        $search = $request->search;


        $user_id = Auth::guard('user')->user()->id;
        $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }

        $cat = $request->cat;
        if($cat == -1){
          $items = Item::Where('zaikosuu', '>=', '0.01')
          ->where(function($items) use($search){
            $items->where('item_name','like', "%$search%")->orWhere('item_id','like', "%$search%");
          })->paginate(30);

          // $items::where('item_name','like', "%$search%")->orWhere('item_id','like', "%$search%")->paginate(30);
          // $items = Item::Where('item_id',$search)->orWhere('item_name','like', "%$search%")->where('zaikosuu', '!=', '0')->paginate(30);
          $search_category = null;
          $search_category_parent = null;
        }elseif(is_numeric($cat)){
          $cat_id = $request->cat;
          $search_category = Category::where('category_id',$cat_id)->first();
          // $items = $search_category->items()->where('zaikosuu', '>=', '0.01')->Where('item_name','like', "%$search%")->paginate(30);

          $items = $search_category->items()->where('zaikosuu', '>=', '0.01')->Where('item_name','like', "%$search%")->paginate(30);
          // dd($items);

          $search_category_parent = null;
        }else{
          $items = Item::Where('busho_name',$cat)->Where('zaikosuu', '>=', '0.01')
          ->where(function($items) use($search){
            $items->where('item_name','like', "%$search%")->orWhere('item_id','like', "%$search%");
          })->paginate(30);
          $search_category = null;
          $search_category_parent = $cat;
        }

        $categories = Category::get()->groupBy('bu_ka_name');

        $user_id = Auth::guard('user')->user()->id;
        $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
        $carts =  Cart::where('user_id',$user_id)->get();
        $kaiin_number = Auth::guard('user')->user()->kaiin_number;
        $now = Carbon::now()->addDay(3)->format('Y-m-d');
        $recommends = Recommend::where('user_id', $kaiin_number)->whereDate('end', '>=', $now)->orWhere('end',null)->get();
        // dd($categories);

        return view('user/auth/search',
        ['items' => $items ,
         'carts' => $carts ,
         'categories' => $categories ,
         'favorite_categories' => $favorite_categories,
         'recommends' => $recommends,
         'search' => $search,
         'search_category' => $search_category,
         'search_category_parent' => $search_category_parent,
        ]);
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

      $category = Category::where('category_id',$id)->first();

      // $category_items = CategoryItem::where('category_id',$id)->get();
      // dd($category_items);
      $items = Item::get();

      // $items = Tag::first()->items()->get();

      $items = Category::where('category_id',$id)->first()->items()->where('zaikosuu', '>=', '0.01')->paginate(30);

      $categories = Category::get()->groupBy('bu_ka_name');
      // $category_name = Category::where('category_id',$id)->first()->category_name;
      $category_name = Category::where('category_id',$id)->first()->category_name;
      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
      $carts =  Cart::where('user_id',$user_id)->get();

      $now = Carbon::now()->addDay(3)->format('Y-m-d');
      $recommendcategories = RecommendCategory::where('category_id', $category->category_id)->whereDate('end', '>=', $now)->orWhere('end',null)->get();
      // dd($recommendcategories);


      return view('user/home', ['items' => $items , 'carts' => $carts , 'categories' => $categories ,  'category_name' => $category_name ,'favorite_categories' => $favorite_categories ,'recommendcategories' => $recommendcategories]);
  }


  public function test(Request $request){
    // 直近の納品予定日を取得
    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date')->toArray();
    for($i = 2; $i < 10; $i++){
      $today_plus = date('Y/m/d', strtotime($today.'+'.$i.'day'));
      // dd($today_plus2);
      $key = array_search($today_plus,(array)$holidays,true);
      if($key){
          // 休みでないので納品日を格納
          $nouhin_yoteibi = $today_plus;
          break;
      }else{
          // 休みなので次の日付を探す
      }
    }
    return redirect()->route('home');
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

    $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
    // dd($price_groupe->price_groupe);
    // $price_groupe = $store->price_groupe();

    // dd($item);
    // dd($item->sku_code);
    $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code])->first();
 // dd($price);

    $cart=Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null]);
    $cart->save();

    // 直近の納品予定日を取得
    $today = date("Y/m/d");
    $holidays = Holiday::get('date');
    // $holidays = Holiday::pluck('date');
    // $holiday = array_column($holidays, 'date');
    Log::debug($holidays);


    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date')->toArray();
    for($i = 3; $i < 10; $i++){
      $today_plus = date('Y/m/d', strtotime($today.'+'.$i.'day'));
      // dd($today_plus2);
      $key = array_search($today_plus,(array)$holidays,true);
      if($key){
          // 休みでないので納品日を格納
      }else{
          // 休みなので次の日付を探す
          $nouhin_yoteibi = $today_plus;
          break;
      }
    }

    $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
    if(isset($price->price)){
    $order->price = $price->price;
    }

    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $item = Item::where('id',$item_id)->first();

    $recommend_item = Recommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'user_id'=>$kaiin_number])->first();
    if(isset($recommend_item->price)){
    $order->price = $recommend_item->price;
    }

    $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
    if(isset($special_price_item->price)){
    $order->price = $special_price_item->price;
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





// カート画面から削除
  public function removecart(Request $request){
    $cart_id = $request->cart_id;
    $delete_cart = Cart::where(['id'=> $cart_id])->first()->delete();
    $delete_order = Order::where(['cart_id'=> $cart_id])->first()->delete();
    $data = "sucsess";
    return redirect()->route('home',$data);
  }


// 商品の配送先を削除
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

// 任意の商品の配送先を削除
  public function removeordernini(Request $request){
    $order_nini_id = $request->order_nini_id;
    $cart_nini_id = $request->cart_nini_id;

    $delete_order=OrderNini::where(['id'=> $order_nini_id])->first()->delete();

    $orders=OrderNini::where(['cart_nini_id'=> $cart_nini_id])->first();
    if(empty($orders)){
    $delete_cart=CartNini::where(['id'=> $cart_nini_id])->first()->delete();
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


  // 配送先店舗を変更
  public function change_store(Request $request){
    $order_id = $request->order_id;
    $store_name = $request->store_name;
    $tokuisaki_name = $request->tokuisaki_name;

    $store = Store::where(['tokuisaki_name'=>$tokuisaki_name,'store_name'=> $store_name])->first();
    $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store->tokuisaki_id,'store_id'=> $store->store_id ])->first();

    $order = Order::where(['id'=> $order_id])->first();
    $cart_id = $order->cart_id;
    $cart = Cart::where(['id'=> $cart_id])->first();
    $item = Item::where('id',$cart->item_id)->first();

    $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id, 'sku_code'=> $item->sku_code])->first();

    $order = Order::where(['id'=> $order_id])->update(['store_name'=> $store_name,'tokuisaki_name'=> $tokuisaki_name,'price'=> $price->price]);
    // Log::debug($order);

    $data = "success";

    return redirect()->route('home',$data);
  }

  // 全店舗に追加
  public function add_all_store(Request $request){
    $order_id = $request->order_id;

    $order = Order::where(['id'=> $order_id])->first();
    $cart_id = $order->cart_id;
    $cart = Cart::where(['id'=> $cart_id])->first();

    $item = Item::where('id',$cart->item_id)->first();

    // 保存中の店舗を削除
    $order_delete = Order::where('cart_id', $cart_id)->delete();

    // 店舗を抽出して保存
    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);

    foreach ($store_users as $store_user) {
      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();

      $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();

      $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code])->first();

      $order=Order::create(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'price'=> $price->price ])->first();
      // Log::debug("count");
    }
    $data = "success";

    return redirect()->route('home',$data);
  }

  // 任意の配送先店舗を変更
  public function nini_change_store(Request $request){
    $order_nini_id = $request->order_nini_id;
    $nini_store_name = $request->nini_store_name;
    $nini_tokuisaki_name = $request->nini_tokuisaki_name;
    $order_nini = OrderNini::where(['id'=> $order_nini_id])->update(['store_name'=> $nini_store_name,'tokuisaki_name'=> $nini_tokuisaki_name]);
    $data = "success";
    return redirect()->route('home',$data);
  }

  // 任意の商品の配送先を全店舗に追加
  public function nini_add_all_store(Request $request){
    $order_nini_id = $request->order_nini_id;

    $order_nini = Ordernini::where(['id'=> $order_nini_id])->first();



    // 保存中の店舗を削除
    $order_nini_delete = Ordernini::where('cart_nini_id', $order_nini->cart_nini_id)->delete();

    // 店舗を抽出して保存
    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);

    // Log::debug($store_users);

    foreach ($store_users as $store_user) {
      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      $order_nini=Ordernini::create(['cart_nini_id'=> $order_nini->cart_nini_id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1]);
    }
    $data = "success";

    return redirect()->route('home',$data);
  }

  // 任意の担当を保存
  public function nini_change_tantou(Request $request){
    $nini_tantou = $request->nini_tantou;
    $cart_nini_id = $request->cart_nini_id;
    $cart_nini_tantou= CartNini::where(['id'=> $cart_nini_id])->update(['tantou_name'=> $nini_tantou]);
    $data = "success";
    return redirect()->route('home',$data);
  }

  // 任意の商品名を保存
  public function nini_change_item_name(Request $request){
    $nini_item_name = $request->nini_item_name;
    $cart_nini_id = $request->cart_nini_id;
    $cart_nini_item_name= CartNini::where(['id'=> $cart_nini_id])->update(['item_name'=> $nini_item_name]);
    $data = "success";
    return redirect()->route('home',$data);
  }

  // 任意の数量を保存
  public function nini_change_quantity(Request $request){
    $nini_quantity = $request->nini_quantity;
    $order_nini_id = $request->order_nini_id;
    $order_nini_quantity = OrderNini::where(['id'=> $order_nini_id])->update(['quantity'=> $nini_quantity]);
    $data = "success";
    return redirect()->route('home',$data);
  }

  // 任意の納品予定日を保存
  public function nini_change_nouhin_yoteibi(Request $request){
    $nini_nouhin_yoteibi = $request->nini_nouhin_yoteibi;
    $order_nini_id = $request->order_nini_id;
    $order_nini_quantity = OrderNini::where(['id'=> $order_nini_id])->update(['nouhin_yoteibi'=> $nini_nouhin_yoteibi]);
    $data = "success";
    return redirect()->route('home',$data);
  }

  public function clonecart(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $cart_id = $request->cart_id;
    $order=Order::Create(['cart_id'=> $cart_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    $data = "sucsess";
    return redirect()->route('home',$data);
  }

  public function addniniorder(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $cart_nini=CartNini::Create(['user_id'=> $user_id]);
    $order_nini=OrderNini::Create(['cart_nini_id'=> $cart_nini->id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    $data = "sucsess";
    return redirect()->route('home',$data);
  }

  public function addordernini(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $cart_nini_id = $request->cart_nini_id;
    $order=OrderNini::Create(['cart_nini_id'=> $cart_nini_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
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
     'user_id' => $user_id,
    ]);

  }

  public function approval(){

    $categories = Category::get()->groupBy('bu_ka_name');
    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date');
    $approval = 1;

    return view('user/auth/approval',
    ['carts' => $carts,
     'categories' => $categories,
     'favorite_categories' => $favorite_categories,
     // 'stores' => $stores,
     'holidays' => $holidays,
     'user_id' => $user_id,
     'approval' => $approval,
    ]);

  }


// カート画面からの遷移先
  public function order(){

    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

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
     'cart_ninis' => $cart_ninis,
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


  public function EditFavoriteCategory(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = $request->input('favorite_category');
    // dd($favorite_categories);

    $delete_favorite_categories = FavoriteCategory::where('user_id', $user_id)->delete();

    foreach ($favorite_categories as $favorite_category) {
      $favorite_category=FavoriteCategory::firstOrNew(['user_id'=> $user_id , 'category_id'=> $favorite_category]);
      $favorite_category->save();
    }

    $user = User::where('id', $user_id)->first();
    $user->first_login = 1;
    $user->save();

    $data = "sucsess";
    return redirect()->route('favorite',$data);
  }


  public function deal(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $deals =  Deal::where('user_id',$user_id)->latest('created_at')->paginate(30);

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

    $deal =  Deal::where(['id'=>$deal_id])->first();

    // 取引IDが一致しているものを取得
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
    $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();

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
     'cart_ninis' => $cart_ninis,
     'stores' => $stores,
     'holidays' => $holidays,
     'deal' => $deal,
    ];
    return view('order', $data);
  }








  public function adddeal(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    $data = $request->all();
    $item_ids = $data['item_id'];

    // dd($data);

    // 在庫チェック
    if($request->has('addsuscess_btn')){
      foreach($item_ids as $key => $input) {
        $cart = Cart::where(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> null])->first();
        $item = item::where(['id'=> $item_ids[$key]])->first();
        $item_name = $item->item_name;
        // dd($item);
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        // dd($orders);
        // 注文個数計算
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['quantity'];
        }
        $nokori_zaiko = $item->zaikosuu - $total;
        // dd($nokori_zaiko);
        if($nokori_zaiko < 0){
          // 在庫不足の場合カート画面に戻す
          $data=[
            'nokori_zaiko' => $nokori_zaiko,
            'item_name' => $item_name,
          ];
          return redirect()->route('confirm',$data);
        }
      }
      // 在庫がある場合商品の在庫数を減らす
      foreach($item_ids as $key => $input) {
        $cart = Cart::where(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> null])->first();
        $item = item::where(['id'=> $item_ids[$key]])->first();
        $item_name = $item->item_name;
        // dd($item);
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        // dd($orders);
        // 注文個数計算
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['quantity'];
        }
        $nokori_zaiko = $item->zaikosuu - $total;
        // dd($nokori_zaiko);
        $item->zaikosuu = $nokori_zaiko;
        $item->save();
      }
    }

    // dd($item_nini_ids);
    // $quantitys = $data['quantity'];

    if(isset($request->memo)){
    $deal = Deal::create(['user_id'=> $user_id, 'memo'=> $request->memo]);
    }else{
    $deal = Deal::create(['user_id'=> $user_id]);
    }
    $deal_id = $deal->id;


    if(isset($data['cart_nini_id'])){
      $item_nini_ids = $data['cart_nini_id'];
      // 任意のカートにオーダーIDを保存
      foreach($item_nini_ids as $key => $input) {
        $cart_nini = CartNini::firstOrNew(['user_id'=> $user_id , 'deal_id'=> null]);
        $cart_nini->deal_id = $deal_id;
        $cart_nini->save();
      }
    }

    // カートにオーダーIDを保存
    foreach($item_ids as $key => $input) {
      $cart = Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_ids[$key], 'deal_id'=> null]);
      $cart->deal_id = $deal_id;
      $cart->save();
    }

    if($request->has('addsuscess_btn')){

      // $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $$deal_id])->get();
      // dd($carts);

      $deal=Deal::firstOrNew(['id'=> $deal_id]);
      $deal->success_flg = True;
      $deal->success_time = Carbon::now();
      $deal->save();

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
    $deal->memo = $request->memo;
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

  public function repeatorder(){
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $kaiin_number = $user->kaiin_number;
    // dd($user_id);
    $items = Item::get();
    // 店舗一覧取得
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
    $stores = [];
    $n=1;
    foreach ($store_users as $store_user) {
    $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      array_push($stores, $store);
    $n++;
    }
    $repeatcarts = Repeatcart::where('kaiin_number',$user->kaiin_number)->get();

    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    $data=[
      'id'=>$user_id,
      'items'=>$items,
      'stores'=>$stores,
      'user'=>$user,
      'repeatcarts'=>$repeatcarts,
      'categories' => $categories,
      'favorite_categories' => $favorite_categories
    ];
    return view('repeatorder', $data);
  }


  public function getzipcode(){
    return view('zipcode');
  }


  public function postzipcode(Request $request){
      $zipcode = $request->zipcode;

      // dd($zipcode);
      $client = new Client();

      // $url = 'http://zipcloud.ibsnet.co.jp/api/search';
      // $option = [
      //   'headers' => [
      //     'Accept' => '*/*',
      //     'Content-Type' => 'application/x-www-form-urlencoded'
      //   ],
      //   'form_params' => [
      //     'zipcode' => $zipcode
      //   ]
      // ];
      // $response = $client->request('POST', $url, $option);
      // $result = json_decode($response->getBody()->getContents(), true);
      // dd($result);

      $url = 'https://demo.yamato-credit-finance.jp/kuroneko-anshin/AN060APIAction.action';
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => '330000051',
          'cId' => '1234-57',
          'hjkjKbn' => '1',
          'houjinKaku' => '1',
          'houjinZengo' => '1',
          'sMei' => '黒猫商店',
          'shitenMei' => '高田馬場支店',
          'sMeikana' => 'クロネコショウテン',
          'shitenMeikana' => 'タカ',
          'ybnNo' => '7200824',
          'Adress' => '広島県福山市多治米町',
          'telNo' => '080-2888-5281',
          'keitaiNo' => '080-2888-5281',
          'gyscod1' => '07',
          'gyscod2' => '082',
          'setsurituNgt' => '200104',
          'shk' => '12345',
          'nsyo' => '12345',
          'kmssyainsu' => '12345',
          'daikjmeiSei' => '黒猫',
          'daikjmeiMei' => '花子',
          'daiknameiSei' => 'クロネコ',
          'daiknameiMei' => 'ハナコ',
          'daiYbnno' => '7200824',
          'daiAddress' => '広島県福山市多治米町',
          'szUmu' => '0',
          'szHjkjKbn' => '1',
          'szHoujinKaku' => '12',
          'szHoujinZengo' => '1',
          'szHonknjmei' => '黒猫商店',
          'szHonknamei' => 'クロネコショウテン',
          'szYbnno' => '7200824',
          'szAddress' => '広島県福山市多治米町',
          'szTelno' => '0849525627',
          'szDaikjmei_sei' => '黒猫',
          'szDaikjmei_mei' => '花子',
          'szDaiknamei_sei' => 'クロネコ',
          'szDaiknamei_mei' => 'ハナコ',
          'sqssfKbn' => '1',
          'sqYbnno' => '7200824',
          'sqAddress' => '広島県福山市多治米町',
          'sofuKnjnam' => '黒猫商店',
          'sofuTntnam' => '黒猫花子',
          'syz' => '購買部',
          'kmsTelno' => '084-952-5627',
          'shrhohKbn' => '2',
          'passWord' => 'UzhJlu8E'
        ]
      ];
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // $result = json_decode($response->getBody()->getContents(), true);
      dd($result);

  }




}
