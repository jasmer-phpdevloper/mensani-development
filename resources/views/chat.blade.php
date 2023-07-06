<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Free Web tutorials">
  <meta name="keywords" content="HTML, CSS, JavaScript">
  <meta name="author" content="John Doe">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
     <style>
   body {
  background: rgb(17,102,126);
  background: -moz-radial-gradient(circle, rgba(17,102,126,1) 0%, rgba(0,8,81,1) 100%);
  font-family: sans-serif;
}




#chatbot-container{
  z-index: 999;
  color: #2c2325;
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: grayscale(0.8);
}

#chatbot-interface {
  height: 70vh;
  width: 60vw;
  background-color: #2c2325;
  border-radius: 1rem;
}

#chatbot-header {
  font-weight: 600;
  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;
  /* background: radial-gradient(ellipse farthest-corner at right bottom, #FEDB37 0%, #FDB931 8%, #9f7928 30%, #8A6E2F 40%, transparent 80%),
    radial-gradient(ellipse farthest-corner at left top, #D1B464 25%, #5d4a1f 62.5%, #5d4a1f 100%); */
    background: #F6A800;
  padding: 0.2rem;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  display: flex;
  flex-wrap: nowrap;
  justify-content: space-between;
  align-items: center;
}

#chatbot-chat{
  height: calc(100% - 56.9px - 6rem);
  border-bottom-left-radius: 1rem;
  border-bottom-right-radius: 1rem;
  padding: 1rem;
  overflow-y: scroll;
  display: flex;
  flex-direction: column-reverse;
}

.chatbot-messages {
  padding: 1rem;
  padding-top: 0.2rem;
  padding-bottom: 0.2rem;
  border-radius: 1rem;
  margin-top: 0.3rem;
  margin-bottom: 0.3rem;
  width: fit-content;
}

.chatbot-received-messages {
  /* background: radial-gradient(ellipse farthest-corner at right bottom, #FDB931 8%, #9f7928 30%, #8A6E2F 40%, transparent 80%),
    radial-gradient(ellipse farthest-corner at left top, #D1B464 25%, #5d4a1f 62.5%, #5d4a1f 100%); */
    background:#3F3F3F;
  filter: grayscale(0.3);
  border-top-left-radius: 0rem;
}

.chatbot-sent-messages {
  color: white;
  background-color: #574449;
  border-top-right-radius: 0rem;
  margin-left: auto; 
  margin-right: 0;
}

.message-area .chatbot-received-messages {
    color: white;
}

#chatbot-footer {
  padding: 2rem;
  padding-top: 1rem;
  padding-bottom: 1rem;
  display: flex;
  flex-wrap: nowrap;
  justify-content: space-around;
  align-items: center;
}

#chatbot-input-container{
  width: calc(100%);
}

#chatbot-input {
  width: calc(100% - 2rem);
  padding: 0.5rem;
  color: white;
  background-color: #574449;
  border: 0.1rem solid #574449;
  border-radius: 1rem;
}

#chatbot-input:focus {
  outline-offset: 0px !important;
  outline: none !important;
  border: 0.1rem solid #8A6E2F;
  /*box-shadow: 0 0 5px #FDB931 !important;*/
}

#chatbot-new-message-send-button{
  cursor: pointer;
}

#send-icon {
    color: white;
    background: #5E5CE6;
    border: none;
    padding: 5px 17px;
    font-size: 14px;
    border-radius: 13px;
}
#chatbot-open-container {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
   background:radial-gradient(ellipse farthest-corner at right bottom, #F6A800  0%, #F6A800  8%, #9f7928 30%, #8A6E2F 40%, transparent 80%),
    radial-gradient(ellipse farthest-corner at left top, #D1B464 25%, #5d4a1f 62.5%, #5d4a1f 100%); 

  padding: 1rem;
  border-radius: 50%;
  width: 3.5rem;
  height: 3.5rem;
  text-align: center;
  cursor: pointer;
  z-index: 1000;
}
#chatbot-open-container i{
  padding-top: 0.25rem;
  font-size: 3rem;
  color: #2c2325;
}

.message-area div#chatbot-header p {
    margin-bottom: 0px;
}

.message-area div#chatbot-header p img {
    border-radius: 40px;
    margin-right: 15px;
    width: 100%;
    max-width: 52px;
}
.message-area #chatbot-chat .chatMessage {
    position: relative;
    margin-top: 24px;
    margin-bottom: 24px;
}
.message-area #chatbot-chat .chatMessage .created_at {
    position: absolute;
    font-size: 12px;
    color: #ffff;
    white-space: nowrap;
    top: -18px;
    right: 0px;

}
.message-area #chatbot-chat .chatMessage .created_ats {
    position: absolute;
    font-size: 12px;
    color: #ffff;
    white-space: nowrap;
    top: -18px;
    left: 0px;
}

div#chatbot-header a {
    color: #1C1C1E;
    font-size: 15px;
    background-color: #fff;
    display: inline-block;
    padding: 1px 9px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 30px;
}
div#chatbot-header a i {
    margin-right: 1px;
}
p{
  line-break: anywhere;

}
@media (max-width:768px) { 
  #chatbot-container {
    align-items: start;
    padding: 30px 30px;
}
#chatbot-footer {
    padding: 2rem 1rem;
}
#chatbot-interface {
    height: 85vh;
    width: 100%;
}
#chatbot-input {
    width: 96%;
}
div#chatbot-header a span {
    display: none;
}
div#chatbot-header a {
    display: inline;
    padding: 2px;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 26px;
}



}






          </style>
        
