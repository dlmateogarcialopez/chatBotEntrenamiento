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
$api->getReqBody($reqBody);


$response = $api->searchAddressVentanaCX($reqBody['queryResult']['parameters']['dir_name']);

header('Content-Type: application/json');
echo json_encode($response);

//Obtener los contextos de la petición
/* foreach ($reqBody['result']['contexts'] as $valor) {
array_push($contexts, $valor);
} */

//mandar la peticion(reqBody) a un archivo.txt para capturar todo lo que viene de facebook
// $req = json_encode($reqBody);
// $file = fopen('reqBodyFacebook.txt', 'w');
// fwrite($file, $req);
// fclose($file); // Cerrar
// //$api->saveAllQerBody($reqBody['session'],  $req);

// //Verifica si de la petición se recibe la entidad number
// if (isset($reqBody['queryResult']['parameters']['number'])) {
//     $number = strval($reqBody['queryResult']['parameters']['number']);
// } elseif (isset($reqBody['queryResult']['parameters']['phone-number'])) {
//     $number = strval($reqBody['queryResult']['parameters']['phone-number']);
// }

// //Verifica si de la petición se recibe la entidad nombre
// if (isset($reqBody['queryResult']['parameters']['any'])) {
//     $Nombre = $reqBody['queryResult']['parameters']['any'];
// }

// $niu = '0';
// $telefono = '';
// $nombreCompleto = '';
// $apellidoCompleto = '';
// $nombreSalida = '😄';
// $contextoNombre = '';
// $contextoReporte = '';

// $api->checkTextUser($reqBody);

// foreach ($reqBody['queryResult']['outputContexts'] as $i => $con) {
//     $name = explode('/', $con['name']);
//     $largeContext = count($name);
//     //$name[$largeContext - 1]

//     if ($name[$largeContext - 1] == 'c1_reporte') {
//         $contextoReporte = $con['name'];
//         $niu = '' . $con['parameters']['number'] . '';

//         if (isset($con['parameters']['telefono'])) {
//             $telefono = $con['parameters']['telefono'];
//         }

//         if (isset($con['parameters']['cedula'])) {
//             $cedula = $con['parameters']['cedula'];
//         }

//         if (isset($con['parameters']['autorizacion'])) {
//             $autorizacion = $con['parameters']['autorizacion'];
//         }
//         if (isset($con['parameters']['nombreFallback'])) {
//             if (strlen($con['parameters']['nombreFallback']) > 0) {
//                 $nombreCompleto .= ' ' . $con['parameters']['nombreFallback'];
//             }
//         }
//         if (isset($con['parameters']['nombreInicial.original'])) {
//             if (strlen($con['parameters']['nombreInicial.original']) > 0) {
//                 //$nombreArray = $con['parameters']['nombreInicial'];

//                 $nombreCompleto .= $con['parameters']['nombreInicial.original'];
//             }
//         }
//         if (isset($con['parameters']['nombreInicial1.original'])) {
//             if (strlen($con['parameters']['nombreInicial1.original']) > 0) {
//                 $nombreCompleto .= ' ' . $con['parameters']['nombreInicial1.original'];
//             }
//         }
//         if (isset($con['parameters']['nombreInicial2.original'])) {
//             if (strlen($con['parameters']['nombreInicial2.original']) > 0) {
//                 $nombreCompleto .= ' ' . $con['parameters']['nombreInicial2.original'];
//             }
//         }
//         if (isset($con['parameters']['nombreInicial3.original'])) {
//             if (strlen($con['parameters']['nombreInicial3.original']) > 0) {
//                 $nombreCompleto .= ' ' . $con['parameters']['nombreInicial3.original'];
//             }
//         }
//         if (isset($con['parameters']['nombreInicial4.original'])) {
//             if (strlen($con['parameters']['nombreInicial4.original']) > 0) {
//                 $nombreCompleto .= ' ' . $con['parameters']['nombreInicial4.original'];
//             }
//         }
//         if (isset($con['parameters']['any'])) {
//             if (count($con['parameters']['any']) > 0) {
//                 $nombreCompleto .= ' ' . implode(' ', $con['parameters']['any']);
//                 $response = $api->guardarNombreExtrano(implode(' ', $con['parameters']['any']), '', false);
//             }
//         }

//         //break;
//     }

