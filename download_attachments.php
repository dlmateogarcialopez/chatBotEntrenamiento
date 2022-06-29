<?php

//ESTE BLOQUE TRABAJA CON SUSPENSIONES PROGRAMADAS
function get_attachments()
{
    set_time_limit(3000);

    /* connect to gmail with your credentials */
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'prjchec.suspensiones_programadas@umanizales.edu.co';
    $password = 'umCHEC1234_761349';

    /* try to connect */
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: '.imap_last_error());

    //$emails = imap_search($inbox, 'FROM "JHON.CALDERON@chec.com.co" UNSEEN');
    $emails = imap_search($inbox, 'FROM "notificacionsgo@chec.com.co" UNSEEN');
    /* if any emails found, iterate through each email */
    if ($emails) {
        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        /* for every email... */
        foreach ($emails as $email_number) {
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);

            $message = imap_fetchbody($inbox, $email_number, 2);

            /* get mail structure */
            $structure = imap_fetchstructure($inbox, $email_number);

            $attachments = array();

            $nextPartsD = false;
            $nextPartsI = false;

            /* if any attachments found... */
            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); ++$i) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => '',
                    );

                    if (isset($structure->parts[$i]->dparameters) && count($structure->parts[$i]->dparameters)) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }
                    if (isset($structure->parts[$i]->parameters) && count($structure->parts[$i]->parameters)) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }
                    if (strlen($attachments[$i]['filename']) == 0 && strlen($attachments[$i]['name']) == 0) {
                        if (isset($structure->parts[$i]->parts) && count($structure->parts[$i]->parts)) {
                            foreach ($structure->parts[$i]->parts as $object) {
                                if (isset($object->dparameters) && count($object->dparameters)) {
                                    foreach ($object->dparameters as $obj) {
                                        if (strtolower($obj->attribute) == 'filename') {
                                            $attachments[$i]['is_attachment'] = true;
                                            $attachments[$i]['filename'] = $obj->value;
                                        }
                                    }
                                }
                                if (isset($object->parameters) && count($object->parameters)) {
                                    foreach ($object->parameters as $obj) {
                                        if (strtolower($obj->attribute) == 'name') {
                                            $attachments[$i]['is_attachment'] = true;
                                            $attachments[$i]['name'] = $obj->value;
                                        }
                                    }
                                }

                                if (strlen($attachments[$i]['filename']) == 0 && strlen($attachments[$i]['name']) == 0) {
                                    if (isset($object->parts) && count($object->parts)) {
                                        foreach ($object->parts as $ob) {
                                            if (isset($ob->dparameters) && count($ob->dparameters)) {
                                                foreach ($ob->dparameters as $obj) {
                                                    if (strtolower($obj->attribute) == 'filename') {
                                                        $attachments[$i]['is_attachment'] = true;
                                                        $attachments[$i]['filename'] = $obj->value;
                                                    }
                                                }
                                            }
                                            if (isset($ob->parameters) && count($object->parameters)) {
                                                foreach ($ob->parameters as $obj) {
                                                    if (strtolower($obj->attribute) == 'name') {
                                                        $attachments[$i]['is_attachment'] = true;
                                                        $attachments[$i]['name'] = $obj->value;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    /* if(!$nextPartsD && !$nextPartsI && isset($structure->parts[$i]->parts) && is_array($structure->parts[$i]->parts)){
                    foreach ($structure->parts[$i]->parts as $part) {
                    if ($part->ifdparameters) {
                    foreach ($part->dparameters as $object) {
                    if (strtolower($object->attribute) == 'filename') {
                    $attachments[$i]['is_attachment'] = true;
                    $attachments[$i]['filename'] = $object->value;
                    $nextPartsD = true;
                    }
                    }
                    }
                    if ($part->ifparameters) {
                    foreach ($part->parameters as $object) {
                    if (strtolower($object->attribute) == 'name') {
                    $attachments[$i]['is_attachment'] = true;
                    $attachments[$i]['name'] = $object->value;
                    $nextPartsI = true;
                    }
                    }
                    }
                    }
                    } */

                    /* if ($structure->parts[$i]->ifdparameters) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                                $nextPartsD = true;
                            }
                        }
                    }
                    if ($structure->parts[$i]->ifparameters) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                                $nextPartsI = true;
                            }
                        }
                    } */

                    if ($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                        /* 3 = BASE64 encoding */
                        if ($structure->parts[$i]->encoding == 3) {
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        }
                        /* 4 = QUOTED-PRINTABLE encoding */
                        elseif ($structure->parts[$i]->encoding == 4) {
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
                /* var_dump("----------------------------------------------------------");
                var_dump("----------------------------------------------------------");
                var_dump("----------------------------------------------------------");
                var_dump($attachments); */
            }

            /* iterate through each attachment and save it */
            foreach ($attachments as $attachment) {
                if ($attachment['is_attachment'] == 1) {
                    $filename = substr($attachment['name'], 0, -3).'html';
                    if (empty($filename)) {
                        $filename = $attachment['filename'];
                    }

                    if (empty($filename)) {
                        $filename = time().'.dat';
                    }

                    $folder = 'attachment';
                    if (!is_dir($folder)) {
                        mkdir($folder);
                    }
                    if (file_exists('./'.$folder.'/'.$filename)) {
                        $filename = 'c-'.$filename;
                    }
                    $fp = fopen('./'.$folder.'/'.$filename, 'w+');
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                    chmod('./'.$folder.'/'.$filename, 0666);
                }
            }
        }
    }

    /* close the connection */
    imap_close($inbox);

    return true;
}

// ESTE BLOQUE TRABAJA CON SCADA
function get_mail_body()
{
    set_time_limit(3000);

    /* connect to gmail with your credentials */
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'prjchec.indisponibilidades_circuito@umanizales.edu.co';
    $password = 'umCHEC1234_761349';

    /* try to connect */
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: '.imap_last_error());

    //$emails = imap_search($inbox, 'FROM "notificacionsgo@chec.com.co" SEEN');
    $emails = imap_search($inbox, 'FROM "notificacionsgo@chec.com.co" UNSEEN');

    /* if any emails found, iterate through each email */
    if ($emails) {
        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        include './lib.php';
        //Instancia de la API
        $api = new chatBotApi();

        //for every email...
        foreach ($emails as $email_number) {
            $ids[] = $email_number;

            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);

            $message = imap_fetchbody($inbox, $email_number, 2);

            $content = imap_headerinfo($inbox, $email_number);

            if (strpos($content->subject, 'Fwd:')) {
                $inicioCadena = '201';
                $pos = strpos($content->subject, $inicioCadena);
                $return = substr($content->subject, $pos);
            } else {
                $return = $content->subject;
            }
            saveIndispCircuito($return, $api);
        }

        imap_setflag_full($inbox, implode(',', $ids), '\\Seen');
    }

    /* close the connection */
    imap_close($inbox);

    return true;
}

//Función para consultar mail y disparar correos electronicos y SMS cuando llegan nuevos correos
function get_mail_body_response_mail_sms()
{
    set_time_limit(3000);

    /* connect to gmail with your credentials */
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'prjchec.egomez@umanizales.edu.co';
    $password = 'conclave01126';

    /* try to connect */
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: '.imap_last_error());

    //$emails = imap_search($inbox, 'FROM "notificacionsgo@chec.com.co" SEEN');
    $emails = imap_search($inbox, 'FROM "edwingomezh@gmail.com" UNSEEN');

    /* if any emails found, iterate through each email */
    if ($emails) {
        $count = 1;

        include './lib.php';
        //Instancia de la API
        $api = new chatBotApi();

        /* put the newest emails on top */
        rsort($emails);

        //for every email...
        foreach ($emails as $email_number) {
            $ids[] = $email_number;

            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);

            $message = imap_fetchbody($inbox, $email_number, 1);

            $content = imap_headerinfo($inbox, $email_number);

            $return = $content->subject;

            sendIndisponibilidades($return, $message, $api);
        }

        imap_setflag_full($inbox, implode(',', $ids), '\\Seen');
    }

    /* close the connection */
    imap_close($inbox);

    return true;
}

//FUNCION PARA REGISTRAR LA CANTIDAD DE VECES QUE SE HACE DIFUSIÓN DE CORREO O SMS
function log_difusion()
{
    include './lib.php';
    //Instancia de la API
    $api = new chatBotApi();

    $api->get_nodos_from_email($global);
}

//ESTE BLOQUE TRABAJA CON SUSPENSIONES EFECTIVAS
function get_attachments_efectivas()
{
    set_time_limit(3000);

    /* connect to gmail with your credentials */
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'prjchec.suspensiones_efectivas@umanizales.edu.co';
    $password = 'umCHEC1234';

    /* try to connect */
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: '.imap_last_error());

    //$emails = imap_search($inbox, 'FROM "JHON.CALDERON@chec.com.co" UNSEEN');

    $emails = imap_search($inbox, 'FROM "MARIA.ISABEL.JIMENEZ@chec.com.co" UNSEEN');

    /* if any emails found, iterate through each email */
    if ($emails) {
        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        /* for every email... */
        foreach ($emails as $email_number) {
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);

            $message = imap_fetchbody($inbox, $email_number, 2);

            /* get mail structure */
            $structure = imap_fetchstructure($inbox, $email_number);

            $attachments = array();

            /* if any attachments found... */
            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); ++$i) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => '',
                    );

                    if ($structure->parts[$i]->ifdparameters) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }

                    if ($structure->parts[$i]->ifparameters) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }

                    if ($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                        /* 3 = BASE64 encoding */
                        if ($structure->parts[$i]->encoding == 3) {
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        }
                        /* 4 = QUOTED-PRINTABLE encoding */
                        elseif ($structure->parts[$i]->encoding == 4) {
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }

            /* iterate through each attachment and save it */
            foreach ($attachments as $attachment) {
                if ($attachment['is_attachment'] == 1) {
                    if (empty($filename)) {
                        $filename = $attachment['filename'];
                    }

                    if (empty($filename)) {
                        $filename = time().'.dat';
                    }

                    //FOLDER DE SUSPENSIONES EFECTIVAS
                    $folder = 'attachment_efectivas';
                    if (!is_dir($folder)) {
                        mkdir($folder);
                    }
                    if (file_exists('./'.$folder.'/'.$filename)) {
                        $filename = 'c-'.$filename;
                    }
                    $fp = fopen('./'.$folder.'/'.$filename, 'w+');
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                    chmod('./'.$folder.'/'.$filename, 0666);
                }
            }
        }
    }

    /* close the connection */
    imap_close($inbox);

    return true;
}