</head>
<body>
  
       <!-- char-area -->
       <section class="message-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="chat-area">
                        <!-- chatlist -->
                

                       @php
                       session()->forget('chat_url');
                       $currenturl = url()->full();
                       session()->put('chat_url', $currenturl);
                       @endphp


                        <div id="chatbot-container" >
                            <div id="chatbot-interface">
                              <div id="chatbot-header">
                                <p> <img class="" src="{{@$data->image}}" onerror="this.onerror=null;this.src='{{asset('storage/athleteimg/1682921017cropped4102272193752535966.jpg')}}'" style="height:50px;width:50px;object-fit: cover;">{{@$data->name}}</p>
                                @if(last(request()->segments()) == 'athletes')
                               
                                <a href="http://meet.google.com/new" class="btn-btn-primary" target="_blank"> <i class="fa fa-video-camera" aria-hidden="true"></i> <span> Google Meet</span></a>
                                <a href="{{route('chatListing')}}" class="btn-btn-primary" > <i class="fa fa-arrow-left" aria-hidden="true"></i><span> Back</span></a>
                                @endif
                                
                                
                              </div>
                              <div id="chatbot-chat">
                              
                                @foreach($chats as $message)
                                @if($message->sender_id == $sender_id)
                              
                                <div class="chatbot-messages chatMessage chatbot-sent-messages">
                                   
                                  <p>{{$message->chat_message}}</p>
                                  <p class="created_at">{{date("d M-Y H:i", strtotime($message->created_at))}}</p>
                                 
                                </div>
                               
                                @else
                               
                                <div class="chatbot-messages  chatMessage chatbot-received-messages">
                                 <p>{{$message->chat_message}}</p>
                                 <p class="created_ats">{{date("d M-Y H:i", strtotime($message->created_at))}}</p>
                                
                                </div>
                               
                                @endif
                                @endforeach

                              </div>
                              <div id="chat-messages" ></div>
                              <form id="chat-form" method="POST" action="{{ route('sendMessage') }}">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                              
                              <div id="chatbot-footer">
                                <div id="chatbot-input-container">
                                <input type="hidden" name="sender" value="{{@$athelete->name}}" placeholder="Your Name">   

                                <input type="hidden" name="receiver_id" value="{{$receiver_id}}"> 
                                <input type="hidden" name="sender_id" value="{{$sender_id}}"> 
                                <input type="hidden" name="type" value="{{$type}}"> 
                                <div id="typing-status-container" style="color:white;">
                                  <!-- Typing status message will be updated here -->
                                </div>  
                                  <input type="text" id="chatbot-input" name="chat_message" placeholder="Type your message here">
                                </div>
                                <div id="chatbot-new-message-send-button">
                                  {{-- <i class="material-icons" id="send-icon">send</i> --}}
                                  <button class="demo material-icons"  id="send-icon" type="submit">Send</button>
                                </div>
                              </div>
                            </form>
                            </div>
                          </div>
                          
                    </div>
                    <!-- chatbox -->


                </div>
            </div>
        </div>
      
    </section>

      <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        const pusher = new Pusher('1cf5d1a73237f5a3f242', {
            cluster: 'ap2',
            encrypted: true,
            useTLS: true
        });

        // Subscribe to the 'chat' channel
        const channel = pusher.subscribe('mensaini-development');

        console.log(channel);

        // Listen to the 'ChatMessageEvent' event
        channel.bind('mensaini-development', function(data) {
            console.log(data);
             const chatMessage = `${data.message.chat_message}`;
          

            const chat = document.getElementById("chatbot-chat");
            let messageDiv = document.createElement("div");
            let responseText = document.createElement("p");
            let currentTime = new Date().toLocaleTimeString();
            let timestamp = document.createElement("p");
            var sender_id ="{{$sender_id}}";
            if(data.message.sender_id == sender_id){
            timestamp.classList.add("created_at"); 
            }else{
             timestamp.classList.add("created_ats");     
            }

            timestamp.appendChild(document.createTextNode(currentTime));
       
            messageDiv.appendChild(timestamp);
          
               
            
            responseText.appendChild(document.createTextNode(chatMessage));

            console.log(responseText);
           
            if(data.message.sender_id == sender_id){
                messageDiv.classList.add("chatbot-messages", "chatbot-sent-messages");
                messageDiv.classList.add("chatMessage"); 

            }else{
                messageDiv.classList.add("chatbot-messages", "chatbot-received-messages");
                messageDiv.classList.add("chatMessage"); 

            }

            console.log(messageDiv);


            messageDiv.appendChild(responseText);
            chat.prepend(messageDiv);


             //document.getElementById('chat-messages').innerHTML += chatMessage;
            // $('.chatbot-messages').append(chatMessage);
        });


   
       $('#chat-form').submit(function(e) {
        e.preventDefault();

        const sender = $('[name="sender"]').val();
        const message = $('[name="chat_message"]').val();
        const sender_id = $('[name="sender_id"]').val();
        const receiver_id = $('[name="receiver_id"]').val();
        const type = $('[name="type"]').val();
       // newText = document.getElementById("chatbot-input").value;
  
       if (message == ""){
         return false; 
    
       }
      
       document.getElementById("chatbot-input").value = "";

        $.ajax({
            url: "{{ route('sendMessage') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                sender: sender,
                chat_message: message,
                sender_id: sender_id,
                receiver_id: receiver_id,
                type: type
            },
            success: function(response) {
                $('[name="chat_message"]').val(''); // Clear the input field after sending the message
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
    jQuery(document).ready(function() {

$(".chat-list a").click(function() {
    $(".chatbox").addClass('showbox');
    return false;
});

$(".chat-icon").click(function() {
    $(".chatbox").removeClass('showbox');
});


});
    
 const chat = document.getElementById("chatbot-chat");

document.getElementById("chatbot-input").addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
    //  newInput();
    }
});


</script>

</body>
</html>