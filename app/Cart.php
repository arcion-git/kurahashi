<?php

namespace App;

use App\Store;
use App\User;
use App\Item;
use App\Order;
use App\StoreUser;
use App\BuyerRecommend;
use App\SpecialPrice;
use App\PriceGroupe;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// 時間に関する処理
use Carbon\Carbon;

class Cart extends Model
{
  protected $fillable = [
    'user_id',
    'deal_id',
    'item_id',
    'quantity',
  ];
  public function item() {
      //リレーション
      return $this->belongsTo('App\Item', 'item_id');
  }

  public function orders() {
      //リレーション
      return $this->hasMany('App\Order');
  }

  public function favoriteitem() {

    $favorite_item = Favorite::where(['item_id' => $this->item_id , 'user_id' => $this->user_id])->first();
    return $favorite_item;
  }

  public function hidden_price() {
    $item = $this->belongsTo('App\Item', 'item_id')->first();
    $user = $this->belongsTo('App\User', 'user_id')->first();

    $kaiin_number = $user->kaiin_number;
    $now = Carbon::now();

    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');

    foreach ($tokuisaki_ids as $key => $value) {
      // 担当のおすすめ商品の納品期日を探す
      $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
      ->where('price', '>=', '1')
      ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
      ->whereDate('start', '<=' , $now)
      ->whereDate('end', '>=', $now)
      ->first();
      if(isset($buyer_recommend_item)){
        return $buyer_recommend_item->hidden_price;
      }
    }
    $buyer_recommend_item = null;
    return $buyer_recommend_item;

  }

  public function nouhin_end() {
    $item = $this->belongsTo('App\Item', 'item_id')->first();
    $user = $this->belongsTo('App\User', 'user_id')->first();

    $kaiin_number = $user->kaiin_number;
    $now = Carbon::now();

    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');

    foreach ($tokuisaki_ids as $key => $value) {
      // 担当のおすすめ商品の納品期日を探す
      $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
      ->where('price', '>=', '1')
      ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
      ->whereDate('start', '<=' , $now)
      ->whereDate('end', '>=', $now)
      ->orderBy('order_no', 'asc')->first();
      // dd($buyer_recommend_item);
      if(isset($buyer_recommend_item)){
        return $buyer_recommend_item->nouhin_end;
      }
      // 市況商品の納品期日を探す
      $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
      $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
      ->whereDate('start', '<=' , $now)
      ->whereDate('end', '>=', $now)->first();
      if(isset($special_price_item)){
        return $special_price_item->nouhin_end;
      }else{
        $nouhin_end = '+31d';
        return $nouhin_end;
      }
    }
  }
  public function stores() {
    $item = $this->belongsTo('App\Item', 'item_id')->first();
    // dd($item);
    $user = $this->belongsTo('App\User', 'user_id')->first();

    $kaiin_number = $user->kaiin_number;
    $now = Carbon::now();

    $stores=[];

    // ユーザーが持っている店舗を全て取得
    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get();
    // dd($tokuisaki_ids);


    // 通常注文の場合のみ
    // if(isset($item)){
      // ユーザーが持っている店舗の中で、得意先おすすめ商品が登録されている得意先店舗のみを抽出
      foreach ($tokuisaki_ids as $key => $value) {
        $store = Store::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
        // dd($store);
        if(isset($store)){
          // dd($store);
          $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $store->tokuisaki_id)
          ->where('price', '>=', '1')
          ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
          ->whereDate('start', '<=' , $now)
          ->whereDate('end', '>=', $now)->first();
          // dd($buyer_recommend_item);
          if(isset($buyer_recommend_item)){
            // $stores = collect($stores)->merge($store);
            array_push($stores, $store);
            // $stores = $stores->array_push($store);
          }
        }
      }
    // }

    // dd($buyer_recommend_item);
    if(!count($stores) == 0){
    $stores = array_unique($stores);

    return collect($stores);
    }

