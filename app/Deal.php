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
    if($first_cart){
      $first_order = Order::where(['cart_id'=>$first_cart->id])->first();
      $nouhin_yoteibi = $first_order->nouhin_yoteibi;
    }else{
      $nouhin_yoteibi = null;
    }
    return $nouhin_yoteibi;
  }




}