//     if ($name[$largeContext - 1] == 'nombre') {
//         if (isset($con['parameters']['nombre'])) {
//             if (is_array($con['parameters']['nombre'])) {
//                 $nombreSalida = $con['parameters']['nombre'][0];
//             } else {
//                 $nombreSalida = $con['parameters']['nombre'];
//             }
//         }
//         $contextoNombre = $con['name'];
//     }
// }

// //estructura de reportes con loas opciones de daños
// if (isset($reqBody['queryResult']['intent']['displayName'])) {
//     $typeIntent = $reqBody['queryResult']['intent']['displayName'];
//     if ($typeIntent == 'inicio_conversacion') {
//         //$response = $api->pruebaMateo($reqBody);

//         $response = $api->getInicioConversacion($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'r1_reporte_danos') {
//         $response = $api->RealizoReporte($niu, $nombreSalida, $contextoReporte, $contextoNombre);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'r1_reporte_danos - fallbackAny - fallback') {
//         //$api->insertIdConversacion($reqBody);
//         $response = $api->guardarNombreExtrano($reqBody['queryResult']['queryText'], $contextoReporte, true);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_Falta_energia_c1') {
//         $response = $api->logMenuSource($reqBody, 'Falta de Energia');
//         $api->logChatWebMenuInicial($reqBody, 'Falta de Energia');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_Pago_linea_c4') {
//         $response = $api->logMenuSource($reqBody, 'Pago en Linea');
//         $api->logChatWebMenuInicial($reqBody, 'Pago en Linea');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_Puntos_atencion_c7') {
//         $response = $api->logMenuSource($reqBody, 'Puntos de Atencion');
//         $api->logChatWebMenuInicial($reqBody, 'Puntos de Atencion');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_Vacantes_c8') {
//         $response = $api->logMenuSource($reqBody, 'Vacantes');
//         $api->logChatWebMenuInicial($reqBody, 'Vacantes');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_PQR_c9') {
//         $response = $api->logMenuSource($reqBody, 'Pqr');
//         $api->logChatWebMenuInicial($reqBody, 'Pqr');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'subMneu_copiaFactura_c10') {
//         $response = $api->logMenuSource($reqBody, 'Copia factura');
//         $api->logChatWebMenuInicial($reqBody, 'Copia factura');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_fraudes_c11') {
//         $response = $api->logMenuSource($reqBody, 'Fraudes');
//         $api->logChatWebMenuInicial($reqBody, 'Fraudes');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'otros_motivos_consulta') {
//         $response = $api->logMenuSource($reqBody, 'Otros motivos');
//         $api->logChatWebMenuInicial($reqBody, 'Otros motivos');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'General Fallback 2') {
//         $response = $api->returnEventoInicio($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'prueba_nombre - custom') {
//         $response = $api->pruebaNombre($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_copiaFactura_c10_Buscar') {
//         //$response = $api->CopiaFactura($reqBody);
//         $response = $api->getIndisNiu($number, false, $nombreSalida);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'c1_re_niuCfactura') {
//         if ($api->validateContext($reqBody)) {
//             $response = $api->CopiaFactura($reqBody, $nombreSalida);
//         } else {
//             $response = $api->CopiaFacturaND($reqBody, $nombreSalida);
//         }
//         $api->logMenu($reqBody, 'fCopia factura');
//         $api->logChatWebMenuInicial($reqBody, 'fCopia factura');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'c12_re_niuCPagoActual') {
//         $response = $api->cuponDePago($reqBody, $nombreSalida, 1);
//         $api->logMenu($reqBody, 'fCupon actual'); //Fcupon actual, significa flujo, se diferencia del menu de seleccionar cupon
//         $api->logChatWebMenuInicial($reqBody, 'fCupon actual');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'c12_re_niuCPagoAntiguo') {
//         //queda faltando revisar el tema de cancelar autorizacion en cupon anterior, organizar metodos y deshabilitar en dialogflow
//         $response = $api->cuponDePago($reqBody, $nombreSalida, 2);
//         $api->logMenu($reqBody, 'fCupon anterior');
//         $api->logChatWebMenuInicial($reqBody, 'fCupon anterior');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'inicio_conversacion_nombre') {
//         $response = $api->logChatWebSaludoInicial();
//         $responseContexts = false;
//     } elseif ($typeIntent == 'General Fallback Menu1') {
//         $response = $api->activarFallbackAyuda($reqBody, 'General Fallback Menu1', $nombreSalida);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_chat_asistido') {
//         $response = $api->logMenuSource($reqBody, 'Chat asistido');
//         $api->logChatWebMenuInicial($reqBody, 'Chat asistido');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_asesor_remoto') {
//         //$response = $api->setLogMenu('Asesor remoto', '', $source);
//         $response = $api->logMenuSource($reqBody, 'Asesor remoto');
//         $api->logChatWebMenuInicial($reqBody, 'Asesor remoto');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'inicio_conversacion_FT') {
//         $response = $api->logChatWebMenuInicial($reqBody, 'inicio_conversacion_FT');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Pago_factura') {
//         $response = $api->logMenuSource($reqBody, 'Pago factura');
//         $api->logChatWebMenuInicial($reqBody, 'Pago factura');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_cupoPago_c12') {
//         $response = $api->logMenuSource($reqBody, 'Cupon pago');
//         $api->logChatWebMenuInicial($reqBody, 'Cupon pago');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'calificacion_servicio_pregunta') {
//         $response = $api->logMenuSource($reqBody, 'Finalizar_consulta');
//         $api->logChatWebMenuInicial($reqBody, 'Finalizar_consulta');
//         $responseContexts = false;
//     } else if ($typeIntent == 'c10_re_nombre_municipio') {
//         $nombreSalidaCopiaFactura = '';
//         $copia_municipio = '';
//         $responseCopia = false;
//         foreach ($reqBody['queryResult']['outputContexts'] as $i => $con) {
//             if (!$responseCopia) {
//                 $name = explode('/', $con['name']);
//                 $largeContext = count($name);
//                 $idInfoAdiReporte = '';

