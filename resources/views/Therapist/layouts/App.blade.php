<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Therapist</title>
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
 </style>
  <body class="doubleScroll">
    
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="{{url('Therapist/dashboard')}}"><img src="{{url('/public/logo/mensani_logo.png')}}" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="{{url('Therapist/dashboard')}}"><img src="{{url('public/logo/mensani_logo.png')}}" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="count-indicator">
                  <img  class="img-xs rounded-circle"  src="{{\Auth::guard('therapist')->user()->image}}" onerror="this.onerror=null;this.src='{{asset('storage/athleteimg/1682921017cropped4102272193752535966.jpg')}}'"alt="" >
           
                  <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal">
                  @if(Auth::guard('therapist')->check())
                   {{ucfirst(Auth::guard('therapist')->user()->name)}}
                  @endif</h5>
                </div>
              </div>
          
            </div>
          </li>
         
          <li class="nav-item menu-items @yield('dashboard')">
            <a class="nav-link @yield('dashboard_item')" href="{{url('Therapist/dashboard')}}">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item menu-items @yield('profile')">
            <a class="nav-link @yield('profile')" href="{{url('Therapist/profile')}}">
              <span class="menu-icon">
                <i class="mdi mdi-account"></i>
              </span>
              <span class="menu-title">Profile</span>
            </a>
          </li>
          <li class="nav-item menu-items @yield('notification')">
            <a class="nav-link @yield('notification')" href="{{url('Therapist/view_notification')}}">
              <span class="menu-icon">
                <i class="mdi mdi-bell"></i>
              </span>
              <span class="menu-title">View Notification</span>
            </a>
          </li>
          <li class="nav-item menu-items @yield('feedback')">
            <a class="nav-link @yield('feedback')" href="{{route('view_feedback')}}">
              <span class="menu-icon">
                <i class="mdi mdi-message-text-outline"></i>
              </span>
              <span class="menu-title">View Feedback</span>
            </a>
          </li>

          <li class="nav-item menu-items @yield('chatListing')">
            <a class="nav-link @yield('chatListing')" href="{{route('chatListing')}}">
              <span class="menu-icon">
                <i class="mdi mdi-chat"></i>
              </span>
              <span class="menu-title">Chat</span><span class="badge">{{\DB::table('chats')->where('sender_id',\Auth::guard('therapist')->user()->id)->where(['message_status'=>1,'message_type'=>1])->count()}}</span>
            </a>
          </li>

          
         

          

          

          <li class="nav-item menu-items @yield('appointment')"">
            <a class="nav-link  @yield('appointment')"" data-bs-toggle="collapse" href="#basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-clock"></i>
              </span>
              <span class="menu-title">Availability Time</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('addAppointmentTime')}}">Add Availability Time</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('appointmentTime')}}">View Availability Time</a></li>
                
            
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items @yield('eventCalender')">
            <a class="nav-link @yield('eventCalender')" href="{{route('eventCalender')}}" target="_blank">
              <span class="menu-icon">
                <i class="mdi mdi-calendar"></i>
              </span>
              <span class="menu-title">Appointment</span>
            </a>
          </li>

         
         
          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#sic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-pencil"></i>
              </span>
              <span class="menu-title">Manage Content</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('add_support')}}">Add Content</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('view_support_therapist')}}">View Content</a></li>
             
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items @yield('changePassword')">
            <a class="nav-link @yield('changePassword')" href="{{route('changePassword')}}">
              <span class="menu-icon">
                <span class="mdi mdi-lock-reset"></span>
              </span>
              <span class="menu-title">Change Password</span>
            </a>
          </li>  
        
          <li class="nav-item menu-items">
            <a class="nav-link" href="{{url('Therapist/logout')}}">
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
      <script src="{{asset('/public/assets/js/jquery.cookie.js')}}"></script>
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
      <!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>



<script>
  
    const firebaseConfig = {
    apiKey: "AIzaSyAqLYJH-iitSAsLJjrW2MGHs2EDCjxsCGk",
    authDomain: "mensani-e1af8.firebaseapp.com",
    projectId: "mensani-e1af8",
    storageBucket: "mensani-e1af8.appspot.com",
    messagingSenderId: "971751303798",
    appId: "1:971751303798:web:4c82959142d616234ecd57"

  };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

   
    $( document ).ready(function() {

   
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("storeToken") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (error) {
                       // alert(error);
                    },
                });
            }).catch(function (error) {
               // alert(error);
            });



  });
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });




    
</script>
<script>

$( document ).ready(function() {

closeAllDropdowns();
  // Get all the dropdown links
  const dropdownLinks = document.querySelectorAll('.nav-link');

  // Add click event listener to each dropdown link
  dropdownLinks.forEach(link => {
    link.addEventListener('click', function(event) {
      // Prevent the default link behavior
      
   
      // Get the corresponding dropdown menu
      const dropdownMenu = this.nextElementSibling;

      // Check if the clicked dropdown menu is already open
      const isOpen = dropdownMenu.classList.contains('show');

      // Close all dropdown menus
      closeAllDropdowns();

      // Open the clicked dropdown menu if it was closed
      if (!isOpen) {
       
        dropdownMenu.classList.add('show');
      }
    });
  });

  // Function to close all dropdown menus
  function closeAllDropdowns() {
    const dropdownMenus = document.querySelectorAll('.collapse.show');
   
    dropdownMenus.forEach(menu => {
      menu.classList.remove('show');
    });
  }

});
</script>
    

      <!-- End custom js for this page -->
      @yield('main_script')
    </body>
  </html>