function saveIndispCircuito($global, $api)
{
    $fecha = '';
    $time = '';
    $condition = '';
    $cod_circuit = '';
    $suscribers = '';

    $data = $api->getIndisponibilidadCircuitoData($global);

    $response = $api->setIndispCircuito($data);
}
//Función para enviar correos y sms a usuarios por indisponibilidad de nodo
function sendIndisponibilidades($global, $message, $api)
{
    $data = $api->get_nodos_from_email($global);
    $response = $api->get_Mail_Sms_Nodo($data);
    $emails = '';
    $phones = array();
    $dateLog[] = array();

    if (count($response) > 0) {
        foreach ($response as $key => $value) {
            $emails .= $value->EMAIIL_VC.',';

            array_push($phones, $value->CELULAR_VC);
        }
        /* $dateLog['FECHA'] = $data['FECHA'];
        $dateLog['HORA'] = $data['HORA'];
        $dateLog['NODO'] = $data['NODO'];
        $dateLog['MOTIVO'] =  */
        $data['MOTIVO'] = $message;

        if ($emails != '') {
            include './sendEmail.php';
            $apiMail = new sendEmailAPI();
            $responseEmail = $apiMail->MailSend($emails, $data);
            if ($responseEmail == 'Ok') {
                $data['CANAL'] = 'EMAIL';
                $api->insertLog_Difusion($data);
                echo 'Message successfully sent!';
            } else {
                echo $responseEmail;
            }
        }
        if (count($phones) > 0) {
            include './sendSMS.php';
            $apiPhone = new sendPhoneAPI();
            $responsePhone = $apiPhone->Send($phones, $data);
            $data['CANAL'] = 'SMS';
            $api->insertLog_Difusion($data);
            echo $responsePhone;
        }
    }
}

function loadReportsIndis()
{
    //FOLDER DE reportes
    $folder = 'attachmentReports';
    if (!is_dir($folder)) {
        mkdir($folder);
    }

    if (copy("./$folder/planilla.xlsx", "./$folder/ARCHIVO_CONTINGENCIA.xls")) {
        chmod("./$folder", 0777);

        return true;
    }

    return false;
}

function loadReportsTexts()
{
    //FOLDER DE reportes
    $folder = 'attachmentReports';
    if (!is_dir($folder)) {
        mkdir($folder);
    }

    if (copy("./$folder/planilla_textos.xlsx", "./$folder/ARCHIVO_TEXTOS.xls")) {
        //chmod("./$folder", 0777);

        return true;
    }

    return false;
}

function loadReportsTexts2()
{
    //FOLDER DE reportes
    $folder = 'attachmentReports';
    if (!is_dir($folder)) {
        mkdir($folder);
    }

    if (copy("./$folder/planilla_textos_2.xlsx", "./$folder/ARCHIVO_TEXTOS_2.xls")) {
        //chmod("./$folder", 0777);

        return true;
    }

    return false;
}

