<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
          'kaiin_number'=>$row['会員No'],
          'name'=>$row['担当者名'],
          'name_kana'=>$row['担当者名カナ'],
          'tel'=>$row['担当者連絡先電話番号'],
          'password'=>'secret',
          'email'=>$row['担当者連絡先メールアドレス'],
        ]);
    }

    public function rules(): array
    {
        return [
            'kaiin_number' => 'required',
        ];
    }

    public function customValidationMessages()
   {
    return [
        'kaiin_number.required' => '会員Noがありません',
    ];
   }

}
