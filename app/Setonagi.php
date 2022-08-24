<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setonagi extends Model
{
  protected $fillable = [
  'user_id',
  'company',
  'company_kana',
  'last_name',
  'first_name',
  'last_name_kana',
  'first_name_kana',
  'address01',
  'address02',
  'address03',
  'address04',
  'address05',
  'kakebarai_sinsa',
  'kakebarai_riyou',
  'setonagi_ok',
  'kakebarai_limit',
  'kakebarai_update_time',
  'uketori_place',
  'uketori_time',
  'uketori_siharai',
  ];


}
