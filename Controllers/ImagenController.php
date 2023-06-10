<?php

namespace Controllers;

use Helpers\RespuestaHelper;
use Services\SesionService;

class ImagenController
{
    function subir(array $archivo)
    {
        $sesion = new SesionService();
        if ($sesion->haySesion()) {
            $contenido = file_get_contents($archivo['tmp_name']);
            RespuestaHelper::enviarRespuesta(200, $contenido);
        } else {
            RespuestaHelper::enviarRespuesta(401);
        }
    }
}