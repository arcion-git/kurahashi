<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = [
    'id','busho_code','ka_code','item_code','category_name',
  ];
}
