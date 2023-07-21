@extends('Admin.layouts.App')
@section('sports','active')
@section('viewsport','active')
@section('sport','show')
<style>
    nav#sidebar {
      width: 20% !important;
      position: fixed;
    overflow-y: auto;
    height: 100%;
    visibility: visible;
  }
  
  .form-control {
    height: inherit;
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

label {
    margin-bottom: 12px;
}

.card {
    border: 2px solid #191c24;
}

  #active{
    border: none;color: blue;font-size: 22px;
  }
  #inactive{
    border: none;color: red;font-size: 22px;
  }
  #loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.3) url("{{ url('public/assets/images/Spin-1.4s-78px.gif') }}") no-repeat center center;
        z-index: 10000;
    }

  
  
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Sports</h4>
      
      <form class="forms-sample" id="form">
        @csrf
       
        <div class="form-group">
          <label for="exampleInputName1">Sports</label>
          <input type="text" class="form-control" id="sport" name="sport" value="{{$sport->sport}}">
        </div>
        <input type="hidden"  id="sport_id" name="sport_id" value="{{$sport->id}}">
       
        <button type="submit" class="btn btn-primary me-2">Update</button>
        <div id="loader" style="display:none;" style="color:white"></div>
       
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

<script>
 
 $(document).ready(function () {

  $('#form').on('submit', function(event){
      event.preventDefault();
      $("#loader").attr("style", "display:block");
     const fd = $('#form').serialize();
    var id = $("#sport_id").val();
     $.ajax({
       type: "POST",
        url: "{{url('Admin/updatesports')}}" + '/'  + id,
        dataType: 'json',
        data:new FormData(this),
        cache : false,
        processData: false,
        contentType: false,
           processData: false,
           success: function(data) {
        
             if(data.status == 1)
            {
                $("#loader").attr("style", "display:none");
              Swal.fire(
                'Good job!',
                'Sports Updated!',
                'success'
              )
              location.reload();
            }
            if(data.status == 0)
            { 
              $("#loader").attr("style", "display:none");
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

$(document).ready(function() {
      // Restrict input to alphabetic characters
      $('#sport').on('input', function() {
        var sanitizedInput = $(this).val().replace(/[^a-zA-Z]/g, '');
        $(this).val(sanitizedInput);
      });
    });

      $(document).ready(function() {
     
      setTimeout(function () {
        $(".menu-items").removeClass('active');
        $(".collapse").removeClass('show');
       }, 500)
   });
 </script>   

