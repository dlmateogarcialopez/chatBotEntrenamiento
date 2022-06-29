<?php
error_reporting(-1);
ini_set('display_errors', 'On');

//permisos cors
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

//se importa el lib, que contiene funciones para trabajar
require './lib.php';

//obtener data que viene del front
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

//Instancia de la API
$api = new chatBotApi();
$json = array();

if (isset($input['coordenadas']) && isset($input['distancia'])) {

    //obtener puntos de atención mas cercanos a la ubicación actual
    $coord = $input['coordenadas'];
    $distancia = $input['distancia'];
    $json = $api->getNodos($coord, $distancia);

} else if (isset($input['coordenadas']) && isset($input['codUser']) && isset($input['distance'])) {

    //obtener puntos de atención mas cercanos a la ubicación actual
    $coord = $input['coordenadas'];
    $codUser = $input['codUser'];
    $distane = $input['distance'];
    $json = $api->saveCorrdinates($coord, $codUser, $distane);

} else{
    $json['error'] = false;
    $json['message'] = 'Parametros incorrectos';
}

echo json_encode($json);
