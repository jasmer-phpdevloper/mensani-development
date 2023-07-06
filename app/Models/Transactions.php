<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon;

class Transactions extends Authenticatable 
{  
   
    use HasFactory;
 
    protected $table = 'subscription_payments';

    public function athlete()
    {
     return $this->hasOne(Athlete::class,'id','athlete_id');
    }

    public function subscription()
    {
     return $this->hasOne(Subscription::class,'id','subscription_id');
    }
  
}
