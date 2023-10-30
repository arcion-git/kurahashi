<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
  protected $fillable = [
    'user_id',
    'memo',
    'success_flg',
  ];
  public function user() {
      return $this->belongsTo('App\User');
  }

  public function first_cart_addtype() {
    $first_cart_addtype = Cart::where(['deal_id' => $this->id])->first();
    if ($first_cart_addtype) {
        $first_cart_addtype = $first_cart_addtype->addtype;
        if ($first_cart_addtype == 'addbuyerrecommend') {
            $addtype = '担当のおすすめ';
        } elseif ($first_cart_addtype == 'addsetonagi') {
            $addtype = '限定お買い得';
        } elseif ($first_cart_addtype == 'addspecialprice') {
            $addtype = '市況';
        } else {
            $addtype = null;
        }
    } else {
        // $first_cart_addtype が null の場合、エラーハンドリングを行うか、デフォルト値を設定するなどの対応が必要です。
        $addtype = null; // あるいはエラーメッセージを設定するなど
    }
    return $addtype;
  }

  public function first_order_nouhin_tokuisaki_name_and_store_name() {
    $first_cart = Cart::where(['deal_id'=>$this->id])->first();
    if($first_cart){
      $first_order = Order::where(['cart_id'=>$first_cart->id])->first();
      $nouhin_tokuisaki_store = $first_order->tokuisaki_name.' '.$first_order->store_name;
    }else{
      $nouhin_tokuisaki_store = null;
    }
    return $nouhin_tokuisaki_store;
  }

  public function first_order_nouhin_yoteibi() {
    $first_cart = Cart::where(['deal_id'=>$this->id])->first();
    // dd($first_cart);
    if($first_cart){
      $first_order = Order::where(['cart_id'=>$first_cart->id])->first();
      $nouhin_yoteibi = $first_order->nouhin_yoteibi;

      $shipping_method = $this->uketori_place;
      $setonagi = Setonagi::where(['user_id'=> $this->user_id])->first();

      if(isset($setonagi)){
        $shipping_setting = ShippingSetting::where(['shipping_method'=> $shipping_method ,'shipping_code'=> $setonagi->shipping_code ])->first();
        if(isset($shipping_setting))
          if($shipping_setting->ukewatasibi_nyuuryoku_umu == 0 && $shipping_setting->ukewatasi_kiboujikan_umu == 0){
          $nouhin_yoteibi = null;
        }
      }
    }else{
      $nouhin_yoteibi = null;
    }
    return $nouhin_yoteibi;
  }

  public function first_order_nouhin_yoteibi_cancel() {
    $first_cart = Cart::where(['deal_id'=>$this->id])->first();
    // dd($first_cart);
    if($first_cart){
      $first_order = Order::where(['cart_id'=>$first_cart->id])->first();
      $nouhin_yoteibi = $first_order->nouhin_yoteibi;
    }else{
      $nouhin_yoteibi = null;
    }
    return $nouhin_yoteibi;
  }



  public function c_uketori_houhou() {

    $setonagi = Setonagi::where(['user_id'=> $this->user_id])->first();
    $shipping_setting = ShippingSetting::where(['shipping_method' => $this->uketori_place , 'shipping_code' => $setonagi->shipping_code])->first();

    return $shipping_setting->shipping_name;
  }

}
