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
// $data = '';
$data = $api->cargarReportes(date('Y-m-d H:i:s'));

// var_dump($data);
if (is_array($data)) {
    if (count($data) > 0) {
        loadReportsIndis();
        $datos = cargaArchivo($data, 'ARCHIVO_CONTINGENCIA.xls', $api);
    }
}

//$resultado = $api->setSuspensionEfectiva($datos);

function cargaArchivo($data, $file, $api)
{
    require_once './PHPExcel-1.8.1/Classes/PHPExcel.php';
    //require_once "./PHPExcel-1.8.1/Classes/PHPExcel/Writer/Excel2007/";
    require_once './IOFactory.php';

    //Variable con el nombre del archivo
    $nombreArchivo = './attachmentReports/'.$file;

    //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);

    //Asigno la hoja de calculo activa
    $objPHPExcel->setActiveSheetIndex(0);

    //creacion de arreglos para llenar en excel
    $reporte = $data['reporte'];
    $cliente = $data['cliente'];

    //recorrido de ambos arreglos conla informacion del reporte y del suario
    $i = 8;

    $_ids = array();

    $ArrayMunicipios = array(
        'AGUADAS' => '[1]', 'ANSERMA' => '[2]', 'APIA' => '[3]', 'ARANZAZU' => '[4]', 'ARAUCA' => '[19564]', 'ARBOLEDA' => '[27750]', 'ARMA' => '[27528]', 'ARMENIA' => '[28861]', 'BALBOA' => '[5]', 'BELALCAZAR' => '[6]', 'BELEN DE UMBRIA' => '[19565]', 'BOGOTA' => '[28858]', 'BOLIVIA' => '[26]', 'BUENAVISTA' => '[21527]', 'CALI' => '[28859]', 'CARTAGO' => '[28393]', 'CASTILLA' => '[32]', 'CHINCHINA' => '[7]', 'DORADA' => '[19561]', 'LA DORADA' => '[19561]', 'DOSQUEBRADAS' => '[8]', 'FILADELFIA' => '[19579]', 'FLORENCIA' => '[27753]', 'GUADUAS' => '[28273]', 'GUATICA' => '[9]', 'SAN CLEMENTE' => '[9]', 'HERVEO' => '[28288]', 'HONDA' => '[28392]', 'IBAGUE' => '[28426]', 'IRRA' => '[19589]', 'ISAZA' => '[19568]', 'LA CELIA' => '[12]', 'LA MERCED' => '[19567]', 'LA VIRGINIA' => '[13]', 'MANIZALES' => '[14]', 'MANZANARES' => '[15]', 'MARIQUITA' => '[28395]', 'MARMATO' => '[19569]', 'MARQUETALIA' => '[19570]', 'MARSELLA' => '[16]', 'MARULANDA' => '[17]', 'MONTEBONITO' => '[17]', 'MEDELLIN' => '[28860]', 'MISTRATO' => '[19571]', 'NEIRA' => '[18]', 'NORCASIA' => '[19]', 'PACORA' => '[20]', 'PALESTINA' => '[21]', 'PENSILVANIA' => '[19572]', 'SAN DANIEL' => '[19572]', 'PEREIRA' => '[34]', 'PUEBLO NUEVO' => '[31735]', 'PUERTO SALGAR' => '[28394]', 'QUINCHIA' => '[23]', 'RIOSUCIO' => '[24]', 'RISARALDA' => '[19573]', 'SALAMINA' => '[25]', 'SAMANA' => '[19574]', 'BERLIN' => '[19574]', 'SAMARIA' => '[19575]', 'SAN ANTONIO DEL CHAMI' => '[33]', 'SAN BARTOLOME' => '[27529]', 'SAN DIEGO' => '[26739]', 'SAN FELIX' => '[19576]', 'SAN JOSE' => '[19577]', 'SANTA CECILIA' => '[27]', 'SANTA ROSA' => '[28]', 'SANTUARIO' => '[29]', 'SUPIA' => '[19578]', 'VICTORIA' => '[19562]', 'VILLAMARIA' => '[30]', 'VITERBO' => '[31]', 'PUEBLO RICO' => '[22]', 'VILLA CLARET' => '[22]',
    );

    $confirmarEnvio = false;
    $contadorRegistros = 0;
    try {
        foreach ($reporte as $key => $rep) {
            if (isset($rep->NOMBREUSUARIO)) {
                foreach ($cliente as $k => $cli) {
                    foreach ($cli as $clave => $c) {
                        $flag = false;
                        $municipio = '';
                        // $_ids = $rep->_id;
                        // var_dump($rep->NIU);
                        // var_dump($c->NIU);
                        if ($rep->NIU == $c->NIU) {
                            if (isset($ArrayMunicipios[strtoupper($c->MUNICIPIO)])) {
                                // var_dump('Entro al envio 1');
                                $municipio = strtoupper($c->MUNICIPIO).' '.$ArrayMunicipios[strtoupper($c->MUNICIPIO)];
                                $flag = true;
                                $confirmarEnvio = true;
                            } else {
                                // var_dump('Entro al envio 2');
                                $municipio = strtoupper($c->MUNICIPIO);
                                $flag = true;
                                $confirmarEnvio = true;
                            }
                            if ($flag) {
                                // var_dump('Entro al envio');
                                $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $rep->FECHA_REPORTE);
                                $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $rep->NIU);
                                $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $rep->NOMBREUSUARIO);
                                /* $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $rep->APELLIDOUSUARIO); */
                                $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $rep->TELEFONO);
                                $telefono = 0;
                                if ($c->TELEFONO != '' && $c->CELULAR != '') {
                                    if ($c->TELEFONO != '-' && $c->CELULAR != '-') {
                                        $telefono = $c->CELULAR;
                                    }
                                } elseif ($c->TELEFONO != '') {
                                    if ($c->TELEFONO != '-') {
                                        $telefono = $c->TELEFONO;
                                    }
                                } elseif ($c->CELULAR != '') {
                                    if ($c->CELULAR != '-') {
                                        $telefono = $c->CELULAR;
                                    }
                                }
                                $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $telefono);
                                $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $c->DIRECCION);
                                $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'FALTA ENERGÍA EN EL SECTOR [38485]');
                                $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $c->DIRECCION);
                                $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $rep->RESOLVEQUERY);
                                //$objPHPExcel->getActiveSheet()->setCellValue('J' . $i, getObservacion($rep->INTENT));
                                $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $municipio);
                                $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Lucy');
                                array_push($_ids, $rep->_id);
                                ++$contadorRegistros;
                            } else {
                                // var_dump('else 1');
                                $confirmarEnvio = false;
                                unlink($nombreArchivo);
                                $contadorRegistros = 0;
                            }
                        }
                    }
                }
                $i = $i + 1;
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($nombreArchivo);
        //envio de archivo de contingencia para subir a sgo

        $envio = 'Error';
        if ($contadorRegistros > 0) {
            $envio = enviarArchivoContingencia($nombreArchivo, $_ids, $api);
        }
        // var_dump('ultima eliminacion');
        unlink($nombreArchivo);

        return $envio;
    } catch (\Exception $th) {
        // var_dump('try catch');
        include './sendEmail.php';
        $apiMail = new sendEmailAPI();

        $send = $apiMail->errorReporte($th);
        unlink($nombreArchivo);
    }
}

//function par realizar llamado al archivo de envio de mensajes
function enviarArchivoContingencia($nombreArchivo, $_ids, $api)
{
    include './sendEmail.php';
    $apiMail = new sendEmailAPI();

    $send = $apiMail->sendFileReports($nombreArchivo);

    if ($send == 'Ok') {
        foreach ($_ids as $_id) {
            // code...
            $updateReportEnviado = $api->updateReporteEnviado($_id);
        }

        return $updateReportEnviado;
    }
}
