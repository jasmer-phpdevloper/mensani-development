@extends('Therapist.layouts.App')
@section('feedback','menu-open')
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
      margin: 0px 0px 0px auto;
    overflow-y: auto;
    visibility: visible;
      
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
input[type="search"] {
    margin-left: 5px;
}
div#Table_ID_paginate {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 1px;
    margin-bottom: 8px;
}

div#Table_ID_paginate a {
    color: white;
    text-decoration: none;
    border: 1px solid;
    padding: 2px 13px;
    margin: 0px 4px;
}
.alert-box {
    position: relative;
    margin: 0px auto;
    width: 36%;
    text-align: center;
}
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card">
        <div class="row">
          @if(session()->has('message'))
          <div class=" col-6 alert alert-success alert-box">
            
           <h4> {{ session('message') }}</h4>
        </div>
        @endif
      </div>
      <div class="card-body">
        <h4 class="card-title">View feedback</h4>
       
        <div class="table-responsive">
          <table id="Table_ID" class="table table-bordered" >
            <thead>
              <tr>
                <th> # </th>
                <th> Feedback </th>
                <th> Stars </th>
              
              </tr>
            </thead>
            <tbody>
             
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
   
    $(document).ready( function () {
        // alert('hh');
   var table = $('#Table_ID').DataTable({
       processing: true,
       serverSide: true,
       bDestroy:true,
       stateSave:true,
       ajax: {
         url: "{{ route('view_feedback') }}",
         data: function (d) {
             
             
           }
       },
       columnDefs: [
           { 
             width: "30%", 
            //  targets: 6, 
           },
       ],
       columns: [
           {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: true, searchable: false},
     
         
           {data: 'feedback', name: 'feedback'},
           {data: 'stars', name: 'stars'},          
                    
       
       ],
       order: [ [1, 'desc'] ]
   });
       
        
       

         table.on('draw.dt', function() {
          
     

  
       });
     });
   
     window.setTimeout(function () { 
         $(".alert-box").alert('close'); 
      }, 2000);
 
       </script>
