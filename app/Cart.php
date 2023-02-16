<?php

namespace App;

use App\Store;
use App\User;
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
    if($buyer_recommend_item){
    // dd(collect($stores));
    return collect($stores);
    }


    foreach ($tokuisaki_ids as $key => $value) {
      $stores_loop = Store::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->first();
      if(isset($stores_loop)){
        array_push($stores, $store);
        // $stores = collect($stores)->merge($stores_loop);
      }
    }
    // dd($stores);
    return collect($stores);
  }
}
