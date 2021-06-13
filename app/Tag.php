<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  protected $fillable = [
      'tag_name',
      'tag_id',
  ];

  public function items()
  {
    return $this->belongsToMany('App\item','item_tag','tag_id','item_id');
  }

}
