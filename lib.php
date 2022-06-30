<?php
//session_start();
error_reporting(-1);
ini_set('display_errors', 'On');
require './consultas.php';

require 'Mesmotronic/Soap/WsaSoap.php';
require 'Mesmotronic/Soap/WsaSoapClient.php';
require 'Mesmotronic/Soap/WsseAuthHeader.php';

//require "Facebook/autoload.php";
// require './SGO/load_reportes_SGO.php';
require_once __DIR__ . '/vendor/autoload.php';


class chatBotAPI
{
    //conexion db desarrollo chec
    private $hostHerokuChec = "mongodb://localhost:27017/admin";
    //private $hostHerokuChec = "mongodb://admin:root@localhost:27017/admin";

    //conexion a BD
    private $conHerokuChecDev;

    public function __construct()
    {
        $this->connectDbHerokuChec();
    }

    //Obtener el cuerpo de la peticion POST del chatbot
    public function detectRequestBody()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        return $input;
    }

    public function connectDbHerokuChec()
    {
        try {
            $this->conHerokuChecDev = new MongoDB\Driver\Manager($this->hostHerokuChec);
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $filename = basename(__FILE__);
            echo "The $filename script has experienced an error.\n";
            echo "It failed with the following exception:\n";
            echo 'Exception:', $e->getMessage(), "\n";
            echo 'In file:', $e->getFile(), "\n";
            echo 'On line:', $e->getLine(), "\n";
        }
    }

    public function chargeText()
    {
        $data = saveTextUser($this->conHerokuChecDev, 'prueba');

        return $data;
    }
}
