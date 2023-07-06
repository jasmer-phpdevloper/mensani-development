<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{   
    protected $table = 'booking';
    use HasFactory;
    protected $appends = ['athlete_name'];
	
	public function Therapist()
   {
    return $this->hasOne(Therapist::class,'id','therapist_id');
   }

   public function Athlete()
   {
    return $this->hasOne(Athlete::class,'id','athlete_id');
   }

   public function getAthleteNameAttribute()
   { 
     $Athlete  = Athlete::where('id',$this->athlete_id)->first();
      if(!empty($Athlete)){

        return   $Athlete->name;

      }else{
        return "";
       }
      
   }

    
}
