<?php

namespace App;
use App\Favorite;
use App\BuyerRecommend;
use App\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BuyerRecommend extends Model
{
  protected $fillable = [
    'tokuisaki_id',
    'item_id',
    'sku_code',
    'price',
    'end',
    'order_no',
  ];

  /**
   * カテゴリに所属する商品を取得
   */
  public function item()
  {
    return $this->belongsTo('App\Item', 'item_id','item_id')
    ->where('sku_code', $this->sku_code)
    ->first();
  }

  public function uwagaki_item_name()
  {
    if(isset($this->uwagaki_item_name)){
      return $this->uwagaki_item_name;
    }else{
      $item_name = Item::where(['item_id' => $this->item_id , 'sku_code' => $this->sku_code])->first()->item_name;
      return $item_name;
    }
  }

  public function uwagaki_kikaku()
  {
    if(isset($this->uwagaki_kikaku)){
      return $this->uwagaki_kikaku;
    }else{
      $kikaku = Item::where(['item_id' => $this->item_id , 'sku_code' => $this->sku_code])->first()->kikaku;
      return $kikaku;
    }
  }

  public function favoriteitem()
  {
    $user_id = Auth::guard('user')->user()->id;
    // dd($this);
    $item = Item::where(['item_id' => $this->item_id , 'sku_code' => $this->sku_code])->first();
    $favorite_item = favorite::where(['item_id' => $item->id , 'user_id' => $user_id])->first();
    return $favorite_item;
  }

}
