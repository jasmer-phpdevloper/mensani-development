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
.stretch-card .form-group label {
  margin-right: 12px;
}
.stretch-card .InputName {
  margin-bottom: 7px;
}
.toast-error {
    background-color: #3533a7 !important;
}
.secondrow {
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.secondrow  .form-group {
    width: 48%;
}
.row {
    display: flex;
    width: 100%;
    margin: 0px auto !important;
    justify-content: space-between;
}

.row  .form-group {
    width: 48%;
 padding: 0px !important
}
body, html {
  
  overflow: initial !important;
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
      <h4 class="card-title">Manage Today's Plan</h4>
      
      <form class="forms-sample" id="form">
        @csrf
      
        <div class="form-group">
          <label for="exampleInputName1">Image</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/*" >
        </div>
        {{-- <div class="form-group">
          <label for="">Message From</label>
          <select class="form-control" name="message_from" id="message_from">
            <option value="">Select</option>
            <option value="Admin">Admin</option>
            <option value="Coach">Coach</option>
            <option value="Therapist">Therapist</option>
          </select>
        </div>
      
        <div class="form-group">
          <label for="exampleInputName1">Name</label>
          <input type="text" class="form-control" id="name" name="name" >
        </div> --}}
        <div class="secondrow">
          <div class="form-group">
                   <label for="">Message From</label>
                   <select class="form-control" name="message_from" id="message_from">
                     <option value="">Select</option>
                     <option value="Admin">Admin</option>
                     <option value="Coach">Coach</option>
                     <option value="Therapist">Therapist</option>
                   </select>
                 </div>   
         <div class="form-group">
                   <label for="exampleInputName1">Name</label>
                   <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                 </div>
         </div>
        <div class="form-group">
          <label for="exampleInputName1">Description</label>
          <textarea id="description" class="form-control"   name="description" rows="4" cols="50"> </textarea>
         
        </div>
          
        <div class="row">
          <div class="form-group">
            <label for="exampleInputName1"> From Date</label>
            <input type="date" class="form-control" id="from_date" name="from_date">
          </div>
          <div class="form-group">
            <label for="exampleInputName1">To Date</label>
            <input type="date" class="form-control" id="to_date" name="to_date">
          </div>
          {{-- <div class="form-group">
            <label for="exampleInputName1">Date</label>
            <input type="date" class="form-control" id="date" name="date" >
          </div> --}}
          <div class="form-group">
            <label for="exampleInputName1">From Time</label>
            <input type="time" class="form-control" id="from_time" name="from_time" >
          </div>
          <div class="form-group">
            <label for="exampleInputName1">To Time</label>
            <input type="time" class="form-control" id="to_time" name="to_time" >
          </div>
        </div>
        <div class="sub-btn text-center">
        <button type="submit" class="btn btn-primary w-25" id="btnValue">Submit</button>
           <div id="loader" style="display:none;" style="color:white"></div>
      </form>
        </div>
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
     $("#loader").attr("style", "display:block");
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{url('Admin/saveplan')}}",
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
                'Plan Added!',
                'success'
              )
              location.reload();
            }
            if(data.status == 0)
            {   $("#loader").attr("style", "display:none");
              toastr["error"](data.message, "Error");
            }
        },
       });
      });
    });
    </script>
    