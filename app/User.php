<?php

namespace App;

use App\Store;
use App\StoreUser;

use App\Notifications\UserResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kaiin_number','name', 'name_kana', 'tel', 'email', 'password' , 'first_login','setonagi','kyuujitu_haisou',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPassword($token));
    }


    public function stores()
    {
        return $this->belongsToMany('App\Store','store_users','store_id','user_id');
    }

    public function setonagi()
    {
        $setonagi_user = Setonagi::where('user_id', $this->id)->first();
        return $setonagi_user;
    }

    // public function tokuisaki_name() {
    //     return $this->belongsTo('App\Store', 'tokuisaki_id')
    //     ->first()->tokuisaki_name;
    // }

    public function tokuisaki_name()
    {
      $kaiin_number = $this->kaiin_number;
      $tokuisaki = StoreUser::where('user_id',$kaiin_number)->first();
      if ($tokuisaki) {
      $tokuisaki_name = Store::where(['tokuisaki_id' => $tokuisaki->tokuisaki_id ,'store_id' => $tokuisaki->store_id])->first()->tokuisaki_name;
        return $tokuisaki_name;
      }
    }

    public function stop_repeatorder()
    {
      $kaiin_number = $this->kaiin_number;
      $repeatcarts = Repeatcart::where('kaiin_number',$kaiin_number)->get();
      // dd($repeatcarts);
      foreach ($repeatcarts as $repeatcart) {
        $cart_id = $repeatcart->id;
        $repeatorders = Repeatorder::where('id',$cart_id)->get();
              // dd($repeatorders);
        foreach ($repeatorders as $repeatorder) {
          if($repeatorder->stop_flg == 1){
            $stop_flg = $repeatorder->stop_flg;
            dd($stop_flg);
            return $stop_flg;
          }
        }
      }
      $repeatorder->stop_flg == 0 ;
      $stop_flg;
    }
}
