<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingCalender extends Model
{
  protected $fillable = [
    'calender_id',
    'date',
  ];
}
