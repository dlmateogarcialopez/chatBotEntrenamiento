<?php

$post['text'] = 'PRUEBA DE ENVIO MENSAJE NUEVO PROVEEDOR';

$post['to'] = '573186336322';
$post['from'] = 'FROM UM';
// $post['campaignName'] = 'Envio APERTURA Interrupcion no programada';
// $post['notificationUrl'] = "http://52.147.221.17/reglasDifusion/acuses_recibo.php?idenvio=umanizales12345&niu=$niu&apertura=$apertura&tel=$celular&estado=%d&fecha=%t&tipo=$tipo";

// {
//     "from":"InfoSMS",
//     "to":"41793026727",
//     "text":"My first SMS."
//  }
// ae8b0cde61ba43556dabb5fe55c16adf-15494b26-709c-4229-a438-cc04c04801c6
$user = 'chec-u';
$password = 'chec2020';
try {
    $ch = curl_init();

    // curl_setopt_array($ch, array(
    //     CURLOPT_URL => 'http://api.messaging-service.com',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS => json_encode($post),
    //     CURLOPT_HTTPHEADER => array(
    //       'accept: application/json',
    //       'authorization: Basic '.base64_encode($user.':'.$password),
    //       'content-type: application/json',
    //     ),
    //   ));

    // curl_setopt($ch, CURLOPT_URL, 'https://gateway.plusmms.net/rest/message');
    curl_setopt($ch, CURLOPT_URL, 'http://api.messaging-service.com');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Authorization: Basic '.base64_encode($user.':'.$password),
        'content-type: application/json',
    ));
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    var_dump($result);
    var_dump($err);
} catch (\Throwable $th) {
    var_dump('Error');
    var_dump($th);
}
