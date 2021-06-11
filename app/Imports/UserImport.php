<?php

namespace App\Imports;

use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UserImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function OnRow(Row $row)
    {
       $row=$row->toArray();
       $user=User::updateOrCreate(
            // キーカラム
            [   'kaiin_number'=>$row['会員No']],
            // 上書き内容
            [
              'name'=>$row['担当者名'],
              'name_kana'=>$row['担当者名カナ'],
              'tel'=>$row['担当者連絡先電話番号'],
              'password'=>\Hash::make('secret') ,
              'email'=>$row['担当者連絡先メールアドレス'],
            ]
        );
    }



    // public function rules(): array
    // {
    //     return [
    //         'kaiin_number' => 'required',
    //     ];
    // }

   //  public function customValidationMessages()
   // {
   //  return [
   //      'kaiin_number.required' => '会員Noがありません',
   //      'name_kana.required' => '担当者名カナがありません',
   //  ];
   // }

}
