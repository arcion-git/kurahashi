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
use App\Holiday;
use App\Deal;



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
            // 該当の曜日が
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
        // 下記からメールを作成して送信
        // $repeatcarts = Repeatcart::where('status','有効')->get();
        // dd($repeatcarts);

      })->withoutOverlapping();
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
