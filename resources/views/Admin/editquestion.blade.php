@extends('Admin.layouts.App')
@section('questions','active')
@section('view_questions','active')
@section('quest','show')
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
  input#question {
    width: 50%;
}
textarea#answer {
    width: 50%;
}
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Question</h4>
      
      <form class="forms-sample" id="form">
        @csrf
        <div class="form-group">
          <label for="exampleInputName1">Question</label>
          <input type="text" class="form-control" id="question" name="question" value="{{$question->question}}">
        </div>
        <input type="hidden"  id="question_id" name="question_id" value="{{$question->id}}">
        <div class="form-group">
          <label for="exampleTextarea1">Answer</label>
          <textarea class="form-control" id="answer" name="answer" rows="4" cols="40">{{$question->answer}}</textarea>
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
<script>
 
 $(document).ready(function () {
// alert('hh');
  
  $('#form').on('submit', function(event){
      event.preventDefault();
    
     const fd = $('#form').serialize();
     var id = $("#question_id").val();
     $.ajax({
       type: "POST",
        url: "{{url('Admin/updatequestion')}}" + '/' + id,
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
                'Question Updated!',
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
