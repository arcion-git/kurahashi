<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Repeatorder;
use App\User;
use App\Item;
use App\Store;
use App\Cart;
use App\Order;


// 時間に関する処理
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->call(function () {
        $now = Carbon::now();
        $repeatorders = Repeatorder::where('status','有効')->get();
        foreach ($repeatorders as $repeatorder) {
          if($repeatorder->startdate <= $nouhin_yoteibi){
            $date = new Carbon($nouhin_yoteibi);
            // 曜日を定義
            $weekday = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            $weekday = $weekday[$date->dayOfWeek];
            $nouhin_youbi = explode(',', $repeatorder->nouhin_youbi);
            // 曜日が含まれているか確認
            $date = $date->format('Y-m-d');
            $repeatcart = $repeatorder->cart;
            $key = in_array($weekday, $nouhin_youbi);
            // 納品予定日であれば出力
            if($key == true){
              $user = User::where('kaiin_number',$repeatcart->kaiin_number)->first();
              $item = Item::where(['item_id'=> $repeatcart->item_id, 'sku_code'=> $repeatcart->sku_code])->first();
              $store = Store::where(['tokuisaki_name'=> $repeatorder->tokuisaki_name , 'store_name'=> $repeatorder->store_name])->first();
              if(!($user->setonagi == 1)){
                // 会社名
                $company = $repeatorder->tokuisaki_name;
                // 会員No
                $kaiin_number = $user->kaiin_number;
                // 郵便番号
                $yuubin = $store->yuubin;
                // 住所
                $jyuusho = $store->jyuusho1;
                // 電話番号
                $tel = $store->tel;
                // FAX番号
                $fax = $store->fax;
                // 配送先氏名
                $store_name = $repeatorder->store_name;
                // 配送先郵便番号
                $yuubin = $repeatorder->yuubin;
                // 配送先住所
                $jyuusho = $store->jyuusho1.$store->jyuusho2;
                // 配送先電話番号
                $tel = $store->tel;
                // 配送先FAX番号
                $fax = $store->fax;
                // 支払方法
                $pay = 'クラハシ払い';
                // 取引種別
                $torihiki_shubetu = $store->torihiki_shubetu;
                // 得意先コード
                $tokuisaki_id = $store->tokuisaki_id;
                // 得意先店舗コード
                $store_id = $store->store_id;
                // 得意先名
                $tokuisaki_name = $store->tokuisaki_name;
                // 得意先店舗名
                $store_name = $store->store_name;
              }
              $array = [
                // 取引番号
                'r'.$repeatcart->id.'-'.$torihiki_date,
                // カート番号
                'r'.$repeatcart->id,
                // 注文日時
                $repeatorder->updated_at,
                // 会員No
                $user->kaiin_number,
                // Eメール
                $user->email,
                // 氏名
                $user->name,
                // 屋号_会社名
                $company,
                // フリガナ
                $user->name_kana,
                // 郵便番号
                $yuubin,
                // 国
                '',
                // 都道府県
                $jyuusho,
                // 市区郡町村
                '',
                // 番地
                '',
                // ビル名
                '',
                // 電話番号
                $tel,
                // FAX番号
                $fax,
                // 配送先氏名
                $store_name,
                // 配送先フリガナ
                '',
                // 配送先郵便番号
                $yuubin,
                // 配送先住所
                $jyuusho,
                // 配送先電話番号
                $tel,
                // 配送先FAX番号
                $fax,
                // 取引種別
                $torihiki_shubetu,
                // 発送日
                '',
                // 支払方法
                $pay,
                // 決済ID
                '',
                // 配送方法
                '',
                // 配送希望日
                $date,
                // 配送時間帯
                '',
                // 発送予定日
                '',
                // ステータス
                '1',
                // 送り状番号
                '',
                // 総合計金額
                '',
                // 商品合計
                '',
                // 送料
                '',
                // 代引手数料
                '',
                // 内消費税
                $zei,
                // 備考
                '',
                // 注文行番号
                $repeatorder->id,
                // 商品コード
                $item->item_id,
                // SKUコード
                $item->sku_code,
                // 商品名
                $item->item_name,
                // SKU表示名
                $item->sku_code,
                // 商品オプション
                '',
                // 数量
                $repeatorder->quantity,
                // 単価
                $repeatorder->price,
                // 単位
                $item->tani,
                // 引渡場所
                '',
                // 発注先企業
                $item->kigyou_code,
                // 発注先部署コード
                $item->busho_code,
                // 発注先部署名
                $item->busho_name,
                // 発注先当者者コード
                $item->tantou_code,
                // 発注先当者名
                $item->tantou_name,
                // 入荷日
                $item->nyuukabi,
                // 荷主コード
                '',
                // ロット番号
                $item->lot_bangou,
                // ロット行
                $item->lot_gyou,
                // ロット枝
                $item->lot_eda,
                // 倉庫コード
                $item->souko_code,
                // 値引率
                '',
                // 得意先コード
                $tokuisaki_id,
                // 得意先店舗コード
                $store_id,
                // 得意先名
                $tokuisaki_name,
                // 得意先店舗名
                $store_name,
              ];
              // dd($array);
              array_push($order_list, $array);
            }
          }
        }
      })->dailyAt('17:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
