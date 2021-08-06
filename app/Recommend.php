<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
  protected $fillable = [
    'user_id',
    'item_id',
    'sku_code',
    'price',
    'end',
  ];
}
