<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/jvectormap/jquery-jvectormap.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/owl-carousel-2/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.min.css" integrity="sha512-cyIcYOviYhF0bHIhzXWJQ/7xnaBuIIOecYoPZBgJHQKFPo+TOBA+BY1EnTpmM8yKDU4ZdI3UGccNGCEUdfbBqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('/public/assets/css/style.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('/public/assets/images/favicon.png')}}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link  rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    
  </head>
  <style>
.sidebar {   
    float: left;   
}
.page-body-wrapper {   
    width: 100%;
}
.main-panel {    
    padding-top: 0px;   
}
nav#sidebar {
    background-color: #000;
}
.sidebar .menu-items {
    margin: 2px 0px;
}
.sidebar-brand-wrapper {
    background: #000 !important;
}
#form .form-group .form-control {
    color: #fff !important;
    padding: 8px;
   
}
.stretch-card select,.stretch-card td,.stretch-card .sorting_asc, .stretch-card.sorting_1 , .stretch-card .sorting, .stretch-card .sorting_desc, .stretch-card .sorting_disabled ,.stretch-card .dataTables_empty , .stretch-card select:focus {
    color: white !important;
}
.sidebar span.menu-title:hover {
    color: #fff !important;
}
/* .sidebar .nav .nav-item .nav-link .menu-title {
       color: #ffffff;
}
.sidebar .nav.sub-menu .nav-item .nav-link {
    color: #ffffff;
   
} */
/* nav#sidebar {
    background-color: #000;
    overflow-y: scroll;
    height: 100%;
    position: fixed;
} */
/* .stretch-card {
    margin-left: 414px;
    position: absolute;
    top: 0;
} */
/* body.doubleScroll {
    overflow: unset !important;
} */
    </style>
  <body>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="{{url('Admin/dashboard')}}"><img src="{{url('/public/logo/mensani_logo.png')}}" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="{{url('Admin/dashboard')}}"><img src="{{url('public/logo/mensani_logo.png')}}" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="count-indicator">
                  <img class="img-xs rounded-circle " src="{{url('/public/assets/images/faces/face15.jpg')}}" alt="">
                  <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal">Admin</h5>
                
                </div>
              </div>
          
            </div>
          </li>
         
          <li class="nav-item menu-items @yield('dashboard')">
            <a class="nav-link @yield('dashboard_item')" href="{{url('Admin/dashboard')}}">
              <span class="menu-icon">
                <i class="mdi mdi-view-dashboard"></i>
              </span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
       


          
          <li class="nav-item menu-items @yield('athletes')">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-run"></i>
              </span>
              <span class="menu-title">Manage Athletes</span>
              <i class="menu-arrow"></i>
            </a> 
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link  @yield('viewathletes')" href="{{url('Admin/view_athletes')}}">View Athletes</a></li>               
              </ul>
            </div>
          </li>
          
          <li class="nav-item menu-items  @yield('therapist')">
            <a class="nav-link" data-bs-toggle="collapse" href="#basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-stethoscope"></i>
              </span>
              <span class="menu-title">Manage Therapist</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" @yield('addtherapist') href="{{route('addtherepist')}}">Add Therapist</a></li>
                <li class="nav-item"> <a class="nav-link" @yield('viewtherapist') href="{{route('view_therapist')}}">View Therapist</a></li>
            
              </ul>
            </div>
          </li>

          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-bell"></i>
              </span>
              <span class="menu-title">Manage Notification</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/notification')}}">Add Notification</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/view_notification')}}">View Notification</a></li>
            
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items  @yield('questions')">
            <a class="nav-link" data-bs-toggle="collapse" href="#asic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-comment-question"></i>
              </span>
              <span class="menu-title">Manage Questions</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse @yield('quest')" id="asic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/questions')}}">Add Question</a></li>
                <li class="nav-item"> <a class="nav-link  @yield('view_questions')" href="{{url('Admin/view_questions')}}">View Questions</a></li>
               
              </ul>
            </div>
          </li>
         

          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#sic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-lifebuoy"></i>
              </span>
              <span class="menu-title">Manage Support</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/support')}}">Add Support</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/view_support')}}">View Support</a></li>
             
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#ic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-timetable"></i>
              </span>
              <span class="menu-title">Manage Today's Plan</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/addplan')}}">Add Today's Plan</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/viewplan')}}">View Today's Plan</a></li>
              
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items @yield('sports')">
            <a class="nav-link" data-bs-toggle="collapse" href="#c" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-basketball"></i>
              </span>
              <span class="menu-title">Manage Sports</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="c">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/sports')}}">Add Sports</a></li>
                <li class="nav-item"> <a class="nav-link @yield('viewsport')" href="{{url('Admin/viewsports')}}">View Sports</a></li>
              
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#ff" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-star"></i>
              </span>
              <span class="menu-title">Manage Points</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ff">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/points')}}">Add Points</a></li>
             
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#ss" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-bank-transfer"></i>
              </span>
              <span class="menu-title">Transactions</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ss">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('subscription_payment')}}">Subscription payment</a></li>
                
                <li class="nav-item"> <a class="nav-link" href="{{route('appoiPayment')}}">Appointment payment</a></li>
              </ul>
            </div>
          </li>
         <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#fi" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-currency-usd"></i>
              </span>
              <span class="menu-title">Manage Subscription</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="fi">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/subscription')}}">Add Subscription</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/viewsubscription')}}">View Subscription</a></li>
              
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#fif" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-shield"></i>
              </span>
              <span class="menu-title">Manage Privacy Policy</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="fif">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('Admin/privacy')}}">Privacy Policy</a></li>
               
              </ul>
            </div>


          </li>
          <li class="nav-item menu-items @yield('changePassword')">
            <a class="nav-link @yield('AdminchangePassword')" href="{{route('AdminchangePassword')}}">
              <span class="menu-icon">
                <span class="mdi mdi-lock-reset"></span>
              </span>
              <span class="menu-title">Change Password</span>
            </a>
          </li>
       
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{url('Admin/logout')}}">
              <span class="menu-icon">
                <i class="mdi mdi-logout"></i>
              </span>
              <span class="menu-title">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
      @yield('main_section')
      <script src="{{asset('/public/assets/vendors/js/vendor.bundle.base.js')}}"></script>
      <!-- endinject -->
      <!-- Plugin js for this page -->
      <script src="{{asset('/public/assets/vendors/chart.js/Chart.min.js')}}"></script>
      <script src="{{asset('/public/assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
      <script src="{{asset('/public/assets/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
      <script src="{{asset('/public/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
      <script src="{{asset('/public/assets/vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
      <script src="{{asset('/public/assets/js/jquery.cookie.js" type="text/javascript')}}"></script>
      <!-- End plugin js for this page -->
      <!-- inject:js -->
      <script src="{{asset('/public/assets/js/off-canvas.js')}}"></script>
      <script src="{{asset('/public/assets/js/hoverable-collapse.js')}}"></script>
      <script src="{{asset('/public/assets/js/misc.js')}}"></script>
      <script src="{{asset('/public/assets/js/settings.js')}}"></script>
      <script src="{{asset('/public/assets/js/todolist.js')}}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.9/sweetalert2.all.min.js" integrity="sha512-IZ95TbsPTDl3eT5GwqTJH/14xZ2feLEGJRbII6bRKtE/HC6x3N4cHye7yyikadgAsuiddCY2+6gMntpVHL1gHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <!-- endinject -->
      <!-- Custom js for this page -->
      <script src="{{asset('/public/assets/js/dashboard.js')}}"></script>
      <script src="{{asset('https://code.jquery.com/jquery-3.2.1.min.js')}}"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="{{asset('/public/tinymce/js/tinymce/tinymce.min.js')}}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js"></script>

      <!-- End custom js for this page -->
      @yield('main_script')
    </body>
  </html>