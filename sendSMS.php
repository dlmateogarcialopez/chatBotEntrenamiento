<?php

class sendPhoneAPI
{
    public function __construct()
    {

    }

    public function Send($phone, $data)
    {
        $body = "";

        if ($data['TIPO'] == "APERTURA") {
            //$post['message'] = "CHEC informa que conoce la ausencia de energía en tu sector, estamos trabajando para restablecer el servicio, Si quieres mas información responde SICHEC";
            $post['message'] = "CHEC informa: Conocemos la ausencia del servicio de energia en tu sector, estamos trabajando para restablecer el servicio";
        } else if ($data['TIPO'] == "CIERRE") {
            $post['message'] = "CHEC informa: Se restablecio el servicio de energia en en tu sector, por favor si tu servicio se restablecio responda SICHEC de lo contrario responda NOCHEC";
        }

        $post['to'] = $phone;
        $post['from'] = "FROM CHEC";
        $post["campaignName"] = "Pruebas Envios SMS CHEC";

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
            var_dump($result);
        } catch (Exception $th) {
            var_dump​($th->getTraceAsString());
        }

    }
}
