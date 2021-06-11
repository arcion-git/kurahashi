<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = [
    'id','category_id','item_code',
  ];

  public function items()
  {
    return $this->belongsToMany('App\Models\item');
  }

}
