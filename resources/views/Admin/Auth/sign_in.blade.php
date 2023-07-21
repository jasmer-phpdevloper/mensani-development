<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mensani</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/public/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/public/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="/public/assets/images/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <style>
    .container-scroller .form-group .p_input {
    color: #fff !important;
    }
    button.btn.btn-primary.btn-block.enter-btn {
    height: 44px;
    width: 99px;
    font-size: medium;
    background-color: #df7225;
}
.fa-eye:before {
    content: "\f06e";
    position: absolute;
    top: 55%;
    left: 80%;
    color: #5c5555;
    z-index: 999;
}
.auth.login-bg {
    background: url(/public/logo/background.jpeg);
    background-size: cover;
}
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto" style="border-radius: 10px;">
              <div class="card-body px-5 py-5">
                <div class="mensani" style="text-align: center;">
                <img src="/public/logo/mensani_logo.png" alt="logo" style="height: 44px;width: 157px;" />
                </div>
                <h3 class="card-title text-left mb-3" style="text-align: center;">Login</h3>
                <form method="POST" action="{{url('Admin/login')}}">
                    @csrf
                    @if(session('errormessage'))
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{session('errormessage')}}
                       
                      </div>
                    @endif
                  <div class="form-group">
                    <label>Email *</label>
                    <input type="email" class="form-control p_input" name="email">
                    @if($errors->has('email'))
                   <div class="error">{{ $errors->first('email') }}</div>
                   @endif
                  </div>
                  <div class="form-group">
                    <label>Password *</label>
                    <div class="ppp">
                    <input type="password" class="form-control p_input" name="password" id="id_password">
                    <i class="far fa-eye" id="togglePassword" ></i>
                    </div>
                    @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password') }}</div>
                    @endif
                  </div>
                  <div class="form-group d-flex align-items-center justify-content-between">
                    {{-- <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input"> Remember me </label>
                    </div> --}}
                    {{-- <a href="#" class="forgot-pass">Forgot password</a> --}}
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn">Login</button>
                  </div>
                  {{-- <div class="d-flex">
                    <button class="btn btn-facebook me-2 col">
                      <i class="mdi mdi-facebook"></i> Facebook </button>
                    <button class="btn btn-google col">
                      <i class="mdi mdi-google-plus"></i> Google plus </button>
                  </div>
                  <p class="sign-up">Don't have an Account?<a href="#"> Sign Up</a></p> --}}
                </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="/public/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="/public/assets/js/off-canvas.js"></script>
    <script src="/public/assets/js/hoverable-collapse.js"></script>
    <script src="/public/assets/js/misc.js"></script>
    <script src="/public/assets/js/settings.js"></script>
    <script src="/public/assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>
<script>
  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#id_password');

  togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
</script>  