<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
  protected $fillable = [
    'user_id',
    'item_id',
  ];

  public function item()
  {
    return $this->belongsTo('App\Item', 'item_id','id')
    ->first();
  }
}
