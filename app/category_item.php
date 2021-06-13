<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category_item extends Model
{
  protected $fillable = [
    'category_id','item_id',
  ];
}
