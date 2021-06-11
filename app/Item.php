<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $fillable = [
      'item_code',
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
      // 'keisai_kigen',
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

  public function category() {
      //リレーション
      return $this->hasMany('App\Category', 'category_id');
  }



  /**
   * CSVヘッダ項目の定義値があれば定義配列のkeyを返す
   *
   * @param string $header
   * @param string $encoding
   * @return string|null
   */
  public static function retrieveTestColumnsByValue(string $header ,string $encoding)
  {
      // CSVヘッダとテーブルのカラムを関連付けておく
      $list = [
          'content' => "内容",
          'memo'    => "備考",
      ];

      foreach ($list as $key => $value) {
          if ($header === mb_convert_encoding($value, $encoding)) {
              return $key;
          }
      }
      return null;
  }

}
