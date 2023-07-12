<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Validator;
use File;
use Session;
use Stripe;
use Stripe\Stripe_CardError;
// use Stripe\Stripe_InvalidRequestError;
use Stripe\Error\Card;
use Illuminate\Support\Facades\Redirect;
use App\Models\Therapist;
use PRedis;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Transactions;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;




class AdminController extends Controller
{    
    
    // public function index()
    // {
    //     return view('index');
    // }
    public function dashboard()
    {   
        $athletes = DB::table('athletes')->count();
        $sports = DB::table('sports')->count();
        $subscription = DB::table('subscriptions')->count();
     
        return view('Admin.dashboard',compact('athletes','sports','subscription'));
    }
    public function login_view(){
        if(Auth::user()){
            return redirect('Admin/dashboard');
        }
        return view('Admin.Auth.sign_in');        
    }
    public function Admin_login(Request $request){
        $request->validate([
           'email'          => 'required',
           'password'       => 'required',
       ]);
     
       $credentials = [
           'email' => $request['email'],
           'password' => $request['password'],
       ];        
          if(Auth::attempt($credentials)){
           return redirect('Admin/dashboard');
          }
          else{
           return back()->with('errormessage', 'Please Enter Valid Credentials!');
       }
      
      }
      public function logout()
      {
       Auth::guard('web')->logout();
       return redirect('Admin/login');
      }
      public function view_athletes(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $athletes = DB::table('athletes')->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        //   dd($athletes);
                return Datatables::of($athletes)
                    ->addIndexColumn()
                  
                    ->addColumn('name', function($row){
                      $name='<a href="'.url("Admin/athletedetails").'/'.$row->id.'">'.$row->name.'</a>';
                      return $name;
                  })
                   
                     ->addColumn('status', function($row){
                     
                       $status = $row->status == 1 ? "<button class='on' id='active' onclick='show($row->id)' ><i class='fa-solid fa-toggle-on'  ></i></button>":
                       "<button class='on' id='inactive' onclick='show($row->id)' ><i class='fa-solid fa-toggle-off'  ></i></button>";
                       return $status;
                   
                     
                  })
              
           
            
                    ->rawColumns(['name','status'])
                    ->make(true);
            }
          