//                 //Verifica si de la petición se recibe el municipio
//                 if ($name[$largeContext - 1] == 'c10_municipio') {
//                     if (isset($reqBody['queryResult']['parameters']['municipio'])) {
//                         $copia_municipio = $reqBody['queryResult']['parameters']['municipio'];
//                     } elseif (isset($con['parameters']['municipio'])) {
//                         $copia_municipio = $con['parameters']['municipio'];
//                     } else {
//                         $copia_municipio = '';
//                     }
//                     $municipio = strtoupper($copia_municipio);
//                 }

//                 //Verifica si de la petición se recibe el nombre usurio
//                 if ($name[$largeContext - 1] == 'c10_nombreusuario') {
//                     if ($nombreSalidaCopiaFactura == '') {
//                         $nombreSalidaCopiaFactura = $con['parameters']['nombreinicialcopiafactura'];
//                     }
//                 }

//                 if ($nombreSalidaCopiaFactura != '' && $copia_municipio != '') {
//                     //$insertALog = $api->setLogBusqueda('c10', 'nombre');
//                     $response = $api->getIndisNombreCopiaFactura($nombreSalidaCopiaFactura, $municipio, false, $nombreSalidaCopiaFactura);
//                     $responseCopia = true;
//                 }
//             }
//         }
//         $responseContexts = false;
//     } else if ($typeIntent == 'c10_re_direccion_municipio') {
//         $nombreSalidaCopiaFactura = '';
//         $copia_municipio = '';
//         $responseCopia = false;
//         foreach ($reqBody['queryResult']['outputContexts'] as $i => $con) {
//             if (!$responseCopia) {
//                 $name = explode('/', $con['name']);
//                 $largeContext = count($name);
//                 $idInfoAdiReporte = '';

//                 //Verifica si de la petición se recibe el municipio
//                 //if ($name[$largeContext - 1] == 'c10_municipio_dir') {
//                 if ($name[$largeContext - 1] == 'c10_municipio') {
//                     if (isset($reqBody['queryResult']['parameters']['municipio'])) {
//                         $copia_municipio = $reqBody['queryResult']['parameters']['municipio'];
//                     } elseif (isset($con['parameters']['municipio'])) {
//                         $copia_municipio = $con['parameters']['municipio'];
//                     } else {
//                         $copia_municipio = '';
//                     }
//                     $municipio = strtoupper($copia_municipio);
//                 }

//                 //Verifica si de la petición se recibe el nombre usurio
//                 if ($name[$largeContext - 1] == 'c10_direccionusuario') {
//                     if ($nombreSalidaCopiaFactura == '') {
//                         $nombreSalidaCopiaFactura = $con['parameters']['direccioncopiafactura'];
//                     }
//                 }

