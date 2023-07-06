<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
Use App\Models\Booking;

class ZoomController extends Controller
{   
    protected $baseUri = 'https://api.zoom.us/v2/';
    public function createMeeting()
    {   $accessToken =\Session::get('zoom_access_token');
        $client = new Client(['base_uri' => $this->baseUri]);
        $response = $client->post('users/me/meetings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'json' => [
                'topic' => 'My Zoom Meeting',
                'type' => 2,
                'start_time' => date('Y-m-d\TH:i:s\Z'),
                'duration' => 60,
                'timezone' => 'America/New_York',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Handle the response, e.g., retrieve join URL, meeting ID, etc.
        $joinUrl = $responseData['join_url'];

        //\Session::put('zoom_access_token', $accessToken);
     //   return redirect()->route('chat');
     
         
        return view('zoom')->with(compact('joinUrl'));
    }

    public function zoomAuthorize()
    {
        // \Session::forget('zoom_access_token');
       
        // $clientId = '4PI49jSsR9aiH1Fhdfe72A';
        // $redirectUri = 'https://phpstack-102119-3423473.cloudwaysapps.com/callback';
        // $zoomAuthUrl = 'https://zoom.us/oauth/authorize';
        // $params = [
        //     'response_type' => 'code',
        //     'client_id' => $clientId,
        //     'redirect_uri' => $redirectUri,
        // ];

        

        // return redirect()->away($zoomAuthUrl . '?' . http_build_query($params));

        $client = new Google_Client();
        $client->setAuthConfig('menseni.json');
        $client->setRedirectUri(route('callback'));
        $client->addScope('https://www.googleapis.com/auth/calendar.events');

        $authUrl = $client->createAuthUrl();

        dd($authUrl);

        return redirect()->away($authUrl);


    }

   


    public function callback(Request $request)
{
    $clientId = '4PI49jSsR9aiH1Fhdfe72A';
    $clientSecret = '2bhEAfoEfXH6WNeAF4XDc5VtSoS2Frwk';
    $redirectUri = 'https://phpstack-102119-3423473.cloudwaysapps.com/callback';
    $tokenEndpoint = 'https://zoom.us/oauth/token';
    $authCode = $request->query('code');
   
    $client = new Client(['base_uri' => $this->baseUri]);
  

    $data = [
        'grant_type' => 'authorization_code',
        'code' => $authCode,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
    ];

     

    try {
        $response = $client->post($tokenEndpoint, [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $authCode,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
            ],
        ]);

     

        $accessToken = json_decode($response->getBody(), true)['access_token'];
        //  dd($accessToken);
        // Store the access token in the database or session for future use
        // For simplicity, we'll store it in the session here
        \Session::put('zoom_access_token', $accessToken);
       // session(['zoom_access_token' => $accessToken]);











        return redirect()->route('createMeeting');
    } catch (RequestException $e) {
       // dd($response);
       
        // Handle the request exception
        $statusCode = $e->getResponse()->getStatusCode();
        $responseBody = json_decode($e->getResponse()->getBody(), true);

        // Log or display the error details for debugging
        Log::error("Error requesting access token from Zoom API: " . $e->getMessage());
        Log::error("Status Code: " . $statusCode);
        Log::error("Response Body: " . print_r($responseBody, true));

        // Redirect the user to an error page or display an error message
        return redirect()->back()->with('error', 'Failed to request access token from Zoom API. Please try again later.');
    }
}


public function eventCalender()
{   
    
    return view('calender');
}

public function getEvent(){
   
    if(request()->ajax()){
     $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
     
     $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
    
     $events = Booking::with('Athlete')->where('therapist_id',\Auth::guard('therapist')->user()->id)->whereBetween('date', [$start, $end])
            ->get()->toArray();
    
            return response()->json($events);
    }
    return view('calender');


}

public function storeToken(Request $request)
{   //dd($request->token);
    \DB::table('therapists')->where('id',\Auth::guard('therapist')->user()->id)->update(['fcm_token'=>$request->token]);
    return response()->json(['Token successfully stored.']);
}








}
