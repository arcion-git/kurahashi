<?php

namespace App;

use App\Item;
use App\Category;
use App\CategoryItem;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = [
    'category_id','busho_code','busho_name','ka_code','bu_ka_name','category_name',
  ];

  /**
   * カテゴリに所属する商品を取得
   */

  // protected $primaryKey = 'category_id';

  public function items() {
    // $category_id = $this->category_id;
    // $CategoryItems = CategoryItem::where('category_id',$category_id)->get(['item_id']);
    // $items = [];
    //
    // $n=1;
    // foreach ($CategoryItems as $CategoryItem) {
    // $item = Item::where(['item_id'=> $CategoryItem->item_id])->first();
    // if(isset($item)){
    // array_push($items, $item);
    // }
    // $n++;
    // }
    //
    // return $items;
    $relation = $this->belongsToMany('App\Item','category_items','category_id','item_id','category_id','item_id');
    return $relation;
    // }else{
    // return $items;
    // }
    // belongsToMany('関係するモデル', '中間テーブルのテーブル名', '中間テーブル内で対応しているID名', '関係するモデルで対応しているID名');
  }


}
