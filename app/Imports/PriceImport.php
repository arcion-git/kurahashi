<?php

namespace App\Imports;

use App\Price;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PriceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Price([
          'price_groupe'=>$row['価格グループコード'],
          'item_id'=>$row['商品コード'],
          'sku_code'=>$row['SKUコード'],
          'start'=>$row['掲載開始日'],
          'end'=>$row['掲載期限'],
          'teika'=>$row['定価'],
          'price'=>$row['単価'],
        ]);
    }
}
