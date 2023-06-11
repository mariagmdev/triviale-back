<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Services\SesionService;

class ImagenController
{
    /**
     * Devuelve el contenido blob de una imagen subida al servidor.
     *
     * @param array $archivo datos del archivo subido
     * @return void
     */
    function subir(array $archivo)
    {
        $sesion = new SesionService();
        // Comprobamos que el usuario tiene la sesión abierta.
        if ($sesion->haySesion()) {
            // Obtenemos el contenido del archivo subido y devolvemos el blob.
            $contenido = file_get_contents($archivo['tmp_name']);

            RespuestaHelper::enviarRespuesta(200, $contenido);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }
}
