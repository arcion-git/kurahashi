<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecommendCategory extends Model
{
  protected $fillable = [
    'category_id',
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
}
