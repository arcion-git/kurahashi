<?php


use App\Cart;
use App\Deal;
use App\Item;
use App\Category;
use App\Tag;
use App\User;
use App\Holiday;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {



      $file = new SplFileObject('database/csv/CategoryMaster.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'category_id'=>$line[0],
              'busho_code'=>$line[1],
              'busho_name'=>$line[2],
              'ka_code'=>$line[3],
              'bu_ka_name'=>$line[4],
              'category_name'=>$line[5],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("categories")->insert($list);



      $file = new SplFileObject('database/csv/ProductCategory.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'category_id'=>$line[0],
              'item_id'=>$line[1],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("category_items")->insert($list);



      $file = new SplFileObject('database/csv/CustomerRep.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'kaiin_number'=> $line[0],
              'name'=> $line[1],
              'name_kana'=> $line[2],
              'tel'=> $line[3],
              'email'=> $line[4],
              'password'=> \Hash::make('secret') ,
              // 'first_login'=> $line[6],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("users")->insert($list);



      $file = new SplFileObject('database/csv/ProductItemDetail.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'item_id'=> $line[0],
              'sku_code'=> $line[1],
              'item_name'=> $line[2],
              'keiyaku'=> $line[3],
              'kikaku'=> $line[4],
              'ninushi_code'=> $line[5],
              'ninushi_name'=> $line[6],
              'sanchi_code'=> $line[7],
              'sanchi_name'=> $line[8],
              'tani'=> $line[9],
              'zaikosuu'=> $line[10],
              'kigyou_code'=> $line[11],
              'busho_code'=> $line[12],
              'busho_name'=> $line[13],
              'tantou_code'=> $line[14],
              'tantou_name'=> $line[15],
              'jan_code'=> $line[16],
              'nouhin_yoteibi_start'=> $line[17],
              'nouhin_yoteibi_end'=> $line[18],
              'nyuukabi'=> $line[19],
              'lot_bangou'=> $line[20],
              'lot_gyou'=> $line[21],
              'lot_eda'=> $line[22],
              'souko_code'=> $line[23],
              'tokkijikou'=> $line[24],
              'haisou_simekiri_jikan'=> $line[25],
              'haisou_nissuu'=> $line[26],
              'shoudan_umu'=> $line[27],
              'nebiki_umu'=> $line[28],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("items")->insert($list);




      $file = new SplFileObject('database/csv/Calendar.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'date'=> $line[0],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("holidays")->insert($list);




      $file = new SplFileObject('database/csv/CustomerStore.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'kigyou_id'=> $line[0],
              'tokuisaki_id'=> $line[1],
              'store_id'=> $line[2],
              'tokuisaki_name'=> $line[3],
              'store_name'=> $line[4],
              'torihiki_shubetu'=> $line[5],
              'tantou_id'=> $line[6],
              'yuubin'=> $line[7],
              'jyuusho1'=> $line[8],
              'jyuusho2'=> $line[9],
              'tel'=> $line[10],
              'fax'=> $line[11],
              'haisou_yuubin'=> $line[12],
              'haisou_jyuusho1'=> $line[13],
              'haisou_jyuusho2'=> $line[14],
              'haisou_tel'=> $line[15],
              'haisou_fax'=> $line[16],
              'haisou_route'=> $line[17],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("stores")->insert($list);





      $file = new SplFileObject('database/csv/CustomerResponsibleStore.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'user_id'=> $line[0],
              'tokuisaki_id'=> $line[1],
              'store_id'=> $line[2],
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("store_users")->insert($list);




      $file = new SplFileObject('database/csv/InCompanySales.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'tantou_id' => $line[0],
              'name' => $line[1],
              'name_kana' => $line[2],
              'tel' => $line[3],
              'shozoku_busho_id' => $line[4],
              'shozoku_busho_name' => $line[5],
              'kengen' => $line[6],
              'email'=> $line[7],
              'password' => \Hash::make($line[8]) ,
              "created_at" => $now,
              "updated_at" => $now,
            ];
          }
      }
      DB::table("admins")->insert($list);





      $file = new SplFileObject('database/csv/PriceGroupInfo.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'tokuisaki_id'=>$line[0],
              'store_id'=>$line[1],
              'kigyou_id'=>$line[2],
              'price_groupe'=>$line[3],
              'price_groupe_name'=>$line[4],
              'nebiki_ritsu'=>$line[5],
            ];
          }
      }
      DB::table("price_groupes")->insert($list);



      $file = new SplFileObject('database/csv/ListPriceInfo.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'price_groupe'=>$line[0],
              'item_id'=>$line[1],
              'sku_code'=>$line[2],
              'start'=>$line[3],
              'end'=>$line[4],
              'teika'=>$line[5],
              'price'=>$line[6],
            ];
          }
      }
      DB::table("prices")->insert($list);


      $file = new SplFileObject('database/csv/SpecialPriceInfo.csv');
      $file->setFlags(
          \SplFileObject::READ_CSV |
          \SplFileObject::READ_AHEAD |
          \SplFileObject::SKIP_EMPTY |
          \SplFileObject::DROP_NEW_LINE
      );
      $list = [];
      $now = Carbon::now();

      foreach ($file as $line) {
          if ($file->key() > 0 && ! $file->eof()) {
            $list[] = [
              'price_groupe'=>$line[0],
              'item_id'=>$line[1],
              'sku_code'=>$line[2],
              'start'=>$line[3],
              'end'=>$line[4],
              'teika'=>$line[5],
              'price'=>$line[6],
            ];
          }
      }
      DB::table("special_prices")->insert($list);





      DB::table("admins")->insert([
        'name'            => '??????????????????',
        'name_kana'           => '???????????????????????????',
        'email'               => 'admin@gmail.com' ,
        'password'            => \Hash::make('secret') ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);

      // \DB::table("categories")->insert([
      //   'category_name'        =>  '????????????',
      // ]);
      // \DB::table("categories")->insert([
      //   'category_name'        =>  '???????????????????????????',
      // ]);
      // \DB::table("categories")->insert([
      //   'category_name'        =>  '??????',
      // ]);
      // \DB::table("categories")->insert([
      //   'category_name'        =>  '?????????',
      // ]);
      // \DB::table("categories")->insert([
      //   'category_name'        =>  '??????????????????',
      // ]);
      // \DB::table("categories")->insert([
      //   'category_name'        =>  '??????',
      // ]);
      // \DB::table("categories")->insert([
      //   'category_name'        =>  '????????????',
      // ]);

for ($i = 1; $i <= 30; $i++) {

      \DB::table("users")->insert([
        'kaiin_number'           => $i,
        'name'           => '????????????'.$i,
        'name_kana'       => '??????????????????',
        'tel'                  => '08012345678',
        'email'                => 'sample'.$i.'@gmail.com' ,
        'password'             => \Hash::make('secret') ,
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
      ]);


      // \DB::table("items")->insert([
      //   'rank'                => 'A' ,
      //   'item_id'           => $i ,
      //   'sku_code'            => 'SKU?????????' ,
      //   'item_name'           => '?????????????????????'.$i ,
      //   'keiyaku'             => '????????????' ,
      //   'ninushi_code'        => '???????????????' ,
      //   'ninushi_name'        => '?????????' ,
      //   'sanchi_code'         => '???????????????' ,
      //   'sanchi_name'         => '?????????' ,
      //   'teika'               => '3000' ,
      //   'tanka'               => '5000' ,
      //   'tani'                => '??' ,
      //   'zaikosuu'            => '10' ,
      //   'kigyou_code'         => '????????????????????????' ,
      //   'busho_code'          => '????????????????????????' ,
      //   'busho_name'          => '??????????????????' ,
      //   'tantou_code'         => '???????????????????????????' ,
      //   'tantou_name'         => '?????????????????????' ,
      //   'kokyaku_item_id'   => '?????????????????????' ,
      //   'nouhin_yoteibi'      => '???????????????' ,
      //   'keisai_kigen'        => '2022/04/01' ,
      //   'nyuukabi'            => '?????????' ,
      //   'lot_bangou'          => '???????????????' ,
      //   'lot_gyou'            => '????????????' ,
      //   'lot_eda'             => '????????????' ,
      //   'souko_code'          => '???????????????' ,
      //   'tokkijikou'          => '????????????' ,
      //   'category_id'         =>  $i ,
      //   'yokujitsuhaisou_simekirijikan' => '????????????????????????' ,
      //   'created_at' => new DateTime(),
      //   'updated_at' => new DateTime(),
      // ]);

      // \DB::table("carts")->insert([
      //   'user_id'             => $i ,
      //   'deal_id'             => $i ,
      //   'item_id'             => $i ,
      //   'quantity'             => $i ,
      //   'created_at' => new DateTime(),
      //   'updated_at' => new DateTime(),
      // ]);

      // \DB::table("item_tag")->insert([
      //   'item_id'             => $i ,
      //   'tag_id'             => $i ,
      //   'created_at' => new DateTime(),
      //   'updated_at' => new DateTime(),
      // ]);
      //
      // \DB::table("category_item")->insert([
      //   'item_id'             => $i ,
      //   'category_id'             => $i ,
      //   'created_at' => new DateTime(),
      //   'updated_at' => new DateTime(),
      // ]);
      //
      // \DB::table("category_items")->insert([
      //   'item_id'             => $i ,
      //   'category_id'             => $i ,
      //   'created_at' => new DateTime(),
      //   'updated_at' => new DateTime(),
      // ]);

      // \DB::table("deals")->insert([
      //   'user_id'             => $i ,
      //   'created_at' => new DateTime(),
      //   'updated_at' => new DateTime(),
      // ]);

    }
    }
}
