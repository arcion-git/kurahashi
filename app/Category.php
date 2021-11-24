<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = [
    'category_id','busho_code','busho_name','ka_code','bu_ka_name','category_name',
  ];

  /**
   * カテゴリに所属する商品を取得
   */
  public function items()
  {
    return $this->belongsToMany('App\Item','category_items','item_id','category_id');
    // belongsToMany('関係するモデル', '中間テーブルのテーブル名', '中間テーブル内で対応しているID名', '関係するモデルで対応しているID名');
  }


}
