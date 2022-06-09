<?php

namespace App\Imports;

use App\Setonagi;
// use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SetonagiImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function OnRow(Row $row)
    {
       $row=$row->toArray();
       $user=Setonagi::updateOrCreate(
            // キーカラム
            [   'user_id'=>$row['user_id']],
            // 上書き内容
            [
              'company'=>$row['company'],
              'company_kana'=>$row['company_kana'],
              'last_name'=>$row['last_name'],
              'first_name'=>$row['first_name'],
              'last_name_kana'=>$row['last_name_kana'],
              'first_name_kana'=>$row['first_name_kana'],
              'address01'=>$row['address01'],
              'address02'=>$row['address02'],
              'address03'=>$row['address03'],
              'address04'=>$row['address04'],
              'address05'=>$row['address05'],
              'kakebarai_riyou'=>$row['kakebarai_riyou'],
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
