<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $fillable = [
      'category_id',
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
