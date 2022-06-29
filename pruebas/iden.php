<?php


    $URL = "http://google.com";
    $conexion = curl_init();
    curl_setopt($conexion, CURLOPT_URL, $URL);
    curl_setopt($conexion, CURLOPT_HTTPGET, TRUE);
    curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //curl_setopt($conexion, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSV1_2);
    curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($conexion, CURLOPT_USERPWD, "usuario:pass");

    $respuesta = curl_exec($conexion);
    if($respuesta!=null){
        echo 'esto tiene algo';
    }else{
        echo 'no tiene nads';
    }
    //echo 'dddd'.$respuesta;
    curl_close($conexion);




?>