//                 if ($nombreSalidaCopiaFactura != '' && $copia_municipio != '') {
//                     //$insertALog = $api->setLogBusqueda('c10', 'direccion');
//                     $response = $api->getIndisAddressCopiaFactura($nombreSalidaCopiaFactura, $municipio, false, $nombreSalidaCopiaFactura);
//                     $responseCopia = true;
//                 }
//             }
//         }
//         $responseContexts = false;
//     } elseif ($typeIntent == 'c10_nombre') {
//         $response = $api->logMenuSource($reqBody, 'copia_factura_nombre');
//         $api->logChatWebMenuInicial($reqBody, 'copia_factura_nombre');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'c10_direccion') {
//         $response = $api->logMenuSource($reqBody, 'copia_factura_direccion');
//         $api->logChatWebMenuInicial($reqBody, 'copia_factura_direccion');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_copiaFactura_c10') {
//         $response = $api->logMenuSource($reqBody, 'copia_factura_niu');
//         $api->logChatWebMenuInicial($reqBody, 'copia_factura_niu');
//         $responseContexts = false;
//     } else if ($typeIntent == 'c1_ubicacion') {
//         $insertALog = $api->setLogBusqueda('c1', 'ubicacion');
//         $response = $api->asegurarUbicacion($reqBody);
//         $responseContexts = false;
//     } else if ($typeIntent == 'c1_ubicacion_indisp') {
//         $response = $api->indisponibilidades($reqBody);
//         $responseContexts = false;
//     } else if ($typeIntent == 'c1_ubicacion_direccion_municipio') {
//         $response = $api->requestDirection($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'c1_ubicacion_reporte_danos') {
//         $response = $api->asociarNiuCoordenadas($reqBody, $niu, $contextoReporte);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'solicitar_actualizacion') {
//         $response = $api->mostrarDatosActualizacion($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'solicitar_actualizacion_cupon') {
//         $response = $api->mostrarDatosActualizacion($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'solicitar_dato_actualizacion') {
//         $response = $api->solicitarDatoActualizar($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'actualizar_dato') {
//         $response = $api->actualizarDato($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'actualizacion_ciclo') {
//         $response = $api->actualizacionCiclo($reqBody); //nuevo esquema
//         $responseContexts = false;
//     } elseif ($typeIntent == 'autorizacion') {
//         $response = $api->guardarAutorizacionCopiaFactura($reqBody);
//         $responseContexts = false;
//     }/* elseif ($typeIntent == 'documento_financiacion_c13') {
//         $response = $api->getDocument($cedula, $cedula);
//         $responseContexts = false;
//     } */ elseif ($typeIntent == 'financiacion_c13') {
//         //$api->chatsAverage();
//         $response = $api->financiacionCreditos($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_cuota_financiacion_c13') {
//         $response = $api->saveCuota($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_medio_financiacion_c13') {
//         $response = $api->saveMedium($reqBody);
//         $responseContexts = false;
//     } /*elseif ($typeIntent == 'seleccionar_deudor_financiacion_c13') {
//         $response = $api->saveDeudor($reqBody);
//         $responseContexts = false;
//     } */ elseif ($typeIntent == 'seleccionar_Tpersona_financiacion_natural_c13') {
//         $response = $api->saveTipoPersona($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_Tpersona_financiacion_juridica_c13') {
//         $response = $api->saveTipoPersona($reqBody);
//         $responseContexts = false;
//     }/*elseif ($typeIntent == 'seleccionar_fechaEx_financiacion_c13') {
//         $response = $api->saveDeliveryDate($reqBody);
//         $responseContexts = false;
//     } */ elseif ($typeIntent == 'seleccionar_identificacionD_financiacion_c13') {
//         $response = $api->saveDeDocumentCustomer($reqBody, true);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_nitD_financiacion_c13') {
//         $response = $api->saveDeDocumentCustomer($reqBody, false);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_nombre_financiacion_c13') {
//         $response = $api->saveDeotrsName($reqBody, true);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_razon_social_financiacion_c13') {
//         $response = $api->saveDeotrsName($reqBody, false);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_apellido_financiacion_c13') {
//         $response = $api->saveDeotrsLastName($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_correo_financiacion_c13') {
//         $response = $api->saveDeotrsEmail($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_celular_financiacion_c13') {
//         $response = $api->saveDeotrsPhone($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Menu_financiacion_c13') {
//         $response = $api->validateCodUser($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'codigo_verificacion_financiacion_c13') {
//         $response = $api->verifyCode($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'confirm_celular_financiacion_c13') {
//         $response = $api->confirmPhone($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Puntos_recaudo') {
//         $response = $api->logMenuSource($reqBody, 'Puntos recaudo');
//         $api->logChatWebMenuInicial($reqBody, 'Puntos recaudo');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Autogenerador') {
//         $response = $api->logMenuSource($reqBody, 'Autogeneradores');
//         $api->logChatWebMenuInicial($reqBody, 'Autogeneradores');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Tarifas') {
//         $response = $api->logMenuSource($reqBody, 'Tarifas');
//         $api->logChatWebMenuInicial($reqBody, 'Tarifas');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Manual_usuario') {
//         $response = $api->logMenuSource($reqBody, 'Manual usuario');
//         $api->logChatWebMenuInicial($reqBody, 'Manual usuario');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Carta_trato_digno') {
//         $response = $api->logMenuSource($reqBody, 'Carta trato digno');
//         $api->logChatWebMenuInicial($reqBody, 'Carta trato digno');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Canales_atencion') {
//         $response = $api->logMenuSource($reqBody, 'Canales atencion');
//         $api->logChatWebMenuInicial($reqBody, 'Canales atencion');
//         $responseContexts = false;
//     } /*elseif ($typeIntent == 'tramites') {
//         $response = $api->logMenuSource($reqBody, 'tramites');
//         $api->logChatWebMenuInicial($reqBody, 'tramites');
//         $responseContexts = false;
//     }*/ elseif ($typeIntent == 'matriz_requisitos') {
//         $response = $api->logMenuSource($reqBody, 'matriz requisitos');
//         $api->logChatWebMenuInicial($reqBody, 'matriz requisitos');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'CCU') {
//         $response = $api->logMenuSource($reqBody, 'CCU');
//         $api->logChatWebMenuInicial($reqBody, 'CCU');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'autorizacionCupon') {
//         $response = $api->guardarAutorizacionCupon($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'autorizacionCuponAterior') {
//         $response = $api->guardarAutorizacionCupon($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'autorizacionFaltaEnergia') {
//         $response = $api->guardarAutorizacionFaltaEnergia($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'menu_actualizacion_datos') {
//         $response = $api->logMenuSource($reqBody, 'Actualizacion Datos');
//         $api->logChatWebMenuInicial($reqBody, 'Actualizacion Datos');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Factura') {
//         $response = $api->logMenuSource($reqBody, 'Factura');
//         $api->logChatWebMenuInicial($reqBody, 'Factura');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'menuTramites') {
//         $response = $api->logMenuSource($reqBody, 'Tramites');
//         $api->logChatWebMenuInicial($reqBody, 'Tramites');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'menuAutogeneracion') {
//         $response = $api->logMenuSource($reqBody, 'Autogeneracion');
//         $api->logChatWebMenuInicial($reqBody, 'Autogeneracion');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'InformacionGeneralMenu') {
//         $response = $api->logMenuSource($reqBody, 'Informacion General');
//         $api->logChatWebMenuInicial($reqBody, 'Informacion General');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'seleccionar_lugarEx_financiacion_c13') {
//         $response = $api->saveDeliveryExpeditionPlace($reqBody);
//         $responseContexts = false;
//     } /*elseif ($typeIntent == 'mostrar_saldo_financiacion_c13') {
//         $response = $api->financiacionCreditosCuotas($reqBody);
//         $responseContexts = false;
//     } */ elseif ($typeIntent == 'menu_explicar_factura') { //------------------------------------------------------------------------INFOFACTURA--------------------------------------------
//         $response = $api->logMenuSource($reqBody, 'menu_explicar_factura');
//         $api->logChatWebMenuInicial($reqBody, 'menu_explicar_factura');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_niu') {
//         $response = $api->logMenuSource($reqBody, 'explicar_factura_niu');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_niu');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_fecha') {
//         $api->logMenuSource($reqBody, 'explicar_factura_fecha');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_fecha');
//         $response = $api->getFecha($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura') {
//         $api->logMenuSource($reqBody, 'explicar_factura');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura');
//         $response = $api->explainEnergyBill($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_submenu') {
//         $response = $api->logMenuSource($reqBody, 'explicar_factura_submenu');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_submenu');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_2') {
//         $api->logMenuSource($reqBody, 'explicar_factura_2');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_2');
//         $response = $api->explainEnergyBillIncrement($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_2_no_detalle') {
//         $api->logMenuSource($reqBody, 'explicar_factura_2_no_detalle');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_2_no_detalle');
//         //$response = $api->askUsefulInformation($reqBody);
//         $response = $api->explainEnergyBillIncrement($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_2_informacion_utilidad') {
//         $api->logMenuSource($reqBody, 'explicar_factura_2_informacion_utilidad');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_2_informacion_utilidad');
//         $response = $api->askUsefulInformation($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_3') {
//         $api->logMenuSource($reqBody, 'explicar_factura_3');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_3');
//         $response = $api->explainEnergyConcepts($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_3_no_detalle') {
//         $api->logMenuSource($reqBody, 'explicar_factura_3_no_detalle');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_3_no_detalle');
//         $response = $api->explainEnergyConcepts($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_3_informacion_utilidad') {
//         $api->logMenuSource($reqBody, 'explicar_factura_3_informacion_utilidad');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_3_informacion_utilidad');
//         $response = $api->askUsefulInformation2($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_factura_4_informacion_utilidad') {
//         $api->logMenuSource($reqBody, 'explicar_factura_4_informacion_utilidad');
//         $api->logChatWebMenuInicial($reqBody, 'explicar_factura_4_informacion_utilidad');
//         $response = $api->askUsefulInformation3($reqBody);
//         $responseContexts = false;
//     } elseif ($typeIntent == 'cuponPagoServicio') {
//         $response = $api->cuponDePagoServicio($reqBody, $nombreSalida, 1);
//         $api->logMenu($reqBody, 'fCupon actual'); //Fcupon actual, significa flujo, se diferencia del menu de seleccionar cupon
//         $api->logChatWebMenuInicial($reqBody, 'fCupon actual');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'explicar_actualizacion_datos') {
//         $response = $api->logMenuSource($reqBody, 'menu_actualizacion_datos');
//         $api->logChatWebMenuInicial($reqBody, 'menu_actualizacion_datos');
//         $responseContexts = false;
//     } elseif ($typeIntent == 'Financiacion_factura') {
//         $response = $api->logMenuSource($reqBody, 'Financiacion_factura');
//         $api->logChatWebMenuInicial($reqBody, 'Financiacion_factura');
//         $responseContexts = false;
//     }
// }

