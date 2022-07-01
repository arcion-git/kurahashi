<?php

namespace App\Imports;

use App\Item;
// use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function OnRow(Row $row)
    {
      $row=$row->toArray();
      $item=Item::updateOrCreate(
           // キーカラム
           [ 'item_id'=>$row['商品コード']],
           // 上書き内容
           [
             'sku_code'=>$row['SKUコード'],
             'item_name'=>$row['商品名'],
             'item_name_kana'=>$row['商品名ひらがな'],
             'keiyaku'=>$row['契約区分'],
             'kikaku'=>$row['規格１名'],
             'ninushi_code'=>$row['荷主コード'],
             'ninushi_name'=>$row['荷主名'],
             'sanchi_code'=>$row['産地コード'],
             'sanchi_name'=>$row['産地名'],
             'tani'=>$row['単位'],
             'zaikosuu'=>$row['在庫数'],
             'kigyou_code'=>$row['発注先企業コード'],
             'busho_code'=>$row['発注先部署コード'],
             'busho_name'=>$row['発注先部署名'],
             'tantou_code'=>$row['発注先担当者コード'],
             'tantou_name'=>$row['発注先担当者名'],
             'jan_code'=>$row['ＪＡＮコード'],
             'nouhin_yoteibi_start'=>$row['納品予定日_開始'],
             'nouhin_yoteibi_end'=>$row['納品予定日_終了'],
             'nyuukabi'=>$row['入荷日'],
             'lot_bangou'=>$row['ロット番号'],
             'lot_gyou'=>$row['ロット行'],
             'lot_eda'=>$row['ロット枝'],
             'souko_code'=>$row['倉庫コード'],
             'tokkijikou'=>$row['特記事項'],
             'haisou_simekiri_jikan'=>$row['配送締切時間'],
             'haisou_nissuu'=>$row['配送日数'],
             'shoudan_umu'=>$row['商談有無'],
             'nebiki_umu'=>$row['値引有無'],
           ]
       );
    }
}
