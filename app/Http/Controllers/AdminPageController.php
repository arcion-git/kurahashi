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

// BtoC向け
use App\ShippingCalender;
use App\ShippingCompanyCode;
use App\ShippingInfo;
use App\ShippingSetting;

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

use App\Imports\BuyerRecommendImport;

// BtoC向け
use App\Imports\ShippingCalenderImport;
use App\Imports\ShippingCompanyCodeImport;
use App\Imports\ShippingInfoImport;
use App\Imports\ShippingSettingImport;


// ページネーション
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
    $deals = Deal::latest('created_at')->paginate(30);


    $tokuisakis = Store::select('tokuisaki_name')->distinct()->get();

    // dd($store_users);
    // foreach ($store_users as $key => $value) {
    //   dd($value);
    // }
    $data=[
      'deals'=>$deals,
      'tokuisakis'=>$tokuisakis,
    ];
    return view('admin/home',$data);
  }



  public function change_tokuisaki_name(Request $request){
      $tokuisaki_name = $request->tokuisaki_name;
      $data=[
        'tokuisaki_name'=>$tokuisaki_name,
      ];
      // return redirect()->route('/admin/home',$data);
      return redirect()->route('admin.search')->with($data);
      // return view('admin/home',$data);
  }

  public function search(Request $request){



    $tokuisakis = Store::select('tokuisaki_name')->distinct()->get();

    // dd($store_users);

    $search = $request->search;
    $tokuisaki_name = $request->tokuisaki_name;

    if($tokuisaki_name != 'すべてバイヤー'){
      $store = Store::where('tokuisaki_name',$tokuisaki_name)->first();
      $store->tokuisaki_id;
      $stores = store::where('tokuisaki_id',$store->tokuisaki_id)->get();
      // dd($stores);
    }else{
      $stores = null;
    }

    // dd($tokuisaki_name);
    $store_name = $request->store_name;
    $cat = $request->cat;

    if($cat == 'すべての取引'){
      $all_deals = Deal::get();
    }elseif($cat == '受注済'){
      $all_deals = Deal::where('status','発注済')->get();
    }else{
      $all_deals = Deal::where('status', $cat)->get();
    }

    $deals = [];
    foreach ($all_deals as $all_deal) {
        // バイヤーが「すべてバイヤー」の場合
        if ($tokuisaki_name == 'すべてバイヤー') {
            $user_name = User::where('id', $all_deal->user_id)->where('name', 'like', "%$search%")->first();
            if ($user_name) {
                array_push($deals, $all_deal);
            }
        } else {
            $carts = Cart::where('deal_id', $all_deal->id)->get();
            foreach ($carts as $cart) {
                if ($store_name == 'すべての店舗' || $store_name == null) {
                    $order = Order::where(['cart_id' => $cart->id, 'tokuisaki_name' => $tokuisaki_name])->first();
                } else {
                    $order = Order::where(['cart_id' => $cart->id, 'tokuisaki_name' => $tokuisaki_name, 'store_name' => $store_name])->first();
                }
                if ($order) {
                    array_push($deals, $all_deal);
                    break;
                }
            }
        }
    }


    // $dealsをCollectionインスタンスに変換
    // $dealsCollection = new Collection($deals);
    $dealsCollection = collect($deals)->sortByDesc('created_at');

    // LengthAwarePaginatorインスタンスを作成
    $perPage = 30; // 1ページあたりのデータ数
    $page = LengthAwarePaginator::resolveCurrentPage(); // 現在のページ数を取得
    $path = url()->current(); // 現在のURLを取得
    $deals = new LengthAwarePaginator(
        $dealsCollection->forPage($page, $perPage),
        $dealsCollection->count(),
        $perPage,
        $page,
        ['path' => $path, 'query' => request()->query()]
    );

    // $deals = new LengthAwarePaginator($deals, count($deals), 3, 1);
    $data=[
      'cat'=>$cat,
      'tokuisaki_name'=>$tokuisaki_name,
      'store_name'=>$store_name,
      'deals'=>$deals,
      'tokuisakis'=>$tokuisakis,
      'stores'=>$stores,
    ];
    return view('admin/home',$data);
  }



  // public function dealdetail($id){
  //
  //   $deal = Deal::where('id',$id)->first();
  //
  //   $carts = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->get();
  //   $cart_ninis = CartNini::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->get();
  //
  //   $user = User::where(['id'=>$deal->user_id])->first();
  //
  //   $nouhin_yoteibi = null;
  //   if($user->setonagi == 1){
  //     $cart_id = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->first()->id;
  //     $nouhin_yoteibi = Order::where(['cart_id'=>$cart_id])->first()->nouhin_yoteibi;
  //   }
  //
  //   $data=[
  //     'nouhin_yoteibi'=>$nouhin_yoteibi,
  //     'carts'=>$carts,
  //     'cart_ninis'=>$cart_ninis,
  //     'deal'=>$deal,
  //   ];
  //   return view('dealdetail', $data);
  // }



  public function dealorder(Request $request){

    $deal_id = $request->deal_id;

    $deal = Deal::where(['id'=> $deal_id])->first();

    $user_id = $deal->user_id;
    // dd($user_id);

    // 取引IDが一致しているものを取得
    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
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
     'set_order' => $set_order,
     'collect' => $collect,
    ];
    return view('order', $data);
  }






  public function discount(Request $request){

    $id = $request->deal_id;
    // dd($id);

    $order_ids = $request->order_id;
    $prices = $request['price'];
    // dd($order_ids);
    $prices = array_combine($order_ids, $prices);

    foreach($prices as $key => $value) {
      $order = Order::firstOrNew(['id'=> $key]);
      $order->price = $value;
      $order->save();
    }

    $deal_id = $request->deal_id;
    $deal = Deal::where('id',$deal_id)->first();
    $user = User::where('id',$deal->user_id)->first();
    $user_id = $deal->user_id;

    // dd($request);
    $zeinuki = $request->all_total_val - $request->tax_val;


    // BtoC+SB合計金額計算
    if($user->setonagi == 1){
      $total_price = [];
      $carts = Cart::where(['deal_id'=> $deal_id])->get();
      // カート商品の出力
      foreach($carts as $cart) {
        $orders = Order::where(['cart_id'=> $cart->id])->get();
        foreach ($orders as $order) {
          // dd($store);
          // 金額未定に対応
          if($order->price == '未定' || $order->price == '-'){
            $array = 0;
          }else{
            $array = $order->price * $order->quantity;
          }
          array_push($total_price, $array);
        }
      }
      // 送料がかかる場合の表示設定
      // 商品合計
      $total_price = array_sum($total_price);
      // 商品合計税込
      $all_total_price = floor($total_price * (108 / 100));
      // 8%税額
      $zeinomi8 = $all_total_price - $total_price;

      if(isset($shipping_price)){
        // 送料税込
        $shipping_price_zei = floor($shipping_price * (110 / 100));
        // 10%税のみ
        $zeinomi10 = $shipping_price_zei - $shipping_price;
        // 10%対象消費合計額（カンマ表示）
        // 税込合計金額
        $all_total_price = $shipping_price_zei + $all_total_price;
        // 税金の計算
        $zei_price = $all_total_price - $total_price - $shipping_price;
      }else{
        // 税金の計算
        $zei_price = $all_total_price - $total_price;
      }




      // SETOnagiユーザーはクレジットカード、もしくは、ヤマトかけばらいの金額を変更する
      // クロネコかけ払い決済金額変更
      if($deal->uketori_siharai == 'クロネコかけ払い'){
        // ヤマトAPI連携確認
        $client = new Client();
        $url = config('app.kakebarai_pricechange');
        $kakebarai_traderCode = config('app.kakebarai_traderCode');
        $kakebarai_passWord = config('app.kakebarai_passWord');
        $envi = config('app.envi');


        // カート商品の出力
        $cart_items = [];
        $n = 1;
        // カート商品の出力
        $carts = Cart::where(['deal_id'=> $deal_id])->get();
        foreach($carts as $cart) {
          $orders = Order::where(['cart_id'=> $cart->id])->get();
          foreach ($orders as $order) {
            $item = Item::where(['id' => $cart->item_id])->first();
          }
          $cart_items['shohinCode' . $n] = $item->item_id;
          $cart_items['shohinMei' . $n] = $item->item_name;
          $cart_items['suryo' . $n] = $order->quantity;
          $cart_items['tanka' . $n] = $order->price;
          $cart_items['tani' . $n] = $item->kuroneko_item_tani();
          $cart_items['kessaiKingaku' . $n] = $order->price * $order->quantity;
          $cart_items['zeiritsuKbn' . $n] = '2';
          $n++;
        }
        // dd($cart_items);

        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'UTF-8',
          ],
          'form_params' => [
            'traderCode' => $kakebarai_traderCode,
            // 日付
            'orderNo' => $deal_id.$envi,
            // バイヤーid
            'buyerId' => $user_id,
            'settlePrice' => $total_price,
            'passWord' => $kakebarai_passWord
          ]
        ];

        // $cart_items 配列の内容を $option の 'form_params' に追加
        foreach($cart_items as $key => $value) {
            $option['form_params'][$key] = $value;
        }
        // dd($option);

        $response = $client->request('POST', $url, $option);
        $result = simplexml_load_string($response->getBody()->getContents());
        // dd($result);
        // エラーの場合戻す
        if($result->returnCode == 1){
          // $delete_deal = Deal::where(['id'=> $deal_id])->first()->delete();
          if($result->errorCode == 123456){
            // 後で処理を作る
            $message = '掛け払い金額オーバー';
            $data=[
              'id' => $id,
              'message' => $message,
            ];
          }else{
            $message = '決済金額変更エラー。金額の変更ができませんでした。';
            $data=[
              'id' => $id,
              'message' => $message,
            ];
          }
          return redirect()->route('admin.dealdetail',$data);
        }
      }



      // クレジットカード決済の金額を変更
      // if($request->uketori_siharai == 'クレジットカード払い'){
      //   // dd($request->token_api);
      //   // EPトークン取得
      //   $client = new Client();
      //   // $url = 'https://api.kuronekoyamato.co.jp/api/credit';
      //
      //   $url = config('app.collect_pricechange');
      //   $collect_tradercode = config('app.collect_tradercode');
      //
      //   $option = [
      //     'headers' => [
      //       'Accept' => '*/*',
      //       'Content-Type' => 'application/x-www-form-urlencoded',
      //       'charset' => 'UTF-8',
      //     ],
      //     'form_params' => [
      //       'function_div' => 'A07',
      //       'trader_code' => $collect_tradercode,
      //       'order_no' => $deal_id,
      //       // 決済変更金額
      //       'new_price' => $request->all_total_val,
      //     ]
      //   ];
      //   // dd($option);
      //   $response = $client->request('POST', $url, $option);
      //   $result = simplexml_load_string($response->getBody()->getContents());
      //   // dd($result);
      //   if($result->returnCode == 1){
      //     if($result->errorCode == 123456){
      //       // 後で処理を作る
      //       $message = 'クレジットカード決済金額変更エラー';
      //     }else{
      //       $message = 'クレジットカード決済金額変更エラー';
      //     }
      //     $data=[
      //       'id' => $id,
      //       'message' => $message,
      //     ];
      //     return redirect()->route('admin.dealdetail',$data);
      //   }
      // }
    }
    // else{
    //   // 法人ユーザーのみ確認待ちに変更
    //   $deal->status = '確認待';
    // }
    // $deal->kakunin_time = Carbon::now();
    // $deal->save();

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

    foreach ($users as $user) {
      $kaiin_number = $user->kaiin_number;
      if($kaiin_number){
        $tokuisaki = StoreUser::where('user_id',$kaiin_number)->first();
        if($tokuisaki){
          $store = Store::where(['tokuisaki_id' => $tokuisaki->tokuisaki_id ,'store_id' => $tokuisaki->store_id])->first();
          if($store){

          }else{
            // $message = $user->name.'が所属している店舗が見つかりません';
            // $data=[
            //   'message'=>$message,
            // ];
            // return redirect()->route('admin.home', $data);
          }
        }else{
          // $message = $user->name.'が所属している店舗一覧が見つかりません';
          // $data=[
          //   'message'=>$message,
          // ];
          // return redirect()->route('admin.home', $data);
        }
      }

    }


    $users = User::paginate(30);

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
    $envi = config('app.envi');

    foreach ($setonagi_users as $setonagi_user) {
      $user_id = $setonagi_user->user_id;
      $user = User::where('id',$user_id)->first();
      // dd($user_id);
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
          'buyerId' => $user_id.$envi,
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
          'buyerId' => $user_id.$envi,
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
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function adminimport(){
  // admin::truncate();
  Excel::import(new AdminImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function itemimport(){
  // Item::truncate();
  Excel::import(new ItemImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function CategoryItemimport(){
  CategoryItem::truncate();
  Excel::import(new CategoryItemImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function categoryimport(){
  Category::truncate();
  Excel::import(new CategoryImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function tagimport(){
  Tag::truncate();
  Excel::import(new TagImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function HolidayImport(){
  Holiday::truncate();
  Excel::import(new HolidayImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function StoreImport(){
  Store::truncate();
  Excel::import(new StoreImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function StoreUserImport(){
  StoreUser::truncate();
  Excel::import(new StoreUserImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function PriceGroupeImport(){
  PriceGroupe::truncate();
  Excel::import(new PriceGroupeImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function PriceImport(){
  Price::truncate();
  Excel::import(new PriceImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function SpecialPriceImport(){
  // SpecialPrice::truncate();
  Excel::import(new SpecialPriceImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function BuyerRecommendImport(){
  BuyerRecommend::truncate();
  Excel::import(new BuyerRecommendImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function SetonagiImport(){
  Setonagi::truncate();
  Excel::import(new SetonagiImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function SetonagiItemImport(){
  SetonagiItem::truncate();
  Excel::import(new SetonagiItemImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  // BtoC向けCSVインポート
  public function ShippingCalenderImport(){
  ShippingCalender::truncate();
  Excel::import(new ShippingCalenderImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function ShippingCompanyCodeImport(){
  ShippingCompanyCode::truncate();
  Excel::import(new ShippingCompanyCodeImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function ShippingInfoImport(){
  ShippingInfo::truncate();
  Excel::import(new ShippingInfoImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }

  public function ShippingSettingImport(){
  ShippingSetting::truncate();
  Excel::import(new ShippingSettingImport, request()->file('file'));
  $message = '正常にインポートが完了しました。';
  $icon = 'success';
  $data=[
    'message'=>$message,
    'icon'=>$icon,
  ];
  return redirect()->route('admin.csv', $data);
  }


  public function download(){
    return view('admin.auth.download');
  }

  public function userdeal($id){

    $deals = Deal::where('user_id',$id)->paginate(30);
    $user = User::where('id',$id)->first();
    // $store_users = StoreUser::select('tokuisaki_id')->distinct()->get();
    $data=[
      'id'=>$id,
      'deals' => $deals,
      'user'=>$user,
      // 'store_users'=>$store_users,
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
  public function userrecommend(Request $request , $id){
    $item_search = $request->item_search;
    $order_no = $request->ordernosave;
    $user = User::where('id',$id)->first();
    // 水産の前方コード
    $code = 1103;
    // 本番用

    // $items = Item::where("busho_code", "LIKE", $code.'%')->get();
    // 処理が重いので一時的に在庫数のある商品だけを表示
    if(isset($item_search)){
    $items = Item::where("busho_code", "LIKE", $code.'%')
    ->where(function($items) use ($item_search){
    $items->where('item_name','like', "%$item_search%")->orWhere('item_id','like', "%$item_search%");
    })->orWhere('item_name_kana','like', "%$item_search%")->get();
    }else{
    $items = [];
    }
    // dd($items);
    $recommends = Recommend::where('user_id',$user->id)->orderByRaw('CAST(order_no as SIGNED) ASC')->get();
    // dd($recommends);
    $data=[
      'id'=>$id,
      'items'=>$items,
      'user'=>$user,
      'recommends'=>$recommends,
      'item_search'=>$item_search,
      'order_no'=>$order_no,
    ];
    return view('recommend', $data);
  }


  public function addrecommend(Request $request){

    $item_id = $request->item_id;
    $user_id = $request->tokuisaki_id;

    $user = User::where('id',$user_id)->first();
    $item = Item::where('id',$item_id)->first();
    // dd($request);

    // 同じ商品の掲載を禁止する場合
    // $recommend = Recommend::firstOrNew(['user_id'=> $user->id , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
    $recommend = Recommend::create(['user_id'=> $user->id , 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ]);
    $recommend -> save();

    $id = $user_id;

    return redirect()->route('recommend', $id);
  }

  public function userduplicaterecommend(Request $request){

    $user_id = $request->tokuisaki_id;
    // $tokuisaki_id = $request->tokuisaki_id;


    $recommend_item = Recommend::where('id',$request->duplicate)->first();
    // dd($buyerrecommend_item);
    $order_no = $recommend_item->order_no;


    $recommend = Recommend::create(['user_id'=> $user_id,'item_id'=> $recommend_item->item_id , 'sku_code'=> $recommend_item->sku_code,'price'=> $recommend_item->price,'order_no'=> $order_no]);
    $recommend -> save();

    // dd($recommend);

    // $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderBy('order_no')->get();
    $recommends = Recommend::where('user_id',$user_id)->orderByRaw('CAST(order_no as SIGNED) ASC')->get();
    // dd($recommends);
    $n=1;
    foreach ($recommends as $recommend) {
      $recommend->order_no = $n;
      $recommend->save();
      $n++;
    }

    $id = $user_id;

    $data=[
      'id'=>$id,
      // 'item_search'=>$item_search,
      // 'order_no'=>$order_no,
    ];

    return redirect()->route('recommend', $id);
  }

  public function saverecommend(Request $request){

    $user_id = $request->tokuisaki_id;
    $recommends = $request->recommend;

    foreach($recommends as  $key => $value) {
      $recommend = Recommend::firstOrNew(['id'=> $key]);
      $recommend->price = $value['price'];
      $recommend->start = $value['start'];
      $recommend->end = $value['end'];
      $recommend->order_no = $value['order_no'];
      $recommend->save();
    }

    $id = $request->tokuisaki_id;
    return redirect()->route('recommend', $id);
  }

  public function removercommend(Request $request){
    $delete_id = $request->delete;
    $delete = Recommend::where('id',$delete_id)->first()->delete();
    $id = $request->tokuisaki_id;
    return redirect()->route('recommend', $id);
  }





  // 企業ごとの担当のおすすめ商品処理
    public function buyerrecommend(Request $request , $id){
      $item_search = $request->item_search;

      // dd($item_search);

      $order_no = $request->ordernosave;

      $tokuisaki_id = $id;
      // 水産の前方コード
      $code = 1103;
      // 本番用
      // $items = Item::where("busho_code", "LIKE", $code.'%')->get();
      // 処理が重いので一時的に在庫数のある商品だけを表示
      // $items = Item::where("busho_code", "LIKE", $code.'%')->where('zaikosuu', '>=', '0.01')->get();
      // $search = 'いか';

      // 店舗一覧取得
      $store_users = StoreUser::where('tokuisaki_id',$tokuisaki_id)->get(['store_id','tokuisaki_id']);

      $stores = [];
      $n=1;
      foreach ($store_users as $store_user) {
      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
        array_push($stores, $store);
      $n++;
      }

      // dd($stores);
      $store = Store::where('tokuisaki_id',$tokuisaki_id)->first();

      if(isset($item_search)){
          // 在庫数が0のものは非表示
          // $items = Item::where("busho_code", "LIKE", $code.'%')->Where('zaikosuu', '>', '1')
          // 水産の前方コードは関係なしにする
          // $items = Item::where("busho_code", "LIKE", $code.'%')
          $items = Item::where(function($items) use ($item_search){
            $items->where('item_name','like', "%$item_search%")->orWhere('item_id','like', "%$item_search%");
          })->orWhere('item_name_kana','like', "%$item_search%")->get();
          // dd($items);
          $special_price_items = SpecialPrice::get();
          // dd($special_price_items);


          // $items = $items->reject(function ($special_price_items) use ($items) {
          //     return $items->where('item_id', $special_price_items['sku_code'])->isNotEmpty();
          // });

          // $newitems = $items->whereNotIn('item_id', $special_price_items->pluck('sku_code'))->merge($special_price_items->whereNotIn('sku_code', $items->pluck('item_id')));
          // $items = $items->diff($special_price_items);

          // // 市況の商品を取り除く（うまく動いていない）
          // $items = $items->reject(function ($items) use ($special_price_items) {
          //          $special_price_items->contains(function ($special_price_items) use ($items) {
          //          $items['item_id'] === $special_price_items['item_id'] && $items['sku_code'] === $special_price_items['sku_code'];
          //     });
          // });

          // 市況の商品を取り除く
          $items = $items->reject(function ($items) use ($special_price_items) {
                  return $special_price_items->contains(function ($special_price_items) use ($items) {
                  return $items->item_id === $special_price_items->item_id && $items->sku_code === $special_price_items->sku_code;
              });
          });

      }else{
        $items = [];
      }

      // dd($tokuisaki_name->tokuisaki_name);
      // $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderBy('order_no')->get();
      $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderByRaw('CAST(order_no as SIGNED) ASC')->get();


      $groupedItems = $buyerrecommends->groupBy('groupe');
      // dd($groupedItems);

      // dd($buyerrecommends);
      $data=[
        'id'=>$id,
        'items'=>$items,
        'store'=>$store,
        'stores'=>$stores,
        'buyerrecommends'=>$buyerrecommends,
        'item_search'=>$item_search,
        'order_no'=>$order_no,
        'groupedItems'=>$groupedItems,
      ];
      return view('buyerrecommend', $data);
    }



    public function buyerduplicaterecommend(Request $request){


      $tokuisaki_id = $request->tokuisaki_id;

      $buyerrecommend_item = BuyerRecommend::where('id',$request->duplicate)->first();
      // dd($buyerrecommend_item);
      $order_no = $buyerrecommend_item->order_no;

      $recommend = BuyerRecommend::create(['item_id'=> $buyerrecommend_item->item_id , 'sku_code'=> $buyerrecommend_item->sku_code,'price'=> $buyerrecommend_item->price,'tokuisaki_id'=> $buyerrecommend_item->tokuisaki_id ,'order_no'=> $order_no,'uwagaki_item_name'=> $buyerrecommend_item->uwagaki_item_name,'uwagaki_kikaku'=> $buyerrecommend_item->uwagaki_kikaku]);
      $recommend->groupe = $buyerrecommend_item->groupe;
      $recommend -> save();

      // $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderBy('order_no')->get();

      $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderByRaw('CAST(order_no as SIGNED) ASC')->get();
      $n=1;
      foreach ($buyerrecommends as $buyerrecommend) {
        $buyerrecommend->order_no = $n;
        $buyerrecommend->save();
        $n++;
      }

      $id = $tokuisaki_id;

      $data=[
        'id'=>$id,
        // 'item_search'=>$item_search,
        // 'order_no'=>$order_no,
      ];

      return redirect()->route('buyerrecommend', $id);
    }

    public function buyeraddrecommend(Request $request){

      $item_search = $request->item_search;
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
      $recommend = BuyerRecommend::create(['item_id'=> $item->item_id , 'sku_code'=> $item->sku_code ,'tokuisaki_id'=> $request->tokuisaki_id ,'order_no'=> $order_no, 'uwagaki_item_name'=> $item->item_name , 'uwagaki_kikaku'=> $item->kikaku]);
      $recommend -> save();

      // $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderBy('order_no')->get();

      $buyerrecommends = BuyerRecommend::where('tokuisaki_id',$tokuisaki_id)->orderByRaw('CAST(order_no as SIGNED) ASC')->get();
      $n=1;
      foreach ($buyerrecommends as $buyerrecommend) {
        $buyerrecommend->order_no = $n;
        $buyerrecommend->save();
        $n++;
      }

      $id = $tokuisaki_id;

      $data=[
        'id'=>$id,
        // 'item_search'=>$item_search,
        // 'order_no'=>$order_no,
      ];

      return redirect()->route('buyerrecommend', $data);
    }

    public function buyersaverecommend(Request $request){


      // $formDataObject = $request->all();


      // $buyerrecommends = [];
      // foreach ($request->json()->all() as $key => $value) {
      //     if (strpos($key, 'buyerrecommend') === 0) {
      //         $buyerrecommends[$key] = $value;
      //     }
      // }

      // Log::debug($formDataObject);


      // return $formDataObject;

      // dd($request);
      $tokuisaki_id = $request->tokuisaki_id;

      $all_zaikokanri = $request->all_zaikokanri;
      $all_hidden_price = $request->all_hidden_price;
      // $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommends = $request->buyerrecommend;

      if(isset($all_hidden_price)){
        $buyer_recommends = BuyerRecommend::where(['tokuisaki_id'=> $tokuisaki_id])->update(['hidden_price'=> 1]);
        return response()->json([
            'status' => 'reload',
        ]);
      }

      if(isset($all_zaikokanri)){
        $buyer_recommends = BuyerRecommend::where(['tokuisaki_id'=> $tokuisaki_id])->update(['zaikokanri'=> 1 , 'zaikosuu'=> null]);
        return response()->json([
            'status' => 'reload',
        ]);
      }



      // オーダー順を取得して並び替える
      foreach($buyerrecommends as  $key => $value) {

        if(isset($value['price'])){
            $buyerrecommend = BuyerRecommend::firstOrNew(['id'=> $key]);
            // $buyerrecommend->price = $value['price'];
            // $buyerrecommend->start = $value['start'];
            // $buyerrecommend->end = Carbon::parse($value['end'])->setTime(17, 0, 0);
            // $buyerrecommend->nouhin_end = $value['nouhin_end'];
            $buyerrecommend->order_no = $value['order_no'];
            // $buyerrecommend->uwagaki_item_name = $value['uwagaki_item_name'];
            // $buyerrecommend->uwagaki_kikaku = $value['uwagaki_kikaku'];
            // $buyerrecommend->gentei_store = $value['gentei_store'];
            // if(isset($value['hidden_price'])){
            //   $buyerrecommend->hidden_price = 1;
            // }else{
            //   $buyerrecommend->hidden_price = null;
            // }
            // if(isset($value['zaikokanri'])){
            //   $buyerrecommend->zaikokanri = 1;
            // }else{
            //   $buyerrecommend->zaikokanri = null;
            // }
            // if(isset($value['zaikosuu'])){
            //   $buyerrecommend->zaikosuu = $value['zaikosuu'];
            // }else{
            //   $buyerrecommend->zaikosuu = null;
            // }
            $group = null; // グループ名を初期化
            $currentOrderNo = $value['order_no']; // 現在のorder_noを取得
            $minDifference = PHP_INT_MAX; // 最小の差分を初期化
            $closestOrderNo = null; // 最も近いorder_noを初期化


            foreach ($buyerrecommends as $innerKey => $innerValue) {
                // priceが設定されていない場合かつ現在のorder_noより小さい場合に処理を実行
                // dd($innerValue['order_no']);
                if (!isset($innerValue['price']) && $innerValue['order_no'] < $currentOrderNo) {
                    $difference = $currentOrderNo - $innerValue['order_no'];
                    // 最小の差分を更新する場合、最も近いorder_noとグループ名を更新
                    if ($difference < $minDifference) {
                        $minDifference = $difference;
                        $closestOrderNo = $innerValue['order_no'];
                        $group = $innerKey;
                    }
                }
            }
            $buyerrecommend->groupe = $group;
            $buyerrecommend->save();
        }
      }
      return;
      // return redirect()->route('buyerrecommend', $id);
    }

    public function buyerrecommend_change_sort(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $data = $request->data;


      // Ajaxリクエストのデータを受け取る
      $formData = $request->all();

      // 受け取ったデータの処理
      // 例えば、グループごとにデータを処理する場合:
      foreach ($formData as $groupName => $data) {
          $orderNoArray = $data['order_no'];
          $priceArray = $data['price'];

          // データの使用例
          // ここで必要な処理を行います
      }


      // $sequentialNumbersMap = $request->input('sequentialNumbersMap');
      Log::debug($formData);
      return $data;


      // $buyerrecommends = BuyerRecommend::where(['tokuisaki_id' => $tokuisaki_id, 'groupe' => $old_groupe_name])->update(['groupe' => $groupe_name]);

      $id = $tokuisaki_id;

      $data=[
        'id'=>$id,
      ];
      return;
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


    public function buyerrecommend_change_groupe_name(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $old_groupe_name = $request->old_groupe_name;
      $groupe_name = $request->groupe_name;

      $buyerrecommends = BuyerRecommend::where(['tokuisaki_id' => $tokuisaki_id, 'groupe' => $old_groupe_name])->update(['groupe' => $groupe_name]);

      $id = $tokuisaki_id;

      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_uwagaki_item_name(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $uwagaki_item_name = $request->uwagaki_item_name;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['uwagaki_item_name' => $uwagaki_item_name]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_uwagaki_kikaku(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $uwagaki_kikaku = $request->uwagaki_kikaku;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['uwagaki_kikaku' => $uwagaki_kikaku]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_price(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $price = $request->price;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['price' => $price]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_start(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $start = $request->start;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['start' => $start]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_end(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $end = $request->end;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['end' => $end]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_nouhin_end(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $nouhin_end = $request->nouhin_end;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['nouhin_end' => $nouhin_end]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_gentei_store(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $gentei_store = $request->gentei_store;
      if($gentei_store == 'on') {
        $gentei_store = 1;
      }
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['gentei_store' => $gentei_store]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_hidden_price(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $hidden_price = $request->hidden_price;
      if($hidden_price == 'on') {
        $hidden_price = 1;
      }
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['hidden_price' => $hidden_price]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_zaikokanri(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $zaikokanri = $request->zaikokanri;
      if($zaikokanri == 'on') {
        $zaikokanri = null;
      }
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['zaikokanri' => $zaikokanri]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
    }

    public function buyerrecommend_change_zaikosuu(Request $request){
      $tokuisaki_id = $request->tokuisaki_id;
      $buyerrecommend_id = $request->buyerrecommend_id;
      $zaikosuu = $request->zaikosuu;
      $buyerrecommends = BuyerRecommend::where(['id' => $buyerrecommend_id])->update(['zaikosuu' => $zaikosuu]);
      $id = $tokuisaki_id;
      $data=[
        'id'=>$id,
      ];
      return;
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
    $text = 'この度、ヤマトクレジットファイナンス株式会社の審査の結果、が利用可能となりました。<br />
    ユーザー登録時にご登録いただいたメールアドレスとパスワードにて、下記URLよりご利用いただけます。<br />
    URL：<a href="'.$url.'">'.$url.'</a>';
    Mail::send('emails.register', [
        'name' => $name,
        'text' => $text,
    ], function ($message) use ($email ,$admin_mail) {
        $message->to($email)->bcc($admin_mail)->subject('SETOnagi審査通過のお知らせ');
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
    が利用不可となりましたことをお知らせいたします。';
    Mail::send('emails.register', [
        'name' => $name,
        'text' => $text,
    ], function ($message) use ($email ,$admin_mail) {
        $message->to($email)->bcc($admin_mail)->subject('SETOnagi利用停止のお知らせ');
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
        $message->to($email)->bcc($admin_mail)->subject('SETOnagi審査結果のお知らせ');
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
    $zei = '8%';

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
              $uketori_place = '';
              // 得意先コード
              $tokuisaki_id = $store->tokuisaki_id;
              // 得意先店舗コード
              $store_id = $store->store_id;
              // 得意先名
              $tokuisaki_name = $store->tokuisaki_name;
              // 得意先店舗名
              $store_name = $store->store_name;
              // 配送方法
              $haisou_houhou = $store->uketori_place;;
              // QR区分
              $qr = '';
            }else{
              // 会社名
              if($user->c_user()){
                $company = $setonagi_user->company_name;
              }else{
                $company = $setonagi_user->company;
              }
              // dd($company);
              // 会員No
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
              if($user->c_user()){
                $torihiki_shubetu = 4;
              }else{
                $torihiki_shubetu = 1;
              }
              // 引き渡し場所
              $uketori_place = null;
              // 得意先コード
              $tokuisaki_id = null;
              // 得意先店舗コード
              $store_id = null;
              // 得意先名
              $tokuisaki_name = null;
              // 得意先店舗名
              $store_name = null;
              // 配送方法
              if($user->c_user()){
                $haisou_houhou = $deal->uketori_place;
              }else{
                if($deal->uketori_place == '福山魚市引き取り'){
                  $haisou_houhou = '010';
                }elseif($deal->uketori_place == '三原引き取り（マリンネクスト）'){
                  $haisou_houhou = '020';
                }elseif($deal->uketori_place == '尾道引取り（ケンスイ）'){
                  $haisou_houhou = '030';
                }else{
                  $haisou_houhou = null;
                }
              }
              // QR区分
              if($user->c_user()){
                $qr = $setonagi_user->shipping_code;
              }else{
                $qr = '';
              }
            }
            if($deal->status == '発注済'){
              $deal_status = 1;
            }elseif($deal->status == '発注済'){
              $deal_status = 1;
            }elseif($deal->status == 'キャンセル'){
              $deal_status = '0';
            }else{
              $deal_status = $deal->status;
            }
            $array = [
              // 取引番号
              $deal->id,
              // カート番号
              $cart->id,
              // 注文日時
              $deal->success_time,
              // 会員No
              $kaiin_number,
              // Eメール
              $user->email,
              // 氏名
              $user->name,
              // 屋号_会社名
              $company,
              // フリガナ
              $user->name_kana,
              // 郵便番号
              $yuubin,
              // 国
              '',
              // 都道府県
              $jyuusho,
              // 市区郡町村
              '',
              // 番地
              '',
              // ビル名
              '',
              // 電話番号
              $tel,
              // FAX番号
              $fax,
              // 配送先氏名
              $store_name,
              // 配送先フリガナ
              '',
              // 配送先郵便番号
              $yuubin,
              // 配送先住所
              $jyuusho,
              // 配送先電話番号
              $tel,
              // 配送先FAX番号
              $fax,
              // 取引種別
              $torihiki_shubetu,
              // 発送日
              '',
              // 支払方法
              $pay,
              // 決済ID
              $deal->id,
              // 配送方法
              $haisou_houhou,
              // 配送希望日
              $order->nouhin_yoteibi,
              // 配送時間帯
              $deal->uketori_time,
              // 発送予定日
              '',
              // ステータス
              $deal_status,
              // 送り状番号
              '',
              // 総合計金額
              '',
              // 商品合計
              '',
              // 送料
              $deal->get_shipping_price(),
              // 代引手数料
              '',
              // 内消費税
              $zei,
              // 備考
              $deal->memo,
              // 注文行番号
              $order->id,
              // 商品コード
              $item->item_id,
              // SKUコード
              $item->sku_code,
              // 商品名
              $item->item_name,
              // SKU表示名
              $item->sku_code,
              // 商品オプション
              '',
              // 数量
              $order->quantity,
              // 単価
              $order->price,
              // 単位
              $item->tani,
              // 引渡場所
              $uketori_place,
              // 発注先企業
              $item->kigyou_code,
              // 発注先部署コード
              $item->busho_code,
              // 発注先部署名
              $item->busho_name,
              // 発注先当者者コード
              $item->tantou_code,
              // 発注先当者名
              $item->tantou_name,
              // 入荷日
              $item->nyuukabi,
              // 荷主コード
              '',
              // ロット番号
              $item->lot_bangou,
              // ロット行
              $item->lot_gyou,
              // ロット枝
              $item->lot_eda,
              // 倉庫コード
              $item->souko_code,
              // 値引率
              '',
              // 得意先コード
              $tokuisaki_id,
              // 得意先店舗コード
              $store_id,
              // 得意先名
              $tokuisaki_name,
              // 得意先店舗名
              $store_name,
              // QR区分
              $qr,
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
            // dd($store->tokuisaki_id);
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
            }elseif($cart_nini->tantou_name == '特殊'){
              $item_code = 101916009;
            }else{
              $item_code = '';
            }
            $pay = 'クラハシ払い';
            if($deal->status == '受注済'){
              $deal_status = 1;
            }elseif($deal->status == '発注済'){
              $deal_status = 1;
            }elseif($deal->status == 'キャンセル'){
              $deal_status = '0';
            }else{
              $deal_status = $deal->status;
            }
            // $item = Item::where(['id'=> $cart->item_id])->first();
            // dd($store);
            $array = [
              // 取引番号
              $deal->id,
              // カート番号
              $cart->id,
              // 注文日時
              $deal->success_time,
              // 会員No
              $user->kaiin_number,
              // Eメール
              $user->email,
              // 氏名
              $user->name,
              // 屋号_会社名
              $order_nini->tokuisaki_name,
              // フリガナ
              $user->name_kana,
              // 郵便番号
              $store->yuubin,
              // 国
              '',
              // 都道府県
              $store->jyuusho1,
              // 市区郡町村
              '',
              // 番地
              '',
              // ビル名
              '',
              // 電話番号
              $store->tel,
              // FAX番号
              $store->fax,
              // 配送先氏名
              $order_nini->store_name,
              // 配送先フリガナ
              '',
              // 配送先郵便番号
              $store->yuubin,
              // 配送先住所
              $store->jyuusho1.$store->jyuusho2,
              // 配送先電話番号
              $store->tel,
              // 配送先FAX番号
              $store->fax,
              // 取引種別
              $store->torihiki_shubetu,
              // 発送日
              '',
              // 支払方法
              $pay,
              // 決済ID
              '',
              // 配送方法
              '',
              // 配送希望日
              $order_nini->nouhin_yoteibi,
              // 配送時間帯
              '',
              // 発送予定日
              '',
              // ステータス
              $deal_status,
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
              $zei,
              // 備考
              $deal->memo,
              // 注文行番号
              $order_nini->id,
              // 商品コード
              $item_code,
              // SKUコード
              '',
              // 商品名
              $cart_nini->item_name,
              // SKU表示名
              '',
              // 商品オプション
              '',
              // 数量
              $order_nini->quantity,
              // 単価
              // $order_nini->price,
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
              '',
              // 得意先コード
              $store->tokuisaki_id,
              // 得意先店舗コード
              $store->store_id,
              // 得意先名
              $store->tokuisaki_name,
              // 得意先店舗名
              $store->store_name,
              // QR区分
              '',
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
              // $date('Y-m-d', strtotime($date));
              $weekday = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
              $weekday = $weekday[$date->dayOfWeek];
              // dd($weekday);
              $nouhin_youbi = explode(',', $repeatorder->nouhin_youbi);
              // dd($nouhin_youbi);
              // dd($weekday);
              // 曜日が含まれているか確認

              // dd($repeatorder->cart);
              $torihiki_date = $date->format('Ymd');
              $date = $date->format('Y-m-d');
              $repeatcart = $repeatorder->cart;
              $key = in_array($weekday, $nouhin_youbi);
              if($key == true){
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
                  $store_name = $store->store_name;
                }
                $array = [
                  // 取引番号
                  'r'.$repeatcart->id.'-'.$torihiki_date,
                  // カート番号
                  'r'.$repeatcart->id,
                  // 注文日時
                  $repeatorder->updated_at,
                  // 会員No
                  $user->kaiin_number,
                  // Eメール
                  $user->email,
                  // 氏名
                  $user->name,
                  // 屋号_会社名
                  $company,
                  // フリガナ
                  $user->name_kana,
                  // 郵便番号
                  $yuubin,
                  // 国
                  '',
                  // 都道府県
                  $jyuusho,
                  // 市区郡町村
                  '',
                  // 番地
                  '',
                  // ビル名
                  '',
                  // 電話番号
                  $tel,
                  // FAX番号
                  $fax,
                  // 配送先氏名
                  $store_name,
                  // 配送先フリガナ
                  '',
                  // 配送先郵便番号
                  $yuubin,
                  // 配送先住所
                  $jyuusho,
                  // 配送先電話番号
                  $tel,
                  // 配送先FAX番号
                  $fax,
                  // 取引種別
                  $torihiki_shubetu,
                  // 発送日
                  '',
                  // 支払方法
                  $pay,
                  // 決済ID
                  '',
                  // 配送方法
                  '',
                  // 配送希望日
                  $date,
                  // 配送時間帯
                  '',
                  // 発送予定日
                  '',
                  // ステータス
                  '1',
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
                  $zei,
                  // 備考
                  '',
                  // 注文行番号
                  $repeatorder->id,
                  // 商品コード
                  $item->item_id,
                  // SKUコード
                  $item->sku_code,
                  // 商品名
                  $item->item_name,
                  // SKU表示名
                  $item->sku_code,
                  // 商品オプション
                  '',
                  // 数量
                  $repeatorder->quantity,
                  // 単価
                  $repeatorder->price,
                  // 単位
                  $item->tani,
                  // 引渡場所
                  '',
                  // 発注先企業
                  $item->kigyou_code,
                  // 発注先部署コード
                  $item->busho_code,
                  // 発注先部署名
                  $item->busho_name,
                  // 発注先当者者コード
                  $item->tantou_code,
                  // 発注先当者名
                  $item->tantou_name,
                  // 入荷日
                  $item->nyuukabi,
                  // 荷主コード
                  '',
                  // ロット番号
                  $item->lot_bangou,
                  // ロット行
                  $item->lot_gyou,
                  // ロット枝
                  $item->lot_eda,
                  // 倉庫コード
                  $item->souko_code,
                  // 値引率
                  '',
                  // 得意先コード
                  $tokuisaki_id,
                  // 得意先店舗コード
                  $store_id,
                  // 得意先名
                  $tokuisaki_name,
                  // 得意先店舗名
                  $store_name,
                  // QR区分
                  '',
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
            $torihiki_date = $date->format('Ymd');
            $date = $date->format('Y-m-d');
            // dd($repeatorder->cart);
            $repeatcart = $repeatorder->cart;
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
                $store_name = $store->store_name;
                // QR区分
                $qr = '';
              }
              $array = [
                // 取引番号
                'r'.$repeatcart->id.'-'.$torihiki_date,
                // カート番号
                'r'.$repeatcart->id,
                // 注文日時
                $repeatorder->updated_at,
                // 会員No
                $user->kaiin_number,
                // Eメール
                $user->email,
                // 氏名
                $user->name,
                // 屋号_会社名
                $company,
                // フリガナ
                $user->name_kana,
                // 郵便番号
                $yuubin,
                // 国
                '',
                // 都道府県
                $jyuusho,
                // 市区郡町村
                '',
                // 番地
                '',
                // ビル名
                '',
                // 電話番号
                $tel,
                // FAX番号
                $fax,
                // 配送先氏名
                $store_name,
                // 配送先フリガナ
                '',
                // 配送先郵便番号
                $yuubin,
                // 配送先住所
                $jyuusho,
                // 配送先電話番号
                $tel,
                // 配送先FAX番号
                $fax,
                // 取引種別
                $torihiki_shubetu,
                // 発送日
                '',
                // 支払方法
                $pay,
                // 決済ID
                '',
                // 配送方法
                '',
                // 配送希望日
                $date,
                // 配送時間帯
                '',
                // 発送予定日
                '',
                // ステータス
                '1',
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
                $zei,
                // 備考
                '',
                // 注文行番号
                $repeatorder->id,
                // 商品コード
                $item->item_id,
                // SKUコード
                $item->sku_code,
                // 商品名
                $item->item_name,
                // SKU表示名
                $item->sku_code,
                // 商品オプション
                '',
                // 数量
                $repeatorder->quantity,
                // 単価
                $repeatorder->price,
                // 単位
                $item->tani,
                // 引渡場所
                '',
                // 発注先企業
                $item->kigyou_code,
                // 発注先部署コード
                $item->busho_code,
                // 発注先部署名
                $item->busho_name,
                // 発注先当者者コード
                $item->tantou_code,
                // 発注先当者名
                $item->tantou_name,
                // 入荷日
                $item->nyuukabi,
                // 荷主コード
                '',
                // ロット番号
                $item->lot_bangou,
                // ロット行
                $item->lot_gyou,
                // ロット枝
                $item->lot_eda,
                // 倉庫コード
                $item->souko_code,
                // 値引率
                '',
                // 得意先コード
                $tokuisaki_id,
                // 得意先店舗コード
                $store_id,
                // 得意先名
                $tokuisaki_name,
                // 得意先店舗名
                $store_name,
                // QR区分
                '',
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
    $writer->setDelimiter(',');
    $writer->setEnclosure('"');
    $writer->setUseBOM(true);
    $writer->save(public_path() . '/storage/excel/export.csv');
    $now = Carbon::now();
    return response()->download(public_path() . '/storage/excel/export.csv', 'orderbook'.$now.'.csv')->deleteFileAfterSend(true);

  }

}
