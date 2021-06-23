<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  protected $fillable = [
    'user_id',
    'deal_id',
    'discount',
    'item_id',
    'quantity',
  ];
  public function item() {
      //リレーション
      return $this->belongsTo('App\Item', 'item_id');
  }

  public function orders() {
      //リレーション
      return $this->hasMany('App\Order');
  }

}
