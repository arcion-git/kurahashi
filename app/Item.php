<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $fillable = [
      'item_id',
      'sku_code',
      'item_name',
      'keiyaku',
      'kikaku',
      'ninushi_code',
      'ninushi_name',
      'sanchi_code',
      'sanchi_name',
      'tani',
      'zaikosuu',
      'kigyou_code',
      'busho_code',
      'busho_name',
      'tantou_code',
      'tantou_name',
      'jan_code',
      'nouhin_yoteibi_start',
      'nouhin_yoteibi_end',
      'nyuukabi',
      'lot_bangou',
      'lot_gyou',
      'lot_eda',
      'souko_code',
      'tokkijikou',
      'haisou_simekiri_jikan',
      'haisou_nissuu',
      'shoudan_umu',
      'nebiki_umu',
  ];

  /**
   * 商品に所属するカテゴリーを取得
   */
  public function categories()
  {
      return $this->belongsToMany('App\Category','category_items','category_id','item_id');
  }

  public function tags()
  {
      return $this->belongsToMany('App\Tag','item_tag','tag_id','item_id');
  }

  // protected $primaryKey = 'item_id';

}
