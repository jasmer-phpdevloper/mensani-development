<!DOCTYPE html>
<html>
   <head>
      {{-- <title>How To Integrate Stripe Payment Gateway In Laravel 8 - Techsolutionstuff</title> --}}
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>      
   </head>
   <style>
      body {
    background-color: black;
    color: lightgray;
   }
   .panel.panel-default.credit-card-box {
    background-color: darkolivegreen;
    margin: auto;
    width: 100%;
}
   .credit-main {
    width: 100%;
    max-width: 600px;
    display: flex;
    height: 100vh;
    margin: auto;
    padding: 0px 15px;
}
.form-row.row {
    align-items: baseline;   
    display: flex;
    justify-content: center;

}
.panel.panel-default.credit-card-box {
    background-color: rgb(41, 41, 37);
}
button.btn.btn-primary.btn-lg.btn-block {
    margin-bottom: 10px;
    background-color: rgb(43, 51, 51);
    width: 100%;
    max-width: 200px;
    margin: 0 auto;
    margin-bottom: 15px;
}
@media screen and (max-width: 768px) {

   .credit-main {
   
    height: auto;
    padding: 30px 15px;
   }
}


#loader {
   position: fixed;
    top: 50%;
    background-color: #f6a702;
    border-radius: 5px;
    padding: 10px;
    color: #fff;
    width: 100%;
    max-width: 254px;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: end;
}
#loader h5 {
    margin-bottom: 0px;
  
}



#loader:before {
   content: "";
    width: 26px;
    position: absolute;
    top: 9px;
    left: 11px;
    height: 26px;
    border-radius: 50%;
    border: 5px solid #fff;
    border-top-color: #0ba5ff;
    animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

   </style>   
   <body>
      <div class="credit-main">         
      
        
               <div class="panel panel-default credit-card-box">
                  <div class="panel-heading" >
                     <div class="row-btn">
                        <h3 style="text-align: center;" class="pt-3">Payment Details</h3>                        
                     </div>
                  </div>
                  <div class="panel-body">
                     @if (Session::has('success'))
                     <div class="alert alert-success text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <p>{{ Session::get('success') }}</p><br>
                     </div>
                     @endif
                     <br>
                     <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                        @csrf
                        {{-- <div class='form-row row'> --}}
                           <div class='col-xs-12 col-xl-12 col-md-12 col-sm-12 form-group required'>
                              <label class='control-label'>Name on Card</label> 
                              <input class='form-control' size='4' type='text' maxlength="10"  required>
                           </div>
                           <div class='col-xs-12 col-xl-12  col-md-12 col-sm-12  form-group required'>
                              <label class='control-label'>Card Number</label> 
                              <input autocomplete='off' class='form-control card-number' maxlength="16" size='20' type='number' required>
                           </div>                           
                        {{-- </div> --}}
                        <input type='hidden' name="user_id" value="{{$user_id}}">
                        <input type='hidden' name="sub_id" value="{{$sub_id}}">
                        <input type='hidden' name="amount" value="{{$amount}}">                        
                        <div class='form-row row px-3'>
                          
                           <div class='col-xs-12 col-xl-6 col-md-12 col-sm-12 form-group expiration required'>
                              <label class='control-label'>Expiration Month</label> 
                              <input class='form-control card-expiry-month' placeholder='MM' size='2'  maxlength="2" type='number' required>
                           </div>
                           <div class='col-xs-12 col-md-12 col-sm-12  col-xl-6 form-group expiration required'>
                              <label class='control-label'>Expiration Year</label> 
                              <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='number' required>
                           </div>
                        </div>                     
                        <div class='col-xs-12 col-md-12 col-sm-12 col-xl-6 form-group cvc required'>
                           <label class='control-label'>CVC</label> 
                           <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='number' required>
                        </div>
                        




                        <div class="form-row pay-btn">
                          
                              <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                              <div id="loader" style="display:none;" style="color:white"><h5>Processing payment...</h5></div>
                        </div>
                     </form>
                  </div>
               </div>
         
       
      </div>
   </body>   
</html>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(function() {
    var $form = $(".require-validation");
    var loader = document.getElementById('loader');
    $('form.require-validation').bind('submit', function(e) {
        // alert('sdsddsds');
        $("#loader").attr("style", "display:block") 
        var $form = $(".require-validation"),
        
        inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]', 'input[type=file]', 'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),

       
        valid = true;
        $errorMessage.addClass('hide');
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
              number: $('.card-number').val(),
              cvc: $('.card-cvc').val(),
              exp_month: $('.card-expiry-month').val(),
              exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
    });

    function stripeResponseHandler(status, response) {
       
     
      if(response.error) {
            $('.error')
            .removeClass('hide')
            .find('.alert')
            .text(response.error.message);
            $("#loader").attr("style", "display:none") 
        }else {
          /* token contains id, last4, and card type */
          var token = response['id'];
          $form.find('input[type=text]').empty();
          $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
          $form.get(0).submit();
        }

        $("#loader").attr("style", "display:none") 
    }
});
</script>