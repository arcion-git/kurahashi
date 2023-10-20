<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingInfo extends Model
{
  protected $fillable = [
  'shipping_code',
  'shipping_name',
  'keiyaku_company_hyouji',
  'keiyaku_company_hissu',
  'price_groupe',
  ];
}
