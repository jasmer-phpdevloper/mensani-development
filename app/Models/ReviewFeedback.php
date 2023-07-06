<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon;

class ReviewFeedback extends Authenticatable 
{  
   
    use HasFactory;
 
    protected $table = 'review_feedback';

    protected $appends = ['duration'];

   
   public function Athlete()
   {
    return $this->hasOne(Athlete::class,'id','athlete_id');
   }

   public function  getDurationAttribute(){
      $time = Carbon\Carbon::now()->diff($this->created_at); 
      if($time->y > 0){
        $data = $time->y .' '."years ago";  
       }elseif($time->m > 0){
        $data= $time->m .' '."months ago";
       }else{
        $data = $time->d .' '."days ago";   
       }   
     return  $data ;
      
   }
   
    
  
}
