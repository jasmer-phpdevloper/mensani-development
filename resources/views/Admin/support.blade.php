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
    width: 57% !important;
    margin: 0px 0px 0px auto;
    position: relative;
    top: 15%;
    right: 11%;
  
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
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
  position: relative;
    bottom: 200px;
    left: 40%;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Manage Support</h4>
      
      <form class="forms-sample" id="form">
        @csrf
      
        <div class="form-group">
          <label class="InputName" for="exampleInputName1">Title</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
        </div>
        <div class="form-group">
          <label class="InputName" for="exampleInputName1">Thumbnail</label>
          <input type="file" class="form-control" id="thumbnail" name="thumbnail" >
        </div>
        <div class="form-group">
        <input type="radio" id="free" name="price" value="Free">
        <label for="free" style="margin-top: 4px;">Free</label>
        <input type="radio" id="paid" name="price" value="Paid">
        <label for="paid" style="margin-top: 4px;">Paid</label>
        </div>
        <div class="form-group">
          <label class="InputName" for="exampleInputName1">Video</label>
          <input type="file" class="form-control" id="video" name="video" accept="video/*">
        </div>
        <div class="form-group">
          <input type="radio" id="educational_support" name="support_type" value="Educational Support">
        <label for="free" style="margin-top: 4px;">Educational Support</label>
        <input type="radio" id="mental_support" name="support_type" value="Mental Training Support">
        <label for="paid" style="margin-top: 4px;">Mental Trainning Support</label>
        </div>
        <div class="loader" style="display: none"></div>
        <div class="text-center">
        <button type="submit" class="btn btn-primary w-25">Submit</button>
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
<script>
 
 $(document).ready(function () {
// alert('hh');

  $('#form').on('submit', function(event){
      event.preventDefault();
   
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{url('Admin/save_support')}}",
        dataType: 'json',
        data:new FormData(this),
        cache : false,
        processData: false,
        contentType: false,
           processData: false,
           beforeSend: function() {
            $(".loader").show();
           },
           success: function(data) {
           
             if(data.status == 1)
            {
           
              Swal.fire(
                'Good job!',
                'Support Added!',
                'success'
              )
              $(".loader").hide();
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
