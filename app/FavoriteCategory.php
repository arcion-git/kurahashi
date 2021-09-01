<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteCategory extends Model
{
  protected $fillable = [
    'category_id','user_id',
  ];

  // public function categories()
  // {
  //   return $this->hasOne('App\category');
  // }

  public function category()
  {
    return $this->belongsTo('App\Category','category_id','category_id');
  }

}
