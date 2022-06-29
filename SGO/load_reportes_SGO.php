<?php

set_time_limit(3000);
//ini_set('memory_limit', '1024M');
error_reporting(-1);
ini_set('display_errors', 'On');

// $api = new chatBotApi();

// $data = $api->getReportsBD();

// if (is_array($data)) {
//     if (count($data) > 0) {
//         $datos = reportInsert($data);

//         var_dump($datos);
//         var_dump('FINAL');
//     }
// }

//$resultado = $api->setSuspensionEfectiva($datos);

class insertReportSGO
{
    
    public function reportInsert($data)
    {
        $reporte = $data['reporte'];
        $cliente = $data['cliente'];
    
        $ArrayMunicipios = array(
            'AGUADAS' => 1, 'ANSERMA' => 2, 'APIA' => 3, 'ARANZAZU' => 4, 'ARAUCA' => 19564, 'ARBOLEDA' => 27750, 'ARMA' => 27528, 'ARMENIA' => 28861, 'BALBOA' => 5, 'BELALCAZAR' => 6, 'BELEN DE UMBRIA' => 19565, 'BOGOTA' => 28858, 'BOLIVIA' => 26, 'BUENAVISTA' => 21527, 'CALI' => 28859, 'CARTAGO' => 28393, 'CASTILLA' => 32, 'CHINCHINA' => 7, 'DORADA' => 19561, 'LA DORADA' => 19561, 'DOSQUEBRADAS' => 8, 'FILADELFIA' => 19579, 'FLORENCIA' => 27753, 'GUADUAS' => 28273, 'GUATICA' => 9, 'SAN CLEMENTE' => 9, 'HERVEO' => 28288, 'HONDA' => 28392, 'IBAGUE' => 28426, 'IRRA' => 19589, 'ISAZA' => 19568, 'LA CELIA' => 12, 'LA MERCED' => 19567, 'LA VIRGINIA' => 13, 'MANIZALES' => 14, 'MANZANARES' => 15, 'MARIQUITA' => 28395, 'MARMATO' => 19569, 'MARQUETALIA' => 19570, 'MARSELLA' => 16, 'MARULANDA' => 17, 'MONTEBONITO' => 17, 'MEDELLIN' => 28860, 'MISTRATO' => 19571, 'NEIRA' => 18, 'NORCASIA' => 19, 'PACORA' => 20, 'PALESTINA' => 21, 'PENSILVANIA' => 19572, 'SAN DANIEL' => 19572, 'PEREIRA' => 34, 'PUEBLO NUEVO' => 31735, 'PUERTO SALGAR' => 28394, 'QUINCHIA' => 23, 'RIOSUCIO' => 24, 'RISARALDA' => 19573, 'SALAMINA' => 25, 'SAMANA' => 19574, 'BERLIN' => 19574, 'SAMARIA' => 19575, 'SAN ANTONIO DEL CHAMI' => 33, 'SAN BARTOLOME' => 27529, 'SAN DIEGO' => 26739, 'SAN FELIX' => 19576, 'SAN JOSE' => 19577, 'SANTA CECILIA' => 27, 'SANTA ROSA' => 28, 'SANTUARIO' => 29, 'SUPIA' => 19578, 'VICTORIA' => 19562, 'VILLAMARIA' => 30, 'VITERBO' => 31, 'PUEBLO RICO' => 22, 'VILLA CLARET' => 22,
        );
    
        try {
            foreach ($reporte as $key => $rep) {
                if (isset($rep->NOMBREUSUARIO)) {
                    foreach ($cliente as $k => $cli) {
                        foreach ($cli as $clave => $c) {
                            $flag = false;
                            $municipio = 0;
                            // var_dump($rep->NIU);
                            // var_dump($c->NIU);
                            if ($rep->NIU == $c->NIU) {
                                if (isset($ArrayMunicipios[strtoupper($c->MUNICIPIO)])) {
                                    // var_dump('Entro al envio 1');
                                    $municipio = $ArrayMunicipios[strtoupper($c->MUNICIPIO)];
                                    $flag = true;
                                } else {
                                    $municipio = strtoupper($c->MUNICIPIO);
                                    $flag = false;
                                    include './sendEmail.php';
                                    $apiMail = new sendEmailAPI();
                                    $send = $apiMail->errorReporte($data);
                                }
                                if ($flag) {
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
    
                                    $insert = GeneracionSDE($rep, $c, $municipio, $telefono);
    
                                    return $insert;
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $th) {
            // var_dump('try catch');
            // include './sendEmail.php';
            // $apiMail = new sendEmailAPI();
    
            // $send = $apiMail->errorReporte($th);
        }
    }
    
    public function executePostgres($conexion, $query)
    {
        try {
            $sql = pg_exec($conexion, $query);
    
            return $sql;
            /* if ($sql) {
                $response = pg_fetch_all($sql);
                var_dump(' ERRORR');
    
                return $response;
            } else {
                var_dump(' ERRORR SQL');
    
                return $sql;
            } */
        } catch (exception $e) {
            return var_dump($e);
        }
    }
    
    public function executeDDLOracle($conexion, $query)
    {
        $stid = oci_parse($conexion, $query);
        oci_execute($stid, OCI_DEFAULT);
    
        return $stid;
    }
    
    public function executeOracle($conexion, $query)
    {
        $stmt = oci_parse($conexion, $query);
        if (!$stmt) {
            $e = oci_error($conexión);
    
            return $e;
        }
    
        $r = oci_execute($stmt);
        if (!$r) {
            $e = oci_error($stmt);
    
            return $e;
        }
    
        $nrows = oci_fetch_all($stmt, $res);
    
        return $res;
    }
    
    public function conectedPostgres()
    {
        // $conexion = pg_connect('host=chec-apd05 dbname=sgc user=intindissgo password=intindissgo');
        $conexion = pg_connect(getenv('connectionSGOPostgres'));
    
        if (!$conexion) {
            var_dump($conexion);
            exit;
        } else {
            return $conexion;
        }
    }
    
    public function conectedOracle()
    {
        try {
            $conn = oci_connect(getenv('userSGOOracle'), getenv('pwdSGOOracle'), getenv('connectionSGOOracle'));
    
            return $conn;
        } catch (\Exception $th) {
            echo $th;
            exit;
        }
    }
    
    #FUNCION QUE INSERTA EN EL SGO
    public function GeneracionSDE($rep, $c, $municipio, $telefono)
    {
        $resp = '';
        try {
            // CONEXION A POSTGRES Y A ORACLE
            $conexionPostgres = conectedPostgres();
            $conexionOracle = conectedOracle();
    
            $fechaConocimiento = $rep->FECHA_REPORTE;
            $matriculaSuscriptor = $rep->NIU;
            $nombreSolicitante = $rep->NOMBREUSUARIO;
            $telefonoSolicitante = $rep->TELEFONO;
            $telefonoInmueble = $telefono;
            $direccionInmueble = $c->DIRECCION;
            $direccionPrecisa = $c->DIRECCION;
            $notaSolicitante = $rep->RESOLVEQUERY;
            $notaRecepcion = '';
            $sintoma = 38485;
            $localidad = $municipio;
            $origen = 'LUCY';
            // $usuario = 'INTINDISSGO';
            // $usuario = 'INTCHATBSPARD';
             
    
            $circuito = '';
    
            //VARIABLE SI DEFINIR
            $pforzada = 0;
    
            // $sql = "SELECT * FROM sgc.tblservicio WHERE fldserserid = '3956498'";
            // $rs = executePostgres($conexionPostgres, $sql);
            // var_dump($rs);
    
            // $sqlsecuencia = "SELECT nextval('sgc.seq_servicio')";
    
            // $rs = executePostgres($conexionPostgres, $sqlsecuencia);
    
            // if (!$rs) {
            //     $resp = 'ERROR$$ AL CONSULTAR LA SECUENCIA DE LA TABLA SGC.TBLSERVICIO SQL: '.$sqlsecuencia.'--'.utf8_encode($rs);
    
            //     return $resp;
            // }
    
            // $secuencia = pg_result($rs, 0, 0);
    
            $queryInsertServicio = "INSERT INTO sgc.tblservicio (fldtimserfecha_conocimiento,fldvchserid_suscriptor, fldvchsernombre, fldvchsertelefono1,fldvchsertelefono2,
                                            fldvchserdireccion, fldvchserdireccion_precisa, fldvchserdescripcion, fldvchsernotas, fldintserid_localidad, fldvchorigen)
                    VALUES ('$fechaConocimiento', '$matriculaSuscriptor', '$nombreSolicitante', '$telefonoSolicitante', '$telefonoInmueble', '$direccionInmueble',
                            '$direccionPrecisa', '$notaSolicitante', '',$localidad, 'LUCY') returning fldserserid";
    
            $rs = executePostgres($conexionPostgres, $queryInsertServicio);
            if (!$rs) {
                $resp = 'ERROR$$ AL INSERTAR EN LA TABLA TBLSERVICIO: '.$queryInsertServicio.'--'.utf8_encode($rs);
    
                return $resp;
            }
            $rs = pg_result($rs, 0, 0);
    
            #DATO PARA RETORNAR
            $insertfldserserid = $rs;
    
            $queryInsertSintoma = "INSERT INTO sgc.tblservicio_sintoma(
                fldintssiid_sintoma,fldintssiid_servicio, fldintssiid_usu)
                VALUES($sintoma,$insertfldserserid,'$matriculaSuscriptor') returning fldintssiid_sintoma";
    
            $rs = executePostgres($conexionPostgres, $queryInsertSintoma);
    
            if (!$rs) {
                $resp = 'ERROR$$ AL INSERTAR EN LA TABLA TBLSERVICIO_SINTOMA: '.$queryInsertSintoma.'--'.utf8_encode($rs);
    
                return $resp;
            }
    
            $sqlcircuito = "SELECT fldvchsusid_nodo, fldvchsusid_trafo from sgc.tblsuscriptor where fldvchsusid='$matriculaSuscriptor'";
    
            $rscircuito = executePostgres($conexionPostgres, $sqlcircuito);
            if ($rscircuito) {
                while ($campo = pg_fetch_row($rscircuito)) {
                    $circuito = $campo[0];
                    $transformador = $campo[1];
                }
            } else {
                $resp .= 'ERROR$$ NO PUDO TRAER DATOS DEL CIRCUITO: '.$sqlcircuito.'--'.utf8_encode($rscircuito);
    
                return $resp;
            }
    
            if ($pforzada == 0) {
                $rural = "SELECT count(1) from AREDES.TRANSFOR where upper(code)=upper('".$transformador."') and group_ =4";
    
                $rsrural = executeOracle($conexionOracle, $rural);
    
                //  echo "----------------- ".$rsrural->fields[0]."-----------------------------";
    
                if (intval($rsrural['COUNT(1)'][0]) > 0) {
                    $pforzada = 2;
                }
            }
    
            // // DELETE ORACLE TABLAS
            $sqldelete = "DELETE FROM CALIDAD097.CHC097_SDETMP WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
    
            $rs = executeDDLOracle($conexionOracle, $sqldelete);
            if (!$rs) {//SI TIENE UN ERROR  AL BORRAR LOS REGISTROS DE ESTE USUARIO
                $resp = 'ERROR$$ NO PUDO SER ELIMINADOS LOS REGISTROS DE LA TABLA TEMPORAL SDE: SQL: '.$sqldelete.'--'.utf8_encode($rs);
    
                return $resp;
            }
            $sqldelete = "DELETE FROM CALIDAD097.CHC097_ODSTEMP WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
            $rs = executeDDLOracle($conexionOracle, $sqldelete);
            if (!$rs) {//SI TIENE UN ERROR  AL BORRAR LOS REGISTROS DE ESTE USUARIO
                $resp = 'ERROR$$ NO PUDO SER ELIMINADOS LOS REGISTROS DE LA TABLA TEMPORAL CHC097_ODSTEMP: SQL: '.$sqldelete.'--'.utf8_encode($rs);
    
                return $resp;
            }
            $sqldeleteras = "DELETE FROM CALIDAD097.CHC097_RESAGUASABAJO_STANDAR WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
            $rs = executeDDLOracle($conexionOracle, $sqldeleteras);
            if (!$rs) {//SI TIENE UN ERROR  AL BORRAR LOS REGISTROS DE ESTE USUARIO
                $resp = 'ERROR$$ NO PUDO SER ELIMINADOS LOS REGISTROS DE LA TABLA TEMPORAL CHC097_RESAGUASABAJO_STANDAR: SQL: '.$sqldelete.'--'.utf8_encode($rs);
    
                return $resp;
            }
            $sqldeleteras = "DELETE FROM CALIDAD097.CHC097_USU_AFECTTMP WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
            $rs = executeDDLOracle($conexionOracle, $sqldeleteras);
            if (!$rs) {//SI TIENE UN ERROR  AL BORRAR LOS REGISTROS DE ESTE USUARIO
                $resp .= 'ERROR$$ NO PUDO SER ELIMINADOS LOS REGISTROS DE LA TABLA TEMPORAL CALIDAD097.CHC097_USU_AFECTTMP SQL:'.$sqldeleteras.'--'.utf8_encode($rs);
    
                return $resp;
            }
    
            //CONSULTA PARA INSERTAR EN LA TABLA CALIDAD097.CHC097_ODSTEMP
            $sql = "SELECT fldintserid_orden as cod_odo, fldserserid as cod_ods, fldtimserfecha_conocimiento,
                        fldvchsusid_nodo as circuito, fldvchsusid_trafo as trafo, fldintserid_sde
                FROM sgc.tblservicio LEFT JOIN sgc.tblsuscriptor
                ON(sgc.tblservicio.fldvchserid_suscriptor = sgc.tblsuscriptor.fldvchsusid)
                LEFT JOIN sgc.tblorden ON fldintserid_orden = fldserodtid
                left join sgc.tblsde ON  (sgc.tblsde.fldsersdeid=sgc.tblservicio.fldintserid_sde)
                WHERE sgc.tblsuscriptor.fldvchsusid_nodo ='".$circuito."'
                AND (fldvchestadoatencion='EJC' OR fldvchestadoatencion='EJE')
                and fldintserid_orden is null
                and fldintserid_sde is not null
                and sgc.tblsde.fldintsdeid_odo is null
                ORDER BY fldtimsercreacion";
    
            $reg = executePostgres($conexionPostgres, $sql);
    
            if (!$reg) {
                $resp = 'ERROR$$ NO PUDO TRAER DATOS DE ODSTEMP USUARIO: SQL: '.$sql.'--'.utf8_encode($reg);
    
                return $resp;
            }
    
            if (pg_numrows($reg) > 0) {
                while ($fields = pg_fetch_row($reg)) {
                    $cod_ods = '';
                    if ($fields[1] == '') {
                        $cod_ods = 'null';
                    } else {
                        $cod_ods = $fields[1];
                    }
    
                    $odo = '';
                    if ($fields[0] == '') {
                        $odo = 'null';
                    } else {
                        $odo = $fields[0];
                    }
                    $sde = '';
                    if ($fields[5] == '') {
                        $sde = 'null';
                    } else {
                        $sde = $fields[5];
                    }
                    $insertsql = 'INSERT INTO CALIDAD097.CHC097_ODSTEMP
                    (COD_ODO, COD_ODS, FECHA_CONOCIMIENTO,
                    CIRCUITO, TRANSFORMADOR, USUARIO, SDE)
                    VALUES ('.$odo.','.$cod_ods.",to_date('".$fields[2]."','YYYY-MM-DD hh24:mi:ss'),
                    '".$fields[3]."','".$fields[4]."',upper('".$usuario."'),".$sde.' )';
    
                    $rs = executeDDLOracle($conexionOracle, $insertsql);
    
                    if (!$rs) {//SI TIENE UN ERROR  GUARDA EL REGISTRO CON EL ERRO EN UNA CADENA
                        $resp = 'ERROR$$ NO PUDO SER INSERTADO EL REGISTRO EN LA TABLA CALIDAD097.CHC097_ODSTEMP SQL: '.$insertsql.'--'.utf8_encode($rs);
    
                        return $resp;
                    }
                }
            }
    
            //CONSULTA PARA INSERTAR EN LA TABLA CALIDAD097.chc097_sdetmp
    
            $sql = "SELECT sde.fldsersdeid, sde.fldintsdeid_zona, sde.fldflosde_vma, sde.fldintsdeid_odo,
                sde.fldvchsde_nodocomun, sde.fldvchsde_tiponodo, sde.fldintsde_cant_usu_afect, sde.fldintsde_sde_padre
                FROM sgc.tblsde as sde ,
                (select  fldintserid_sde
                FROM sgc.tblservicio LEFT JOIN sgc.tblsuscriptor ON(sgc.tblservicio.fldvchserid_suscriptor = sgc.tblsuscriptor.fldvchsusid)
                WHERE sgc.tblsuscriptor.fldvchsusid_nodo ='".$circuito."'  AND fldvchestadoatencion='EJE'
                and fldintserid_sde is not null
                and fldintserid_orden is null
                group by fldintserid_sde)as codsde
                where sde.fldsersdeid=codsde.fldintserid_sde
                and   fldintsdeid_odo is null
                order by sde.fldsersdeid";
    
            $reg = executePostgres($conexionPostgres, $sql);
    
            if (!$reg) {
                $resp = 'ERROR$$ NO PUDO TRAER DATOS DE LA TABLA CALIDAD097.CHC097_SDETMP SQL: '.$sql.'--'.utf8_encode($reg);
    
                return $resp;
            }
    
            if (pg_numrows($reg) > 0) {
                while ($fields = pg_fetch_row($reg)) {
                    $sdepadre = '';
    
                    if ($fields[7] == '') {
                        $sdepadre = 'null';
                    } else {
                        $sdepadre = $fields[7];
                    }
                    $odo = '';
    
                    if ($fields[3] == '') {
                        $odo = 'null';
                    } else {
                        $odo = $fields[3];
                    }
                    $vma = '';
    
                    if ($fields[2] == '') {
                        $vma = 'null';
                    } else {
                        $vma = $fields[2];
                    }
                    $cantusuafec = '';
    
                    if ($fields[6] == '') {
                        $cantusuafec = 'null';
                    } else {
                        $cantusuafec = $fields[6];
                    }
    
                    $insertsql = 'INSERT INTO calidad097.chc097_sdetmp
                            (fldsersdeid, fldintsdeid_zona, fldflosde_vma, fldintsdeid_odo, fldvchsde_nodocomun,
                                fldvchsde_tiponodo, fldintsde_cant_usu_afect, fldintsde_sde_padre, usuario, FLDVCHSDE_SDE_HIJA , FLDVCHSDE_RESPUESTA)
                            VALUES
                            ('.$fields[0].', '.$fields[1].', '.$vma.', '.$odo.", '".$fields[4]."',
                            '".$fields[5]."', ".$cantusuafec.', '.$sdepadre.", upper('".$usuario."'), '0', '0' )";
    
                    $rs = executeDDLOracle($conexionOracle, $insertsql);
    
                    if (!$rs) {//SI TIENE UN ERROR  GUARDA EL REGISTRO CON EL ERRO EN UNA CADENA
                        $resp = 'ERROR$$ NO PUDO SER INSERTADO EL REGISTRO  EN LA TABLA CALIDAD097.CHC097_SDETMP SQL: '.$insertsql.'--'.utf8_encode($rs);
    
                        return $resp;
                    }
                }
            }
    
            $sqlaguasabajo = "BEGIN CALIDAD097.CHC097_AUTOGENERACIONSDE.GENERACION ( '".$transformador."', '".$circuito."', '".$usuario."','".$pforzada."'); END;";
            var_dump($sqlaguasabajo);
            $rs = executeDDLOracle($conexionOracle, $sqlaguasabajo);
    
            if ($rs == false) {
                $e = oci_error($stmt);
                $resp = 'ERROR$$ NOSE PUDO INICIAR EL PROCEDIMIENTO CHC097_AUTOGENERACIONSDE.GENERACION SQL: '.utf8_encode($e);
    
                return $resp;
            } else {
                $sqlverificacion = "SELECT ESTADO, MENSAJE_ERROR, PROCESO
                                FROM CALIDAD097.CHC097_RESAGUASABAJO_STANDAR
                                WHERE UPPER(USUARIO)=UPPER('".$usuario."')";
    
                $rs = executeOracle($conexionOracle, $sqlverificacion);
    
                //PENDIENTE DE VERIFICACION
                if ($rs) {
                    $resultado = $rs['ESTADO'][0];
                    $msjerror = $rs['MENSAJE_ERROR'][0];
                    $enproceso = $rs['PROCESO'][0];
                }
                if ($resultado == '0') {
                    $sql = "SELECT * FROM CALIDAD097.CHC097_SDETMP WHERE UPPER(USUARIO)=UPPER('".$usuario."') AND  FLDVCHSDE_RESPUESTA ='1'";
    
                    $rs = executeOracle($conexionOracle, $sql);
                    if (!$rs) {
                        $resp .= 'ERROR$$ AL EJECUTAR CONSULTA A LA TABLA  CALIDAD097.CHC097_SDETMP USUARIO: '.$usuario.'$$ SQL: '.$sql.'--'.utf8_encode($conexionOracle);
    
                        return $resp;
                    }
                    $fldsersdeid = $rs['FLDSERSDEID'][0];
                    $fldintsdeid_zona = $rs['FLDINTSDEID_ZONA'][0];
                    $fldflosde_vma = str_replace(',', '.', $rs['FLDFLOSDE_VMA'][0]);
                    $fldintsdeid_odo = $rs['FLDINTSDEID_ODO'][0];
                    $fldvchsde_nodocomun = $rs['FLDVCHSDE_NODOCOMUN'][0];
                    $fldvchsde_tiponodo = $rs['FLDVCHSDE_TIPONODO'][0];
                    $fldintsde_cant_usu_afect = $rs['FLDINTSDE_CANT_USU_AFECT'][0];
                    $fldintsde_sde_padre = $rs['FLDINTSDE_SDE_PADRE'][0];
    
                    // var_dump('PENDIENTE DE PERMISOS');
                    $sqlzona = "SELECT FLDINTSUB_ZONA
                    FROM SGC.TBLSUBESTACIONES, SGC.TBLGRUPOOPERATIVO
                    WHERE FLDINTGOPID_INSTALACION = FLDINTSUB_CODIGO
                    AND FLDVCHGOPID = (SELECT  FLDVCHSUSID_NODO
                    FROM SGC.TBLSERVICIO LEFT JOIN SGC.TBLSUSCRIPTOR
                    ON(SGC.TBLSERVICIO.FLDVCHSERID_SUSCRIPTOR = SGC.TBLSUSCRIPTOR.FLDVCHSUSID)
                    WHERE  SGC.TBLSERVICIO.FLDSERSERID='".$insertfldserserid."')
                    LIMIT 1";
    
                    $rs = executePostgres($conexionPostgres, $sqlzona);
    
                    if (!$rs) {
                        $resp .= 'ERROR$$ AL EJECUTAR CONSULTA A LA TABLA  SGC.TBLSERVICIO LEFT JOIN SGC.TBLSUSCRIPTOR USUARIO: '.$usuario.'$$ SQL: '.$sqlzona.'--'.utf8_encode($conexionPostgres);
    
                        return $resp;
                    }
    
                    $rs = pg_result($rs, 0, 0);
                    $fldintsdeid_zona = $rs;
    
                    if ($fldsersdeid == '') {//se  va a crear  la SDE nueva
                        $sqlsecuencia = "SELECT nextval('sgc.seq_sde')";
    
                        $rs = executePostgres($conexionPostgres, $sqlsecuencia);
    
                        if (!$rs) {
                            $resp .= 'ERROR$$ AL CONSULTAR LA SECUENCIA DE LA TABLA SGC.TBLSDE  USUARIO: '.$usuario.'$$ SQL: '.$sqlsecuencia.'--'.utf8_encode($conexionPostgres);
    
                            return $resp;
                        }
    
                        // $secuencia = $rs->fields[0];
                        $secuenciaSDE = pg_result($rs, 0, 0);
    
                        //INSERTAMOS  UN REGISTRO DE SDE EN LA TABLA SGC.TBLSDE
    
                        $sqlinsert = 'INSERT INTO sgc.tblsde(
                                        fldsersdeid, fldintsdeid_zona, fldflosde_vma, fldintsdeid_odo,
                                        fldvchsde_nodocomun, fldvchsde_tiponodo, fldintsde_cant_usu_afect,
                                        fldintsde_sde_padre)
                                VALUES ('.$secuenciaSDE.', '.$fldintsdeid_zona.' , '.$fldflosde_vma." , null,
                                        '".$fldvchsde_nodocomun."', '".$fldvchsde_tiponodo."', ".$fldintsde_cant_usu_afect.',
                                        NULL) returning fldsersdeid';
    
                        $rs = executePostgres($conexionPostgres, $sqlinsert);
    
                        if (pg_numrows($rs) == 0) {
                            $resp .= 'ERROR$$ AL EJECUTAR LA INSERCION EN LA TABLA SGC.TBLSDE USUARIO: '.$usuario.'$$ SQL: '.$sqlinsert.'--'.utf8_encode($conexionPostgres);
    
                            return $resp;
                        } else {
                            //ACTUALIZAMOS  EL CODIGO DEL SDE  EN LA TABLA DE USUARIOS AFECTADOS chc097_usu_afecttmp
    
                            $sqlupdateuserafec = 'UPDATE calidad097.chc097_usu_afecttmp
                                    set sde='.$secuenciaSDE.'
                                    where sde=99999999';
    
                            $rs = executeDDLOracle($conexionOracle, $sqlupdateuserafec);
    
                            if (!$rs) {
                                $resp .= 'ERROR$$ AL EJECUTAR LA ACTUALIZACION DE EL CAMPOS SDE EN LA TABLA calidad097.chc097_usu_afecttmp USUARIO: '.$usuario.'$$ SQL: '.$sqlupdateuserafec.'--'.utf8_encode($conexionOracle);
    
                                return $resp;
                            }
                        }
    
                        //SI TRAJO  SDE COMO HIJAS  SE SACA EL LISTADO PARA ACTUALIZAR EN LA TABLA  DE LA SDE A CREAR
    
                        $sqluphijas = "SELECT FLDSERSDEID FROM CALIDAD097.CHC097_SDETMP WHERE UPPER(USUARIO)=UPPER('".$usuario."') AND  FLDVCHSDE_SDE_HIJA ='1'";
                        $rs = executeOracle($conexionOracle, $sqluphijas);
                        if (!$rs) {
                            $resp .= 'ERROR$$ AL EJECUTAR CONSULTA A LA TABLA  CALIDAD097.CHC097_SDETMP USUARIO: '.$usuario.'$$ SQL: '.$sqluphijas.'--'.utf8_encode($conexionOracle);
    
                            return $resp;
                        }
    
                        if (count($rs) > 0) {
    
                            foreach ($rs as $fields) {
                            
                                if (isset($fields[0])) {
                                    //ACTUALIZAMOS CADA UNO DE LAS SDE QUE ENCEONTRO COMO HIJAS Y LE COLOCAMOS LA SDE CREADA COMO PADRE
    
                                    $sqluppadre = 'UPDATE sgc.tblsde set fldintsde_sde_padre='.$secuenciaSDE.' where fldsersdeid='.$fields[0].' RETURNING fldsersdeid';
    
                                    $rs1 = executePostgres($conexionPostgres, $sqluppadre);
    
                                    if (!$rs1) {
                                        $resp .= 'ERROR$$ AL EJECUTAR LA ACTUALIZACION EN LA TABLA SGC.TBLSDE USUARIO: '.$usuario.'$$ SQL: '.$sqluppadre.'--'.utf8_encode($conexionPostgres);
    
                                        return $resp;
                                    }
                                }
                            }
                        }
    
                        //PENDIENTE VERIFICACION DE VARIABLES
                        // if ($secuenciaSDE != '0' && $secuenciaSDE != '') {
                        //     $sqlactualizamos_sdeold = "UPDATE sgc.tblsde set fldintsdeid_odo= null where fldsersdeid='".$secuenciaSDE."' RETURNING fldsersdeid";
    
                        //     if ($fldintsdeid_odo != '') {
                        //         $sqlactualizamos_sde_new = 'UPDATE sgc.tblsde set fldintsdeid_odo= '.$fldintsdeid_odo." where fldsersdeid='".$secuenciaSDE."'";
    
                        //         $rsactualizamos_sde_new = executePostgres($conexionPostgres, $sqlactualizamos_sde_new);
                        //     }
                        // }
    
                        //ACTUALIZAMOSENEL CAMPOS SDE DE LA TABLA SGC.TBLSERVICIO
                        $sqlactualizacionsde = "UPDATE sgc.tblservicio set fldintserid_sde='".$secuenciaSDE."', fldvchestadoatencion = 'EJE' where fldserserid='".$insertfldserserid."' RETURNING fldserserid";
    
                        $rs = executePostgres($conexionPostgres, $sqlactualizacionsde);
    
                        if (!$rs) {
                            $resp .= 'ERROR$$ AL ACTUALIZAR LA SDE EN LA TABLA  SGC.TBLSERVICIO  USUARIO: '.$usuario.'$$ SQL: '.$sqlactualizacionsde.'--'.utf8_encode($conexionPostgres);
    
                            return $resp;
                        }
    
                        $resp .= 'OK$$ SE CREO CORRECTAMENTE LA SDE';
    
                        $sqldeleteras = "DELETE FROM CALIDAD097.CHC097_RESAGUASABAJO_STANDAR WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
    
                        $rs = executeDDLOracle($conexionOracle, $sqldeleteras);
    
                        return $resp;
                    } else {
                        //se asocia a la SDE que trae
    
                        if ($fldflosde_vma != '' && $fldvchsde_nodocomun != '' && $fldintsde_cant_usu_afect != '' && $fldvchsde_tiponodo != '') {
                            //ACTUALIZAMOS LA SDE CON LOS DATOS TRAIDOS PR EL PROCEDIMIENTO ALMACENADO
                            $sqlupdateinsert = 'UPDATE sgc.tblsde
                            SET fldflosde_vma='.$fldflosde_vma.", fldvchsde_nodocomun='".$fldvchsde_nodocomun."' , fldvchsde_tiponodo='".$fldvchsde_tiponodo."', fldintsde_cant_usu_afect=".$fldintsde_cant_usu_afect."
                            WHERE fldsersdeid='".$fldsersdeid."' RETURNING fldsersdeid";
    
                            $rs = executePostgres($conexionPostgres, $sqlupdateinsert);
    
                            if (!$rs) {
                                $resp .= 'ERROR$$ AL EJECUTAR LA ACTUALIZACION EN LA TABLA SGC.TBLSDE USUARIO: '.$usuario.'$$ SQL: '.$sqlupdateinsert.'--'.utf8_encode($conexionPostgres);
    
                                return $resp;
                            }
                            $sql_actualizar_odo = "SELECT fldintsdeid_odo  FROM sgc.tblsde where fldsersdeid='".$fldsersdeid."'";
                            $rs_actualizar_odo = executePostgres($conexionPostgres, $sql_actualizar_odo);
    
                            if ($rs_actualizar_odo) {
                                if (pg_numrows($rs_actualizar_odo) > 0) {
                                    while ($fields = pg_fetch_row($rs_actualizar_odo)) {
                                        if (($fields[0] != '') && ($fields[0] != 'null') && ($fields[0] != null)) {
                                            $rs_actualizar = $fields[0];
                                            $sqlactualizacionODO = "UPDATE sgc.tblservicio set fldintserid_orden='".$rs_actualizar."' where fldintserid_sde='".$fldsersdeid."' RETURNING fldintserid_sde";
    
                                            $rs_actualizacionODO = executePostgres($conexionPostgres, $sqlactualizacionODO);
    
                                            if (!$rs_actualizacionODO) {
                                                $resp .= 'ERROR$$ AL ACTUALIZAR LA ODO EN LA TABLA  SGC.TBLSERVICIO  USUARIO: '.$usuario.'$$ SQL: '.$sqlactualizacionsde.'-ODO:'.$rs_actualizar_odo[0].':-'.utf8_encode($conexionPostgres);
    
                                                return $resp;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $resp .= 'ERROR$$ AL CONSULTAR LA TABLA  SGC.TBLSDE  USUARIO: '.$usuario.'$$ SQL: '.$sql_actualizar_odo.'--'.utf8_encode($conexionPostgres);
    
                                return $resp;
                            }
                        }
    
                        //ACTUALIZAMOSENEL CAMPOS SDE DE LA TABLA SGC.TBLSERVICIO
    
                        $sqlactualizacionsde = "UPDATE sgc.tblservicio set fldintserid_sde='".$fldsersdeid."' where fldserserid='".$insertfldserserid."' RETURNING fldserserid";
    
                        $rs = executePostgres($conexionPostgres, $sqlactualizacionsde);
    
                        if (!$rs) {
                            $resp .= 'ERROR$$ AL ACTUALIZAR LA SDE EN LA TABLA  SGC.TBLSERVICIO  USUARIO: '.$usuario.'$$ SQL: '.$sqlactualizacionsde.'--'.utf8_encode($conexionPostgres);
    
                            return $resp;
                        }
    
                        $sql_actualizar_odo = "SELECT fldintsdeid_odo  FROM sgc.tblsde where fldsersdeid='".$fldsersdeid."'";
    
                        $rs_actualizar_odo = executePostgres($conexionPostgres, $sql_actualizar_odo);
    
                        if ($rs_actualizar_odo) {
                            if (pg_numrows($rs_actualizar_odo) > 0) {
                                $rs_actualizar_odo = pg_result($rs_actualizar_odo, 0, 0);
                                //ACTUALIZAMOSENEL CAMPOS SDE DE LA TABLA SGC.TBLSERVICIO
    
                                if (($rs_actualizar_odo != '') && ($rs_actualizar_odo != 'null') && ($rs_actualizar_odo != null)) {
                                    $sqlactualizacionODO = "UPDATE sgc.tblservicio set fldintserid_orden='$rs_actualizar_odo' where fldintserid_sde='$fldsersdeid'";
    
                                    $rs_actualizacionODO = executePostgres($conexionPostgres, $sqlactualizacionODO);
    
                                    if (!$rs_actualizacionODO) {
                                        $resp .= 'ERROR$$ AL ACTUALIZAR LA ODO EN LA TABLA  SGC.TBLSERVICIO  USUARIO: '.$usuario.'$$ SQL: '.$sqlactualizacionsde.'-ODO:'.$rs_actualizar_odo[0]['fldintsdeid_odo'].':-'.utf8_encode($conexionPostgres);
    
                                        return $resp;
                                    }
                                }
                            }
                        } else {
                            $resp .= 'ERROR$$ AL CONSULTAR LA TABLA  SGC.TBLSDE  USUARIO: '.$usuario.'$$ SQL: '.$sql_actualizar_odo.'--'.utf8_encode($conexionPostgres);
    
                            return $resp;
                        }
    
                        $resp .= 'OK$$ SE CREO CORRECTAMENTE LA SDE';
    
                        $sqldeleteras = "DELETE FROM CALIDAD097.CHC097_RESAGUASABAJO_STANDAR WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
    
                        $rs = executeDDLOracle($conexionOracle, $sqldeleteras);
    
                        return $resp;
                    }
                } else {
                    $resp .= 'ERROR$$ NO EXISTEN DATOS EN LA TABLA CALIDAD097.CHC097_RESAGUASABAJO_STANDAR  '.$msjerror.' EN EL PROCESO '.$enproceso.' USUARIO: '.$usuario.'$$ SQL: '.$sqlverificacion.'--'.utf8_encode($conexionOracle);
    
                    return $resp;
                }
                $sqldeleteras = "DELETE FROM CALIDAD097.CHC097_RESAGUASABAJO_STANDAR WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
                $rs = executeDDLOracle($conexionOracle, $sqldeleteras);
            }
        } catch (exception $e) {
            return $resp.' - '.$e;
        }
    
        return $resp;
    }
}

