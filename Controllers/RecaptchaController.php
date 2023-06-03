<?php

namespace Controllers;

use Helpers\RespuestaHelper;

class RecaptchaController
{
    function verificar(string $token)
    {
        $contexto = stream_context_create([
            'http' => [
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query([
                    'secret' => RECAPTCHA_SECRET,
                    'response' => $token
                ])
            ]
        ]);

        $res = file_get_contents(RECAPTCHA_API, false, $contexto);

        if ($res === FALSE) {
            RespuestaHelper::enviarRespuesta(500);
        }
        $res = json_decode($res);
        RespuestaHelper::enviarRespuesta(200, $res->success);
    }
}
