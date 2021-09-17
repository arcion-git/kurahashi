<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderNini extends Model
{
  protected $fillable = [
  'cart_id',
  'item_name',
  'tantou_name',
  'tokuisaki_name',
  'store_name',
  'price',
  'nouhin_yoteibi',
  'quantity',
];
public function cart() {
  return $this->belongsTo('App\Cart');
}
}
