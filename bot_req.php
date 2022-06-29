<?php

error_reporting(-1);
ini_set('display_errors', 'On');
//prueba

require './lib.php';

//Instancia de la API
$api = new chatBotApi();
$Nombre = '';
$Telefono = '';
$Direccion = '';
$number = '';
$response = '';
$responseContexts = true;

//Almacena los contextos de la petición
//$contexts = array();

//Obtener el cuerpo de la petición que viene de API.ai

$reqBody = $api->detectRequestBody();

//Verifica si de la petición se recibe la entidad number
if (isset($reqBody['queryResult']['parameters']['number'])) {
    $number = strval($reqBody['queryResult']['parameters']['number']);
} elseif (isset($reqBody['queryResult']['parameters']['phone-number'])) {
    $number = strval($reqBody['queryResult']['parameters']['phone-number']);
}

//Verifica si de la petición se recibe la entidad nombre
if (isset($reqBody['queryResult']['parameters']['any'])) {
    $Nombre = $reqBody['queryResult']['parameters']['any'];
}

$niu = '0';
$telefono = '';
$nombreCompleto = '';
$apellidoCompleto = '';
$nombreSalida = '😄';
$contextoNombre = '';
$contextoReporte = '';

$api->checkTextUser($reqBody);

foreach ($reqBody['queryResult']['outputContexts'] as $i => $con) {
    $name = explode('/', $con['name']);
    $largeContext = count($name);
    //$name[$largeContext - 1]

    if ($name[$largeContext - 1] == 'c1_reporte') {
        $contextoReporte = $con['name'];
        $niu = '' . $con['parameters']['number'] . '';

        if (isset($con['parameters']['telefono'])) {
            $telefono = $con['parameters']['telefono'];
        }
    }

    if ($name[$largeContext - 1] == 'nombre') {
        if (isset($con['parameters']['nombre'])) {
            if (is_array($con['parameters']['nombre'])) {
                $nombreSalida = $con['parameters']['nombre'][0];
            } else {
                $nombreSalida = $con['parameters']['nombre'];
            }
        }
        $contextoNombre = $con['name'];
    }
}

//estructura de reportes con loas opciones de daños
if (isset($reqBody['queryResult']['intent']['displayName'])) {
    $typeIntent = $reqBody['queryResult']['intent']['displayName'];
    if ($typeIntent == 'inicio_conversacion') {
        //$response = $api->pruebaMateo($reqBody);

        $response = $api->getInicioConversacion($reqBody);
        $responseContexts = false;
    }
}

if ($responseContexts) {
    //flag para identificar si se hace un request diferente a niu y no sobreescribir la respuesta
    $answered = false;

    $contextoReporte = '';
    //Switch que determina cuál es el contexto principal de la petición y ejecuta una función del objeto api correspondientemente.
    foreach ($reqBody['queryResult']['outputContexts'] as $i => $con) {
        $name = explode('/', $con['name']);
        $largeContext = count($name);
        $idInfoAdiReporte = '';
        //$name[$largeContext - 1]

        //Verifica si de la petición se recibe el municipio
        if (isset($reqBody['queryResult']['parameters']['municipio'])) {
            $raw_municipio = $reqBody['queryResult']['parameters']['municipio'];
        } elseif (isset($con['parameters']['municipio'])) {
            $raw_municipio = $con['parameters']['municipio'];
        } else {
            $raw_municipio = '';
        }

        $municipio = strtoupper($raw_municipio);

        if ($name[$largeContext - 1] == 'c1_reporte') {
            if (isset($con['parameters']['contextoNombre'])) {
                $contextoReporte = $con['name'];
                $contextoNombre = $con['parameters']['contextoNombre'];
            }
            if ($nombreSalida == '') {
                $nombreSalida = $con['parameters']['nombre'];
            }
        }

        switch ($name[$largeContext - 1]) {
            case 'c1_cc':
                $insertALog = $api->setLogBusqueda('c1', 'cedula');
                $response = $api->getIndisCC($number, $nombreSalida);
                $answered = true;
                break;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
