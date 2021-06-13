<?php

namespace App\Imports;

use App\category_item;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoryItemImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new category_item([
          'item_id'=>$row['商品コード'],
          'category_id'=>$row['カテゴリID'],
        ]);
    }
}
