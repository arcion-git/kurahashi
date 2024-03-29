<?php

namespace App\Imports;

use Carbon\Carbon;
use App\BuyerRecommend;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BuyerRecommendImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function OnRow(Row $row)
    {
      $row=$row->toArray();
      // dd($row);

      $item=BuyerRecommend::updateOrCreate(
           // キーカラム
           [
              'tokuisaki_id'=>$row['得意先ID'],
              'item_id'=>$row['商品コード'],
              'sku_code'=>$row['SKUコード']
           ],
           // 上書き内容
           [
              'price'=>$row['価格'],
              'start'=>$row['掲載開始'],
              'end'=>$row['掲載期限'],
              'nouhin_end'=>$row['納品期限'],
              'order_no'=>$row['並び順'],
              'groupe'=>$row['グループ'],
              'hidden_price'=>$row['価格非表示'],
              'zaikokanri'=>$row['在庫管理しない'],
              'zaikosuu'=>$row['在庫数'],
              'uwagaki_item_name'=>$row['上書き商品名'],
              'uwagaki_kikaku'=>$row['上書き規格'],
              'gentei_store'=>$row['限定店舗'],
           ]
       );
       // dd($item);
    }
}
