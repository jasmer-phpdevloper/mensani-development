@extends('Admin.layouts.App')
@section('athletes','menu-open')
@section('view_athletes','active')
<head>
</head>
<style>
  th{
    color:#0b528f !important;
    font-size: 0.9rem;
    text-align: center !important;
  }
  div#show_services {
      margin-left: 14px;
  }
  img#new_profile_image {
      border: 1px solid black;
  }
  /* section.content.second {
      margin-top: 74px !important;
  } */
  .content-wrapper{
    min-height: 615px !important;
  }
  .card.new {
      margin-left: 257px;
      width: 80%;
  }

  
  table.dataTable.no-footer {
      width: 100%;
  }
  a.btn.btn-primary.back_btn{
        background-color:#ED9B2D;
        color:black;
  }
    .name_class.ml-2 {
      margin-top: -15px;
      margin-left: 20px !important;
  } 
  div#doctor_class {
    float: right;
    margin-top: -49px;
  }
  button#doctor_btn {
      color: black;
      background-color: #ff7600;
  }
  img#new_profile_image {
    border-radius: 50%;
    width: 125px;
    height: 125px;
    margin-top: -87px;
    margin-left: 63px;
  }
  b {
    font-weight: 500;
    color: black;
  }
  .col-lg-4.col-md-4.col-sm-12 {
    font-weight: 400;
  }


  div#show_image_popup {
    display: none;
    top:50%;
    left:37%;
    position:absolute;
    z-index: 1000; /  adobe all elements   /
  transform: translate(-50%, -50%); /  make center   /

  } 
  div#show_aadhar_popup {
      display: none;
      top:50%;
      left:37%;
      position:absolute;
      z-index: 1000; /  adobe all elements   /
    transform: translate(-50%, -50%); /  make center   /
  }
  div#show_voter_popup {
      display: none;
      top:50%;
      left:37%;
      position:absolute;
      z-index: 1000; /  adobe all elements   /
    transform: translate(-50%, -50%); /  make center   /

  }
    #active{
      border: none;color: blue;font-size: 22px;
    }
    #inactive{
      border: none;color: red;font-size: 22px;
    }
    .first_row {
      background-color: #dce4eb;
      width: 100%;
      height: 160px;
    }
    img#show_profile_image {
      height: 125px;
      width: 12%;
      margin-left: 61px;
      margin-top: -84px;
      border-radius: 50%;
      border: 2px solid #ff6a00;
    }
    .name_class.ml-2 {
      margin-top: -15px;
  }
  .close-btn-area {
      background-color: white;
    }
    #show_image_popup{
    position: absolute; /  so that not take place   /
    top: 50%;
    left: 50%;
    z-index: 1000; /  adobe all elements   /
    transform: translate(-50%, -50%); /  make center   /
    display: none; /  to hide first time   /
    } 
    .profile-info, .business-info {
      height:100%;
    }
    .profile-info .heading i , .business-info .heading i{
      position: absolute;
      font-size:25px;
    }
    .profile-info .table tr th, .business-info .table tr th{
      color:#d2b6f5 !important;
      text-align:left !important;
      font-size:1rem !important;
    }

    .profile-info .table tr td{
      width: 60%;
    }
    i.material-icons {
    margin-top: -5px;
}
.first_row {
    background: #373944;
    border: 4px solid #5a5a5a;
}

