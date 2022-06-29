<?php

//error_reporting(-1);
//ini_set('display_errors', 'On');
require 'lib.php';

$direccion = "";
$nombre = "";
$cedula = "";
$nit = "";
$telefono = "";
$response = "";
$api = new chatBotApi();

$reqBody = $api->detectRequestBody();
if (isset($reqBody['token'])) {
    if ($reqBody['token'] == "gdjSkskBBdbbA!jdndn") {
        if (isset($reqBody['criterio'])) {
            if ($reqBody['criterio'] == 'direccion') {
                if (isset($reqBody['dato_busqueda'], $reqBody['municipio_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "" && $reqBody['municipio_busqueda'] != "") {
                        $direccion = $reqBody['dato_busqueda'];
                        $municipio = $reqBody['municipio_busqueda'];
                        $response = $api->searchAddress($direccion, $municipio);
                    } else {
                        $response = "No se ha enviado el municipio o el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el municipio o el dato de búsqueda";
                }
            } elseif ($reqBody['criterio'] == 'nombre') {
                if (isset($reqBody['dato_busqueda'], $reqBody['municipio_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "" && $reqBody['municipio_busqueda'] != "") {
                        $nombre = $reqBody['dato_busqueda'];
                        $municipio = $reqBody['municipio_busqueda'];
                        $response = $api->searchName($nombre, $municipio);
                    } else {
                        $response = "No se ha enviado el municipio o el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el municipio o el dato de búsqueda";
                }
            } elseif ($reqBody['criterio'] == 'cedula') {
                if (isset($reqBody['dato_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "") {
                        $cedula = $reqBody['dato_busqueda'];
                        $response = $api->searchCedula($cedula);
                    } else {
                        $response = "No se ha enviado el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el dato de búsqueda";
                }
            } elseif ($reqBody['criterio'] == 'nit') {
                if (isset($reqBody['dato_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "") {
                        $nit = $reqBody['dato_busqueda'];
                        $response = $api->searchNit($nit);
                    } else {
                        $response = "No se ha enviado el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el dato de búsqueda";
                }
            } elseif ($reqBody['criterio'] == 'niu') {
                if (isset($reqBody['dato_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "") {
                        $niu = $reqBody['dato_busqueda'];
                        $response = $api->searchNiu($niu);
                    } else {
                        $response = "No se ha enviado el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el dato de búsqueda";
                }
            } elseif ($reqBody['criterio'] == 'telefono') {
                if (isset($reqBody['dato_busqueda'])) {
                    if ($reqBody['dato_busqueda'] != "") {
                        $telefono = $reqBody['dato_busqueda'];
                        $response = $api->searchPhone($telefono);
                    } else {
                        $response = "No se ha enviado el municipio o el dato de búsqueda";
                    }
                } else {
                    $response = "No se ha enviado el municipio o el dato de búsqueda";
                }
            } else {
                $response = "No hay definido un criterio de búsqueda";
            }
        } else {
            $response = "No hay definido un criterio de búsqueda";
        }
    } else {
        $response = "El token no corresponde con los parametros de seguridad";
    }
} else {
    $response = "No hay un token definido para la verificación";
}

header("Content-Type: application/json");
echo json_encode($response);
