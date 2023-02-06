<?php

namespace App;

use App\Store;

use Illuminate\Database\Eloquent\Model;

class StoreUser extends Model
{
  protected $fillable = [
  'tokuisaki_id',
  'store_id',
  'user_id',
  ];

  public function store() {
    // dd($this->tokuisaki_id);
    $store = Store::where(['tokuisaki_id' => $this->tokuisaki_id])->first();
    if($store){
      return $store;
    }else{
      $store = null;
      return $store;
    }
  }

}
