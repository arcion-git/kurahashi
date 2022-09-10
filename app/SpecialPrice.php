<?php

namespace App;
use App\Favorite;
use App\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SpecialPrice extends Model
{
  protected $fillable = [
    'price_groupe',
    'item_id',
    'sku_code',
    'start',
    'end',
    'teika',
    'price',
  ];

  /**
   * 商品を取得
   */
  public function item()
  {
        // dd($this->sku_code);
    $item = $this->belongsTo('App\Item', 'item_id','item_id')
    ->where('sku_code', $this->sku_code)
    ->first();

    return $item;

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
