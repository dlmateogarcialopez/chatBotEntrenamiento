<?php

class sendFinanciacionMsm
{
    public function __construct()
    {

    }

    public function sendMsm($phone, $code, $coduser, $niu, $documento)
    {
        $body = "";

        $celular = '57'.$phone;
        
        $post['message'] = "CHEC informa: Tu codigo de verificacion es:$code";

        $post['to'] = array($celular);
        $post['from'] = "FROM CHEC";
        $post["campaignName"] = "Financiación Chec";        
        $post['notificationUrl'] = "https://chatbotchecserver.com/chatbotCHECUsuarios/acuse_recibo_financiacion.php?idenvio=umanizales12345&estado=%d&fecha=%t&telefono=$phone&coduser=$coduser&niu=$niu&documento=$documento";
        //$post['notificationUrl'] = "acuse_recibo_financiacion.php?idenvio=umanizales12345&estado=%d&fecha=%t&telefono=$phone&coduser=$coduser&niu=$niu&documento=$documento";
        
        $user = "umanizales360";
        $password = 'Umanizl8';
        /* $user = "umanizales";
        $password = 'Umaniza8'; */
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://dashboard.360nrs.com/api/rest/sms");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Authorization: Basic " . base64_encode($user . ":" . $password),
            ));
            $result = curl_exec($ch);
            $resultado = json_decode($result);
            $resultado->texto = "Te acabo de enviar un código de verificación al celular: +$celular, por favor ingresalo para completar la financiación";
            return $resultado;
        } catch (Exception $th) {
            var_dump​($th->getTraceAsString());
        }

    }
}
