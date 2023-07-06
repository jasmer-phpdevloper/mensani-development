@extends('Admin.layouts.App')
@section('addplan','menu-open')
{{-- @section('dashboard_active','active') --}}
<style>
  nav#sidebar {
    width: 20% !important;
    position: fixed;
    overflow-y: auto;
    height: 100%;
    visibility: visible;
}

.stretch-card {
  width: 57% !important;
  margin: 0px auto;
  position: relative;
  top: 100px;
  left: 10%;

}
.card-body{
background: #000 !important;
}
  #active{
    border: none;color: blue;font-size: 22px;
  }
  #inactive{
    border: none;color: red;font-size: 22px;
  }
  .select2-container--default .select2-selection--multiple {
    background-color: #2A3038 !important;
   
}
body, html {
  
  overflow: initial !important;
}  
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Today's Plan</h4>
      
      <form class="forms-sample" id="form">
        @csrf
      
        <div class="form-group">
          <label for="exampleInputName1">Image</label>
          <input type="file" class="form-control" id="image" name="image" >
        </div>
        <input type="hidden"  id="image" name="oldimage" value="{{$plan->image}}">
        <img src="{{$plan->image}}" style="height: 50px;width:50px;">
        <div class="form-group">
          <label for="">Message From</label>
          <select class="form-control" name="message_from" id="message_from">
            <option value="">Select</option>
            <option value="Admin" {{$plan->message_from == "Admin"  ? 'selected' : ''}}>Admin</option>
            <option value="Coach" {{$plan->message_from == "Coach"  ? 'selected' : ''}}>Coach</option>
            <option value="Therapist" {{$plan->message_from == "Therapist"  ? 'selected' : ''}}>Therapist</option>
          </select>
        </div>
        <input type="hidden"  id="plan_id" name="plan_id" value="{{$plan->id}}">
        <div class="form-group">
          <label for="exampleInputName1">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{$plan->name}}">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Description</label>
          <textarea id="description" class="form-control"   name="description" rows="4" cols="50">{{$plan->description}} </textarea>
         
        </div>
          
        <div class="row">
          <div class="form-group">
            <label for="exampleInputName1">From Time</label>
            <input type="time" class="form-control" id="from_time" name="from_time" value="{{$plan->from_time}}">
          </div>
          <div class="form-group">
            <label for="exampleInputName1">To Time</label>
            <input type="time" class="form-control" id="to_time" name="to_time" value="{{$plan->to_time}}">
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Date</label>
          <input type="date" class="form-control" id="date" name="date" value="{{$plan->date}}">
        </div>
        <button type="submit" class="btn btn-primary me-2" id="btnValue">Submit</button>
       
      </form>
    </div>
  </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js"></script>
<script src="/public/tinymce/js/tinymce/tinymce.min.js"></script>

<script>
 $(document).ready(function () {
  tinymce.init({
    selector: '#description',
    plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table contextmenu paste"
  ],
  toolbar: "bullist numlist"
});
});
</script>
<script>
 
 $(document).ready(function () {
// alert('hh');

  $('#form').on('submit', function(event){
      event.preventDefault();
    
     const fd = $('#form').serialize();
    var id = $("#plan_id").val();
     $.ajax({
       type: "POST",
        url: "{{url('Admin/updateplan')}}" + '/' + id,
        dataType: 'json',
        data:new FormData(this),
        cache : false,
        processData: false,
        contentType: false,
           processData: false,
           success: function(data) {
        
             if(data.status == 1)
            {
           
              Swal.fire(
                'Good job!',
                'Plan Updated!',
                'success'
              )
              location.reload();
            }
            if(data.status == 0)
            {
              toastr["error"](data.message, "Error");
            }
        },
       });
      });
    });
    </script>
   <script>
    $(document).ready(function(){
      
    $(window).on('load', function(){
     
      $(".nav-item").removeClass("active");
      $(".nav-link ").removeClass("active");
      $(".nav-link ").addClass("collapsed");
      // $(".nav-link ").setAttribute("aria-expanded", "false");
      $(".menu-items > div").removeClass("show");
      $(".nav-item").closest('div').removeClass("show");
      var show = $(".nav-item").closest('div').find(".show");
    
      if(show){
        $(".nav-item").closest('div').removeClass("show");
  
      }else{
        $(".nav-item").closest('div').removeClass("show");
  
      }
   
   
    });
  }); 
   </script>   