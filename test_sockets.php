<?php
// Set your server key and device registration token
$serverKey = 'AAAAfvIBv_s:APA91bENsFTT4XLkcbNoNs1XA-YQm3xYatYPr9VOjDfWzmRmfgIJDN3D560Tymw91tZJMHDIMtOkt3tU1PpdiI3S6Ea04vCZZEQMY9wvfemSKpqgqDCdzxu3rO1haVofOkOK3Yuo3Jgc';
$deviceToken = 'e7MB2xXAffNBY2KJR-fFKW:APA91bED9nMpthi2QYMXQbMa3CLjN0njBRrirPki_Vjx_LDIW5JCfmYzyKhFfOv9MMpKYQ-Onnvsv3y-5_aXCjRCFRda3X9Mx0LSQc-mMAo6jtxS4_81odmmKygmInkKHJ612pK2hvfc';


$notification = [
    'title' => 'Notification Title',
    'body' => 'Notification Body',
    'icon' => 'https://example.com/icon.png',
    'click_action' => 'https://example.com'
];

// Set request data
$data = [
    'to' => $deviceToken,
    'notification' => $notification
];

// Set cURL options
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
    CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER=>false,
    CURLOPT_SSL_VERIFYHOST=> false,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $serverKey
    ]
]);

// Send the request
$response = curl_exec($curl);
curl_close($curl);
print_r($response); die;
// Handle the response
if ($response === false) {
    echo 'Error: ' . curl_error($curl);
} else {
    echo 'Notification sent successfully!';
}

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
	
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: key=' . $serverKey
    ]
]);

// Send the request
$response = curl_exec($curl);
curl_close($curl);

// Handle the response
if ($response === false) {
    echo 'Error: ' . curl_error($curl);
} else {
    echo 'Notification sent successfully!';
}
?>