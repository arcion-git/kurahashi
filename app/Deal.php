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
    $first_cart_addtype = Cart::where(['deal_id'=>$this->id])->first()->addtype;
    if($first_cart_addtype == 'addbuyerrecommend'){
      $addtype = '担当のおすすめ';
    }elseif($first_cart_addtype == 'addsetonagi'){
      $addtype = '限定お買い得';
    }elseif($first_cart_addtype == 'addspecialprice'){
      $addtype = '市況';
    }
    return $addtype;
  }

  public function first_order_nouhin_tokuisaki_name_and_store_name() {
    $first_cart = Cart::where(['deal_id'=>$this->id])->first();
    $first_order = Order::where(['cart_id'=>$first_cart->id])->first();
    $nouhin_tokuisaki_store = $first_order->tokuisaki_name.' '.$first_order->store_name;
    return $nouhin_tokuisaki_store;
  }

  public function first_order_nouhin_yoteibi() {
    $first_cart = Cart::where(['deal_id'=>$this->id])->first();
    $first_order = Order::where(['cart_id'=>$first_cart->id])->first();
    $nouhin_yoteibi = $first_order->nouhin_yoteibi;
    return $nouhin_yoteibi;
  }




}
