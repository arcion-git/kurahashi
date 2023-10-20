<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingCompanyCode extends Model
{
  protected $fillable = [
    'company_code',
    'company_name',
    'shipping_code',
  ];
}
