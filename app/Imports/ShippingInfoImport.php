<?php

namespace App\Imports;

use App\ShippingInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ShippingInfoImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ShippingInfo([
          'shipping_code'=>$row['配送コード'],
          'shipping_name'=>$row['配送名'],
          'keiyaku_company_hyouji'=>$row['契約会社表示有無'],
          'keiyaku_company_hissu'=>$row['契約会社必須'],
          'price_groupe'=>$row['価格グループ'],
        ]);
    }
}
