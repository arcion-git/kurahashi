<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Deal;
use App\User;
use App\Item;
use App\Category;
use App\CategoryItem;
use App\Tag;
use App\Holiday;
use App\Store;
use App\StoreUser;
use App\Recommend;
use App\FavoriteCategory;
use App\Repeatcart;
use App\Repeatorder;
use App\RecommendCategory;
use App\CartNini;
use App\OrderNini;

use App\PriceGroupe;
use App\Price;
use App\SpecialPrice;

// 時間に関する処理
use Carbon\Carbon;

// 認証関連
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// CSVインポート
use App\Imports\ItemImport;
use App\Imports\UserImport;
use App\Imports\CategoryImport;
use App\Imports\TagImport;
use App\Imports\CategoryItemImport;
use App\Imports\HolidayImport;
use App\Imports\StoreImport;
use App\Imports\StoreUserImport;

use App\Imports\PriceGroupeImport;
use App\Imports\PriceImport;
use App\Imports\SpecialPriceImport;

// ページネーション
use Illuminate\Pagination\LengthAwarePaginator;


use Maatwebsite\Excel\Facades\Excel;

// デバッグを出力
use Log;
use Response;

class AdminPageController extends Controller
{
  public function __construct(){
		$this->middleware('admin');
	}

  public function index()
  {
    if ( Auth::guard('user')->check() ){
        Auth::guard('user')->logout();
    }
    $deals =  Deal::latest('created_at')->paginate(30);

    return view('admin/home', ['deals' => $deals]);
  }


  public function search(Request $request)
  {
      $search = $request->search;
      $cat = $request->cat;
      if($cat == -1){
        $all_deals = Deal::get();
        $deals = [];
        $n=1;
        foreach ($all_deals as $all_deal) {
        $user_name = User::where('id',$all_deal->user_id)->where('name','like', "%$search%")->first();
        if($user_name){
          array_push($deals, $all_deal);
        }
        $n++;
        }
        $deals = new LengthAwarePaginator($deals, count($deals), 10, 1);
      }elseif($cat == '交渉中'){
        $all_deals = Deal::where('success_flg',0)->get();
        $deals = [];
        $n=1;
        foreach ($all_deals as $all_deal) {
        $user_name = User::where('id',$all_deal->user_id)->where('name','like', "%$search%")->first();
        if($user_name){
          array_push($deals, $all_deal);
        }
        $n++;
        }
        $deals = new LengthAwarePaginator($deals, count($deals), 10, 1);
      }elseif($cat == '受注済'){
        $all_deals = Deal::where('success_flg',1)->get();
        $deals = [];
        $n=1;
        foreach ($all_deals as $all_deal) {
        $user_name = User::where('id',$all_deal->user_id)->where('name','like', "%$search%")->first();
        if($user_name){
          array_push($deals, $all_deal);
        }
        $n++;
        }
        $deals = new LengthAwarePaginator($deals, count($deals), 10, 1);
      }elseif($cat == 'キャンセル'){
        $all_deals = Deal::where('cancel_flg',1)->get();
        $deals = [];
        $n=1;
        foreach ($all_deals as $all_deal) {
        $user_name = User::where('id',$all_deal->user_id)->where('name','like', "%$search%")->first();
        if($user_name){
          array_push($deals, $all_deal);
        }
        $n++;
        }
        $deals = new LengthAwarePaginator($deals, count($deals), 10, 1);
      }
    return view('admin/home', ['deals' => $deals]);
  }



  public function dealdetail($id){
    $deal = Deal::where('id',$id)->first();

    $carts = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->get();
    $cart_ninis = CartNini::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->get();
    $data=[
      'carts'=>$carts,
      'cart_ninis' => $cart_ninis,
      'deal'=>$deal
    ];
    return view('dealdetail', $data);
  }



