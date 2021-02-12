<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    protected $guarded = [];
    protected $table = "user_wallets";


    public  function  user(){
        return $this->belongsTo('App\User','user_id');
    }
}
