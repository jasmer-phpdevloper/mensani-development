<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use stdClass;
use App\Models\Athlete;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Config;
use Carbon\Carbon;
use File;
use Hash;
use App\Models\Therapist;
use App\Models\ReviewFeedback;
use DateTime;
use  App\Models\Appointment;
use  App\Models\Booking;



class ApiTherapistController extends Controller
{
   
  
    public function view_support(Request $request)
    {
        $x = new stdClass();
       
       $therapist =  auth('api')->authenticate($request->bearerToken());
   
       if(!$therapist){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  Therapist::get();
    //    $data = DB::table('supports')->where('user_type','Therapist')->select('id','title','thumbnail','price','video','support_type','user_id')->orderby('id','desc')->get();
    //    foreach($data as $value){
    //     $count = DB::table('support_counts')->where('video_id',$value->id)->count();
    //     $value->count = $count; 
    //    }
     
       if($data)
        {
            return response()->json([
                'status'=>'1',
                'message'=>'success',
                'data'=>$data,
                
            ]); 
        }
        else{
            return response()->json([
                'status'=>'0',
                'message'=>'No data found',
                'data'=> [],
            ]);     
        }
       
    
    }

    public function profile(Request $request)
    {
        $x = new stdClass();
       
        $therapist =  auth('api')->authenticate($request->bearerToken());
   
       if(!$therapist){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }

       $data['therapist_profile'] =  Therapist::where('id',$request->therapist_id)->first();
	   $data['therapist_review'] =   ReviewFeedback::with('Athlete')->where('therapist_id',$request->therapist_id)->orderBy('id','desc')->get();

       if($data)
       {
           return response()->json([
               'status'=>'1',
               'message'=>'success',
               'data'=>$data,
               
           ]); 
       }
       else{
           return response()->json([
               'status'=>'0',
               'message'=>'No data found',
               'data'=> [],
           ]);     
       }

      
    }

    public function therapist_appointment_slot(Request $request)
    {
        $x = new stdClass();
       
        $therapist =  auth('api')->authenticate($request->bearerToken());
   
        if(!$therapist){
            return response()->json([
                'status'=>'2',
                'message'=>"Token is mismatch",
                'data'=>$x
            ]);       
        }

        $validator = Validator::make($request->all(), [            
            'therapist_id' => 'required',
            'date' => 'required',
          
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }

        $day = strtotime($request->date);
        $day = date('l', $day);

        //dd($day);

        $appointments =  DB::table('appointments')->where('day',$day)->WhereNotNull(['start_time','end_time'])->where('therapist_id',$request->therapist_id)->first();
        if (!empty($appointments)){
            $startTime1 = new \DateTime($appointments->start_time);
            $endTime1 = new \DateTime($appointments->end_time);

            $timeIn24HourFormatStartTime = $startTime1->format('H:i');
            $timeIn24HourFormatEndTime = $endTime1->format('H:i');
           // DB::table('slot_time')->where('appointment_id',$appointments->id)->delete();
            $slots = ApiTherapistController::getTimeSlot(60,$startTime1->format('H:i'),$endTime1->format('H:i'),$appointments->therapist_id,$appointments->id,$request->date);
           
            $data = Appointment::where('day',$day)->where('therapist_id',$request->therapist_id)->first();
            $data['time_slot'] =  $slots;
            return response()->json([
                'status'=>'1',
                'message'=>'slot time',
                'data'=> $data,
            ]);   

        }else{
            return response()->json([
                'status'=>'0',
                'message'=>'Therapist has not created slots for this date yet',
                'data'=> [],
            ]); 


        }

       
      
    }

    function getTimeSlot($interval, $start_time, $end_time,$therapist_id,$appointments_id,$date)
   {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        $i=0;
        $time = [];
        while(strtotime($startTime) <= strtotime($endTime)){
            $start = $startTime;
            $end = date('H:i',strtotime('+'.$interval.' minutes',strtotime($startTime)));
            $startTime = date('H:i',strtotime('+'.$interval.' minutes',strtotime($startTime)));
            $i++;
            if(strtotime($startTime) <= strtotime($endTime)){
                $time[$i]['slot_start_time'] = $start;
                $time[$i]['slot_end_time'] = $end;
                $time[$i]['therapist_id'] = $therapist_id;
                $time[$i]['appointments_id'] = $appointments_id;
                 
                $booking = DB::table('booking')
                ->where('therapist_id',$therapist_id)
                //->where('appointment_id',$appointments_id)
                ->where('start_time',$start)
                ->where('end_time',$end)
                ->whereDate('date', $date)
                ->first();
               // dd($appointments_id);
                if(!empty($booking)){

                    $time[$i]['booking'] = 1;

                 }else{
                
                    $time[$i]['booking'] = 0;

                 }




            }
        }
        return $time;
   }


   public function booking(Request $request)
   {  
       $x = new stdClass();
        $validator = Validator::make($request->all(), [            
                'athlete_id' => 'required'
            
            ]);
        
        if ($validator->fails()) { 
        
            return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
        }
    
    
        $x = new stdClass();
        
        $therapist =  auth('api')->authenticate($request->bearerToken());

        if(!$therapist){
            return response()->json([
                'status'=>'2',
                'message'=>"Token is mismatch",
                'data'=>$x
            ]);       
        }


        $data['booking'] = Booking::with('Therapist')
        ->where('athlete_id',$request->athlete_id)
        ->get();
        $data['athletes'] = DB::table('athletes')
        ->where('id',$request->athlete_id)
        ->first();

        return response()->json([
            'status'=>'1',
            'message'=>'BOOKING',
            'data'=> $data,
        ]);  
      
   }
    
    public function therapist_support_video(Request $request)
    {

        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
                'therapist_id' => 'required'
            
            ]);
        
        if ($validator->fails()) { 
        
            return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
        }
    
    
        $x = new stdClass();
        
        $therapist =  auth('api')->authenticate($request->bearerToken());

        if(!$therapist){
            return response()->json([
                'status'=>'2',
                'message'=>"Token is mismatch",
                'data'=>$x
            ]);       
        }


        $data = DB::table('supports')->select('id','title','thumbnail','price','video','support_type')->where('user_id',$request->therapist_id)->orderby('id','desc')->get();
        foreach($data as $value){
         $count = DB::table('support_counts')->where('video_id',$value->id)->count();
         $value->count = $count; 
        }

        return response()->json([
            'status'=>'1',
            'message'=>'Therapist video',
            'data'=> $data,
        ]);

    }
  
      

   

}
