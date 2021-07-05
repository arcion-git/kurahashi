<?php

namespace App\Imports;

use App\StoreUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StoreUserImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StoreUser([
          'user_id'=>$row['会員No'],
          'tokuisaki_id'=>$row['得意先コード'],
          'store_id'=>$row['得意先店舗コード'],
        ]);
    }
}
