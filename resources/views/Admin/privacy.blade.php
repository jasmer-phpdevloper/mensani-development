@extends('Admin.layouts.App')
@section('view_notification','menu-open')
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
      position: relative;
      left: 20%;
  }
  #active{
    border: none;color: blue;font-size: 22px;
  }
  #inactive{
    border: none;color: red;font-size: 22px;
  }
  div#Table_ID_filter {
    float: right;
}
.dataTables_wrapper label {
    font-size: .8125rem;
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
div#Table_ID_length {
    float: left;
}
.dataTables_wrapper .dataTables_length select {
    min-width: 51px;
    min-height: 24px;
    text-align: center;
    margin-left: .25rem;
    padding: 0px 15px;
    margin-right: .25rem;
}
.toast-error {
    background-color: #3533a7 !important;
}
iframe#description_ifr {
    height: 316px !important;
}
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Manage Privacy Policy</h4>
      
      <form class="forms-sample" id="form">
        @csrf
      
       
        <div class="form-group">
          <label for="exampleInputName1">Privacy Policy</label>
          <textarea id="description" class="form-control"   name="content" rows="4" cols="50">{{$privacy->content}}</textarea>
         
        </div>
          
       
        <div class="sub-btn text-center">
        <button type="submit" class="btn btn-primary w-25" id="btnValue">Submit</button>
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
        url: "{{url('Admin/save_privacy')}}",
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
                'Policy Added!',
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