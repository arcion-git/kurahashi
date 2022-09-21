<?php

namespace App\Imports;

use App\Admin;
// use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AdminImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function OnRow(Row $row)
    {
       $row=$row->toArray();
       $admin=Admin::updateOrCreate(
            // キーカラム
            [
              'email'=>$row['メール']
            ],
            // 上書き内容
            [
              'tantou_id'=>$row['担当者コード'],
              'name'=>$row['氏名'],
              'name_kana'=>$row['フリガナ'],
              'tel'=>$row['電話番号'],
              'shozoku_busho_id'=>$row['所属部署コード'],
              'shozoku_busho_name'=>$row['所属部署名'],
              'kengen'=>$row['権限'],
              'password'=>\Hash::make($row['パスワード']) ,
            ]
        );
    }


}
