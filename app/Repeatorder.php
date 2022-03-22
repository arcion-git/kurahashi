<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repeatorder extends Model
{
  protected $fillable = [
    'cart_id',
    'tokuisaki_name',
    'store_name',
    'nouhin_youbi',
    'quantity',
    'nouhin_youbi',
    'price',
  ];

  public function cart() {
    return $this->belongsTo('App\Repeatcart');
  }
}
