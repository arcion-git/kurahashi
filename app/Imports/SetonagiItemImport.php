<?php

namespace App\Imports;

use App\SetonagiItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SetonagiItemImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SetonagiItem([
          'item_id'=>$row['商品コード'],
          'sku_code'=>$row['SKUコード'],
          'price'=>$row['価格'],
          'start_date'=>$row['掲載開始日'],
          'end_date'=>$row['掲載終了日'],
        ]);
    }
}
