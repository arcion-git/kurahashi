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
use App\Setonagi;
use App\SetonagiItem;
use App\Favorite;

// デバッグを出力
use Log;
use Response;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// 配列をページネーションする
use Illuminate\Pagination\LengthAwarePaginator;

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



  public function index(Request $request)
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

      $setonagi_user = Auth::guard('user')->user()->setonagi;
      // セトナギユーザーの場合は取得しない
      if(!$setonagi_user){
        // 価格が存在するユーザーごとの商品に絞り込み
        $kaiin_number = Auth::guard('user')->user()->kaiin_number;
        // dd($kaiin_number);
        $store_user = StoreUser::where('user_id',$kaiin_number)->first(['store_id','tokuisaki_id']);
        // dd($store_user);
        $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
        // dd($store);
        $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
        // dd($price_groupe->price_groupe);
        // $price_groupe = $store->price_groupe();
        // dd($price_groupe->price_groupe);

        $prices = Price::where(['price_groupe'=>$price_groupe->price_groupe])->get();

        $user_items = [];
        $n=1;
        foreach ($prices as $price) {
          $item = $price->item();
          // dd($item);
        if($item->zaikosuu >= 0.01){
          array_push($user_items, $item);
        }
        $n++;
        }
        $items = $user_items;
        $items = collect($items);
        // dd($request->page);
        // dd($items);
        // $all_num = count($items);
        // $disp_limit = '10';
        // $items = new LengthAwarePaginator($items , $all_num, $disp_limit, $request->page, array('path' => $request->url()));
        $items = new LengthAwarePaginator(
                    // $items->forPage($request->page, 30),
                    $items->forPage($request->page, 30),
                    // $items->get($request->page - 30),
                    count($items),
                    30,
                    $request->page,
                    array('path' => $request->url() , "pageName" => "page")
                );

        // $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);
                // dd($items->first());
      }else{
        $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);
      }
      // dd($items);





      $categories = Category::get()->groupBy('bu_ka_name');

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $favorite_items = Favorite::where('user_id', $user_id)->get();
      // dd($favorite_items);

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
       'favorite_items' => $favorite_items,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
       'page' => $request->page,
      ]);
  }



  public function saiji()
  {

      $user_id = Auth::guard('user')->user()->id;
      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->first();

        if ($favorite_categories === null) {
          $categories = Category::get();
          $categories = $categories->groupBy('bu_ka_name');
          // dd($categories);
          return view('user/auth/questionnaire', ['categories' => $categories]);
        }

      $categories = Category::get()->groupBy('bu_ka_name');

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $carts =  Cart::where('user_id',$user_id)->get();

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;

      $now = Carbon::now()->addDay(3)->format('Y-m-d');

      $recommends = Recommend::where('user_id', $kaiin_number)->whereDate('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();

      $special_prices = SpecialPrice::get();

      return view('user/saiji',
      ['carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'recommends' => $recommends,
       'special_prices' => $special_prices,
      ]);
  }



  public function setonagi()
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
      $categories = Category::get()->groupBy('bu_ka_name');

      // $items = Item::where('zaikosuu', '>=', '0.01')->paginate(30);


      // foreach($setonagi_items as $setonagi_item){
      //   $setonagi_item = $setonagi_item->item()->item_name;
      // }

      $setonagi_items = SetonagiItem::get();
      $items = [];
      $n=1;

      foreach ($setonagi_items as $setonagi_item) {
      $item = $setonagi_item->item();
      if($item->zaikosuu >= 0.01){
        array_push($items, $setonagi_item);
      }
      $n++;
      }
      $setonagi_items = $items;

      $user_id = Auth::guard('user')->user()->id;

      $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

      $favorite_items = Favorite::where('user_id', $user_id)->get();

      $carts =  Cart::where('user_id',$user_id)->get();

      $kaiin_number = Auth::guard('user')->user()->kaiin_number;

      $now = Carbon::now()->addDay(3)->format('Y-m-d');

      $recommends = Recommend::where('user_id', $kaiin_number)->whereDate('end', '>=', $now)->orWhere('end',null)->where('user_id', $kaiin_number)->get();

      $special_prices = SpecialPrice::get();

      return view('user/setonagi',
      ['setonagi_items' => $setonagi_items,
       'carts' => $carts ,
       'categories' => $categories ,
       'favorite_categories' => $favorite_categories,
       'favorite_items' => $favorite_items,
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
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date')->toArray();
    for($i = 2; $i < 10; $i++){
      $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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

    $setonagi_user = Auth::guard('user')->user()->setonagi;
    // dd($setonagi_user);

    // セトナギユーザーの場合は取得しない
    if(!$setonagi_user){
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
    }

    $cart_in=Cart::where(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null])->first();
    if($cart_in){
      return response()->json([
      'message' => 'cart_in',
      ]);
    }

    $cart=Cart::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null]);
    $cart->save();

    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }

    // hiddenで値を持たせる
    // $setonagi_item_id = $request->setonagi_item_id;
    // if(isset($setonagi_item_id)){
    //   $setonagi_item = SetonagiItem::where(['id'=>$setonagi_item_id])->first();
    //   $price = $setonagi_item->price;
    //   $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
    //   if(isset($price)){
    //   $order->price = $price;
    //   }
    // }else{
    //  $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
    //  if(isset($price->price)){
    //  $order->price = $price->price;
    //  }
    // }

    // セトナギユーザーの場合は得意先を取得しない
    if(!$setonagi_user){
      $order=Order::firstOrNew(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
      if(isset($price->price)){
      $order->price = $price->price;
      }
    }else{
      $order=Order::firstOrNew(['cart_id'=> $cart->id , 'quantity'=> 1 , 'nouhin_yoteibi'=> $nouhin_yoteibi]);
      if(isset($price->price)){
      $order->price = $price->price;
      }
    }
    // ユーザーの会員番号を取得
    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $item = Item::where('id',$item_id)->first();

    // 市況商品価格上書き
    $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
    if(isset($special_price_item->price)){
    $order->price = $special_price_item->price;
    }

    // セトナギ商品上書き
    $setonagi_item = SetonagiItem::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
    if(isset($setonagi_item->price)){
    $order->price = $setonagi_item->price;
    }

    // 担当のおすすめ商品価格上書き
    $recommend_item = Recommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'user_id'=>$kaiin_number])->first();
    if(isset($recommend_item->price)){
    $order->price = $recommend_item->price;
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
    $user = Auth::guard('user')->user();
    $user_id = Auth::guard('user')->user()->id;
    $setonagi = Setonagi::where('user_id',$user_id)->first();
    // $setonagi_uketori_place = $setonagi->uketori_place;
    // dd($setonagi_uketori_place);

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }

    return view('user/auth/confirm',
    ['carts' => $carts,
     'categories' => $categories,
     'favorite_categories' => $favorite_categories,
     // 'stores' => $stores,
     'holidays' => $holidays,
     'user_id' => $user_id,
     'user' => $user,
     'setonagi' => $setonagi,
     'today_plus' => $today_plus,
    ]);

  }

  public function approval(Request $request){

    // dd($request->memo);

    if($request->setonagi_id){
      $setonagi = Setonagi::where('id',$request->setonagi_id)->first();
      // $setonagi = 1;
      $setonagi->uketori_place = $request->uketori_place;
      $setonagi->uketori_time = $request->uketori_time;
      $setonagi->uketori_siharai = $request->uketori_siharai;
      $setonagi->save();
    }

    $categories = Category::get()->groupBy('bu_ka_name');
    $user_id = Auth::guard('user')->user()->id;
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $approval = 1;

    // $memo = $request->memo;
    // dd($memo);

    $data =
    ['carts' => $carts,
     'categories' => $categories,
     'favorite_categories' => $favorite_categories,
     // 'stores' => $stores,
     'holidays' => $holidays,
     'user_id' => $user_id,
     'approval' => $approval,
     // 'memo' => $memo,
    ];

    return view('user/auth/approval',$data);

    // return view('user/auth/approval',
    // ['carts' => $carts,
    //  'categories' => $categories,
    //  'favorite_categories' => $favorite_categories,
    //  // 'stores' => $stores,
    //  'holidays' => $holidays,
    //  'user_id' => $user_id,
    //  'approval' => $approval,
    //  'memo' => $memo,
    // ]);

  }


