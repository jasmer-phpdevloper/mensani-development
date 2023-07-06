<!DOCTYPE html>
<html>
<head>
    <title>Appointment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  <style>
    
        #calendar .fc-widget-header span {
    font-size: 24px;
    font-weight: 500;
      }
        body {
            background-color: #191c24;
            color: white;
        }

    #calendar .event-details {
        text-align: center;
    }
    .back-btn {
    float: right;
    border: 1px solid white;
    font-size: 17px;
    font-weight: 500;
    background-color: #fff;
    padding: 4px 14px;
    margin-top: 10px;
    color: #000;
    border-radius: 4px;
}
 .fc-unthemed td.fc-today {
    background: #707070;
}
  </style>  
</head>
<body>
  
<div class="container">
    <a href="{{ url('Therapist/dashboard') }}" class="btn-btn-primary back-btn"> <i class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
    <h1>Events</h1>
    <div id='calendar'></div>
</div>
   
<script>
     $(document).ready(function () {
        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            navLinks: true,
            editable: true,
            events: "{{route('getEvent')}}",           
            displayEventTime: false,
            eventRender: function (event, element, view) {
               
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }

                var eventDetails = $('<div class="event-details"></div>');
                var userName = $('<span class="user-name">' + event.athlete_name + '</span>');
                   eventDetails.append(userName);
    
                   // Add the event time to the event details element
                var eventTime = $('<span class="event-time">'+ '(' + event.start_time + ' - ' + event.end_time + ')' +'</span>');
                 eventDetails.append(eventTime);
    
    // Add the user name to the event details element
                
    
    // Append the event details to the event element
                   element.append(eventDetails);

              
            },
        selectable: true,
        selectHelper: true,
        eventClick:  function(event) {
            //  $('#modalBody > #title').text(arg.event.title);
            //  $('#modalWhen').text(arg.event.start);
            //  $('.modal-content > #eventID').val(arg.event._def.defId);
            console.log(event.athlete_name);
         },
       
        });
    });
</script>
  
</body>
</html>