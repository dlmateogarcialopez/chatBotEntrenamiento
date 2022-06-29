<?php

require_once __DIR__ . '/vendor/autoload.php';

//redirection
$token = "EAAEbDdhspC4BALmpd322QT7nh1t6U2RKyaZB94oJnTyCl7ZAw9jM1AnflhIbwfYFDNqm0AE3FRfsIDBtoEtK86z219PZAUm5W44wfVxQarWo29u0cwLOpTYf9VnrvGLH9wdxXX8xgiD6ioqMTN3vJEbuj8uTtLOueTUfgE33AZDZD";

$senderid = '2868075403232590';

$fb = new \Facebook\Facebook([
    'app_id' => '311221256234030',
    'app_secret' => '9bbe00eaf82a480085446c30dfc616ca',
    'default_graph_version' => 'v3.3',
]);

$response = '';
$campos = "id,name,first_name,last_name";
try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get("/$senderid?fields=$campos", $token);
    //$response = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    $response = 'Graph returned an error: ' . $e->getMessage();

} catch (Facebook\Exceptions\FacebookSDKException $e) {
    $response = 'Facebook SDK returned an error: ' . $e->getMessage();

}

$user = $response->getGraphObject();

$url = "https://graph.facebook.com/v2.6/me/messages?access_token=$token";

$jsonData = '{
            "recipient":{
            "id":"' . $senderid . '"
            },
            "message":{
                "text":"' . $user['name'] . ' 😃 Ahora te atenderá un asesor, deja tu mensaje y alguien se comunicará contigo"
            }
            }';
$url = "https://graph.facebook.com/v2.6/me/messages?access_token=$token";
//Initiate cURL.
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result = curl_exec($ch);

$url = "https://graph.facebook.com/v2.6/me/pass_thread_control?access_token=$token";
/* $jsonData = '{
"recipient":{
"id":"' . $senderid . '",
"target_app_id":"263902037430900",
"metadata":"String to pass to secondary receiver app"
}
}'; */

$jsonData = '{
            "recipient" : {
                "id" : "' . $senderid . '" },
                "target_app_id" : 311221256234030,
                "metadata" : "String to pass to secondary receiver app"
            }';

//Initiate cURL.
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result = curl_exec($ch);
