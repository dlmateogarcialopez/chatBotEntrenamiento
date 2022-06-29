<?php

error_reporting(-1);
ini_set('display_errors', 'On');
require 'lib.php';

$direccion = "";
$nombre = "";
$cedula = "";
$nit = "";
$telefono = "";
$response = array();
$api = new chatBotApi();

$reqBody = $api->detectRequestBody();
if (isset($reqBody['token'])) {
    if ($reqBody['token'] == "gdjSkskBBdbbA!jdndn") {
        if (isset($reqBody['criterio'])) {
            if ($reqBody['criterio'] == 'niu') {
                if (isset($reqBody['dato_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "") {
                        $niu = $reqBody['dato_busqueda'];
                        $response['niu_consultado'] = $niu;
                        $response['turnos'] = $api->wsTurnosBpmco($niu, false);
                    } else {
                        $response = "No se ha enviado el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el dato de búsqueda";
                }
            }
            else{
                $response = "Criterio de búsqueda invalido.";
            }
        } else {
            $response = "No esta definido el criterio de búsqueda";
        }
    }else{
        $response = "El token no corresponde con los parametros de seguridad";
    }
} else {
    $response = "No hay un token definido para la verificación";
}

header("Content-Type: application/json");
echo json_encode($response);
