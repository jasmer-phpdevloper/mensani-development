@extends('Therapist.layouts.App')
@section('feedback','menu-open')
<style>
  nav#sidebar {
     width: 20% !important;
     position: fixed;
   overflow-y: auto;
   height: 100%;
   visibility: visible;
 }
 
 .stretch-card {
     width: 80% !important;
     margin: 0px 0px 0px auto;
   overflow-y: auto;
   visibility: visible;
     
 }
 #active{
   border: none;color: blue;font-size: 22px;
 }
 #inactive{
   border: none;color: red;font-size: 22px;
 }
 div#Table_ID_filter {
   float: right;
}
.dataTables_wrapper label {
   font-size: .8125rem;
   display: flex;
   align-items: center;
   margin-bottom: 10px;
}
div#Table_ID_length {
   float: left;
}
.dataTables_wrapper .dataTables_length select {
   min-width: 51px;
   min-height: 24px;
   text-align: center;
   margin-left: .25rem;
   padding: 0px 15px;
   margin-right: .25rem;
}
input[type="search"] {
   margin-left: 5px;
}
div#Table_ID_paginate {
   display: flex;
   justify-content: flex-start;
   align-items: center;
   gap: 1px;
   margin-bottom: 8px;
}

div#Table_ID_paginate a {
   color: white;
   text-decoration: none;
   border: 1px solid;
   padding: 2px 13px;
   margin: 0px 4px;
}
.alert-box {
   position: relative;
   margin: 0px auto;
   width: 36%;
   text-align: center;
}
.chat-name h3 {
   margin: 0px;
   font-size: 19px;
   font-weight: 400;
   position: relative;
}
.chat-name h3 a{
   color: #fff;
   margin-right: 6px;
}
.chat-name h3 span {
   background-color: red;
   width: 9px;
   height: 9px;
   position: absolute;
   border-radius: 20px;
}
.notification {
 background-color: #555;
 color: white;
 text-decoration: none;
 padding: 15px 26px;
 position: relative;
 display: inline-block;
 border-radius: 2px;
}

.notification:hover {
 background: red;
}

.notification .badge {
 position: absolute;
 top: -10px;
 right: -10px;
 padding: 5px 10px;
 border-radius: 50%;
 background: red;
 color: white;
}
   </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="row">
        @if(session()->has('message'))
        <div class=" col-6 alert alert-success alert-box">
          
         <h4> {{ session('message') }}</h4>
      </div>
      
      @endif
      @php
      session()->forget('chat_url');
      $currenturl = url()->full();
      session()->put('chat_url', $currenturl);
      @endphp
    </div>
      <div class="card-body">
        <h4 class="card-title">View Chat</h4>
       
        <div class="table-responsive">
          <table id="Table_ID" class="table table-bordered" >
            <thead>
              <tr>
                <th style="display:none">sds</th>
                   
                <th> Athletes </th>
                <th> Chat </th>
                <th> Action </th>
              </tr>
            </thead>
            <tbody>
              <?php $i =1; ?>
              @foreach($athletes as $value)
              <tr>
              <td style="display:none">{{strtotime($value->updated_at) }}</td>  
           
              <td>     <div class="col-8">
                  <div class="d-flex align-items-center">

                      <?php  $chatCount = \DB::table('chats')->where('sender_id',\Auth::guard('therapist')->user()->id)->where('receiver_id',$value->id)->where(['message_status'=>1,'message_type'=>1])->count();
                            
                      ?>
                     
                     <img class="" src="{{$value->image}}" onerror="this.onerror=null;this.src='https://img.icons8.com/fluency/48/gender-neutral-user.png'" style="height:50px;width:50px;object-fit: cover;">
                      <div class="flex-grow-1 ms-3 chat-name">
                        <h3><a href="{{url("chat").'/'.$value->id.'/'.\Auth::guard('therapist')->user()->id.'/'.'athletes'}}">{{$value->name}}</a>@if($chatCount >= 1) <span></span> @endif</h3>
                        
                      </div>
                  </div>
                      
                  </div>
              </div></td>
              <td><a href="{{url("chat").'/'.$value->id.'/'.\Auth::guard('therapist')->user()->id.'/'.'athletes'}}"><i class="fas fa-comments" style="font-size:37px;"></i></a></td>
              <td><a href="http://meet.google.com/new" class="btn-btn-primary" target="_blank"> <i class="fa fa-video-camera" aria-hidden="true"></i> Google Meet</a></td>
              </tr>
              <?php $i++;?>
              @endforeach 
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
   
    $(document).ready( function () {
        // alert('hh');
   var table = $('#Table_ID').DataTable({
       processing: true,
  
       order: [ [0, 'desc'] ]
   });
        table.on('draw.dt', function() {
        
       });
     });
   

 
       </script>
     