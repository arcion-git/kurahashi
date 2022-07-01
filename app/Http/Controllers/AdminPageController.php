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

use App\Setonagi;
use App\SetonagiItem;
use App\ItemImage;

// API通信
use GuzzleHttp\Client;

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

use App\Imports\SetonagiImport;
use App\Imports\SetonagiItemImport;

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
        $all_deals = Deal::where('status','交渉中')->get();
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
        $all_deals = Deal::where('status','発注済')->get();
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
        $all_deals = Deal::where('status','キャンセル')->get();
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
      'deal'=>$deal,
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

    $user = User::where('id',$deal->user_id)->first();

    // 店舗一覧取得
    $store_users = StoreUser::where('user_id',$user->kaiin_number)->get(['store_id','tokuisaki_id']);
    $stores = [];

    $n=1;
    foreach ($store_users as $store_user) {
    $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      array_push($stores, $store);
    $n++;
    }

    $user = User::where('id',$user_id)->first();
    $setonagi = Setonagi::where('user_id',$user_id)->first();
    // dd($user->setonagi);

    $data=
    ['deal' => $deal,
     'carts' => $carts,
     'cart_ninis' => $cart_ninis,
     'stores' => $stores,
     'holidays' => $holidays,
     'user' => $user,
     'setonagi' => $setonagi,
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


    $deal_id = $request->deal_id;
    $deal = Deal::where('id',$deal_id)->first();
    $deal->status = '確認待';
    $deal->kakunin_time = Carbon::now();
    $deal->save();


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
    $setonagi_users = Setonagi::get();
    $now = Carbon::now();

    return view('admin.auth.user', ['users' => $users]);
  }


  public function setonagiuser(){

    $users = User::where('setonagi',1)->paginate(30);
    $setonagi_users = Setonagi::get();
    $now = Carbon::now();

    // dd($setonagi);

    // // ヤマトAPI連携審査状況確認
    foreach ($setonagi_users as $setonagi_user) {
      $user_id = $setonagi_user->user_id;
      $client = new Client();
      $url = 'https://demo.yamato-credit-finance.jp/kuroneko-anshin/AN070APIAction.action';
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => '330000051',
          // バイヤーid
          'buyerId' => $user_id,
          'buyerTelNo' => '',
          'passWord' => 'UzhJlu8E'
        ]
      ];
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result);
      $setonagi_user = Setonagi::where('user_id',$user_id)->first();
      $setonagi_user->kakebarai_limit = $result->useOverLimit;
      $setonagi_user->kakebarai_sinsa = $result->judgeStatus;
      $setonagi_user->kakebarai_update_time = $now;
      $setonagi_user->save();

    // ヤマトAPI連携利用金額確認
      $client = new Client();
      $url = 'https://demo.yamato-credit-finance.jp/kuroneko-anshin/AN050APIAction.action';
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => '330000051',
          // バイヤーid
          'buyerId' => $user_id,
          'buyerTelNo' => '',
          'passWord' => 'UzhJlu8E'
        ]
      ];
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result->usePayment);
      $setonagi_user = Setonagi::where('user_id',$user_id)->first();
      $setonagi_user->kakebarai_usepay = $result->usePayment;
      $setonagi_user->save();
    }




    return view('admin.auth.setonagiuser', ['users' => $users]);
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

  public function SetonagiImport(){
  Setonagi::truncate();
  Excel::import(new SetonagiImport, request()->file('file'));
  return back();
  }

  public function SetonagiItemImport(){
  SetonagiItem::truncate();
  Excel::import(new SetonagiItemImport, request()->file('file'));
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
    // dd($repeatcarts);
    $data=[
      'id'=>$id,
      'items'=>$items,
      'stores'=>$stores,
      'user'=>$user,
      'repeatcarts'=>$repeatcarts,
    ];
    return view('repeatorder', $data);
  }

