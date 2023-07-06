<!doctype html>
    <html lang="en-US">
    <head>
        <title>Video Conferencing, Web Conferencing, Online Meetings, Screen Sharing - Zoom</title> <!--customize landing page title-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="keywords" content="zoom, zoom.us, video conferencing, video conference, online meetings, web meeting, video meeting, cloud meeting, cloud video, group video call, group video chat, screen share, application share, mobility, mobile collaboration, desktop share, video collaboration, group messaging" />
        <meta name="description" content="Zoom unifies cloud video conferencing, simple online meetings, and cross platform group chat into one easy-to-use platform. Our solution offers the best video, audio, and screen-sharing experience across Zoom Rooms, Windows, Mac, iOS, Android, and H.323/SIP room systems." />
        <link rel="shortcut icon" href="https://d24cgw3uvb9a9h.cloudfront.net/zoom.ico"/>
        <style>
            html, body {
                margin: 0px;
                font-family: sans-serif;
                height: 100%
            }
            body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    h1 {
      color: #333333;
    }
    
    p {
      margin-bottom: 10px;
      color: #666666;
    }
    
    .btn-primary {
      display: inline-block;
      padding: 10px 20px;
      background-color: #337ab7;
      color: #ffffff;
      text-decoration: none;
      border-radius: 4px;
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
            .container {
                display: table;
                height: 100%;
                width: 100%;
                background-image: url("https://d24cgw3uvb9a9h.cloudfront.net/static/93386/image/new/home/DefaultLandingBgImg.jpg");  /*customize background image*/
                background-size: cover;
            }
            .content-body {
                display: table-cell;
                vertical-align: middle;
            }
            a {
                text-decoration: none;
                color: #2470cc;
            }
    
            a:hover {
                text-decoration: none;
            }
            .content {
                /*customize position*/
                padding: 40px;
                max-width: 450px;
                margin-left: 45%;
            }
            h1 {
                padding-top: 20px;
                padding-bottom: 20px;
                font-weight: normal;
            }
            p {
                font-size: 17px;
            }
            .button {
               
                background-color: #2D8CFF; /*customize button color */
                border-color: #2D8CFF;
                padding: 12px 20px;
                display: inline-block;
                text-align: center;
                margin: 10px 0;
                transition: .3s ease;
            }
            .button:hover {
                background-color: rgb(45, 165, 255);  /*customize button color */
            }
            .btn-text {
                color: white;   /*customize button font color */
                font-size: 14px;
            }
            .made-with {
                padding-top: 15px;
                padding-bottom: 0;
                margin-bottom: 0;
            }
            .row > div {
                display: inline-block;
                vertical-align: middle;
            }
            .right {
                margin-left: 15px;
            }
            .right > span{
                display: block;
            }
    
            .zoom-logo {
                width: 140px;
                height: 31.5px;
            }
            .footer {
                display: table-row;
                height: 60px;
                text-align: center;
                background-color: rgba(255, 255, 255, 0.4);
                box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.15);
                vertical-align: middle;
            }
            .footer a {
                padding-right: 40px;
                width: 99px;
                height: 19px;
                opacity: 0.6;
                font-size: 14px;
                font-weight: normal;
                font-style: normal;
                font-stretch: normal;
                line-height: normal;
                letter-spacing: normal;
                color: #2d292a !important;
                line-height: 60px;
            }
    
            @media screen and (max-width: 768px) {
                h1 {
                    font-size: 27px;
                }
                .row {
                    margin-bottom: 15px;
                }
                .row > div {
                    display: block;
                }
    
                .footer {
                    padding-bottom: 15px;
                    height: 40px;
                }
    
                .footer a {
                    display: block;
                    width: 100%;
                    height: inherit;
                    padding-left: 0;
                    padding-right: 0;
                }
    
                .content {
                    margin: auto;
                }
    
                .content > div, .made-with {
                    text-align: center;
                }
            }
            .content-body .back-btn {
            position: absolute;
            top: 0;
            left: 0;
            border: 1px solid;
            max-width: 65px;
            padding: 3px 14px;
            font-size: 15px;
            display: block;
            background-color: #2d8cff;
            color: white;
        }
        .content-body {
            display: table-cell;
            vertical-align: middle;
            position: relative;
        }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="content-body" role="main" aria-label="main content">
            <div class="HiddenText"><a id="the-main-content" tabindex="-1"></a></div>
            <div class="content"><!--customize position-->
                <div class="row">
                    <img src="https://d24cgw3uvb9a9h.cloudfront.net/static/93386/image/new/ZoomLogo.png" class="zoom-logo" alt="Zoom Logo">  <!--customize company logo-->
                </div>
                <div class="row">
                    <h1> Video Conferencing </h1>
                    @php 

                     $variable = session()->get('chat_url');

                    @endphp
                    <a href="{{$variable}}" class="btn-btn-primary back-btn"> <i class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="row">
                    <div class="left">

                        <a href="{{@$joinUrl}}" class="button" target="_blank"><span class="btn-text">Join</span></a>
                    </div>
                    <div class="right">
                        <span>Connect to a meeting in progress</span>
                            
                    </div>
                </div>
                <div class="row">
                    <div class="left">
                        <button id="copyLinkButton" class="button">
                            <span class="btn-text">Copy link</span>
                        </button>
                    </div>
                    <div class="right">
                        <span>Click on the copy link button to send the zoom call link to the athlete</span>
                    </div>
                </div>
                <div class="row">
                    <div class="left">
                    </div>
                </div>
              
            </div>
        </div>
        
    </div>

    
        </body> 
        <script>
            document.getElementById("copyLinkButton").addEventListener("click", function() {
              var zoomCallLink = "{{@$joinUrl}}";
              navigator.clipboard.writeText(zoomCallLink)
                .then(function() {
                  alert("Zoom call link copied to clipboard!");
                })
                .catch(function(error) {
                  console.error("Failed to copy Zoom call link: ", error);
                });
            });
          </script>      
</html>
