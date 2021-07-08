<?php

namespace App\Imports;

use App\PriceGroupe;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PriceGroupeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PriceGroupe([
          'tokuisaki_id'=>$row['得意先コード'],
          'store_id'=>$row['得意先店舗コード'],
          'kigyou_id'=>$row['所管企業コード'],
          'price_groupe'=>$row['価格グループコード'],
          'price_groupe_name'=>$row['価格グループ名'],
          'nebiki_ritsu'=>$row['値引率'],
        ]);
    }
}