// if ($responseContexts) {
//     //flag para identificar si se hace un request diferente a niu y no sobreescribir la respuesta
//     $answered = false;

//     $contextoReporte = '';
//     //Switch que determina cuál es el contexto principal de la petición y ejecuta una función del objeto api correspondientemente.
//     foreach ($reqBody['queryResult']['outputContexts'] as $i => $con) {
//         $name = explode('/', $con['name']);
//         $largeContext = count($name);
//         $idInfoAdiReporte = '';
//         //$name[$largeContext - 1]

//         //Verifica si de la petición se recibe el municipio
//         if (isset($reqBody['queryResult']['parameters']['municipio'])) {
//             $raw_municipio = $reqBody['queryResult']['parameters']['municipio'];
//         } elseif (isset($con['parameters']['municipio'])) {
//             $raw_municipio = $con['parameters']['municipio'];
//         } else {
//             $raw_municipio = '';
//         }

//         $municipio = strtoupper($raw_municipio);

//         if ($name[$largeContext - 1] == 'c1_reporte') {
//             if (isset($con['parameters']['contextoNombre'])) {
//                 $contextoReporte = $con['name'];
//                 $contextoNombre = $con['parameters']['contextoNombre'];
//             }
//             if ($nombreSalida == '') {
//                 $nombreSalida = $con['parameters']['nombre'];
//             }
//         }

