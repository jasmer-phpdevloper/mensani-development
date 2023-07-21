<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Therapist extends Authenticatable implements JWTSubject
{  
    use SoftDeletes;
    use HasFactory;
   
    protected $guard = 'api';
    protected $table = 'therapists';

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    } 

    public function getImageAttribute($image)
    {   
        if(!empty($image)){
            return  $image;  
        }else{
            return  'https://img.icons8.com/fluency/48/gender-neutral-user.png';
        }
       
        
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = Carbon::now('America/New_York');
            $model->updated_at = Carbon::now('America/New_York');
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now('America/New_York');
        });
    }

  

   
    
  
}
