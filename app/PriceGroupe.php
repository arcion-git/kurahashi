<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceGroupe extends Model
{
  protected $fillable = [
    'tokuisaki_id',
    'store_id',
    'kigyou_id',
    'price_groupe',
    'price_groupe_name',
    'nebiki_ritsu',
  ];
}
