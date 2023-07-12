@extends('Admin.layouts.App')
@section('edit_therapist','menu-open')
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
  line-height: unset;
}
.stretch-card .InputName {
  margin-bottom: 7px;
}
.toast-error {
    background-color: #3533a7 !important;
}
/* body.doubleScroll {
    overflow: unset !important;
} */
body, html {
  
  overflow: initial !important;
}  
.field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

  </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Therapist</h4>
      
      <form class="forms-sample" id="form">
        @csrf
        <input type="hidden" class="form-control" name="id" value="{{$therapists->id}}" >
        <div class="form-group">
          <label for="exampleInputName1">First name <span style="color:red;">*</span></label>
          <input type="text" class="form-control" name="first_name" value="{{old('first_name',$therapists->first_name)}}" placeholder="Enter First Name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Last name <span style="color:red;">*</span></label>
          <input type="text" class="form-control"  name="last_name" value="{{old('last_name',$therapists->last_name)}}" placeholder="Enter Last Name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Image </label>
          <input type="file" class="form-control" id="image" name="image" accept="image/png, image/gif, image/jpeg" >
        </div>
        <input type="hidden"  id="image" name="oldthumbnail" value="{{$therapists->image}}">
        <img class="mb-4" src="{{$therapists->image}}" onerror="this.onerror=null;this.src='{{asset('public/assets/images/faces/face15.jpg')}}'" style="height:50px;width:50px;">
        
        <div class="form-group">
          <label for="exampleInputName1">Gender <span style="color:red;">*</span></label><br>
          Male  
          <input type="radio"  name="gender" value="male"{{ (old('gender',$therapists->gender) == 'male') ? 'checked' : ''}}>
          Female 
          <input type="radio"  name="gender" value="female"{{ (old('gender',$therapists->gender) == 'female') ? 'checked' : ''}}><br>
        </div> 

        <div class="form-group" style="pointer-events:none;" >
          <label for="exampleInputName1">Email <span style="color:red;">*</span></label>
          <input type="email"  class="form-control" value="{{old('email',$therapists->email)}}" name="email" placeholder="Enter Email">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Phone <span style="color:red;">*</span></label>
          <input type="tel" class="form-control" value="{{old('phone',$therapists->phone)}}" name="phone" placeholder="Enter phone">
        </div>
        <div class="form-group">
        
          <label for="exampleInputName1">Password </label>
          <input type="password" class="form-control" id="password-field"   value="{{old('password')}}" name="password" placeholder="Enter password">
          <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password">
        </div>
       
        <div class="secondrow">
          <div class="form-group">
                   <label for="">Country <span style="color:red;">*</span></label>
                   <select class="form-control" name="country"  id="country-dd">
                     <option value="">Select</option>
                     @foreach($country_codes as $value)
                     <option {{ old('country',$value->id) == $therapists->country ? "selected" : "" }} value="{{$value->id}}">{{$value->name}}</option>
                     @endforeach
                   </select>
          </div>   
          <div class="form-group">
            <label for="">State</label>
            <select id="state-dd" class="form-control" name="state">
              @foreach($states as $state)
              <option {{ old('state',$state->id) == $therapists->state ? "selected" : "" }} value="{{$state->id}}">{{$state->name}}</option>
              @endforeach
            </select>
        </div>
        
        <div class="secondrow">
          <div class="form-group">
                   <label for="">Sports <span style="color:red;">*</span></label>
                   <select class="form-control" name="sport" >
                     <option value="">Select</option>
                     @foreach($sports as $sport)
                     <option {{ old('sport',$sport->id) == $therapists->sport ? "selected" : "" }} value="{{$sport->id}}">{{$sport->sport}}</option>
                     @endforeach
                   </select>
          </div> 
        
        
        <div class="form-group">
          <label for="exampleInputName1">Hourly rate</label>
          <input type="number" min="1" class="form-control" name="hourly_rate" value="{{old('hourly_rate',$therapists->hourly_rate)}}" placeholder="$">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Experience <span style="color:red;">*</span></label>
          <select class="form-control" name="experience" >
                
                   <option value="< 6 month" {{ old("experience","< 6 month") == $therapists->experience ? 'selected' : '' }}>< 6 month</option>
                   <option value="1 year"  {{ old("experience","1 year") == $therapists->experience ? 'selected' : '' }}>1 year</option>
                   <option value="2 year" {{ old("experience","2 year") == $therapists->experience ? 'selected' : '' }}>2 year</option>
                   <option value="3 year"  {{ old("experience","3 year") == $therapists->experience ? 'selected' : '' }}>3 year</option>
                   <option value="4 year" {{ old("experience","4 year") == $therapists->experience ? 'selected' : '' }}>4 year</option>
                   <option value="5+ year" {{ old("experience","5+ year") == $therapists->experience ? 'selected' : '' }}>5+ year</option>
                 
                 </select>
        
        </div>
        <div class="form-group">
          <label for="exampleInputName1">License <span style="color:red;">*</span></label>
          <input type="text"  class="form-control" name="license" value="{{old('license',$therapists->license)}}" placeholder="Enter License">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Degree <span style="color:red;">*</span></label>
          <input type="text"  class="form-control" name="degree" value="{{old('degree',$therapists->degree)}}" placeholder="Enter Degree Name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Mensani level </label><br>
          Pro User 
          <input type="checkbox"  name="pro_user" value="1" @if($therapists->pro_user == 1) checked @else @endif>
         
        </div> 
        
        <div class="sub-btn text-center">
        <button type="submit" class="btn btn-primary w-25" id="btnValue">Update</button>
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
// alert('hh');

  $('#form').on('submit', function(event){
      event.preventDefault();
    
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{route('update_therepist')}}",
        dataType: 'json',
        data:new FormData(this),
        cache : false,
        processData: false,
        contentType: false,
           processData: false,
           success: function(data) {
           
             if(data.status == 1)
            {
             swal.fire({
              title: "Data updated!",
              text: "Therapist Updated Successfully!",
              type: "success"
              }).then(function() {
                location.replace("{{route('view_therapist')}}")
              });

              
            }
            if(data.status == 0)
            {  toastr.clear(); 
              toastr["error"](data.message, "Error");
            }
        },
       });
      });
    });

    $(document).ready(function () {
            $('#country-dd').on('change', function () {
                var idCountry = this.value;
               
                $("#state-dd").html('');
                $.ajax({
                    url: "{{route('fetchState')}}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#state-dd').html('<option value="">Select State</option>');
                        $.each(result.states, function (key, value) {
                            $("#state-dd").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        $('#city-dd').html('<option value="">Select City</option>');
                    }
                });
            });
         
        });
        $(document).ready(function() {
          $('body').addClass('doubleScroll');
          $(".toggle-password").click(function() {
          
          console.log($(this).toggleClass("fa-eye fa-eye-slash"));
          var input = $($(this).attr("toggle"));
          if (input.attr("type") == "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
          });
         });
    </script>

    