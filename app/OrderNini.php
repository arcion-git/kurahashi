<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderNini extends Model
{
  protected $fillable = [
  'cart_nini_id',
  'tokuisaki_name',
  'store_name',
  'price',
  'nouhin_yoteibi',
  'quantity',
];
public function cart_nini() {
  return $this->belongsTo('App\CartNini');
}
}
