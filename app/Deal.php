<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
  protected $fillable = [
    'user_id',
    'success_flg',
  ];
  public function user() {
      return $this->belongsTo('App\User', 'user_id');
  }

}
