@extends('Therapist.layouts.App')
@section('appointment','menu-open')
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
.card .form-group input.form-control , .card select.form-control {
    background: unset !important;
}

    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Availability Time</h4>
      
      <form class="forms-sample" id="form">
        @csrf
        <input type="hidden" class="form-control" id="id"  name="id" value="{{$appointment->id}}" placeholder="Enter start Time"  >
        <div class="form-group" disabled>
          <label class="InputName" for="exampleInputName1">Day</label>
          <select class="form-control" name="day" disabled>
      
            <option value="Monday" @if($appointment->day == "Monday") selected @endif>Monday</option>
            <option value="Tuesday" @if($appointment->day == "Tuesday") selected @endif>Tuesday</option>
            <option value="Wednesday" @if($appointment->day == "Wednesday") selected @endif>Wednesday</option>
            <option value="Thursday" @if($appointment->day == "Thursday") selected @endif>Thursday</option>
            <option value="Friday"  @if($appointment->day == "Friday") selected @endif>Friday</option>
            <option value="Saturday"  @if($appointment->day == "Saturday") selected @endif>Saturday</option>

          </select>  
        </div>
        <div class="form-group">
          <label class="InputName" for="exampleInputName1">Start Time  </label>
          <input type="text" class="form-control" id="start_time"  name="start_time" value="{{$appointment->start_time}}" placeholder="Enter start Time"  >
        </div>
        <div class="form-group">
          <label class="InputName" for="exampleInputName1">End Time </label>
          <input type="text" class="form-control" id="end_time" name="end_time" value="{{$appointment->end_time}}"  placeholder="Enter end Time" >
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js"></script>

<script>
  $(function() {
    flatpickr("#start_time", {
   enableTime: true,
   noCalendar: true,
   dateFormat: "H:i", // Format to show only hours and minutes
   time_24hr: true, // Use a 24-hour format (optional)
   minuteIncrement: 30,
});
flatpickr("#end_time", {
   enableTime: true,
   noCalendar: true,
   dateFormat: "H:i", // Format to show only hours and minutes
   time_24hr: true, // Use a 24-hour format (optional)
   minuteIncrement: 30,
});
  });
  </script>
<script>
 
 $(document).ready(function () {
// alert('hh');

  $('#form').on('submit', function(event){
      event.preventDefault();
   
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{route('updateAppointmentTime')}}",
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
            { $(".loader").hide();
              swal.fire({
              title: "Data updated!",
              text: "Availability Time updated Successfully!",
              type: "success"
              }).then(function() {
                location.replace("{{route('appointmentTime')}}")
              });

             // $(".loader").hide();
            //  location.reload();
              
            }
            if(data.status == 0)
            {  $(".loader").hide();
              toastr.clear();
              toastr["error"](data.message, "Error");
            }
        },
       });
      });
    });

    $(document).keypress(function(e) {
    return false;
});

  //  const timeValue = timeInput.value;
   

    </script>
 
