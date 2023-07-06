@extends('Admin.layouts.App')
@section('notification','menu-open')
{{-- @section('dashboard_active','active') --}}
<style>
    nav#sidebar {
      width: 20% !important;
      position: fixed;
    overflow-y: auto;
    height: 100%;
    visibility: visible;
  }
  
  .form-control {
    height: inherit;
}

.stretch-card {
    width: 57% !important;
    margin: 0px 0px 0px auto;
    position: relative;
    top: 22%;
    right: 11%;
   
  
}
.card-body{
  background: #000 !important;
}

label {
    margin-bottom: 12px;
}

.card {
    border: 2px solid #191c24;
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
.toast-error {
    background-color: #3533a7 !important;
}
    </style>
@section('main_section')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Add Notification</h4>
      
      <form class="forms-sample" id="form">
        @csrf
        <div class="form-group">
          <label for="email">Select User:</label><br>
          <select class="form-control" id="userType"  name="userType" style="width: 100%;height:37px;" >
            <option value="Athlete">Athlete</option>
            <option value="Therapist">Therapist</option>
          </select>
        </div>

        <div class="form-group athlete_d"  >
          <label for="email">Select Athlete:</label><br>
          <select class="form-control js-example-basic-multiple" id="athlete" name="athlete[]" multiple="multiple" style="width: 100%;height:37px;"  >
            <option value="All">All</option>
            @foreach($athletes as $athlete)
            <option value="{{$athlete->id}}">{{$athlete->name}}</option>
            @endforeach
           
         </select>
        </div>

        <div class="form-group therapist_d" style="display: none;">
          <label for="email">Select Therapist:</label><br>
          <select class="form-control js-example-basic-multiple" id="therapist" name="therapist[]" multiple="multiple" style="width: 100%;height:37px;" >
            <option value="All">All</option>
            @foreach($therapists as $therapist)
            <option value="{{$therapist->id}}">{{$therapist->name}}</option>
            @endforeach
           
         </select>
        </div>


        
        {{-- <input type="button" id="select_all" name="select_all" value="Select All"> --}}
        <div class="form-group">
          <label for="exampleInputName1">Title</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
        </div>
      
        <div class="form-group">
          <label for="exampleTextarea1">Description</label>
          <textarea class="form-control" id="description" name="description" rows="4" cols="40" placeholder="Enter Description"></textarea>
        </div>
        <div class="text-center">
        <button type="submit" class="btn btn-primary w-25">Submit</button>
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
<script>
 
 $(document).ready(function () {
// alert('hh');
$('.js-example-basic-multiple').select2();
  $('#form').on('submit', function(event){
      event.preventDefault();
    
     const fd = $('#form').serialize();
    
     $.ajax({
       type: "POST",
        url: "{{url('Admin/save_notification')}}",
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
                'Notication Added!',
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
    <script>
      $(document).ready(function () {
        $.fn.select2.amd.define('select2/selectAllAdapter', [
    'select2/utils',
    'select2/dropdown',
    'select2/dropdown/attachBody'
], function (Utils, Dropdown, AttachBody) {

    function SelectAll() { }
    SelectAll.prototype.render = function (decorated) {
        var self = this,
            $rendered = decorated.call(this),
            $selectAll = $(
                '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-check-square-o"></i> Select All</button>'
            ),
            $unselectAll = $(
                '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-square-o"></i> Unselect All</button>'
            ),
            $btnContainer = $('<div style="margin-top:3px;">').append($selectAll).append($unselectAll);
        if (!this.$element.prop("multiple")) {
            // this isn't a multi-select -> don't add the buttons!
            return $rendered;
        }
        $rendered.find('.select2-dropdown').prepend($btnContainer);
        $selectAll.on('click', function (e) {
            var $results = $rendered.find('.select2-results__option[aria-selected=false]');
            $results.each(function () {
                self.trigger('select', {
                    data: $(this).data('data')
                });
            });
            self.trigger('close');
        });
        $unselectAll.on('click', function (e) {
            var $results = $rendered.find('.select2-results__option[aria-selected=true]');
            $results.each(function () {
                self.trigger('unselect', {
                    data: $(this).data('data')
                });
            });
            self.trigger('close');
        });
        return $rendered;
    };

    return Utils.Decorate(
        Utils.Decorate(
            Dropdown,
            AttachBody
        ),
        SelectAll
    );

});

$('#parent_filter_select2').select2({
    placeholder: 'Select',
    dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter')
});
  });

  $(document).ready(function() {
    $('#userType').change(function() {
      if(this.value == "Athlete"){
      $('.therapist_d').hide();   
      $('.athlete_d').show(); 
      }else{
      $('.athlete_d').hide();     
      $('.therapist_d').show(); 
      }
    });
});  
      </script>
