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
use App\recommend;
use App\FavoriteCategory;

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



use Maatwebsite\Excel\Facades\Excel;

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



  public function dealorder(Request $request){

    $deal_id = $request->deal_id;

    $deal = Deal::where(['id'=> $deal_id])->first();


    $user_id = $deal->user_id;
    // dd($user_id);

    // 取引IDが一致しているものを取得
    $carts =  Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();

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

    $users = User::get();

    return view('admin.auth.user', ['users' => $users]);
  }

  public function item(){

    $items = Item::get();

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

    $deals = Deal::where('user_id',$id)->get();

    return view('admin.home', ['deals' => $deals]);
  }



  public function userrecommend($id){

    $user = User::where('id',$id)->first();
    $deals = Deal::where('user_id',$id)->get();
    $items = Item::get();

    $recommends = Recommend::where('user_id',$user->kaiin_number)->get();
    // dd($recommends);
    $data=[
      'id'=>$id,
      'items'=>$items,
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

    $recommend_id = $request->recommend_id;
    $price = $request->price;
    $end = $request->end;

    dd($end);

    $id = $request->user_id;
    return redirect()->route('recommend', $id);
  }


}