// カート画面からの遷移先
  public function order(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
    $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> null])->get();

    $today = date("Y-m-d");
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

    $user = Auth::guard('user')->user();
    $setonagi = Setonagi::where('user_id',$user_id)->first();
    // dd($user->setonagi);

    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }
    // $sano_nissuu = '+'.((strtotime($nouhin_yoteibi) - strtotime($today)) / 86400).'d';
    // dd($sano_nissuu);
    $sano_nissuu = $nouhin_yoteibi;

    if(isset($data->memo)){
    $memo -> $request->memo;
    }

    $data=
    ['carts' => $carts,
     'cart_ninis' => $cart_ninis,
     'stores' => $stores,
     'holidays' => $holidays,
     'user' => $user,
     'setonagi' => $setonagi,
     'today_plus' => $today_plus,
     'sano_nissuu' => $sano_nissuu,
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


    $user = Auth::guard('user')->user();
    $user_id = Auth::guard('user')->user()->id;
    $setonagi = Setonagi::where('user_id',$user_id)->first();


    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');

    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date')->toArray();
    for($i = 3; $i < 10; $i++){
      $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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


    $categories = Category::get()->groupBy('bu_ka_name');

    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    $deal = Deal::where('id',$id)->first();
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();



    // キャンセルができるか判定
    $user = User::where(['id'=> $user_id])->first();

    // セトナギユーザーの場合
    // 注文完了時間
    $success_time = $deal->success_time;
    $success_jikan = date('H:i:s', strtotime($success_time));
    // dd($success_jikan);

    // 注文完了日から受け取り予定日時を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($success_jikan) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
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
    }
    // dd($nouhin_yoteibi);
    // 注文の翌営業日の納品予定19時を取得
    $zenjitu19ji = date($nouhin_yoteibi.'19:00:00');
    // 納品予定日の19時を取得
    $zenjitu19ji = date('Y-m-d H:i:s', strtotime($zenjitu19ji.'-1 day'));
    // dd($zenjitu19ji);

    // 今の日付時間
    $now = date("Y-m-d H:i:s");
    // 次の営業日の前日19時以降はキャンセル不可

    // 翌営業日締め時間より前
    if (strtotime($now) < strtotime($zenjitu19ji)) {
      // dd('キャンセル可');
      $deal_cancel_button = 1;
    }else{
      // dd('キャンセル不可');
      $deal_cancel_button = null;
    }


    $data=[
      // 'carts'=>$carts,
      'deal'=>$deal,
      'categories' => $categories,
      'favorite_categories' => $favorite_categories,
      'holidays' => $holidays,
      'user_id' => $user_id,
      'user' => $user,
      'setonagi' => $setonagi,
      'today_plus' => $today_plus,
      'deal_cancel_button' => $deal_cancel_button,
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
    $today = date("Y-m-d");
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

    $user = Auth::guard('user')->user();
    $setonagi = Setonagi::where('user_id',$user_id)->first();
    // dd($user->setonagi);

    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }
    $sano_nissuu = '+'.((strtotime($nouhin_yoteibi) - strtotime($today)) / 86400).'d';

    $data=
    ['carts' => $carts,
     'cart_ninis' => $cart_ninis,
     'stores' => $stores,
     'holidays' => $holidays,
     'deal' => $deal,
     'user' => $user,
     'setonagi' => $setonagi,
     'sano_nissuu' => $sano_nissuu,
    ];
    return view('order', $data);
  }








  public function adddeal(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    $data = $request->all();
    $item_ids = $data['item_id'];




    // dd($data);
    // 在庫チェック
    // if($request->has('addsuscess_btn')){
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
    // }

    // dd($item_nini_ids);
    // $quantitys = $data['quantity'];

    if(isset($request->memo)){
      $deal = Deal::create(['user_id'=> $user_id, 'memo'=> $request->memo]);
      }else{
      $deal = Deal::create(['user_id'=> $user_id]);
      }
    $deal_id = $deal->id;


    $now = Carbon::now()->format('Ymd');
    // dd($now);

    if($request->uketori_siharai == 'クロネコかけ払い'){
      // ヤマトAPI連携確認
      $client = new Client();
      $url = 'https://demo.yamato-credit-finance.jp/kuroneko-anshin/AN010APIAction.action';
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => '330000051',
          // 日付
          'orderDate' => $now,
          'orderNo' => $deal_id.'1d',
          // バイヤーid
          'buyerId' => $user_id,
          'settlePrice' => $request->all_total_val,
          'shohiZei' => $request->tax_val,
          'meisaiUmu' => '2',
          'passWord' => 'UzhJlu8E'
        ]
      ];
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result);
      if($result->returnCode == 1){
        $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
        if($result->errorCode == 123456){
          // 後で処理を作る
          $message = '掛け払い金額オーバー';
          $data=[
            'message' => $message,
          ];
        }else{
          $message = 'error';
          $data=[
            'message' => $message,
          ];
        }
        return redirect()->route('confirm',$data);
      }
    }




    if($request->uketori_siharai == 'クレジットカード払い'){
      dd($request->token_api);
      // EPトークン取得
      $client = new Client();
      // $url = 'https://api.kuronekoyamato.co.jp/api/credit';
      $url = 'https://ptwebcollect.jp/test_gateway/creditToken.api';

      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'function_div' => 'A08',
          'trader_code' => '888888709',
          // パソコンかスマホか
          'device_div' => 1,
          'order_no' => $deal_id,
          // 決済合計金額
          'settle_price' => $request->all_total_val,
          'buyer_name_kanji' => $user->name,
          'buyer_tel' => $user->tel,
          'buyer_email' => $user->email,
          'pay_way' => 1,
          'token' => $request->token_api,

          // 'device_info' => 1,
          // 'option_service_div' => 00,
          // 'check_sum' => '',
          // 'cardNo' => '',
          // 'cardOwner' => '',
          // 'cardExp' => '',
          // 'securityCode' => '',
        ]
      ];
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // $result = $response->getBody()->getContents();
      dd($result);
    }


    if($request->has('addsuscess_btn')){
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


    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->status = '交渉中';
    $deal->start_time = Carbon::now();

    // セトナギユーザーのみ「受け取り場所」「時間帯」「支払い方法を保存」
    if($request->uketori_siharai){
      $deal->uketori_siharai = $request->uketori_siharai;
      $deal->uketori_place = $request->uketori_place;
      $deal->uketori_time = $request->uketori_time;
    }
    $deal->save();

    if($request->has('addsuscess_btn')){
      // $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $$deal_id])->get();
      // dd($carts);
      $deal=Deal::firstOrNew(['id'=> $deal_id]);
      $deal->status = '発注済';
      $deal->success_time = Carbon::now();
      $deal->save();


      // 注文完了メール送信
      $user = User::where('id',$user_id)->first();

      $name = $user->name;
      // $email = $user->email;
      $email = 'sk8.panda.27@gmail.com';
      $url = url('');
      $text = 'SETOnagiオーダーブックをご利用くださいまして誠にありがとうございます。<br />
      下記の通りご注文をお受けいたしましたのでご確認をお願いいたします。<br />
      <br />
      【ご注文内容】';
      if($user->setonagi == 1){
        // 支払方法
        $pay = '【お支払い方法】<br />'.$deal->uketori_siharai;
        // 受け取り場所
        $uketori_place = '【受け取り場所】<br />'.$deal->uketori_place;
        // 受け取り予定日
        $uketori_time = '【受け取り時間】<br />'.$deal->uketori_time;
      }else{
        // 支払方法
        $pay = null;
        // 受け取り場所
        $uketori_place = null;
        // 受け取り時間
        $uketori_time = null;
      }
      // メモ
      $memo =  '【メモ】<br />'.$deal->memo;

      // 合計金額
      $total_price = [];
      $carts = Cart::where(['deal_id'=> $deal->id])->get();
      // カート商品の出力
      foreach ($carts as $cart) {
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        foreach ($orders as $order) {
          // dd($store);
          $array = $order->price;
          array_push($total_price, $array);
        }
      }
      $total_price = array_sum($total_price);
      $total_price = $total_price * 1.1;
      $total_price =  '【合計金額】<br />'.$total_price.'円（税込）';

      // オーダーリストの作成
      $order_list=[];
        $carts = Cart::where(['deal_id'=> $deal->id])->get();
        // カート商品の出力
        foreach ($carts as $cart) {
          $orders = Order::where(['cart_id'=> $cart->id])->get();
          foreach ($orders as $order) {
            $user = User::where('id',$deal->user_id)->first();
            $item = Item::where(['id'=> $cart->item_id])->first();
            // セトナギユーザーの場合
            if($user->setonagi == 1){
              $setonagi_user = Setonagi::where('user_id',$user->id)->first();
            }
            // BtoBユーザーの場合
            if(!($user->setonagi == 1)){
              $store = Store::where(['tokuisaki_name'=> $order->tokuisaki_name , 'store_name'=> $order->store_name])->first();
            }

            // 単位を取得
            if ($item->tani == 1){
            $tani = 'ｹｰｽ';
            }
            elseif ($item->tani == 2){
            $tani = 'ﾎﾞｰﾙ';
            }
            elseif ($item->tani == 3){
            $tani = 'ﾊﾞﾗ';
            }
            elseif ($item->tani == 4){
            $tani = 'kg';
            }

            // 出力が分岐する項目
            // BtoBユーザー
            if(!($user->setonagi == 1)){

              // BtoBの場合は配送先の店舗を追加する
              $store = $order->tokuisaki_name.$order->store_name;
              $nouhin_yoteibi = $order->nouhin_yoteibi;
              $array =
                '・'.
                // 商品コード
                // $item->item_id.
                // SKUコード
                // $item->sku_code.
                // 商品名
                $item->item_name.' × '.
                // 数量
                $order->quantity.
                // 単位
                $tani.' '.
                // 単価
                $order->price.'円 '.$store.' '.$nouhin_yoteibi
                // 配送希望日
                // $order->nouhin_yoteibi.
                // 受け取り
                // ''.
                // 内消費税
                // '10%'
              ;
            // セトナギユーザー
            }else{
              $store = null;
              // 受け取り予定日
              $nouhin_yoteibi = '【受け取り予定日】<br />'.$order->nouhin_yoteibi;
              $array =
                '・'.
                // 商品コード
                // $item->item_id.
                // SKUコード
                // $item->sku_code.
                // 商品名
                $item->item_name.' × '.
                // 数量
                $order->quantity.
                // 単位
                $tani.' '.
                // 単価
                $order->price.'円'
                // 配送希望日
                // $order->nouhin_yoteibi.
                // 受け取り
                // ''.
                // 内消費税
                // '10%'
              ;
            }
            array_push($order_list, $array);
          }
        }
        // BtoBユーザーのみ任意の商品を出力
        if(!($user->setonagi == 1)){
          $cart_ninis = CartNini::where(['deal_id'=> $deal->id])->get();
          // dd($cart_ninis);
          foreach ($cart_ninis as $cart_nini) {
            $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->get();
            foreach ($order_ninis as $order_nini) {
              // dd($orders);
              // orderslist出力
              $user = User::where('id',$deal->user_id)->first();
              // dd($user);
              $store = Store::where(['tokuisaki_name'=> $order_nini->tokuisaki_name , 'store_name'=> $order_nini->store_name])->first();
              // 出力が分岐する項目
              // BtoBユーザー
              if(!($user->setonagi == 1)){
                // BtoBの場合は配送先の店舗を追加する
                $store = $order_nini->tokuisaki_name.$order_nini->store_name;
                $nouhin_yoteibi = $order_nini->nouhin_yoteibi;
                $array =
                  '・[任意の商品]'.
                  $cart_nini->item_name.' × '.
                  // 数量
                  $order_nini->quantity.' '.$store.' '.$nouhin_yoteibi
                ;
              // セトナギユーザー
              }
              array_push($order_list, $array);
            }
          }
        }
      $order_list = implode('<br>', $order_list);
      // dd($order_list);

      Mail::send('emails.register', [
          'name' => $name,
          'user' => $user,
          'text' => $text,
          'pay' => $pay,
          'uketori_place' => $uketori_place,
          'uketori_time' => $uketori_time,
          'order_list' => $order_list,
          'total_price' => $total_price,
          'memo' => $memo,
          'nouhin_yoteibi' => $nouhin_yoteibi,
      ], function ($message) use ($email) {
          $message->to($email)->subject('SETOnagiオーダーブックご注文承りました。');
      });
      // 注文完了メール送信ここまで
    }



    return redirect('deal');
  }

  public function addsuscess(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    // $data = $request->all();
    // $item_ids = $data['item_id'];
    // $quantitys = $data['quantity'];

    $deal_id = $request->deal_id;
    // 直近の納品予定日に問題がないかチェックする
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }

    // 納品日が今の日付より前に設定されていないかチェック
    $nouhin_youbi_list = [];
    $carts = Cart::where(['deal_id'=> $deal_id])->get();
    foreach ($carts as $cart) {
      $orders = Order::where(['cart_id'=> $cart->id])->get();
      foreach ($orders as $order) {
        $array = $order->nouhin_yoteibi;
        $sano_nissuu = ((strtotime($order->nouhin_yoteibi) - strtotime($today)) / 86400);
        $nouhin_yoteibi = date('Y年m月d日', strtotime($order->nouhin_yoteibi));
        if($sano_nissuu < 0){
          // 納品日エラーの場合カート画面に戻す
          $data=[
            'id' => $deal_id,
            'order_id' => $order->id,
            'nouhin_yoteibi' => $nouhin_yoteibi,
          ];
          return redirect()->route('dealdetail',$data);
        }
        array_push($nouhin_youbi_list, $sano_nissuu);
      }
    }

    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->status = '発注済';
    $deal->success_time = Carbon::now();
    // セトナギユーザーのみ「受け取り場所」「時間帯」「支払い方法を保存」
    if($request->uketori_siharai){
      $deal->uketori_siharai = $request->uketori_siharai;
      $deal->uketori_place = $request->uketori_place;
      $deal->uketori_time = $request->uketori_time;
    }
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

  public function dealcancel(Request $request){
    $user_id = Auth::guard('user')->user()->id;

    // $data = $request->all();
    // $item_ids = $data['item_id'];
    // $quantitys = $data['quantity'];

    $deal_id = $request->deal_id;

    // キャンセルができるか判定
    $deal=Deal::where(['id'=> $deal_id])->first();
    $user = User::where(['id'=> $user_id])->first();

    // セトナギユーザーの場合
    // 注文完了時間
    $success_time = $deal->success_time;
    $success_jikan = date('H:i:s', strtotime($success_time));
    // dd($success_jikan);

    // 注文完了日から受け取り予定日時を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($success_jikan) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($success_time.'+'.$i.'day'));
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
    }
    // dd($nouhin_yoteibi);
    // 注文の翌営業日の納品予定19時を取得
    $zenjitu19ji = date($nouhin_yoteibi.'19:00:00');
    // 納品予定日の19時を取得
    $zenjitu19ji = date('Y-m-d H:i:s', strtotime($zenjitu19ji.'-1 day'));
    // dd($zenjitu19ji);

    // 今の日付時間
    $now = date("Y-m-d H:i:s");
    // 次の営業日の前日19時以降はキャンセル不可

    // 翌営業日締め時間より前
    if (strtotime($now) < strtotime($zenjitu19ji)) {
      // dd('キャンセル可');
    }else{
      // dd('キャンセル不可');
    // 翌営業日締め時間より後
    // キャンセルはできないのでその旨をアラート
      $id= $deal_id;
      $data=[
        'id' => $deal_id,
        'cancel_error' => 'キャンセルエラーです',
      ];
      return redirect()->route('dealdetail',$data);
    }



    $deal=Deal::firstOrNew(['id'=> $deal_id]);
    $deal->status = 'キャンセル';
    $deal->cancel_time = Carbon::now();
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

  // 繰り返し使う納品予定日
  function nouhin_yoteibi(){
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('19:00:00')) {
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 1; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }else{
    // 19時より後の処理
      $holidays = Holiday::pluck('date')->toArray();
      for($i = 2; $i < 10; $i++){
        $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
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
    }
    return $nouhin_yoteibi;
  }



  public function favoriteitem(){
    $user_id = Auth::guard('user')->user()->id;
    $categories = Category::get()->groupBy('bu_ka_name');
    $favorite_categories = FavoriteCategory::where('user_id', $user_id)->get();

    $favorite_items= favorite::where('user_id',$user_id)->get();

    $data=[
      'categories' => $categories,
      'favorite_categories' => $favorite_categories,
      'favorite_items' => $favorite_items,
    ];
    return view('user.auth.favoriteitem', $data);

  }


  public function addfavoriteitem(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;

    $favorite_in = Favorite::where(['user_id'=> $user_id , 'item_id'=> $item_id])->first();
    if($favorite_in){
      return response()->json([
      'message' => 'favorite_in',
      ]);
    }
    // $cart_in=Cart::where(['user_id'=> $user_id , 'item_id'=> $item->id , 'deal_id'=> null])->first();
    // if($cart_in){
    //   return response()->json([
    //   'message' => 'cart_in',
    //   ]);
    // }
    $favorite_item = Favorite::firstOrNew(['user_id'=> $user_id , 'item_id'=> $item_id]);
    $favorite_item->save();

    $data = "sucsess";
    return redirect()->route('home',$data);
  }


  public function removefavoriteitem(Request $request){

    $user_id = Auth::guard('user')->user()->id;
    $item_id = $request->item_id;

    $favorite_in = Favorite::where(['user_id'=> $user_id , 'item_id'=> $item_id])->first()->delete();;

    $data = "sucsess";
    return redirect()->route('home',$data);




  }



}
