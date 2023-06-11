<?php

namespace Controllers;

use Helpers\RespuestaHelper;

/**
 * Clase controladora que gestiona todo lo relacionado con el catpcha.
 */
class RecaptchaController
{
    /**
     * Verifica un token con el secreto del catpcha para comprobar que es válido y que no es un robot.
     *
     * @param string $token
     * @return void
     */
    function verificar(string $token)
    {
        // Configuramos la petición a lanzar. 
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

        // Enviamos una petición a la comprobación del captcha.
        $res = file_get_contents(RECAPTCHA_API, false, $contexto);

        // Si la petición ha ido mal, enviar error de servidor.
        if ($res === FALSE) {
            RespuestaHelper::enviarRespuesta(500);
        }

        // Convertir respuesta a JSON y devolver el resultado de la comprobación.
        $res = json_decode($res);
        RespuestaHelper::enviarRespuesta(200, $res->success);
    }
}
