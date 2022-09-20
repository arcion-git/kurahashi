<?php

namespace App\Imports;

use App\SpecialPrice;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SpecialPriceImport implements OnEachRow, WithHeadingRow
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
      $item=SpecialPrice::updateOrCreate(
           // キーカラム
           [
              'price_groupe'=>$row['価格グループコード'],
              'item_id'=>$row['商品コード'],
              'sku_code'=>$row['SKUコード']],
           // 上書き内容
           [
             'start'=>$row['掲載開始日'],
             'end'=>$row['掲載期限'],
             'teika'=>$row['定価'],
             'price'=>$row['単価'],
           ]
       );
    }
}
