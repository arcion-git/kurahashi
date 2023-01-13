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
      //リレーション

      $item = $this->belongsTo('App\Item', 'item_id')->first();
      $user = $this->belongsTo('App\User', 'user_id')->first();

      $kaiin_number = $user->kaiin_number;
      $store = StoreUser::where('user_id',$kaiin_number)->first();
      $tokuisaki_id = $store->tokuisaki_id;

      $buyer_recommend_item = BuyerRecommend::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'tokuisaki_id'=>$tokuisaki_id])->first();

      if(isset($buyer_recommend_item)){
      return $buyer_recommend_item->nouhin_end;
      }else{
        $store = Store::where(['tokuisaki_id'=>$tokuisaki_id,'store_id'=>$store->store_id])->first();
        $price_groupe = PriceGroupe::where(['tokuisaki_id'=>$tokuisaki_id,'store_id'=>$store->store_id])->first()->price_groupe;
        $special_price_item = SpecialPrice::where(['item_id'=>$item->item_id,'sku_code'=>$item->sku_code,'price_groupe'=>$price_groupe])->first();
        if(isset($special_price_item)){
          return $special_price_item->nouhin_end;
        }else{
          $nouhin_end = '+31d';
          return $nouhin_end;
        }
      }
  }

}