</style>

  <!-- Content Wrapper. Contains page content -->
  @section('main_section')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{$athlete->name}} 
            </h1>
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

   
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <!-- /.card-body -->
                <div class="row">
                  <div class="first_row">
                   <div style="color: #d2b6f5;font-size: large;"> 
                   Name: {{$athlete->name}} <br>
                   Email: {{$athlete->email}} <br>
                   </div>
                   @if($mood)
                   <div style="color: #d2b6f5;font-size: large;float: right;margin-top: -46px;width: 50%;"> 
                    Mood:  {{$mood->mood}}<br>
                    Thought:  {{$mood->thought}}<br>
                    </div>
                    @endif
                  </div>
                </div><br>
                <div class="row">
                  <div class="col-9">
                    @if($athlete->image)
                    <img src="{{$athlete->image}}" id="new_profile_image">
                    @else
                    <img src="/public/logo/download.png" id="new_profile_image">
                    @endif
                    <h4 class="d-inline ml-2 position-absolute" style="top:-15px;"><b></b></h4>
                  </div>
                 
                </div><br>
              </div>          
            </div>
          </div>      
        </div> 

        <div class="row">
          <div class="col-6">
            <div class="card profile-info">
                <!-- /.card-header -->
              <div class="card-body">
                <div class="heading mt-2">
                  <h5>
                    <i class="material-icons ">person</i>
                    <b style="margin-left:2rem;color:white">Seasons Goals</b>
                  </h5>
                </div>
                <table class="table mt-2 mb-4">
                  <tr>
                    <th>Primary Goals:</th>
                    @if($seasongoals)
                    <td>{{$seasongoals->primary_goal}}</td>
                    @else
                    <td></td>
                    @endif
                  </tr>
                  <tr>
                    <th>Secondary Goals:</th>
                    @if($seasongoals)
                    <td>{{$seasongoals->secondary_goal}}</td>
                    @else
                    <td></td>
                    @endif
                  </tr>
                 
                </table>
                <div class="heading mt-2">
                  <h5>
                    <i class="material-icons ">person</i>
                    <b style="margin-left:2rem;color:white">Dreams Goals</b>
                  </h5>
                </div>
                <table class="table mt-2 mb-4">
                  <tr>
                    <th>Dreams Goal:</th>
                    @if($dreamgoals)
                    <td>{{$dreamgoals->dream_goal}}</td>
                    @else
                    <td></td>
                    @endif
                  </tr>
                 
                 
                </table>
                
              </div>          
            </div>
          </div>  
          <div class="col-6">
            <div class="card business-info">
                <!-- /.card-header -->
              <div class="card-body">
                <div class="heading mt-2">
                  <h5>
                    <i class="material-icons ">business_center</i>
                    <b style="margin-left:2rem;color:white">Self-talk Setup</b>
                  </h5>
                </div>
                <table class="table mt-2 mb-4">
                  <tr>
                    <th>Role Model:</th>
                    @if($selftalks)
                    <td>{{$selftalks->role_model}}</td>
                    @else
                    <td></td>
                    @endif
                  </tr>
                  <tr>
                    <th>Image:</th>
                    @if($selftalks)
                    <td> 
                     <img src="{{$selftalks->image}}" style="height:50px;width:50px;">
                    </td>
                    @else
                    <td></td>
                    @endif
                  </tr>
                  <tr>
                    <th>Challenge:</th>
                    @if($selftalks)
                    <td>{{$selftalks->challenge}}</td>
                    @else
                    <td></td>
                    @endif
                  </tr>
                  <tr>
                    <th>Recording:</th>
                    @if($selftalks)
                    <td><audio controls>
                      <source src="{{$selftalks->recording}}" type="audio/mpeg"> 
                      </audio></td>
                      @else
                      <td></td>
                      @endif
                  </tr>
                </table>
                
              </div>          
            </div>
          </div>      
        </div>        
      </div>      
    </section>
  
   
    

  
    <!-- /.content -->
  </div>
 

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->

<!-- DataTables  & Plugins -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js"></script>
<script src="/public/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
  $(document).ready( function () {
    // alert('hh');
    var t = $('#Table_ID').DataTable({
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0,
            },
        ],
        order: [[0, 'desc']],
    });
 
    t.on('order.dt search.dt', function () {
        let i = 1;
 
        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
    
  } );
  </script>
  <script>
    $(document).ready(function(){
      // alert('hh');
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
  @endsection
  
<!-- AdminLTE App -->
<!-- Page specific script -->
