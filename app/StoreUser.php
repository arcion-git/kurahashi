<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreUser extends Model
{
  protected $fillable = [
  'tokuisaki_id',
  'store_id',
  'user_id',
  ];
}
