<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'cart_id',
    'tokuisaki_name',
    'store_name',
    'nouhin_yoteibi',
    'quantity',
  ];
  public function cart() {
    return $this->belongsTo('App\Cart');
  }

}
