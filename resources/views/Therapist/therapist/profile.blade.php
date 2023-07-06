@extends('Therapist.layouts.App')
@section('profile','menu-open')
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
  top: 18px;
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
.card .imgbox {
    width: 158px;
    height: 158px;
    border-radius: 97px;
}
.card .row-box {
    border: 2px solid white;
    padding: 12px;
    margin: 0px 0px 21px 0px;
}
#form .form-group .form-control {
    background-color: transparent;
}
  </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="col-8 grid-margin stretch-card">
  <div class="card">
    <div class="row row-box align-items-center">
      <div class="col-3">
       
          <img src="{{$therapist->image}}" onerror="this.onerror=null;this.src='{{asset('storage/athleteimg/1682921017cropped4102272193752535966.jpg')}}'" class="imgbox">
      
          
         </div>
 
      <div class="col-9 text-end">
        <div class="first_row">
          <div> 
          Name: {{$therapist->name}} <br>
          Email: {{$therapist->email}} <br>
      </div> 
      </div>
    </div>
  </div>
    <div class="card-body">
     
      <form class="forms-sample" id="form">
        @csrf
        <input type="hidden" class="form-control" name="id" value="{{$therapist->id}}" >
        <div class="form-group">
          <label for="exampleInputName1">First name</label>
          <input type="text" readonly class="form-control" name="first_name" value="{{old('first_name',$therapist->first_name)}}" placeholder="Enter First Name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Last name</label>
          <input type="text" readonly class="form-control"  name="last_name" value="{{old('last_name',$therapist->last_name)}}" placeholder="Enter Last Name">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Image </label>
          <input type="file" class="form-control" id="image" name="image" accept="image/png, image/gif, image/jpeg" >
        </div>
        <input type="hidden"  id="image" name="oldthumbnail" value="{{$therapist->image}}">
        <img class="mb-4" src="{{$therapist->image}}" onerror="this.onerror=null;this.src='{{asset('storage/athleteimg/1682921017cropped4102272193752535966.jpg')}}'" style="height:50px;width:50px;">
        
        <div class="form-group">
          <label for="exampleInputName1">Gender</label><br><br>
          Male  
          <input type="radio"   name="gender" value="male"{{ (old('gender',$therapist->gender) == 'male') ? 'checked' : ''}}>
          Female 
          <input type="radio"  name="gender" value="female"{{ (old('gender',$therapist->gender) == 'female') ? 'checked' : ''}}><br><br>
        </div> 

        <div class="form-group">
          <label for="exampleInputName1">Email</label>
          <input type="email" readonly class="form-control" value="{{old('email',$therapist->email)}}" name="email" placeholder="Enter Email">
        </div>
        
       
        <div class="secondrow">
          <div class="form-group">
                   <label for="">Country</label>
                   <select class="form-control" disabled  name="country"  id="country-dd">
                     <option value="">Select</option>
                     @foreach($country_codes as $value)
                     <option {{ old('country',$value->id) == $therapist->country ? "selected" : "" }} value="{{$value->id}}">{{$value->name}}</option>
                     @endforeach
                   </select>
          </div>   
          <div class="form-group">
            <label for="">State</label>
            <select id="state-dd" class="form-control" disabled  name="state">
              @foreach($states as $state)
              <option {{ old('state',$state->id) == $therapist->state ? "selected" : "" }} value="{{$state->id}}">{{$state->name}}</option>
              @endforeach
            </select>
        </div>
        
        <div class="secondrow">
          <div class="form-group">
                   <label for="">Sports</label>
                   <select class="form-control" disabled name="sport" >
                     <option value="">Select</option>
                     @foreach($sports as $sport)
                     <option {{ old('sport',$sport->id) == $therapist->sport ? "selected" : "" }} value="{{$sport->id}}">{{$sport->sport}}</option>
                     @endforeach
                   </select>
          </div> 
        
        
        <div class="form-group">
          <label for="exampleInputName1">Hourly rate</label>
          <input type="number" min="1" class="form-control" name="hourly_rate" value="{{old('hourly_rate',$therapist->hourly_rate)}}" placeholder="Enter Hourly Rate">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">License <span style="color:red;">*</span></label>
          <input type="text"  class="form-control" name="license" value="{{old('license',$therapist->license)}}" placeholder="Enter License">
        </div>
        <div class="form-group">
          <label for="exampleInputName1">Degree <span style="color:red;">*</span>  </label>
          <input type="text"  class="form-control" name="degree" value="{{old('degree',$therapist->degree)}}" placeholder="Enter Degree Name">
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
        url: "{{route('update_therepist_profile')}}",
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
              text: "Profile Updated Successfully!",
              type: "success"
              }).then(function() {
                location.replace("{{route('profile')}}")
              });

              
            }
            if(data.status == 0)
            {
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
    </script>

    