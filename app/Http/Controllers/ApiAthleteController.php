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

class ApiAthleteController extends Controller
{
    public function signup(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'email' => 'required|unique:athletes',
            'password' => 'required',
            'name' => 'required',
        
            
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
 
       $athlete['name'] = $request->name;  
       $athlete['email'] = $request->email;
       $athlete['password'] = bcrypt($request->password);  
       $athlete['fcm_token'] = $request->fcm_token;
       $athlete['device_type'] = $request->device_type;
       $athlete_id = DB::table('athletes')->insertgetId($athlete);
       $credentials = $request->only('email', 'password');
       $token = null;
       
       try {
           if (!$token = auth('api')->attempt($credentials)) {
             
               return response()->json([
                   'status'=>'0',
                   'message'=>"User not exists",
                   'data'=>$x
               ]);
           }
       } catch (JWTAuthException $e) {
           return response()->json([
               'status' => '0',
               'message' => 'failed to create token',
               'data'=>$x
           ]);
       }
      
       $data = DB::table('athletes')->select('id','name','email')->where('id',$athlete_id)->first();
       $data->token =  $token;
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
                'data'=> $x,
            ]);     
        }
    }
    public function login(Request $request){
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'email' => 'required',
            'password' => 'required',
            
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
         
        $email = $request->input('email');
      
        $credentials = $request->only('email', 'password');
        $data = DB::table('athletes')->select('id','name','email','status')->where('email',$email)->first();
        // $data->token = $token;
        if($data){
            DB::table('athletes')->where('id',$data->id)->update(['fcm_token'=>$request->fcm_token,'device_type'=>$request->device_type]);
        }
       
        $token = null;
       
        try {
            if (!$token = auth('api')->attempt($credentials)) {
              
                return response()->json([
                    'status'=>'0',
                    'message'=>"Invalid email or password",
                    'data'=>$x
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'status' => '0',
                'message' => 'failed to create token',
                'data'=>$x
            ]);
        }
        if($data->status == 1){
            if($data){
                $data->token = $token;
              return response()->json([
            
                  'status' => '1',            
                  'message' => 'Logged in successfully',
                  // 'token' => $token,
                  'data'=>$data,
             
          ]);
        
          }else{
              return response()->json([
                  'status'=>'0',
                  'message'=>"Logged in failed",
                  'data'=>$x
              ]);       
          }
        }else{
            return response()->json([
                'status'=>'0',
                'message'=>"Your account is not active",
                'data'=>$x
            ]);       
        }
      
       
    }
    public function logout() {
        $x = new stdClass();
        Auth::guard('api')->logout();
        return response()->json([
            'status'=>'1',
            'message' => 'Logged out successfully',
            'data'=>$x
          
        ]);
    
       
    }

    public function  delete_athlete_account(Request $request){
    //  $athlete =  auth('api')->authenticate($request->bearerToken());
    // //    dd( $athlete);
    //    if(!$athlete){
    //     return response()->json([
    //         'status'=>'2',
    //         'message'=>"Token is mismatch",
    //         'data'=>$x
    //     ]);       
    //    }

       $x = new stdClass();
       $validator = Validator::make($request->all(), [            
        'athlete_id' => 'required',
       
       ]);
    
    if ($validator->fails()) { 
       
         return response()->json([
            'status'=>'0',
            'message'=>$validator->errors()->first(),
            'data'=>$x
        ]);           
     }
       $data = DB::table('start_selftalks')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('start_goals')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('self_talks')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('post_performances')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('points')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('notifications')->where('athlete_id',$request->athlete_id)->delete();
    
       $data = DB::table('dreams_goals')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('booking')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('post_improvements')->where('athlete_id',$request->athlete_id)->delete();
       $data = DB::table('visualizations')->where('athlete_id',$request->athlete_id)->delete();
       
       $data = DB::table('athletes')->where('id',$request->athlete_id)->delete();

       return response()->json([
        'status'=>'1',
        'message' => 'Account delete successfully',
     
    ]);
    }









    public function edit_profile(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            // 'name' => 'required',
            // 'email' => 'required',            
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       if($request->name){
        $athletes['name'] = $request->name;
       }
       if($request->email){
        $athletes['email'] = $request->email;
       }
       if($request->hasfile('image'))
       {
           $file = $request->image;
           $path = storage_path().'/athleteimg/';
           File::makeDirectory($path, $mode = 0777, true, true);
           $imagePath = storage_path().'/athleteimg/';         
           $post_image        = time().$file->getClientOriginalName();
           $image_url          = url('/').'/storage/athleteimg/'.'/'. $post_image;   
           $file->move($imagePath, $post_image);
       }
       if($request->image){
       $athletes['image']= $image_url;
       }
       if($request->sports_name){
        $athletes['sports_name']= $request->sports_name;
      }
        
    //    dd($athletes);
       DB::table('athletes')->where('id',$athlete->id)->update($athletes);
       $data = DB::table('athletes')->select('id','name','email','image','sports_name')->where('id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function view_notification(Request $request)
    {
        $x = new stdClass();
   
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
     
       $data = DB::table('notifications')->select('id','title','description','created_at')->where('user_type','Athlete')->where('athlete_id',$athlete->id)->get();
     
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
    public function home_screen(Request $request)
    {
        $x = new stdClass();
    //  dd(Carbon::today());
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $myDate = Carbon::now()->dayOfWeek;    
       if($myDate == 1){
        $data = DB::table('questions')->select('id','question','answer')->take(1)->first();
       }elseif($myDate == 2){
        $data = DB::table('questions')->select('id','question','answer')->skip(1)->take(1)->first();
       }elseif($myDate == 3){
        $data = DB::table('questions')->select('id','question','answer')->skip(2)->take(1)->first();
       }elseif($myDate == 4){
        $data = DB::table('questions')->select('id','question','answer')->skip(3)->take(1)->first();
       }elseif($myDate == 5){
        $data = DB::table('questions')->select('id','question','answer')->skip(4)->take(1)->first();
       }elseif($myDate == 6){
        $data = DB::table('questions')->select('id','question','answer')->skip(5)->take(1)->first();
       }else{
        $data = DB::table('questions')->select('id','question','answer')->skip(6)->take(1)->first();
       }
     
       if($data){
       $data->daypoints = (int)DB::table('points')->where('athlete_id',$athlete->id)->whereDate('created_at', Carbon::today())->sum('point') ?? ''; 
       $data->weeklypoints = (int)DB::table('points')->where('athlete_id',$athlete->id)
                             ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('point'); 
       $data->monthlypoints = (int)DB::table('points')->where('athlete_id',$athlete->id)
                             ->whereMonth('created_at', date('m'))
                             ->whereYear('created_at', date('Y'))->sum('point');
        $self_counts = (int)DB::table('start_selftalks')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
        $visualizations_counts = DB::table('visualizations')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
        if($self_counts > 4){
            $data->selftalks_flag = 1;
        }else{
            $data->selftalks_flag = 0;
        }
        if($visualizations_counts > 4){
            $data->visualizations_flag = 1;
        }else{
            $data->visualizations_flag = 0;
        }
        $data->admin_points = DB::table('admin_points')->select('performace','start_goals','visualization','start_selftalks')->first();
        $time = DB::table('plans')->orderby('id','desc')->first();
        // dd( $time);
       if(Carbon::today() <= $time->to_date){
        $data->todayplans = DB::table('plans')->select('id','image','message_from','name','description','date')->orderby('id','desc')->first();
        $data->todayplans->time = $time->from_time.' to '.$time->to_time;
        $data->todayplans->validity = $time->from_date.' to '.$time->to_date;
       }else{
        //$data->todayplans = $x;
        $data->todayplans = DB::table('plans')->select('id','image','message_from','name','description','date')->orderby('id','desc')->first();
        $data->todayplans->time = $time->from_time.' to '.$time->to_time;
        $data->todayplans->validity = $time->from_date.' to '.$time->to_date;
       }        
       
       $data->user_profile = DB::table('athletes')->select('id','name','email',DB::raw("if(image!='',image,'') as image"),DB::raw("if(sports_name!='',sports_name,'') as sports_name"),DB::raw("if(subscription_id!='',subscription_id,'') as subscription_id"),'created_at')->where('id',$athlete->id)->first();
       $time = Carbon::now()->diff($data->user_profile->created_at); 
       if($time->y > 0){
        $data->user_profile->time = $time->y .' '."years ago";  
       }elseif($time->m > 0){
        $data->user_profile->time = $time->m .' '."months ago";
       }else{
        $data->user_profile->time = $time->d .' '."days ago";   
       } 
        
       $data->season_goal = DB::table('season_goals')->select('id','primary_goal','secondary_goal')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       DB::table('season_goals')->select('id','primary_goal','secondary_goal')->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       $data->dreams_goal = DB::table('dreams_goals')->select('id','dream_goal')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       $data->dreams_goal = DB::table('dreams_goals')->select('id','dream_goal')->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       $data->wellbeing = DB::table('wellbeings')->select('id','mood','thought')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       $data->wellbeing = DB::table('wellbeings')->select('id','mood','thought')->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       $data->self_talk = DB::table('self_talks')->select('id','role_model','image','challenge','recording','audio_name')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       $data->self_talk = DB::table('self_talks')->select('id','role_model','image','challenge','recording','audio_name')->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       $data->start_goal = DB::table('start_goals')->select('id','primary_goal','secondary_goal')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       $data->start_goal = DB::table('start_goals')->select('id',DB::raw("if(secondary_goal!='',secondary_goal,'') as secondary_goal"),DB::raw("if(primary_goal!='',primary_goal,'') as primary_goal"))->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       $data->post_performance = DB::table('post_performances')->select('id','performance')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       $data->post_performance = DB::table('post_performances')->select('id','performance')->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       $data->start_selftalk = DB::table('start_selftalks')->select('id','recording','audio_name')->where('athlete_id',$athlete->id)->orderby('id','desc')->exists() ?
       $data->start_selftalk = DB::table('start_selftalks')->select('id','recording','audio_name')->where('athlete_id',$athlete->id)->orderby('id','desc')->first() : $x;
       }
    //    else{
    //       $time = DB::table('plans')->orderby('id','desc')->first();  
    //     $data = [
    //   $daypoints = DB::table('points')->where('athlete_id',$athlete->id)->whereDate('created_at', Carbon::today())->sum('point') ?? '', 
    //    $weeklypoints = DB::table('points')->where('athlete_id',$athlete->id)
    //                          ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('point'),
    //    $monthlypoints = DB::table('points')->where('athlete_id',$athlete->id)
    //                          ->whereMonth('created_at', date('m'))
    //                          ->whereYear('created_at', date('Y'))->sum('point'),
          
    //    $todayplans = DB::table('plans')->select('id','image','message_from','name','description','date')->orderby('id','desc')->first(),
    //    $todayplans->time = $time->from_time.' to '.$time->to_time,
    //    $user_profile = DB::table('athletes')->select('id','name','email','image','created_at')->where('id',$athlete->id)->first(),                      
    //     ];
       
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
    public function view_support(Request $request)
    {
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
     
       $data = DB::table('supports')->select('id','title','thumbnail','price','video','support_type')->whereNull('user_type')->where('user_id',1)->orderby('id','desc')->get();
       foreach($data as $value){
        $count = DB::table('support_counts')->where('video_id',$value->id)->count();
        $value->count = $count; 
       }
     
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
    public function change_password(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'old_password' => 'required',
            'new_password' => 'required',
                     
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       if (Hash::check($request->old_password, $athlete->password)) { 
        $athletes['password'] = Hash::make($request->new_password);    
        DB::table('athletes')->where('id',$athlete->id)->update($athletes);
        $data = DB::table('athletes')->select('id','name','email','image')->where('id',$athlete->id)->first();
        if($data)
        {
            return response()->json([
                'status'=>'1',
                'message'=>'success',
                'data'=>$data,                
            ]); 
        }
     
     } else {
        return response()->json([
            'status'=>'0',
            'message'=>'Old password doesn`t match',
            'data'=> $x,
        ]);     
     }
  
    }
    public function notification(Request $request)
    {
        $x = new stdClass();
   
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       if($request->type == 0){
        $date = Carbon::today()->subDay(7);
        
        $data = DB::table('notifications')->select('id','title','description','created_at')->where('created_at','>=',$date)
        ->where('athlete_id',$athlete->id)->get();
       }else{
        $data = DB::table('notifications')->select('id','title','description','created_at')->where('athlete_id',$athlete->id)->get();
       }
       
     
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
    public function view_todayplans(Request $request)
    {
        $x = new stdClass();
   
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
      
        $data = DB::table('plans')->select('id','image','message_from','name','description','date')->get();
        foreach($data as $value)
        {
            $from_time = DB::table('plans')->where('id',$value->id)->value('from_time');
            $to_time = DB::table('plans')->where('id',$value->id)->value('to_time');
            $value->time = $from_time.' to '.$to_time;
        }
       
       
     
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
    public function season_goals(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            // 'primary_goal' => 'required',
            // 'secondary_goal' => 'required',            
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }

        $season['athlete_id'] = $athlete->id;
        if($request->primary_goal){
          $season['primary_goal'] = $request->primary_goal;   
        }
        if($request->secondary_goal){
            $season['secondary_goal'] = $request->secondary_goal; 
        }  
         
     
       $season_id = DB::table('season_goals')->insertGetId($season);
       $data = DB::table('season_goals')->select('id','athlete_id','primary_goal','secondary_goal')->where('id',$season_id)->where('athlete_id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function dreams_goals(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'dream_goal' => 'required',
                    
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }

        $dream['athlete_id'] = $athlete->id;
        $dream['dream_goal'] = $request->dream_goal;     
      
     
       $dream_id = DB::table('dreams_goals')->insertGetId($dream);
       $data = DB::table('dreams_goals')->select('id','athlete_id','dream_goal')->where('id',$dream_id)->where('athlete_id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function wellbeing(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'mood' => 'required',
            // 'thought' => 'required',
                    
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
  
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }

        $wellbeing['athlete_id'] = $athlete->id;
        $wellbeing['mood'] = $request->mood;  
        if($request->thought){
            $wellbeing['thought'] = $request->thought;
        }   
        
     
       $wellbeing_id = DB::table('wellbeings')->insertGetId($wellbeing);
       $data = DB::table('wellbeings')->select('id','athlete_id','mood','thought')->where('id',$wellbeing_id)->where('athlete_id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function start_goals(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            // 'primary_goal' => 'required',
            // 'secondary_goal' => 'required',            
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }

        $start['athlete_id'] = $athlete->id;
        if( $request->primary_goal){
            $start['primary_goal'] = $request->primary_goal; 
        }
        if($request->secondary_goal){
        $start['secondary_goal'] = $request->secondary_goal;   
        }
       $start_id = DB::table('start_goals')->insertGetId($start);

       $today = Carbon::now()->format('Y-m-d');
       $pointdate = DB::table('points')->where('athlete_id',$athlete->id)->where('type','start_goal')->where('date',$today)->exists();
       $newpoint = DB::table('admin_points')->value('start_goals');
    
       if($newpoint){
           $start_goals_point = $newpoint;
       }else{
           $start_goals_point = 1;
       }
       if(!$pointdate){
        $point['athlete_id']  = $athlete->id;
        $point['point']  = $start_goals_point;
        $point['date']  = $today;
        $point['type']  = 'start_goal';
        DB::table('points')->insert($point);
       }
       $data = DB::table('start_goals')->select('id','athlete_id','primary_goal','secondary_goal')->where('id',$start_id)->where('athlete_id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function self_talks(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [     

            // 'image' => 'required',
            'challenge' => 'required',    
            // 'recording' => 'required', 
            'role_model' => 'required',       
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $selftalk['role_model'] = $request->role_model;
       if($request->hasfile('image'))
       {
           $file = $request->image;
           $path = storage_path().'/athleteimg/';
           File::makeDirectory($path, $mode = 0777, true, true);
           $imagePath = storage_path().'/athleteimg/';         
           $post_image        = time().$file->getClientOriginalExtension();
           $image_url          = url('/').'/storage/athleteimg/'.'/'. $post_image;   
           $file->move($imagePath, $post_image);
       }
       if($request->image){
       $selftalk['image']= $image_url;
       }
      
        
        $selftalk['challenge'] = $request->challenge;
        $selftalk['athlete_id'] = $athlete->id;
      
       if($request->hasfile('recording'))
       {
          $file = $request->file('recording');
           $path = storage_path().'/athletevoice/';
           File::makeDirectory($path, $mode = 0777, true, true);
           $imagePath = storage_path().'/athletevoice/';         
           $post_image        = time().'.'.$file->getClientOriginalExtension();
           $image_url          = url('/').'/storage/athletevoice/'.'/'. $post_image;   
           $file->move($imagePath, $post_image);

       }
       if($request->recording){
       $selftalk['recording']= $image_url;
       }
       if($request->audio_name){
        $selftalk['audio_name']= $request->audio_name;
       }
       
       $exists = DB::table('self_talks')->where('athlete_id',$athlete->id)->exists();
       if($exists){
        DB::table('self_talks')->where('athlete_id',$athlete->id)->update($selftalk);
       }else{
        DB::table('self_talks')->insert($selftalk);
       }
       
       $data = DB::table('self_talks')->select('id','athlete_id','role_model','image','challenge','recording','audio_name')->where('athlete_id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function post_performance(Request $request)
    {
        // dd($request->performance);
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'performance' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
        if($request->performance){
          
              $performance_id = DB::table('post_performances')->insertGetId([
                    'athlete_id'=> $athlete->id,
                    'performance'=> $request->performance,
                ]);
            }
        
    //    $oldpoints = DB::table('athletes')->where('id',$athlete->id)->value('points');
    //    $pointdate = DB::table('athletes')->where('id',$athlete->id)->value('date');
    //    $today = Carbon::now()->format('Y-m-d');
    //    if($pointdate != $today){
    //     DB::table('athletes')->where('id',$athlete->id)->update(['points'=>$oldpoints+1,'date'=>$today]);
    //    }
   
    $today = Carbon::now()->format('Y-m-d');
    $pointdate = DB::table('points')->where('athlete_id',$athlete->id)->where('type','performance')->where('date',$today)->exists();
    $newpoint = DB::table('admin_points')->value('performace');
    
    if($newpoint){
        $performance_point = $newpoint;
    }else{
        $performance_point = 1;
    }
    if(!$pointdate){
     $point['athlete_id']  = $athlete->id;
     $point['point']  = $performance_point;
     $point['date']  = $today;
     $point['type']  = 'performance';
     DB::table('points')->insert($point);
    }
       $data = DB::table('post_performances')->select('id','performance')->where('athlete_id',$athlete->id)->orderBy('id','desc')->get();
     
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
    public function delete_performance(Request $request)
    {
        // dd($request->performace);
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'performance_id' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       DB::table('post_performances')->where('id',$request->performance_id)->where('athlete_id',$athlete->id)->delete();
      /// $exists =  DB::table('post_performances')->where('id',$request->performance_id)->where('athlete_id',$athlete->id)->exists();
       $data = DB::table('post_performances')->where('athlete_id',$athlete->id)->orderBy('id','desc')->get();
       
       if(!empty($data)){
       
        return response()->json([
            'status'=>'1',
            'message'=>'success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }
      
     
    
       
    
    }
    public function post_improvement(Request $request)
    {
       
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'improvement' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
        if($request->improvement){
           
              $improvement_id = DB::table('post_improvements')->insertGetId([
                    'athlete_id'=> $athlete->id,
                    'improvement'=> $request->improvement,
                ]);
          
        }
       
       $data = DB::table('post_improvements')->select('id','improvement')->where('athlete_id',$athlete->id)->orderBy('id','desc')->get();
     
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
    public function delete_improvement(Request $request)
    {
      
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'improvement_id' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $exists =  DB::table('post_improvements')->where('id',$request->improvement_id)->where('athlete_id',$athlete->id)->exists();
    
       if($exists){
        DB::table('post_improvements')->where('id',$request->improvement_id)->where('athlete_id',$athlete->id)->delete();
        $data = DB::table('post_improvements')->select('id','improvement')->where('athlete_id',$athlete->id)->orderBy('id','desc')->get();
        return response()->json([
            'status'=>'1',
            'message'=>'success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> $x,
        ]);     
      }      
    
    }
    public function forget_password(Request $request)
    {
      
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'email' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
    //    $athlete =  auth('api')->authenticate($request->bearerToken());
    
    //    if(!$athlete){
    //     return response()->json([
    //         'status'=>'2',
    //         'message'=>"Token is mismatch",
    //         'data'=>$x
    //     ]);       
    //    }
       $exists =  DB::table('athletes')->where('email',$request->email)->exists();   
       if($exists){
        $otp = rand(10000,99999);
        $details = [
         'title' => 'Mail from Mensani',
         'body' => "Your otp is $otp"
       ];
    
       \Mail::to($request->email)->send(new \App\Mail\MyMail($details));
       DB::table('athletes')->where('email',$request->email)->update(['otp'=>$otp]);
        return response()->json([
            'status'=>'1',
            'message'=>'Otp send successfully',
            'data'=>$x,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'Email doesn`t exists',
            'data'=> $x,
        ]);     
      }      
    
    }
    public function verify_otp(Request $request)
    {
        
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'otp' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
    //    $athlete =  auth('api')->authenticate($request->bearerToken());
    
    //    if(!$athlete){
    //     return response()->json([
    //         'status'=>'2',
    //         'message'=>"Token is mismatch",
    //         'data'=>$x
    //     ]);       
    //    }
      $result = DB::table('athletes')->where('otp',$request->otp)->exists();
       if($result){
        return response()->json([
            'status'=>'1',
            'message'=>'Otp matched successfully',
            'data'=>$x,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'Otp doesn`t matched',
            'data'=> $x,
        ]);     
      }      
    
    }
    public function reset_password(Request $request)
    {
        
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'email' => 'required',
            'password' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
    //    $athlete =  auth('api')->authenticate($request->bearerToken());
    
    //    if(!$athlete){
    //     return response()->json([
    //         'status'=>'2',
    //         'message'=>"Token is mismatch",
    //         'data'=>$x
    //     ]);       
    //    }
       $exists =  DB::table('athletes')->where('email',$request->email)->exists();
       if($exists){
        DB::table('athletes')->where('email',$request->email)->update(['password'=>bcrypt($request->password)]);
        return response()->json([
            'status'=>'1',
            'message'=>'Password updated successfully',
            'data'=>$x,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'Email doesn`t exists',
            'data'=> $x,
        ]);     
      }      
    
    }
    public function view_sports(Request $request)
    {
        
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
    
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  DB::table('sports')->select('id','sport')->orderby('id','desc')->get();
       if($data){
       
        return response()->json([
            'status'=>'1',
            'message'=>'Success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }      
    
    }
    public function visualization(Request $request)
    {
        // dd($request->all());
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'recording' => 'required',        
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
     
       $visualization['athlete_id'] = $athlete->id;
      
       if($request->hasfile('recording'))
       {
           //$file = $request->recording;
           $file = $request->file('recording');
           $path = storage_path().'/athletevoice/';
           File::makeDirectory($path, $mode = 0777, true, true);
           $imagePath = storage_path().'/athletevoice/';         
           $post_image        = time().'.'.$file->getClientOriginalExtension();
           $image_url          = url('/').'/storage/athletevoice/'.'/'. $post_image;   
           $file->move($imagePath, $post_image);


       }
       if($request->recording){
       $visualization['recording']= $image_url;
       }
       if($request->audio_name){
        $visualization['audio_name']= $request->audio_name;
        }
     
       $today = Carbon::today(); 
       $counts = DB::table('visualizations')->where('athlete_id',$athlete->id)->whereDate('created_at',$today)->count();
    
       if($counts < 4){
        $visualization_id = DB::table('visualizations')->insertGetId($visualization);
        $data = DB::table('visualizations')->select('id','recording','audio_name')->where('athlete_id',$athlete->id)->get();
       }else{
        return response()->json([
            'status'=>'0',
            'message'=>'Your limit is over',
            'data'=> [],
        ]);     
       }    
       $today = Carbon::now()->format('Y-m-d');
       $pointdate = DB::table('points')->where('athlete_id',$athlete->id)->where('type','visualization')->where('date',$today)->exists();
       $newpoint = DB::table('admin_points')->value('visualization');
    
       if($newpoint){
           $visualization_point = $newpoint;
       }else{
           $visualization_point = 4;
       }
       if(!$pointdate){
        $point['athlete_id']  = $athlete->id;
        $point['point']  = $visualization_point;
        $point['date']  = $today;
        $point['type']  = 'visualization';
        DB::table('points')->insert($point);
       }
     
       $visualizations_counts = DB::table('visualizations')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
     
       foreach($data as $value){
        if($visualizations_counts >= 4){
            $value->flag = 1;
        }else{
            $value->flag = 0;
        }
       }
      
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
    public function view_visualization(Request $request)
    {
        
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
    
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  DB::table('visualizations')->select('id','recording','audio_name')->where('athlete_id',$athlete->id)->orderby('id','desc')->get();
       $visualizations_counts = DB::table('visualizations')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
    //    dd($visualizations_counts);
       foreach($data as $value){
        if($visualizations_counts >= 4){
            $value->flag = 1;
        }else{
            $value->flag = 0;
        }
       }
       if($data){
       
        return response()->json([
            'status'=>'1',
            'message'=>'Success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }      
    
    }
    public function delete_visualization(Request $request)
    {
      
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'visualization_id' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $exists =  DB::table('visualizations')->where('id',$request->visualization_id)->where('athlete_id',$athlete->id)->exists();
       if($exists){
        DB::table('visualizations')->where('id',$request->visualization_id)->where('athlete_id',$athlete->id)->delete();
        $visualizations_counts = DB::table('visualizations')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
        
            if($visualizations_counts >= 4){
                $flag = 1;
            }else{
                $flag = 0;
            }
         
        return response()->json([
            'status'=>'1',
            'message'=>'success',
            'flag'=>$flag,
            'data'=>$x,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> $x,
        ]);     
      }      
    
    }
    public function start_selftalk(Request $request)
    {
        // dd($request->all());
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'recording' => 'required',        
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
     
       $selftalk['athlete_id'] = $athlete->id;
       if($request->audio_name){
        $selftalk['audio_name']= $request->audio_name;
        }
        $file = $request->file('recording');

       

       if($request->hasfile('recording'))
       {
           $file = $request->file('recording');

          
           $path = storage_path().'/athletevoice/';
           File::makeDirectory($path, $mode = 0777, true, true);
           $imagePath = storage_path().'/athletevoice/';         
           $post_image        = time().'.'.$file->getClientOriginalExtension();
           $image_url          = url('/').'/storage/athletevoice/'.'/'. $post_image;   
           $file->move($imagePath, $post_image);
       }
       if($request->recording){
       $selftalk['recording']= $image_url;
       }
       //dd($selftalk);
  
       $today = Carbon::today(); 
       $counts = DB::table('start_selftalks')->where('athlete_id',$athlete->id)->whereDate('created_at',$today)->count();
    
       if($counts < 4){
        $selftalk_id = DB::table('start_selftalks')->insertGetId($selftalk);
        $data = DB::table('start_selftalks')->select('id','recording','audio_name')->where('athlete_id',$athlete->id)->get();
       }else{
        return response()->json([
            'status'=>'0',
            'message'=>'Your limit is over',
            'data'=> [],
        ]);     
       }    
       $today = Carbon::now()->format('Y-m-d');
       $pointdate = DB::table('points')->where('athlete_id',$athlete->id)->where('type','selftalk')->where('date',$today)->exists();
       $newpoint = DB::table('admin_points')->value('start_selftalks');
    
       if($newpoint){
           $start_selftalks_point = $newpoint;
       }else{
           $start_selftalks_point = 1;
       }
       if(!$pointdate){
        $point['athlete_id']  = $athlete->id;
        $point['point']  = $start_selftalks_point;
        $point['date']  = $today;
        $point['type']  = 'selftalk';
        DB::table('points')->insert($point);
       }
       $self_counts = DB::table('start_selftalks')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
    
       foreach($data as $value){
        if($self_counts >= 4){
            $value->flag = 1;
        }else{
            $value->flag = 0;
        }
       }
      
    
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
    public function view_start_selftalk(Request $request)
    {
        
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
    
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  DB::table('start_selftalks')->select('id','recording','audio_name')->where('athlete_id',$athlete->id)->orderby('id','desc')->get();
       $self_counts = DB::table('start_selftalks')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
       foreach($data as $value){
        if($self_counts >= 4){
            $value->flag = 1;
        }else{
            $value->flag = 0;
        }
       }
       
      
       if($data){
       
        return response()->json([
            'status'=>'1',
            'message'=>'Success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }      
    
    }
    public function delete_start_selftalk(Request $request)
    {
      
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'selftalk_id' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
   
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $exists =  DB::table('start_selftalks')->where('id',$request->selftalk_id)->where('athlete_id',$athlete->id)->exists();
       if($exists){
        DB::table('start_selftalks')->where('id',$request->selftalk_id)->where('athlete_id',$athlete->id)->delete();
        $self_counts = DB::table('start_selftalks')->where('athlete_id',$athlete->id)->whereDate('created_at',Carbon::today())->count();
       
         if($self_counts >= 4){
             $flag = 1;
         }else{
             $flag = 0;
         }
        
        return response()->json([
            'status'=>'1',
            'message'=>'success',
            'flag'=>$flag,
            'data'=>$x,
            
        ]);    
      }else{ 
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> $x,
        ]);     
      }      
    
    }
    public function subscription_plan(Request $request)
    {
        
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
    
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  DB::table('subscriptions')->select('id','name','description','duration','price')->orderby('id','desc')->get();
       $subscription_id = $athlete->subscription_id  ?? '' ;
       
       if($data){
       
        return response()->json([
            'status'=>'1',
            'message'=>'Success',
            'data'=>$data,
            'subscription_id'=>$subscription_id
        
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
            'subscription_id'=>""
        ]);     
      }      
    
    }
    public function delete_notification(Request $request)
    {
        // dd($request->performace);
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'notification_id' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $exists =  DB::table('notifications')->where('id',$request->notification_id)->where('athlete_id',$athlete->id)->exists();
       if($exists){
        DB::table('notifications')->where('id',$request->notification_id)->where('athlete_id',$athlete->id)->delete();
        return response()->json([
            'status'=>'1',
            'message'=>'success',
            'data'=>[],
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }
    }
    public function view_performances(Request $request)
    {
        
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
    
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  DB::table('post_performances')->select('id','performance')->where('athlete_id',$athlete->id)->orderby('id','desc')->get();
       if($data){
       
        return response()->json([
            'status'=>'1',
            'message'=>'Success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }      
    
    }
    public function view_improvements(Request $request)
    {
        
        $x = new stdClass();
       
       $athlete =  auth('api')->authenticate($request->bearerToken());
    
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
       $data =  DB::table('post_improvements')->select('id','improvement')->where('athlete_id',$athlete->id)->orderby('id','desc')->get();
       if($data){
       
        return response()->json([
            'status'=>'1',
            'message'=>'Success',
            'data'=>$data,
            
        ]);    
      }else{
        return response()->json([
            'status'=>'0',
            'message'=>'No data found',
            'data'=> [],
        ]);     
      }      
    
    }
    public function support_count(Request $request)
    {
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'video_id' => 'required',
                     
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }

        $support['athlete_id'] = $athlete->id;
      
        $support['video_id'] = $request->video_id; 
        
         
       $exists =  DB::table('support_counts')->where('athlete_id',$athlete->id)->where('video_id',$request->video_id)->exists();
       if(!$exists){
        DB::table('support_counts')->insert($support);
       }else{
        return response()->json([
            'status'=>'0',
            'message'=>'This video is already seen',
            'data'=>$x,
            
        ]); 
       }      
       $data = DB::table('support_counts')->select('id','athlete_id','video_id')->where('athlete_id',$athlete->id)->first();
     
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
                'data'=> $x,
            ]);     
        }
       
    
    }
    public function therapist_for_review(Request $request)
    {
       
        $x = new stdClass();
        $validator = Validator::make($request->all(), [            
            'therapist_id' => 'required',
            'athlete_id' => 'required',
            'stars' => 'required',
            'feedback' => 'required',
                
        ]);
        
        if ($validator->fails()) { 
           
             return response()->json([
                'status'=>'0',
                'message'=>$validator->errors()->first(),
                'data'=>$x
            ]);           
         }
       $athlete =  auth('api')->authenticate($request->bearerToken());
    //    dd( $athlete);
       if(!$athlete){
        return response()->json([
            'status'=>'2',
            'message'=>"Token is mismatch",
            'data'=>$x
        ]);       
       }
     
           $review_feedback_id = DB::table('review_feedback')->insertGetId([
                    'therapist_id'=> $request->therapist_id,
                    'athlete_id'=> $request->athlete_id,
                    'stars'=> $request->stars,
                    'feedback'=> $request->feedback,
                ]);
          
       
       $data = DB::table('review_feedback')->where('id',$review_feedback_id)->get();
      
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

}
