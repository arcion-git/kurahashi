<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
  protected $fillable = [
    'shipping_code',
    'shipping_method',
    'shipping_name',
    'ukewatasibi_nyuuryoku_umu',
    'ukewatasi_kiboujikan_umu',
    'calender_id',
    'shipping_price',
  ];
}
