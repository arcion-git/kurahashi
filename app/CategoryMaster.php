<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryMaster extends Model
{
  protected $fillable = [
    'category_id','busho_code','busho_name','ka_code','bu_ka_name','category_name',
  ];
}
