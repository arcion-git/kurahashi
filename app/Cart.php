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
    $user = $this->belongsTo('App\User', 'user_id')->first();

    $kaiin_number = $user->kaiin_number;
    $now = Carbon::now();

    $stores = [];
    $tokuisaki_ids = StoreUser::where('user_id',$kaiin_number)->get();
    foreach ($tokuisaki_ids as $key => $value) {
      $buyer_recommend_item = BuyerRecommend::where('tokuisaki_id', $value->tokuisaki_id)
      ->where('price', '>=', '1')
      ->where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code])
      ->whereDate('start', '<=' , $now)
      ->whereDate('end', '>=', $now)
      ->orderBy('order_no', 'asc')->get();
      if(isset($buyer_recommend_item)){
        $stores_loop = Store::where(['tokuisaki_id'=>$buyer_recommend_item->tokuisaki_id,'store_id'=>$value->store_id])->first();
        $stores = collect($stores)->merge($stores_loop);
      }
    }
    if(isset($buyer_recommend_item)){
      return $stores;
    }
    foreach ($tokuisaki_ids as $key => $value) {
      $stores_loop = Store::where(['tokuisaki_id'=>$value->tokuisaki_id,'store_id'=>$value->store_id])->get();
      $stores = collect($stores)->merge($stores_loop);
    }
    return $stores;
  }
}
