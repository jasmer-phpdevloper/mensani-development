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

use Carbon\Carbon;

class TherepistController extends Controller
{
    

    public function __construct()
    {
       // $this->zoomApi = $zoomApi;
    }

    public function dashboard()
    { 
        $athletes = DB::table('athletes')->count();
        $sports = DB::table('sports')->count();
        $subscription = DB::table('subscriptions')->count();
       
        $currentDateTime = now()->format('Y-m-d H:i:s');  
        
        $todayAvailbelityTime = \DB::table('appointments')->where('therapist_id',Auth::guard('therapist')->user()->id)->where('day',date('l'))->latest()->first(); 
        $bookingCount= \DB::table('booking')->where('therapist_id',Auth::guard('therapist')->user()->id)->count(); 
        $PastbookingCount= \DB::table('booking')
        ->where('therapist_id',Auth::guard('therapist')->user()->id)
        ->whereRaw(DB::raw("CONCAT(`date`, ' ', `end_time`) < '{$currentDateTime}'"))
        ->count();   

        $FuturebookingCount= \DB::table('booking')
        ->where('therapist_id',Auth::guard('therapist')->user()->id)
        ->whereRaw(DB::raw("CONCAT(`date`, ' ', `start_time`) > '{$currentDateTime}'"))
        ->count();  
       
       
       
        return view('Therapist.dashboard',compact('athletes','sports','subscription','todayAvailbelityTime','bookingCount','PastbookingCount','FuturebookingCount'));
    }
    public function therepist_login_view()
    {
       
         if(Auth::guard('therapist')->user()){
            return redirect('Therapist/dashboard');
          }
        return view('Therapist.Auth.therepist_login');        
    }

    public function therepist_login(Request $request){
        $request->validate([
           'email'          => 'required',
           'password'       => 'required',
       ]);
     
       $credentials = [
           'email' => $request['email'],
           'password' => $request['password'],
       ];  
               
          if(Auth::guard('therapist')->attempt($credentials)){
            
            $user  = Auth::guard('therapist')->user()->status;
           
            if($user == 0){
            
              Auth::guard('therapist')->logout();
          
              return redirect()->route('therapist_login_view')->with('errormessage', 'Your account suspended please contact to superadmin!');

            }   


           return redirect()->route('therepist_dashboard');
          }
       
       else{
           return back()->with('errormessage', 'Please Enter Valid Credentials!');
       }
      
      }

      public function logout()
      {
       Auth::guard('therapist')->logout();
       return redirect()->route('therapist_login_view');
      }

      public function profile()
      {
       $therapist = Auth::guard('therapist')->user() ;
       $country_codes = DB::table('countries')->get();
       $sports = DB::table('sports')->get();
       $states = DB::table('states')->get();
       return view('Therapist.therapist.profile')->with(compact('country_codes','sports','therapist','states'));
           
      }