// リピートオーダー追加
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

    // dd($request->repeatorder);

    $kaiin_number = $request->kaiin_number;
    $repeatorders = $request->repeatorder;

    // dd($kaiin_number);

    foreach($repeatorders as  $key => $value) {

      $store = explode(',',$value['store']);

      $nouhin_youbi = $value['nouhin_youbi'];
      $nouhin_youbi = implode(',', $nouhin_youbi);
      $repeatorder = Repeatorder::firstOrNew(['id'=> $key]);
      $repeatorder->price = $value['price'];
      $repeatorder->quantity = $value['quantity'];
      $repeatorder->nouhin_youbi = $value['nouhin_youbi'];
      $repeatorder->status = $value['status'];

      $repeatorder->tokuisaki_name = $store[0];
      $repeatorder->store_name = $store[1];

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


  public function imgupload(){
    return view('admin.auth.imgupload');
  }

  public function imgsave(Request $request){
  if(isset($request->file)){
  $files = $request->file;
  foreach ($files as $file) {
    $filename = $file->getClientOriginalName();
    $filesave = $file->storeAs('public/item',$filename);
  }
  }
  return redirect()->route('imgupload');
  }



  public function riyoukyoka(Request $request){
    $riyoukyoka_user_id = $request->user_id;
    $setonagi = User::where('id',$riyoukyoka_user_id)->first()->setonagi();
    // $setonagi->kakebarai_riyou = 1;
    // $setonagi->save();

    $name = 'テスト ユーザー';
    $email = 'sk8.panda.27@gmail.com';

    Mail::send('emails.register', [
        'name' => $name,
    ], function ($message) use ($email) {
        $message->to($email)->subject('テストタイトル');
    });


    return redirect()->route('admin.setonagiuser');
  }

  public function riyouteisi(Request $request){
    return redirect()->route('admin.setonagiuser');
  }

  public function card_riyoukyoka(Request $request){
    return redirect()->route('admin.setonagiuser');
  }






  // Ajax
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

  // 個数を変更
  public function change_quantity(Request $request){
    $order_id = $request->order_id;
    $quantity = $request->quantity;

    $order=Order::where(['id'=> $order_id])->update(['quantity'=> $quantity]);

    $data = "sucsess";
    return redirect()->route('home',$data);
  }

  // 納品予定日を変更
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

    $order = Order::where(['id'=> $order_id])->first();

    $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    // Log::debug($kaiin_number);

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

      $price = $price->price;

      // 市況商品価格上書き
      $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
      if(isset($special_price_item->price)){
        $price = $special_price_item->price;
      }

      // セトナギ商品上書き
      $setonagi_item = SetonagiItem::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
      if(isset($setonagi_item->price)){
        $price = $setonagi_item->price;
      }

      // 担当のおすすめ商品価格上書き
      $recommend_item = Recommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'user_id'=>$kaiin_number])->first();
      if(isset($recommend_item->price)){
        $price = $recommend_item->price;
      }

      $order=Order::create(['cart_id'=> $cart->id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1 , 'price'=> $price ])->first();
      // Log::debug("count");

      $order->save();

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

  // 配送先店舗を追加
  public function clonecart(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $cart_id = $request->cart_id;
    $order=Order::Create(['cart_id'=> $cart_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    $data = "sucsess";
    return redirect()->route('home',$data);
  }

  // 任意の商品を追加
  public function addniniorder(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    if(isset($request->deal_id)){
      $cart_nini=CartNini::Create(['user_id'=> $user_id , 'deal_id'=> $request->deal_id]);
      $order_nini=OrderNini::Create(['cart_nini_id'=> $cart_nini->id , 'deal_id'=> $request->deal_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    }else{
      $cart_nini=CartNini::Create(['user_id'=> $user_id]);
      $order_nini=OrderNini::Create(['cart_nini_id'=> $cart_nini->id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    }
    $data = "sucsess";
    return redirect()->route('home',$data);
  }

  // 任意の配送先を追加
  public function addordernini(Request $request){
    $user_id = Auth::guard('user')->user()->id;
    $cart_nini_id = $request->cart_nini_id;
    $order=OrderNini::Create(['cart_nini_id'=> $cart_nini_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    $data = "sucsess";
    return redirect()->route('home',$data);
  }

}
