<?php
session_start();

require_once __DIR__.'/../functions.php';

$CLIENT_ID = 'your_client_id';
$CLIENT_SECRET = 'your_client_secret';
$USERNAME = 'example@mail.com';//your email
$PASSWORD = 'your_password';//your password

if (empty($_SESSION['virocket_token'])) {
    //get new access token
    $response = sendRequest(
        "http://app.virocket.com/oauth/v2/token",
        "POST",
        array(
            'grant_type'    => 'password',
            'client_id'     => $CLIENT_ID,
            'client_secret' => $CLIENT_SECRET,
            'username'      => $USERNAME,
            'password'      => $PASSWORD
        )
    );

    //store token to the session
    $_SESSION['virocket_token'] = json_decode($response, true);
}

//get saved token from the session
$token = $_SESSION['virocket_token'];
$videosResponse = sendRequest(
    "http://app.virocket.com/api/v1/videos", "GET",
    array(),
    array(
        'Authorization: Bearer ' . $token['access_token']
    )
);

$videos = json_decode($videosResponse, true);
foreach ($videos['data'] as $video) {
    echo '<a href="'. $video['url'] .'">Download video "'. $video['name'] .'"</a><br>';
}

//get next page
if (!empty($videos['next'])) {
    $videosResponse = sendRequest(
        "http://app.virocket.com/api/v1/videos?page={$videos['next']}", "GET",
        array(),
        array(
            'Authorization: Bearer ' . $token['access_token']
        )
    );

    $videos = json_decode($videosResponse, true);
    foreach ($videos['data'] as $video) {
        echo '<a href="'. $video['url'] .'">Download video "'. $video['name'] .'"</a><br>';
    }
}
