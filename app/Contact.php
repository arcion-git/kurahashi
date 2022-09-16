<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  protected $fillable = [
    'name',
    'address',
    'email',
    'tel',
    'shubetu',
    'naiyou',
  ];
}
