<?php

namespace App\Imports;

use App\CategoryMaster;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoryMasterImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function model(array $row)
    {

        return new CategoryMaster([
          'category_id'=>$row['カテゴリID'],
          'busho_code'=>$row['部署コード'],
          'busho_name'=>$row['部署名'],
          'ka_code'=>$row['課コード'],
          'bu_ka_name'=>$row['部課名'],
          'category_name'=>$row['カテゴリ名'],
        ]);
    }

    public function rules(): array
    {
        return [
            'busho_code' => 'required',
        ];
    }

    public function customValidationMessages()
   {
    return [
        'busho_code.required' => '部署コードがありません',
    ];
   }

}
