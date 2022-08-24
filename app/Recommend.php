<?php

namespace App;
use App\Favorite;
use App\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Recommend extends Model
{
  protected $fillable = [
    'user_id',
    'item_id',
    'sku_code',
    'price',
    'end',
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

  public function favoriteitem()
  {

    $user_id = Auth::guard('user')->user()->id;
    // dd($this);
    $item = Item::where(['item_id' => $this->item_id , 'sku_code' => $this->sku_code])->first();
    $favorite_item = favorite::where(['item_id' => $item->id , 'user_id' => $user_id])->first();
    return $favorite_item;
  }

}
