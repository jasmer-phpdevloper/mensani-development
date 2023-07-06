@extends('Admin.layouts.App')
@section('subscription','menu-open')
{{-- @section('dashboard_active','active') --}}
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
  .select2-container--default .select2-selection--multiple {
    background-color: #2A3038 !important;
   
}
.toast-error {
    background-color: #3533a7 !important;
}
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Add Subscription Plan</h4>
      
      <form class="forms-sample" id="form">
        @csrf
        <div class="form-group">
          <label for="exampleInputName1">Name:</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
        </div>
        <div class="form-group">
          <label for="exampleTextarea1">Description:</label>
          <textarea class="form-control" id="description" name="description" rows="4" cols="40" placeholder="Enter Description"></textarea>
        </div>
        <div class="form-group">
          <label for="email">Duration:</label><br>
          <select class="form-control " name="duration"  style="width: 100%;height:37px;" >
            <option value="">Select</option>
            <option value="Weekly">Weekly</option>
            <option value="Monthly">Monthly</option>
            <option value="Yearly">Yearly</option>
         </select>
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Price:</label>
          <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price">
        </div>
      
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


  $('#form').on('submit', function(event){
      event.preventDefault();
    
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{url('Admin/savesubscription')}}",
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
                'Subscription Added!',
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