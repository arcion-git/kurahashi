<?php

namespace App;


use App\PriceGroupe;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
  protected $fillable = [
  'kigyou_id',
  'tokuisaki_id',
  'store_id',
  'tokuisaki_name',
  'store_name',
  'torihiki_shubetu',
  'tantou_id',
  'yuubin',
  'jyuusho1',
  'jyuusho2',
  'tel',
  'fax',
  'haisou_yuubin',
  'haisou_jyuusho1',
  'haisou_jyuusho2',
  'haisou_tel',
  'haisou_fax',
  'haisou_route',
  ];

  public function price() {
      return PriceGroupe::where(['tokuisaki_id'=>$tokuisaki_id, 'store_id'=> $store_id])->first();
  }


}
