<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
   * リピートオーダーに所属する商品を取得
   */
  public function item()
  {
    return $this->belongsTo('App\Item', 'item_id','item_id')
    ->where('sku_code', $this->sku_code)
    ->first();
  }
}