    $stores=[];
    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get();
    foreach ($tokuisaki_ids as $key => $value) {
      $stores_loop = Store::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
      if(isset($stores_loop)){
        array_push($stores, $stores_loop);
      }
    }
    $stores = array_unique($stores);
    return collect($stores);
  }

  public function zaikosuu() {
    $item = $this->belongsTo('App\Item', 'item_id')->first();
    $user = $this->belongsTo('App\User', 'user_id')->first();
    if(!$user->setonagi == 1){
      $kaiin_number = $user->kaiin_number;
      $now = Carbon::now();
      $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
      foreach ($tokuisaki_ids as $key => $value) {
        // 担当のおすすめ商品の在庫管理をしない場合を探す
        $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
        ->where('price', '>=', 1)
        ->where('zaikokanri', 1)
        ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
        ->whereDate('start', '<=' , $now)
        ->whereDate('end', '>=', $now)
        ->orderBy('order_no', 'asc')->first();
        if(isset($buyer_recommend_item)){
          return 99;
        }
        // 担当のおすすめ商品の在庫数を探す
        $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
        ->where('price', '>=', 1)
        ->whereNull('zaikokanri')
        ->where('zaikosuu', '>=', 1)
        ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
        ->whereDate('start', '<=' , $now)
        ->whereDate('end', '>=', $now)
        ->orderBy('order_no', 'asc')->first();
        if(isset($buyer_recommend_item)){
          return $buyer_recommend_item->zaikosuu;
        }
      }
    }
    $zaikosuu = $item->zaikosuu;
    // dd($zaikosuu);
    return $zaikosuu;
  }

  public function order_this() {
    // $order_this = Order::where(['cart_id'=>$this->id,'quantity'=>'0'])->first();
    $order_this = Order::where(['cart_id' => $this->id])->where(function ($query) {
    $query->where('quantity', '0')->orWhereNull('quantity');
    })->first();
    return $order_this;
  }

  public function special_price_nouhin(){
    $cart = Cart::where(['id'=>$this->id])->first();
    if($cart->addtype == 'addspecialprice'){
      $item = $this->belongsTo('App\Item', 'item_id')->first();
      $user = $this->belongsTo('App\User', 'user_id')->first();
      $kaiin_number = $user->kaiin_number;
      $now = Carbon::now();
      $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
      foreach ($tokuisaki_ids as $key => $value) {
        // 市況商品の納品期日を探す
        $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
        $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
        ->whereDate('start', '<=' , $now)
        ->whereDate('end', '>=', $now)->first();
        if(isset($special_price_item)){
          return true;
          break;
        }
      }
      if(!$buyerrecommend){
        return false;
      }
    }
  }


  public function order_store() {

    $cart = Cart::where(['id'=>$this->id])->first();
    // 納品する店舗
    $order = Order::where(['cart_id'=>$this->id])->first();

    // 商品
    $item = Item::where(['id'=>$this->item_id])->first();
    $kaiin_number = User::where(['id'=>$this->user_id])->first()->kaiin_number;
    $now = Carbon::now();
    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');

    // 担当のおすすめ商品の得意先コードを取得
    if($cart->addtype == 'addbuyerrecommend'){
      foreach ($tokuisaki_ids as $key => $value) {
        $buyerrecommend = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
        ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
        ->where('sku_code' , $item->sku_code)
        ->where('price', '>=', '1')
        ->whereDate('start', '<=' , $now)
        ->whereDate('end', '>=', $now)
        ->whereDate('nouhin_end', '>=', $order->nouhin_yoteibi)->first();
        if(isset($buyerrecommend)){
          break;
        }
      }
      if(!$buyerrecommend){
        return false;
      }

      $tokuisaki_id = $buyerrecommend->tokuisaki_id;

      $tokuisaki_name = $order->tokuisaki_name;
      $store_name = $order->store_name;

      $store = Store::where(['store_name'=>$store_name,'tokuisaki_name'=>$tokuisaki_name])->first();

      // $price_groupe =PriceGroupe::where(['store_id'=>$store->store_id,'tokuisaki_id'=>$store->tokuisaki_id])->first()->price_groupe;
      $store_tokuisaki_id = $store->tokuisaki_id;

      if($store_tokuisaki_id == $tokuisaki_id){
        return true;
        }else{
        return false;
      }
      
      }elseif($cart->addtype == 'addspecialprice'){
      $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get()->unique('tokuisaki_id');
      foreach ($tokuisaki_ids as $key => $value) {
        // 市況商品の納品期日を探す
        $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
        $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe->price_groupe])
        ->whereDate('start', '<=' , $now)
        ->whereDate('end', '>=', $now)->first();
        if(isset($special_price_item)){
          return true;
          break;
        }
      }
      if(!$special_price_item){
        return false;
      }
    }else{
      return true;
    }
  }


}
