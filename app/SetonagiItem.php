<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetonagiItem extends Model
{
  protected $fillable = [
  'item_id',
  'sku_code',
  'img',
  'price',
  'start_date',
  'end_date',
  ];
  public function item()
  {
    // dd($this->sku_code);
    return $this->belongsTo('App\Item', 'item_id','item_id')
    ->where('sku_code', $this->sku_code)
    ->first();
  }

}
