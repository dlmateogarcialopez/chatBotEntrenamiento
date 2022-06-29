<?php
set_time_limit(3000);

require './lib.php';
require_once "./PHPExcel-1.8.1/Classes/PHPExcel.php";
//require_once "./PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007";
require_once './IOFactory.php';

$response = leerExcel();

//$response = crearEstadisticas();

function crearEstadisticas()
{

    /* $nombreArchivo = 'C:\Users\Umanizales\Desktop\estadistica.xlsx';
    $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo); */

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->
        getProperties()
        ->setCreator("TEDnologia.com")
        ->setLastModifiedBy("TEDnologia.com")
        ->setTitle("Exportar Excel con PHP")
        ->setSubject("Documento de prueba")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("reportes");

    $api = new chatBotAPI();

    $respuesta = $api->guardarExcelPrueba();

    /* foreach ($respuesta as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i, $value->{'_id'})
    ->setCellValue('B'.$i, $value->{'total'});
    } */
    for ($i = 0; $i < count($respuesta); $i++) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $respuesta[$i]->{'_id'})
            ->setCellValue('B' . $i, $respuesta[$i]->{'total'});
    }

    //$objPHPExcel->getActiveSheet()->setTitle("Usuarios");
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

    header('Content-type: application/vnd.ms-excel');

    header('Content-Disposition: attachment; filename="C:\Users\Umanizales\Desktop\estadistica.xlsx"');

    $objWriter->save('php://output');

    exit();
}

function leerExcel()
{
//Variable con el nombre del archivo
    $nombreArchivo = 'C:\Users\Umanizales\Documents\Tablas Mongo\usuariosMongoActualizadoTelefonosSinDuplicados.xlsx';
// Cargo la hoja de cálculo
    $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
  //  NIU	NOMBRE	DIRECCION	TELEFONO	TELEFONO1	TELEFONO2	CELULAR1	CELULAR2	EMAIL1	EMAIL2	MUNICIPIO	UBICACION	TRAFO	CIRCUITO	DOCUMENTO	TIPO_DOC

//Asigno la hoja de calculo activa
    $objPHPExcel->setActiveSheetIndex(0);
//Obtengo el numero de filas del archivo
    $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

    $datos = array();
    for ($i = 2; $i <= $numRows; $i++) {
        $data['NIU'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
        $data['NOMBRE'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
        $data['DIRECCION'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
        $data['TELEFONO'] = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
        $data['TELEFONO1'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
        $data['TELEFONO2'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
        $data['CELULAR1'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
        $data['CELULAR2'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
        $data['EMAIL1'] = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
        $data['EMAIL2'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
        $data['MUNICIPIO'] = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
        $data['UBICACION'] = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
        $data['TRAFO'] = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
        $data['CIRCUITO'] = $objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue();
        $data['DOCUMENTO'] = $objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
        $data['TIPO_DOC'] = $objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue();

        array_push($datos, $data);
        //var_dump($id ." - " .$fecha." - ".$hora." - ".$estado." - ".$circuito."\n");
    }
    return aperturaCircuito($datos);
}

function aperturaCircuito($datos)
{
    $datosGuardar = array();
    
    foreach ($datos as $key => $value) {
        var_dump($datos['NIU']);
    }

    $api = new chatBotApi();
    return $api->guardarExcel($datos);
}

function cierreCircuito($datos, $data)
{
    for ($i = 0; $i < count($datos); $i++) {
        for ($i = 0; $i < count($datos[$i]); $i++) {
            if ($datos[$i]['fecha'] == $data['fecha']) {
                if ($datos[$i]['numero'] == "") {
                    if ($datos[$i]['estado'] == "CIERRE") {
                        if ($datos[$i]['circuito'] == $data['circuito']) {
                            if (($data['HORA'] - $datos[$i]['HORA']) < 15) {
                                $datos[$i]['numero'] = $data['numero'];
                            }
                        }
                    }
                }
            }
        }
    }
}
