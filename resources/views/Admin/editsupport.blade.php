@extends('Admin.layouts.App')
@section('support','menu-open')
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
      width: 80% !important;
      position: relative;
    /* top: 100px; */
    left: 20%;
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
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Support</h4>
      
      <form class="forms-sample" id="form">
        @csrf
      
        <div class="form-group">
          <label for="exampleInputName1">Title</label>
          <input type="text" class="form-control" id="title" name="title" value="{{$support->title}}">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Thumbnail</label>
          <input type="file" class="form-control" id="thumbnail" name="thumbnail" >
        </div>
        <input type="hidden"  id="thumbnail" name="oldthumbnail" value="{{$support->thumbnail}}">
        <img src="{{$support->thumbnail}}" style="height:50px;width:50px;">
        <div class="form-group">
        <input type="radio" id="free" name="price" value="Free" {{ $support->price =="Free" ? 'checked':''}}>
        <label for="free" style="margin-top: 4px;">Free</label>
        <input type="radio" id="paid" name="price" value="Paid" {{ $support->price =="Paid" ? 'checked':''}}>
        <label for="paid" style="margin-top: 4px;">Paid</label>
        </div>
        <input type="hidden" id="support_id" name="support_id" value="{{$support->id}}">
        <div class="form-group">
          <label for="exampleInputName1">Video</label>
          <input type="file" class="form-control" id="video" name="video" accept="video/*">
        </div>
        <input type="hidden" class="form-control" id="video" name="oldvideo" value="{{$support->video}}">
        <video controls style="height:50px;width:50px"> <source src="{{$support->video}}" type="video/mp4"><source src="{{$support->video}}" type="video/ogg"> </video>
        <div class="form-group">
          <input type="radio" id="educational_support" name="support_type" value="Educational Support" {{ $support->support_type =="Educational Support" ? 'checked':''}}>
        <label for="free" style="margin-top: 4px;">Educational Support</label>
        <input type="radio" id="mental_support" name="support_type" value="Mental Training Support" {{ $support->support_type =="Mental Training Support" ? 'checked':''}}>
        <label for="paid" style="margin-top: 4px;">Mental Trainning Support</label>
        </div>
        <button type="submit" class="btn btn-primary me-2">Update</button>
       
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
<script>
 
 $(document).ready(function () {
// alert('hh');

  $('#form').on('submit', function(event){
      event.preventDefault();
    
     const fd = $('#form').serialize();
    var id = $("#support_id").val();
     $.ajax({
       type: "POST",
        url: "{{url('Admin/updatesupport')}}" + '/' + id,
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
                'Support Updated!',
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