//         switch ($name[$largeContext - 1]) {
//             case 'c1_cc':
//                 $insertALog = $api->setLogBusqueda('c1', 'cedula');
//                 $response = $api->getIndisCC($number, $nombreSalida);
//                 $answered = true;
//                 break;
//             case 'c1_direccion_municipio':
//                 $insertALog = $api->setLogBusqueda('c1', 'direccion');
//                 $direccion = $reqBody['queryResult']['queryText'];
//                 $response = $api->getIndisAddress($direccion, $municipio, $nombreSalida);
//                 $answered = true;
//                 break;
//             case 'c1_nit':
//                 $insertALog = $api->setLogBusqueda('c1', 'nit');
//                 $response = $api->getIndisNIT($number, $nombreSalida);
//                 $answered = true;
//                 break;
//             case 'c1_niu_search':
//                 if (!$answered) {
//                     $insertALog = $api->setLogBusqueda('c1', 'niu');
//                     $response = $api->getIndisNiu($number, false, $nombreSalida);
//                     $answered = true;
//                 }
//                 break;
//             case 'c1_niu':
//                 $typeIntent = $reqBody['queryResult']['intent']['displayName'];
//                 if ($typeIntent == 'c1_re_niu') {
//                     if (!$answered && strlen($number) > 0) {
//                         $insertALog = $api->setLogBusqueda('c1', 'niu');
//                         $response = $api->getSearchIndis($number, false, $nombreSalida, $reqBody, $contextoNombre);
//                         $answered = true;
//                     }
//                 }
//                 break;
//             case 'c1_niu_test':
//                 if (!$answered) {
//                     //$response = $api->getIndisNiu($number, true);
//                 }
//                 break;
//             case 'c1_nombre_municipio':
//                 $insertALog = $api->setLogBusqueda('c1', 'nombre');
//                 $nombre = $reqBody['queryResult']['queryText'];
//                 $response = $api->getIndisNombre($nombre, $municipio, false, $nombreSalida);
//                 $answered = true;
//                 break;
//             case 'c1_tel':
//                 $insertALog = $api->setLogBusqueda('c1', 'telefono');
//                 $response = $api->getIndisTelefono($number, false, $nombreSalida);
//                 $answered = true;
//                 break;
//             case 'c1_reporte_danos':
//                 $query = '';
//                 if ($reqBody['queryResult']['queryText'] != '👌 Enviar Reporte') {
//                     $query = $reqBody['queryResult']['queryText'];
//                 }
//                 $response = $api->generar_reportes_Sin_adicion($query, $niu, $telefono, $nombreCompleto);
//                 $answered = true;
//                 break;
//             case 'c1_info_adicional': //c1 info adicional es para guardar temporalmente la informacion adicional que el usuario usministre para un reporte de daño

