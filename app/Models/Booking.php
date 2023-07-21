<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{   
    protected $table = 'booking';
    use HasFactory;
    protected $appends = ['athlete_name','therapist_name','therapist_image'];
	
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
   // dd($athlete_id);
     $Athlete  = Athlete::where('id',$this->athlete_id)->first();
      if(!empty($Athlete)){

        return   $Athlete->name;

      }else{
        return "";
       }
      
   }

   public function getTherapistNameAttribute()
   { 
   // dd($athlete_id);
     $therapist  = Therapist::where('id',$this->therapist_id)->first();
      if(!empty($therapist)){

        return   $therapist->name;

      }else{
        return "";
       }
      
   }

   public function getTherapistImageAttribute()
   { 
   // dd($athlete_id);
     $therapist  = Therapist::where('id',$this->therapist_id)->first();
      if(!empty($therapist)){

        return   $therapist->image;

      }else{
        return 'https://img.icons8.com/fluency/48/gender-neutral-user.png';
       }
      
   }

    
}
