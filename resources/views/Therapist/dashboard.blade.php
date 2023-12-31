@extends('Therapist.layouts.App')
@section('dashboard','menu-open')
<style>
 .time-btn p {
    text-transform: capitalize;
}

 .sidebar {
  overflow-y: scroll;
    width: 20% !important;
    height: 100vh;
  }

  .card {
  border: 1px solid #e6e6e6;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
}



.time-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 10px;
}

.time-btn p {
  margin: 0;
}

h6 {
  margin-bottom: 0;
}

.text-muted {
  color: #777777;
}

.font-weight-normal {
  font-weight: normal;
}


</style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-scroller">
<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
      </div>
    
    </nav>
    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
     
        <div class="row">
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="time-btn">
                    <p>
                    <i class="fas fa-calendar-alt"></i>
                    {{ date('l') }}, {{ date('M, d, Y') }}
                  </p> 
                    <p>
                    <i class="fas fa-play-circle"></i> <!-- Icon for start time -->
                    {{ $todayAvailbelityTime->start_time ?? "00:00" }}
                    <i class="fas fa-long-arrow-alt-right"></i> <!-- Icon for arrow -->
                    {{ $todayAvailbelityTime->end_time ?? "00:00" }}
                    <i class="fas fa-stop-circle"></i> <!-- Icon for end time -->
                  </p>
                  

                      {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> --}}
                    </div>
                  </div>
                  {{-- <div class="col-3">
                    <div class="icon icon-box-success ">
                      <span class="mdi mdi-arrow-top-right icon-item"></span>
                    </div>
                  </div> --}}
                </div>
                <h6 class="text-muted font-weight-normal">Availability Time</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">{{$bookingCount}}</h3>
                      {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p> --}}
                    </div>
                  </div>
                  {{-- <div class="col-3">
                    <div class="icon icon-box-success">
                      <span class="mdi mdi-arrow-top-right icon-item"></span>
                    </div>
                  </div> --}}
                </div>
                <h6 class="text-muted font-weight-normal">Total Booking</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">{{$PastbookingCount}}</h3>
                      {{-- <p class="text-danger ms-2 mb-0 font-weight-medium">-2.4%</p> --}}
                    </div>
                  </div>
                  {{-- <div class="col-3">
                    <div class="icon icon-box-danger">
                      <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                    </div>
                  </div> --}}
                </div>
                <h6 class="text-muted font-weight-normal">Complete booking</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">{{$FuturebookingCount}}</h3>
                      {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> --}}
                    </div>
                  </div>
                  {{-- <div class="col-3">
                    <div class="icon icon-box-success ">
                      <span class="mdi mdi-arrow-top-right icon-item"></span>
                    </div>
                  </div> --}}
                </div>
                <h6 class="text-muted font-weight-normal">Future Booking</h6>
              </div>
            </div>
          </div>
        </div>
        {{-- <div class="row">
          <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Transaction History</h4>
                <canvas id="transaction-history" class="transaction-chart"></canvas>
                <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                  <div class="text-md-center text-xl-left">
                    <h6 class="mb-1">Transfer to Paypal</h6>
                    <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                  </div>
                  <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                    <h6 class="font-weight-bold mb-0">$236</h6>
                  </div>
                </div>
                <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                  <div class="text-md-center text-xl-left">
                    <h6 class="mb-1">Tranfer to Stripe</h6>
                    <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                  </div>
                  <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                    <h6 class="font-weight-bold mb-0">$593</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                  <h4 class="card-title mb-1">Open Projects</h4>
                  <p class="text-muted mb-1">Your data status</p>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="preview-list">
                      <div class="preview-item border-bottom">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-primary">
                            <i class="mdi mdi-file-document"></i>
                          </div>
                        </div>
                        <div class="preview-item-content d-sm-flex flex-grow">
                          <div class="flex-grow">
                            <h6 class="preview-subject">Admin dashboard design</h6>
                            <p class="text-muted mb-0">Broadcast web app mockup</p>
                          </div>
                          <div class="me-auto text-sm-right pt-2 pt-sm-0">
                            <p class="text-muted">15 minutes ago</p>
                            <p class="text-muted mb-0">30 tasks, 5 issues </p>
                          </div>
                        </div>
                      </div>
                      <div class="preview-item border-bottom">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-success">
                            <i class="mdi mdi-cloud-download"></i>
                          </div>
                        </div>
                        <div class="preview-item-content d-sm-flex flex-grow">
                          <div class="flex-grow">
                            <h6 class="preview-subject">Wordpress Development</h6>
                            <p class="text-muted mb-0">Upload new design</p>
                          </div>
                          <div class="me-auto text-sm-right pt-2 pt-sm-0">
                            <p class="text-muted">1 hour ago</p>
                            <p class="text-muted mb-0">23 tasks, 5 issues </p>
                          </div>
                        </div>
                      </div>
                      <div class="preview-item border-bottom">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-info">
                            <i class="mdi mdi-clock"></i>
                          </div>
                        </div>
                        <div class="preview-item-content d-sm-flex flex-grow">
                          <div class="flex-grow">
                            <h6 class="preview-subject">Project meeting</h6>
                            <p class="text-muted mb-0">New project discussion</p>
                          </div>
                          <div class="me-auto text-sm-right pt-2 pt-sm-0">
                            <p class="text-muted">35 minutes ago</p>
                            <p class="text-muted mb-0">15 tasks, 2 issues</p>
                          </div>
                        </div>
                      </div>
                      <div class="preview-item border-bottom">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-danger">
                            <i class="mdi mdi-email-open"></i>
                          </div>
                        </div>
                        <div class="preview-item-content d-sm-flex flex-grow">
                          <div class="flex-grow">
                            <h6 class="preview-subject">Broadcast Mail</h6>
                            <p class="text-muted mb-0">Sent release details to team</p>
                          </div>
                          <div class="me-auto text-sm-right pt-2 pt-sm-0">
                            <p class="text-muted">55 minutes ago</p>
                            <p class="text-muted mb-0">35 tasks, 7 issues </p>
                          </div>
                        </div>
                      </div>
                      <div class="preview-item">
                        <div class="preview-thumbnail">
                          <div class="preview-icon bg-warning">
                            <i class="mdi mdi-chart-pie"></i>
                          </div>
                        </div>
                        <div class="preview-item-content d-sm-flex flex-grow">
                          <div class="flex-grow">
                            <h6 class="preview-subject">UI Design</h6>
                            <p class="text-muted mb-0">New application planning</p>
                          </div>
                          <div class="me-auto text-sm-right pt-2 pt-sm-0">
                            <p class="text-muted">50 minutes ago</p>
                            <p class="text-muted mb-0">27 tasks, 4 issues </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
        {{-- <div class="row">
          <div class="col-sm-4 grid-margin">
            <div class="card">
              <div class="card-body">
                <h5>Revenue</h5>
                <div class="row">
                  <div class="col-8 col-sm-12 col-xl-8 my-auto">
                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                      <h2 class="mb-0">$32123</h2>
                      <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p>
                    </div>
                    <h6 class="text-muted font-weight-normal">11.38% Since last month</h6>
                  </div>
                  <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                    <i class="icon-lg mdi mdi-codepen text-primary ms-auto"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4 grid-margin">
            <div class="card">
              <div class="card-body">
                <h5>Sales</h5>
                <div class="row">
                  <div class="col-8 col-sm-12 col-xl-8 my-auto">
                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                      <h2 class="mb-0">$45850</h2>
                      <p class="text-success ms-2 mb-0 font-weight-medium">+8.3%</p>
                    </div>
                    <h6 class="text-muted font-weight-normal"> 9.61% Since last month</h6>
                  </div>
                  <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                    <i class="icon-lg mdi mdi-wallet-travel text-danger ms-auto"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4 grid-margin">
            <div class="card">
              <div class="card-body">
                <h5>Purchase</h5>
                <div class="row">
                  <div class="col-8 col-sm-12 col-xl-8 my-auto">
                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                      <h2 class="mb-0">$2039</h2>
                      <p class="text-danger ms-2 mb-0 font-weight-medium">-2.1% </p>
                    </div>
                    <h6 class="text-muted font-weight-normal">2.27% Since last month</h6>
                  </div>
                  <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                    <i class="icon-lg mdi mdi-monitor text-success ms-auto"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
        {{-- <div class="row ">
          <div class="col-12 grid-margin">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Order Status</h4>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>
                          <div class="form-check form-check-muted m-0">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                            </label>
                          </div>
                        </th>
                        <th> Client Name </th>
                        <th> Order No </th>
                        <th> Product Cost </th>
                        <th> Project </th>
                        <th> Payment Mode </th>
                        <th> Start Date </th>
                        <th> Payment Status </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <div class="form-check form-check-muted m-0">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                            </label>
                          </div>
                        </td>
                        <td>
                          <img src="assets/images/faces/face1.jpg" alt="image" />
                          <span class="ps-2">Henry Klein</span>
                        </td>
                        <td> 02312 </td>
                        <td> $14,500 </td>
                        <td> Dashboard </td>
                        <td> Credit card </td>
                        <td> 04 Dec 2019 </td>
                        <td>
                          <div class="badge badge-outline-success">Approved</div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check form-check-muted m-0">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                            </label>
                          </div>
                        </td>
                        <td>
                          <img src="assets/images/faces/face2.jpg" alt="image" />
                          <span class="ps-2">Estella Bryan</span>
                        </td>
                        <td> 02312 </td>
                        <td> $14,500 </td>
                        <td> Website </td>
                        <td> Cash on delivered </td>
                        <td> 04 Dec 2019 </td>
                        <td>
                          <div class="badge badge-outline-warning">Pending</div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check form-check-muted m-0">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                            </label>
                          </div>
                        </td>
                        <td>
                          <img src="assets/images/faces/face5.jpg" alt="image" />
                          <span class="ps-2">Lucy Abbott</span>
                        </td>
                        <td> 02312 </td>
                        <td> $14,500 </td>
                        <td> App design </td>
                        <td> Credit card </td>
                        <td> 04 Dec 2019 </td>
                        <td>
                          <div class="badge badge-outline-danger">Rejected</div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check form-check-muted m-0">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                            </label>
                          </div>
                        </td>
                        <td>
                          <img src="assets/images/faces/face3.jpg" alt="image" />
                          <span class="ps-2">Peter Gill</span>
                        </td>
                        <td> 02312 </td>
                        <td> $14,500 </td>
                        <td> Development </td>
                        <td> Online Payment </td>
                        <td> 04 Dec 2019 </td>
                        <td>
                          <div class="badge badge-outline-success">Approved</div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-check form-check-muted m-0">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input">
                            </label>
                          </div>
                        </td>
                        <td>
                          <img src="assets/images/faces/face4.jpg" alt="image" />
                          <span class="ps-2">Sallie Reyes</span>
                        </td>
                        <td> 02312 </td>
                        <td> $14,500 </td>
                        <td> Website </td>
                        <td> Credit card </td>
                        <td> 04 Dec 2019 </td>
                        <td>
                          <div class="badge badge-outline-success">Approved</div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Today appointments</h4>
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                        <tr>
                            <th style="color:white"><b>Athelete</b></th>
                            <th style="color:white"><b>Start Time</b></th>
                            <th style="color:white"><b>End  Time</b></th>
                       </tr>
                        </thead>  
                        <tbody>
                          @php   $events = App\Models\Booking::with('Athlete')->where('therapist_id',\Auth::guard('therapist')->user()->id)->whereDate('date', date('Y-m-d'))
            ->get();
                    
                         @endphp
                         @foreach ($events as $event)
                          <tr>
                            <td>
                             {{$event->athlete['name']}}
                            </td>
                            <td>  {{$event->start_time}}</td>
                            <td>  {{$event->end_time}}</td>
                          </tr>
                         @endforeach
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    
      <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © protolabzit.com 2023</span>
          
        </div>
      </footer>
    
    </div>
  
  </div>
</div>
<script src="https://www.tutorialspoint.com/jquery/jquery-3.6.0.js"></script>

  @endsection