              return view('Admin.view_athlete');
          
           
      }
      public function change_athlete_status(Request $request)
      {
       // dd($request->all());
       $validator = Validator::make($request->all(), [ 
         'athlete_id'  => 'required',
       ]);
       if ($validator->fails()) { 
         return response()->json([
           'status'=>'0',
           'message'=>$validator->errors(),
         ]);          
       }
       try{
           DB::beginTransaction();          
           $result = DB::table('athletes')->where('id',$request->athlete_id)->first();
           // dd($result);
           if($result->status == 1){
               $athlete['status'] = 0;
           }else{
               $athlete['status'] = 1;
           }
           DB::table('athletes')->where('id',$request->athlete_id)->update($athlete);
           DB::commit();
           return response()->json([
               'status'=>'1',
               'message'=>'done',
           ]);
   
       }
       catch(Exception $e){
           DB::rollback();
           return response()->json([
               'status'=>'0',
               'message'=>$e->getMessage(),
           ]);
       }
      }
      public function notification()
      {
        $athletes = DB::table('athletes')->orderby('id','desc')->get();
        $therapists = Therapist::orderby('id','desc')->get();
        return view('Admin.notification',compact('athletes','therapists'));
      }
      public  function save_notification(Request $request)
      { 
       
       try{
       
            if($request->userType == "Therapist")
            {
                  $validator = Validator::make($request->all(), [
                    'therapist'           =>     'required',
                    'title'           =>     'required',
                    'description'      =>     'required',
                    
          
                ]);
                if ( $validator->fails()) { 
                  return response()->json([
                      'status'=>'0',
                      'message'=>$validator->errors()->first(),
                  ]);          
              } 
          
                  if($request->therapist){

                    foreach($request->therapist as $therapist)
                    {
                      if($request->therapist[0] == "All")
                        {
                            
                          $users = Therapist::get();
                          foreach($users as $user)
                          {
                              $notification_id =  DB::table('notifications')->insertGetId([
                                'athlete_id'    => $user->id,                      
                                'title'       => $request->get('title'),
                                'description'       => $request->get('description'),
                                'user_type'  => $request->userType ,  

                                          
                              ]);
                          }
                      }else{
                      
                          $notification_id =  DB::table('notifications')->insertGetId([
                            'athlete_id'    => $therapist,                      
                            'title'       => $request->get('title'),
                            'description'       => $request->get('description'), 
                            'user_type'  => $request->userType            
                                      
                          ]);
                      
                      }
                        
                        if($request->therapist[0] == "All"){
                          $users = DB::table('therapists')->where('fcm_token','!=','')->get();
                          foreach($users as $user)
                          { 
                          
                            $device_token = $user->fcm_token ?? '';
                            $devtype   = $user->device_type ?? '';
                            $badge = '0';
                            $title = $request->title;
                            $req_status="1";
                            $description = $request->description;            
                            $result = $this->push_notification($device_token,$devtype,$req_status,$badge,$title,$description);   
                          }
                        }else{
                        
                          $user = DB::table('therapists')->where('fcm_token','!=','')->where('id',$therapist)->first();
                        
                          $device_token = $user->fcm_token ?? '';
                          $devtype   = $user->device_type ?? '';
                          $badge = '0';
                          $title = $request->title;
                          $req_status="1";
                          $description = $request->description;            
                          $result = $this->push_notification($device_token,$devtype,$req_status,$badge,$title,$description);   
                          // dd($result); 
                        }  
                          
                    } 
                  }
          
            }
            else
            { 
              
              // athelete   
              $validator = Validator::make($request->all(), [
                    'athlete'           =>     'required',
                    'title'           =>     'required',
                    'description'      =>     'required',
                    
          
                ]);
                if ( $validator->fails()) { 
                  return response()->json([
                      'status'=>'0',
                      'message'=>$validator->errors()->first(),
                  ]);          
              } 
          
                  if($request->athlete){
                        
          
                    foreach($request->athlete as $athlete)
                    {
                        if($request->athlete[0] == "All")
                        {
                            
                          $users = DB::table('athletes')->get();
                          foreach($users as $user)
                          {
                              $notification_id =  DB::table('notifications')->insertGetId([
                                'athlete_id'    => $user->id,                      
                                'title'       => $request->get('title'),
                                'description'       => $request->get('description'),
                                'user_type'  => $request->userType ,       
                                          
                              ]);
                          }
                      }else{
                      
                        $notification_id =  DB::table('notifications')->insertGetId([
                          'athlete_id'    => $athlete,                      
                          'title'       => $request->get('title'),
                          'description'       => $request->get('description'), 
                          'user_type'  => $request->userType ,            
                                    
                        ]);
                      
                      }


                        if($request->athlete[0] == "All"){
                          $users = DB::table('athletes')->where('fcm_token','!=','')->where('device_type','!=','')->get();
                          foreach($users as $user)
                          {
                            $device_token = $user->fcm_token ?? '';
                            $devtype   = $user->device_type ?? '';
                            $badge = '0';
                            $title = $request->title;
                            $req_status="1";
                            $description = $request->description;            
                            $result = $this->push_notification($device_token,$devtype,$req_status,$badge,$title,$description);   
                          }
                        }else{
                          $user = DB::table('athletes')->where('fcm_token','!=','')->where('device_type','!=','')->where('id',$athlete)->first();
                        
                          $device_token = $user->fcm_token ?? '';
                          $devtype   = $user->device_type ?? '';
                          $badge = '0';
                          $title = $request->title;
                          $req_status="1";
                          $description = $request->description;            
                          $result = $this->push_notification($device_token,$devtype,$req_status,$badge,$title,$description);   
                          // dd($result); 
                        }  
                          
                    } 
                  }
          
            }
          
                return response()->json([
                  'status'=>'1',
                  'message'=>'Notification Added',
              ]);                  
                    
      
       } catch(Exception $e){
       
            return response()->json([
              'status'=>'0',
              'message'=>$e->getMessage(),
          ]);  
       
      }  
      
      }
      public function push_notification($device_token,$devtype,$req_status,$badge,$title,$description)
    {  
    // echo $device_id.' # '.$devicetype.' # '.$mymessage.' # '.$title.' # '.$rid.' # '.$req_status.' # '.$ncount;exit();
   
    
    // $url = 'https://fcm.googleapis.com/fcm/send';
      // $api_key = 'AAAAv8OtDio:APA91bHFDffZ-g1fEJXS4AtG6tr-6TdJaGyj3hnqv7TvLyo0CPAMS9pBGbnW7TIJnza1rcWz0NquOv2zGDnT8y3IV0RKuaTUjXLO1UMr3974tk5x8KPGatOGsFkjmPzW_M0vz-dgbqDB';

      $url = 'https://fcm.googleapis.com/fcm/send';
      $api_key = 'AAAA4kDkKnY:APA91bFaAZzdgY42phw85t6_v2Vvqny7DkCuT-4957-KRicxgSU9k4TYacoEtTcZGV3CUoPsP8tm4narlOvTjooP5RyR5RzxWD7XhL4ilPp1AtdoXP56WaP3BFSaPuEgMKu56lxYKkG9';

      if($devtype ==1){
        $fields = (object) [
              "to" =>$device_token,
               "notification" => (object) [
                "title"     => $title,             
                "body"        => $description,
              
                // "description" => $description,               
                "requestStatus" => $req_status,
                "vibrate"   => "default",
                "sound"     => "default",
                "badge"     =>  $badge
               ],
              "data" => (object) [
                // "requestID"   => $rid,
                "title"     => $title,
                "body"        => $description,
               
                // "description" => $description,
                "vibrate"   => "default",
                "sound"     => "default",
                "requestStatus" => $req_status,
                "badge"     => $badge
              ]
        ];
      }else{
        $fields = (object) [
              "to" =>$device_token,
               "notification" => (object) [
                "title"     => $title,
              "body"        => $description,
               
                // "description" => $description,
                "requestStatus" => $req_status,
                "vibrate"   => "default",
                "sound"     => "default",
                "badge"     =>  $badge
               ],
              "data" => (object) [
                "title"     => $title,
                "body"        => $description,
                
                // "description" => $description,
                "requestStatus" => $req_status,
                "vibrate"   => "default",
                "sound"     => "default",
                "badge"     =>  $badge
              ]
        ];
      } 
      // dd($fields);
      $headers = array(
          'Content-Type:application/json',
          'Authorization:key='.$api_key
      );              
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('FCM Send Error: ' . curl_error($ch));
      }
      curl_close($ch);
     //  dd($result);
      return $result;
}
      public function view_notification(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $notification = DB::table('notifications')->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        //   dd($athletes);
                return Datatables::of($notification)
                    ->addIndexColumn()
                  
                    ->addColumn('action', function($row){
                      // $actionBtn = '<a href="'.url("Admin/editquestion").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                      $actionBtn = '<a href="'.url("Admin/deletenotification").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                      
                      return $actionBtn;
                      
                  })
                  ->addColumn('user_name', function($row){
                   
                    if($row->user_type =="Athlete"){
                      $user_name = DB::table('athletes')->where('id',$row->athlete_id)->pluck('name')->first();
                    }else{
                      $user_name = DB::table('therapists')->where('id',$row->athlete_id)->pluck('name')->first();
                    }
                    
                    return $user_name;
                    
                })
                   
                    ->rawColumns(['action','user_name'])
                    ->make(true);
            }
          
              return view('Admin.view_notification');
          
           
      }
      // therapist 
      public function view_therapist(Request $request)
      { 
        DB::statement(DB::raw('set @rownum=0'));
        $therapists = Therapist::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
                 
                  return Datatables::of($therapists)
                    
                      ->addIndexColumn()

                      ->addColumn('actions', function($row){
                         $actionBtn = '<a href="'.url("Admin/edit_therepist").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                         $actionBtn .= '<a href="'.url("Admin/deletetherepist").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                      
                         return $actionBtn;
                       
                    })
                    ->addColumn('created_at', function($row){
                      
                   
                      return '<span style="display:none;">'.strtotime($row->created_at).'</span>'.date('Y-m-d H:i:s',strtotime($row->created_at));
                    
                 })
                    ->addColumn('status', function($row){
                     
                        $status = $row->status == 1 ? "<button class='on' id='active' onclick='show($row->id)' ><i class='fa-solid fa-toggle-on'  ></i></button>":
                        "<button class='on' id='inactive' onclick='show($row->id)' ><i class='fa-solid fa-toggle-off'  ></i></button>";
                        return $status;
                    
                       })
                      ->rawColumns(['name','status','actions','created_at'])
                   
                      ->make(true);
              }
      
       
        return view('Admin.therapist.view_therapist');
      }

      public function deletetherepist($id)
      { 
        Therapist::where('id',$id)->delete();
        return redirect()->back()->with('message', 'Therapist deleted successfully');;
      }

      public function change_therepist_status(Request $request)
      {
      
        $validator = Validator::make($request->all(), [ 
          'therapist_id'  => 'required',
        ]);
        if ($validator->fails()) { 
          return response()->json([
            'status'=>'0',
            'message'=>$validator->errors(),
          ]);          
        }
        try{
            DB::beginTransaction();          
            $result = DB::table('therapists')->where('id',$request->therapist_id)->first();
          
            if($result->status == 1){
                $therapist['status'] = 0;
            }else{
                $therapist['status'] = 1;
            }
            DB::table('therapists')->where('id',$request->therapist_id)->update($therapist);
            DB::commit();
            return response()->json([
                'status'=>'1',
                'message'=>'done',
            ]);
    
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status'=>'0',
                'message'=>$e->getMessage(),
            ]);
        }
      }


      public function addtherepist()
      { 
        $country_codes = DB::table('countries')->get();
        $sports = DB::table('sports')->get();

        return view('Admin.therapist.addtherapist')->with(compact('country_codes','sports'));
      }

      public function fetchState(Request $request)
      {  
        $data['states'] = DB::table('states')->where("country_id",$request->country_id)->get(["name", "id"]);
        return response()->json($data);
      }

      public function savetherepist(Request $request)
      {

        $validator = Validator::make($request->all(), [
                 
          'first_name'    =>     'required',
          'last_name'      =>     'required',  
          'gender'      =>     ['required','in:male,female'],  
          'email' => 'required|unique:therapists,email,NULL,id,deleted_at,NULL', 
          'phone'      =>     'required', 
          'password'      =>     'required', 
          'country'      =>     'required',
          'sport'      =>     'required',
          'license'      =>     'required',
          'degree'      =>     'required',
          'experience'      =>     'required',
          'phone' => 'required|min:11|numeric',

          
         ]);
             if ( $validator->fails()) { 
               return response()->json([
                   'status'=>'0',
                   'message'=>$validator->errors()->first(),
               ]);          
           } 

          $input = $request->all();
          $input['password']  = bcrypt($request->password); 
          $input['name']  = $request->first_name.' '.$request->last_name;  
          if($request->hasfile('image'))
          {
              $file = $request->image;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $user_image        = time().$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $user_image;   
              $file->move($imagePath, $user_image);
          }
          if($request->image){
              $input['image']= $image_url;
          }
          unset( $input['_token']);
          $therapist_id = DB::table('therapists')->insertGetId($input);
        
           if($therapist_id){       
             return response()->json([
               'status'=>'1',
               'message'=>'Therapist Added Successfully',
           ]);                  
           }else{
             return response()->json([
               'status'=>'0',
               'message'=>'Therapist not Added',
           ]);          
           }   
      }

      public function edit_therepist($id)
      {
        $country_codes = DB::table('countries')->get();
        $sports = DB::table('sports')->get();
        $therapists = DB::table('therapists')->find($id);
        $states = DB::table('states')->get();

        return view('Admin.therapist.editTherapist')->with(compact('country_codes','sports','therapists','states'));
      }

      public function update_therepist(Request $request)
      {

        $validator = Validator::make($request->all(), [
                 
          'first_name'    =>     'required',
          'last_name'      =>     'required',  
          'gender'      =>     ['required','in:male,female'],  
          'email' => 'required|email|unique:therapists,email,'.$request->id,
          'country'      =>     'required',
          'sport'      =>     'required',
          'license'      =>     'required',
          'degree'      =>     'required',
          'experience'      =>     'required',
          'phone' => 'required|min:11|numeric',
          
         ]);
          if ( $validator->fails()) { 
               return response()->json([
                   'status'=>'0',
                   'message'=>$validator->errors()->first(),
               ]);          
           }
          try {
           
          $therapists = DB::table('therapists')->find($request->id);  
          $input = $request->all();
          if(!empty($input['password'])){
            $input['password']  = bcrypt($request->password); 
          }else{
            $input['password']  = $therapists->password;
          }
          $image_url = $request->oldthumbnail;
          if($request->hasfile('image'))
          {
              $file = $request->image;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $user_image        = time().$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $user_image;   
              $file->move($imagePath, $user_image);
          }
          
          $input['image']= $image_url;
       
          
          if( $request->has('pro_user') ){
            $input['pro_user']  = 1;
          }else{
            $input['pro_user']  = 0;
          }


        
          $input['name']  = $request->first_name.' '.$request->last_name;  
          unset($input['_token'],$input['id'],$input['oldthumbnail']);
       
          $therapist_id = DB::table('therapists')->where(['id'=>$request->id])->update($input);
           
             return response()->json([
               'status'=>'1',
               'message'=>'Therapist Updated Successfully',
           ]);                  
          }
           catch(Exception $e){
          
            return response()->json([
                'status'=>'0',
                'message'=>$e->getMessage(),
            ]);
        }   
      }
      public function deletenotification($id)
      {
        DB::table('notifications')->where('id',$id)->delete();
        return redirect()->back();
      }
      public function questions()
      {
        return view('Admin.question');
      }
      public  function save_questions(Request $request)
      { 
        // dd($request->all());       
          $validator = Validator::make($request->all(), [
                 
                  'question.*'    =>     'required',
                  'answer.*'      =>     'required',
                 
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(), 
                ]);          
            } 
        
            // $ids = [56, 57, 58, 59, 60, 61, 62];
            DB::table('questions')->delete();
          for($i=0;$i<count($request->question);$i++){
         
            $question_id = DB::table('questions')->insertGetId([
              'question'    => $request->question[$i],
              'answer'    => $request->answer[$i],             
            ]);  
           
        }
        
          // $question_id = DB::table('questions')->insertGetId($question);
        
          if($question_id){       
            return response()->json([
              'status'=>'1',
              'message'=>'Question Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Question not Added',
          ]);          
          }
      }
      public function view_questions(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $question = DB::table('questions')->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        //   dd($athletes);
                return Datatables::of($question)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                      $actionBtn = '<a href="'.url("Admin/editquestion").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                      $actionBtn .= '<a href="'.url("Admin/deletequestion").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                      
                      return $actionBtn;
                      
                  })
              
                    ->rawColumns(['action'])
                    ->make(true);
            }
          
              return view('Admin.view_questions');
          
           
      }
      public function editquestion($id)
      {
        $question =  DB::table('questions')->where('id',$id)->first();
        return view('Admin.editquestion',compact('question'));
      }
      public  function updatequestion(Request $request,$id)
      { 
        // dd($request->all());       
          $validator = Validator::make($request->all(), [
                 
                  // 'question'           =>     'required',
                  // 'answer'      =>     'required',                 
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
          if($request->get('question')){
            $question['question'] = $request->get('question');
          }
          if($request->get('answer')){
            $question['answer'] = $request->get('answer');
          }
         
        
          DB::table('questions')->where('id',$id)->update($question);
        
          if($question){       
            return response()->json([
              'status'=>'1',
              'message'=>'Question Updated',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Question not Updated',
          ]);          
          }
      }
      public function deletequestion($id)
      {
        DB::table('questions')->where('id',$id)->delete();
        return redirect()->back();
      }
      public function privacy_policy()
      {
        $policy = DB::table('privacy_policy')->first();
        return view('privacy_policy',compact('policy'));
      }
      public function privacy()
      {
        $privacy =   DB::table('privacy_policy')->first();     
        return view('Admin.privacy',compact('privacy'));
      }
      public  function save_privacy(Request $request)
      { 
        // dd($request->all());       
          $validator = Validator::make($request->all(), [
                 
           'content'    =>     'required',                
        
          ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
         
          $policy['content'] = $request->get('content');   
        
          DB::table('privacy_policy')->update($policy);
        
          if($policy){       
            return response()->json([
              'status'=>'1',
              'message'=>'Policy Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Policy not Added',
          ]);          
        }
      }
      public function support()
      {
        return view('Admin.support');
      }
      public  function save_support(Request $request)
      { 
        // dd($request->all());       
          $validator = Validator::make($request->all(), [
                 
           'title'    =>     'required',
           'thumbnail'      =>     'required',  
           'price'      =>     'required',  
           'video'      =>     'required',  
           'support_type'      =>     'required',                 
        
          ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
         
          $support['title'] = $request->get('title');
          $support['user_id'] = Auth::user()->id;
       
          if($request->hasfile('thumbnail'))
          {
              $file = $request->thumbnail;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $post_image        = time().$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
            
          }
          if($request->thumbnail){
          $support['thumbnail']= $image_url;
          }
          $support['price'] = $request->get('price');
         
          if($request->hasfile('video'))
          {
              // $file = $request->video;
              // $path = storage_path().'/supportvideo/';
              // File::makeDirectory($path, $mode = 0777, true, true);
              // $imagePath = storage_path().'/supportvideo/';         
              // $post_image        = time().$file->getClientOriginalExtension();
              // $image_url          = url('/').'/storage/supportvideo/'.'/'. $post_image;   
              // $file->move($imagePath, $post_image);
              $file = $request->file('video');
              $path = storage_path().'/supportvideo/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/supportvideo/';         
              $post_image        = time().'.'.$file->getClientOriginalExtension();
              $image_url          = url('/').'/storage/supportvideo/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
          }
          if($request->video){
          $support['video']= $image_url;
          }
          $support['support_type'] = $request->get('support_type');
        
          $support_id = DB::table('supports')->insertGetId($support);
        
          if($support_id){       
            return response()->json([
              'status'=>'1',
              'message'=>'Support Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Support not Added',
          ]);          
          }
      }
      public function view_support(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $support = DB::table('supports')->where('user_id',1)->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        //   dd($athletes);
                return Datatables::of($support)
                    ->addIndexColumn()
                    ->addColumn('thumbnail', function($row){
                      
                        $thumbnail='<img src="'.$row->thumbnail.'" style="height: 100px;width:100px;">';
                        return $thumbnail;
                    
                      
                  })
                    ->addColumn('video_data', function($row){
                      
                     return '<video controls style="height:100px;width:100px"> <source src="'.$row->video.'" type="video/mp4"><source src="'.$row->video.'" type="video/ogg"> </video>';;
                      
                  })
              
                  ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.url("Admin/editsupport").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                    $actionBtn .= '<a href="'.url("Admin/deletesupport").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                    
                    return $actionBtn;
                    
                })
                   
           
            
                    ->rawColumns(['thumbnail','video_data','action'])
                    ->make(true);
            }
          
              return view('Admin.view_support');
      }
      public function deletesupport($id)
      {
        $supports = DB::table('supports')->where('id', $id)->first();
        $fileUrl = $supports->video;
        $fileName = basename($fileUrl);
      
        $directory = storage_path('supportvideo').'/'.$fileName; // Replace with the actual directory path where files are stored
       
        $files = File::delete($directory);
    
        DB::table('supports')->where('id', $id)->delete();  
          // User and associated files deleted successfully
     
        return redirect()->back();
      }
      public function editsupport($id)
      {
        $support = DB::table('supports')->where('id',$id)->first();
        return view('Admin.editsupport',compact('support'));
      }
      public  function updatesupport(Request $request,$id)
      { 
        // dd($request->all());       
          $validator = Validator ::make($request->all(), [
                 
           'title'    =>     'required',
          //  'thumbnail'      =>     'required',  
           'price'      =>     'required',  
          //  'video'      =>     'required',  
           'support_type'      =>     'required',                 
        
          ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
         
          $support['title'] = $request->get('title');
       
          $image_url = $request->oldthumbnail;
          if($request->hasfile('thumbnail'))
          {
              $file = $request->thumbnail;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $post_image        = time().$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
          }
        
          $support['thumbnail']= $image_url;
          
          $support['price'] = $request->get('price');
         
          $image_url = $request->oldvideo;
          if($request->hasfile('video'))
          {
              $file = $request->video;
              $path = storage_path().'/supportvideo/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/supportvideo/';         
              $post_image        = time().$file->getClientOriginalExtension();
              $image_url          = url('/').'/storage/supportvideo/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
          }
       
          $support['video']= $image_url;
          
          $support['support_type'] = $request->get('support_type');
        
          DB::table('supports')->where('id',$id)->update($support);
        
          if($support){       
            return response()->json([
              'status'=>'1',
              'message'=>'Support Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Support not Added',
          ]);          
          }
      }
      public function addplan()
      {
        return view('Admin.addplan');
      }
      public  function saveplan(Request $request)
      { 
        // dd($request->all());        
          $validator = Validator::make($request->all(), [
                 
           'image'    =>     'required',
           'message_from'      =>     'required',  
           'name'      =>     'required',  
           'description'      =>     'required',  
           'from_time'      =>     'required',  
           'to_time'      =>     'required', 
          //  'date'      =>     'required',                
        
          ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            }        
       
          if($request->hasfile('image'))
          {
              $file = $request->image;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $post_image        = time().$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
          }
          if($request->image){
          $plan['image']= $image_url;
          }
          $plan['message_from'] = $request->get('message_from');
          $plan['name'] = $request->get('name');
          $plan['description'] = $request->get('description');          
          $plan['from_time'] = $request->get('from_time');
          $plan['to_time'] = $request->get('to_time');
          if($request->get('from_date') < $request->get('to_date')){
            $plan['from_date'] = $request->get('from_date');
            $plan['to_date'] = $request->get('to_date');
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Enter valid date',
          ]);        
          }       
          $plan['date'] = $request->get('to_date');        
          $plan_id = DB::table('plans')->insertGetId($plan);
        
          if($plan_id){       
            return response()->json([
              'status'=>'1',
              'message'=>'Plan Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Plan not Added',
          ]);          
          }
      }
      public function viewplan(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $plan = DB::table('plans')->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        //   dd($athletes);
                return Datatables::of($plan)
                    ->addIndexColumn()
                    ->addColumn('image', function($row){
                      
                        $image='<img src="'.$row->image.'" style="height: 100px;width:100px;">';
                        return $image;
                    
                      
                  })
                  ->addColumn('time', function($row){
                      
                    $time= $row->from_time.' to '.$row->to_time;
                    return $time;
                
                  
              })
              ->addColumn('description', function($row){
                      
                $description= $row->description;
                return $description;
            
              
          })
              
                  ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.url("Admin/editplan").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                    $actionBtn .= '<a href="'.url("Admin/deleteplan").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                    // $actionBtn = '<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#yourModal">';
                    return $actionBtn;
                    
                })
                   
           
            
                    ->rawColumns(['image','time','description','action'])
                    ->make(true);
            }
          
              return view('Admin.viewplan');
      }
      public function editplan($id)
      {
        $plan = DB::table('plans')->where('id',$id)->first();
        return view('Admin.editplan',compact('plan'));
      }
      public  function updateplan(Request $request,$id)
      { 
        // dd($request->all());        
          $validator = Validator::make($request->all(), [
                 
          //  'image'    =>     'required',
           'message_from'      =>     'required',  
           'name'      =>     'required',  
           'description'      =>     'required',  
           'from_time'      =>     'required',  
           'to_time'      =>     'required', 
           'date'      =>     'required',                
        
          ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            }        
       
            $image_url = $request->oldimage;  
          if($request->hasfile('image'))
          {
              $file = $request->image;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $post_image        = time().$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
          }
          
          $plan['image']= $image_url;
          
          $plan['message_from'] = $request->get('message_from');
          $plan['name'] = $request->get('name');
          $plan['description'] = $request->get('description');          
          $plan['from_time'] = $request->get('from_time');
          $plan['to_time'] = $request->get('to_time');       
          $plan['date'] = $request->get('date');        
          DB::table('plans')->where('id',$id)->update($plan);
        
          if($plan){       
            return response()->json([
              'status'=>'1',
              'message'=>'Plan Updated',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Plan not Updated',
          ]);          
          }
      }
      public function deleteplan($id)
      {
        DB::table('plans')->where('id',$id)->delete();
        return redirect()->back();
      }
      public function sports()
      {       
        return view('Admin.sports');
      }
      public  function savesports(Request $request)
      {    
          $validator = Validator::make($request->all(), [
                  'sport'           =>     'required',                 
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
            $sports['sport'] = $request->sport;
            $sport_id =  DB::table('sports')->insertGetId($sports);  
         
        
          if($sport_id){       
            return response()->json([
              'status'=>'1',
              'message'=>'Sports Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Sports not Added',
          ]);          
          }
      }
      public function viewsports(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $sport = DB::table('sports')->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        
                return Datatables::of($sport)
                    ->addIndexColumn()
                  
                    ->addColumn('action', function($row){
                      $actionBtn = '<a href="'.url("Admin/editsports").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                      $actionBtn .= '<a href="'.url("Admin/deletesports").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                      
                      return $actionBtn;
                      
                  })
                   
                   
           
            
                    ->rawColumns(['action'])
                    ->make(true);
            }
          
              return view('Admin.viewsports');
          
           
      }
      public function editsports($id)
      {
        $sport = DB::table('sports')->where('id',$id)->first();
        return view('Admin.editsports',compact('sport'));
      }
      public  function updatesports(Request $request,$id)
      {    
          $validator = Validator::make($request->all(), [
                  'sport'           =>     'required',                 
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
            $sports['sport'] = $request->sport;
            DB::table('sports')->where('id',$id)->update($sports);  
         
        
          if($sports){       
            return response()->json([
              'status'=>'1',
              'message'=>'Sports Updated',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Sports not Updated',
          ]);          
          }
      }
      public function deletesports($id)
      {
        DB::table('sports')->where('id',$id)->delete();
        return redirect()->back();
      }
      public function points()
      {
        $point = DB::table('admin_points')->first();
        return view('Admin.points',compact('point'));
      }
      public  function savepoints(Request $request)
      { 
        // dd($request->all());       
          $validator = Validator::make($request->all(), [
                  // 'athlete'           =>     'required',
                  // 'title'           =>     'required',
                  // 'description'      =>     'required',
                  
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
          if($request->performace){
            $points['performace'] = $request->performace;
          }
          if($request->start_goals){
            $points['start_goals'] = $request->start_goals;
          }
          if($request->visualization){
            $points['visualization'] = $request->visualization;
          }
          if($request->start_selftalks){
            $points['start_selftalks'] = $request->start_selftalks;
          }
         
         
          DB::table('admin_points')->update($points);
        
          if($points){       
            return response()->json([
              'status'=>'1',
              'message'=>'Points Updated',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Points not Updated',
          ]);          
          }
      }
      public function athletedetails($id)
      {
        $athlete = DB::table('athletes')->where('id',$id)->first();
        $mood = DB::table('wellbeings')->where('athlete_id',$id)->orderby('id','desc')->first();
        $seasongoals = DB::table('season_goals')->where('athlete_id',$id)->orderby('id','desc')->first();
        $dreamgoals = DB::table('dreams_goals')->where('athlete_id',$id)->orderby('id','desc')->first();
        $selftalks = DB::table('self_talks')->where('athlete_id',$id)->orderby('id','desc')->first();
        return view('Admin.athletedetails',compact('athlete','mood','seasongoals','dreamgoals','selftalks'));
      }
      public function subscription()
      {
        return view('Admin.subscription');
      }
      public  function savesubscription(Request $request)
      { 
              
          $validator = Validator::make($request->all(), [
                  'name'           =>     'required',
                  'description'    =>     'required',
                  'duration'       =>     'required',
                  'price'          =>     'required',
                  
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
          $subscription['name'] = $request->name;
          $subscription['description'] = $request->description;
          $subscription['duration'] = $request->duration;
          $subscription['price'] = $request->price;

          $subscription_id = DB::table('subscriptions')->insertGetId($subscription);
        
          if($subscription_id){       
            return response()->json([
              'status'=>'1',
              'message'=>'Subscription Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Subscription not Added',
          ]);          
          }
      }
      public function viewsubscription(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $subscription = DB::table('subscriptions')->orderby('id','desc')->get();
      
        if ($request->ajax()) {
        
                return Datatables::of($subscription)
                    ->addIndexColumn()
                  
                    ->addColumn('action', function($row){
                      $actionBtn = '<a href="'.url("Admin/editsubscription").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                      $actionBtn .= '<a href="'.url("Admin/deletesubscription").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                      
                      return $actionBtn;
                      
                  })
                   
                   
           
            
                    ->rawColumns(['action'])
                    ->make(true);
            }
          
              return view('Admin.viewsubscription');
          
           
      }
      public function editsubscription($id)
      {
        $subscription = DB::table('subscriptions')->where('id',$id)->first();
        return view('Admin.editsubscription',compact('subscription'));
      }
      public  function updatesubscription(Request $request,$id)
      { 
              
          $validator = Validator::make($request->all(), [
                  // 'name'           =>     'required',
                  // 'description'    =>     'required',
                  // 'duration'       =>     'required',
                  // 'price'          =>     'required',
                  
        
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
          if($request->name){
            $subscription['name'] = $request->name;
          }
          if($request->description){
            $subscription['description'] = $request->description;
          }
          if($request->duration){
            $subscription['duration'] = $request->duration;
          }
          if($request->price){
            $subscription['price'] = $request->price;
          }
         

          DB::table('subscriptions')->where('id',$id)->update($subscription);
        
          if($subscription){       
            return response()->json([
              'status'=>'1',
              'message'=>'Subscription Updated',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Subscription not Updated',
          ]);          
          }
      }
      public function deletesubscription($id)
      {
        DB::table('subscriptions')->where('id',$id)->delete();
        return redirect()->back();
      }
      public function stripe($user_id,$sub_id,$amount)
      {  
      
       
        return view('stripe',compact('user_id','sub_id','amount'));
      }

       
      public function appointment_payment($user_id,$amount,$start_time = null ,$end_time  = null ,$date = null ,$therapist_id = null,$appointment_id = null)
      {
       
        return view('appointment_payment',compact('user_id','amount',"start_time","end_time","date","therapist_id","appointment_id"));
      }

      public function appointPayment(Request $request)
      {  
         try {
 
           Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
       
        $charge = Stripe\Charge::create ([
                 "amount" => $request->amount * 100,
                 "currency" => "usd",
                 "source" => $request->stripeToken,
                 "description" => "This is test payment",
                 "metadata" => array("customer" => $request->user_id, "appointment" => 'appointment')
                 // "customer" => $request->user_id,
                 // "subscription_id" => $request->sub_id,
         ]);
         Session::flash('success', 'Payment Successful !');
         if($charge)
         { 
           
          // DB::table('athletes')->where('id',$request->user_id)->update(['subscription_id'=>$request->sub_id]);
           
           DB::table('subscription_payments')->insert([
             "transaction_id" => $charge->id,
             "athlete_id" => $request->user_id,
             "subscription_id" => 0,
             "amount" => $request->amount,
             "appointment_for" => '1',
             
           ]);
           DB::table('booking')->insert([
             "start_time" => $request->start_time,
             "end_time" => $request->end_time,
             "therapist_id" => $request->therapist_id,
             "athlete_id" => $request->user_id,
             "date" => $request->date,
             "appointment_id" => $request->appointment_id,
           ]);
          
         }
         return Redirect::to("/success");  
         // return back();
       } 
      catch(\Stripe\Exception\CardException $e) {
       Session::flash('success', 'Invalid Card Details' );
       return back();
       // error_log("A payment error occurred: {$e->getError()->message}");
     } catch (\Stripe\Exception\InvalidRequestException $e) {
       Session::flash('success', 'Invalid Request ' );
       return back();
       // error_log("An invalid request occurred.");
     } catch (Exception $e) {
       Session::flash('success', 'Invalid Request' );
       return back();
       // error_log("Another problem occurred, maybe unrelated to Stripe.");
     }
       //  dd($charge);
        
     }


      public function stripePost(Request $request)
     {  
        try {

          Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      
       $charge = Stripe\Charge::create ([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "This is test payment",
                "metadata" => array("customer" => $request->user_id, "subscription_id" => $request->sub_id)
                // "customer" => $request->user_id,
                // "subscription_id" => $request->sub_id,
        ]);
        Session::flash('success', 'Payment Successful !');
        if($charge)
        { 
          
          DB::table('athletes')->where('id',$request->user_id)->update(['subscription_id'=>$request->sub_id]);
          
          DB::table('subscription_payments')->insert([
            "transaction_id" => $charge->id,
            "athlete_id" => $request->user_id,
            "subscription_id" => $request->sub_id,
            "amount" => $request->amount,
            "appointment_for" => '0',
          ]);
        
         
        }
        return Redirect::to("/success");  
        // return back();
      } 
     catch(\Stripe\Exception\CardException $e) {
      Session::flash('success', 'Invalid Card Details' );
      return back();
      // error_log("A payment error occurred: {$e->getError()->message}");
    } catch (\Stripe\Exception\InvalidRequestException $e) {
      Session::flash('success', 'Invalid Request ' );
      return back();
      // error_log("An invalid request occurred.");
    } catch (Exception $e) {
      Session::flash('success', 'Invalid Request' );
      return back();
      // error_log("Another problem occurred, maybe unrelated to Stripe.");
    }
      //  dd($charge);
       
    }
    public function success()
    {
      return view('success');
    }

    public function chat($sender_id,$receiver_id,$type)
    {  
	      // $chats = DB::table('chats')->where('sender_id',$sender_id)->orWhere('receiver_id',$sender_id)
        // ->orWhere('receiver_id',$sender_id)->orWhere('receiver_id',$receiver_id)->orderBy('created_at','desc')->get();
     
     
        $chats = DB::table('chats')->where(function ($query) use ($sender_id, $receiver_id) {
          $query->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id);
      })->orWhere(function ($query) use ($sender_id, $receiver_id) {
          $query->where('sender_id', $receiver_id)
                ->where('receiver_id', $sender_id);
      })->orderBy('created_at', 'desc')->get();
      
      
        if($type == 'athletes'){
          $data = DB::table('athletes')->where('id',$sender_id)->first();
           DB::table('athletes')->where('id',$sender_id)->update(['message_status'=>0]);
           \DB::table('chats')->where('sender_id',\Auth::guard('therapist')->user()->id)->where('receiver_id',$sender_id)->update(['message_status'=>0,'message_type'=>0]);
        }else{
          $data = DB::table('therapists')->where('id',$sender_id)->first();
          DB::table('therapists')->where('id',$sender_id)->update(['message_status'=>0]);
         // dd($data);
        }

        return view('chat', compact('chats','data','sender_id','receiver_id','type'));
     
    }

    public function sendMessage(Request $request)
    {
    
      $validatedData = $request->validate([
     
        'chat_message' => 'required',
        'sender_id' => 'required',
        'receiver_id' => 'required',

        
    ]);

     if($request->type == 'athletes'){
      $data = DB::table('therapists')->where('id',$request->receiver_id)->update(['message_status'=>1]);
      $user = DB::table('athletes')->where('fcm_token','!=','')->where('device_type','!=','')->where('id',$request->sender_id)->first();
      $therapist = DB::table('therapists')->where('id',$request->receiver_id)->first(); 
      
     // dd($therapist);
      $device_token = $user->fcm_token ?? '';
      $devtype   = $user->device_type ?? '';
      $badge = '0';
      $title = $therapist->name ?? '';
      $req_status="1";
      $description = $request->chat_message;            
      $result = $this->push_notification($device_token,$devtype,$req_status,$badge,$title,$description);   
      $validatedData['message_type']  = 0;
      $validatedData['message_status']  = 0;
     }else{
      $data = DB::table('athletes')->where('id',$request->receiver_id)->update(['message_status'=>1]);
      $athletes = DB::table('athletes')->where('id',$request->receiver_id)->first(); 
      $validatedData['message_type']  = 1;
      $validatedData['message_status']  = 1;

      $user = DB::table('therapists')->where('fcm_token','!=','')->where('id',$request->sender_id)->first();
      // dd($user);
       $device_token = $user->fcm_token ?? '';
       $devtype   = $user->device_type ?? '';
       $badge = '0';
       $title = $athletes->name?? '';
       $req_status="1";
       $description =$request->chat_message;          
       $result = $this->push_notification($device_token,$devtype,$req_status,$badge,$title,$description);     

     }

     $chat = Chat::create($validatedData);

     event(new MessageSent($chat));
    
      return response()->json(['success' => true]);
    }

    public function guide()
    {
      
      return view('guide');
    }
    
    public function subscription_payment(){

      $transactions   = Transactions::with('athlete','subscription')
      ->where('subscription_id','!=',0)->get();

       return view('Admin/view_payment')->with(compact('transactions'));
    }
    
    public function appoiPayment(){

      $transactions   = Transactions::with('athlete','subscription')
      ->where('subscription_id','==',0)->get();

      return view('Admin/appointment_payment')->with(compact('transactions'));

    }

    public function typingNotification(Request $request)
{
    // Return a response indicating the success of the typing notification
    return response()->json(['message' => 'Typing notification received','sender_id'=>$request->sender_id ], 200);
}

public function handleRequest(Request $request)
{
    $message = $request->input('message');
    $sender_id = $request->input('sender_id');
    
    $client = new Client();

    try {
        $response = $client->post('http://localhost:3000/process-ajax?message='.$message.'&sender_id='.$sender_id, [
            'form_params' => [
                'message' => $message,
            ],
        ]);

        $nodeResponse = $response->getBody()->getContents();
      //  $receivedMessage = $nodeResponse[0]->message;
        return $nodeResponse;
    } catch (RequestException $e) {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();
            return response()->json(['error' => $errorBody], $statusCode);
        } else {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


     public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
          $request->validate([
            'email' => 'required|email|exists:therapists',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
          ]);

        \Mail::send('emails.forget_password', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    }

    public function showResetPasswordForm($token) { 

     
      return view('auth.passwords.reset', ['token' => $token]);
   }

   public function submitResetPasswordForm(Request $request)
   {
       $request->validate([
           'email' => 'required|email|exists:therapists',
           'password' => 'required|string|min:6|confirmed',
           'password_confirmation' => 'required'
       ]);

       $updatePassword = DB::table('password_resets')
                           ->where([
                             'email' => $request->email, 
                             'token' => $request->token
                           ])
                           ->first();

       if(!$updatePassword){
           return back()->withInput()->with('error', 'Invalid token!');
       }

       $user = DB::table('therapists')->where('email', $request->email)
                   ->update(['password' => bcrypt($request->password)]);

       DB::table('password_resets')->where(['email'=> $request->email])->delete();
    
       return redirect()->route('therapist_login_view')->with('sucesssmessage', 'Your password has been changed!');
   }

   public function  AdminchangePassword(Request $request){
  
        if ($request->isMethod('post')) {
          
          $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
            

        ]);
        if ( $validator->fails()) { 
          return response()->json([
              'status'=>'0',
              'message'=>$validator->errors()->first(),
          ]);          
      } 
      $user = DB::table('users')->where('email', $request->email)
      ->update(['password' => bcrypt($request->password)]);
      
      return response()->json([
        'status'=>'1',
        'message'=>'Password chanage successfully',
        ]);  

      }

    return view('Admin/changePassword');
  
  }






}
