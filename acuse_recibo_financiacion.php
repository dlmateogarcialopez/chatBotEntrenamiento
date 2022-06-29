<?php

error_reporting(-1);
ini_set('display_errors', 'On');
// include './lib.php';
//Instancia de la API
// $api = new chatBotAPI();
include './lib.php';
// //Instancia de la API
$api = new chatBotAPI();
//idenvio=umanizales12345&niu=625376487&apertura=5d02653cf119802524ab749f&tel=%P&estado=%d&fecha=%t';

// var_dump($_GET['niu'], $_GET['apertura'], $_GET['tel'], $_GET['estado'], $_GET['fecha'], $_GET['tipo']);
if (isset($_GET['idenvio'], $_GET['estado'], $_GET['fecha'], $_GET['telefono'], $_GET['coduser'], $_GET['niu'], $_GET['documento'])) {
    if ($_GET['idenvio'] == 'umanizales12345') {        
        $api->acuseReciboFinanciacion($_GET['idenvio'], $_GET['estado'], $_GET['fecha'], $_GET['telefono'], $_GET['coduser'], $_GET['niu'], $_GET['documento']);
        return 'GUARDADO';
    }
}
