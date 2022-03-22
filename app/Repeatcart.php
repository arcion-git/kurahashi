<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repeatcart extends Model
{
  protected $fillable = [
    'item_id',
    'sku_code',
    'kaiin_number',
  ];
  /**
   * リピートオーダーに所属する商品を取得
   */
  public function item()
  {
    return $this->belongsTo('App\Item', 'item_id','item_id')
    ->where('sku_code', $this->sku_code)
    ->first();
  }
  public function orders() {
      //リレーション
      return $this->hasMany('App\Repeatorder','cart_id');
  }
}
