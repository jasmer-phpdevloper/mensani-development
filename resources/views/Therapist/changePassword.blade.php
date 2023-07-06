@extends('Therapist.layouts.App')
@section('changePassword','menu-open')
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
.card .card-body input#password{
    color: black;
    background-color: #fff;
}
.card .card-body input#password-confirm{
    background-color: #fff;
}

  </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Change Password</h4>
      
      <form class="forms-sample mb-0" id="form">
        @csrf
        <div class="row mb-3">
          <input id="password-confirm" type="hidden" class="form-control" name="email" value="{{Auth::guard('therapist')->user()->email}}">
          <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

          <div class="col-md-6">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
      </div>
      
      <div class="row mb-3">
          <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

          <div class="col-md-6">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
          </div>
      </div>
      <div class="sub-btn text-center">
        <button type="submit" class="btn btn-primary w-25" id="btnValue">Submit</button>
        </div>
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
    
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{route('changePassword')}}",
        dataType: 'json',
        data:new FormData(this),
        cache : false,
        processData: false,
        contentType: false,
           processData: false,
           success: function(data) {
           
           if(data.status == 1)
          { $(".loader").hide();
            swal.fire({
            title: "Data updated!",
            text: "Password Change Successfully!",
            type: "success"
            }).then(function() {
              location.replace("{{route('changePassword')}}")
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
    </script>
    