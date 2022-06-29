<?php
error_reporting(-1);
ini_set('display_errors', 'On');
set_time_limit(3000);
ini_set('memory_limit', '-1');
require './lib.php';
require 'download_attachments.php';

$api = new chatBotApi();

//traer la informacion actualizada
//$data = $api->cargarDifusion();
$data = $api->cargarUsuarios();

//var_dump($data);
$datos = cargaArchivo($data, "ARCHIVOPRUEBA3.xlsx");

//$resultado = $api->setSuspensionEfectiva($datos);

function cargaArchivo($data, $file)
{

    require_once "./PHPExcel-1.8.1/Classes/PHPExcel.php";
    //require_once "./PHPExcel-1.8.1/Classes/PHPExcel/Writer/Excel2007/";
    require_once './IOFactory.php';

    //Variable con el nombre del archivo
    $nombreArchivo = './attachmentReports/' . $file;

    //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);

    //Asigno la hoja de calculo activa
    $objPHPExcel->setActiveSheetIndex(0);

    //recorrido de ambos arreglos conla informacion del reporte y del suario

    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'MUNICIPIO');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CANTIDAD_CUENTAS');

    $i = 2;

    foreach ($data as $key => $value) {

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value->_id);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value->cantidad);
        $i++;

    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($nombreArchivo);
    var_dump($i);
    //envio de archivo de contingencia para subir a sgo
    //enviarArchivoContingencia($nombreArchivo);
}

//function par realizar llamado al archivo de envio de mensajes
function enviarArchivoContingencia($nombreArchivo)
{
    include './sendEmail.php';
    $apiMail = new sendEmailAPI();

    $send = $apiMail->sendFileReports($nombreArchivo);
}
