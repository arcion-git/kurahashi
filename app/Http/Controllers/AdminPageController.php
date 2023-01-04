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
use App\BuyerRecommend;
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

// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


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
use App\Imports\AdminImport;
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

    $user = User::where(['id'=>$deal->user_id])->first();
    $nouhin_yoteibi = null;
    if($user->setonagi == 1){
      $cart_id = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->first()->id;
      $nouhin_yoteibi = Order::where(['cart_id'=>$cart_id])->first()->nouhin_yoteibi;
    }

    $data=[
      'nouhin_yoteibi'=>$nouhin_yoteibi,
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
    $today = date("Y-m-d");
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

    // 直近の納品予定日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
    // 19時より前の処理
    if (strtotime($currentTime) < strtotime('17:00:00')) {
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
    $collect = config('app.collect_password');

    $data=
    ['deal' => $deal,
     'carts' => $carts,
     'cart_ninis' => $cart_ninis,
     'stores' => $stores,
     'holidays' => $holidays,
     'user' => $user,
     'setonagi' => $setonagi,
     'sano_nissuu' => $sano_nissuu,
     'collect' => $collect,
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
    $user = User::where('id',$deal->user_id)->first();
    $user_id = $deal->user_id;

    if($user->setonagi == 1){
      // SETOnagiユーザーはクレジットカード、もしくは、ヤマトかけばらいの金額を変更する
      // クロネコかけ払い決済取消
      if($deal->uketori_siharai == 'クロネコかけ払い'){
        // ヤマトAPI連携確認
        $client = new Client();
        $url = config('app.kakebarai_torikesi');
        $kakebarai_traderCode = config('app.kakebarai_traderCode');
        $kakebarai_passWord = config('app.kakebarai_passWord');
        $envi = config('app.envi');
        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
          ],
          'form_params' => [
            'traderCode' => $kakebarai_traderCode,
            'orderNo' => $deal_id.$envi,
            'buyerId' => $user_id,
            'passWord' => $kakebarai_passWord
          ]
        ];
        // dd($option);
        $response = $client->request('POST', $url, $option);
        $result = simplexml_load_string($response->getBody()->getContents());
        // dd($result);
        // エラーの場合戻す
        if($result->returnCode == 1){
          $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
          if($result->errorCode == 123456){
            // 後で処理を作る
            $message = '掛け払い金額オーバー';
            $data=[
              'message' => $message,
            ];
          }else{
            $message = '決済金額変更エラー。金額の変更ができませんでした。';
            $data=[
              'message' => $message,
            ];
          }
          return redirect()->route('admin.dealdetail',$id);
        }
        // かけ払い決済再登録
        $client = new Client();
        $url = config('app.kakebarai_touroku');
        $now = Carbon::now()->format('Ymd');
        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
          ],
          'form_params' => [
            'traderCode' => $kakebarai_traderCode,
            // 日付
            'orderDate' => $now,
            'orderNo' => $deal_id.$envi,
            // バイヤーid
            'buyerId' => $user_id,
            'settlePrice' => $request->all_total_val,
            'shohiZei' => $request->tax_val,
            'meisaiUmu' => '2',
            'passWord' => $kakebarai_passWord
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
            $message = '決済金額変更エラー。金額の変更ができませんでした。';
            $data=[
              'message' => $message,
            ];
          }
          return redirect()->route('admin.dealdetail',$id);
        }
      }



      // クレジットカード決済の金額を変更
      if($request->uketori_siharai == 'クレジットカード払い'){
        // dd($request->token_api);
        // EPトークン取得
        $client = new Client();
        // $url = 'https://api.kuronekoyamato.co.jp/api/credit';

        $url = config('app.collect_pricechange');
        $collect_tradercode = config('app.collect_tradercode');

        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
          ],
          'form_params' => [
            'function_div' => 'A07',
            'trader_code' => $collect_tradercode,
            'order_no' => $deal_id,
            // 決済変更金額
            'new_price' => $request->all_total_val,
          ]
        ];
        // dd($option);
        $response = $client->request('POST', $url, $option);
        $result = simplexml_load_string($response->getBody()->getContents());
        if($result->returnCode == 1){
          $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
          if($result->errorCode == 123456){
            // 後で処理を作る
            $message = '掛け払い金額オーバー';
            $data=[
              'message' => $message,
            ];
          }else{
            $message = '決済金額変更エラー。金額の変更ができませんでした。';
            $data=[
              'message' => $message,
            ];
          }
          return redirect()->route('admin.dealdetail',$id);
        }
      }

    }else{
      // 法人ユーザーのみ確認待ちに変更
      $deal->status = '確認待';
    }
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

    $kaiin_number = User::first()->kaiin_number;
    $tokuisaki = StoreUser::where('user_id',$kaiin_number)->first();
    $tokuisaki_name = Store::where(['tokuisaki_id' => $tokuisaki->tokuisaki_id ,'store_id' => $tokuisaki->store_id])->first()->tokuisaki_name;

    $setonagi_users = Setonagi::get();
    $now = Carbon::now();

    return view('admin.auth.user', ['users' => $users]);
  }

  public function buyer(){

    // 得意先コードがユニークなものだけを取得
    // $stores = Store::paginate(30);
    // $stores = Store::groupBy('tokuisaki_id')->get(['tokuisaki_id']);
    // $stores = Store::get();
    // dd($stores);
    // $storeusers = StoreUser::get();
    $stores = StoreUser::get()->unique('tokuisaki_id');
    // dd($stores);
    // $stores = Store::get()->unique('tokuisaki_id');
    // StoreUserに値があるものだけを取得



    // dd($stores);

    // $kaiin_number = User::first()->kaiin_number;
    // $tokuisaki = StoreUser::where('user_id',$kaiin_number)->first();
    // $tokuisaki_name = Store::where(['tokuisaki_id' => $tokuisaki->tokuisaki_id ,'store_id' => $tokuisaki->store_id])->first()->tokuisaki_name;

    // $setonagi_users = Setonagi::get();
    // $now = Carbon::now();

    return view('admin.auth.buyer', ['stores' => $stores]);
  }

  public function setonagiuser(){

    $users = User::where('setonagi',1)->paginate(30);
    $setonagi_users = Setonagi::get();
    $now = Carbon::now();

    $kakebarai_traderCode = config('app.kakebarai_traderCode');
    $kakebarai_passWord = config('app.kakebarai_passWord');

    foreach ($setonagi_users as $setonagi_user) {
      $user_id = $setonagi_user->user_id;
      $user =  User::where('id',$user_id)->first();


      // ヤマトAPI連携利用金額確認
      $client = new Client();
      $url = config('app.kakebarai_riyoukingaku');
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => $kakebarai_traderCode,
          // バイヤーid
          'buyerId' => $user_id,
          'buyerTelNo' => '',
          'passWord' => $kakebarai_passWord
        ]
      ];
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result);
      if($result->returnCode == 0){
        $setonagi_user = Setonagi::where('user_id',$user_id)->first();
        $setonagi_user->kakebarai_usepay = $result->usePayment;
        $setonagi_user->kakebarai_limit = $result->useOverLimit;
        $setonagi_user->kakebarai_sinsa = $result->useUsable;
        $setonagi_user->kakebarai_update_time = $now;
        $setonagi_user->save();
      }

      // ヤマトAPI連携審査状況確認
      $client = new Client();
      $url = config('app.kakebarai_sinsa');
      $option = [
        'headers' => [
          'Accept' => '*/*',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'charset' => 'UTF-8',
        ],
        'form_params' => [
          'traderCode' => $kakebarai_traderCode,
          // バイヤーid
          'buyerId' => $user->id,
          'buyerTelNo' => '',
          'passWord' => $kakebarai_passWord
        ]
      ];
      // dd($option);
      $response = $client->request('POST', $url, $option);
      $result = simplexml_load_string($response->getBody()->getContents());
      // dd($result);
      if($result->returnCode == 0){
        $setonagi_user = Setonagi::where('user_id',$user_id)->first();
        $setonagi_user->kakebarai_sinsa = $result->judgeStatus;
        $setonagi_user->kakebarai_update_time = $now;
        $setonagi_user->save();
      }
      // elseif($result->returnCode == 1){
      //   $setonagi_user = Setonagi::where('user_id',$user_id)->first();
      //   $setonagi_user->kakebarai_sinsa = '審査状況照会エラー';
      //   $setonagi_user->save();
      // }
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
  // User::truncate();
  Excel::import(new UserImport, request()->file('file'));
  return back();
  }

  public function adminimport(){
  // admin::truncate();
  Excel::import(new AdminImport, request()->file('file'));
  return back();
  }

  public function itemimport(){
  // Item::truncate();
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
    // 水産の前方コード
    $code = 1103;
    // 本番用
    $items = Item::where("busho_code", "LIKE", $code.'%')->get();
    // 処理が重いので一時的に在庫数のある商品だけを表示
    $items = Item::where("busho_code", "LIKE", $code.'%')->where('zaikosuu', '>=', '0.01')->get();
    // dd($items);
    $recommends = Recommend::where('user_id',$user->id)->orderBy('order_no')->get();
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

    // 同じ商品の掲載を禁止する場合
    // $recommend = Recommend::firstOrNew(['user_id'=> $user->id , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
    $recommend = Recommend::create(['user_id'=> $user->id , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
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
      $recommend->start = $value['start'];
      $recommend->end = $value['end'];
      $recommend->order_no = $value['order_no'];
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





  // 企業ごとの担当のおすすめ商品処理
    public function buyerrecommend(Request $request , $id){
      $item_search = $request->item_search;

      $order_no = $request->ordernosave;

      $tokuisaki_id = $id;
      // 水産の前方コード
      $code = 1103;
      // 本番用
      // $items = Item::where("busho_code", "LIKE", $code.'%')->get();
      // 処理が重いので一時的に在庫数のある商品だけを表示
      // $items = Item::where("busho_code", "LIKE", $code.'%')->where('zaikosuu', '>=', '0.01')->get();
      // $search = 'いか';
      $store = Store::where('tokuisaki_id',$tokuisaki_id)->first();

      if(isset($item_search)){
        $items = Item::where("busho_code", "LIKE", $code.'%')->Where('zaikosuu', '>=', '0.01')->where(function($items) use($item_search){
          $items->where('item_name','like', "%$item_search%")->orWhere('item_id','like', "%$item_search%");
        })->orWhere('item_name_kana','like', "%$item_search%")->paginate(30);
      }else{
        $items = [];
      }

      // dd($tokuisaki_name->tokuisaki_name);
      $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderBy('order_no')->get();
      // dd($buyerrecommends);
      $data=[
        'id'=>$id,
        'items'=>$items,
        'store'=>$store,
        'buyerrecommends'=>$buyerrecommends,
        'item_search'=>$item_search,
        'order_no'=>$order_no,
      ];
      return view('buyerrecommend', $data);
    }





    public function buyeraddrecommend(Request $request){


      $order_no = $request->ordernosave;
      // dd($order_no);
      $tokuisaki_id = $request->tokuisaki_id;
      $item_id = $request->item_id;
      $item = Item::where('id',$item_id)->first();
      // dd($user);

      // 同じ商品の掲載を禁止する場合
      // $recommend = Recommend::firstOrNew(['user_id'=> $user->id , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);

      // dd($tokuisaki_id);

      if($order_no){
        $order_no = $order_no +0.1;
      }else{
        $order_no = 99999;
      }
      $recommend = BuyerRecommend::create(['item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ,'tokuisaki_id'=> $request->tokuisaki_id ,'order_no'=> $order_no]);
      $recommend -> save();

      $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderBy('order_no')->get();
      $n=1;
      foreach ($buyerrecommends as $buyerrecommend) {
        $buyerrecommend->order_no = $n;
        $buyerrecommend->save();
        $n++;
      }

      $id = $tokuisaki_id;

      return redirect()->route('buyerrecommend', $id);
    }

    public function buyersaverecommend(Request $request){

      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommends = $request->buyerrecommend;
      // dd($tokuisaki_id);

      // 価格が保存されないので修正
      foreach($buyerrecommends as  $key => $value) {
        $buyerrecommend = BuyerRecommend::firstOrNew(['id'=> $key]);
        $buyerrecommend->price = $value['price'];
        $buyerrecommend->start = $value['start'];
        $buyerrecommend->end = $value['end'];
        $buyerrecommend->nouhin_end = $value['nouhin_end'];
        $buyerrecommend->order_no = $value['order_no'];
        $buyerrecommend->save();
      }

      $id = $request->tokuisaki_id;
      return redirect()->route('buyerrecommend', $id);
    }

    public function buyerremovercommend(Request $request){
      $delete_id = $request->delete;
      $delete = BuyerRecommend::where('id',$delete_id)->first();
      if(isset($delete)){
        $delete->delete();
      };
      $id = $request->tokuisaki_id;
      return redirect()->route('buyerrecommend', $id);
    }





// リピートオーダー詳細ページを修正（user_idで取得してSETOnagiユーザーも使えるように）
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

    // dd($request->kaiin_number);

    foreach($repeatorders as  $key => $value) {
      $store = explode(',',$value['store']);
      if(isset($value['nouhin_youbi'])){
      }else{
        $message = '曜日を選択してください';
        $data=[
          'id'=>$request->kaiin_number,
          'message'=>$message,
        ];
        return redirect()->route('repeatorder', $data);
      }
      $nouhin_youbi = $value['nouhin_youbi'];
      $nouhin_youbi = implode(',', $nouhin_youbi);
      $repeatorder = Repeatorder::firstOrNew(['id'=> $key]);
      $repeatorder->price = $value['price'];
      $repeatorder->quantity = $value['quantity'];
      $repeatorder->nouhin_youbi = $nouhin_youbi;
      $repeatorder->status = $value['status'];

      $repeatorder->tokuisaki_name = $store[0];
      $repeatorder->store_name = $store[1];
      $repeatorder->stop_flg = 0;

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
    $user = User::where('id',$riyoukyoka_user_id)->first();
    $setonagi = User::where('id',$riyoukyoka_user_id)->first()->setonagi();
    $setonagi->kakebarai_riyou = 1;
    $setonagi->setonagi_ok = null;
    $setonagi->save();

    $name = $user->name;
    $email = $user->email;
    $admin_mail = config('app.admin_mail');
    $url = url('');
    $text = 'この度、ヤマトクレジットファイナンス株式会社の審査の結果、オーダーブックが利用可能となりました。<br />
    ユーザー登録時にご登録いただいたメールアドレスとパスワードにて、下記URLよりご利用いただけます。<br />
    URL：<a href="'.$url.'">'.$url.'</a>';
    Mail::send('emails.register', [
        'name' => $name,
        'text' => $text,
    ], function ($message) use ($email ,$admin_mail) {
        $message->to($email)->bcc($admin_mail)->subject('SETOnagiオーダーブック審査通過のお知らせ');
    });
    return redirect()->route('admin.setonagiuser');
  }

  public function riyouteisi(Request $request){
    $user_id = $request->user_id;
    $user = User::where('id',$user_id)->first();
    $setonagi = User::where('id',$user_id)->first()->setonagi();
    $setonagi->kakebarai_riyou = null;
    $setonagi->setonagi_ok = null;
    $setonagi->save();

    $name = $user->name;
    $email = $user->email;
    $admin_mail = config('app.admin_mail');
    $url = url('');
    $text = 'この度、ヤマトクレジットファイナンス株式会社の審査の結果、<br />
    オーダーブックが利用不可となりましたことをお知らせいたします。';
    Mail::send('emails.register', [
        'name' => $name,
        'text' => $text,
    ], function ($message) use ($email , $admin_mail) {
        $message->to($email)->bcc($admin_mail)->subject('SETOnagiオーダーブック利用停止のお知らせ');
    });
    return redirect()->route('admin.setonagiuser');
  }

  public function card_riyoukyoka(Request $request){
    $user_id = $request->user_id;
    $user = User::where('id',$user_id)->first();
    $setonagi = User::where('id',$user_id)->first()->setonagi();
    $setonagi->kakebarai_riyou = null;
    $setonagi->setonagi_ok = 1;
    $setonagi->save();

    $name = $user->name;
    $email = $user->email;
    $url = url('');
    $admin_mail = config('app.admin_mail');
    $text = 'この度、ヤマトクレジットファイナンス株式会社の審査の結果、かけ払いでのお支払いはご利用いただけない結果となりました。<br />
    クレジットカード払いでの利用については可能です。<br />
    ユーザー登録時にご登録いただいたメールアドレスとパスワードにて、下記URLよりご利用いただけます。<br />
    URL：<a href="'.$url.'">'.$url.'</a>';
    Mail::send('emails.register', [
        'name' => $name,
        'text' => $text,
    ], function ($message) use ($email , $admin_mail) {
        $message->to($email)->bcc($admin_mail)->subject('SETOnagiオーダーブック審査結果のお知らせ');
    });
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

  public function Export(Request $request){

    $kikan = $request->kikan;
    $start = $request->start;
    $end = $request->end;


    // 次の営業日を取得
    $today = date("Y-m-d");
    $holidays = Holiday::pluck('date');
    $currentTime = date('H:i:s');
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


    // テンプレートファイルを読み込み
    $spreadsheet = IOFactory::load(public_path() . '/storage/excel/template.csv');

    // アクティブなシートを取得
    $sheet = $spreadsheet->getActiveSheet();
    // シートを指定する場合は記述
		// $sheet = $spreadsheet->getSheetByName("原本");

    $deals =  Deal::where('status','発注済')->orwhere('status','キャンセル')->get();
    // dd($deals);

    if($deals){
      $order_list=[];
      foreach ($deals as $deal) {
        // dd($deal);
        $carts = Cart::where(['deal_id'=> $deal->id])->get();
        // カート商品の出力
        foreach ($carts as $cart) {
          if($kikan){
            // dd($end);
            $orders = Order::where(['cart_id'=> $cart->id])->whereBetween('nouhin_yoteibi', [$start, $end])->get();
          }else{
            $orders = Order::where(['cart_id'=> $cart->id , 'nouhin_yoteibi'=> $nouhin_yoteibi])->get();
          }
          foreach ($orders as $order) {
            // dd($orders);
            // orderslist出力
            $user = User::where('id',$deal->user_id)->first();
            if($user->setonagi == 1){
              $setonagi_user = Setonagi::where('user_id',$user->id)->first();
              // dd($setonagi_user);
            }
            $item = Item::where(['id'=> $cart->item_id])->first();
            $zei = '8%';
            // dd($user);
            if(!($user->setonagi == 1)){
              $store = Store::where(['tokuisaki_name'=> $order->tokuisaki_name , 'store_name'=> $order->store_name])->first();
            }
            if(!($user->setonagi == 1)){
              // 会社名
              $company = $order->tokuisaki_name;
              // 会員No
              $kaiin_number = $user->kaiin_number;
              // 郵便番号
              $yuubin = $store->yuubin;
              // 住所
              $jyuusho = $store->jyuusho1;
              // 電話番号
              $tel = $store->tel;
              // FAX番号
              $fax = $store->fax;
              // 配送先氏名
              $store_name = $order->store_name;
              // 配送先郵便番号
              $yuubin = $store->yuubin;
              // 配送先住所
              $jyuusho = $store->jyuusho1.$store->jyuusho2;
              // 配送先電話番号
              $tel = $store->tel;
              // 配送先FAX番号
              $fax = $store->fax;
              // 支払方法
              $pay = 'クラハシ払い';
              // 取引種別
              $torihiki_shubetu = $store->torihiki_shubetu;
              // 引き渡し場所
              $uketori_place = $store->uketori_place;
              // 得意先コード
              $tokuisaki_id = $store->tokuisaki_id;
              // 得意先店舗コード
              $store_id = $store->store_id;
              // 得意先名
              $tokuisaki_name = $store->tokuisaki_name;
              // 得意先店舗名
              $store_name = $store->tokuisaki_name;
            }else{
              // 会社名
              $company = $setonagi_user->company;
              // 会員No
              $kaiin_number = 's'.$user->id;
              // 郵便番号
              $yuubin = $setonagi_user->address01;
              // 住所
              $jyuusho = $setonagi_user->address02.$setonagi_user->address03.$setonagi_user->address04;
              // 電話番号
              $tel = $user->tel;
              // FAX番号
              $fax = null;
              // 配送先氏名
              $store_name = $setonagi_user->last_name.$setonagi_user->first_name;
              // 配送先住所
              $jyuusho = $setonagi_user->address02.$setonagi_user->address03.$setonagi_user->address04;
              // 配送先電話番号
              // $tel = $setonagi_user->tel;
              // 配送先FAX番号
              // $fax = $setonagi_user->fax;
              // 支払方法
              if($deal->uketori_siharai == 'クレジットカード払い'){
                $pay = 'クレジットカード払い';
              }elseif($deal->uketori_siharai == 'クロネコかけ払い'){
                $pay = 'クロネコかけ払い';
              }
              // 取引種別
              $torihiki_shubetu = 1;
              // 引き渡し場所
              if($deal->uketori_place == '福山魚市引き取り'){
                $uketori_place = '010';
              }elseif($deal->uketori_place == '引取り（マリンネクスト）'){
                $uketori_place = '020';
              }elseif($deal->uketori_place == '引取り（尾道ケンスイ）'){
                $uketori_place = '030';
              }
              // 得意先コード
              $tokuisaki_id = null;
              // 得意先店舗コード
              $store_id = null;
              // 得意先名
              $tokuisaki_name = null;
              // 得意先店舗名
              $store_name = null;
            }
            if($deal->status == '発注済'){
              $deal_status = 1;
            }elseif($deal->status == 'キャンセル'){
              $deal_status = 0;
            }
            $array = [
              // 取引番号
              "\"".$deal->id."\"",
              // カート番号
              "\"".$cart->id."\"",
              // 注文日時
              "\"".$deal->success_time."\"",
              // 会員No
              "\"".$kaiin_number."\"",
              // Eメール
              "\"".$user->email."\"",
              // 氏名
              "\"".$user->name."\"",
              // 屋号_会社名
              "\"".$company."\"",
              // フリガナ
              "\"".$user->name_kana."\"",
              // 郵便番号
              "\"".$yuubin."\"",
              // 国
              '',
              // 都道府県
              "\"".$jyuusho."\"",
              // 市区郡町村
              '',
              // 番地
              '',
              // ビル名
              '',
              // 電話番号
              "\"".$tel."\"",
              // FAX番号
              "\"".$fax."\"",
              // 配送先氏名
              "\"".$store_name."\"",
              // 配送先フリガナ
              '',
              // 配送先郵便番号
              "\"".$yuubin."\"",
              // 配送先住所
              "\"".$jyuusho."\"",
              // 配送先電話番号
              "\"".$tel."\"",
              // 配送先FAX番号
              "\"".$fax."\"",
              // 取引種別
              "\"".$torihiki_shubetu."\"",
              // 発送日
              '',
              // 支払方法
              "\"".$pay."\"",
              // 決済ID
              "\"".$deal->id."\"",
              // 配送方法
              '',
              // 配送希望日
              "\"".$order->nouhin_yoteibi."\"",
              // 配送時間帯
              "\"".$deal->uketori_time."\"",
              // 発送予定日
              '',
              // ステータス
              "\"".$deal_status."\"",
              // 送り状番号
              '',
              // 総合計金額
              '',
              // 商品合計
              '',
              // 送料
              '',
              // 代引手数料
              '',
              // 内消費税
              "\"".$zei."\"",
              // 備考
              "\"".$deal->memo."\"",
              // 注文行番号
              "\"".$order->id."\"",
              // 商品コード
              "\"".$item->item_id."\"",
              // SKUコード
              "\"".$item->sku_code."\"",
              // 商品名
              "\"".$item->item_name."\"",
              // SKU表示名
              "\"".$item->sku_code."\"",
              // 商品オプション
              '',
              // 数量
              "\"".$order->quantity."\"",
              // 単価
              "\"".$order->price."\"",
              // 単位
              "\"".$item->tani."\"",
              // 引渡場所
              "\"".$uketori_place."\"",
              // 発注先企業
              "\"".$item->kigyou_code."\"",
              // 発注先部署コード
              "\"".$item->busho_code."\"",
              // 発注先部署名
              "\"".$item->busho_name."\"",
              // 発注先当者者コード
              "\"".$item->tantou_code."\"",
              // 発注先当者名
              "\"".$item->tantou_name."\"",
              // 入荷日
              "\"".$item->nyuukabi."\"",
              // 荷主コード
              '',
              // ロット番号
              "\"".$item->lot_bangou."\"",
              // ロット行
              "\"".$item->lot_gyou."\"",
              // ロット枝
              "\"".$item->lot_eda."\"",
              // 倉庫コード
              "\"".$item->souko_code."\"",
              // 値引率
              '',
              // 得意先コード
              "\"".$tokuisaki_id."\"",
              // 得意先店舗コード
              "\"".$store_id."\"",
              // 得意先名
              "\"".$tokuisaki_name."\"",
              // 得意先店舗名
              "\"".$store_name."\"",
            ];
            // dd($array);
      			array_push($order_list, $array);
          }
        }
        // 任意の商品を出力
        $cart_ninis = CartNini::where(['deal_id'=> $deal->id])->get();
        // dd($cart_ninis);
        foreach ($cart_ninis as $cart_nini) {
          if($kikan){
            $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->whereBetween('nouhin_yoteibi', [$start, $end])->get();
            // dd($order_ninis);
          }else{
            $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id , 'nouhin_yoteibi'=> $nouhin_yoteibi])->get();
          }
          foreach ($order_ninis as $order_nini) {
            // dd($orders);
            // orderslist出力
            $user = User::where('id',$deal->user_id)->first();
            // dd($user);
            $store = Store::where(['tokuisaki_name'=> $order_nini->tokuisaki_name , 'store_name'=> $order_nini->store_name])->first();

            // 商品コード
            if($cart_nini->tantou_name == '青物'){
              $item_code = 101911009;
            }elseif($cart_nini->tantou_name == '太物'){
              $item_code = 101913009;
            }elseif($cart_nini->tantou_name == '近海'){
              $item_code = 101914009;
            }elseif($cart_nini->tantou_name == '養魚'){
              $item_code = 101915009;
            }elseif($cart_nini->tantou_name == '特種'){
              $item_code = 101916009;
            }elseif($cart_nini->tantou_name == '水産'){
              $item_code = 101945009;
            }else{
              $item_code = '';
            }
            $pay = 'クラハシ払い';
            if($deal->status == '受注済'){
              $deal_status = 1;
            }elseif($deal->status == 'キャンセル'){
              $deal_status = 0;
            }
            // $item = Item::where(['id'=> $cart->item_id])->first();
            // dd($store);
            $array = [
              // 取引番号
              "\"".$deal->id."\"",
              // カート番号
              "\"".$cart->id."\"",
              // 注文日時
              "\"".$deal->success_time."\"",
              // 会員No
              "\"".$user->kaiin_number."\"",
              // Eメール
              "\"".$user->email."\"",
              // 氏名
              "\"".$user->name."\"",
              // 屋号_会社名
              "\"".$order_nini->tokuisaki_name."\"",
              // フリガナ
              "\"".$user->name_kana."\"",
              // 郵便番号
              "\"".$store->yuubin."\"",
              // 国
              '',
              // 都道府県
              "\"".$store->jyuusho1."\"",
              // 市区郡町村
              '',
              // 番地
              '',
              // ビル名
              '',
              // 電話番号
              "\"".$store->tel."\"",
              // FAX番号
              "\"".$store->fax."\"",
              // 配送先氏名
              "\"".$order_nini->store_name."\"",
              // 配送先フリガナ
              '',
              // 配送先郵便番号
              "\"".$store->yuubin."\"",
              // 配送先住所
              "\"".$store->jyuusho1.$store->jyuusho2."\"",
              // 配送先電話番号
              "\"".$store->tel."\"",
              // 配送先FAX番号
              "\"".$store->fax."\"",
              // 取引種別
              "\"".$store->torihiki_shubetu."\"",
              // 発送日
              '',
              // 支払方法
              "\"".$pay."\"",
              // 決済ID
              '',
              // 配送方法
              '',
              // 配送希望日
              "\"".$order_nini->nouhin_yoteibi."\"",
              // 配送時間帯
              '',
              // 発送予定日
              '',
              // ステータス
              "\"".$deal_status."\"",
              // 送り状番号
              '',
              // 総合計金額
              '',
              // 商品合計
              '',
              // 送料
              '',
              // 代引手数料
              '',
              // 内消費税
              "\"".$zei."\"",
              // 備考
              "\"".$deal->memo."\"",
              // 注文行番号
              "\""."n".$order->id."\"",
              // 商品コード
              "\"".$item_code."\"",
              // SKUコード
              '',
              // 商品名
              "\"".$cart_nini->item_name."\"",
              // SKU表示名
              '',
              // 商品オプション
              '',
              // 数量
              "\"".$order_nini->quantity."\"",
              // 単価
              // "\"".$order_nini->price."\"",
              '',
              // 単位
              '',
              // 引渡場所
              '',
              // 発注先企業
              '',
              // 発注先部署コード
              '',
              // 発注先部署名
              '',
              // 発注先当者者コード
              '',
              // 発注先当者名
              '',
              // 入荷日
              '',
              // 荷主コード
              '',
              // ロット番号
              '',
              // ロット行
              '',
              // ロット枝
              '',
              // 倉庫コード
              '',
              // 値引率
              ''
            ];
      			array_push($order_list, $array);
          }
        }
      }
    }

    // リピートオーダーを出力

    $repeatorders = Repeatorder::where('status','有効')->get();
    if(isset($repeatorders)){
      if($kikan){
        for ($nouhin_yoteibi = $start; $nouhin_yoteibi <= $end; $nouhin_yoteibi = date('Y-m-d', strtotime($nouhin_yoteibi . '+1 day'))) {
          foreach ($repeatorders as $repeatorder) {
            if($repeatorder->startdate <= $nouhin_yoteibi){
              $date = new Carbon($nouhin_yoteibi);
              $weekday = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
              $weekday = $weekday[$date->dayOfWeek];
              // dd($weekday);
              $nouhin_youbi = explode(',', $repeatorder->nouhin_youbi);
              // dd($nouhin_youbi);
              // dd($weekday);
              // 曜日が含まれているか確認

              // dd($repeatorder->cart);
              $repeatcart = $repeatorder->cart;
              $zei = '8%';
              $key = in_array($weekday, $nouhin_youbi);
              if($key == true){
                // 会員IDを取得して、ユーザー情報を取得するところから
                $user = User::where('kaiin_number',$repeatcart->kaiin_number)->first();
                $item = Item::where(['item_id'=> $repeatcart->item_id, 'sku_code'=> $repeatcart->sku_code])->first();
                $store = Store::where(['tokuisaki_name'=> $repeatorder->tokuisaki_name , 'store_name'=> $repeatorder->store_name])->first();
                if(!($user->setonagi == 1)){
                  // 会社名
                  $company = $repeatorder->tokuisaki_name;
                  // 会員No
                  $kaiin_number = $user->kaiin_number;
                  // 郵便番号
                  $yuubin = $store->yuubin;
                  // 住所
                  $jyuusho = $store->jyuusho1;
                  // 電話番号
                  $tel = $store->tel;
                  // FAX番号
                  $fax = $store->fax;
                  // 配送先氏名
                  $store_name = $repeatorder->store_name;
                  // 配送先郵便番号
                  $yuubin = $repeatorder->yuubin;
                  // 配送先住所
                  $jyuusho = $store->jyuusho1.$store->jyuusho2;
                  // 配送先電話番号
                  $tel = $store->tel;
                  // 配送先FAX番号
                  $fax = $store->fax;
                  // 支払方法
                  $pay = 'クラハシ払い';
                  // 取引種別
                  $torihiki_shubetu = $store->torihiki_shubetu;
                  // 得意先コード
                  $tokuisaki_id = $store->tokuisaki_id;
                  // 得意先店舗コード
                  $store_id = $store->store_id;
                  // 得意先名
                  $tokuisaki_name = $store->tokuisaki_name;
                  // 得意先店舗名
                  $store_name = $store->tokuisaki_name;
                }
                $array = [
                  // 取引番号
                  "\"".'r'.$repeatcart->id."\"",
                  // カート番号
                  "\"".'r'.$repeatcart->id."\"",
                  // 注文日時
                  "\"".$repeatorder->updated_at."\"",
                  // 会員No
                  "\"".$user->kaiin_number."\"",
                  // Eメール
                  "\"".$user->email."\"",
                  // 氏名
                  "\"".$user->name."\"",
                  // 屋号_会社名
                  "\"".$company."\"",
                  // フリガナ
                  "\"".$user->name_kana."\"",
                  // 郵便番号
                  "\"".$yuubin."\"",
                  // 国
                  '',
                  // 都道府県
                  "\"".$jyuusho."\"",
                  // 市区郡町村
                  '',
                  // 番地
                  '',
                  // ビル名
                  '',
                  // 電話番号
                  "\"".$tel."\"",
                  // FAX番号
                  "\"".$fax."\"",
                  // 配送先氏名
                  "\"".$store_name."\"",
                  // 配送先フリガナ
                  '',
                  // 配送先郵便番号
                  "\"".$yuubin."\"",
                  // 配送先住所
                  "\"".$jyuusho."\"",
                  // 配送先電話番号
                  "\"".$tel."\"",
                  // 配送先FAX番号
                  "\"".$fax."\"",
                  // 取引種別
                  "\"".$torihiki_shubetu."\"",
                  // 発送日
                  '',
                  // 支払方法
                  "\"".$pay."\"",
                  // 決済ID
                  '',
                  // 配送方法
                  '',
                  // 配送希望日
                  "\"".$date."\"",
                  // 配送時間帯
                  '',
                  // 発送予定日
                  '',
                  // ステータス
                  '有効',
                  // 送り状番号
                  '',
                  // 総合計金額
                  '',
                  // 商品合計
                  '',
                  // 送料
                  '',
                  // 代引手数料
                  '',
                  // 内消費税
                  "\"".$zei."\"",
                  // 備考
                  '',
                  // 注文行番号
                  "\"".'r'.$repeatorder->id."\"",
                  // 商品コード
                  "\"".$item->item_id."\"",
                  // SKUコード
                  "\"".$item->sku_code."\"",
                  // 商品名
                  "\"".$item->item_name."\"",
                  // SKU表示名
                  "\"".$item->sku_code."\"",
                  // 商品オプション
                  '',
                  // 数量
                  "\"".$repeatorder->quantity."\"",
                  // 単価
                  "\"".$repeatorder->price."\"",
                  // 単位
                  "\"".$item->tani."\"",
                  // 引渡場所
                  '',
                  // 発注先企業
                  "\"".$item->kigyou_code."\"",
                  // 発注先部署コード
                  "\"".$item->busho_code."\"",
                  // 発注先部署名
                  "\"".$item->busho_name."\"",
                  // 発注先当者者コード
                  "\"".$item->tantou_code."\"",
                  // 発注先当者名
                  "\"".$item->tantou_name."\"",
                  // 入荷日
                  "\"".$item->nyuukabi."\"",
                  // 荷主コード
                  '',
                  // ロット番号
                  "\"".$item->lot_bangou."\"",
                  // ロット行
                  "\"".$item->lot_gyou."\"",
                  // ロット枝
                  "\"".$item->lot_eda."\"",
                  // 倉庫コード
                  "\"".$item->souko_code."\"",
                  // 値引率
                  '',
                  // 得意先コード
                  "\"".$tokuisaki_id."\"",
                  // 得意先店舗コード
                  "\"".$store_id."\"",
                  // 得意先名
                  "\"".$tokuisaki_name."\"",
                  // 得意先店舗名
                  "\"".$store_name."\"",
                ];
                // dd($array);
          			array_push($order_list, $array);
              }
            }
          }
        }
        // $repeatorders = Repeatorder::whereBetween('startdate', [$start, $end])->get();
        // dd($order_ninis);
      }else{
        foreach ($repeatorders as $repeatorder) {
          if($repeatorder->startdate <= $nouhin_yoteibi){
            $date = new Carbon($nouhin_yoteibi);
            $weekday = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            $weekday = $weekday[$date->dayOfWeek];
            // dd($weekday);
            $nouhin_youbi = explode(',', $repeatorder->nouhin_youbi);
            // dd($nouhin_youbi);
            // dd($weekday);
            // 曜日が含まれているか確認

            // dd($repeatorder->cart);
            $repeatcart = $repeatorder->cart;
            $zei = '8%';
            $key = in_array($weekday, $nouhin_youbi);
            if($key == true){
              // 会員IDを取得して、ユーザー情報を取得するところから
              $user = User::where('kaiin_number',$repeatcart->kaiin_number)->first();
              $item = Item::where(['item_id'=> $repeatcart->item_id, 'sku_code'=> $repeatcart->sku_code])->first();
              $store = Store::where(['tokuisaki_name'=> $repeatorder->tokuisaki_name , 'store_name'=> $repeatorder->store_name])->first();
              if(!($user->setonagi == 1)){
                // 会社名
                $company = $repeatorder->tokuisaki_name;
                // 会員No
                $kaiin_number = $user->kaiin_number;
                // 郵便番号
                $yuubin = $store->yuubin;
                // 住所
                $jyuusho = $store->jyuusho1;
                // 電話番号
                $tel = $store->tel;
                // FAX番号
                $fax = $store->fax;
                // 配送先氏名
                $store_name = $repeatorder->store_name;
                // 配送先郵便番号
                $yuubin = $repeatorder->yuubin;
                // 配送先住所
                $jyuusho = $store->jyuusho1.$store->jyuusho2;
                // 配送先電話番号
                $tel = $store->tel;
                // 配送先FAX番号
                $fax = $store->fax;
                // 支払方法
                $pay = 'クラハシ払い';
                // 取引種別
                $torihiki_shubetu = $store->torihiki_shubetu;
                // 得意先コード
                $tokuisaki_id = $store->tokuisaki_id;
                // 得意先店舗コード
                $store_id = $store->store_id;
                // 得意先名
                $tokuisaki_name = $store->tokuisaki_name;
                // 得意先店舗名
                $store_name = $store->tokuisaki_name;
              }
              $array = [
                // 取引番号
                "\"".'r'.$repeatcart->id."\"",
                // カート番号
                "\"".'r'.$repeatcart->id."\"",
                // 注文日時
                "\"".$repeatorder->updated_at."\"",
                // 会員No
                "\"".$user->kaiin_number."\"",
                // Eメール
                "\"".$user->email."\"",
                // 氏名
                "\"".$user->name."\"",
                // 屋号_会社名
                "\"".$company."\"",
                // フリガナ
                "\"".$user->name_kana."\"",
                // 郵便番号
                "\"".$yuubin."\"",
                // 国
                '',
                // 都道府県
                "\"".$jyuusho."\"",
                // 市区郡町村
                '',
                // 番地
                '',
                // ビル名
                '',
                // 電話番号
                "\"".$tel."\"",
                // FAX番号
                "\"".$fax."\"",
                // 配送先氏名
                "\"".$store_name."\"",
                // 配送先フリガナ
                '',
                // 配送先郵便番号
                "\"".$yuubin."\"",
                // 配送先住所
                "\"".$jyuusho."\"",
                // 配送先電話番号
                "\"".$tel."\"",
                // 配送先FAX番号
                "\"".$fax."\"",
                // 取引種別
                "\"".$torihiki_shubetu."\"",
                // 発送日
                '',
                // 支払方法
                "\"".$pay."\"",
                // 決済ID
                '',
                // 配送方法
                '',
                // 配送希望日
                "\"".$date."\"",
                // 配送時間帯
                '',
                // 発送予定日
                '',
                // ステータス
                '有効',
                // 送り状番号
                '',
                // 総合計金額
                '',
                // 商品合計
                '',
                // 送料
                '',
                // 代引手数料
                '',
                // 内消費税
                "\"".$zei."\"",
                // 備考
                '',
                // 注文行番号
                "\"".'r'.$repeatorder->id."\"",
                // 商品コード
                "\"".$item->item_id."\"",
                // SKUコード
                "\"".$item->sku_code."\"",
                // 商品名
                "\"".$item->item_name."\"",
                // SKU表示名
                "\"".$item->sku_code."\"",
                // 商品オプション
                '',
                // 数量
                "\"".$repeatorder->quantity."\"",
                // 単価
                "\"".$repeatorder->price."\"",
                // 単位
                "\"".$item->tani."\"",
                // 引渡場所
                '',
                // 発注先企業
                '',
                // 発注先部署コード
                "\"".$item->kigyou_code."\"",
                // 発注先部署名
                "\"".$item->bucho_code."\"",
                // 発注先当者者コード
                "\"".$item->tantou_code."\"",
                // 発注先当者名
                "\"".$item->tantou_name."\"",
                // 入荷日
                "\"".$item->nyuukabi."\"",
                // 荷主コード
                '',
                // ロット番号
                "\"".$item->lot_bangou."\"",
                // ロット行
                "\"".$item->lot_gyou."\"",
                // ロット枝
                "\"".$item->lot_eda."\"",
                // 倉庫コード
                "\"".$item->souko_code."\"",
                // 値引率
                '',
                // 得意先コード
                "\"".$tokuisaki_id."\"",
                // 得意先店舗コード
                "\"".$store_id."\"",
                // 得意先名
                "\"".$tokuisaki_name."\"",
                // 得意先店舗名
                "\"".$store_name."\"",
              ];
              // dd($array);
        			array_push($order_list, $array);
            }
          }
        }
      }
    }
    // dd($repeatorders);
    // dd($repeatorders);
    $today = date("Y-m-d");
    foreach ($repeatorders as $repeatorder) {
      $startdate = $repeatorder->startdate;
      if(isset($startdate)){

      }
    }

    // dd($deals);
    // dd($order_list);
    $sheet->fromArray($order_list,null,'A2');
    // データを上書き
		// $sheet->setCellValue('A1', 'test');

    // アップロードディレクトリを指定
    File::setUseUploadTempDirectory(public_path());

    $writer = new Csv($spreadsheet);
    $writer->save(public_path() . '/storage/excel/export.csv');
    $now = Carbon::now();
    return response()->download(public_path() . '/storage/excel/export.csv', 'orderbook'.$now.'.csv')->deleteFileAfterSend(true);

  }

}
