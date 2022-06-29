<?php

set_time_limit(3000);
//ini_set('memory_limit', '1024M');
error_reporting(-1);
ini_set('display_errors', 'On');
require './lib.php';
require 'download_attachments.php';

$api = new chatBotApi();
//Para copiar el archivo de contingencia
//traer la informacion actualizada
$data = $api->chargeText();
// var_dump($data);
if (is_array($data)) {
    if (count($data) > 0) {        
        loadReportsTexts();
        $datos = cargaArchivo($data, 'ARCHIVO_TEXTOS.xls', $api);
    }
}

//$resultado = $api->setSuspensionEfectiva($datos);

function cargaArchivo($data, $file, $api)
{
    require_once './PHPExcel-1.8.1/Classes/PHPExcel.php';
    //require_once "./PHPExcel-1.8.1/Classes/PHPExcel/Writer/Excel2007/";
    require_once './IOFactory.php';

    //Variable con el nombre del archivo
    $nombreArchivo = './attachmentReports/' . $file;

    //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);

    //Asigno la hoja de calculo activa
    $objPHPExcel->setActiveSheetIndex(0);

    //recorrido de ambos arreglos conla informacion del reporte y del suario
    $i = 6;

    $_ids = array();

    $contadorRegistros = 0;
    try {
        foreach ($data as $key => $rep) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $rep->TEXTO);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $rep->CONTACTO);
            if (isset($rep->NOMBRE)) {
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $rep->NOMBRE);
            }
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $rep->FECHA);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $rep->SESSIONID);
            array_push($_ids, $rep->_id);
            ++$contadorRegistros;

            $i = $i + 1;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($nombreArchivo);
        //envio de archivo de contingencia para subir a sgo

        $envio = 'Error';
        if ($contadorRegistros > 0) { 
            $envio = enviarArchivoContingencia($nombreArchivo, $_ids, $api);
        }
        unlink($nombreArchivo);

        return $envio;
    } catch (\Exception $th) {
        include './sendEmail.php';
        $apiMail = new sendEmailAPI();

        $send = $apiMail->errorReporteTexto($th);
        unlink($nombreArchivo);
    }
}

//function par realizar llamado al archivo de envio de mensajes
function enviarArchivoContingencia($nombreArchivo, $_ids, $api)
{
    include './sendEmail.php';
    $apiMail = new sendEmailAPI();    

    $send = $apiMail->sendFileReportsText($nombreArchivo);              
    var_dump('send', $send);

    if ($send == 'Ok') {
        foreach ($_ids as $_id) {
            // code...
            $updateReportEnviado = $api->updateTextUser($_id);
        }

        return $updateReportEnviado;
    }
}