//                 $response = $api->guardar_informacion_adicional($reqBody, $reqBody['queryResult']['queryText'], $niu, $nombreSalida, $contextoNombre, $contextoReporte);
//                 $answered = true;
//                 break;
//             case 'c1_generar_reporte': //c1 generar reporte es para guardar el resto de informacion del usuario luego de que este haya ingresado infromacion del fallo de energia
//                 $response = $api->guardar_reporte($reqBody, $niu, $telefono, $nombreCompleto, $apellidoCompleto, $nombreSalida, $cedula, $autorizacion);
//                 $answered = true;
//                 break;
//                 /* case 'c2':
//             if (!$answered) {
//             //$insertALog = $api->setLogBusqueda('c2', 'Consumos');
//             $response = $api->getConsumosSIEC($number, false);
//             $answered = true;
//             }
//             break;
//             case 'c3':
//             if (!$answered) {
//             //$insertALog = $api->setLogBusqueda('c3', 'Productos Activos');
//             $response = $api->getProductosActivosSIEC($number, false);
//             $answered = true;
//             }
//             break;
//             case 'c5':
//             if (!$answered) {
//             //$insertALog = $api->setLogBusqueda('c3', 'Productos Activos');
//             $response = $api->getFinanciacionesSIEC($number, false);
//             $answered = true;
//             }
//             break;
//             case 'c6':
//             if (!$answered) {
//             //$insertALog = $api->setLogBusqueda('c3', 'Productos Activos');
//             $response = $api->getHistoricoProductosSIEC($number, false);
//             $answered = true;
//             }
//             break; */
//             case 'calificacion':
//                 if (!$answered) {
//                     if (isset($con['parameters']['calNegativa'])) {
//                         $senderid = 'pruebasDatalab';
//                         if (isset($reqBody['originalDetectIntentRequest']['source'])) {
//                             if ($reqBody['originalDetectIntentRequest']['source'] == 'facebook') {
//                                 if ($reqBody['originalDetectIntentRequest']['payload']['data']['sender']['id'] != '2868075403232590') {
//                                     $senderid = $reqBody['originalDetectIntentRequest']['payload']['data']['sender']['id'];
//                                 }
//                             } elseif ($reqBody['originalDetectIntentRequest']['source'] == 'skype') {
//                                 $senderid = $reqBody['originalDetectIntentRequest']['payload']['data']['address']['conversation']['id'];
//                             } elseif ($reqBody['originalDetectIntentRequest']['source'] == 'telegram') {
//                                 if (isset($reqBody['originalDetectIntentRequest']['payload']['data']['message']['from']['id'])) {
//                                     if ($reqBody['originalDetectIntentRequest']['payload']['data']['message']['from']['id'] != 613620891) {
//                                         $senderid = $reqBody['originalDetectIntentRequest']['payload']['data']['message']['from']['id'];
//                                     }
//                                 } elseif (isset($reqBody['originalDetectIntentRequest']['payload']['data']['from']['id'])) {
//                                     if ($reqBody['originalDetectIntentRequest']['payload']['data']['from']['id'] != 613620891) {
//                                         $senderid = $reqBody['originalDetectIntentRequest']['payload']['data']['from']['id'];
//                                     }
//                                 }
//                             }
//                         }

