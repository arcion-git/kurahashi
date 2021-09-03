<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repeatorder extends Model
{
  protected $fillable = [
    'kaiin_number',
    'item_id',
    'sku_code',
    'store',
    'price',
    'status',
    'nouhin_youbi',
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
