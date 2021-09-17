<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartNini extends Model
{
  protected $fillable = [
    'user_id',
    'deal_id',
    'item_name',
    'tantou_name',
  ];

  public function order_ninis() {
      //リレーション
      return $this->hasMany('App\OrderNini');
  }
}
