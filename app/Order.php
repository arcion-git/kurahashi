<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'cart_id',
    'store_id',
    'nouhin_yoteibi',
    'quantity',
  ];
  public function cart() {
    return $this->belongsTo('App\Cart');
  }
}
