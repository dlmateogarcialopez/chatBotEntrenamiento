<?php

error_reporting(-1);
ini_set('display_errors', 'On');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/vendor/autoload.php';

//require_once '../../../lib/php/Mail-1.4.1/Mail.php';
//require_once '../../../lib/php/Mail-1.4.1/Mail/mime.php';
// require_once "../../php/Mail-1.4.1/Mail.php";
// require_once "../../php/Mail-1.4.1/Mail/mime.php";


class sendEmailAPI
{
    public function __construct()
    {
    }

    public function MailSend($mail, $data)
    {
        $body = '';

        if ($data['TIPO'] == 'APERTURA') {
            $body = 'CHEC informa que conoce la ausencia del servicio de energía en tu sector, estamos trabajando para restablecer el servicio';
        } elseif ($data['TIPO'] == 'CIERRE') {
            $body = 'CHEC Informa: La falla en el servicio de energía ha sido solucionada.';
        }
        $from = 'Um CHEC <prjchec.egomez@umanizales.edu.co>';
        $to = $mail;
        $subject = 'CHEC - Falla en el servicio de Energía';

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
        );
        $smtp = Mail::factory(
            'smtp',
            array(
                'host' => 'ssl://smtp.gmail.com',
                'port' => '465',
                'auth' => true,
                'username' => 'prjchec.egomez@umanizales.edu.co',
                'password' => 'DiosmiPastor93',
            )
        );

        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
            return $mail->getMessage();
            //echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            return 'Ok';
            //echo ("<p>Message successfully sent!</p>");
        }
    }

    //ENVIO DE ERROR CUANDO SE CAE LA VPN CON EL SGO
    /*public function errorSGO($message)
    {
        $from = 'Um CHEC <prjchec.egomez@umanizales.edu.co>';
        // $to = "<dl.mgarcia@umanizales.edu.co>, <prjchec.egomez@umanizales.edu.co>";
        $to = '<prjchec.egomez@umanizales.edu.co>';
        $subject = 'WSCB - AVISO INTERRUPCIÓN EN LA CONEXIÓN VPN CHATBOT';
        $body = $message;

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
        );
        $smtp = Mail::factory(
            'smtp',
            array(
                'host' => 'ssl://smtp.gmail.com',
                'port' => '465',
                'auth' => true,
                'username' => 'prjchec.egomez@umanizales.edu.co',
                'password' => 'C0nclave01126',
            )
        );

        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
            return $mail->getMessage();
            //echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            return 'Ok';
            //echo ("<p>Message successfully sent!</p>");
        }
    }*/

    /*public function sendFileReports($attachment)
    {
        try {
            //<prjchec.jcardona@umanizales.edu.co>,
            $from = 'Um CHEC <prjchec.egomez@umanizales.edu.co>';
            // $from = "Um CHEC <prjchec.egomez@umanizales.edu.co>";
  	    //$to = '<dl.mgarcia@umanizales.edu.co>';
	    //$to = '<prjchec.egomez@umanizales.edu.co>, <servicio@chec.com.co>, <MONICA.MARIN@chec.com.co>, <GrupoSupervisoresEPM@epmcc-pob.com>, <GrupoChec@EPMCC-POB.com>, <dl.mgarcia@umanizales.edu.co>';
            $to = '<prjchec.egomez@umanizales.edu.co>, <MONICA.MARIN@chec.com.co>, <requerimientoschec@outsourcing.com.co>, <dl.mgarcia@umanizales.edu.co>';
            // $to = '<prjchec.egomez@umanizales.edu.co>';
            $subject = 'Reporte de Interrupciones SGO ChatBot';
            $headers = array(
                'From' => $from,
                'To' => $to,
                'Subject' => $subject,
            );
            $mime = new Mail_mime();

            $text = 'Archivo Contingencia para la carga de reportes en el sgo.';
            $html = '<html><body>Archivo Contingencia para la carga de reportes en el sgo.</body></html>';

            $mime->setTXTBody($text);
            $mime->setHTMLBody($html);

            $mime->addAttachment($attachment, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $attachment);

            $body = $mime->get();
            $hdrs = $mime->headers($headers);
            $smtp = Mail::factory(
                'smtp',
                array(
                    'host' => 'ssl://smtp.gmail.com',
                    'port' => '465',
                    'auth' => true,
                    'username' => 'prjchec.egomez@umanizales.edu.co',
                    'password' => 'C0nclave01126',
                )
            );

            $mail = $smtp->send($to, $hdrs, $body);

            if (PEAR::isError($mail)) {
                return $mail->getMessage();
                //echo ("<p>" . $mail->getMessage() . "</p>");
            } else {
                return 'Ok';
                //echo ("<p>Message successfully sent!</p>");
            }

            return 'Ok';
        } catch (exception $e) {
            var_dump($e);
        }
    }*/

    //ERROR AL ENVIAR ARCHIVO DE REPORTES
    /*public function errorReporte($message)
    {
        $from = 'Um CHEC <prjchec.egomez@umanizales.edu.co>';
        $to = '<prjchec.egomez@umanizales.edu.co>, <dl.mgarcia@umanizales.edu.co>';
        //$to = "<prjchec.egomez@umanizales.edu.co>";
        $subject = 'ERROR AL ENVIAR REPORTES DE FALLA DE ENERGÍA';
        $body = $message;

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
        );
        $smtp = Mail::factory(
            'smtp',
            array(
                'host' => 'ssl://smtp.gmail.com',
                'port' => '465',
                'auth' => true,
                'username' => 'prjchec.egomez@umanizales.edu.co',
                'password' => 'C0nclave01126',
            )
        );

        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
            return $mail->getMessage();
            //echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            return 'Ok';
            //echo ("<p>Message successfully sent!</p>");
        }
    }*/

    //ERROR AL ENVIAR ARCHIVO DE TEXTOS
    /*public function errorReporteTexto($message)
    {
        $from = 'Um CHEC <prjchec.egomez@umanizales.edu.co>';
        $to = '<dl.mgarcia@umanizales.edu.co>';
        //$to = "<prjchec.egomez@umanizales.edu.co>";
        $subject = 'ERROR AL ENVIAR REPORTES DE TEXTOS';
        $body = $message;

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject,
        );
        $smtp = Mail::factory(
            'smtp',
            array(
                'host' => 'ssl://smtp.gmail.com',
                'port' => '465',
                'auth' => true,
                'username' => 'prjchec.egomez@umanizales.edu.co',
                'password' => 'C0nclave01126',
            )
        );

        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
            return $mail->getMessage();
            //echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            return 'Ok';
            //echo ("<p>Message successfully sent!</p>");
        }
    }*/

    /*public function sendFileReportsText($attachment)
    {
        try {
            //<prjchec.jcardona@umanizales.edu.co>,
            $from = 'Um CHEC <prjchec.egomez@umanizales.edu.co>';
            // $from = "Um CHEC <prjchec.egomez@umanizales.edu.co>";
            $to = '<JUAN.CARDONA.GUTIERREZ@chec.com.co>, <dl.mgarcia@umanizales.edu.co>, <ana.devia@chec.com.co>, <juan.gomez.maya@chec.com.co>';

            //$to = '<dl.mgarcia@umanizales.edu.co>, <JUAN.CARDONA.GUTIERREZ@chec.com.co >';
            // $to = '<prjchec.egomez@umanizales.edu.co>';
            $subject = 'Reporte de Textos ChatBot de WhatsApp y Telegram';
            $headers = array(
                'From' => $from,
                'To' => $to,
                'Subject' => $subject,
            );
            $mime = new Mail_mime();

            $text = 'Archivo de textos.';
            $html = '<html><body>Archivo de textos.</body></html>';

            $mime->setTXTBody($text);
            $mime->setHTMLBody($html);

            $mime->addAttachment($attachment, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $attachment);

            $body = $mime->get();
            $hdrs = $mime->headers($headers);
            $smtp = Mail::factory(
                'smtp',
                array(
                    'host' => 'ssl://smtp.gmail.com',
                    'port' => '465',
                    'auth' => true,
                    'username' => 'prjchec.egomez@umanizales.edu.co',
                    'password' => 'C0nclave01126',
                )
            );

            $mail = $smtp->send($to, $hdrs, $body);

            if (PEAR::isError($mail)) {
                return $mail->getMessage();
                //echo ("<p>" . $mail->getMessage() . "</p>");
            } else {
                return 'Ok';
                //echo ("<p>Message successfully sent!</p>");
            }

            return 'Ok';
        } catch (exception $e) {
            var_dump('e', $e);
        }
    }*/

    //CORREOS ENVIADOS DESDE LA LIBRERÍA PHPMAILER

    public function sendFileReportsText($attachment)
    {
        $mail = new PHPMailer(true);
        $smtpAuth = true; // Habilita autenticación SMTP
        $host = "mail.epm.com.co"; // Servidor SMTP por el cual enviar
        $username = "CHEC\\infocomercial"; // Nombre de usuario SMTP
        $password = '1yX8EwuICjVZ'; // Contraseña SMTP
        $port = 465; // Puerto TCP al cual conectarse; usar 465 para `PHPMailer::ENCRYPTION_SMTPS`
        $charSet = "UTF-8";
        $address = "infocomercial@chec.com.co"; // Dirección del correo
        $addressName = "Sistema automático";
        $isHtml = true;
        $domain = "https://intrachecdes.chec.com.co/sgcb";
        //$smtpSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` recomendada

        try {
            $mail->SMTPAuth = $smtpAuth;
            // $mail->SMTPSecure = $this->smtpSecure;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = $charSet;
            $mail->isSMTP();
            $mail->setFrom($address, $addressName);
            $mail->isHTML($isHtml);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];
            $mail->addAddress("dl.mgarcia@umanizales.edu.co");
            //$mail->addAddress("prjchec.egomez@umanizales.edu.co");
            //$mail->addAddress("JUAN.CARDONA.GUTIERREZ@chec.com.co");
            //$mail->addAddress("ana.devia@chec.com.co");
            //$mail->addAddress("juan.gomez.maya@chec.com.co");
            //$mail->addAddress("LYDA.GONZALEZ@chec.com.co");


            /* Contenido */
            $mail->Subject = 'Reporte de Textos ChatBot de WhatsApp y Telegram';
            $mail->Body = 'Archivo de textos.';
            $mail->addAttachment($attachment);

            $mail->send();
            return 'Ok';
        } catch (Exception $e) {
            // echo "Mailer Error: {$mail->ErrorInfo}";
            var_dump('e', $e);
            return "El mensaje no pudo ser enviado. Por favor, inténtalo de nuevo.";
        }
    }

    public function sendFileReports($attachment)
    {
        $smtpAuth = true; // Habilita autenticación SMTP
        $host = "mail.epm.com.co"; // Servidor SMTP por el cual enviar
        $username = "CHEC\\infocomercial"; // Nombre de usuario SMTP
        $password = '1yX8EwuICjVZ'; // Contraseña SMTP
        $port = 465; // Puerto TCP al cual conectarse; usar 465 para `PHPMailer::ENCRYPTION_SMTPS`
        $charSet = "UTF-8";
        $address = "infocomercial@chec.com.co"; // Dirección del correo
        $addressName = "Sistema automático";
        $isHtml = true;
        $domain = "https://intrachecdes.chec.com.co/sgcb";
        $smtpSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` recomendada
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPAuth = $smtpAuth;
            // $mail->SMTPSecure = $this->smtpSecure;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = $charSet;
            $mail->isSMTP();
            $mail->setFrom($address, $addressName);
            $mail->isHTML($isHtml);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];
            $mail->addAddress("dl.mgarcia@umanizales.edu.co");
            $mail->addAddress("prjchec.egomez@umanizales.edu.co");
            $mail->addAddress("MONICA.MARIN@chec.com.co");
            $mail->addAddress("jdlopezh@outsourcing.com.co");
            $mail->addAddress("meariasc@outsourcing.com.co");
            $mail->addAddress("requerimientoschec@outsourcing.com.co");

            //Contenido
            $mail->Subject = 'Reporte de Interrupciones SGO ChatBot';
            $mail->Body = 'Archivo Contingencia para la carga de reportes en el sgo.';
            $mail->addAttachment($attachment);

            $mail->send();
            return "Ok";
        } catch (Exception $e) {
            // echo "Mailer Error: {$mail->ErrorInfo}";
            var_dump($e);
            return "El mensaje no pudo ser enviado. Por favor, inténtalo de nuevo.";
        }
    }

    //ERROR AL ENVIAR ARCHIVO DE TEXTOS
    public function errorReporteTexto($message)
    {
        $smtpAuth = true; // Habilita autenticación SMTP
        $host = "mail.epm.com.co"; // Servidor SMTP por el cual enviar
        $username = "CHEC\\infocomercial"; // Nombre de usuario SMTP
        $password = '1yX8EwuICjVZ'; // Contraseña SMTP
        $port = 465; // Puerto TCP al cual conectarse; usar 465 para `PHPMailer::ENCRYPTION_SMTPS`
        $charSet = "UTF-8";
        $address = "infocomercial@chec.com.co"; // Dirección del correo
        $addressName = "Sistema automático";
        $isHtml = true;
        $domain = "https://intrachecdes.chec.com.co/sgcb";
        $smtpSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` recomendada
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPAuth = $smtpAuth;
            // $mail->SMTPSecure = $this->smtpSecure;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = $charSet;
            $mail->isSMTP();
            $mail->setFrom($address, $addressName);
            $mail->isHTML($isHtml);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];
            $mail->addAddress("dl.mgarcia@umanizales.edu.co");
            $mail->addAddress("prjchec.egomez@umanizales.edu.co");

            //Contenido
            $mail->Subject = 'ERROR AL ENVIAR REPORTES DE TEXTOS';
            $mail->Body = $message;

            $mail->send();
            return "Ok";
        } catch (Exception $e) {
            // echo "Mailer Error: {$mail->ErrorInfo}";
            var_dump($e);
            return "El mensaje no pudo ser enviado. Por favor, inténtalo de nuevo.";
        }
    }

    //ERROR AL ENVIAR ARCHIVO DE REPORTES
    public function errorReporte($message)
    {
        $smtpAuth = true; // Habilita autenticación SMTP
        $host = "mail.epm.com.co"; // Servidor SMTP por el cual enviar
        $username = "CHEC\\infocomercial"; // Nombre de usuario SMTP
        $password = '1yX8EwuICjVZ'; // Contraseña SMTP
        $port = 465; // Puerto TCP al cual conectarse; usar 465 para `PHPMailer::ENCRYPTION_SMTPS`
        $charSet = "UTF-8";
        $address = "infocomercial@chec.com.co"; // Dirección del correo
        $addressName = "Sistema automático";
        $isHtml = true;
        $domain = "https://intrachecdes.chec.com.co/sgcb";
        $smtpSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` recomendada
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPAuth = $smtpAuth;
            // $mail->SMTPSecure = $this->smtpSecure;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = $charSet;
            $mail->isSMTP();
            $mail->setFrom($address, $addressName);
            $mail->isHTML($isHtml);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];
            $mail->addAddress("dl.mgarcia@umanizales.edu.co");
            $mail->addAddress("prjchec.egomez@umanizales.edu.co");

            //Contenido
            $mail->Subject = 'ERROR AL ENVIAR REPORTES DE FALLA DE ENERGÍA';
            $mail->Body = $message;

            $mail->send();
            return "Ok";
        } catch (Exception $e) {
            // echo "Mailer Error: {$mail->ErrorInfo}";
            var_dump($e);
            return "El mensaje no pudo ser enviado. Por favor, inténtalo de nuevo.";
        }
    }

    //ENVIO DE ERROR CUANDO SE CAE LA VPN CON EL SGO
    public function errorSGO($message)
    {
        $smtpAuth = true; // Habilita autenticación SMTP
        $host = "mail.epm.com.co"; // Servidor SMTP por el cual enviar
        $username = "CHEC\\infocomercial"; // Nombre de usuario SMTP
        $password = '1yX8EwuICjVZ'; // Contraseña SMTP
        $port = 465; // Puerto TCP al cual conectarse; usar 465 para `PHPMailer::ENCRYPTION_SMTPS`
        $charSet = "UTF-8";
        $address = "infocomercial@chec.com.co"; // Dirección del correo
        $addressName = "Sistema automático";
        $isHtml = true;
        $domain = "https://intrachecdes.chec.com.co/sgcb";
        $smtpSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` recomendada
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPAuth = $smtpAuth;
            // $mail->SMTPSecure = $this->smtpSecure;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = $charSet;
            $mail->isSMTP();
            $mail->setFrom($address, $addressName);
            $mail->isHTML($isHtml);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];
            $mail->addAddress("dl.mgarcia@umanizales.edu.co");
            $mail->addAddress("prjchec.egomez@umanizales.edu.co");

            //Contenido
            $mail->Subject = 'WSCB - AVISO INTERRUPCIÓN EN LA CONEXIÓN VPN CHATBOT';
            $mail->Body = $message;

            $mail->send();
            return "Ok";
        } catch (Exception $e) {
            // echo "Mailer Error: {$mail->ErrorInfo}";
            var_dump($e);
            return "El mensaje no pudo ser enviado. Por favor, inténtalo de nuevo.";
        }
    }

    public function sendErrorFinancing($error)
    {
        $mail = new PHPMailer(true);
        $smtpAuth = true; // Habilita autenticación SMTP
        $host = "mail.epm.com.co"; // Servidor SMTP por el cual enviar
        $username = "CHEC\\infocomercial"; // Nombre de usuario SMTP
        $password = '1yX8EwuICjVZ'; // Contraseña SMTP
        $port = 465; // Puerto TCP al cual conectarse; usar 465 para `PHPMailer::ENCRYPTION_SMTPS`
        $charSet = "UTF-8";
        $address = "infocomercial@chec.com.co"; // Dirección del correo
        $addressName = "Sistema automático";
        $isHtml = true;
        $domain = "https://intrachecdes.chec.com.co/sgcb";
        //$smtpSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita encriptación TLS; `PHPMailer::ENCRYPTION_SMTPS` recomendada

        try {
            $mail->SMTPAuth = $smtpAuth;
            // $mail->SMTPSecure = $this->smtpSecure;
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = $host;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Port = $port;
            $mail->CharSet = $charSet;
            $mail->isSMTP();
            $mail->setFrom($address, $addressName);
            $mail->isHTML($isHtml);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];
            $mail->addAddress("dl.mgarcia@umanizales.edu.co");


            /* Contenido */
            $mail->Subject = 'ERROR DE FINANCIACIÓN';
            $mail->Body = $error;
            //$mail->addAttachment($error);

            $mail->send();
            return 'Ok';
        } catch (Exception $e) {
            // echo "Mailer Error: {$mail->ErrorInfo}";
            var_dump('e', $e);
            return "El mensaje no pudo ser enviado. Por favor, inténtalo de nuevo.";
        }
    }
}
