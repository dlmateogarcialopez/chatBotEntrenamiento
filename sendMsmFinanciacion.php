<?php
define("SMS_API_KEY", "J274bMdKCjx/J3JkHdglXGOjs3NpnMz5k3EfjuQCyZw=");

class sendFinanciacionMsm
{
    public function __construct()
    {

    }

    public function sendMsm($phone, $code, $coduser, $niu, $documento)
    {
        $celular = '57'.$phone;
  
        $datosJson = [
            "api_key" => SMS_API_KEY,
            "data" => [
                "messages" => [
                    [
                        "correlative" => 1,
                        "phone" => $phone,
                        "message" => "CHEC informa: Tu codigo de verificacion es:$code"
                    ]
                ],
                "smslargo" => 1,
                "hour" => date("H:i:s"),
                "delivery_date" => date("d/m/Y"),
                "delivery_type" => 0,
                "user" => ["user_id" => 14817]
            ]
        ];

        try {
            $curl = curl_init();
            $url = "https://sms.intico.com.co:3312/api/sms_bulk";
            curl_setopt_array($curl, $this->setCurlOptionsSmsIntcobranza($datosJson, $url));
            $response = curl_exec($curl);
            curl_close($curl);
            $resultado = json_decode($response);           
            $resultado->texto = "Te acabo de enviar un código de verificación al celular: +$celular, por favor ingresalo para completar la financiación";
            return $resultado;
        } catch (\Throwable $th) {
            return null;
        }

    }

    public function setCurlOptionsSmsIntcobranza($requestBody, $url)
    {
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($requestBody),
            CURLOPT_HTTPHEADER => [
                "api_key: y5tFdsSDF3$78dftttpokk88374fWwerWr",
                "session_key: FGW3xrX0YYvnCOPZM1n2liC",
                "Content-Type: application/json",
                "Accept: application/json"
            ]
        ];
    }
}
