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


      DB::table("admins")->insert([
        'last_name'            => '管理者',
        'first_name'           => '太郎',
        'email'               => 'admin@gmail.com' ,
        'password'            => \Hash::make('secret') ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);

      \DB::table("categories")->insert([
        'category_name'        =>  'おすすめ',
      ]);
      \DB::table("categories")->insert([
        'category_name'        =>  'クラハシオリジナル',
      ]);
      \DB::table("categories")->insert([
        'category_name'        =>  '鮮魚',
      ]);
      \DB::table("categories")->insert([
        'category_name'        =>  '訳あり',
      ]);
      \DB::table("categories")->insert([
        'category_name'        =>  '塩蔵・塩干し',
      ]);
      \DB::table("categories")->insert([
        'category_name'        =>  '冷凍',
      ]);
      \DB::table("categories")->insert([
        'category_name'        =>  '冷凍加工',
      ]);

for ($i = 1; $i <= 10; $i++) {

      \DB::table("users")->insert([
        'kaiin_number'           => '0000'.$i,
        'name'           => '太郎'.$i,
        'name_kana'       => 'ヤマダ',
        'tel'                  => '08012345678',
        'email'                => 'sample'.$i.'@gmail.com' ,
        'password'             => \Hash::make('secret') ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);


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
        'tanka'               => '5000' ,
        'tani'                => '¥' ,
        'zaikosuu'            => '10' ,
        'kigyou_code'         => '発注先企業コード' ,
        'busho_code'          => '発注先部署コード' ,
        'busho_name'          => '発注先部署名' ,
        'tantou_code'         => '発注先担当者コード' ,
        'tantou_name'         => '発注先担当者名' ,
        'kokyaku_item_code'   => '顧客商品コード' ,
        'nouhin_yoteibi'      => '納品予定日' ,
        'keisai_kigen'        => '2022/04/01' ,
        'nyuukabi'            => '入荷日' ,
        'lot_bangou'          => 'ロット番号' ,
        'lot_gyou'            => 'ロット行' ,
        'lot_eda'             => 'ロット枝' ,
        'souko_code'          => '倉庫コード' ,
        'tokkijikou'          => '特記事項' ,
        'category_id'         =>  $i ,
        'yokujitsuhaisou_simekirijikan' => '翌日配送締切時間' ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);

      \DB::table("carts")->insert([
        'user_id'             => $i ,
        'deal_id'             => $i ,
        'item_id'             => $i ,
        'quantity'             => $i ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);

      \DB::table("deals")->insert([
        'user_id'             => $i ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);

    }
    }
}
