<?php

namespace App\Imports;

use App\Store;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StoreImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Store([
          'kigyou_id'=>$row['所管企業コード'],
          'tokuisaki_id'=>$row['得意先コード'],
          'store_id'=>$row['得意先店舗コード'],
          'tokuisaki_name'=>$row['得意先名'],
          'store_name'=>$row['得意先店舗名'],
          'torihiki_shubetu'=>$row['取引種別'],
          'tantou_id'=>$row['営業担当者コード'],
          'yuubin'=>$row['郵便番号'],
          'jyuusho1'=>$row['住所１'],
          'jyuusho2'=>$row['住所２'],
          'tel'=>$row['電話番号'],
          'fax'=>$row['ＦＡＸ番号'],
          'haisou_yuubin'=>$row['配送先_郵便番号'],
          'haisou_jyuusho1'=>$row['配送先_住所１'],
          'haisou_jyuusho2'=>$row['配送先_住所２'],
          'haisou_tel'=>$row['配送先_電話番号'],
          'haisou_fax'=>$row['配送先_ＦＡＸ番号'],
          'haisou_route'=>$row['配送ルート'],
        ]);
    }
}
