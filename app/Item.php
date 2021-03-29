<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $fillable = [
      'category_id',
  ];

  public function category() {
      //リレーション
      return $this->hasMany('App\Category', 'category_id');
  }
}