      public function update_therepist(Request $request)
      {

        $validator = Validator::make($request->all(), [
                 
          //'first_name'    =>     'required',
         // 'last_name'      =>     'required',  
         // 'gender'      =>     ['required','in:male,female'],  
          //'email' => 'required|email|unique:therapists,email,'.$request->id,
          //'country'      =>     'required',
         // 'sport'      =>     'required',
          'license'      =>     'required',
          'degree'      =>     'required',
          
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
          if(!empty($input['password'])){
            $input['password']  = bcrypt($request->password); 
          }else{
            $input['password']  = $therapists->password;
          }
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
      
      public function notification()
      {
        $athletes = DB::table('athletes')->orderby('id','desc')->get();
        return view('Therapist.notification',compact('athletes'));
      }
      public  function save_notification(Request $request)
      { 
        // dd($request->athlete[0]);       
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
              // dd($athlete);
                $notification_id =  DB::table('notifications')->insertGetId([
                  'athlete_id'    => $athlete,                      
                  'title'       => $request->get('title'),
                  'description'       => $request->get('description'),           
                             
                ]);
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
        
        
          if($notification_id){       
            return response()->json([
              'status'=>'1',
              'message'=>'Notification Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Notification not Added',
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
       
        $notification = DB::table('notifications')->where('user_type',"Therapist")
        ->where('athlete_id',Auth::guard('therapist')->user()->id)->orderby('id','desc')->get();
      
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
          
              return view('Therapist.view_notification');
          
           
      }
    
      public function deletenotification($id)
      {
        DB::table('notifications')->where('id',$id)->delete();
        return redirect()->back();
      }
     
     
      public function support()
      {
        return view('Therapist.support');
      }
      public  function save_support(Request $request)
      { 
            
          $validator = Validator::make($request->all(), [
                 
           'title'    =>     'required',
           'thumbnail'      =>     'required',  
           'price'      =>     'required',  
           'video'      =>     'required',  
           //'support_type'      =>     'required',                 
        
          ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
         
          $support['title'] = $request->get('title');
       
          if($request->hasfile('thumbnail'))
          {
              $file = $request->thumbnail;
              $path = storage_path().'/support_thumbnail/';
              File::makeDirectory($path, $mode = 0777, true, true);
              $imagePath = storage_path().'/support_thumbnail/';         
              $post_image        = time().'.'.$file->getClientOriginalName();
              $image_url          = url('/').'/storage/support_thumbnail/'.'/'. $post_image;   
              $file->move($imagePath, $post_image);
          }
          if($request->thumbnail){
          $support['thumbnail']= $image_url;
          }
          $support['price'] = $request->get('price');
          $support['user_id']  = Auth::guard('therapist')->user()->id;
          $support['user_type']  = "Therapist";
         
          if($request->hasfile('video'))
          {
              $file = $request->video;
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
              'message'=>'Content Added',
          ]);                  
          }else{
            return response()->json([
              'status'=>'0',
              'message'=>'Content not Added',
          ]);          
          }
      }
      public function view_support(Request $request)
      {
        DB::statement(DB::raw('set @rownum=0'));
       
        $support = DB::table('supports')->where('user_id',Auth::guard('therapist')->user()->id)->orderby('id','desc')->get();
       
        if ($request->ajax()) {
         
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
                    $actionBtn = '<a href="'.url("Therapist/editsupport").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                    $actionBtn .= '<a href="'.url("Therapist/deletesupport").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                    
                    return $actionBtn;
                    
                })
                   
           
            
                    ->rawColumns(['thumbnail','video_data','action'])
                    ->make(true);
            }
          
              return view('Therapist.view_support');
      }
      public function deletesupport($id)
      { 
        $supports = DB::table('supports')->where('id', $id)->first();
        $fileUrl = $supports->video;
        $fileName = basename($fileUrl);
      
        $directory = storage_path('supportvideo').'/'.$fileName; // Replace with the actual directory path where files are stored
       
        $files = File::delete($directory);
    
        DB::table('supports')->where('id', $id)->delete();  
         

        return redirect()->back()->with('message', 'Content deleted successfully');
      }
      public function editsupport($id)
      {
        $support = DB::table('supports')->where('id',$id)->first();
        return view('Therapist.editsupport',compact('support'));
      }
      public  function updatesupport(Request $request,$id)
      { 
              
          $validator = Validator ::make($request->all(), [
                 
            'title'    =>     'required',
            'price'      =>     'required',  
                       
        
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

    public function view_feedback(Request $request)
    {
      DB::statement(DB::raw('set @rownum=0'));
      
      $review_feedback = DB::table('review_feedback')->where('therapist_id',Auth::guard('therapist')->user()->id)->orderby('id','desc')->get();
    
      if ($request->ajax()) {
      
              return Datatables::of($review_feedback)->addIndexColumn()->make(true);
          }
        
            return view('Therapist.view_feedback');
        
    }

    // public function createMeeting(Request $request)
    // {
    //     // Validate the request data here

    //     $data = [
    //         'topic' => $request->input('topic'),
    //         'type' => 2,
    //         'start_time' => $request->input('start_time'),
    //         // Add any additional parameters you need
    //     ];

    //     $response = $this->zoomApi->createMeeting($data);

    //     // Handle the Zoom API response here

    //     return response()->json($response);
    //   }

      public function appointmentTime(Request $request)
      {  
        
        DB::statement(DB::raw('set @rownum=0'));
         
        $appointments = DB::table('appointments')->where('therapist_id',Auth::guard('therapist')->user()->id)->orderby('id','asc')->get();
      
        if ($request->ajax()) {
        
                return Datatables::of($appointments)->addIndexColumn()
                ->addColumn('action', function($row){
                  $actionBtn = '<a href="'.url("Therapist/edit-appointment-time").'/'.$row->id.'"><i class="fa-solid fa-square-pen" style="font-size:25px;"></i></a>';
                  $actionBtn .= '<a href="'.url("Therapist/deleteAppointmentTime").'/'.$row->id.'"><i class="fa-solid fa-trash-can" style="color:red; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to delete this?`)" ></i></a>';
                  $actionBtn .= '<a href="'.url("Therapist/clear-appointment-time").'/'.$row->id.'"><i class="fa-solid fa-eraser" style="color:white; font-size:23px;margin-left: 6px;" onclick="return confirm(`Are you sure to clear this?`)" ></i></a>';
                  
                  return $actionBtn;
                  
              })
              ->rawColumns(['action'])->make(true);
            }
         return view('Therapist/appointment/appointmentTime');
      }

      public function addAppointmentTime()
      {
        return view('Therapist/appointment/addAppointmentTime');
      }

      public function saveAppointmentTime(Request $request)
      {  
        
              $validator = Validator::make($request->all(), [
                  //  'day'           =>     'required',
                  //  'start_time'    =>     'required|array',
                  // 'end_time'       =>     'required|array',
                  //  'start_time.*' => 'required|date_format:H:i',
                  //   'end_time.*' => 'required|date_format:H:i|after_or_equal:start_time.*',
                 // 'start_time.*'    =>     'required',
                //  'end_time.*'      =>     'required',
                
              ]);
              if ( $validator->fails()) { 
                return response()->json([
                    'status'=>'0',
                    'message'=>$validator->errors()->first(),
                ]);          
            } 
        
            $all = $request->all();

            $startTimes = $request->start_time ;
           // dd($startTimes);
            $endTimes = $request->end_time;



         
            foreach ($startTimes as $day => $startTime) {
             
             
               $endTime = $endTimes[$day];

               $startTime1 = new \DateTime($startTime);
               
               $endTime1 = new \DateTime($endTime);

               $timeIn24HourFormatStartTime = $startTime1->format('H:i');
               $timeIn24HourFormatEndTime = $endTime1->format('H:i');
              
               if(!empty($endTime ) ||   !empty($startTime)){

                $startTimestamp = strtotime($startTime);
                $endTimestamp = strtotime($endTime);


                if ($startTimestamp > $endTimestamp) {

                  return response()->json([
                    'status'=>'0',
                    'message'=>'Start time is greater than end time of'."   ".$day,
                  ]); 
                
                }

           

                if (!($startTime1->format('H') >= 0  && $startTime1->format('H') < 12 )&&($endTime1->format('H') >= 0  && $endTime1->format('H') < 12)) {
                
                    return response()->json([
                      'status'=>'0',
                      'message'=>'End time cannot be select after 12 PM because a new day begins'."   ".$day,
                    ]);  
              
                  }
            
              
                  $diff = $startTime1->diff($endTime1,true); 
              
                  if($diff->format('%H%') == 00){
                    
                    return response()->json([
                      'status'=>'0',
                      'message'=>'Your slot should be of 1 hour or more than 1 hour'."   ".$day,
                    ]);  
    
                  }
            
                  if($diff->format('%I%') == 30){
                  
                    return response()->json([
                      'status'=>'0',
                      'message'=>'There is 30 mins extra in your slot plz select full hours'."   ".$day,
                    ]); 
      
                  }
                }
           


               $appointments =  DB::table('appointments')->where('day',$day)->where('therapist_id',Auth::guard('therapist')->user()->id)->first();
            
               if(empty($appointments)){

                $appointments =  DB::table('appointments')->insertGetId([
                  'therapist_id'    => Auth::guard('therapist')->user()->id,                      
                  'start_time'       => $startTime,
                  'end_time'       => $endTime, 
                  'day'  => $day ,            
                            
                ]);

              }else{
                
                $appointments =  DB::table('appointments')->where('id',$appointments->id)->update([
                              
                  'start_time'       => $startTime,
                  'end_time'       => $endTime, 
                        
                ]);

              }

            }
           
   
            return response()->json([
                'status'=>'1',
              'message'=>'Appointment Time Added Successfully',
            ]);  
     
   
     }

    public function editAppointmentTime($id)
    {
        $appointment =  DB::table('appointments')->where('id',$id)->first();
        
        return view('Therapist/appointment/editAppointmentTime')->with(compact('appointment'));
    }

     public function updateAppointmentTime(Request $request)
     {
                   $validator = Validator::make($request->all(), [
                   
                     'start_time' => 'required|date_format:H:i',
                     'end_time' => 'required|date_format:H:i|after:start_time',
                     
                
             ]);
            if ( $validator->fails()) { 
              return response()->json([
                  'status'=>'0',
                  'message'=>$validator->errors()->first(),
              ]);          
            } 

            $all = $request->all();

            $startTime = new \DateTime($request->start_time);
            $endTime = new \DateTime($request->end_time);
            $timeIn24HourFormatStartTime = $startTime->format('H:i');
            $timeIn24HourFormatEndTime = $endTime->format('H:i');
            
            if (!($startTime->format('H') >= 0  && $startTime->format('H') < 12)&&($endTime->format('H') >= 0  && $endTime->format('H') < 12)) {

                return response()->json([
                  'status'=>'0',
                  'message'=>'End time cannot be select after 12 PM because a new day begins',
                ]);  

            }

            $diff = $startTime->diff($endTime,true); 
           
            if($diff->format('%H%') == 00){
              
              return response()->json([
                'status'=>'0',
                'message'=>'Your slot should be of 1 hour or more than 1 hour',
              ]);  

            }

            if($diff->format('%I%') == 30){
              
              return response()->json([
                'status'=>'0',
                'message'=>'There is 30 mins extra in your slot plz select full hours',
              ]); 


            }
           
              $appointments =  DB::table('appointments')->where('id',$request->id)->update([
                            
                'start_time'       => $timeIn24HourFormatStartTime,
                'end_time'       => $timeIn24HourFormatEndTime, 
                        
              ]);

            return response()->json([
              'status'=>'1',
              'message'=>'Appointment Time Updated Successfully',
            ]);  
    }

    public function deleteAppointmentTime($id)
    { 
          $appointments =  DB::table('appointments')->where('id',$id)->delete();
          return redirect()->back()->with('message', 'Data deleted successfully');
    }

    public function clearAppointmentTime($id)
    {
      $appointments =  DB::table('appointments')->where('id',$id)->update(['start_time'=>'00:00','end_time'=>'00:00']);
      return redirect()->back()->with('message', 'Data cleared successfully');
    }

    public function chatListing()
    { 
      $chats = DB::table('chats')->where(function ($query) {
        $query->where('receiver_id', Auth::guard('therapist')->user()->id);
    })->orWhere(function ($query){
        $query->where('sender_id',  Auth::guard('therapist')->user()->id);
              //->where('receiver_id', $sender_id);
    })->get();
    
      $athlete_id =[];
      foreach($chats as $value){
        $athlete_id[] =  $value->sender_id;
        $athlete_id[] =  $value->receiver_id;

      }

      $result = array_diff($athlete_id, [Auth::guard('therapist')->user()->id]);

    // dd($result);

      $athletes = DB::table('athletes')->whereIn('id',$result)->orderby('updated_at','desc')->get();

      return view('chatListing')->with(compact('athletes'));
    }

   Public function changePassword(Request $request){
   
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
  $user = DB::table('therapists')->where('email', $request->email)
  ->update(['password' => bcrypt($request->password)]);
   
  return response()->json([
    'status'=>'1',
    'message'=>'Password chanage successfully',
    ]);  

   }

    return view('Therapist/changePassword');
   }

}
