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
    // dd($this);
    $store = Store::where(['tokuisaki_id' => $this->tokuisaki_id])->first();
    // $store = Store::where(['tokuisaki_id' => $this->tokuisaki_id , 'store_id' => $this->store_id])->first();
    return $store;
  }

  public function tokuisaki_name() {
    // dd($this);
    $tokuisaki_name = Store::where(['tokuisaki_id' => $this->tokuisaki_id])->first()->tokuisaki_name;
    // $store = Store::where(['tokuisaki_id' => $this->tokuisaki_id , 'store_id' => $this->store_id])->first();
    return $tokuisaki_name;
  }

  public function store_name() {
    // dd($this);
    $store_name = Store::where(['tokuisaki_id' => $this->tokuisaki_id])->first()->store_name;
    // $store = Store::where(['tokuisaki_id' => $this->tokuisaki_id , 'store_id' => $this->store_id])->first();
    return $store_name;
  }


}
