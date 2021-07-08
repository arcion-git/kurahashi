<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialPrice extends Model
{
  protected $fillable = [
    'price_groupe',
    'item_id',
    'sku_code',
    'start',
    'end',
    'teika',
    'price',
  ];
}
