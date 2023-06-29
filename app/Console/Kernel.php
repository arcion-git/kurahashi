<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Repeatorder;
use App\Repeatcart;
use App\User;
use App\Item;
use App\Store;
use App\Cart;
use App\Order;
use App\Holiday;
use App\Deal;


use Illuminate\Support\Facades\Mail;

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
        // 今の時間を取得
        $now = Carbon::now();
        // 次の営業日（納品予定日）を取得
        $today = date("Y-m-d");
        $holidays = Holiday::pluck('date');
        $currentTime = date('H:i:s');
        $holidays = Holiday::pluck('date')->toArray();
        for($i = 1; $i < 10; $i++){
          $today_plus = date('Y-m-d', strtotime($today.'+'.$i.'day'));
          // dd($today_plus2);
          $key = array_search($today_plus,(array)$holidays,true);
          if($key){
              // 休みでないので納品日を格納
          }else{
              // 休みなので次の日付を探す
              $nouhin_yoteibi = $today_plus;
              break;
          }
        }
        // 有効のリピートオーダーを取得
        $repeatorders = Repeatorder::where('status','有効')->get();
        foreach ($repeatorders as $repeatorder) {
          // 今の時間よりも開始時間が前であれば発火
          if($repeatorder->startdate <= $now){
            $date = new Carbon($nouhin_yoteibi);
            // 曜日を定義
            $weekday = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            $weekday = $weekday[$date->dayOfWeek];
            // カンマ区切りを配列に変換
            $nouhin_youbi = explode(',', $repeatorder->nouhin_youbi);
            $date = $date->format('Y-m-d');
            $repeatcart = $repeatorder->cart;
            // 配列に曜日が含まれているか確認
            $key = in_array($weekday, $nouhin_youbi);
            // 該当の曜日があれば出力
            if($key == true){
              $user = User::where('kaiin_number',$repeatcart->kaiin_number)->first();
              $item = Item::where(['item_id'=> $repeatcart->item_id, 'sku_code'=> $repeatcart->sku_code])->first();
              $store = Store::where(['tokuisaki_name'=> $repeatorder->tokuisaki_name , 'store_name'=> $repeatorder->store_name])->first();

              $deal = Deal::create(['user_id'=> $user->id]);
              $deal->status = 'リピートオーダー';
              $deal->success_time = Carbon::now();
              $deal->save();

              $cart = Cart::create(['user_id'=> $user->id , 'item_id'=> $item->id , 'deal_id'=> $deal->id]);
              $cart->addtype = 'addrepeatorder';
              $cart->save();

              $order = Order::create(['cart_id'=> $cart->id]);
              $order->tokuisaki_name = $store->tokuisaki_name;
              $order->store_name = $store->store_name;
              $order->nouhin_yoteibi = $nouhin_yoteibi;
              $order->price = $repeatorder->price;
              $order->quantity = $repeatorder->quantity;
              $order->save();
            }
          }
        }
        // ユーザーごとにメールを作成
        $repeatcarts = Repeatcart::get();
        foreach ($repeatcarts as $repeatcart) {
          // 注文完了メールを作成
          $user = User::where(['kaiin_number'=> $repeatcart->kaiin_number])->first();
          // dd($user);
          $name = $user->name;
          $email = $user->email;
          // $url = url('');
          $text = 'SETOnagiオーダーブックをご利用くださいまして誠にありがとうございます。<br />
          下記の通り、リピートオーダーでのご注文をお受けいたしましたのでご確認をお願いいたします。<br />
          <br />
          【ご注文内容】';
          $repeatcarts = Repeatcart::where(['kaiin_number'=> $user->kaiin_number])->get();
          $repeatorder_items = [];
          foreach ($repeatcarts as $repeatcart) {
            $repeatorders = Repeatorder::where(['cart_id' => $repeatcart->id , 'status' => '有効'])->get();
            // 上記ユーザーが発注した内容を取得
            foreach ($repeatorders as $repeatorder) {
              $repeatorder_item = null;
              // 今の時間よりも開始時間が前であれば発火
              if($repeatorder->startdate <= $now){
                $date = new Carbon($nouhin_yoteibi);
                // 曜日を定義
                $weekday = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                $weekday = $weekday[$date->dayOfWeek];
                // カンマ区切りを配列に変換
                $nouhin_youbi = explode(',', $repeatorder->nouhin_youbi);
                $date = $date->format('Y-m-d');
                $repeatcart = $repeatorder->cart;
                // 配列に曜日が含まれているか確認
                $key = in_array($weekday, $nouhin_youbi);
                // 該当の曜日が
                if($key == true){
                  $item = Item::where(['item_id'=> $repeatcart->item_id, 'sku_code'=> $repeatcart->sku_code])->first();

                  // 単位を取得
                  if ($item->tani == 1){
                  $tani = 'ｹｰｽ';
                  }
                  elseif ($item->tani == 2){
                  $tani = 'ﾎﾞｰﾙ';
                  }
                  elseif ($item->tani == 3){
                  $tani = 'ﾊﾞﾗ';
                  }
                  elseif ($item->tani == 4){
                  $tani = 'kg';
                  }

                  if($repeatorder->price == '未定'){
                    $item_price = '金額未定 ' ;
                  }elseif($repeatorder->price == '-'){
                    $item_price = '- ' ;
                  }else{
                    $item_price = number_format($order->price).'円 ';
                  }

                  $array =
                    '・'.
                    // 商品コード
                    // $item->item_id.
                    // SKUコード
                    // $item->sku_code.
                    // 商品名
                    $item->item_name.' × '.
                    // 数量
                    $repeatorder->quantity.
                    // 単位
                    $tani.' '.
                    // 単価
                    $item_price.' '.$repeatorder->tokuisaki_name.' '.$repeatorder->store_name.' '.$nouhin_yoteibi
                    // 配送希望日
                    // $order->nouhin_yoteibi.
                    // 受け取りå
                    // ''.
                    // 内消費税
                    // '10%'
                  ;

                  // $repeatorder_item =
                  // ['item_name' => $item->item_name,
                  //  'price' => $repeatorder->price,
                  //  'quantity' => $repeatorder->quantity,
                  //  'tani' => $tani,
                  //  'kikaku' => $item->kikaku,
                  //  'tokuisaki_name' => $repeatorder->tokuisaki_name,
                  //  'store_name'=> $repeatorder->store_name,
                  // ];
                  array_push($repeatorder_items, $array);
                }
              }
            }
          }
          // dd($repeatorder_items);
          if (!empty($repeatorder_items)) {
            $repeatorder_items = implode('<br>', $repeatorder_items);
            $admin_mail = config('app.admin_mail');
            Mail::send('emails.register', [
                'name' => $name,
                'user' => $user,
                'text' => $text,
                'order_list' => $repeatorder_items,
                'nouhin_yoteibi' => $nouhin_yoteibi,
            ], function ($message) use ($email , $admin_mail) {
                $message->to($email)->bcc($admin_mail)->subject('【リピートオーダー】SETOnagiオーダーブックご注文承りました。');
            });
          }
        }
      });
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