  public function dealorder(Request $request){

    $deal_id = $request->deal_id;

    $deal = Deal::where(['id'=> $deal_id])->first();


    $user_id = $deal->user_id;
    // dd($user_id);

    // 取引IDが一致しているものを取得
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
    $cart_ninis = CartNini::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();

    // 休日についての処理
    $today = date("Y/m/d");
    $holidays = Holiday::pluck('date');

    // 店舗一覧取得
    $store_users = StoreUser::where('user_id',$user_id)->get(['store_id','tokuisaki_id']);
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






  public function discount(Request $request){

    $id = $request->deal_id;

    $order_ids = $request->order_id;
    $prices = $request['price'];

    $prices = array_combine($order_ids, $prices);
    // dd($prices);
    foreach($prices as $key => $value) {
      $order = Order::firstOrNew(['id'=> $key]);
      $order->price = $value;
      $order->save();
    }


    // foreach (array_map(null, $order_ids, $prices) as [$val1, $val2]) {
    //   $order = Order::where(['id'=> $val1 , 'price'=> $val2])->first();
    //   $order->price = $val2;
    //   $order->save();
    // }

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

    $users = User::paginate(30);

    return view('admin.auth.user', ['users' => $users]);
  }

  public function item(){

    $items = Item::paginate(30);

    return view('admin.auth.item', ['items' => $items]);
  }



  public function csv(){
    return view('admin.auth.csv');
  }

  public function userimport(){
  User::truncate();
  Excel::import(new UserImport, request()->file('file'));
  return back();
  }

  public function itemimport(){
  Item::truncate();
  Excel::import(new ItemImport, request()->file('file'));
  return back();
  }

  public function CategoryItemimport(){
  CategoryItem::truncate();
  Excel::import(new CategoryItemImport, request()->file('file'));
  return back();
  }

  public function categoryimport(){
  Category::truncate();
  Excel::import(new CategoryImport, request()->file('file'));
  return back();
  }

  public function tagimport(){
  Tag::truncate();
  Excel::import(new TagImport, request()->file('file'));
  return back();
  }

  public function HolidayImport(){
  Holiday::truncate();
  Excel::import(new HolidayImport, request()->file('file'));
  return back();
  }

  public function StoreImport(){
  Store::truncate();
  Excel::import(new StoreImport, request()->file('file'));
  return back();
  }

  public function StoreUserImport(){
  StoreUser::truncate();
  Excel::import(new StoreUserImport, request()->file('file'));
  return back();
  }

  public function PriceGroupeImport(){
  PriceGroupe::truncate();
  Excel::import(new PriceGroupeImport, request()->file('file'));
  return back();
  }

  public function PriceImport(){
  Price::truncate();
  Excel::import(new PriceImport, request()->file('file'));
  return back();
  }

  public function SpecialPriceImport(){
  SpecialPrice::truncate();
  Excel::import(new SpecialPriceImport, request()->file('file'));
  return back();
  }




  public function download(){
    return view('admin.auth.download');
  }

  public function userdeal($id){

    $deals = Deal::where('user_id',$id)->paginate(30);
    $user = User::where('id',$id)->first();
    $data=[
      'id'=>$id,
      'deals' => $deals,
      'user'=>$user,
    ];
    return view('admin.home', $data);
  }




  // カテゴリごとのおすすめ商品処理


    public function recommendcategory(){
      $items = Item::get();
      $categories = Category::get();
      $data=[
        'items'=>$items,
        'categories'=>$categories,
      ];
      return view('recommendcategory', $data);
    }

    public function recommendcategorydetail($id){
      $category = Category::where('category_id',$id)->first();
      $items = $category->items()->get();
      $recommendcategories = RecommendCategory::where('category_id',$id)->get();
      // dd($recommendcategorys);
      $data=[
        'id'=>$id,
        'items'=>$items,
        'category'=>$category,
        'recommendcategories'=>$recommendcategories,
      ];
      return view('recommendcategorydetail', $data);
    }


    public function addrecommendcategory(Request $request){

      $item_id = $request->item_id;
      $category_id = $request->category_id;
      $category = Category::where('id',$category_id)->first();
      $item = Item::where('id',$item_id)->first();

      $recommendcategory = RecommendCategory::firstOrNew(['category_id'=> $category_id , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
      $recommendcategory -> save();

      $id = $category_id;

      return redirect()->route('recommendcategorydetail', $id);
    }

    public function saverecommendcategory(Request $request){

      $category_id = $request->category_id;
      $recommendcategorys = $request->recommendcategory;

      foreach($recommendcategorys as  $key => $value) {
        $recommendcategory = RecommendCategory::firstOrNew(['id'=> $key]);
        $recommendcategory->price = $value['price'];
        $recommendcategory->end = $value['end'];
        $recommendcategory->save();
      }

      $id = $request->category_id;
      return redirect()->route('recommendcategorydetail', $id);
    }

    public function removerecommendcategory(Request $request){
      $delete_id = $request->delete;
      $delete = RecommendCategory::where('id',$delete_id)->first()->delete();
      $id = $request->category_id;
      return redirect()->route('recommendcategorydetail', $id);
    }



// 担当のおすすめ商品処理
  public function userrecommend($id){

    $user = User::where('id',$id)->first();
    $items = Item::get();

    $recommends = Recommend::where('user_id',$user->kaiin_number)->get();
    // dd($recommends);
    $data=[
      'id'=>$id,
      'items'=>$items,
      'user'=>$user,
      'recommends'=>$recommends,
    ];
    return view('recommend', $data);
  }


  public function addrecommend(Request $request){

    $item_id = $request->item_id;
    $user_id = $request->user_id;

    $user = User::where('id',$user_id)->first();
    $item = Item::where('id',$item_id)->first();
    // dd($user);

    $recommend = Recommend::firstOrNew(['user_id'=> $user->kaiin_number , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
    $recommend -> save();

    $id = $user_id;

    return redirect()->route('recommend', $id);
  }

  public function saverecommend(Request $request){

    $user_id = $request->user_id;
    $recommends = $request->recommend;

    foreach($recommends as  $key => $value) {
      $recommend = Recommend::firstOrNew(['id'=> $key]);
      $recommend->price = $value['price'];
      $recommend->end = $value['end'];
      $recommend->save();
    }

    $id = $request->user_id;
    return redirect()->route('recommend', $id);
  }

  public function removercommend(Request $request){
    $delete_id = $request->delete;
    $delete = Recommend::where('id',$delete_id)->first()->delete();
    $id = $request->user_id;
    return redirect()->route('recommend', $id);
  }






// リピートオーダー処理
  public function userrepeatorder($id){

    $user = User::where('id',$id)->first();
    $items = Item::get();

    $repeatcarts = Repeatcart::where('kaiin_number',$user->kaiin_number)->get();
    // dd($repeatcarts);
    $data=[
      'id'=>$id,
      'items'=>$items,
      'user'=>$user,
      'repeatcarts'=>$repeatcarts,
    ];
    return view('repeatorder', $data);
  }


  public function addrepeatorder(Request $request){

    $item_id = $request->item_id;
    $kaiin_number = $request->kaiin_number;

    $user = User::where('id',$kaiin_number)->first();
    $item = Item::where('id',$item_id)->first();
    // dd($user);
    // dd($user->kaiin_number);
    $store_user = StoreUser::where('user_id',$user->kaiin_number)->first(['store_id','tokuisaki_id']);

    $store = Store::where(['tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
    // dd($store);
    $repeatcart = Repeatcart::firstOrNew(['kaiin_number'=> $user->kaiin_number , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
    $repeatcart -> save();

    $repeatorder = Repeatorder::firstOrNew(['cart_id'=> $repeatcart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1]);
    $repeatorder -> save();

    $id = $kaiin_number;

    return redirect()->route('repeatorder', $id);
  }

  public function clonerepeatorder(Request $request){
    $cart_id = $request->cart_id;
    $repeatorder=Repeatorder::Create(['cart_id'=> $cart_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    $data = "sucsess";
    // Log::debug($repeatorder);
    return redirect()->route('home',$data);
  }

  public function saverepeatorder(Request $request){

    dd($request);

    $kaiin_number = $request->kaiin_number;
    $repeatorders = $request->repeatorder;

    $nouhin_youbi = $request->nouhin_youbi;

    foreach($repeatorders as  $key => $value) {
      $nouhin_youbi = $value['nouhin_youbi'];
      $nouhin_youbi = implode(',', $nouhin_youbi);
      // dd($nouhin_youbi);
      $repeatorder = Repeatorder::firstOrNew(['id'=> $key]);
      $repeatorder->price = $value['price'];
      $repeatorder->quantity = $value['quantity'];
      $repeatorder->nouhin_youbi = $value['nouhin_youbi'];
      $repeatorder->status = $value['status'];
      // $repeatorder->status = $value['store'];
      $repeatorder->startdate = $value['startdate'];
      // $repeatorder->end = $value['end'];
      $repeatorder->save();
    }

    $id = $request->kaiin_number;
    return redirect()->route('repeatorder', $id);
  }

  public function removerepeatorder(Request $request){
    $delete_id = $request->delete;
    $cart_id = $request->cart_id;

    $delete_order = Repeatorder::where('id',$delete_id)->first()->delete();

    $order = Repeatorder::where(['cart_id'=> $cart_id])->first();
    if(empty($order)){
    $delete_cart=Repeatcart::where(['id' => $cart_id])->first()->delete();
    }

    $id = $request->kaiin_number;
    return redirect()->route('repeatorder', $id);
  }





}