//                         $response = $api->setCalificacion2($con['parameters']['calNegativa'], $senderid, $reqBody['queryResult']['queryText']);
//                     } else {
//                         $calificacion['id'] = 'pruebasDatalab';
//                         $calificacion['sessionId'] = $reqBody['session'];
//                         $calificacion['query'] = $reqBody['queryResult']['queryText'];
//                         $calificacion['source'] = 'pruebasDatalab';

//                         if (isset($reqBody['originalDetectIntentRequest']['source'])) {
//                             $calificacion['source'] = $reqBody['originalDetectIntentRequest']['source'];
//                             if ($reqBody['originalDetectIntentRequest']['source'] == 'facebook') {
//                                 if ($reqBody['originalDetectIntentRequest']['payload']['data']['sender']['id'] != '2868075403232590') {
//                                     $calificacion['id'] = $reqBody['originalDetectIntentRequest']['payload']['data']['sender']['id'];
//                                 }
//                             } elseif ($reqBody['originalDetectIntentRequest']['source'] == 'skype') {
//                                 $calificacion['id'] = $reqBody['originalDetectIntentRequest']['payload']['data']['address']['conversation']['id'];
//                             } elseif ($reqBody['originalDetectIntentRequest']['source'] == 'telegram') {
//                                 if (isset($reqBody['originalDetectIntentRequest']['payload']['data']['message']['from']['id'])) {
//                                     if ($reqBody['originalDetectIntentRequest']['payload']['data']['message']['from']['id'] != 613620891) {
//                                         $calificacion['id'] = $reqBody['originalDetectIntentRequest']['payload']['data']['message']['from']['id'];
//                                     }
//                                 } elseif (isset($reqBody['originalDetectIntentRequest']['payload']['data']['from']['id'])) {
//                                     if ($reqBody['originalDetectIntentRequest']['payload']['data']['from']['id'] != 613620891) {
//                                         $calificacion['id'] = $reqBody['originalDetectIntentRequest']['payload']['data']['from']['id'];
//                                     }
//                                 }
//                             }
//                         }

//                         if ($con['parameters']['calificacion'] != '') {
//                             $calificacion['calificacion'] = $con['parameters']['calificacion'];
//                         } else {
//                             $calificacion['calificacion'] = $con['parameters']['calificacion2'];
//                         }

//                         $niu_cuenta = '';
//                         foreach ($reqBody['queryResult']['outputContexts'] as $i => $con_tmp) {
//                             $name = explode('/', $con_tmp['name']);
//                             $largeContext = count($name);
//                             //$name[$largeContext - 1]
//                             if ($name[$largeContext - 1] == 'nombre') {
//                                 if (isset($con_tmp['parameters']['niu_cuenta'])) {
//                                     if (strlen($con_tmp['parameters']['niu_cuenta']) > 0) {
//                                         $niu_cuenta = $con_tmp['parameters']['niu_cuenta'];
//                                     }
//                                 }
//                             }
//                         }

//                         $response = $api->setCalificacion($calificacion, $niu_cuenta, $con['name']);
//                         $answered = true;
//                     }
//                 }
//                 break;
//             case 'c1_ubicacion_mostrardir': //---------------------------------------------------------------------------
//                 if ($typeIntent == 'c1_ubicacio_re_direccion_municipio') {
//                     //$insertALog = $api->setLogBusqueda('c1', 'ubicacion');
//                     $direccion = $reqBody['queryResult']['queryText'];
//                     $response = $api->getIndisAddress($direccion, $municipio, $nombreSalida, $reqBody);
//                     $answered = true;
//                     break;
//                 }
//             default:
//                 break;
//         }
//     }
// }

// header('Content-Type: application/json');
// echo json_encode($response);
