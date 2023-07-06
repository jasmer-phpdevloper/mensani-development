<html lang="en">
  <head>
    <title>Therapist</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('/public/assets/css/style.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('/public/assets/images/favicon.png')}}" />
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
  
    .thakyou-page {
      background-color: #191c24;
      height: 100vh;
      text-align: center;
      display: flex;
     }

     .thank.you.inner {
    margin: auto;
    color: #fff;
    background-color: #000;
    padding: 30px;
}
.thank.you.inner p {
    margin: 0px;
    margin-top: 14px;
    font-size: 22px;
}
.thank.you.inner h2 {
    /* text-transform: capitalize; */
    font-size: 29px;
}

    .resetPassword .card {
    border: 1px solid #fff;
}

.resetPassword .card-header {
    border-bottom: 1px solid #fff;
}

.resetPassword .card-body input#email{
    color: black;
    background-color: #fff;
}
.resetPassword .card-body input#password{
    color: black;
    background-color: #fff;
}
.resetPassword .card-body input#password-confirm{
    background-color: #fff;
}




</style>
<div class="resetPassword"  >
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="margin-top: 58px;">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{route('reset.password.post')}}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

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
                                <input id="password-confirm" type="text" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

