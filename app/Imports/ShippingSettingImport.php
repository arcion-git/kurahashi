<?php

namespace App\Imports;

use App\ShippingSetting;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ShippingSettingImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ShippingSetting([
          'shipping_code'=>$row['配送コード'],
          'shipping_method'=>$row['配送方法'],
          'shipping_name'=>$row['配送名'],
          'ukewatasibi_nyuuryoku_umu'=>$row['受渡し日入力有無'],
          'ukewatasi_kiboujikan_umu'=>$row['受渡し希望時間有無'],
          'calender_id'=>$row['カレンダーID'],
          'shipping_price'=>$row['配送料'],
        ]);
    }
}
