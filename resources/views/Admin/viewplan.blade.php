@extends('Admin.layouts.App')
@section('viewplan','menu-open')
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
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">View Plans</h4>
       
        <div class="table-responsive">
          <table id="Table_ID" class="table table-bordered" >
            <thead>
              <tr>
                <th> # </th>
                <th> Image </th>
                <th> Message Sender </th>
                <th> Name </th>
                <th> Description	 </th>
                <th> Time </th>
                <th> Date </th>
                <th> Action </th>
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
         url: "{{ url('Admin/viewplan') }}",
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
     
         
           {data: 'image', name: 'image'},
           {data: 'message_from', name: 'message_from'},          
           {data: 'name', name: 'name'},  
           {data: 'description', name: 'description'},  
           {data: 'time', name: 'time'},  
           {data: 'date', name: 'date'}, 
           {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },             
       
       ],
       order: [ [1, 'desc'] ]
   });
       
        
       

         table.on('draw.dt', function() {
          
     

  
       });
     });
   

 
       </script>
<div class="modal fade" id="yourModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>