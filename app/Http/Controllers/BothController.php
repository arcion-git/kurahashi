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
use App\BuyerRecommend;
use App\RecommendCategory;
use App\CartNini;
use App\OrderNini;
use App\Repeatcart;
use App\Repeatorder;
use App\Setonagi;
use App\SetonagiItem;

// デバッグを出力
use Log;
use Response;

// 時間に関する処理
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// BtoC向け
use App\ShippingCalender;
use App\ShippingCompanyCode;
use App\ShippingInfo;
use App\ShippingSetting;

// 配列をページネーションする
use Illuminate\Pagination\LengthAwarePaginator;

// API通信
use GuzzleHttp\Client;


class BothController extends Controller
{
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
    return redirect()->route('setonagi',$data);
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
    return $data;
  }

  // 個数を変更
  public function change_quantity(Request $request){
    $order_id = $request->order_id;
    $quantity = $request->quantity;


    // $order=Order::where(['id'=> $order_id])->first();
    // // 元々入っていた個数
    // $old_quantity = $order->quantity;
    // // 在庫数の増減量を計算
    // $change_quantity = $quantity - $old_quantity;
    // $cart=Cart::where(['id'=> $order->cart_id])->first();
    //
    //
    // $deal=Deal::where(['id'=> $cart->deal_id])->first();
    //
    //
    // if(isset($deal)){
    //   $setonagi=Setonagi::where(['user_id'=> $deal->user_id])->first();
    //   $addtype = $cart->addtype;
    //   if( $addtype == 'addsetonagi' || $addtype == 'addspecialprice' || $addtype == 'addbuyerrecommend' && isset($setonagi)){
    //     $item=Item::where(['id'=> $cart->item_id])->first();
    //     // 在庫数を増減させるロジック
    //     $item->zaikosuu  -= $change_quantity;
    //     // 在庫数をデータベースに更新
    //     $item->save();
    //   }elseif ( $addtype == 'addbuyerrecommend' ) {
    //
    //   }
    // }


    $order=Order::where(['id'=> $order_id])->update(['quantity'=> $quantity]);


    $data = "sucsess";
    return $data;
  }

  // 価格を変更
  public function change_price(Request $request){
    $order_id = $request->order_id;
    $price = $request->price;

    $order=Order::where(['id'=> $order_id])->update(['price'=> $price]);

    $data = "sucsess";
    return redirect()->route('setonagi',$data);
  }

  public function change_uketori_place(Request $request){

    $user_id = $request->user_id;
    $uketori_place = $request->uketori_place;
    $uketori_place=Setonagi::where(['user_id'=> $user_id])->update(['uketori_place'=> $uketori_place]);

    $data = "sucsess";
    return redirect()->route('setonagi',$data);
  }


  public function getDeliveryTimes(Request $request){
      // データベースからデータを取得
      $methodId = $request->methodId;
      $user_id = $request->user_id;
      $nouhin_yoteibi = $request->sano_nissuu;
      $setonagi = Setonagi::where(['user_id'=> $user_id])->first();
      $shipping_code = $setonagi->shipping_code;
      $shipping_setting = ShippingSetting::where(['shipping_code'=> $shipping_code,'shipping_method'=> $methodId])->first();

      $ukewatasibi_nyuuryoku_umu= $shipping_setting->ukewatasibi_nyuuryoku_umu;
      $ukewatasi_kiboujikan_umu = $shipping_setting->ukewatasi_kiboujikan_umu;
      $shipping_price = $shipping_setting->shipping_price;
      $calender_id= $shipping_setting->calender_id;

      if(isset($calender_id)){
        $holidays = ShippingCalender::where(['calender_id'=>$calender_id])->get('date');
      }else{
        $holidays = null;
      }

      if($shipping_setting->ukewatasi_kiboujikan_umu == 0 && $shipping_setting->ukewatasibi_nyuuryoku_umu == 0){
        $carts = Cart::where(['user_id'=>$user_id , 'deal_id'=>null, 'addtype'=>'addsetonagi'])->get();
        foreach ($carts as $cart) {
          // オーダー内容を保存
          $order = Order::where(['cart_id'=> $cart->id])->first();
          $order->nouhin_yoteibi = $nouhin_yoteibi;
          $order->save();
        }
      }

      return response()->json(['ukewatasibi_nyuuryoku_umu' => $ukewatasibi_nyuuryoku_umu, 'holidays' => $holidays , 'ukewatasi_kiboujikan_umu' => $ukewatasi_kiboujikan_umu , 'shipping_price' => $shipping_price]);
  }


  // 納品予定日を変更
  public function change_nouhin_yoteibi(Request $request){
    $order_id = $request->order_id;
    $nouhin_yoteibi = $request->nouhin_yoteibi;

    $order=Order::where(['id'=> $order_id])->update(['nouhin_yoteibi'=> $nouhin_yoteibi]);

    // 法人ユーザーのみ処理を分岐
    $order=Order::where(['id'=> $order_id])->first();
    $item_id=Cart::where(['id'=> $order->cart_id])->first()->item_id;
    $item=Item::where('id',$item_id)->first();

    $user_id = Cart::where(['id'=> $order->cart_id])->first()->user_id;
    $kaiin_number = User::where(['id'=> $user_id])->first()->kaiin_number;

    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');

    // Log::debug($tokuisaki_ids);
    // 得意先商品価格上書き

    if($tokuisaki_ids){
      foreach ($tokuisaki_ids as $key => $value) {
        // dd($value->tokuisaki_id);
        $buyerrecommend_item = BuyerRecommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'tokuisaki_id'=>$value->tokuisaki_id])->where('start', '<=' , $nouhin_yoteibi)->where('end', '>=', $nouhin_yoteibi)->first();
        if(isset($buyerrecommend_item)){
          $order->price = $buyerrecommend_item->price;
          $order->save();
        }
      }
    }

    $data = "sucsess";
    return redirect()->route('setonagi',$data);
  }





  // 任意の配送先店舗を変更
  public function nini_change_store(Request $request){
    $order_nini_id = $request->order_nini_id;
    $nini_store_name = $request->nini_store_name;
    $nini_tokuisaki_name = $request->nini_tokuisaki_name;
    $order_nini = OrderNini::where(['id'=> $order_nini_id])->update(['store_name'=> $nini_store_name,'tokuisaki_name'=> $nini_tokuisaki_name]);
    $data = "success";
    return redirect()->route('setonagi',$data);
  }



  // 任意の担当を保存
  public function nini_change_tantou(Request $request){
    $nini_tantou = $request->nini_tantou;
    $cart_nini_id = $request->cart_nini_id;
    $cart_nini_tantou= CartNini::where(['id'=> $cart_nini_id])->update(['tantou_name'=> $nini_tantou]);
    $data = "success";
    return $data;
  }

  // 任意の商品名を保存
  public function nini_change_item_name(Request $request){
    $nini_item_name = $request->nini_item_name;
    $cart_nini_id = $request->cart_nini_id;
    $cart_nini_item_name= CartNini::where(['id'=> $cart_nini_id])->update(['item_name'=> $nini_item_name]);
    $data = "success";
    return $data;
  }

  // 任意の価格を保存
  public function nini_change_price(Request $request){
    $nini_price = $request->nini_price;
    $order_nini_id = $request->order_nini_id;
    $order_nini_price = OrderNini::where(['id'=> $order_nini_id])->update(['price'=> $nini_price]);
    $data = "success";
    return $data;
  }

  // 任意の数量を保存
  public function nini_change_quantity(Request $request){
    $nini_quantity = $request->nini_quantity;
    $order_nini_id = $request->order_nini_id;
    $order_nini_quantity = OrderNini::where(['id'=> $order_nini_id])->update(['quantity'=> $nini_quantity]);
    $data = "success";
    return $data;
  }

  // 任意の納品予定日を保存
  public function nini_change_nouhin_yoteibi(Request $request){
    $nini_nouhin_yoteibi = $request->nini_nouhin_yoteibi;
    $order_nini_id = $request->order_nini_id;
    $order_nini_quantity = OrderNini::where(['id'=> $order_nini_id])->update(['nouhin_yoteibi'=> $nini_nouhin_yoteibi]);
    $data = "success";
    return redirect()->route('setonagi',$data);
  }

  // 配送先店舗を追加
  public function clonecart(Request $request){
    $cart_id = $request->cart_id;
    $order=Order::Create(['cart_id'=> $cart_id , 'tokuisaki_name'=>'' , 'nouhin_yoteibi'=> '', 'quantity'=> 1]);
    $data = "sucsess";
    return redirect()->route('setonagi',$data);
  }
  // 任意の配送先を追加
  public function addordernini(Request $request){
    $cart_nini_id = $request->cart_nini_id;
    $order=OrderNini::Create(['cart_nini_id'=> $cart_nini_id , 'quantity'=> 1]);
    $data = "sucsess";
    return redirect()->route('setonagi',$data);
  }






  // 全ての配送先店舗を変更
  public function change_all_store(Request $request){
    // $user_id = $request->user_id;
    // $addtype = $request->addtype;
    // $store_name = $request->store_name;
    // $tokuisaki_name = $request->tokuisaki_name;
    //
    // $kaiin_number = User::where(['id'=>$user_id])->first()->kaiin_number;
    // $store = Store::where(['tokuisaki_name'=>$tokuisaki_name,'store_name'=> $store_name])->first();
    // $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store->tokuisaki_id,'store_id'=> $store->store_id ])->first();
    //
    // $carts = Cart::where(['user_id' => $user_id , 'addtype' => $addtype , 'deal_id' => null])->get();
    //
    // foreach ($carts as $cart) {
    //
    //   // オーダー内容を保存
    //   $order = Order::where(['cart_id'=> $cart->id])->first();
    //   $order->store_name = $store_name;
    //   $order->tokuisaki_name = $tokuisaki_name;
    //   $order->save();
    //
    //   // オーダー内容を保存（ボツ）
    //   // $order = Order::where(['cart_id'=> $cart->id])
    //   // ->update([
    //   //     'store_name' => $store_name,
    //   //     'tokuisaki_name' => $tokuisaki_name
    //   // ]);
    //
    //   // 商品情報を取得
    //   $item = Item::where('id',$cart->item_id)->first();
    //
    //   // 市況商品価格上書き
    //   $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->$price_groupe])->first();
    //   if(isset($special_price_item->price)){
    //   $order->price = $special_price_item->price;
    //   }
    //
    //   // セトナギ商品上書き
    //   $setonagi_item = SetonagiItem::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
    //   if(isset($setonagi_item->price)){
    //     $order->price = $setonagi_item->price;
    //   }
    //
    //   $setonagi_user = User::where(['kaiin_number'=>$kaiin_number])->first()->setonagi;
    //   $now = Carbon::now();
    //   if(!$setonagi_user){
    //     $buyer_recommends = [];
    //     $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
    //     foreach ($tokuisaki_ids as $key => $value){
    //       // 得意先おすすめ商品の価格を探す
    //       $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
    //       ->where('price', '>=', '1')
    //       ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
    //       ->where('start', '<=' , $now)
    //       ->where('end', '>=', $now)
    //       ->orderBy('order_no', 'asc')->first();
    //       // dd($buyer_recommend_item);
    //       if(isset($buyer_recommend_item)){
    //         $order->price = $buyer_recommend_item->price;
    //       }
    //       // 市況商品の価格を探す
    //       $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
    //       $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
    //       ->where('start', '<=' , $now)
    //       ->where('end', '>=', $now)->first();
    //       if(isset($special_price_item)){
    //         $order->price = $special_price_item->price;
    //       }
    //     }
    //   }
    //   // 担当のおすすめ商品価格上書き
    //   $recommend_item = Recommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'user_id'=>$kaiin_number])->first();
    //   if(isset($recommend_item->price)){
    //   $order->price = $recommend_item->price;
    //   }
    //   $order->save();
    // }
    //
    // $cart_ninis = CartNini::where(['user_id' => $user_id , 'deal_id' => null])->get();
    // foreach ($cart_ninis as $cart_nini) {
    //   // オーダー内容を保存
    //   $ordernini = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->first();
    //   $ordernini->store_name = $store_name;
    //   $ordernini->tokuisaki_name = $tokuisaki_name;
    //   $ordernini->save();
    // }


    $data = "success";

    return;
  }


  // 配送先店舗を変更
  public function change_all_nouhin_yoteibi(Request $request){
    $user_id = $request->user_id;
    $addtype = $request->addtype;
    $nouhin_yoteibi = $request->nouhin_yoteibi;

    // Log::debug($nouhin_yoteibi);

    $kaiin_number = User::where(['id'=>$user_id])->first()->kaiin_number;

    $carts = Cart::where(['user_id' => $user_id , 'addtype' => $addtype , 'deal_id' => null])->get();

    foreach ($carts as $cart) {
      // オーダー内容を保存
      $order = Order::where(['cart_id'=> $cart->id])->first();
      $order->nouhin_yoteibi = $nouhin_yoteibi;
      $order->save();
    }

    $cart_ninis = CartNini::where(['user_id' => $user_id , 'deal_id' => null])->get();
    foreach ($cart_ninis as $cart_nini) {
      // オーダー内容を保存
      $ordernini = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->first();
      $ordernini->nouhin_yoteibi = $nouhin_yoteibi;
      $ordernini->save();
    }

    $data = "success";

    return redirect()->route('setonagi',$data);
  }

  // 配送先店舗を変更
  public function change_store(Request $request){
    $order_id = $request->order_id;
    $store_name = $request->store_name;
    $tokuisaki_name = $request->tokuisaki_name;
    $kaiin_number = $request->kaiin_number;

    $store = Store::where(['tokuisaki_name'=>$tokuisaki_name,'store_name'=> $store_name])->first();

    $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store->tokuisaki_id,'store_id'=> $store->store_id ])->first();

    // Log::debug($price_groupe);
    // return Response::json($price_groupe);

    $order = Order::where(['id'=> $order_id])->first();
    $cart_id = $order->cart_id;
    $cart = Cart::where(['id'=> $cart_id])->first();
    $item = Item::where('id',$cart->item_id)->first();

    // 価格グループを取得
    // $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id, 'sku_code'=> $item->sku_code])->first();

    // 価格の上書きを行う
    $order = Order::where(['id'=> $order_id])->first();
    // Log::debug($order->store_name);
    $order->store_name = $store_name;
    $order->tokuisaki_name = $tokuisaki_name;
    $order->save();



    // 市況商品価格上書き
    $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->$price_groupe])->first();
    if(isset($special_price_item->price)){
    $order->price = $special_price_item->price;
    }

    // セトナギ商品上書き
    $setonagi_item = SetonagiItem::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])->first();
    if(isset($setonagi_item->price)){
      $order->price = $setonagi_item->price;
    }

    $setonagi_user = User::where(['kaiin_number'=>$kaiin_number])->first()->setonagi;
    $now = Carbon::now();
    if(!$setonagi_user){
      $buyer_recommends = [];
      $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
      foreach ($tokuisaki_ids as $key => $value){
        // 担当のおすすめ商品の価格を探す
        $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
        ->where('price', '>=', '1')
        ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
        ->where('start', '<=' , $now)
        ->where('end', '>=', $now)
        ->orderBy('order_no', 'asc')->first();
        // dd($buyer_recommend_item);
        if(isset($buyer_recommend_item)){
          $order->price = $buyer_recommend_item->price;
        }
        // 市況商品の価格を探す
        $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
        $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
        ->where('start', '<=' , $now)
        ->where('end', '>=', $now)->first();
        if(isset($special_price_item)){
          $order->price = $special_price_item->price;
        }
      }
    }

    // 担当のおすすめ商品価格上書き
    $recommend_item = Recommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'user_id'=>$kaiin_number])->first();
    if(isset($recommend_item->price)){
    $order->price = $recommend_item->price;
    }

    $order->save();

    $data = "success";

    return redirect()->route('setonagi',$data);
  }

  // 全店舗に追加
  public function add_all_store(Request $request){
    $order_id = $request->order_id;
    $kaiin_number = $request->kaiin_number;

    $order = Order::where(['id'=> $order_id])->first();
    $cart_id = $order->cart_id;
    $cart = Cart::where(['id'=> $cart_id])->first();

    $item = Item::where('id',$cart->item_id)->first();

    // 保存中の店舗を削除
    $order_delete = Order::where('cart_id', $cart_id)->delete();

    // 店舗を抽出して保存
    // $kaiin_number = Auth::guard('user')->user()->kaiin_number;

    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get();
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);

    foreach ($store_users as $store_user) {
      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();

      $price_groupe = PriceGroupe::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();

      // $price = Price::where(['price_groupe'=>$price_groupe->price_groupe, 'item_id'=> $item->item_id , 'sku_code'=> $item->sku_code])->first();
      //
      // $price = $price->price;


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


      // 得意先商品価格上書き
      // $kaiin_number = Auth::guard('user')->user()->kaiin_number;
      // $store = StoreUser::where('user_id',$kaiin_number)->first();

      $now = Carbon::now();
      if($tokuisaki_ids){
        foreach ($tokuisaki_ids as $key => $value) {
          // dd($value->tokuisaki_id);
          $buyerrecommend_item = BuyerRecommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'tokuisaki_id'=>$value->tokuisaki_id])->where('start', '<=' , $now)->where('end', '>=', $now)->first();
          if(isset($buyerrecommend_item)){
            $price = $buyerrecommend_item->price;
          }
        }
      }

      // $now = Carbon::now();
      // $buyerrecommend_item = BuyerRecommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'tokuisaki_id'=>$store->tokuisaki_id])->where('start', '<=' , $now)->where('end', '>=', $now)->first();
      // if(isset($buyerrecommend_item->price)){
      //   $price = $buyerrecommend_item->price;
      // }

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

    return redirect()->route('setonagi',$data);
  }

  // 任意の商品の配送先を全店舗に追加
  public function nini_add_all_store(Request $request){
    $order_nini_id = $request->order_nini_id;
    $kaiin_number = $request->kaiin_number;

    $order_nini = Ordernini::where(['id'=> $order_nini_id])->first();
    // 保存中の店舗を削除
    $order_nini_delete = Ordernini::where('cart_nini_id', $order_nini->cart_nini_id)->delete();

    // 店舗を抽出して保存
    // $kaiin_number = Auth::guard('user')->user()->kaiin_number;
    $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);

    // Log::debug($store_users);

    foreach ($store_users as $store_user) {
      $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
      $order_nini=Ordernini::create(['cart_nini_id'=> $order_nini->cart_nini_id , 'tokuisaki_name'=> $store->tokuisaki_name , 'store_name'=> $store->store_name , 'quantity'=> 1]);
    }
    $data = "success";

    return redirect()->route('setonagi',$data);
  }

  // 任意の商品を追加
  public function addniniorder(Request $request){

    $kaiin_number = $request->kaiin_number;

    $deal_id = $request->deal_id;

    $addtype = $request->addtype;


    $nouhin_yoteibi = $request->nouhin_yoteibi;
    $user_id = User::where(['kaiin_number'=> $kaiin_number])->first()->id;

    // 納品先の店舗を取得
    if(!isset($request->deal_id)){
      $carts = Cart::where(['user_id'=> $user_id,'addtype'=> $addtype,'deal_id'=> null])->get();
      foreach ($carts as $cart) {
        $order = Order::where(['cart_id'=> $cart->id])->first();
        if(isset($order)){
          break;
        }
      }
      // if(isset($order)){
      //   $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
      //   foreach ($cart_ninis as $cart_nini) {
      //     $order_ninis = OrderNini::where(['cart_nini_id'=> $cart_nini->id])->get();
      //     foreach ($order_ninis as $order_nini) {
      //       $order_nini->tokuisaki_name = $order->tokuisaki_name;
      //       $order_nini->store_name = $order->store_name;
      //       $order_nini->nouhin_yoteibi = $order->nouhin_yoteibi;
      //       $order_nini->save();
      //     }
      //   }
      // }
    }
    // Log::debug($cart);




    if(isset($request->deal_id)){
      $cart_nini=CartNini::Create(['user_id'=> $user_id , 'deal_id'=> $request->deal_id]);
      $order_nini=OrderNini::Create(['cart_nini_id'=> $cart_nini->id , 'deal_id'=> $request->deal_id , 'tokuisaki_name'=>'' , 'store_name'=> $store_name , 'nouhin_yoteibi'=> $nouhin_yoteibi, 'quantity'=> 1]);
    }else{
      $cart_nini=CartNini::Create(['user_id'=> $user_id]);
      // Log::debug($cart_nini);
      // return Response::json($cart_nini);
      $order_nini=OrderNini::Create(['cart_nini_id'=> $cart_nini->id ,'quantity'=> 1 , 'tokuisaki_name'=> $order->tokuisaki_name ,'store_name'=> $order->store_name ,'nouhin_yoteibi'=> $order->nouhin_yoteibi]);
    }
    $data = "sucsess";
    return $data;
  }


  public function dealdetail($id){

    $deal = Deal::where('id',$id)->first();

    if (Auth::guard('user')->check()) {
      $login_user_id = Auth::guard('user')->user()->id;
      if($deal->user_id  == $login_user_id ){

      }else{
        return redirect()->route('bulk');
      }
    }

    $user_id = $deal->user_id;
    $user = User::where('id',$user_id)->first();

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


    $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $id])->get();


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
    if (strtotime($success_jikan) < strtotime('17:00:00')) {
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
    $zenjitu19ji = date($nouhin_yoteibi.'17:00:00');
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


    // キャンセルができるか判定
    $user = User::where(['id'=>$deal->user_id])->first();
    $nouhin_yoteibi = null;
    if($user->setonagi == 1){
      $cart_id = Cart::where(['user_id'=>$deal->user_id, 'deal_id'=> $id])->first()->id;
      $nouhin_yoteibi = Order::where(['cart_id'=>$cart_id])->first()->nouhin_yoteibi;
      // 納品予定日のレンダリングを阻止する
    }



    $collect_tradercode = config('app.collect_tradercode');
    $collect_password = config('app.collect_password');
    $collect_touroku = config('app.collect_touroku');
    $collect_token = config('app.collect_token');


    $data=[
      // 'carts'=>$carts,
      'nouhin_yoteibi'=>$nouhin_yoteibi,
      'deal'=>$deal,
      'categories' => $categories,
      'favorite_categories' => $favorite_categories,
      'holidays' => $holidays,
      'user_id' => $user_id,
      'user' => $user,
      'setonagi' => $setonagi,
      'today_plus' => $today_plus,
      'deal_cancel_button' => $deal_cancel_button,
      'collect_tradercode' => $collect_tradercode,
      'collect_password' => $collect_password,
      'collect_touroku' => $collect_touroku,
      'collect_token' => $collect_token,
    ];
    return view('dealdetail', $data);
  }




    // 取引詳細を表示するためのもの
    public function dealorder(Request $request){

      $deal_id = $request->deal_id;
      $deal =  Deal::where(['id'=>$deal_id])->first();
      $user_id = $deal->user_id;

      $user = User::where('id',$user_id)->first();
      $setonagi = Setonagi::where('user_id',$user_id)->first();

      $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();


      foreach ($carts as $cart) {
        $set_order = Order::where(['cart_id'=>$cart->id])->first();
        if($set_order){
          break;
        }
      }
      $groupedItems = $carts->groupBy('groupe');

      // dd($groupedItems);
      // 取引IDが一致しているものを取得
      if(!$setonagi){
        $cart_ninis =  CartNini::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
      // dd($cart_ninis);
      }else{
        $cart_ninis = null;
      }

      // 休日についての処理
      $today = date("Y-m-d");
      $holidays = Holiday::pluck('date');

      // BtoBユーザーの場合、店舗一覧取得
      if(!isset($setonagi)){
        $kaiin_number = $user->kaiin_number;
        $store_users = StoreUser::where('user_id',$kaiin_number)->get(['store_id','tokuisaki_id']);
        $stores = [];
        $n=1;
        foreach ($store_users as $store_user) {
        $store = Store::where([ 'tokuisaki_id'=> $store_user->tokuisaki_id,'store_id'=> $store_user->store_id ])->first();
          array_push($stores, $store);
        $n++;
        }
      }else{
        $stores = null;
      }



      if(isset($request->url)){
        $url = $request->url;
      }else{
        $url = null;
      }


      // 納品予定日を取得
      $currentDate = Carbon::now();
      $oneMonthLater = $currentDate->addMonth();

      // 1ヶ月後をフォーマット指定
      $oneMonthLaterFormatted = $oneMonthLater->format('Y-m-d');
      $all_nouhin_end = $oneMonthLaterFormatted;



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
      // $sano_nissuu = '+'.((strtotime($nouhin_yoteibi) - strtotime($today)) / 86400).'d';

      $sano_nissuu = $nouhin_yoteibi;
      $url = 'deal';


      $collect = config('app.collect_password');
      $collect_tradercode = config('app.collect_tradercode');
      $collect_password = config('app.collect_password').'2';
      $collect_touroku = config('app.collect_touroku');
      $collect_token = config('app.collect_token');


      $data=
      ['deal' => $deal,
       'setonagi' => $setonagi,
       'carts' => $carts,
       'cart_ninis' => $cart_ninis,
       'stores' => $stores,
       'holidays' => $holidays,
       'url' => $url,
       'user' => $user,
       'today_plus' => $today_plus,
       'sano_nissuu' => $sano_nissuu,
       'set_order' => $set_order,
       'all_nouhin_end' => $all_nouhin_end,
       'groupedItems' => $groupedItems,
       'nouhin_yoteibi' => $nouhin_yoteibi,
       'collect_tradercode' => $collect_tradercode,
       'collect_password' => $collect_password,
       'collect' => $collect,
       'collect_touroku' => $collect_touroku,
       'collect_token' => $collect_token,
      ];

      // return view('order', $data)->with($message);
      return view('order', $data);
    }

    public function change_nouhin_yoteibi_c(Request $request){

      // データベースからデータを取得
      $nouhin_yoteibi_c = $request->nouhin_yoteibi_c;
      $deal_id = $request->deal_id;
      $user_id = $request->user_id;
      $setonagi = Setonagi::where(['user_id'=> $user_id])->first();

      if(isset($deal_id)){
        $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> $deal_id])->get();
      }else{
        $carts = Cart::where(['user_id'=>$user_id, 'deal_id'=> null])->get();
      }

      if($carts){
        foreach ($carts as $cart) {
          $order = Order::where(['cart_id'=>$cart->id])->first();
          $order->nouhin_yoteibi = $nouhin_yoteibi_c;
          $order->save();
        }
      }

      return $nouhin_yoteibi_c;

    }

}
