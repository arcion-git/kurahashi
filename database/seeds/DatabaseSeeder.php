<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      \DB::table("users")->insert([
        'last_name'            => '山田',
        'first_name'           => '太郎',
        'last_name_kana'       => 'ヤマダ',
        'first_name_kana'      => 'タロウ',
        'company'              => '株式会社サンプル',
        'company_kana'         => 'カブシキガイシャサンプル',
        'address01'            => '7201234',
        'address02'            => '広島県',
        'address03'            => '福山市引野町',
        'address04'            => '1丁目1-1',
        'address05'            => '',
        'tel'                  => '08012345678',
        'email'                => 'sample1@gmail.com' ,
        'password'             => \Hash::make('secret') ,
      ]);

      DB::table("admins")->insert([
          'name'                => '管理者 太郎' ,
          'email'               => 'admin@gmail.com' ,
          'password'            => \Hash::make('secret') ,
      ]);

for ($i = 1; $i <= 10; $i++) {
      \DB::table("items")->insert([
        'rank'                => 'A' ,
        'item_code'           => $i ,
        'sku_code'            => 'SKUコード' ,
        'item_name'           => 'サンプル商品名'.$i ,
        'keiyaku'             => '契約区分' ,
        'ninushi_code'        => '荷主コード' ,
        'ninushi_name'        => '荷主名' ,
        'sanchi_code'         => '産地コード' ,
        'sanchi_name'         => '産地名' ,
        'teika'               => '3000' ,
        'tanka'               => '3000' ,
        'tani'                => '¥' ,
        'zaikosuu'            => '10' ,
        'kigyou_code'         => '発注先企業コード' ,
        'busho_code'          => '発注先部署コード' ,
        'busho_name'          => '発注先部署名' ,
        'tantou_code'         => '発注先担当者コード' ,
        'tantou_name'         => '発注先担当者名' ,
        'kokyaku_item_code'   => '顧客商品コード' ,
        'nouhin_yoteibi'      => '納品予定日' ,
        'keisai_kigen'        => '掲載期限' ,
        'nyuukabi'            => '入荷日' ,
        'lot_bangou'          => 'ロット番号' ,
        'lot_gyou'            => 'ロット行' ,
        'lot_eda'             => 'ロット枝' ,
        'souko_code'          => '倉庫コード' ,
        'tokkijikou'          => '特記事項' ,
        'yokujitsuhaisou_simekirijikan' => '翌日配送締切時間' ,
      ]);
    }
    }
}
