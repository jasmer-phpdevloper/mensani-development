@extends('Admin.layouts.App')
@section('question','menu-open')
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
      margin: 0px 0px 0px auto;
    overflow-y: auto;
    visibility: visible;
      
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
  .stretch-card  .card #form {
    display: grid;
    grid-template-columns: auto auto auto;
    align-items: center;
    justify-content: space-between;
}
.stretch-card .form-group{ 
 width: 302px;
 margin-bottom: 40px;
}
.form-group label , .form-group input {
    margin: 5px 0px;
}
.form-group input {
    padding: 0px;
}

.form-group {
    border-style: solid;
    padding: 18px;
}
.toast-error {
    background-color: #3533a7 !important;
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
    {{-- 1 --}}
    <div class="card-body">
      <h4 class="card-title">Add Question</h4>
      
      <form class="forms-sample" id="form">
        @csrf
        {{-- 1 --}}
        <div class="form-group">
          <label for="exampleInputName1">Question1</label>
          <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 1">
          <label for="exampleTextarea1">Answer1</label>
          <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 1"></textarea>
        </div>
           <!--2 -->
          <div class="form-group">
            <label for="exampleInputName1">Question2</label>
            <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 2">
               <label for="exampleTextarea1">Answer2</label>
            <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 2"></textarea>
          </div>
        <!--3 -->
        <div class="form-group">
          <label for="exampleInputName1">Question3</label>
          <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 3">
             <label for="exampleTextarea1">Answer3</label>
          <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 3"></textarea>
        </div>

            <!--4 -->
          <div class="form-group">
            <label for="exampleInputName1">Question4</label>
            <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 4">
               <label for="exampleTextarea1">Answer4</label>
            <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 4"></textarea>
          </div>
        <!--5 -->
        <div class="form-group">
          <label for="exampleInputName1">Question5</label>
          <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 5">
             <label for="exampleTextarea1">Answer5</label>
          <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 5"></textarea>
        </div>

            <!--6 -->
          <div class="form-group">
            <label for="exampleInputName1">Question6</label>
            <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 6">
               <label for="exampleTextarea1">Answer6</label>
            <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 6"></textarea>
          </div>
          <div class="form-group">
            <label for="exampleInputName1">Question7</label>
            <input type="text" class="form-control" id="question" name="question[]" placeholder="Enter Question 7">
            <label for="exampleTextarea1">Answer7</label>
            <textarea class="form-control" id="answer" name="answer[]" rows="4" cols="40" placeholder="Enter Answer 7"></textarea>
          </div>
        
            <button type="submit" class="btn btn-primary me-2" style="position: relative;top: 108px;">Submit</button>
             <div id="loader" style="display:none;" style="color:white"></div> 
      
       
      </form>
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
      $("#loader").attr("style", "display:block")
     $.ajax({
       type: "POST",
        url: "{{url('Admin/save_questions')}}",
        dataType: 'json',
        data:new FormData(this),
        cache : false,
        processData: false,
        contentType: false,
           processData: false,
           success: function(data) {
        
             if(data.status == 1)
            {
                $("#loader").attr("style", "display:none")
              Swal.fire(
                'Good job!',
                'Question Added!',
                'success'
              )
              location.reload();
            }
            if(data.status == 0)
            {    $("#loader").attr("style", "display:none")
              toastr["error"](data.message, "Error");
            }
        },
       });
      });
    });
    </script>
