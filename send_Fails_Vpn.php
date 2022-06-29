<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include './lib.php';

$api = new chatBotApi();

$cuentas = array(276960238,277360405,277425591,275862186,595641228,268191497,
136930504,137347119,474490000,170254785,599708594,519201834,389706305,496118185,
505249313,642845227,124602405,250408217,500722446,369379317,655948849,348360962,
223207232,567887507,645593504,506090070,883789099,122179554,582570897,357412616,
660904407,464695692,626747056,553873147,192945434,325460478,746360700,343024638,
499993425,314257748,169574168,187851146,494426073,497174340,358488570,497544781,
507338232,494407002,237218351,707375255);

$numero = $cuentas[mt_rand(0, 49)];
$tiempo_inicio = date('Y-m-d H:i:s');

$response = $api->getSGO($numero, true, '', '');
// $response = $api->sendAlertSGOerror('PRUEBA DE CORREO');

echo json_encode($response);
// $tiempo_fin = date('Y-m-d H:i:s');

// $dteStart = new DateTime($tiempo_inicio); 
// $dteEnd   = new DateTime($tiempo_fin); 

// $diff = date_diff($dteStart, $dteEnd);

// $response = $api->insertLogTimeRequest($diff->format('%I:%S'), $numero);

// header("Content-Type: application/json");
// echo json_encode($response);

// phpinfo();
