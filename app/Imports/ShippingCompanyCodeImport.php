<?php

namespace App\Imports;

use App\ShippingCompanyCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ShippingCompanyCodeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ShippingCompanyCode([
          'company_code'=>$row['契約会社コード'],
          'company_name'=>$row['契約会社名'],
          'shipping_code'=>$row['配送コード'],
        ]);
    }
}
