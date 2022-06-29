<?php
//redirection
$url = 'http://40.87.47.53/chatbotUniversidad/bot_req.php';
$body = detectRequestBody();


$response = postRequest($url, $body);
echo $response;



function detectRequestBody()
{
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    return $input;
}

function postRequest($url, $data){
    $data_string = json_encode($data);

    $ch = curl_init($url);                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );  
    // Send the request & save response to $resp
    $resp = curl_exec($ch);
    // Close request to clear up some resources
    curl_close($ch);
    return $resp;
}