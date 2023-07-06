<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon;

class Appointment extends Authenticatable 
{  
   
    use HasFactory;
 
    protected $table = 'appointments';

//    public function slot()
//    {
//     return $this->hasMany(SlotTime::class,'appointment_id','id');
//    }

   
   
    
  
}
