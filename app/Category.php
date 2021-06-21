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
    return $this->belongsToMany('App\Item','category_items','category_id','item_id');
  }


}
